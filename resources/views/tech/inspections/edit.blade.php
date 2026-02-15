@extends('layouts.tech')

@section('title', 'ویرایش بازدید')

@section('content')
<div class="container">
    <h2 class="mb-4"><i class="bi bi-pencil"></i> ویرایش بازدید</h2>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('inspections.update', $inspection->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label required">تاریخ بازدید</label>
                        <input type="date" name="inspection_date" class="form-control" value="{{ $inspection->inspection_date }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required">زمان شروع</label>
                        <input type="time" name="daily_start_time" class="form-control" value="{{ $inspection->daily_start_time }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required">زمان پایان</label>
                        <input type="time" name="daily_end_time" class="form-control" value="{{ $inspection->daily_end_time }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label required">پیمانکار</label>
                        <input type="text" name="contractor" class="form-control" value="{{ $inspection->contractor }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required">ضریب قرارداد</label>
                        <input type="number" step="0.01" name="contract_coefficient" class="form-control" value="{{ $inspection->contract_coefficient }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">شماره قرارداد</label>
                        <input type="text" name="contract_number" class="form-control" value="{{ $inspection->contract_number }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">شماره واتساپ</label>
                        <input type="text" name="whatsapp_number" class="form-control" value="{{ $inspection->whatsapp_number }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label required">وضعیت</label>
                        <select name="status" class="form-control" required>
                            <option value="draft" {{ $inspection->status == 'draft' ? 'selected' : '' }}>پیش‌نویس</option>
                            <option value="completed" {{ $inspection->status == 'completed' ? 'selected' : '' }}>تکمیل شده</option>
                            <option value="archived" {{ $inspection->status == 'archived' ? 'selected' : '' }}>بایگانی</option>
                        </select>
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="mb-3"><i class="bi bi-hdd-stack"></i> تجهیزات</h5>
                
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i>
                    برای ویرایش تجهیزات به صفحه تجهیزات بروید.
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> بروزرسانی
                    </button>
                    <a href="{{ route('tech.inspections.show', $inspection->id) }}" class="btn btn-secondary">
                        <i class="bi bi-x"></i> انصراف
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection