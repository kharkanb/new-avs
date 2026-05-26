@extends('layouts.admin')

@section('title', 'داشبورد')

@section('content')
<div class="container-fluid">
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

    <!-- نمودارها -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-graph-up"></i> آمار بازدیدهای ماهانه</h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" style="width: 100%; height: 350px;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-pie-chart"></i> وضعیت بازدیدها</h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" style="width: 100%; height: 350px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- جدول آخرین بازدیدها -->
    <div class="card mt-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-clock-history"></i> آخرین بازدیدها</h5>
            <a href="{{ route('dashboard.inspections') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-arrow-left"></i> مشاهده همه
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>تاریخ بازدید</th>
                            <th>پیمانکار</th>
                            <th>امور/شهرستان</th>
                            <th>تعداد تجهیزات</th>
                            <th>وضعیت</th>
                            <th>هزینه (ریال)</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentInspections ?? [] as $index => $inspection)
                        @php
                            $jalaliDate = $inspection->inspection_date;
                            try {
                                if (class_exists('Hekmatinasser\Verta\Verta')) {
                                    $jalaliDate = \Hekmatinasser\Verta\Verta::instance($inspection->inspection_date)->format('Y/m/d');
                                }
                            } catch (\Exception $e) {}
                            
                            $departmentName = '-';
                            if ($inspection->mainEquipments && $inspection->mainEquipments->isNotEmpty()) {
                                $firstEquipment = $inspection->mainEquipments->first();
                                if ($firstEquipment && $firstEquipment->department) {
                                    $departmentName = $firstEquipment->department->name;
                                }
                            }
                            
                            $statusClass = 'secondary';
                            $statusText = 'نامشخص';
                            if ($inspection->status == 'completed') {
                                $statusClass = 'success';
                                $statusText = 'تکمیل شده';
                            } elseif ($inspection->status == 'draft') {
                                $statusClass = 'warning';
                                $statusText = 'پیش‌نویس';
                            }
                            
                            $cost = $inspection->total_cost ?? 0;
                        @endphp
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $jalaliDate }}</td>
                            <td>{{ $inspection->contractor->name ?? $inspection->contractor ?? '-' }}</td>
                            <td>{{ $departmentName }}</td>
                            <td class="text-center">{{ $inspection->mainEquipments->count() ?? 0 }}</td>
                            <td><span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span></td>
                            <td>{{ number_format($cost) }}</td>
                            <td>
                                <a href="{{ route('inspection.show', $inspection->id) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">هیچ بازدیدی یافت نشد</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/chart.umd.min.js') }}"></script>

<script>
$(document).ready(function() {
    const monthlyCtx = document.getElementById('monthlyChart');
    if (monthlyCtx) {
        new Chart(monthlyCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($monthlyLabels ?? []) !!},
                datasets: [{
                    label: 'تعداد بازدیدها',
                    data: {!! json_encode($monthlyData ?? []) !!},
                    backgroundColor: '#3498db',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });
    }

    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        new Chart(statusCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['تکمیل شده', 'پیش‌نویس', 'بایگانی شده'],
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
    }
});
</script>
@endpush