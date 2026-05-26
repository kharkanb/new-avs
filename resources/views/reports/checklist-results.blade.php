@extends('layouts.admin')

@section('title', 'گزارش نتایج چک‌لیست')

@section('header', 'گزارش نتایج چک‌لیست')

@section('content')
<div class="container-fluid">
    <!-- نوار بالایی (در لایوت اصلی وجود دارد) -->
    
    <!-- فیلترها -->
    <div class="filter-box">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-funnel"></i> فیلترهای پیشرفته</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('reports.checklist-results') }}" id="filterForm">
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
                            <label class="form-label">آیتم چک‌لیست</label>
                            <select name="item" class="form-select">
                                <option value="">همه آیتم‌ها</option>
                                @foreach($checklistItems ?? [] as $item)
                                    <option value="{{ $item }}" {{ request('item') == $item ? 'selected' : '' }}>
                                        {{ $item }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">وضعیت</label>
                            <select name="status" class="form-select">
                                <option value="all">همه</option>
                                <option value="OK" {{ request('status') == 'OK' ? 'selected' : '' }}>OK</option>
                                <option value="Not OK" {{ request('status') == 'Not OK' ? 'selected' : '' }}>Not OK</option>
                                <option value="Not Checked" {{ request('status') == 'Not Checked' ? 'selected' : '' }}>Not Checked</option>
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
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i> اعمال فیلتر
                            </button>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <a href="{{ route('reports.checklist-results') }}" class="btn btn-secondary w-100">
                                <i class="bi bi-x-circle"></i> حذف فیلترها
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- کارت‌های آمار -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-box">
                <h3>{{ number_format($stats['total'] ?? 0) }}</h3>
                <p>کل آیتم‌ها</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-box stats-ok">
                <h3>{{ number_format($stats['ok'] ?? 0) }}</h3>
                <p>OK</p>
                <small>{{ ($stats['total'] ?? 0) > 0 ? round((($stats['ok'] ?? 0)/($stats['total'] ?? 1))*100) : 0 }}%</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-box stats-not-ok">
                <h3>{{ number_format($stats['not_ok'] ?? 0) }}</h3>
                <p>Not OK</p>
                <small>{{ ($stats['total'] ?? 0) > 0 ? round((($stats['not_ok'] ?? 0)/($stats['total'] ?? 1))*100) : 0 }}%</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-box">
                <h3>{{ number_format($stats['not_checked'] ?? 0) }}</h3>
                <p>بررسی نشده</p>
            </div>
        </div>
    </div>

    <!-- جدول نتایج چک‌لیست -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">لیست نتایج چک‌لیست</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px">#</th>
                            <th>تاریخ بازدید</th>
                            <th>پیمانکار</th>
                            <th>نوع تجهیز</th>
                            <th>کد اسکادا</th>
                            <th>آیتم چک‌لیست</th>
                            <th>وضعیت</th>
                            <th style="width: 100px">توضیحات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($results ?? [] as $result)
                        @php
                            try {
                                $inspection = $result->mainEquipment->inspection ?? null;
                                $date = $inspection ? verta($inspection->inspection_date)->format('Y/m/d') : '---';
                                $contractor = $inspection->contractor->name ?? $inspection->contractor ?? '---';
                            } catch (\Exception $e) {
                                $date = '---';
                                $contractor = '---';
                            }
                            
                            $equipmentType = $result->mainEquipment->mainEquipmentType->name ?? 
                                            $result->mainEquipment->cellEquipmentType->name ?? 
                                            '---';
                        @endphp
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $date }}</td>
                            <td>{{ $contractor }}</td>
                            <td>{{ $equipmentType }}</td>
                            <td>{{ $result->mainEquipment->scada_code ?? '---' }}</td>
                            <td>{{ $result->item ?? $result->item_text ?? '---' }}</td>
                            <td>
                                @if($result->status == 'OK')
                                    <span class="badge bg-success">OK</span>
                                @elseif($result->status == 'Not OK')
                                    <span class="badge bg-danger">Not OK</span>
                                @else
                                    <span class="badge bg-secondary">بررسی نشده</span>
                                @endif
                            </td>
                            <td>
                                @if($result->description)
                                    <span class="text-info" title="{{ $result->description }}">
                                        <i class="bi bi-chat-dots"></i>
                                    </span>
                                @else
                                    <span class="text-muted">---</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <p class="mt-3">هیچ نتیجه‌ای یافت نشد</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if(isset($results) && method_exists($results, 'links'))
            <div class="d-flex justify-content-center mt-4">
                {{ $results->links() }}
            </div>
            <div class="text-center mt-2 text-muted">
                <small>نمایش {{ $results->count() }} از {{ $results->total() }} نتیجه</small>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/jquery.min.js') }}"></script>
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

function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('show');
}
</script>
@endpush
@endsection