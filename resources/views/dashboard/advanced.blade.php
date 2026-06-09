@extends('layouts.admin')

@section('title', 'داشبورد پیشرفته | گزارشات تحلیلی')

@section('content')


<div class="container-fluid">

    <!-- هدر -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
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

<!-- ============================================ -->
<!-- CSS کارت‌های داشبورد -->
<!-- ============================================ -->

<style>

.dashboard-stat-card{
    position: relative;
    overflow: hidden;

    display: flex;
    align-items: center;
    gap: 20px;

    min-height: 145px;

    padding: 24px;

    border-radius: 22px;

    color: #fff;

    box-shadow: 0 10px 30px rgba(0,0,0,.12);

    transition: all .35s ease;
}

.dashboard-stat-card:hover{
    transform: translateY(-6px);
    box-shadow: 0 18px 40px rgba(0,0,0,.18);
}

.dashboard-stat-icon{
    width: 72px;
    height: 72px;

    border-radius: 20px;

    background: rgba(255,255,255,.15);

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 32px;

    flex-shrink: 0;

    backdrop-filter: blur(5px);
}

.dashboard-stat-content{
    flex: 1;
}

.dashboard-stat-content h2{
    margin: 0;

    font-size: 34px;
    font-weight: 800;

    line-height: 1.1;
}

.dashboard-stat-content p{
    margin: 8px 0 0;

    font-size: 15px;

    opacity: .92;
}

.dashboard-stat-content small{
    display: block;

    margin-top: 6px;

    font-size: 13px;

    opacity: .85;
}

/* رنگ‌ها */

