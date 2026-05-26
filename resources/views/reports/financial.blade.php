@extends('layouts.admin')

@section('title', 'گزارش مالی صورت وضعیت')

@section('header', 'گزارش مالی صورت وضعیت')

@section('content')
<div class="container-fluid">
    <!-- فیلترها -->
    <div class="filter-box">
        <form method="GET" action="{{ route('reports.financial') }}" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">از تاریخ</label>
                <input type="text" class="form-control persian-date" name="start_date" 
                       value="{{ request('start_date') }}" autocomplete="off">
            </div>
            <div class="col-md-3">
                <label class="form-label">تا تاریخ</label>
                <input type="text" class="form-control persian-date" name="end_date" 
                       value="{{ request('end_date') }}" autocomplete="off">
            </div>
            <div class="col-md-3">
                <label class="form-label">پیمانکار</label>
                <select name="contractor_id" class="form-select">
                    <option value="">همه پیمانکاران</option>
                    @foreach($contractors ?? [] as $contractor)
                        <option value="{{ $contractor->id }}" {{ request('contractor_id') == $contractor->id ? 'selected' : '' }}>
                            {{ $contractor->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">نوع تجهیز</label>
                <select name="equipment_type_id" class="form-select">
                    <option value="">همه تجهیزات</option>
                    @foreach($equipmentTypes ?? [] as $type)
                        <option value="{{ $type->id }}" {{ request('equipment_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mt-3">
                <label class="form-label">وضعیت تایید</label>
                <select name="final_status" class="form-select">
                    <option value="all">همه</option>
                    <option value="approved" {{ request('final_status') == 'approved' ? 'selected' : '' }}>تایید شده</option>
                    <option value="pending" {{ request('final_status') == 'pending' ? 'selected' : '' }}>در انتظار تایید</option>
                    <option value="rejected" {{ request('final_status') == 'rejected' ? 'selected' : '' }}>رد شده</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end mt-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> اعمال فیلتر
                </button>
            </div>
            <div class="col-md-3 d-flex align-items-end mt-3">
                <a href="{{ route('reports.financial') }}" class="btn btn-secondary w-100">
                    <i class="bi bi-x-circle"></i> حذف فیلترها
                </a>
            </div>
        </form>
    </div>

    <!-- کارت‌های آماری -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #2c3e50, #3498db);">
                <h3>{{ number_format($stats['total_inspections'] ?? 0) }}</h3>
                <p>تعداد بازدیدها</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #27ae60, #2ecc71);">
                <h3>{{ number_format($stats['total_activities'] ?? 0) }}</h3>
                <p>کل فعالیت‌ها</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #e67e22, #f39c12);">
                <h3>{{ number_format($stats['total_cost'] ?? 0) }}</h3>
                <p>هزینه کل (بدون ضریب)</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                <h3>{{ number_format($stats['final_cost'] ?? 0) }}</h3>
                <p>هزینه نهایی</p>
            </div>
        </div>
    </div>

    <!-- جدول صورت وضعیت مالی -->
    <div class="card">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">صورت وضعیت نهایی بر اساس پیمانکار</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <table>
                            <th>پیمانکار</th>
                            <th>تعداد بازدید</th>
                            <th>ضریب قرارداد</th>
                            <th>هزینه بدون ضریب</th>
                            <th>هزینه نهایی</th>
                            <th style="width: 50px">جزئیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalAll = 0;
                            $totalFinalAll = 0;
                        @endphp
                        @forelse($statement ?? [] as $contractorName => $data)
                        @php
                            $totalAll += $data['total_cost'];
                            $totalFinalAll += $data['final_cost'];
                        @endphp
                        <tr>
                            <td><strong>{{ $contractorName }}</strong></td>
                            <td class="text-center">{{ number_format($data['count']) }}</td>
                            <td class="text-center">{{ number_format($data['coefficient']) }}</td>
                            <td class="text-start">{{ number_format($data['total_cost']) }} ریال</td>
                            <td class="text-start text-success fw-bold">{{ number_format($data['final_cost']) }} ریال</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-info" type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#details-{{ Str::slug($contractorName) }}">
                                    <i class="bi bi-list-ul"></i>
                                </button>
                            </td>
                        </tr>
                        <tr class="collapse" id="details-{{ Str::slug($contractorName) }}">
                            <td colspan="6">
                                <div class="p-3 bg-light">
                                    <h6>جزئیات بازدیدهای {{ $contractorName }}</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>تاریخ بازدید</th>
                                                    <th>تعداد تجهیزات</th>
                                                    <th>هزینه</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($data['inspections'] as $ins)
                                                <tr>
                                                    <td>{{ verta($ins->inspection_date)->format('Y/m/d') }}</td>
                                                    <td class="text-center">{{ $ins->mainEquipments->count() }}</td>
                                                    <td>{{ number_format($ins->mainEquipments->sum(function($e) { 
                                                        return $e->activities->sum('total'); 
                                                    })) }} ریال</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <p class="mt-3">هیچ اطلاعاتی برای نمایش وجود ندارد</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if(!empty($statement) && count($statement) > 0)
                    <tfoot class="table-total-row">
                        <tr>
                            <td class="text-end fw-bold" colspan="3">جمع کل:</td>
                            <td class="fw-bold">{{ number_format($totalAll) }} ریال</td>
                            <td class="fw-bold">{{ number_format($totalFinalAll) }} ریال</td>
                            <td></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <!-- دکمه بازگشت -->
    <div class="back-btn">
        <a href="{{ route('reports.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-right"></i> بازگشت به گزارشات
        </a>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/persian-date.min.js') }}"></script>
<script src="{{ asset('js/persian-datepicker.min.js') }}"></script>
<link href="{{ asset('css/persian-datepicker.min.css') }}" rel="stylesheet">

<script>
$(document).ready(function() {
    // تنظیم تاریخ‌های پیش‌فرض (یک ماه پیش تا امروز)
    var today = new Date();
    var oneMonthAgo = new Date();
    oneMonthAgo.setMonth(today.getMonth() - 1);
    
    var persianToday = new persianDate(today);
    var persianOneMonthAgo = new persianDate(oneMonthAgo);
    
    $('.persian-date').each(function() {
        if (!$(this).val()) {
            if ($(this).attr('name') == 'start_date') {
                $(this).val(persianOneMonthAgo.format('YYYY/MM/DD'));
            } else if ($(this).attr('name') == 'end_date') {
                $(this).val(persianToday.format('YYYY/MM/DD'));
            }
        }
    });
    
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

function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('show');
}
</script>
@endpush
@endsection