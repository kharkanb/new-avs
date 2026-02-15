@extends('layouts.admin')

@section('title', 'گزارش ماهانه')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-calendar-month"></i> گزارش ماهانه بازدیدها</h2>
        <div>
            <button class="btn btn-success me-2" onclick="exportExcel()">
                <i class="bi bi-file-excel"></i> خروجی Excel
            </button>
            <button class="btn btn-danger" onclick="exportPDF()">
                <i class="bi bi-file-pdf"></i> خروجی PDF
            </button>
        </div>
    </div>

    <!-- انتخاب سال و ماه -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reports.monthly') }}" class="row">
                <div class="col-md-3">
                    <label class="form-label">سال</label>
                    <select name="year" class="form-control">
                        @for($y = now()->year; $y >= now()->year - 5; $y--)
                            <option value="{{ $y }}" {{ request('year', now()->year) == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">ماه</label>
                    <select name="month" class="form-control">
                        @foreach(['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 
                                 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'] as $index => $monthName)
                            <option value="{{ $index + 1 }}" {{ request('month', now()->month) == $index + 1 ? 'selected' : '' }}>
                                {{ $monthName }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> نمایش گزارش
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- کارت‌های آمار ماهانه -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6>کل بازدیدها</h6>
                    <h3>{{ $monthlyStats['total_inspections'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6>کل تجهیزات</h6>
                    <h3>{{ $monthlyStats['total_equipments'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6>کل فعالیت‌ها</h6>
                    <h3>{{ $monthlyStats['total_activities'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6>هزینه کل</h6>
                    <h3>{{ number_format($monthlyStats['total_cost'] ?? 0) }} ریال</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- نمودار ماهانه (با Chart.js) -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>نمودار بازدیدهای روزانه در ماه جاری</h5>
        </div>
        <div class="card-body">
            <canvas id="dailyChart" style="height: 300px;"></canvas>
        </div>
    </div>

    <!-- جدول توزیع توسط کارشناسان -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>توزیع بازدیدها توسط کارشناسان</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>کارشناس</th>
                                <th>تعداد بازدید</th>
                                <th>تجهیزات</th>
                                <th>درصد</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($techStats ?? [] as $tech)
                            <tr>
                                <td>{{ $tech->name }}</td>
                                <td class="text-center">{{ $tech->inspections_count }}</td>
                                <td class="text-center">{{ $tech->equipments_count }}</td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: {{ $tech->percentage }}%">
                                            {{ $tech->percentage }}%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- جدول پرتکرارترین تجهیزات -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>پرتکرارترین تجهیزات بازدید شده</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>نوع تجهیز</th>
                                <th>تعداد</th>
                                <th>درصد</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topEquipments ?? [] as $equipment)
                            <tr>
                                <td>{{ $equipment->equipment_type }}</td>
                                <td class="text-center">{{ $equipment->count }}</td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" style="width: {{ $equipment->percentage }}%">
                                            {{ $equipment->percentage }}%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- خلاصه فعالیت‌های ماهانه -->
    <div class="card">
        <div class="card-header">
            <h5>خلاصه فعالیت‌های انجام شده</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>کد فعالیت</th>
                        <th>عنوان فعالیت</th>
                        <th>تعداد دفعات</th>
                        <th>جمع تعداد</th>
                        <th>جمع مبلغ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activitySummary ?? [] as $activity)
                    <tr>
                        <td>{{ $activity->code }}</td>
                        <td>{{ $activity->title }}</td>
                        <td class="text-center">{{ $activity->occurrences }}</td>
                        <td class="text-center">{{ $activity->total_quantity }}</td>
                        <td class="text-end">{{ number_format($activity->total_amount) }} ریال</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('dailyChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($chartLabels ?? []) !!},
        datasets: [{
            label: 'تعداد بازدید',
            data: {!! json_encode($chartData ?? []) !!},
            borderColor: '#3498db',
            backgroundColor: 'rgba(52, 152, 219, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

function exportExcel() {
    const params = new URLSearchParams(window.location.search);
    window.location.href = '{{ route("admin.reports.monthly.excel") }}?' + params.toString();
}

function exportPDF() {
    const params = new URLSearchParams(window.location.search);
    window.location.href = '{{ route("admin.reports.monthly.pdf") }}?' + params.toString();
}
</script>
@endpush