.bg-blue{
    background: linear-gradient(135deg,#3b82f6,#2563eb);
}

.bg-orange{
    background: linear-gradient(135deg,#f59e0b,#d97706);
}

.bg-green{
    background: linear-gradient(135deg,#10b981,#059669);
}

.bg-red{
    background: linear-gradient(135deg,#ef4444,#dc2626);
}

.bg-purple{
    background: linear-gradient(135deg,#8b5cf6,#7c3aed);
}

.bg-teal{
    background: linear-gradient(135deg,#14b8a6,#0f766e);
}

.bg-dark{
    background: linear-gradient(135deg,#334155,#0f172a);
}

/* Responsive */

@media(max-width:768px){

    .dashboard-stat-card{
        min-height: 120px;
        padding: 18px;
        border-radius: 18px;
    }

    .dashboard-stat-icon{
        width: 58px;
        height: 58px;
        font-size: 24px;
    }

    .dashboard-stat-content h2{
        font-size: 26px;
    }

    .dashboard-stat-content p{
        font-size: 14px;
    }

}

</style>


<!-- ============================================ -->
<!-- کارت‌های آماری ردیف اول -->
<!-- ============================================ -->

<div class="row g-4 mb-4">

    <!-- تعداد بازدیدها -->
    <div class="col-xxl-3 col-lg-6 col-md-6">
        <div class="dashboard-stat-card bg-blue">

            <div class="dashboard-stat-icon">
                <i class="bi bi-calendar-check"></i>
            </div>

            <div class="dashboard-stat-content">
                <h2>{{ number_format($stats['total_inspections'] ?? 0) }}</h2>
                <p>تعداد بازدیدها</p>
            </div>

        </div>
    </div>

    <!-- تعداد تجهیزات -->
    <div class="col-xxl-3 col-lg-6 col-md-6">
        <div class="dashboard-stat-card bg-orange">

            <div class="dashboard-stat-icon">
                <i class="bi bi-hdd-stack"></i>
            </div>

            <div class="dashboard-stat-content">
                <h2>{{ number_format($stats['total_equipments'] ?? 0) }}</h2>
                <p>تعداد تجهیزات</p>
            </div>

        </div>
    </div>

    <!-- کل فعالیت‌ها -->
    <div class="col-xxl-3 col-lg-6 col-md-6">
        <div class="dashboard-stat-card bg-green">

            <div class="dashboard-stat-icon">
                <i class="bi bi-list-check"></i>
            </div>

            <div class="dashboard-stat-content">
                <h2>{{ number_format($stats['total_activities'] ?? 0) }}</h2>
                <p>کل فعالیت‌ها</p>
            </div>

        </div>
    </div>

    <!-- هزینه کل -->
    <div class="col-xxl-3 col-lg-6 col-md-6">
        <div class="dashboard-stat-card bg-red">

            <div class="dashboard-stat-icon">
                <i class="bi bi-cash-stack"></i>
            </div>

            <div class="dashboard-stat-content">
                <h2>{{ number_format($totalCost ?? 0) }}</h2>
                <p>هزینه کل (ریال)</p>
            </div>

        </div>
    </div>

</div>


<!-- ============================================ -->
<!-- کارت‌های آماری ردیف دوم -->
<!-- ============================================ -->

<div class="row g-4 mb-4">

    <!-- بازدیدهای امروز -->
    <div class="col-lg-4 col-md-6">
        <div class="dashboard-stat-card bg-purple">

            <div class="dashboard-stat-icon">
                <i class="bi bi-calendar-day"></i>
            </div>

            <div class="dashboard-stat-content">
                <h2>{{ number_format($inspectionsToday ?? 0) }}</h2>
                <p>بازدیدهای امروز</p>
            </div>

        </div>
    </div>

    <!-- بازدیدهای ماه -->
    <div class="col-lg-4 col-md-6">
        <div class="dashboard-stat-card bg-teal">

            <div class="dashboard-stat-icon">
                <i class="bi bi-calendar-month"></i>
            </div>

            <div class="dashboard-stat-content">
                <h2>{{ number_format($inspectionsThisMonth ?? 0) }}</h2>
                <p>بازدیدهای این ماه</p>
            </div>

        </div>
    </div>

    <!-- هزینه ماه -->
    <div class="col-lg-4 col-md-12">
        <div class="dashboard-stat-card bg-dark">

            <div class="dashboard-stat-icon">
                <i class="bi bi-graph-up"></i>
            </div>

            <div class="dashboard-stat-content">
                <h2>{{ number_format(($totalCostThisMonth ?? 0) / 1000000, 1) }}</h2>
                <p>هزینه این ماه (میلیون ریال)</p>
            </div>

        </div>
    </div>

</div>


<!-- ============================================ -->
<!-- کارت‌های آماری ردیف سوم -->
<!-- ============================================ -->

<div class="row g-4 mb-5">

    <!-- تجهیزات سالم -->
    <div class="col-lg-6 col-md-6">
        <div class="dashboard-stat-card bg-green">

            <div class="dashboard-stat-icon">
                <i class="bi bi-check-circle"></i>
            </div>

            <div class="dashboard-stat-content">
                <h2>{{ number_format($okEquipmentsCount ?? 0) }}</h2>

                <p>تجهیزات سالم (OK)</p>

                <small>
                    {{ number_format($okPercent, 1) }}%
                    از کل تجهیزات
                </small>
            </div>

        </div>
    </div>

    <!-- تجهیزات خراب -->
    <div class="col-lg-6 col-md-6">
        <div class="dashboard-stat-card bg-red">

            <div class="dashboard-stat-icon">
                <i class="bi bi-exclamation-triangle"></i>
            </div>

            <div class="dashboard-stat-content">
                <h2>{{ number_format($notOkEquipmentsCount ?? 0) }}</h2>

                <p>تجهیزات خراب (Not OK)</p>

                <small>
                    {{ number_format($notOkPercent, 1) }}%
                    از کل تجهیزات
                </small>
            </div>

        </div>
    </div>

</div>


{{-- ================= FILTER BAR ================= --}}

<div class="card shadow-sm border-0 mb-4">

    <div class="card-body">

        <form method="GET"
              action="{{ route('advanced.dashboard') }}">

            <div class="row g-3 align-items-end">

                <div class="col-xl-2 col-lg-3 col-md-6">
                    <label class="form-label fw-bold">از تاریخ</label>
                    <input type="text" name="chart_date_from" class="form-control persian-date" value="{{ request('chart_date_from') }}" placeholder="1405/01/01">
                </div>

                <div class="col-xl-2 col-lg-3 col-md-6">
                    <label class="form-label fw-bold">تا تاریخ</label>
                    <input type="text" name="chart_date_to" class="form-control persian-date" value="{{ request('chart_date_to') }}" placeholder="1405/12/29">
                </div>

                <div class="col-xl-2 col-lg-3 col-md-6">
                    <label class="form-label fw-bold">امور</label>
                    <select name="chart_department" class="form-select">
                        <option value="">همه امورها</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ request('chart_department') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-xl-2 col-lg-3 col-md-6">
                    <label class="form-label fw-bold">نوع تجهیز</label>
                    <select name="chart_equipment_type" class="form-select">
                        <option value="">همه تجهیزات</option>
                        @foreach($equipmentTypes as $type)
                            <option value="{{ $type->id }}" {{ request('chart_equipment_type') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-xl-2 col-lg-6 col-md-6">
                    <button class="btn btn-primary w-100"><i class="bi bi-funnel"></i> اعمال فیلتر</button>
                </div>

                <div class="col-xl-2 col-lg-6 col-md-6">
                    <a href="{{ route('advanced.dashboard') }}" class="btn btn-outline-secondary w-100"><i class="bi bi-arrow-clockwise"></i> پاکسازی</a>
                </div>

            </div>

        </form>

    </div>

</div>


{{-- ================= CHARTS ================= --}}

<div class="row g-4 mb-5">

    <div class="col-xxl-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0"><i class="bi bi-pie-chart-fill text-primary"></i> خرابی بر اساس نوع تجهیز</h5>
            </div>
            <div class="card-body">
                <canvas id="equipmentTypeChart" height="260"></canvas>
            </div>
        </div>
    </div>

    <div class="col-xxl-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0"><i class="bi bi-bar-chart-fill text-danger"></i> خرابی بر اساس امور</h5>
            </div>
            <div class="card-body">
                <canvas id="departmentChart" height="260"></canvas>
            </div>
        </div>
    </div>

    <div class="col-xxl-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0"><i class="bi bi-clipboard-data-fill text-success"></i> بیشترین فعالیت‌های انجام شده</h5>
            </div>
            <div class="card-body">
                <canvas id="activityChart" height="260"></canvas>
            </div>
        </div>
    </div>

    <div class="col-xxl-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0"><i class="bi bi-diagram-3-fill text-warning"></i> برندهای دارای خرابی</h5>
            </div>
            <div class="card-body">
                <canvas id="brandChart" height="260"></canvas>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0"><i class="bi bi-graph-up-arrow text-info"></i> روند ماهانه بازدیدها</h5>
            </div>
            <div class="card-body">
                <canvas id="trendChart" height="110"></canvas>
            </div>
        </div>
    </div>

<div class="col-12">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-2">
            <h6 class="mb-0">
                <i class="bi bi-bar-chart-steps text-danger"></i>
                توزیع خرابی (Not OK) بر اساس نوع تجهیز
            </h6>
        </div>

        <div class="card-body p-2">
            <div style="height:220px">
                <canvas id="notOkByEquipmentChart"></canvas>
            </div>
        </div>
    </div>
</div>
{{-- ================= 4 CHARTS FOR TOP FAILURES ================= --}}
<div class="row g-4 mb-5">
    
    {{-- 1. سکسیونر --}}
    <div class="col-lg-6 col-md-12">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0">
                    <i class="bi bi-bar-chart-steps text-primary"></i>
                    سکسیونر - ۵ آیتم Not OK برتر
                </h5>
            </div>
            <div class="card-body">
                <canvas id="failureChartSections" height="260"></canvas>
            </div>
        </div>
    </div>

    {{-- 2. ریکلوزر --}}
    <div class="col-lg-6 col-md-12">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0">
                    <i class="bi bi-bar-chart-steps text-danger"></i>
                    ریکلوزر - ۵ آیتم Not OK برتر
                </h5>
            </div>
            <div class="card-body">
                <canvas id="failureChartRecloser" height="260"></canvas>
            </div>
        </div>
    </div>

    {{-- 3. سکشنالایزر --}}
    <div class="col-lg-6 col-md-12">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0">
                    <i class="bi bi-bar-chart-steps text-warning"></i>
                    سکشنالایزر - ۵ آیتم Not OK برتر
                </h5>
            </div>
            <div class="card-body">
                <canvas id="failureChartSectionalizer" height="260"></canvas>
            </div>
        </div>
    </div>

    {{-- 4. فالت دتکتور --}}
    <div class="col-lg-6 col-md-12">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0">
                    <i class="bi bi-bar-chart-steps text-info"></i>
                    فالت دتکتور - ۵ آیتم Not OK برتر
                </h5>
            </div>
            <div class="card-body">
                <canvas id="failureChartFaultDetector" height="260"></canvas>
            </div>
        </div>
    </div>

</div>


</div>

    <!-- فیلترهای نمودار -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-2">
            <label class="form-label">انتخاب سال</label>
            <select id="yearFilter" class="form-select">
                <option value="1405" {{ $currentJalaliYear == 1405 ? 'selected' : '' }}>سال 1405</option>
                <option value="1404" {{ $currentJalaliYear == 1404 ? 'selected' : '' }}>سال 1404</option>
                <option value="1403" {{ $currentJalaliYear == 1403 ? 'selected' : '' }}>سال 1403</option>
            </select>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <label class="form-label">نوع نمودار</label>
            <select id="chartType" class="form-select">
                <option value="bar">نمودار میله‌ای</option>
                <option value="line">نمودار خطی</option>
            </select>
        </div>
        <div class="col-md-3 col-sm-12 mb-2">
            <label class="form-label">&nbsp;</label>
            <button class="btn btn-primary w-100" id="updateChart">
                <i class="bi bi-arrow-repeat"></i> بروزرسانی نمودار
            </button>
        </div>
    </div>

    <!-- نمودار ماهانه -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
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
                        <div style="flex:1 ; min-width: 50px; text-align: center;">
                            <div style="background: linear-gradient(to top, #3498db, #2980b9); height: {{ $height }}px; border-radius: 8px 8px 0 0;"></div>
                            <div style="margin-top: 10px; font-size: 12px; white-space: nowrap; transform: rotate(45deg);">{{ $label }}</div>
                            <div style="font-size: 13px; color: #e74c3c; font-weight: bold;">{{ $monthlyData[$index] }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- جداول (به همین صورت باقی می‌مانند) -->
    <!-- ============================================ -->
    
    <!-- جدول آخرین بازدیدها -->
    <div class="card shadow-sm mt-4" id="recent-section">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="bi bi-clock-history"></i> آخرین بازدیدها</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('advanced.dashboard') }}" class="mb-3">
                <input type="hidden" name="active_table" value="recent">
                <div class="row g-2">
                    <div class="col-md-2 col-sm-6">
                        <select name="recent_contractor" class="form-select form-select-sm">
                            <option value="">همه پیمانکاران</option>
                            @foreach($contractors as $contractor)
                                <option value="{{ $contractor->id }}" {{ ($recentFilters['contractor'] ?? '') == $contractor->id ? 'selected' : '' }}>{{ $contractor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <select name="recent_department" class="form-select form-select-sm">
                            <option value="">همه امورها</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ ($recentFilters['department'] ?? '') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <select name="recent_equipment_type" class="form-select form-select-sm">
                            <option value="">همه تجهیزات</option>
                            @foreach($equipmentTypes as $type)
                                <option value="{{ $type->id }}" {{ ($recentFilters['equipment_type'] ?? '') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <input type="text" name="recent_date_from" class="form-control form-control-sm persian-date" placeholder="تاریخ از" value="{{ $recentFilters['date_from'] ?? '' }}" autocomplete="off">
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <input type="text" name="recent_date_to" class="form-control form-control-sm persian-date" placeholder="تاریخ تا" value="{{ $recentFilters['date_to'] ?? '' }}" autocomplete="off">
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-funnel"></i> فیلتر</button>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <button type="submit" name="reset_table" value="recent" class="btn btn-secondary btn-sm w-100"><i class="bi bi-x-circle"></i> پاک کردن</button>
                    </div>
                </div>
            </form>
            
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="recentInspectionsTable">
                    <thead class="table-light">
                        <tr><th>#</th><th>تاریخ بازدید</th><th>پیمانکار</th><th>وضعیت</th><th>هزینه (ریال)</th></tr>
                    </thead>
                    <tbody>
                        @forelse($recentInspections as $inspection)
                        <tr><td>{{ $loop->iteration }}</td><td>{{ $inspection->jalali_date ?? '-' }}</td><td>{{ $inspection->contractor->name ?? $inspection->contractor_name ?? '-' }}</td>
                            <td>@if($inspection->status == 'completed')<span class="badge bg-success">تکمیل شده</span>@elseif($inspection->status == 'draft')<span class="badge bg-warning">پیش‌نویس</span>@else<span class="badge bg-secondary">بایگانی</span>@endif</td>
                            <td>{{ number_format($inspection->total_cost ?? 0) }}</td>
                        </tr>
                        @empty<tr><td colspan="5" class="text-center py-5">هیچ بازدیدی یافت نشد</td></tr>@endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $recentInspections->appends(['active_table' => 'recent'] + $recentFilters)->links() }}
            </div>
        </div>
    </div>

    <!-- جدول تجهیزات سالم (OK) -->
    <div class="card shadow-sm mt-4" id="ok-section">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="bi bi-check-circle text-success"></i> گزارش تجهیزات سالم (OK)</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('advanced.dashboard') }}" class="mb-3">
                <input type="hidden" name="active_table" value="ok">
                <div class="row g-2">
                    <div class="col-md-2 col-sm-6">
                        <select name="ok_contractor" class="form-select form-select-sm">
                            <option value="">همه پیمانکاران</option>
                            @foreach($contractors as $contractor)
                                <option value="{{ $contractor->id }}" {{ ($okFilters['contractor'] ?? '') == $contractor->id ? 'selected' : '' }}>{{ $contractor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <select name="ok_department" class="form-select form-select-sm">
                            <option value="">همه امورها</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ ($okFilters['department'] ?? '') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <select name="ok_equipment_type" class="form-select form-select-sm">
                            <option value="">همه تجهیزات</option>
                            @foreach($equipmentTypes as $type)
                                <option value="{{ $type->id }}" {{ ($okFilters['equipment_type'] ?? '') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <input type="text" name="ok_date_from" class="form-control form-control-sm persian-date" placeholder="تاریخ از" value="{{ $okFilters['date_from'] ?? '' }}" autocomplete="off">
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <input type="text" name="ok_date_to" class="form-control form-control-sm persian-date" placeholder="تاریخ تا" value="{{ $okFilters['date_to'] ?? '' }}" autocomplete="off">
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-funnel"></i> فیلتر</button>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <button type="submit" name="reset_table" value="ok" class="btn btn-secondary btn-sm w-100"><i class="bi bi-x-circle"></i> پاک کردن</button>
                    </div>
                </div>
            </form>
            
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="okTable">
                    <thead class="table-light"><tr><th>#</th><th>نوع تجهیزات</th><th>کد SCADA</th><th>تاریخ بازدید</th><th>پیمانکار</th><th>موقعیت</th><th>وضعیت</th></tr></thead>
                    <tbody>
                        @forelse($okEquipments as $equipment)
                        <tr class="table-success"><td class="text-center">{{ $loop->iteration }}</td><td>{{ $equipment['equipment_type'] }}</td><td>{{ $equipment['scada_code'] }}</td><td>{{ $equipment['inspection_date'] }}</td><td>{{ $equipment['contractor_name'] }}</td><td>{{ $equipment['location'] }}</td><td><span class="badge bg-success">OK</span></td></tr>
                        @empty<tr><td colspan="7" class="text-center py-5">هیچ تجهیز سالمی یافت نشد</td></tr>@endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $okEquipments->appends(['active_table' => 'ok'] + $okFilters)->links() }}
            </div>
        </div>
    </div>

    <!-- جدول تجهیزات خراب (Not OK) -->
    <div class="card shadow-sm mt-4" id="failure-section">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="bi bi-exclamation-triangle text-danger"></i> گزارش تجهیزات خراب (Not OK)</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('advanced.dashboard') }}" class="mb-3">
                <input type="hidden" name="active_table" value="failure">
                <div class="row g-2">
                    <div class="col-md-2 col-sm-6">
                        <select name="failure_contractor" class="form-select form-select-sm">
                            <option value="">همه پیمانکاران</option>
                            @foreach($contractors as $contractor)
                                <option value="{{ $contractor->id }}" {{ ($failureFilters['contractor'] ?? '') == $contractor->id ? 'selected' : '' }}>{{ $contractor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <select name="failure_department" class="form-select form-select-sm">
                            <option value="">همه امورها</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ ($failureFilters['department'] ?? '') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <select name="failure_equipment_type" class="form-select form-select-sm">
                            <option value="">همه تجهیزات</option>
                            @foreach($equipmentTypes as $type)
                                <option value="{{ $type->id }}" {{ ($failureFilters['equipment_type'] ?? '') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <input type="text" name="failure_date_from" class="form-control form-control-sm persian-date" placeholder="تاریخ از" value="{{ $failureFilters['date_from'] ?? '' }}" autocomplete="off">
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <input type="text" name="failure_date_to" class="form-control form-control-sm persian-date" placeholder="تاریخ تا" value="{{ $failureFilters['date_to'] ?? '' }}" autocomplete="off">
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-funnel"></i> فیلتر</button>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <button type="submit" name="reset_table" value="failure" class="btn btn-secondary btn-sm w-100"><i class="bi bi-x-circle"></i> پاک کردن</button>
                    </div>
                </div>
            </form>
            
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="failureTable">
                    <thead class="table-light"><tr><th>#</th><th>نوع تجهیزات</th><th>کد SCADA</th><th>آیتم چک‌لیست خراب</th><th>توضیحات خرابی</th><th>تاریخ بازدید</th><th>پیمانکار</th><th>موقعیت</th><th>وضعیت</th></tr></thead>
                    <tbody>
                        @forelse($failureEquipments ?? [] as $equipment)
                        <tr class="table-danger"><td class="text-center">{{ $loop->iteration }}</td><td>{{ $equipment['equipment_type'] }}</td><td>{{ $equipment['scada_code'] }}</td><td class="text-danger fw-bold">{{ $equipment['failure_item'] }}</td><td>{{ $equipment['failure_description'] }}</td><td>{{ $equipment['inspection_date'] }}</td><td>{{ $equipment['contractor_name'] }}</td><td>{{ $equipment['location'] }}</td><td><span class="badge bg-danger">Not OK</span></td></tr>
                        @empty<tr><td colspan="9" class="text-center py-5">هیچ خرابی یافت نشد</td></tr>@endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $failureEquipments->appends(['active_table' => 'failure'] + $failureFilters)->links() }}
            </div>
        </div>
    </div>

    <!-- جدول فعالیت‌ها -->
    <div class="card shadow-sm mt-4" id="activities-section">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="bi bi-clipboard-check text-primary"></i> فعالیت‌های انجام شده در بازدیدها</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('advanced.dashboard') }}" class="mb-3">
                <input type="hidden" name="active_table" value="activities">
                <div class="row g-2">
                    <div class="col-md-2 col-sm-6">
                        <select name="act_contractor" class="form-select form-select-sm">
                            <option value="">همه پیمانکاران</option>
                            @foreach($contractors as $contractor)
                                <option value="{{ $contractor->id }}" {{ ($activitiesFilters['contractor'] ?? '') == $contractor->id ? 'selected' : '' }}>{{ $contractor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <select name="act_department" class="form-select form-select-sm">
                            <option value="">همه امورها</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ ($activitiesFilters['department'] ?? '') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <select name="act_equipment_type" class="form-select form-select-sm">
                            <option value="">همه تجهیزات</option>
                            @foreach($equipmentTypes as $type)
                                <option value="{{ $type->id }}" {{ ($activitiesFilters['equipment_type'] ?? '') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <input type="text" name="act_date_from" class="form-control form-control-sm persian-date" placeholder="تاریخ از" value="{{ $activitiesFilters['date_from'] ?? '' }}" autocomplete="off">
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <input type="text" name="act_date_to" class="form-control form-control-sm persian-date" placeholder="تاریخ تا" value="{{ $activitiesFilters['date_to'] ?? '' }}" autocomplete="off">
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-funnel"></i> فیلتر</button>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <button type="submit" name="reset_table" value="activities" class="btn btn-secondary btn-sm w-100"><i class="bi bi-x-circle"></i> پاک کردن</button>
                    </div>
                </div>
            </form>
            
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="activitiesTable">
                    <thead class="table-light"><tr><th>#</th><th>کد فعالیت</th><th>عنوان فعالیت</th><th>واحد</th><th>قیمت واحد (ریال)</th><th>تعداد</th><th>مبلغ کل (ریال)</th><th>بازدید مربوطه</th></tr></thead>
                    <tbody>
                        @forelse($activities ?? [] as $activity)
                        <tr><td class="text-center">{{ $loop->iteration }}</td><td>{{ $activity->code ?? '-' }}</td><td>{{ $activity->title ?? '-' }}</td><td>{{ $activity->unit ?? '-' }}</td><td>{{ number_format($activity->unit_price ?? 0) }}</td><td class="text-center">{{ $activity->quantity ?? 0 }}</td><td class="text-end fw-bold">{{ number_format($activity->total ?? 0) }}</td>
                            <td>@if($activity->mainEquipment && $activity->mainEquipment->inspection)<a href="{{ route('inspection.show', $activity->mainEquipment->inspection->id) }}" target="_blank" class="btn btn-sm btn-outline-info">بازدید شماره {{ $activity->mainEquipment->inspection->id }}</a>@else-@endif</td>
                        </tr>
                        @empty<td colspan="8" class="text-center py-5">هیچ فعالیتی یافت نشد</td>@endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $activities->appends(['active_table' => 'activities'] + $activitiesFilters)->links() }}
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const equipmentTypeData = @json($chart_equipment_types ?? []);
    const departmentData = @json($chart_departments ?? []);
    const activityData = @json($chart_activities ?? []);
    const brandData = @json($chart_brands ?? []);
    const trendData = @json($chart_trend ?? []);
    const notOkByEquipmentData = @json($chart_not_ok_by_equipment ?? []);

    Chart.defaults.font.family = 'Vazirmatn';
    Chart.defaults.color = '#64748b';

    // 1. EQUIPMENT TYPE (Doughnut)
    const equipmentTypeCanvas = document.getElementById('equipmentTypeChart');
    if (equipmentTypeCanvas && equipmentTypeData.labels && equipmentTypeData.labels.length) {
        new Chart(equipmentTypeCanvas, {
            type: 'doughnut',
            data: { labels: equipmentTypeData.labels, datasets: [{ data: equipmentTypeData.data, backgroundColor: ['#3b82f6','#ef4444','#10b981','#f59e0b','#8b5cf6','#06b6d4'], borderWidth: 0 }] },
            options: { responsive: true, plugins: { legend: { position: 'bottom', rtl: true } } }
        });
    }

    // 2. DEPARTMENT (Bar)
    const departmentCanvas = document.getElementById('departmentChart');
    if (departmentCanvas && departmentData.labels && departmentData.labels.length) {
new Chart(departmentCanvas, {
    type: 'bar',
    data: {
        labels: departmentData.labels,

        datasets: [
            {
                label: 'تجهیزات دارای خرابی',
                data: departmentData.equipment_data,
                backgroundColor: '#f59e0b',
                borderRadius: 8
            },
            {
                label: 'تعداد کل خرابی‌ها',
                data: departmentData.failure_data,
                backgroundColor: '#ef4444',
                borderRadius: 8
            }
        ]
    },

    options: {
        responsive: true,

        plugins: {
            legend: {
                position: 'top'
            }
        },

        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
    }

    // 3. ACTIVITIES (Bar horizontal)
    const activityCanvas = document.getElementById('activityChart');
    if (activityCanvas && activityData.labels && activityData.labels.length) {
        new Chart(activityCanvas, {
            type: 'bar',
            data: { labels: activityData.labels, datasets: [{ label: 'تعداد فعالیت', data: activityData.data, backgroundColor: '#10b981', borderRadius: 8 }] },
            options: { indexAxis: 'y', responsive: true }
        });
    }

    // 4. BRANDS (PolarArea)
    const brandCanvas = document.getElementById('brandChart');
    if (brandCanvas && brandData.labels && brandData.labels.length) {
        new Chart(brandCanvas, {
            type: 'polarArea',
            data: { labels: brandData.labels, datasets: [{ data: brandData.data, backgroundColor: ['#f97316','#3b82f6','#10b981','#ef4444','#8b5cf6'] }] },
            options: { responsive: true }
        });
    }

    // 5. TREND (Line)
    const trendCanvas = document.getElementById('trendChart');
    if (trendCanvas && trendData.labels && trendData.labels.length) {
        new Chart(trendCanvas, {
            type: 'line',
            data: { labels: trendData.labels, datasets: [{ label: 'بازدیدها', data: trendData.data, borderColor: '#3b82f6', backgroundColor: 'rgba(59,130,246,.15)', fill: true, tension: 0.4, pointRadius: 5, pointHoverRadius: 7 }] },
            options: { responsive: true, interaction: { intersect: false, mode: 'index' }, scales: { y: { beginAtZero: true } } }
        });
    }

    // 6. NOT OK BY EQUIPMENT TYPE (Bar)
    const notOkByEquipmentCanvas = document.getElementById('notOkByEquipmentChart');
    if (notOkByEquipmentCanvas && notOkByEquipmentData.labels && notOkByEquipmentData.labels.length) {
new Chart(notOkByEquipmentCanvas, {
    type: 'bar',
    data: {
        labels: notOkByEquipmentData.labels,
        datasets: [{
            label: 'تعداد خرابی',
            data: notOkByEquipmentData.data,
            backgroundColor: '#ef4444',
            borderRadius: 5,
            barThickness: 22,
            maxBarThickness: 28
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,

        plugins: {
            legend: {
                display: false
            }
        },

        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: '#f1f5f9'
                },
                ticks: {
                    stepSize: 1,
                    font: {
                        size: 11
                    }
                }
            },

            x: {
                grid: {
                    display: false
                },
                ticks: {
                    font: {
                        size: 11
                    },
                    maxRotation: 25,
                    minRotation: 25
                }
            }
        }
    }
}); 
   }
// =====================================================
// CHARTS FOR TOP FAILURES BY EQUIPMENT TYPE
// =====================================================

const topFailuresData = @json($chart_top_failures ?? []);

// رنگ‌های ثابت برای هر نوع تجهیز
const colors = {
    'سکسیونر': '#3b82f6',
    'ریکلوزر': '#ef4444',
    'سکشنالایزر': '#f59e0b',
    'فالت دتکتور': '#10b981'
};

// تابع کمکی برای رسم نمودار میله‌ای افقی (Horizontal Bar)
function createHorizontalBarChart(canvasId, data, color, title) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;
    
    if (data.labels && data.labels.length > 0) {
        new Chart(canvas, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'تعداد Not OK',
                    data: data.data,
                    backgroundColor: color,
                    borderRadius: 6,
                    barPercentage: 0.6,
                    categoryPercentage: 0.8
                }]
            },
            options: {
                indexAxis: 'y',  // نمودار افقی برای خوانایی بهتر متن‌های طولانی
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                        rtl: true,
                        labels: {
                            font: { family: 'Vazirmatn', size: 12 }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `تعداد Not OK: ${context.raw.toLocaleString()}`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'تعداد موارد Not OK',
                            font: { family: 'Vazirmatn', size: 12 }
                        },
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        }
                    },
                    y: {
                        ticks: {
                            font: { family: 'Vazirmatn', size: 11 },
                            autoSkip: false
                        }
                    }
                }
            }
        });
    } else {
        // نمایش پیام اگر داده‌ای وجود نداشت
        canvas.parentElement.innerHTML = `<div class="alert alert-warning text-center py-4">هیچ داده Not OK برای ${title} یافت نشد</div>`;
    }
}

// رسم 4 نمودار
createHorizontalBarChart('failureChartSections', topFailuresData['سکسیونر'] || { labels: [], data: [] }, colors['سکسیونر'], 'سکسیونر');
createHorizontalBarChart('failureChartRecloser', topFailuresData['ریکلوزر'] || { labels: [], data: [] }, colors['ریکلوزر'], 'ریکلوزر');
createHorizontalBarChart('failureChartSectionalizer', topFailuresData['سکشنالایزر'] || { labels: [], data: [] }, colors['سکشنالایزر'], 'سکشنالایزر');
createHorizontalBarChart('failureChartFaultDetector', topFailuresData['فالت دتکتور'] || { labels: [], data: [] }, colors['فالت دتکتور'], 'فالت دتکتور');


});
</script>

@endsection

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

function generateLineChart(months, counts, maxVal) {
    const chartWidth = 1000, chartHeight = 350, paddingLeft = 60, paddingRight = 40, paddingTop = 30, paddingBottom = 60;
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
    for (let i = 0; i <= 4; i++) yLabels.push(Math.round((maxVal / 4) * i));
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

function updateChart(year, type) {
    const container = document.getElementById('chartBars');
    if (!container) return;
    container.innerHTML = '<div class="text-center p-5"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">در حال بارگذاری...</p></div>';
    fetch(`/api/advanced-dashboard/monthly?year=${year}`).then(res => res.json()).then(data => {
        if (!data.months || !data.counts) throw new Error('داده نامعتبر');
        const maxVal = Math.max(...data.counts, 1);
        if (type === 'bar') {
            container.style.display = 'flex'; container.style.alignItems = 'flex-end'; container.style.height = '350px'; container.style.gap = '8px'; container.style.overflowX = 'auto';
            let html = '';
            for (let i = 0; i < data.months.length; i++) {
                const height = (data.counts[i] / maxVal) * 280;
                html += `<div style="flex:1; min-width: 50px; text-align:center;"><div style="background: linear-gradient(to top, #3498db, #2980b9); height: ${height}px; border-radius: 8px 8px 0 0; transition: height 0.3s;"></div><div style="margin-top: 8px; font-size: 12px; transform: rotate(45deg); white-space: nowrap;">${data.months[i]}</div><div style="font-size: 13px; color: #e74c3c; font-weight: bold;">${data.counts[i]}</div></div>`;
            }
            container.innerHTML = html;
        } else {
            container.style.display = 'block'; container.style.height = 'auto';
            container.innerHTML = generateLineChart(data.months, data.counts, maxVal);
        }
        document.getElementById('currentYear').innerText = data.year || year;
    }).catch(err => { container.innerHTML = '<div class="text-center p-5 text-danger">❌ خطا در بارگذاری داده‌ها</div>'; });
}

const exportData = {
    monthlyInspections: @json($monthlyInspections ?? []),
    topContractors: @json($topContractors ?? []),
    okEquipments: @json($okEquipments->items() ?? []),
    failureEquipments: @json($failureEquipments->items() ?? []),
    priceList: @json($priceList ?? []),
    activities: @json($activities->items() ?? []),
    recentInspections: @json($recentInspections->items() ?? []),
    totalInspections: '{{ $totalInspections }}',
    totalCost: '{{ number_format($totalCost) }}',
    totalUsers: '{{ $totalUsers }}',
    inspectionsToday: '{{ number_format($inspectionsToday) }}',
    inspectionsThisMonth: '{{ number_format($inspectionsThisMonth) }}',
    totalCostThisMonth: '{{ number_format($totalCostThisMonth) }}',
    okEquipmentsCount: '{{ number_format($okEquipmentsCount) }}',
    notOkEquipmentsCount: '{{ number_format($notOkEquipmentsCount) }}',
    okPercent: '{{ number_format($okPercent, 1) }}',
    notOkPercent: '{{ number_format($notOkPercent, 1) }}',
    totalEquipments: '{{ $totalEquipments }}',
    completedInspections: '{{ $completedInspections }}',
    draftInspections: '{{ $draftInspections }}',
    completedPercent: '{{ number_format($completedPercent, 1) }}',
    draftPercent: '{{ number_format($draftPercent, 1) }}',
    archivedPercent: '{{ number_format($archivedPercent, 1) }}',
    statusArchived: '{{ $statusStats['archived'] ?? 0 }}',
};

async function exportToExcel() {
    try {
        Swal.fire({ title: 'در حال آماده‌سازی گزارش...', text: 'لطفاً صبر کنید', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
        const response = await fetch('{{ route("advanced.dashboard.export") }}');
        const data = await response.json();
        Swal.close();
        function safeText(v, m=100) { if (!v && v!==0) return '-'; let s=String(v); return s.length>m ? s.substring(0,m)+'...' : s; }
        const wb1 = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb1, XLSX.utils.aoa_to_sheet([['گزارش کلی داشبورد',''],['تاریخ:',new Date().toLocaleDateString('fa-IR')],['ساعت:',new Date().toLocaleTimeString('fa-IR')],[''],['آمار کلی',''],['تعداد کل بازدیدها',data.recentInspections.length],['تعداد پیمانکاران','{{ $stats['total_contractors'] ?? 0 }}'],['تعداد کاربران','{{ $totalUsers }}'],['بازدیدهای امروز','{{ number_format($inspectionsToday) }}'],['بازدیدهای این ماه','{{ number_format($inspectionsThisMonth) }}']]), 'آمار کلی');
        XLSX.writeFile(wb1, `1_گزارش_کلی_داشبورد_${new Date().toLocaleDateString('fa-IR')}.xlsx`);
        const wb2 = XLSX.utils.book_new();
        if (data.failureEquipments?.length) {
            const fd = [['گزارش تجهیزات خراب (Not OK)',''],['ردیف','نوع تجهیزات','کد SCADA','آیتم چک‌لیست خراب','توضیحات خرابی','تاریخ بازدید','پیمانکار','موقعیت','وضعیت']];
            data.failureEquipments.forEach((item,i)=>fd.push([i+1, safeText(item.equipment_type), safeText(item.scada_code), safeText(item.item), safeText(item.description), safeText(item.inspection_date), safeText(item.contractor_name), safeText(item.location), 'Not OK']));
            XLSX.utils.book_append_sheet(wb2, XLSX.utils.aoa_to_sheet(fd), 'گزارش خرابی (Not OK)');
        }
        if (data.okEquipments?.length) {
            const od = [['گزارش تجهیزات سالم (OK)',''],['ردیف','نوع تجهیزات','کد SCADA','تاریخ بازدید','پیمانکار','موقعیت','وضعیت']];
            data.okEquipments.forEach((item,i)=>od.push([i+1, safeText(item.equipment_type), safeText(item.scada_code), safeText(item.inspection_date), safeText(item.contractor_name), safeText(item.location), 'OK']));
            XLSX.utils.book_append_sheet(wb2, XLSX.utils.aoa_to_sheet(od), 'گزارش سالم (OK)');
        }
        XLSX.writeFile(wb2, `2_گزارش_چک‌لیست_${new Date().toLocaleDateString('fa-IR')}.xlsx`);
        const wb3 = XLSX.utils.book_new();
        if (data.priceList?.length) {
            const pd = [['فهرست بها',''],['ردیف','کد','عنوان فعالیت','واحد','قیمت واحد (ریال)','توضیحات']];
            data.priceList.forEach((item,i)=>pd.push([i+1, safeText(item.code), safeText(item.title), safeText(item.unit), item.unit_price?.toLocaleString() || '0', safeText(item.description)]));
            XLSX.utils.book_append_sheet(wb3, XLSX.utils.aoa_to_sheet(pd), 'فهرست بها');
        }
        if (data.activities?.length) {
            const ad = [['فعالیت‌های انجام شده در بازدیدها',''],['ردیف','کد فعالیت','عنوان فعالیت','واحد','قیمت واحد (ریال)','تعداد','مبلغ کل (ریال)','بازدید مربوطه']];
            data.activities.forEach((item,i)=>ad.push([i+1, safeText(item.code), safeText(item.title), safeText(item.unit), item.unit_price?.toLocaleString() || '0', item.quantity || 0, item.total?.toLocaleString() || '0', item.main_equipment?.inspection?.id || '-']));
            XLSX.utils.book_append_sheet(wb3, XLSX.utils.aoa_to_sheet(ad), 'فعالیت‌های بازدیدها');
        }
        XLSX.writeFile(wb3, `3_گزارش_فهرست_بها_و_فعالیت‌ها_${new Date().toLocaleDateString('fa-IR')}.xlsx`);
        Swal.fire({ icon: 'success', title: 'موفق!', text: 'سه فایل گزارش با موفقیت ذخیره شدند', confirmButtonText: 'باشه' });
    } catch(e) { Swal.close(); Swal.fire({ icon: 'error', title: 'خطا', text: 'خطا در ایجاد گزارش: ' + e.message }); }
}

$(document).ready(function() {
    $('.persian-date').persianDatepicker({ format: 'YYYY/MM/DD', autoClose: true, initialValue: false, calendar: { persian: { locale: 'fa' } } });
    $('#updateChart').on('click', function() { updateChart($('#yearFilter').val(), $('#chartType').val()); });
    $('#exportExcel').on('click', exportToExcel);
});
</script>
@endpush