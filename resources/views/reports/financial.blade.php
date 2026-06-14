@extends('layouts.admin')

@section('title', 'گزارش مالی صورت وضعیت')

@section('content')

<div class="container-fluid">

    <!-- هدر -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h2 class="mb-0">💰 گزارش مالی صورت وضعیت</h2>
                    <p class="text-muted mt-2">خلاصه مالی و صورت وضعیت کلیه بازدیدها</p>
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

<!-- فیلترها -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('reports.financial') }}" class="row g-3 align-items-end" id="filterForm">
            <div class="col-md-3">
                <label class="form-label fw-bold">از تاریخ (شمسی)</label>
                <input type="text" name="date_from" class="form-control persian-date" 
                       value="{{ request('date_from', $dateFrom ?? '') }}" placeholder="1400/01/01" autocomplete="off">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">تا تاریخ (شمسی)</label>
                <input type="text" name="date_to" class="form-control persian-date" 
                       value="{{ request('date_to', $dateTo ?? '') }}" placeholder="1400/12/29" autocomplete="off">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-bold">پیمانکار</label>
                <select name="contractor_id" class="form-select">
                    <option value="">همه پیمانکاران</option>
                    @foreach($contractors ?? [] as $contractor)
                        <option value="{{ $contractor->id }}" {{ (request('contractor_id', $contractorId ?? '') == $contractor->id) ? 'selected' : '' }}>
                            {{ $contractor->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-bold">گروه‌بندی</label>
                <select name="group_by" class="form-select">
                    <option value="contractor" {{ request('group_by', 'contractor') == 'contractor' ? 'selected' : '' }}>بر اساس پیمانکار</option>
                    <option value="activity" {{ request('group_by') == 'activity' ? 'selected' : '' }}>بر اساس فعالیت</option>
                </select>
            </div>
            <div class="col-md-2">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="bi bi-funnel"></i> اعمال فیلتر
                    </button>
                    <a href="{{ route('reports.financial') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> حذف فیلترها
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- کارت‌های آماری ردیف اول -->
<div class="row g-4 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="card bg-primary text-white shadow-sm h-100">
            <div class="card-body text-center">
                <i class="bi bi-calendar-check fs-1"></i>
                <h6 class="mt-2 mb-1">تعداد بازدیدها</h6>
                <h3 class="mb-0">{{ number_format($totalInspections ?? 0) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card bg-info text-white shadow-sm h-100">
            <div class="card-body text-center">
                <i class="bi bi-cash-stack fs-1"></i>
                <h6 class="mt-2 mb-1">هزینه کل (بدون ضریب)</h6>
                <h5 class="mb-0">{{ number_format($totalCostAll ?? 0) }} <small>ریال</small></h5>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card bg-success text-white shadow-sm h-100">
            <div class="card-body text-center">
                <i class="bi bi-graph-up fs-1"></i>
                <h6 class="mt-2 mb-1">هزینه نهایی</h6>
                <h5 class="mb-0">{{ number_format($totalFinalCost ?? 0) }} <small>ریال</small></h5>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card bg-dark text-white shadow-sm h-100">
            <div class="card-body text-center">
                <i class="bi bi-people fs-1"></i>
                <h6 class="mt-2 mb-1">تعداد پیمانکاران فعال</h6>
                <h3 class="mb-0">{{ number_format($contractorStats->count() ?? 0) }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- کارت‌های آماری ردیف دوم -->
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card bg-secondary text-white shadow-sm h-100">
            <div class="card-body text-center">
                <i class="bi bi-calculator fs-1"></i>
                <h6 class="mt-2 mb-1">میانگین هزینه هر بازدید</h6>
                <h4 class="mb-0">{{ number_format($avgCostPerInspection ?? 0) }} <small>ریال</small></h4>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-danger text-white shadow-sm h-100">
            <div class="card-body text-center">
                <i class="bi bi-box-seam fs-1"></i>
                <h6 class="mt-2 mb-1">تعداد کل تجهیزات بازدید شده</h6>
                <h4 class="mb-0">{{ number_format($totalEquipments ?? 0) }} <small>دستگاه</small></h4>
            </div>
        </div>
    </div>
</div>

<!-- صورت وضعیت نهایی بر اساس پیمانکار -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="bi bi-receipt"></i> صورت وضعیت نهایی بر اساس پیمانکار</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ردیف</th>
                        <th>نام پیمانکار</th>
                        <th>تعداد بازدید</th>
                        <th>ضریب قرارداد</th>
                        <th>هزینه بدون ضریب (ریال)</th>
                        <th>هزینه نهایی (ریال)</th>
                        <th>میانگین هزینه هر بازدید</th>
                        <th>جزئیات</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $totalInspectionsCount = 0;
                        $totalCostAll = 0; 
                        $totalFinalAll = 0; 
                    @endphp
                    
                    @forelse($contractorStats ?? [] as $index => $contractor)
                    @php
                        $totalInspectionsCount += $contractor->inspections_count;
                        $totalCostAll += $contractor->total_cost;
                        $finalForContractor = $contractor->total_cost * ($contractor->coefficient ?? 2.35);
                        $totalFinalAll += $finalForContractor;
                        $avgCost = $contractor->inspections_count > 0 ? $contractor->total_cost / $contractor->inspections_count : 0;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td><strong>{{ $contractor->contractor_name ?? 'نامشخص' }}</strong></td>
                        <td class="text-center">{{ number_format($contractor->inspections_count) }}</td>
                        <td class="text-center">
                            <span class="badge bg-warning text-dark">{{ number_format($contractor->coefficient ?? 2.35, 2) }}</span>
                        </td>
                        <td class="text-end">{{ number_format($contractor->total_cost) }} ریال</td>
                        <td class="text-end text-success fw-bold">{{ number_format($finalForContractor) }} ریال</td>
                        <td class="text-end">{{ number_format($avgCost) }} ریال</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-info" type="button" data-bs-toggle="collapse" data-bs-target="#details-{{ $index }}">
                                <i class="bi bi-eye"></i>
                            </button>
                        </td>
                    </tr>
                    <tr class="collapse" id="details-{{ $index }}">
                        <td colspan="8">
                            <div class="p-3 bg-light">
                                <h6 class="mb-3">📋 لیست بازدیدهای {{ $contractor->contractor_name }}</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>ردیف</th>
                                                <th>تاریخ بازدید</th>
                                                <th>تعداد تجهیزات</th>
                                                <th>هزینه (ریال)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $inspections = App\Models\Inspection::where('contractor_id', $contractor->contractor_id)
                                                    ->when(request('date_from'), function($q) {
                                                        $from = \Hekmatinasser\Verta\Verta::parse(request('date_from'))->datetime();
                                                        return $q->whereDate('inspection_date', '>=', $from);
                                                    })
                                                    ->when(request('date_to'), function($q) {
                                                        $to = \Hekmatinasser\Verta\Verta::parse(request('date_to'))->datetime();
                                                        return $q->whereDate('inspection_date', '<=', $to);
                                                    })
                                                    ->get();
                                            @endphp
                                            @forelse($inspections as $inspection)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="text-center">{{ verta($inspection->inspection_date)->format('Y/m/d') }}</td>
                                                <td class="text-center">{{ $inspection->mainEquipments->count() }}</td>
                                                <td class="text-end">{{ number_format($inspection->total_cost ?? 0) }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">هیچ بازدیدی یافت نشد</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                            <p class="mt-3">هیچ اطلاعاتی برای نمایش وجود ندارد</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                
                @if(($contractorStats ?? [])->count() > 0)
                @php
                    $overallAvgCost = $totalInspectionsCount > 0 ? $totalCostAll / $totalInspectionsCount : 0;
                    $weightedAverageCoefficient = $totalCostAll > 0 ? $totalFinalAll / $totalCostAll : 2.35;
                @endphp
                <tfoot>
                    <tr class="bg-light fw-bold">
                        <td colspan="2" class="text-end">جمع کل:</td>
                        <td class="text-center">{{ number_format($totalInspectionsCount) }}</td>
                        <td class="text-center">-</td>
                        <td class="text-end">{{ number_format($totalCostAll) }} ریال</td>
                        <td class="text-end text-success">{{ number_format($totalFinalAll) }} ریال</td>
                        <td class="text-end">{{ number_format($overallAvgCost) }} ریال</td>
                        <td></td>
                    </tr>
                    <tr class="bg-info bg-opacity-10">
                        <td colspan="3" class="text-end fw-bold">
                            <i class="bi bi-percent"></i> میانگین وزنی ضریب قرارداد:
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary text-white px-3 py-2">
                                {{ number_format($weightedAverageCoefficient, 2) }}
                            </span>
                        </td>
                        <td class="text-end text-muted" colspan="4">
                            <small>(بر اساس هزینه کل)</small>
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
<!-- جدول خلاصه فعالیت‌ها -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="bi bi-list-check text-success"></i> خلاصه فعالیت‌های فهرست بها</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ردیف</th>
                        @if(request('group_by', 'contractor') == 'contractor')
                        <th>پیمانکار</th>
                        @endif
                        <th>کد</th>
                        <th>عنوان فعالیت</th>
                        <th>واحد</th>
                        <th>تعداد کل</th>
                        <th>قیمت واحد (میانگین)</th>
                        <th>مبلغ کل (ریال)</th>
                        <th>مبلغ با ضریب (ریال)</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalAmountAll = 0; @endphp
                    @forelse($activitiesSummary ?? [] as $index => $activity)
                    @php
                        $totalAmountAll += $activity->total_amount;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        @if(request('group_by', 'contractor') == 'contractor')
                        <td class="fw-bold">{{ $activity->contractor_name ?? '-' }}</td>
                        @endif
                        <td>{{ $activity->code }}</td>
                        <td>{{ $activity->title }}</td>
                        <td>{{ $activity->unit }}</td>
                        <td class="text-end">{{ number_format($activity->total_quantity) }}</td>
                        <td class="text-end">{{ number_format($activity->avg_price) }}</td>
                        <td class="text-end fw-bold">{{ number_format($activity->total_amount) }}</td>
                        <td class="text-end text-success">{{ number_format($activity->total_amount * ($coefficient ?? 2.35)) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">هیچ فعالیتی ثبت نشده است<\/td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-light fw-bold">
                    <tr>
                        @if(request('group_by', 'contractor') == 'contractor')
                        <td colspan="6" class="text-end">جمع کل:</td>
                        @else
                        <td colspan="5" class="text-end">جمع کل:</td>
                        @endif
                        <td class="text-end">{{ number_format($totalAmountAll) }} ریال<\/td>
                        <td class="text-end text-success">{{ number_format($totalAmountAll * ($coefficient ?? 2.35)) }} ریال<\/td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>



<!-- جدول خلاصه اقلام مصرفی -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="bi bi-box-seam text-info"></i> خلاصه اقلام مصرفی</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ردیف</th>
                        @if(request('group_by', 'contractor') == 'contractor')
                        <th>پیمانکار</th>
                        @endif
                        <th>نام قلم مصرفی</th>
                        <th>تعداد کل</th>
                        <th>واحد</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($consumablesSummary ?? [] as $index => $consumable)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        @if(request('group_by', 'contractor') == 'contractor')
                        <td class="fw-bold">{{ $consumable->contractor_name ?? '-' }}</td>
                        @endif
                        <td>{{ $consumable->name }}</td>
                        <td class="text-end">{{ number_format($consumable->total_quantity) }}</td>
                        <td>{{ $consumable->unit ?? 'عدد' }}</td>
                    </tr>
                    @empty
                    <tr>
                        @if(request('group_by', 'contractor') == 'contractor')
                        <td colspan="5" class="text-center py-5">هیچ قلم مصرفی ثبت نشده است<\/td>
                        @else
                        <td colspan="4" class="text-center py-5">هیچ قلم مصرفی ثبت نشده است<\/td>
                        @endif
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-light fw-bold">
                    <tr>
                        @if(request('group_by', 'contractor') == 'contractor')
                        <td colspan="2" class="text-end">جمع کل:</td>
                        @else
                        <td colspan="1" class="text-end">جمع کل:</td>
                        @endif
                        <td class="text-end">{{ number_format($consumablesSummary->sum('total_quantity') ?? 0) }}<\/td>
                        <td colspan="2"><\/td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>


    <!-- بازدیدهای اخیر -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="bi bi-clock-history text-warning"></i> آخرین بازدیدها</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ردیف</th>
                            <th>تاریخ بازدید</th>
                            <th>پیمانکار</th>
                            <th>تعداد تجهیزات</th>
                            <th>هزینه (ریال)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentInspections ?? [] as $index => $inspection)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $inspection->jalali_date ?? '-' }}</td>
                            <td>{{ $inspection->contractor->name ?? $inspection->contractor_name ?? '-' }}</td>
                            <td class="text-center">{{ $inspection->mainEquipments->count() ?? 0 }}</td>
                            <td class="text-end">{{ number_format($inspection->total_cost ?? 0) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <p class="mt-3">هیچ بازدیدی یافت نشد</p>
                            </td>
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
<script src="{{ asset('js/xlsx.full.min.js') }}"></script>
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/persian-date.min.js') }}"></script>
<script src="{{ asset('js/persian-datepicker.min.js') }}"></script>
<script src="{{ asset('js/sweetalert2.min.js') }}"></script>
<link href="{{ asset('css/persian-datepicker.min.css') }}" rel="stylesheet">

<script>
$(document).ready(function() {
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

// تابع خروجی اکسل - فقط هدایت به کنترلر
function exportToExcel() {
    Swal.fire({
        title: 'در حال آماده‌سازی گزارش...',
        text: 'لطفاً صبر کنید',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    var params = new URLSearchParams(window.location.search);
    var url = '{{ route("reports.financial.export") }}';
    
    if (params.toString()) {
        url = url + '?' + params.toString();
    }
    
    window.location.href = url;
    
    setTimeout(function() {
        Swal.close();
    }, 1000);
}

document.getElementById('exportExcel').addEventListener('click', function(e) {
    e.preventDefault();
    exportToExcel();
});
</script>
@endpush