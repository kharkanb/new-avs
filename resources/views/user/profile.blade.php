@extends('layouts.user')

@section('title', 'پروفایل کاربری')

@section('content')
<div class="container">
    <h2 class="mb-4"><i class="bi bi-person-circle"></i> پروفایل کاربری</h2>

    <div class="row">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="bi bi-person-circle" style="font-size: 5rem;"></i>
                    <h4 class="mt-3">{{ auth()->user()->name }}</h4>
                    <p class="text-muted">{{ auth()->user()->email }}</p>
                    <p>
                        <span class="badge bg-{{ auth()->user()->role == 'admin' ? 'danger' : (auth()->user()->role == 'tech' ? 'success' : 'info') }}">
                            {{ auth()->user()->role }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>ویرایش اطلاعات</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label required">نام</label>
                            <input type="text" name="name" class="form-control" value="{{ auth()->user()->name }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label required">ایمیل</label>
                            <input type="email" name="email" class="form-control" value="{{ auth()->user()->email }}" required>
                        </div>
                        
                        <hr>
                        
                        <h6>تغییر رمز عبور (اختیاری)</h6>
                        
                        <div class="mb-3">
                            <label class="form-label">رمز عبور جدید</label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">تکرار رمز عبور</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> ذخیره تغییرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection