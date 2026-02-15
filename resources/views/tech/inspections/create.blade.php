@extends('layouts.tech')

@section('title', 'ایجاد بازدید جدید')

@section('content')
<div class="container">
    <h2 class="mb-4"><i class="bi bi-plus-circle"></i> ایجاد بازدید جدید</h2>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('inspections.store') }}" method="POST" id="inspectionForm">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label required">تاریخ بازدید</label>
                        <input type="date" name="inspection_date" class="form-control @error('inspection_date') is-invalid @enderror" 
                               value="{{ old('inspection_date', date('Y-m-d')) }}" required>
                        @error('inspection_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required">زمان شروع</label>
                        <input type="time" name="daily_start_time" class="form-control @error('daily_start_time') is-invalid @enderror" 
                               value="{{ old('daily_start_time', '08:00') }}" required>
                        @error('daily_start_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required">زمان پایان</label>
                        <input type="time" name="daily_end_time" class="form-control @error('daily_end_time') is-invalid @enderror" 
                               value="{{ old('daily_end_time', '16:00') }}" required>
                        @error('daily_end_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label required">پیمانکار</label>
                        <input type="text" name="contractor" class="form-control @error('contractor') is-invalid @enderror" 
                               value="{{ old('contractor', 'سام سرمد کویر') }}" required>
                        @error('contractor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required">ضریب قرارداد</label>
                        <input type="number" step="0.01" name="contract_coefficient" class="form-control @error('contract_coefficient') is-invalid @enderror" 
                               value="{{ old('contract_coefficient', '2.35') }}" required>
                        @error('contract_coefficient')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">شماره قرارداد</label>
                        <input type="text" name="contract_number" class="form-control" value="{{ old('contract_number', '.../.../.../...') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">شماره واتساپ</label>
                        <input type="text" name="whatsapp_number" class="form-control" value="{{ old('whatsapp_number') }}">
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="mb-3"><i class="bi bi-hdd-stack"></i> انتخاب تجهیزات</h5>
                
                <div id="equipments-container">
                    <!-- تجهیزات اینجا اضافه می‌شوند -->
                </div>

                <div class="text-center my-3">
                    <button type="button" class="btn btn-success" onclick="addEquipment()">
                        <i class="bi bi-plus-circle"></i> افزودن تجهیز
                    </button>
                </div>

                <hr class="my-4">

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> ذخیره بازدید
                    </button>
                    <a href="{{ route('tech.inspections.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x"></i> انصراف
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let equipmentCount = 0;

function addEquipment() {
    equipmentCount++;
    const container = document.getElementById('equipments-container');
    
    const equipmentHtml = `
        <div class="card mb-3 equipment-card" id="equipment-${equipmentCount}">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="mb-0">تجهیز ${equipmentCount}</h6>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeEquipment(${equipmentCount})">
                    <i class="bi bi-trash"></i> حذف
                </button>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-4">
                        <label class="form-label required">نوع تجهیز</label>
                        <select name="equipments[${equipmentCount}][type]" class="form-control" required>
                            <option value="">انتخاب کنید</option>
                            <option value="ریکلوزر">ریکلوزر</option>
                            <option value="سکسیونر">سکسیونر</option>
                            <option value="سکشنالایزر">سکشنالایزر</option>
                            <option value="فالت دتکتور">فالت دتکتور</option>
                            <option value="پست دو سو تغذیه (مشترک حساس)">پست دو سو تغذیه (مشترک حساس)</option>
                            <option value="پست دو سو تغذیه (بیمارستانی)">پست دو سو تغذیه (بیمارستانی)</option>
                            <option value="مشترک ولتاژ اولیه">مشترک ولتاژ اولیه</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required">کد اسکادا</label>
                        <input type="text" name="equipments[${equipmentCount}][scada_code]" class="form-control" maxlength="4" pattern="[0-9]{4}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required">پست</label>
                        <select name="equipments[${equipmentCount}][post_id]" class="form-control" required>
                            <option value="">انتخاب کنید</option>
                            @foreach($posts ?? [] as $post)
                                <option value="{{ $post->id }}">{{ $post->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', equipmentHtml);
}

function removeEquipment(id) {
    document.getElementById(`equipment-${id}`).remove();
}

// اضافه کردن اولین تجهیز به صورت خودکار
addEquipment();
</script>
@endpush