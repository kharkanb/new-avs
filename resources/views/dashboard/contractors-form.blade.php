@extends('layouts.admin')

@section('title', isset($contractor) ? 'ویرایش پیمانکار' : 'افزودن پیمانکار جدید')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>{{ isset($contractor) ? 'ویرایش پیمانکار' : 'افزودن پیمانکار جدید' }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ isset($contractor) ? route('dashboard.contractors.update', $contractor) : route('dashboard.contractors.store') }}">
            @csrf
            @if(isset($contractor))
                @method('PUT')
            @endif

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label required">نام پیمانکار</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name', $contractor->name ?? '') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label required">ضریب قرارداد</label>
                    <input type="number" step="0.01" name="coefficient" class="form-control @error('coefficient') is-invalid @enderror" 
                           value="{{ old('coefficient', $contractor->coefficient ?? 2.35) }}" required>
                    @error('coefficient')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">شماره قرارداد</label>
                    <input type="text" name="contract_number" class="form-control @error('contract_number') is-invalid @enderror" 
                           value="{{ old('contract_number', $contractor->contract_number ?? '') }}">
                    @error('contract_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">شماره تلفن</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                           value="{{ old('phone', $contractor->phone ?? '') }}">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">شماره واتساپ</label>
                    <input type="text" name="whatsapp" class="form-control @error('whatsapp') is-invalid @enderror" 
                           value="{{ old('whatsapp', $contractor->whatsapp ?? '') }}">
                    <small class="text-muted">با کد کشور (مثال: 989123456789)</small>
                    @error('whatsapp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">آدرس</label>
                    <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3">{{ old('address', $contractor->address ?? '') }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="text-center mt-3">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save"></i> {{ isset($contractor) ? 'به‌روزرسانی' : 'ذخیره' }}
                </button>
                <a href="{{ route('dashboard.contractors') }}" class="btn btn-secondary px-4">
                    <i class="bi bi-x"></i> انصراف
                </a>
            </div>
        </form>
    </div>
</div>
@endsection