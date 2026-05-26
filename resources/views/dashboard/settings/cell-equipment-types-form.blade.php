@extends('layouts.admin')

@section('title', isset($item) ? 'ویرایش نوع تجهیز سلولی' : 'افزودن نوع تجهیز سلولی جدید')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>{{ isset($item) ? 'ویرایش نوع تجهیز سلولی' : 'افزودن نوع تجهیز سلولی جدید' }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ isset($item) ? route('dashboard.cell-equipment-types.update', $item) : route('dashboard.cell-equipment-types.store') }}">
            @csrf
            @if(isset($item)) @method('PUT') @endif
            
            <div class="mb-3">
                <label for="name" class="form-label required">نام</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                       value="{{ old('name', $item->name ?? '') }}" required autocomplete="off">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            
            <div class="mb-3">
                <label for="code" class="form-label">کد</label>
                <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" 
                       value="{{ old('code', $item->code ?? '') }}" autocomplete="off">
                @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">توضیحات</label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                          rows="3">{{ old('description', $item->description ?? '') }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            
            <div class="text-center">
                <button type="submit" class="btn btn-primary px-4">ذخیره</button>
                <a href="{{ route('dashboard.cell-equipment-types.index') }}" class="btn btn-secondary px-4">انصراف</a>
            </div>
        </form>
    </div>
</div>
@endsection