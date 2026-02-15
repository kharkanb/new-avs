@extends('layouts.admin')

@section('title', 'ویرایش کاربر')

@section('content')
<div class="container">
    <h2 class="mb-4">ویرایش کاربر: {{ $user->name }}</h2>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label required">نام</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required">ایمیل</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">رمز عبور جدید (اختیاری)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">تکرار رمز عبور</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label required">نقش</label>
                        <select name="role" class="form-control" required>
                            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>کاربر عادی</option>
                            <option value="tech" {{ $user->role == 'tech' ? 'selected' : '' }}>کارشناس فنی</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>مدیر</option>
                        </select>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> بروزرسانی
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x"></i> انصراف
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection