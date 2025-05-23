<?php

namespace App\Console\Commands;

use App\Models\Medication;
use App\Models\ReminderSchedule;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ScheduleReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:schedule-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Schedules medication reminders for users';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $medications = Medication::with('user')->get();

        foreach ($medications as $medication) {
            $this->scheduleMedicationReminders($medication);
        }

        $this->info('Medication reminders scheduled successfully.');
        Log::info('Medication reminders scheduled successfully.');
    }

    private function scheduleMedicationReminders(Medication $medication)
    {
        $now = Carbon::now();
        $intervalType = $medication->interval_type;
        $intervalValue = $medication->interval_value;

        // فرض می‌کنیم اولین یادآوری بلافاصله بعد از اضافه شدن دارو نیست
        // و ما فقط یادآوری‌های آینده رو زمانبندی می‌کنیم.
        // شما ممکنه منطق متفاوتی برای اولین یادآوری داشته باشید.

        $lastReminder = ReminderSchedule::where('medication_id', $medication->id)
            ->where('reminder_time', '>', $now)
            ->orderBy('reminder_time', 'desc')
            ->first();

        $nextReminderTime = null;

        if ($lastReminder) {
            $nextReminderTime = Carbon::parse($lastReminder->reminder_time);
            switch ($intervalType) {
                case 'روزانه':
                    $nextReminderTime->addDay();
                    break;
                case 'هر X روز':
                    $nextReminderTime->addDays($intervalValue);
                    break;
                case 'هر X ساعت':
                    $nextReminderTime->addHours($intervalValue);
                    break;
            }
        } else {
            // اگر هیچ یادآوری قبلی وجود نداره، زمان بعدی رو بر اساس زمان فعلی و بازه مصرف محاسبه می‌کنیم.
            $nextReminderTime = $now->copy();
            switch ($intervalType) {
                case 'روزانه':
                    $nextReminderTime->addDay();
                    break;
                case 'هر X روز':
                    $nextReminderTime->addDays($intervalValue);
                    break;
                case 'هر X ساعت':
                    $nextReminderTime->addHours($intervalValue);
                    break;
            }

            // برای جلوگیری از ایجاد تعداد زیادی یادآوری در صورت اجرای مکرر،
            // ممکنه بخواهید یک زمان شروع برای زمانبندی در نظر بگیرید.
            // برای مثال، فقط یادآوری‌ها برای 24 ساعت آینده رو زمانبندی کنید.
            if ($nextReminderTime->diffInHours($now) > 24) {
                return;
            }
        }

        if ($nextReminderTime && $nextReminderTime->isFuture()) {
            ReminderSchedule::create([
                'user_id' => $medication->user_id,
                'medication_id' => $medication->id,
                'reminder_time' => $nextReminderTime,
                'message' => "وقت مصرف داروی {$medication->name} به مقدار {$medication->dosage} فرا رسیده است.",
            ]);
        }
    }
}