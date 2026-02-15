@extends('layouts.admin')

@section('title', 'گزارش روزانه')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-calendar-day"></i> گزارش روزانه بازدیدها</h2>
        <div>
            <button class="btn btn-success me-2" onclick="exportExcel()">
                <i class="bi bi-file-excel"></i> خروجی Excel
            </button>
            <button class="btn btn-danger" onclick="exportPDF()">
                <i class="bi bi-file-pdf"></i> خروجی PDF
            </button>
        </div>
    </div>

    <!-- فیلتر تاریخ -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reports.daily') }}" class="row">
                <div class="col-md-4">
                    <label class="form-label">از تاریخ</label>
                    <input type="date" name="from_date" class="form-control" 
                           value="{{ request('from_date', now()->startOfMonth()->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">تا تاریخ</label>
                    <input type="date" name="to_date" class="form-control" 
                           value="{{ request('to_date', now()->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> نمایش گزارش
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- آمار کلی -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6>کل بازدیدها</h6>
                    <h3>{{ $totalInspections ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6>کل تجهیزات</h6>
                    <h3>{{ $totalEquipments ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6>کل فعالیت‌ها</h6>
                    <h3>{{ $totalActivities ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6>هزینه کل</h6>
                    <h3>{{ number_format($totalCost ?? 0) }} ریال</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- جدول گزارش روزانه -->
    <div class="card">
        <div class="card-header">
            <h5>جزئیات بازدیدهای روزانه</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>تاریخ بازدید</th>
                        <th>کارشناس</th>
                        <th>پیمانکار</th>
                        <th>تعداد تجهیزات</th>
                        <th>فعالیت‌ها</th>
                        <th>مصرفی‌ها</th>
                        <th>هزینه (بدون ضریب)</th>
                        <th>هزینه نهایی</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inspections ?? [] as $inspection)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $inspection->inspection_date }}</td>
                        <td>{{ $inspection->user->name ?? '---' }}</td>
                        <td>{{ $inspection->contractor }}</td>
                        <td class="text-center">{{ $inspection->equipments_count ?? 0 }}</td>
                        <td class="text-center">{{ $inspection->activities_count ?? 0 }}</td>
                        <td class="text-center">{{ $inspection->consumables_count ?? 0 }}</td>
                        <td class="text-end">{{ number_format($inspection->total_cost ?? 0) }}</td>
                        <td class="text-end">{{ number_format(($inspection->total_cost ?? 0) * ($inspection->contract_coefficient ?? 1)) }}</td>
                        <td>
                            <a href="{{ route('inspections.show', $inspection->id) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                            <p class="mt-3">هیچ بازدیدی در این بازه یافت نشد</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-secondary">
                    <tr>
                        <td colspan="7" class="text-end"><strong>جمع کل:</strong></td>
                        <td class="text-end"><strong>{{ number_format($totalCost ?? 0) }} ریال</strong></td>
                        <td class="text-end"><strong>{{ number_format($finalCost ?? 0) }} ریال</strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            
            <div class="mt-3">
                {{ $inspections->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function exportExcel() {
    const params = new URLSearchParams(window.location.search);
    window.location.href = '{{ route("admin.reports.daily.excel") }}?' + params.toString();
}

function exportPDF() {
    const params = new URLSearchParams(window.location.search);
    window.location.href = '{{ route("admin.reports.daily.pdf") }}?' + params.toString();
}
</script>
@endpush