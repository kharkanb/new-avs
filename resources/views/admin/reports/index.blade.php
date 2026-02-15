@extends('layouts.admin')

@section('title', 'گزارش‌ها')

@section('content')
<div class="container">
    <h2 class="mb-4">گزارش‌های سیستم</h2>

    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-calendar-week" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">گزارش روزانه</h5>
                    <p>مشاهده خلاصه بازدیدهای روز</p>
                    <a href="{{ route('admin.reports.daily') }}" class="btn btn-primary">مشاهده</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-calendar-month" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">گزارش ماهانه</h5>
                    <p>آمار بازدیدهای ماه</p>
                    <a href="{{ route('admin.reports.monthly') }}" class="btn btn-success">مشاهده</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-hdd-stack" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">گزارش تجهیزات</h5>
                    <p>وضعیت تجهیزات بازدید شده</p>
                    <a href="{{ route('admin.reports.equipments') }}" class="btn btn-info">مشاهده</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection