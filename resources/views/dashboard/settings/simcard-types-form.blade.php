@extends('layouts.admin')

@section('title', isset($item) ? 'ویرایش نوع سیم‌کارت' : 'افزودن نوع سیم‌کارت جدید')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">{{ isset($item) ? 'ویرایش نوع سیم‌کارت' : 'افزودن نوع سیم‌کارت جدید' }}</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ isset($item) ? route('dashboard.simcard-types.update', $item) : route('dashboard.simcard-types.store') }}">
                @csrf
                @if(isset($item)) @method('PUT') @endif
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label required">نام نوع سیم‌کارت</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $item->name ?? '') }}" required autocomplete="off">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="code" class="form-label">کد</label>
                        <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" 
                               value="{{ old('code', $item->code ?? '') }}" autocomplete="off">
                        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <label for="description" class="form-label">توضیحات</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="3">{{ old('description', $item->description ?? '') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save"></i> ذخیره
                    </button>
                    <a href="{{ route('dashboard.simcard-types.index') }}" class="btn btn-secondary px-4">
                        <i class="bi bi-x-circle"></i> انصراف
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection