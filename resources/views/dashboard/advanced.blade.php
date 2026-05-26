@extends('layouts.admin')

@section('title', 'داشبورد پیشرفته | گزارشات تحلیلی')

@section('content')

@php
    $completedPercent = $totalInspections > 0 ? ($completedInspections / $totalInspections) * 100 : 0;
    $draftPercent = $totalInspections > 0 ? ($draftInspections / $totalInspections) * 100 : 0;
    $archivedPercent = $totalInspections > 0 ? (($statusStats['archived'] ?? 0) / $totalInspections) * 100 : 0;
@endphp

<div class="container-fluid">

    <!-- هدر -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">📊 داشبورد پیشرفته</h2>
                    <p class="text-muted mt-2">گزارشات تحلیلی و آمار پیشرفته</p>
                </div>
                <div>
                    <button class="btn btn-outline-primary" onclick="window.print()">
                        <i class="bi bi-printer"></i> چاپ گزارش
                    </button>
                    <button class="btn btn-outline-success" id="exportExcel">
                        <i class="bi bi-file-excel"></i> خروجی Excel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- کارت‌های آماری ردیف اول -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div style="background: linear-gradient(135deg, #3498db, #2980b9); border-radius: 15px; padding: 20px; text-align: center; color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                <i class="bi bi-calendar-check" style="font-size: 35px;"></i>
                <h2 style="margin: 10px 0; font-size: 32px;">{{ number_format($stats['total_inspections'] ?? 0) }}</h2>
                <p style="margin: 0; opacity: 0.9;">تعداد بازدیدها</p>
            </div>
        </div>
        <div class="col-md-3">
            <div style="background: linear-gradient(135deg, #f39c12, #e67e22); border-radius: 15px; padding: 20px; text-align: center; color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                <i class="bi bi-hdd-stack" style="font-size: 35px;"></i>
                <h2 style="margin: 10px 0; font-size: 32px;">{{ number_format($stats['total_equipments'] ?? 0) }}</h2>
                <p style="margin: 0; opacity: 0.9;">تعداد تجهیزات</p>
            </div>
        </div>
        <div class="col-md-3">
            <div style="background: linear-gradient(135deg, #27ae60, #1e8449); border-radius: 15px; padding: 20px; text-align: center; color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                <i class="bi bi-list-check" style="font-size: 35px;"></i>
                <h2 style="margin: 10px 0; font-size: 32px;">{{ number_format($stats['total_activities'] ?? 0) }}</h2>
                <p style="margin: 0; opacity: 0.9;">کل فعالیت‌ها</p>
            </div>
        </div>
        <div class="col-md-3">
            <div style="background: linear-gradient(135deg, #e74c3c, #c0392b); border-radius: 15px; padding: 20px; text-align: center; color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                <i class="bi bi-cash-stack" style="font-size: 35px;"></i>
                <h2 style="margin: 10px 0; font-size: 32px;">{{ number_format($totalCost ?? 0) }}</h2>
                <p style="margin: 0; opacity: 0.9;">هزینه کل (ریال)</p>
            </div>
        </div>
    </div>

    <!-- کارت‌های آماری ردیف دوم -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div style="background: linear-gradient(135deg, #9b59b6, #7d3c98); border-radius: 15px; padding: 20px; text-align: center; color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                <i class="bi bi-calendar-day" style="font-size: 35px;"></i>
                <h2 style="margin: 10px 0; font-size: 32px;">{{ number_format($inspectionsToday ?? 0) }}</h2>
                <p style="margin: 0; opacity: 0.9;">بازدیدهای امروز</p>
            </div>
        </div>
        <div class="col-md-4">
            <div style="background: linear-gradient(135deg, #1abc9c, #148f77); border-radius: 15px; padding: 20px; text-align: center; color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                <i class="bi bi-calendar-month" style="font-size: 35px;"></i>
                <h2 style="margin: 10px 0; font-size: 32px;">{{ number_format($inspectionsThisMonth ?? 0) }}</h2>
                <p style="margin: 0; opacity: 0.9;">بازدیدهای این ماه</p>
            </div>
        </div>
        <div class="col-md-4">
            <div style="background: linear-gradient(135deg, #2c3e50, #1a252f); border-radius: 15px; padding: 20px; text-align: center; color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                <i class="bi bi-graph-up" style="font-size: 35px;"></i>
                <h2 style="margin: 10px 0; font-size: 32px;">{{ number_format(($totalCostThisMonth ?? 0) / 1000000, 1) }}</h2>
                <p style="margin: 0; opacity: 0.9;">هزینه این ماه (میلیون ریال)</p>
            </div>
        </div>
    </div>

    <!-- کارت‌های آماری ردیف سوم (تجهیزات) -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div style="background: linear-gradient(135deg, #27ae60, #1e8449); border-radius: 15px; padding: 20px; text-align: center; color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                <i class="bi bi-check-circle" style="font-size: 35px;"></i>
                <h2 style="margin: 10px 0; font-size: 32px;">{{ number_format($okEquipmentsCount ?? 0) }}</h2>
                <p style="margin: 0; opacity: 0.9;">تجهیزات سالم (OK)</p>
                <small>{{ number_format($okPercent, 1) }}% از کل تجهیزات</small>
            </div>
        </div>
        <div class="col-md-6">
            <div style="background: linear-gradient(135deg, #e74c3c, #c0392b); border-radius: 15px; padding: 20px; text-align: center; color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                <i class="bi bi-exclamation-triangle" style="font-size: 35px;"></i>
                <h2 style="margin: 10px 0; font-size: 32px;">{{ number_format($notOkEquipmentsCount ?? 0) }}</h2>
                <p style="margin: 0; opacity: 0.9;">تجهیزات خراب (Not OK)</p>
                <small>{{ number_format($notOkPercent, 1) }}% از کل تجهیزات</small>
            </div>
        </div>
    </div>

    <!-- نمودار وضعیت تجهیزات -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-pie-chart"></i> وضعیت تجهیزات</h5>
                </div>
                <div class="card-body text-center">
                    <canvas id="equipmentChart" style="max-width: 300px; max-height: 300px; margin: 0 auto;"></canvas>
                </div>
            </div>
        </div>
    </div>


    <!-- برای دیباگ - مقادیر را نمایش بده -->
    <div class="alert alert-info text-center">
        OK: {{ $okEquipmentsCount ?? 'تعریف نشده' }} | 
        Not OK: {{ $notOkEquipmentsCount ?? 'تعریف نشده' }}
    </div>

    <!-- فیلترهای نمودار -->
    <div class="row mb-4">
        <div class="col-md-3">
            <label class="form-label">انتخاب سال</label>
            <select id="yearFilter" class="form-select">
                <option value="1405" selected>سال 1405</option>
                <option value="1404">سال 1404</option>
                <option value="1403">سال 1403</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">نوع نمودار</label>
            <select id="chartType" class="form-select">
                <option value="bar">نمودار میله‌ای</option>
                <option value="line">نمودار خطی</option>
            </select>
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary mt-3" id="updateChart">
                <i class="bi bi-arrow-repeat"></i> بروزرسانی نمودار
            </button>
        </div>
    </div>

    <!-- نمودار ماهانه -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">تعداد بازدیدها در سال <span id="currentYear">{{ $currentJalaliYear }}</span></h5>
                </div>
                <div class="card-body">
                    <div id="chartBars" style="display: flex; align-items: flex-end; height: 350px; gap: 30px; overflow-x: auto; padding-bottom: 10px;">
                        @php
                            $maxMonthly = max($monthlyData) > 0 ? max($monthlyData) : 1;
                        @endphp
                        @foreach($monthlyLabels as $index => $label)
                        @php
                            $height = ($monthlyData[$index] / $maxMonthly) * 280;
                        @endphp
                        <div style="flex:1 ; max-width: 50px; text-align: center;">
                            <div style="background: linear-gradient(to top, #3498db, #2980b9); height: {{ $height }}px; border-radius: 8px 8px 0 0;"></div>
                            <div style="margin-top: 10px; font-size: 15px; white-space: nowrap; transform: rotate(45deg);">{{ $label }}</div>
                            <div style="font-size: 15px; color: #666;">{{ $monthlyData[$index] }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- ============================================ -->
<!-- جدول آخرین بازدیدها با فیلترهای اختصاصی + تاریخ -->
<!-- ============================================ -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="bi bi-clock-history"></i> آخرین بازدیدها</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('advanced.dashboard') }}" class="mb-3">
            <input type="hidden" name="table" value="recent">
            <div class="row g-2">
                <div class="col-md-2">
                    <select name="recent_contractor" class="form-select form-select-sm">
                        <option value="">همه پیمانکاران</option>
                        @foreach($contractors as $contractor)
                            <option value="{{ $contractor->id }}" {{ request('recent_contractor') == $contractor->id ? 'selected' : '' }}>
                                {{ $contractor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="recent_department" class="form-select form-select-sm">
                        <option value="">همه امورها</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ request('recent_department') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="recent_equipment_type" class="form-select form-select-sm">
                        <option value="">همه تجهیزات</option>
                        @foreach($equipmentTypes as $type)
                            <option value="{{ $type->id }}" {{ request('recent_equipment_type') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" name="recent_date_from" class="form-control form-control-sm persian-date" 
                           placeholder="تاریخ از" value="{{ request('recent_date_from') }}" autocomplete="off">
                </div>
                <div class="col-md-2">
                    <input type="text" name="recent_date_to" class="form-control form-control-sm persian-date" 
                           placeholder="تاریخ تا" value="{{ request('recent_date_to') }}" autocomplete="off">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-funnel"></i> فیلتر
                    </button>
                </div>
            </div>
        </form>
        
        <div class="table-responsive">
            <table class="table table-bordered" id="recentInspectionsTable">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>تاریخ بازدید</th>
                        <th>پیمانکار</th>
                        <th>وضعیت</th>
                        <th>هزینه</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentInspections as $inspection)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $inspection->jalali_date ?? '-' }}</td>
                        <td>{{ $inspection->contractor->name ?? $inspection->contractor_name ?? '-' }}</td>
                        <td>
                            @if($inspection->status == 'completed')
                                <span class="badge bg-success">تکمیل شده</span>
                            @elseif($inspection->status == 'draft')
                                <span class="badge bg-warning">پیش‌نویس</span>
                            @else
                                <span class="badge bg-secondary">بایگانی</span>
                            @endif
                        </td>
                        <td>{{ number_format($inspection->total_cost ?? 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-3">
            {{ $recentInspections->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<div style="clear: both; height: 20px;"></div>

<!-- ============================================ -->
<!-- گزارش تجهیزات سالم (OK) با فیلترهای اختصاصی + تاریخ -->
<!-- ============================================ -->
<div class="card mt-4">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="bi bi-check-circle text-success"></i> گزارش تجهیزات سالم (OK)</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('advanced.dashboard') }}" class="mb-3">
            <input type="hidden" name="table" value="ok">
            <div class="row g-2">
                <div class="col-md-2">
                    <select name="ok_contractor" class="form-select form-select-sm">
                        <option value="">همه پیمانکاران</option>
                        @foreach($contractors as $contractor)
                            <option value="{{ $contractor->id }}" {{ request('ok_contractor') == $contractor->id ? 'selected' : '' }}>
                                {{ $contractor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="ok_department" class="form-select form-select-sm">
                        <option value="">همه امورها</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ request('ok_department') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="ok_equipment_type" class="form-select form-select-sm">
                        <option value="">همه تجهیزات</option>
                        @foreach($equipmentTypes as $type)
                            <option value="{{ $type->id }}" {{ request('ok_equipment_type') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" name="ok_date_from" class="form-control form-control-sm persian-date" 
                           placeholder="تاریخ از" value="{{ request('ok_date_from') }}" autocomplete="off">
                </div>
                <div class="col-md-2">
                    <input type="text" name="ok_date_to" class="form-control form-control-sm persian-date" 
                           placeholder="تاریخ تا" value="{{ request('ok_date_to') }}" autocomplete="off">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-funnel"></i> فیلتر
                    </button>
                </div>
            </div>
        </form>
        
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="okTable">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>نوع تجهیزات</th>
                        <th>کد SCADA</th>
                        <th>تاریخ بازدید</th>
                        <th>پیمانکار</th>
                        <th>موقعیت</th>
                        <th>وضعیت</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($okEquipments as $index => $equipment)
                    <tr class="table-success">
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $equipment['equipment_type'] }}</td>
                        <td>{{ $equipment['scada_code'] }}</td>
                        <td>{{ $equipment['inspection_date'] }}</td>
                        <td>{{ $equipment['contractor_name'] }}</td>
                        <td>{{ $equipment['location'] }}</td>
                        <td><span class="badge bg-success">OK</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-info-circle text-info" style="font-size: 40px;"></i>
                            <p class="mt-2">هیچ تجهیز سالمی یافت نشد</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-3">
            {{ $okEquipments->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<div style="clear: both; height: 20px;"></div>

<!-- ============================================ -->
<!-- گزارش تجهیزات خراب (Not OK) با فیلترهای اختصاصی + تاریخ -->
<!-- ============================================ -->
<div class="card mt-4">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="bi bi-exclamation-triangle text-danger"></i> گزارش تجهیزات خراب (Not OK)</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('advanced.dashboard') }}" class="mb-3">
            <input type="hidden" name="table" value="failure">
            <div class="row g-2">
                <div class="col-md-2">
                    <select name="failure_contractor" class="form-select form-select-sm">
                        <option value="">همه پیمانکاران</option>
                        @foreach($contractors as $contractor)
                            <option value="{{ $contractor->id }}" {{ request('failure_contractor') == $contractor->id ? 'selected' : '' }}>
                                {{ $contractor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="failure_department" class="form-select form-select-sm">
                        <option value="">همه امورها</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ request('failure_department') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="failure_equipment_type" class="form-select form-select-sm">
                        <option value="">همه تجهیزات</option>
                        @foreach($equipmentTypes as $type)
                            <option value="{{ $type->id }}" {{ request('failure_equipment_type') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" name="failure_date_from" class="form-control form-control-sm persian-date" 
                           placeholder="تاریخ از" value="{{ request('failure_date_from') }}" autocomplete="off">
                </div>
                <div class="col-md-2">
                    <input type="text" name="failure_date_to" class="form-control form-control-sm persian-date" 
                           placeholder="تاریخ تا" value="{{ request('failure_date_to') }}" autocomplete="off">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-funnel"></i> فیلتر
                    </button>
                </div>
            </div>
        </form>
        
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="failureTable">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>نوع تجهیزات</th>
                        <th>کد SCADA</th>
                        <th>آیتم چک‌لیست خراب</th>
                        <th>توضیحات خرابی</th>
                        <th>تاریخ بازدید</th>
                        <th>پیمانکار</th>
                        <th>موقعیت</th>
                        <th>وضعیت</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($failureEquipments ?? [] as $index => $equipment)
                    <tr class="table-danger">
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $equipment['equipment_type'] }}</td>
                        <td>{{ $equipment['scada_code'] }}</td>
                        <td class="text-danger fw-bold">{{ $equipment['failure_item'] }}</td>
                        <td>{{ $equipment['failure_description'] }}</td>
                        <td>{{ $equipment['inspection_date'] }}</td>
                        <td>{{ $equipment['contractor_name'] }}</td>
                        <td>{{ $equipment['location'] }}</td>
                        <td><span class="badge bg-danger">Not OK</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <i class="bi bi-check-circle text-success" style="font-size: 40px;"></i>
                            <p class="mt-2">هیچ خرابی یافت نشد - همه تجهیزات سالم هستند</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-3">
            {{ $failureEquipments->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<div style="clear: both; height: 20px;"></div>

<!-- ============================================ -->
<!-- فعالیت‌های انجام شده با فیلترهای اختصاصی + تاریخ -->
<!-- ============================================ -->
<div class="card mt-4">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="bi bi-clipboard-check text-primary"></i> فعالیت‌های انجام شده در بازدیدها</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('advanced.dashboard') }}" class="mb-3">
            <input type="hidden" name="table" value="activities">
            <div class="row g-2">
                <div class="col-md-2">
                    <select name="act_contractor" class="form-select form-select-sm">
                        <option value="">همه پیمانکاران</option>
                        @foreach($contractors as $contractor)
                            <option value="{{ $contractor->id }}" {{ request('act_contractor') == $contractor->id ? 'selected' : '' }}>
                                {{ $contractor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="act_department" class="form-select form-select-sm">
                        <option value="">همه امورها</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ request('act_department') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="act_equipment_type" class="form-select form-select-sm">
                        <option value="">همه تجهیزات</option>
                        @foreach($equipmentTypes as $type)
                            <option value="{{ $type->id }}" {{ request('act_equipment_type') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" name="act_date_from" class="form-control form-control-sm persian-date" 
                           placeholder="تاریخ از" value="{{ request('act_date_from') }}" autocomplete="off">
                </div>
                <div class="col-md-2">
                    <input type="text" name="act_date_to" class="form-control form-control-sm persian-date" 
                           placeholder="تاریخ تا" value="{{ request('act_date_to') }}" autocomplete="off">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-funnel"></i> فیلتر
                    </button>
                </div>
            </div>
        </form>
        
        <div class="table-responsive">
            <table class="table table-bordered" id="activitiesTable">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>کد فعالیت</th>
                        <th>عنوان فعالیت</th>
                        <th>واحد</th>
                        <th>قیمت واحد (ریال)</th>
                        <th>تعداد</th>
                        <th>مبلغ کل (ریال)</th>
                        <th>بازدید مربوطه</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities ?? [] as $index => $activity)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $activity->code ?? '-' }}</td>
                        <td>{{ $activity->title ?? '-' }}</td>
                        <td>{{ $activity->unit ?? '-' }}</td>
                        <td>{{ number_format($activity->unit_price ?? 0) }}</td>
                        <td class="text-center">{{ $activity->quantity ?? 0 }}</td>
                        <td class="text-end fw-bold">{{ number_format($activity->total ?? 0) }}</td>
                        <td>
                            @if($activity->mainEquipment && $activity->mainEquipment->inspection)
                                <a href="{{ route('inspection.show', $activity->mainEquipment->inspection->id) }}" target="_blank">
                                    بازدید شماره {{ $activity->mainEquipment->inspection->id }}
                                </a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">هیچ فعالیتی یافت نشد</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-3">
            {{ $activities->appends(request()->query())->links() }}
        </div>
    </div>
</div>


@push('scripts')
<script src="{{ asset('js/xlsx.full.min.js') }}"></script>
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/persian-date.min.js') }}"></script>
<script src="{{ asset('js/persian-datepicker.min.js') }}"></script>
<script src="{{ asset('js/select2.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/sweetalert2.min.js') }}"></script>
<link href="{{ asset('css/persian-datepicker.min.css') }}" rel="stylesheet">
<script src="{{ asset('js/chart.umd.min.js') }}"></script>

<script>
console.log('اسکریپت اصلی لود شد');

// ========== تابع کمکی برای تولید نمودار خطی ==========
function generateLineChart(months, counts, maxVal) {
    const chartWidth = 1000;
    const chartHeight = 350;
    const paddingLeft = 60;
    const paddingRight = 40;
    const paddingTop = 30;
    const paddingBottom = 60;
    
    const graphWidth = chartWidth - paddingLeft - paddingRight;
    const graphHeight = chartHeight - paddingTop - paddingBottom;
    const step = counts.length > 1 ? graphWidth / (counts.length - 1) : graphWidth;
    
    let points = [];
    for (let i = 0; i < counts.length; i++) {
        const x = paddingLeft + (i * step);
        const y = paddingTop + graphHeight - ((counts[i] / maxVal) * graphHeight);
        points.push(`${x},${y}`);
    }
    
    let yLabels = [];
    for (let i = 0; i <= 4; i++) {
        const val = Math.round((maxVal / 4) * i);
        yLabels.push(val);
    }
    
    let svgHtml = '<svg viewBox="0 0 ' + chartWidth + ' ' + chartHeight + '" style="width:100%; height:auto; min-height:400px; background:#fafafa; border-radius:10px;">';
    
    for (let idx = 0; idx < yLabels.length; idx++) {
        const y = paddingTop + graphHeight - (graphHeight * idx / 4);
        svgHtml += '<line x1="' + paddingLeft + '" y1="' + y + '" x2="' + (chartWidth - paddingRight) + '" y2="' + y + '" stroke="#ddd" stroke-width="1" stroke-dasharray="4,4"/>';
        svgHtml += '<text x="' + (paddingLeft - 10) + '" y="' + (y + 4) + '" text-anchor="end" font-size="11" fill="#666">' + yLabels[idx] + '</text>';
    }
    
    svgHtml += '<line x1="' + paddingLeft + '" y1="' + (paddingTop + graphHeight) + '" x2="' + (chartWidth - paddingRight) + '" y2="' + (paddingTop + graphHeight) + '" stroke="#999" stroke-width="1.5"/>';
    svgHtml += '<polyline points="' + points.join(' ') + '" fill="none" stroke="#e74c3c" stroke-width="2.5" stroke-linejoin="round" stroke-linecap="round"/>';
    
    for (let idx = 0; idx < points.length; idx++) {
        const [x, y] = points[idx].split(',');
        svgHtml += '<circle cx="' + x + '" cy="' + y + '" r="6" fill="#e74c3c" stroke="white" stroke-width="2"/>';
        svgHtml += '<text x="' + x + '" y="' + (parseFloat(y) - 10) + '" text-anchor="middle" font-size="13" fill="#e74c3c" font-weight="bold">' + counts[idx] + '</text>';
    }
    
    for (let idx = 0; idx < months.length; idx++) {
        const x = paddingLeft + (idx * step);
        svgHtml += '<text x="' + x + '" y="' + (chartHeight - paddingBottom + 20) + '" text-anchor="middle" font-size="12" fill="#333" transform="rotate(45, ' + x + ', ' + (chartHeight - paddingBottom + 20) + ')">' + months[idx] + '</text>';
    }
    
    svgHtml += '</svg>';
    return svgHtml;
}

// ========== تابع به‌روزرسانی نمودار ==========
function updateChart(year, type) {
    console.log('درخواست برای سال:', year, 'نوع:', type);
    const container = document.getElementById('chartBars');
    if (!container) {
        console.error('عنصر chartBars یافت نشد');
        return;
    }
    
    container.innerHTML = '<div class="text-center p-5">در حال بارگذاری...</div>';
    
    const url = `/api/advanced-dashboard/monthly?year=${year}`;
    
    fetch(url)
        .then(res => {
            if (!res.ok) throw new Error('خطا در دریافت داده');
            return res.json();
        })
        .then(data => {
            if (!data.months || !data.counts) {
                throw new Error('داده نامعتبر');
            }
            
            const maxVal = Math.max(...data.counts, 1);
            
            if (type === 'bar') {
                container.style.display = 'flex';
                container.style.alignItems = 'flex-end';
                container.style.height = '350px';
                container.style.gap = '8px';
                container.style.overflowX = 'auto';
                
                let html = '';
                for (let i = 0; i < data.months.length; i++) {
                    const height = (data.counts[i] / maxVal) * 280;
                    html += `
                        <div style="flex:1; min-width: 50px; text-align:center;">
                            <div style="background: linear-gradient(to top, #3498db, #2980b9); height: ${height}px; border-radius: 8px 8px 0 0; transition: height 0.3s;"></div>
                            <div style="margin-top: 8px; font-size: 12px; transform: rotate(45deg); white-space: nowrap;">${data.months[i]}</div>
                            <div style="font-size: 13px; color: #e74c3c; font-weight: bold;">${data.counts[i]}</div>
                        </div>
                    `;
                }
                container.innerHTML = html;
            } else {
                container.style.display = 'block';
                container.style.height = 'auto';
                const svgHtml = generateLineChart(data.months, data.counts, maxVal);
                container.innerHTML = svgHtml;
            }
            
            document.getElementById('currentYear').innerText = data.year || year;
        })
        .catch(err => {
            console.error('خطا:', err);
            container.innerHTML = '<div class="text-center p-5 text-danger">❌ خطا در بارگذاری داده‌ها<br>مسیر API را بررسی کنید</div>';
        });
}

// ========== تابع جدا کردن جداول ==========
function separateTables() {
    const firstTable = document.getElementById('recentInspectionsTable');
    if (!firstTable) {
        console.error('جدول اول پیدا نشد');
        return;
    }
    
    console.log('جدول اول پیدا شد، در حال اصلاح...');
    
    const allCards = document.querySelectorAll('.card');
    const container = document.querySelector('.container-fluid');
    
    let failureCard = null;
    let okCard = null;
    let activitiesCard = null;
    
    for (let card of allCards) {
        const header = card.querySelector('.card-header');
        if (header && header.innerText.includes('گزارش تجهیزات خراب')) {
            failureCard = card;
        } else if (header && header.innerText.includes('گزارش تجهیزات سالم')) {
            okCard = card;
        } else if (header && header.innerText.includes('فعالیت‌های انجام شده')) {
            activitiesCard = card;
        }
    }
    
    if (failureCard && container && failureCard.parentNode !== container) {
        container.appendChild(failureCard);
    }
    if (okCard && container && okCard.parentNode !== container) {
        container.appendChild(okCard);
    }
    if (activitiesCard && container && activitiesCard.parentNode !== container) {
        container.appendChild(activitiesCard);
    }
    
    const thead = firstTable.querySelector('thead');
    const tbody = firstTable.querySelector('tbody');
    
    if (thead) {
        const headerRow = thead.querySelector('tr');
        const ths = headerRow.querySelectorAll('th');
        for (let i = ths.length - 1; i >= 5; i--) {
            ths[i].remove();
        }
    }
    
    if (tbody) {
        const rows = tbody.querySelectorAll('tr');
        rows.forEach(row => {
            const tds = row.querySelectorAll('td');
            for (let i = tds.length - 1; i >= 5; i--) {
                tds[i].remove();
            }
        });
    }
    
    console.log('اصلاح جداول کامل شد');
}

// ========== تابع خروجی اکسل ==========
function exportToExcel() {
    try {
        function safeTableToSheet(tableElement, maxLength = 150) {
            const clonedTable = tableElement.cloneNode(true);
            const cells = clonedTable.querySelectorAll('td, th');
            cells.forEach(cell => {
                let text = cell.innerText || '';
                if (text.length > maxLength) {
                    cell.innerText = text.substring(0, maxLength) + '...';
                }
            });
            return XLSX.utils.table_to_sheet(clonedTable);
        }
        
        const wb = XLSX.utils.book_new();
        
        const tables = document.querySelectorAll('.card .table');
        tables.forEach((table, index) => {
            const card = table.closest('.card');
            const header = card?.querySelector('.card-header h5, .card-header h6');
            let sheetName = header ? header.innerText.trim().substring(0, 31) : `Sheet${index + 1}`;
            sheetName = sheetName.replace(/[\\/*?:[\]]/g, '');
            const safeSheet = safeTableToSheet(table, 150);
            XLSX.utils.book_append_sheet(wb, safeSheet, sheetName);
        });
        
        XLSX.writeFile(wb, `گزارش_داشبورد_${new Date().toLocaleDateString('fa-IR')}.xlsx`);
        alert('✅ فایل گزارش با موفقیت ذخیره شد');
        
    } catch(e) {
        console.error('❌ خطا:', e);
        alert('❌ خطا در ایجاد گزارش: ' + e.message);
    }
}

// ========== آماده شدن صفحه ==========
$(document).ready(function() {
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
    
    // ========== رسم نمودار دایره‌ای تجهیزات ==========
    const chartCanvas = document.getElementById('equipmentChart');
    if (chartCanvas) {
        const okCount = {{ $okEquipmentsCount ?? 0 }};
        const notOkCount = {{ $notOkEquipmentsCount ?? 0 }};
        
        console.log('رسم نمودار با مقادیر:', okCount, notOkCount);
        
        if (okCount > 0 || notOkCount > 0) {
            try {
                new Chart(chartCanvas, {
                    type: 'doughnut',
                    data: {
                        labels: ['سالم (OK)', 'خراب (Not OK)'],
                        datasets: [{
                            data: [okCount, notOkCount],
                            backgroundColor: ['#27ae60', '#e74c3c'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                rtl: true,
                                labels: {
                                    font: {
                                        family: 'Vazirmatn',
                                        size: 12
                                    }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.raw || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percent = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                        return `${label}: ${value.toLocaleString()} (${percent}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
                console.log('نمودار با موفقیت رسم شد');
            } catch(e) {
                console.error('خطا در رسم نمودار:', e);
            }
        } else {
            console.log('داده‌ای برای رسم نمودار وجود ندارد');
            if (chartCanvas.parentElement) {
                chartCanvas.parentElement.innerHTML = '<div class="alert alert-warning">داده‌ای برای نمایش وجود ندارد</div>';
            }
        }
    }
});

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM آماده است');
    
    const updateBtn = document.getElementById('updateChart');
    if(updateBtn) {
        updateBtn.addEventListener('click', function() {
            const year = document.getElementById('yearFilter').value;
            const chartType = document.getElementById('chartType').value;
            updateChart(year, chartType);
        });
        console.log('دکمه بروزرسانی پیدا شد');
    }
    
    const exportBtn = document.getElementById('exportExcel');
    if(exportBtn) {
        exportBtn.addEventListener('click', exportToExcel);
        console.log('دکمه اکسل پیدا شد');
    }
    
    setTimeout(separateTables, 200);
});
</script>
@endpush