@extends('layouts.admin')

@section('title', 'گزارش تجهیزات')

@section('content')
<div class="container">
    <h2 class="mb-4"><i class="bi bi-hdd-stack"></i> گزارش جامع تجهیزات</h2>

    <!-- فیلترها -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reports.equipments') }}" class="row">
                <div class="col-md-3">
                    <label class="form-label">نوع تجهیز</label>
                    <select name="equipment_type" class="form-control">
                        <option value="">همه انواع</option>
                        <option value="ریکلوزر">ریکلوزر</option>
                        <option value="سکسیونر">سکسیونر</option>
                        <option value="سکشنالایزر">سکشنالایزر</option>
                        <option value="فالت دتکتور">فالت دتکتور</option>
                        <option value="پست دو سو تغذیه">پست دو سو تغذیه</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">برند</label>
                    <select name="brand" class="form-control">
                        <option value="">همه برندها</option>
                        <option value="ABB">ABB</option>
                        <option value="Schneider">Schneider</option>
                        <option value="Siemens">Siemens</option>
                        <option value="NOJA">NOJA</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">از تاریخ</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">تا تاریخ</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel"></i> اعمال فیلتر
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- آمار کلی تجهیزات -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6>کل تجهیزات</h6>
                    <h3>{{ $stats['total'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6>تجهیزات فعال</h6>
                    <h3>{{ $stats['active'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6>تجهیزات با چک‌لیست کامل</h6>
                    <h3>{{ $stats['complete_checklist'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6>نیازمند توجه</h6>
                    <h3>{{ $stats['needs_attention'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- نمودار توزیع تجهیزات -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>توزیع تجهیزات بر اساس نوع</h5>
                </div>
                <div class="card-body">
                    <canvas id="typeChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>توزیع تجهیزات بر اساس برند</h5>
                </div>
                <div class="card-body">
                    <canvas id="brandChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- جدول جزئیات تجهیزات -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>لیست تجهیزات</h5>
            <button class="btn btn-sm btn-success" onclick="exportEquipmentsExcel()">
                <i class="bi bi-file-excel"></i> خروجی Excel
            </button>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover" id="equipmentsTable">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>نوع تجهیز</th>
                        <th>کد اسکادا</th>
                        <th>پست</th>
                        <th>برند</th>
                        <th>تعداد بازدید</th>
                        <th>آخرین بازدید</th>
                        <th>وضعیت چک‌لیست</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($equipments ?? [] as $equipment)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $equipment->equipment_type }}</td>
                        <td>{{ $equipment->scada_code ?? '---' }}</td>
                        <td>{{ $equipment->post->name ?? '---' }}</td>
                        <td>{{ $equipment->switch_brand ?? '---' }}</td>
                        <td class="text-center">{{ $equipment->inspections_count ?? 0 }}</td>
                        <td>{{ $equipment->last_inspection_date ?? '---' }}</td>
                        <td>
                            @php
                                $checklistPercent = $equipment->checklist_completion ?? 0;
                                $color = $checklistPercent >= 80 ? 'success' : ($checklistPercent >= 50 ? 'warning' : 'danger');
                            @endphp
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-{{ $color }}" style="width: {{ $checklistPercent }}%">
                                    {{ $checklistPercent }}%
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('tech.equipments.show', $equipment->id) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                            <p class="mt-3">هیچ تجهیزی یافت نشد</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="mt-3">
                {{ $equipments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// نمودار نوع تجهیزات
const typeCtx = document.getElementById('typeChart').getContext('2d');
new Chart(typeCtx, {
    type: 'pie',
    data: {
        labels: {!! json_encode($typeLabels ?? []) !!},
        datasets: [{
            data: {!! json_encode($typeData ?? []) !!},
            backgroundColor: [
                '#3498db', '#2ecc71', '#f1c40f', '#e74c3c', '#9b59b6',
                '#1abc9c', '#e67e22', '#34495e', '#7f8c8d', '#16a085'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// نمودار برندها
const brandCtx = document.getElementById('brandChart').getContext('2d');
new Chart(brandCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($brandLabels ?? []) !!},
        datasets: [{
            label: 'تعداد تجهیزات',
            data: {!! json_encode($brandData ?? []) !!},
            backgroundColor: '#3498db'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
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

function exportEquipmentsExcel() {
    const params = new URLSearchParams(window.location.search);
    window.location.href = '{{ route("admin.reports.equipments.excel") }}?' + params.toString();
}
</script>
@endpush