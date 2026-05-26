@extends('layouts.admin')

@section('title', 'گزارش روزانه بازدیدها')

@section('header', 'گزارش روزانه')

@section('content')
<div class="container-fluid">
    <!-- فیلترها -->
    <div class="filter-section">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-funnel"></i> فیلترهای پیشرفته</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('reports.daily') }}" id="filterForm">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">از تاریخ</label>
                            <input type="text" class="form-control persian-date" name="start_date" 
                                   id="start_date" value="{{ request('start_date') }}" autocomplete="off">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">تا تاریخ</label>
                            <input type="text" class="form-control persian-date" name="end_date" 
                                   id="end_date" value="{{ request('end_date') }}" autocomplete="off">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">پیمانکار</label>
                            <select name="contractor" class="form-select">
                                <option value="">همه پیمانکاران</option>
                                @foreach($contractors ?? [] as $contractorItem)
                                    <option value="{{ $contractorItem }}" {{ request('contractor') == $contractorItem ? 'selected' : '' }}>
                                        {{ $contractorItem }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">نوع تجهیز</label>
                            <select name="equipment_type" class="form-select">
                                <option value="">همه تجهیزات</option>
                                @foreach($equipmentTypes ?? [] as $type)
                                    <option value="{{ $type->id }}" {{ request('equipment_type') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">وضعیت</label>
                            <select name="status" class="form-select">
                                <option value="all">همه</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>تکمیل شده</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>پیش‌نویس</option>
                                <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>بایگانی</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i> اعمال فیلتر
                            </button>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <a href="{{ route('reports.daily') }}" class="btn btn-secondary w-100">
                                <i class="bi bi-x-circle"></i> حذف فیلترها
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- خلاصه آمار -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="summary-box">
                <h5>تعداد بازدیدها</h5>
                <h2>{{ number_format($todayInspections ?? 0) }}</h2>
                @if(isset($compareText))
                <small class="text-{{ ($todayInspections ?? 0) > ($yesterdayInspections ?? 0) ? 'success' : 'danger' }}">
                    <i class="bi bi-arrow-{{ ($todayInspections ?? 0) > ($yesterdayInspections ?? 0) ? 'up' : 'down' }}"></i>
                    {{ $compareText ?? '' }}
                </small>
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <div class="summary-box">
                <h5>تعداد تجهیزات بازدید شده</h5>
                <h2>{{ number_format($todayEquipments ?? 0) }}</h2>
                <small>میانگین {{ number_format($avgEquipments ?? 0) }} تجهیز در هر بازدید</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="summary-box">
                <h5>پیمانکاران فعال</h5>
                <h2>{{ number_format($activeContractors ?? 0) }}</h2>
                <small>{{ $contractorList ?? '---' }}</small>
            </div>
        </div>
    </div>

    <!-- جدول بازدیدها -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">لیست بازدیدها</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>تاریخ</th>
                            <th>پیمانکار</th>
                            <th>تعداد تجهیزات</th>
                            <th>زمان شروع</th>
                            <th>زمان پایان</th>
                            <th>کاربر ثبت‌کننده</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inspectionsList ?? [] as $index => $inspection)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $inspection->inspection_date }}</td>
                            <td>{{ $inspection->contractor }}</td>
                            <td class="text-center">{{ $inspection->mainEquipments->count() }}</td>
                            <td>{{ $inspection->daily_start_time ?? '---' }}</td>
                            <td>{{ $inspection->daily_end_time ?? '---' }}</td>
                            <td>{{ $inspection->user->name ?? 'نامشخص' }}</td>
                            <td>
                                <a href="{{ route('inspections.show', $inspection->id) }}" class="btn btn-sm btn-info" target="_blank">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <p class="mt-3">هیچ بازدیدی در بازه زمانی انتخاب شده یافت نشد</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- دکمه‌های پایین صفحه -->
    <div class="text-center mt-4">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="bi bi-printer"></i> چاپ گزارش
        </button>
        <a href="{{ route('reports.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-right"></i> بازگشت به گزارشات
        </a>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/moment.min.js') }}"></script>
<script src="{{ asset('js/moment-jalaali.js') }}"></script>
<script src="{{ asset('js/persian-date.min.js') }}"></script>
<script src="{{ asset('js/persian-datepicker.min.js') }}"></script>
<link href="{{ asset('css/persian-datepicker.min.css') }}" rel="stylesheet">

<script>
$(document).ready(function() {
    // تنظیم تاریخ‌های پیش‌فرض (امروز)
    var today = new Date();
    var persianToday = new persianDate(today);
    var todayFormatted = persianToday.format('YYYY/MM/DD');
    
    if (!$('#start_date').val()) {
        $('#start_date').val(todayFormatted);
    }
    if (!$('#end_date').val()) {
        $('#end_date').val(todayFormatted);
    }
    
    // فعال‌سازی تقویم شمسی
    $('.persian-date').persianDatepicker({
        format: 'YYYY/MM/DD',
        autoClose: true,
        initialValue: false,
        calendar: {
            persian: {
                locale: 'fa'
            }
        }
    });
});
</script>
@endpush
@endsection