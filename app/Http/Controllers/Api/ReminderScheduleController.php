<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReminderScheduleRequest; // فرض می‌کنیم ReminderScheduleRequest وجود دارد و درست تنظیم شده است
use App\Models\Medication;
use App\Models\ReminderSchedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ReminderScheduleController extends Controller
{
    /**
     * Store a newly created reminder schedule for the specified medication in storage.
     */
    public function store(ReminderScheduleRequest $request, Medication $medication): JsonResponse
    {
        // بررسی اینکه آیا داروی مورد نظر متعلق به کاربر احراز هویت شده هست یا نه
        if ($medication->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized. This medication does not belong to you.'], 403);
        }

        // دریافت ID کاربر احراز هویت شده
        $userId = Auth::id();

        // ایجاد زمان‌بندی یادآوری با اضافه کردن user_id
        // **مهم:** استفاده از 'reminder_time' به جای 'remind_at' برای مطابقت با نام ستون دیتابیس شما
        // **مهم:** استفاده از reminderSchedules() به جای reminders() برای مطابقت با نام متد رابطه در مدل Medication
        $reminderSchedule = $medication->reminderSchedules()->create(array_merge(
            $request->validated(),
            [
                'user_id' => $userId,
                // اگر در ReminderScheduleRequest 'remind_at' را به 'reminder_time' تغییر داده باشید، نیازی به این خط نیست
                // اگر هنوز در Request 'remind_at' است، این خط را فعال کنید:
                // 'reminder_time' => $request->input('remind_at'),
            ]
        ));

        return response()->json($reminderSchedule, 201);
    }

    /**
     * Display a listing of the reminder schedules for the specified medication.
     */
    public function index(Medication $medication): JsonResponse
    {
        // بررسی اینکه آیا داروی مورد نظر متعلق به کاربر احراز هویت شده هست یا نه
        if ($medication->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized. This medication does not belong to you.'], 403);
        }

        // دریافت تمام یادآوری‌ها برای داروی مشخص و کاربر احراز هویت شده
        // **مهم:** استفاده از reminderSchedules() به جای reminders() برای مطابقت با نام متد رابطه در مدل Medication
        $reminders = $medication->reminderSchedules()->where('user_id', Auth::id())->get();

        return response()->json([
            'medication_id' => $medication->id,
            'medication_name' => $medication->name,
            'reminders' => $reminders
        ], 200);
    }

    /**
     * Display the specified reminder schedule.
     */
    public function show(Medication $medication, ReminderSchedule $reminderSchedule): JsonResponse
    {
        // بررسی اینکه آیا یادآوری مربوط به داروی صحیح و کاربر صحیح است
        if ($medication->user_id !== Auth::id() || $reminderSchedule->medication_id !== $medication->id || $reminderSchedule->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized or reminder not found.'], 403);
        }

        return response()->json($reminderSchedule, 200);
    }

    // متدهای update و destroy (DELETE) برای تکمیل CRUD
    // اگر نیاز دارید، می‌توانید این‌ها را نیز اضافه کنید.
    // مطمئن شوید که Routeهای مربوطه را هم در api.php اضافه می‌کنید.

    /*
    public function update(ReminderScheduleRequest $request, Medication $medication, ReminderSchedule $reminderSchedule): JsonResponse
    {
        if ($medication->user_id !== Auth::id() || $reminderSchedule->medication_id !== $medication->id || $reminderSchedule->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized or reminder not found.'], 403);
        }

        // **مهم:** اطمینان حاصل کنید که Request دارای 'reminder_time' باشد اگر نام ستون در دیتابیس همین است
        $reminderSchedule->update($request->validated());

        return response()->json($reminderSchedule, 200);
    }
    */

    /*
    public function destroy(Medication $medication, ReminderSchedule $reminderSchedule): JsonResponse
    {
        if ($medication->user_id !== Auth::id() || $reminderSchedule->medication_id !== $medication->id || $reminderSchedule->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized or reminder not found.'], 403);
        }

        $reminderSchedule->delete();

        return response()->json(['message' => 'Reminder deleted successfully.'], 204);
    }
    */
}