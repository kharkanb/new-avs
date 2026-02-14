<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMainEquipmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'inspection_id' => 'required|exists:inspections,id',
            'main_equipment_type_id' => 'required|exists:main_equipment_types,id',
            'scada_code' => 'nullable|string|size:4|unique:main_equipments',
            'post_id' => 'required|exists:posts,id',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'height' => 'nullable|integer|min:0',
            'installation_type' => 'nullable|string|max:50',
            'feeder_ids' => 'nullable|array',
            'feeder_ids.*' => 'exists:feeders,id'
        ];
    }

    public function messages(): array
    {
        return [
            'inspection_id.required' => 'شناسه بازرسی الزامی است',
            'inspection_id.exists' => 'بازرسی یافت نشد',
            'main_equipment_type_id.required' => 'نوع تجهیز الزامی است',
            'main_equipment_type_id.exists' => 'نوع تجهیز یافت نشد',
            'scada_code.size' => 'کد اسکادا باید ۴ کاراکتر باشد',
            'scada_code.unique' => 'کد اسکادا تکراری است',
            'post_id.required' => 'پست الزامی است',
            'post_id.exists' => 'پست یافت نشد',
            'latitude.between' => 'عرض جغرافیایی باید بین -90 و 90 باشد',
            'longitude.between' => 'طول جغرافیایی باید بین -180 و 180 باشد',
            'height.min' => 'ارتفاع نمی‌تواند منفی باشد',
        ];
    }
}