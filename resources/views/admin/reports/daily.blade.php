@extends('layouts.admin')

@section('title', 'گزارش روزانه')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-calendar-day"></i> گزارش روزانه</h2>
        <div>
            <button class="btn btn-success" onclick="exportExcel()">
                <i class="bi bi-file-excel"></i> خروجی Excel
            </button>
            <button class="btn btn-danger" onclick="exportPDF()">
                <i class="bi bi-file-pdf"></i> خروجی PDF
            </button>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row">
                <div class="col-md-3">
                    <label>از تاریخ:</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date', now()->startOfMonth()->format('Y-m-d')) }}">
                </div>
                <div class="col-md-3">
                    <label>تا تاریخ:</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date', now()->format('Y-m-d')) }}">
                </div>
                <div class="col-md-2">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary d-block">نمایش</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>خلاصه بازدیدها</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>تاریخ</th>
                        <th>تعداد بازدید</th>
                        <th>تعداد تجهیزات</th>
                        <th>جمع فعالیت‌ها</th>
                        <th>جمع هزینه</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dailyReports ?? [] as $report)
                    <tr>
                        <td>{{ $report->date }}</td>
                        <td>{{ $report->inspections_count }}</td>
                        <td>{{ $report->equipments_count }}</td>
                        <td>{{ number_format($report->activities_total) }}</td>
                        <td>{{ number_format($report->total_cost) }} ریال</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                            <p class="mt-2">داده‌ای برای نمایش وجود ندارد</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function exportExcel() {
    window.location.href = '{{ route("admin.reports.daily.excel") }}' + window.location.search;
}
function exportPDF() {
    window.location.href = '{{ route("admin.reports.daily.pdf") }}' + window.location.search;
}
</script>
@endpush