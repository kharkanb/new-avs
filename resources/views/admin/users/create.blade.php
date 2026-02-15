@extends('layouts.admin')

@section('title', 'ایجاد کاربر جدید')

@section('content')
<div class="container">
    <h2 class="mb-4">ایجاد کاربر جدید</h2>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label required">نام</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required">ایمیل</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label required">رمز عبور</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required">تکرار رمز عبور</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label required">نقش</label>
                        <select name="role" class="form-control" required>
                            <option value="user">کاربر عادی</option>
                            <option value="tech">کارشناس فنی</option>
                            <option value="admin">مدیر</option>
                        </select>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> ذخیره
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