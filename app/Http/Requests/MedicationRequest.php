<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class MedicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check(); // فقط کاربران احراز هویت شده می‌توانند این درخواست را ارسال کنند
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:Tablet,Injection,Syrup,Drop',
            'dosage' => 'required|string',
            'interval_type' => 'required|string|in:Hourly,Daily,Weekly',
            'interval_value' => 'required|integer|min:1',
        ];
    }
}
