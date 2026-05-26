@extends('layouts.admin')

@section('title', 'گزارش ماهانه')

@section('content')
<div class="container-fluid">
    <!-- فیلترها -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-funnel"></i> فیلترهای گزارش ماهانه</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.monthly') }}" id="filterForm">
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
                    <div class="col-md-3 mb-3">
                        <label class="form-label">وضعیت</label>
                        <select name="status" class="form-select">
                            <option value="all">همه</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>تکمیل شده</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>پیش‌نویس</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> اعمال فیلتر
                        </button>
                        <a href="{{ route('reports.monthly') }}" class="btn btn-secondary">
                            <i class="bi bi-eraser"></i> حذف فیلترها
                        </a>
                        <button type="button" class="btn btn-success" onclick="window.print()">
                            <i class="bi bi-printer"></i> چاپ
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @php
        use App\Models\Inspection;
        use Hekmatinasser\Verta\Verta;
        
        $startDate = request('start_date');
        $endDate = request('end_date');
        $contractorId = request('contractor_id');
        $equipmentType = request('equipment_type');
        $departmentId = request('department_id');
        $status = request('status');
        
        $query = Inspection::with(['user', 'contractor', 'mainEquipments.activities', 'mainEquipments.type', 'mainEquipments.department']);
        
        if ($startDate) {
            try {
                $start = Verta::parse($startDate)->datetime();
                $query->whereDate('inspection_date', '>=', $start);
            } catch (\Exception $e) {
                $query->whereDate('inspection_date', '>=', $startDate);
            }
        } else {
            $now = Verta::now();
            $startOfMonth = $now->startMonth()->datetime();
            $endOfMonth = $now->endMonth()->datetime();
            $query->whereBetween('inspection_date', [$startOfMonth, $endOfMonth]);
        }
        
        if ($endDate) {
            try {
                $end = Verta::parse($endDate)->datetime();
                $query->whereDate('inspection_date', '<=', $end);
            } catch (\Exception $e) {
                $query->whereDate('inspection_date', '<=', $endDate);
            }
        }
        
        if ($contractorId) {
            $query->where('contractor_id', $contractorId);
        }
        
        if ($departmentId) {
            $query->whereHas('mainEquipments', function($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }
        
        if ($status && $status != 'all') {
            $query->where('status', $status);
        }
        
        if ($equipmentType) {
            $query->whereHas('mainEquipments', function($q) use ($equipmentType) {
                $q->where('main_equipment_type_id', $equipmentType);
            });
        }
        
        $inspections = $query->get();
        
        $persianMonths = [
            1 => 'فروردین', 2 => 'اردیبهشت', 3 => 'خرداد', 4 => 'تیر',
            5 => 'مرداد', 6 => 'شهریور', 7 => 'مهر', 8 => 'آبان',
            9 => 'آذر', 10 => 'دی', 11 => 'بهمن', 12 => 'اسفند'
        ];
        
        if ($startDate && $endDate) {
            $displayDate = "از $startDate تا $endDate";
        } elseif ($startDate) {
            $displayDate = "از $startDate به بعد";
        } else {
            $now = Verta::now();
            $displayDate = $persianMonths[$now->month] . " " . $now->year;
        }
        
        $totalInspections = $inspections->count();
        $totalEquipments = $inspections->sum(function($inspection) {
            return $inspection->mainEquipments->count();
        });
        
        $activeContractors = $inspections->pluck('contractor_id')->unique()->filter()->count();
        
        $totalCost = $inspections->flatMap->mainEquipments
            ->flatMap->activities
            ->sum('total');
        
        $contractorStats = $inspections->groupBy(function($inspection) {
            return $inspection->contractor->name ?? 'نامشخص';
        })->map(function($items) {
            return [
                'count' => $items->count(),
                'equipments' => $items->sum(function($i) {
                    return $i->mainEquipments->count();
                }),
                'cost' => $items->sum(function($i) {
                    return $i->mainEquipments->sum(function($e) {
                        return $e->activities->sum('total');
                    });
                })
            ];
        });
        
        $weeklyStats = [0, 0, 0, 0];
        foreach ($inspections as $inspection) {
            $date = verta($inspection->inspection_date);
            $day = $date->day;
            
            if ($day <= 7) $weeklyStats[0]++;
            elseif ($day <= 14) $weeklyStats[1]++;
            elseif ($day <= 21) $weeklyStats[2]++;
            else $weeklyStats[3]++;
        }
        
        function toPersianNumber($num) {
            if (!$num && $num !== 0) return '۰';
            $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
            $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
            return str_replace($english, $persian, number_format($num));
        }
    @endphp

    <!-- گزارش اصلی -->
    <div class="card">
        <div class="card-header text-center">
            <h4>گزارش ماهانه بازدیدها</h4>
            <p class="text-muted">{{ $displayDate }}</p>
        </div>
        <div class="card-body">
            <!-- آمار کلی -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-white bg-primary">
                        <div class="card-body text-center">
                            <i class="bi bi-calendar-check" style="font-size: 2rem;"></i>
                            <h2 class="mb-0">{{ toPersianNumber($totalInspections) }}</h2>
                            <p>تعداد بازدیدها</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success">
                        <div class="card-body text-center">
                            <i class="bi bi-hdd-stack" style="font-size: 2rem;"></i>
                            <h2 class="mb-0">{{ toPersianNumber($totalEquipments) }}</h2>
                            <p>تعداد تجهیزات بازدید شده</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-warning">
                        <div class="card-body text-center">
                            <i class="bi bi-people" style="font-size: 2rem;"></i>
                            <h2 class="mb-0">{{ toPersianNumber($activeContractors) }}</h2>
                            <p>پیمانکاران فعال</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-info">
                        <div class="card-body text-center">
                            <i class="bi bi-cash-coin" style="font-size: 2rem;"></i>
                            <h2 class="mb-0">{{ toPersianNumber($totalCost) }}</h2>
                            <p>هزینه کل (ریال)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- نمودار -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <h5 class="mb-3">توزیع بازدیدها در هفته‌های ماه</h5>
                    <div style="position: relative; height: 300px;">
                        <canvas id="weeklyChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- جدول پیمانکاران -->
            <h5 class="mb-3">آمار بازدیدها بر اساس پیمانکار</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>پیمانکار</th>
                            <th>تعداد بازدید</th>
                            <th>تعداد تجهیزات</th>
                            <th>هزینه (ریال)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contractorStats as $contractorName => $stats)
                        <tr>
                            <td>{{ $contractorName }}</td>
                            <td class="text-center">{{ toPersianNumber($stats['count']) }}</td>
                            <td class="text-center">{{ toPersianNumber($stats['equipments']) }}</td>
                            <td class="text-start">{{ toPersianNumber($stats['cost']) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <p class="mt-3">هیچ بازدیدی با این فیلترها یافت نشد</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/persian-date.min.js') }}"></script>
<script src="{{ asset('js/persian-datepicker.min.js') }}"></script>
<link href="{{ asset('css/persian-datepicker.min.css') }}" rel="stylesheet">
<script src="{{ asset('js/chart.umd.min.js') }}"></script>

<script>
$(document).ready(function() {
    $('.persian-date').persianDatepicker({
        format: 'YYYY/MM/DD',
        autoClose: true,
        initialValue: false,
        calendar: { persian: { locale: 'fa' } }
    });
});

const ctx = document.getElementById('weeklyChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['هفته اول', 'هفته دوم', 'هفته سوم', 'هفته چهارم'],
        datasets: [{
            label: 'تعداد بازدیدها',
            data: [{{ $weeklyStats[0] }}, {{ $weeklyStats[1] }}, {{ $weeklyStats[2] }}, {{ $weeklyStats[3] }}],
            backgroundColor: '#3498db',
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'top' }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1,
                    callback: function(value) {
                        return value.toLocaleString('fa-IR');
                    }
                }
            }
        }
    }
});
</script>
@endpush
@endsection