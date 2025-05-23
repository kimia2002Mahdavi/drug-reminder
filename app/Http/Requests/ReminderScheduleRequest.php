<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth; // از قبل وجود دارد، اما مطمئن می‌شویم

class ReminderScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // این خط دسترسی را بر اساس احراز هویت کاربر JWT چک می‌کند.
        // اگر کاربر احراز هویت شده باشد، اجازه می‌دهد.
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // نام فیلد به 'reminder_time' تغییر داده شد.
            // 'date_format:Y-m-d H:i:s' اضافه شد تا فرمت دقیق تاریخ و زمان بررسی شود.
            // 'after_or_equal:now' برای اطمینان از اینکه زمان یادآوری در آینده یا همین لحظه است.
            'reminder_time' => 'required|date_format:Y-m-d H:i:s|after_or_equal:now',

            // 'status' به 'nullable' تغییر یافت تا ارسال آن اختیاری باشد
            // و 'in:pending,sent,failed' برای اطمینان از مقادیر مجاز.
            'status' => 'nullable|string|in:pending,sent,failed',

            // 'message' به 'nullable' تغییر یافت تا ارسال آن اختیاری باشد
            // و 'max:255' برای محدود کردن طول پیام اضافه شد (اختیاری، اما خوب است).
            'message' => 'nullable|string|max:255',

            // 'user_id' و 'medication_id' را از اینجا حذف می‌کنیم،
            // چون همانطور که اشاره کردید، 'user_id' از Auth::id() در کنترلر می‌آید
            // و 'medication_id' از Route Model Binding (در آدرس URL) می‌آید و نیازی به validation اینجا ندارد.
            // 'user_id' => 'exists:users,id',
            // 'medication_id' => 'exists:medications,id',
        ];
    }
}