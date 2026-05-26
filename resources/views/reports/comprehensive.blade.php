@extends('layouts.admin')

@section('title', 'گزارش جامع داشبورد')

@section('header', 'گزارش جامع')

@section('content')
<div class="container-fluid">
    <!-- صفحه عنوان -->
    <div class="page-title" data-aos="fade-down">
        <h1>گزارش جامع داشبورد</h1>
        <p>تحلیل کامل بازدیدهای تجهیزات اتوماسیون و عملکرد پیمانکاران</p>
    </div>

    <!-- فیلترهای پیشرفته -->
    <div class="filter-section" data-aos="fade-up">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-funnel"></i> فیلترهای پیشرفته</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('reports.comprehensive') }}" id="filterForm">
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
                            <select name="contractor_id" class="form-select">
                                <option value="">همه پیمانکاران</option>
                                @foreach($contractors ?? [] as $contractor)
                                    <option value="{{ $contractor->id }}" {{ request('contractor_id') == $contractor->id ? 'selected' : '' }}>
                                        {{ $contractor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
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
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">امور/شهرستان</label>
                            <select name="department_id" class="form-select">
                                <option value="">همه امورها</option>
                                @foreach($departments ?? [] as $department)
                                    <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">وضعیت</label>
                            <select name="status" class="form-select">
                                <option value="all">همه</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>تکمیل شده</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>پیش‌نویس</option>
                                <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>بایگانی شده</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">حداقل تعداد تجهیزات</label>
                            <input type="number" class="form-control" name="min_equipments" 
                                   value="{{ request('min_equipments') }}" placeholder="همه" autocomplete="off">
                        </div>
                        <div class="col-md-4 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-gradient w-100">
                                <i class="bi bi-search"></i> اعمال فیلتر
                            </button>
                            <a href="{{ route('reports.comprehensive') }}" class="btn btn-secondary w-100">
                                <i class="bi bi-x-circle"></i> حذف فیلترها
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- کارت‌های آمار کلی -->
    <div class="row mb-4">
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div class="stat-title">تعداد بازدیدها</div>
                <div class="stat-value">{{ number_format($totalInspections ?? 0) }}</div>
                <div class="stat-change">
                    <i class="bi bi-arrow-up"></i> نسبت به دوره قبل
                </div>
            </div>
        </div>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-hdd-stack"></i>
                </div>
                <div class="stat-title">تعداد تجهیزات</div>
                <div class="stat-value">{{ number_format($totalEquipments ?? 0) }}</div>
                <div class="stat-change">
                    میانگین {{ number_format(round(($totalEquipments ?? 0) / max($totalInspections ?? 0, 1))) }} تجهیز در هر بازدید
                </div>
            </div>
        </div>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-list-check"></i>
                </div>
                <div class="stat-title">کل فعالیت‌ها</div>
                <div class="stat-value">{{ number_format($totalActivities ?? 0) }}</div>
                <div class="stat-change">
                    میانگین {{ number_format(round(($totalActivities ?? 0) / max($totalEquipments ?? 0, 1))) }} فعالیت در هر تجهیز
                </div>
            </div>
        </div>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="400">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-cash-coin"></i>
                </div>
                <div class="stat-title">هزینه کل (ریال)</div>
                <div class="stat-value">{{ number_format($totalCost ?? 0) }}</div>
                <div class="stat-change">
                    میانگین {{ number_format(round(($totalCost ?? 0) / max($totalInspections ?? 0, 1))) }} ریال در هر بازدید
                </div>
            </div>
        </div>
    </div>

    <!-- نمودارهای اصلی -->
    <div class="row">
        <div class="col-md-8" data-aos="fade-up">
            <div class="chart-card">
                <h5><i class="bi bi-bar-chart"></i> روند بازدیدها در بازه زمانی</h5>
                <div style="position: relative; height: 300px;">
                    <canvas id="dailyChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
            <div class="chart-card">
                <h5><i class="bi bi-pie-chart"></i> وضعیت بازدیدها</h5>
                <div style="position: relative; height: 300px;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6" data-aos="fade-up">
            <div class="chart-card">
                <h5><i class="bi bi-people"></i> عملکرد پیمانکاران</h5>
                <div style="position: relative; height: 300px;">
                    <canvas id="contractorChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="chart-card">
                <h5><i class="bi bi-building"></i> عملکرد امورها</h5>
                <div style="position: relative; height: 300px;">
                    <canvas id="departmentChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12" data-aos="fade-up">
            <div class="chart-card">
                <h5><i class="bi bi-hdd-rack"></i> توزیع تجهیزات بر اساس نوع</h5>
                <div style="position: relative; height: 400px;">
                    <canvas id="equipmentTypeChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- دکمه بازگشت -->
    <div class="text-center mt-4">
        <a href="{{ route('reports.index') }}" class="btn btn-gradient">
            <i class="bi bi-arrow-right"></i> بازگشت به گزارشات
        </a>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/chart.js') }}"></script>
<script src="{{ asset('js/moment.min.js') }}"></script>
<script src="{{ asset('js/moment-jalaali.min.js') }}"></script>
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
    
    if (!$('#start_date').val()) {
        $('#start_date').val(persianOneMonthAgo.format('YYYY/MM/DD'));
    }
    if (!$('#end_date').val()) {
        $('#end_date').val(persianToday.format('YYYY/MM/DD'));
    }
    
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

// نمودار روند روزانه
const dailyCtx = document.getElementById('dailyChart').getContext('2d');
new Chart(dailyCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode(array_keys($dailyStats->toArray() ?? [])) !!},
        datasets: [
            {
                label: 'تعداد بازدیدها',
                data: {!! json_encode(array_column($dailyStats->toArray() ?? [], 'count')) !!},
                borderColor: '#3498db',
                backgroundColor: 'rgba(52,152,219,0.1)',
                tension: 0.4,
                fill: true,
                yAxisID: 'y'
            },
            {
                label: 'هزینه (میلیون ریال)',
                data: {!! json_encode(array_map(function($item) { 
                    return round(($item['cost'] ?? 0) / 1000000); 
                }, $dailyStats->toArray() ?? [])) !!},
                borderColor: '#27ae60',
                backgroundColor: 'rgba(39,174,96,0.1)',
                tension: 0.4,
                fill: true,
                yAxisID: 'y1'
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { position: 'top' }
        },
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                title: { display: true, text: 'تعداد بازدیدها' }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                title: { display: true, text: 'هزینه (میلیون ریال)' },
                grid: { drawOnChartArea: false }
            }
        }
    }
});

