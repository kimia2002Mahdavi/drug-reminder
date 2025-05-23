<?php

namespace App\Jobs;

use App\Mail\ReminderMail;
use App\Models\ReminderSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
  use Illuminate\Support\Facades\Mail;

class ProcessReminders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
        {
            // پیدا کردن یادآوری‌هایی که زمانشون گذشته و هنوز ارسال نشدن
            $reminders = ReminderSchedule::with('medication', 'user')
                                        ->where('reminder_time', '<=', now())
                                        ->where('status', 'pending') // فرض می‌کنیم یک فیلد status دارید
                                        ->get();

            foreach ($reminders as $reminder) {
                // ارسال ایمیل
                if ($reminder->user && $reminder->user->email) { // مطمئن شویم کاربر و ایمیل دارد
                    Mail::to($reminder->user->email)->send(new ReminderMail($reminder, $reminder->medication));
                }

                // به‌روزرسانی وضعیت یادآوری
                $reminder->status = 'sent';
                $reminder->save();
            }
        }
}