<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInspectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'inspection_date' => 'required|date',
            'daily_start_time' => 'nullable|date_format:H:i',
            'daily_end_time' => 'nullable|date_format:H:i|after:daily_start_time',
            'contractor' => 'nullable|string|max:255',
            'contract_coefficient' => 'nullable|numeric|min:0|max:999.99',
            'contract_number' => 'nullable|string|max:100',
            'whatsapp_number' => 'nullable|string|max:20',
            'status' => 'nullable|in:draft,completed,archived'
        ];
    }

    public function messages(): array
    {
        return [
            'inspection_date.required' => 'تاریخ بازرسی الزامی است',
            'inspection_date.date' => 'تاریخ بازرسی معتبر نیست',
            'daily_end_time.after' => 'زمان پایان باید بعد از زمان شروع باشد',
            'status.in' => 'وضعیت باید یکی از مقادیر draft, completed, archived باشد'
        ];
    }
}