// نمودار وضعیت بازدیدها
const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['تکمیل شده', 'پیش‌نویس', 'بایگانی شده'],
        datasets: [{
            data: [{{ $statusStats['completed'] ?? 0 }}, {{ $statusStats['draft'] ?? 0 }}, {{ $statusStats['archived'] ?? 0 }}],
            backgroundColor: ['#27ae60', '#f39c12', '#95a5a6'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});

// نمودار پیمانکاران
const contractorCtx = document.getElementById('contractorChart').getContext('2d');
new Chart(contractorCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_keys($contractorStats->toArray() ?? [])) !!},
        datasets: [
            {
                label: 'تعداد بازدیدها',
                data: {!! json_encode(array_column($contractorStats->toArray() ?? [], 'count')) !!},
                backgroundColor: '#3498db',
                yAxisID: 'y'
            },
            {
                label: 'هزینه (میلیون ریال)',
                data: {!! json_encode(array_map(function($item) { 
                    return round(($item['cost'] ?? 0) / 1000000); 
                }, $contractorStats->toArray() ?? [])) !!},
                backgroundColor: '#27ae60',
                yAxisID: 'y1'
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'top' }
        },
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                title: { display: true, text: 'تعداد بازدیدها' }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                title: { display: true, text: 'هزینه (میلیون ریال)' },
                grid: { drawOnChartArea: false }
            }
        }
    }
});

// نمودار امورها
const departmentCtx = document.getElementById('departmentChart').getContext('2d');
new Chart(departmentCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_keys($departmentStats->toArray() ?? [])) !!},
        datasets: [{
            label: 'تعداد تجهیزات',
            data: {!! json_encode(array_column($departmentStats->toArray() ?? [], 'count')) !!},
            backgroundColor: '#f39c12'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'top' }
        }
    }
});

// نمودار انواع تجهیزات
const equipmentTypeCtx = document.getElementById('equipmentTypeChart').getContext('2d');
new Chart(equipmentTypeCtx, {
    type: 'pie',
    data: {
        labels: {!! json_encode(array_keys($equipmentTypeStats->toArray() ?? [])) !!},
        datasets: [{
            data: {!! json_encode(array_column($equipmentTypeStats->toArray() ?? [], 'count')) !!},
            backgroundColor: [
                '#3498db', '#2ecc71', '#f39c12', '#e74c3c', '#9b59b6', 
                '#1abc9c', '#34495e', '#e67e22', '#7f8c8d', '#16a085'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'right' }
        }
    }
});
</script>
@endpush
@endsection