@extends('layouts.admin')

@section('title', 'گزارش‌های نموداری')

@section('content')
<div class="container-fluid">
    
    <!-- هدر صفحه با نمایش تاریخ -->
    <div class="card mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="bi bi-graph-up"></i> نمودارهای آماری</h5>
            <small class="text-muted">{{ $displayDate ?? '' }}</small>
        </div>
    </div>

    <!-- فیلترها -->
    <div class="filter-section">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-funnel"></i> فیلترهای پیشرفته</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('reports.charts') }}" id="filterForm">
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
                                @foreach($equipmentTypesList ?? [] as $type)
                                    <option value="{{ $type->id }}" {{ request('equipment_type_id') == $type->id ? 'selected' : '' }}>
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
                            <a href="{{ route('reports.charts') }}" class="btn btn-secondary w-100">
                                <i class="bi bi-x-circle"></i> حذف فیلترها
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- نمودارها -->
    <div class="row">
        <!-- نمودار 1: روند بازدیدهای ماهانه -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">روند بازدیدهای ماهانه</h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" style="width: 100%; height: 350px;"></canvas>
                </div>
            </div>
        </div>

        <!-- نمودار 2: توزیع تجهیزات -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">توزیع تجهیزات بر اساس نوع</h5>
                </div>
                <div class="card-body">
                    <canvas id="equipmentChart" style="width: 100%; height: 350px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- نمودار 3: وضعیت بازدیدها -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">وضعیت بازدیدها</h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" style="width: 100%; height: 350px;"></canvas>
                </div>
            </div>
        </div>

        <!-- نمودار 4: پرتکرارترین فعالیت‌ها -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">پرتکرارترین فعالیت‌ها</h5>
                </div>
                <div class="card-body">
                    <canvas id="activitiesChart" style="width: 100%; height: 350px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- نمودار 5: عملکرد پیمانکاران -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">عملکرد پیمانکاران</h5>
                </div>
                <div class="card-body">
                    <canvas id="contractorChart" style="width: 100%; height: 350px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- دکمه بازگشت -->
    <div class="text-center mt-4">
        <a href="{{ route('reports.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-right"></i> بازگشت به گزارشات
        </a>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/chart.umd.min.js') }}"></script>
<script src="{{ asset('js/persian-date.min.js') }}"></script>
<script src="{{ asset('js/persian-datepicker.min.js') }}"></script>
<link href="{{ asset('css/persian-datepicker.min.css') }}" rel="stylesheet">

<script>
$(document).ready(function() {
    $('.persian-date').persianDatepicker({
        format: 'YYYY/MM/DD',
        autoClose: true,
        initialValue: false,
        calendar: { persian: { locale: 'fa' } }
    });
});

// نمودار 1: روند بازدیدهای ماهانه
const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
new Chart(monthlyCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($monthlyLabels ?? []) !!},
        datasets: [{
            label: 'تعداد بازدیدها',
            data: {!! json_encode($monthlyData ?? []) !!},
            borderColor: '#3498db',
            backgroundColor: 'rgba(52,152,219,0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } }
    }
});

// نمودار 2: توزیع تجهیزات
const equipmentCtx = document.getElementById('equipmentChart').getContext('2d');
new Chart(equipmentCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($equipmentLabels ?? []) !!},
        datasets: [{
            data: {!! json_encode($equipmentData ?? []) !!},
            backgroundColor: ['#3498db', '#2ecc71', '#f39c12', '#e74c3c', '#9b59b6']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom' } }
    }
});

// نمودار 3: وضعیت بازدیدها
const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'pie',
    data: {
        labels: ['تکمیل شده', 'پیش‌نویس', 'بایگانی'],
        datasets: [{
            data: [{{ $statusStats['completed'] ?? 0 }}, {{ $statusStats['draft'] ?? 0 }}, {{ $statusStats['archived'] ?? 0 }}],
            backgroundColor: ['#27ae60', '#f39c12', '#95a5a6']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom' } }
    }
});

// نمودار 4: پرتکرارترین فعالیت‌ها
const activitiesCtx = document.getElementById('activitiesChart').getContext('2d');
new Chart(activitiesCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($activityLabels ?? []) !!},
        datasets: [{
            label: 'تعداد تکرار',
            data: {!! json_encode($activityData ?? []) !!},
            backgroundColor: '#3498db',
            borderRadius: 8
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } }
    }
});

// نمودار 5: عملکرد پیمانکاران
const contractorCtx = document.getElementById('contractorChart').getContext('2d');
new Chart(contractorCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($contractorLabels ?? []) !!},
        datasets: [{
            label: 'تعداد بازدیدها',
            data: {!! json_encode($contractorData ?? []) !!},
            backgroundColor: '#f39c12',
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } }
    }
});
</script>
@endpush
@endsection