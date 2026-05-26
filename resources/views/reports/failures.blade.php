@extends('layouts.admin')

@section('title', 'گزارش خرابی‌ها (آیتم‌های Not OK)')

@section('header', 'گزارش خرابی‌ها')

@section('content')
<div class="container-fluid">
    <!-- فیلترها -->
    <div class="filter-section">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-funnel"></i> فیلترهای پیشرفته</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('reports.failures') }}" id="filterForm">
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
                            <label class="form-label">کد اسکادا</label>
                            <input type="text" class="form-control" name="scada_code" 
                                   value="{{ request('scada_code') }}" placeholder="مثال: 1234" autocomplete="off">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">آیتم چک‌لیست</label>
                            <input type="text" class="form-control" name="checklist_item" 
                                   value="{{ request('checklist_item') }}" placeholder="جستجوی آیتم" autocomplete="off">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i> اعمال فیلتر
                            </button>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <a href="{{ route('reports.failures') }}" class="btn btn-secondary w-100">
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
        <div class="col-md-4">
            <div class="stats-card" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                <h3>{{ number_format($stats['total'] ?? 0) }}</h3>
                <p>کل خرابی‌ها</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                <h3>{{ number_format($stats['unique_items'] ?? 0) }}</h3>
                <p>آیتم‌های آسیب‌دیده</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card" style="background: linear-gradient(135deg, #3498db, #2980b9);">
                <h3>{{ number_format($stats['affected_equipments'] ?? 0) }}</h3>
                <p>تجهیزات دارای عیب</p>
            </div>
        </div>
    </div>

    <!-- جدول بیشترین خرابی‌ها -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">بیشترین آیتم‌های خرابی</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>ردیف</th>
                                    <th>آیتم چک‌لیست</th>
                                    <th>تعداد تکرار</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stats['by_type'] ?? [] as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $item->item ?? $item['item'] ?? '-' }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-danger">{{ number_format($item->total ?? $item['total'] ?? 0) }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4">
                                        <i class="bi bi-info-circle"></i> هیچ خرابی یافت نشد
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- لیست کامل خرابی‌ها -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">لیست کامل خرابی‌ها</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>تاریخ بازدید</th>
                            <th>پیمانکار</th>
                            <th>نوع تجهیز</th>
                            <th>کد اسکادا</th>
                            <th>آیتم</th>
                            <th>توضیحات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($failures ?? [] as $index => $failure)
                        <tr>
                            <td class="text-center">{{ $failures->firstItem() + $index }}</td>
                            <td>{{ $failure->inspection_date ?? $failure->created_at ?? '-' }}</td>
                            <td>{{ $failure->contractor_name ?? $failure->contractor ?? '-' }}</td>
                            <td>{{ $failure->equipment_type ?? $failure->mainEquipmentType->name ?? '-' }}</td>
                            <td>{{ $failure->scada_code ?? $failure->equipment_code ?? '-' }}</td>
                            <td>
                                <span class="badge bg-danger">{{ $failure->item ?? $failure->checklist_item ?? '-' }}</span>
                            </td>
                            <td>{{ Str::limit($failure->description ?? $failure->checklist_description ?? '-', 50) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                                <p class="mt-3">هیچ خرابی یافت نشد</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if(isset($failures) && method_exists($failures, 'links'))
            <div class="d-flex justify-content-center mt-4">
                {{ $failures->links() }}
            </div>
            <div class="text-center mt-2 text-muted">
                <small>نمایش {{ $failures->count() }} از {{ $failures->total() }} خرابی</small>
            </div>
            @endif
        </div>
    </div>

    <!-- دکمه بازگشت -->
    <div class="back-btn">
        <a href="{{ route('reports.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-right"></i> بازگشت به گزارشات
        </a>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/jquery.min.js') }}"></script>
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
    
    // تبدیل به تاریخ شمسی
    var persianToday = new persianDate(today);
    var persianOneMonthAgo = new persianDate(oneMonthAgo);
    
    // تنظیم مقادیر پیش‌فرض اگر خالی باشند
    if (!$('#start_date').val()) {
        $('#start_date').val(persianOneMonthAgo.format('YYYY/MM/DD'));
    }
    if (!$('#end_date').val()) {
        $('#end_date').val(persianToday.format('YYYY/MM/DD'));
    }
    
    // فعال‌سازی تقویم شمسی
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