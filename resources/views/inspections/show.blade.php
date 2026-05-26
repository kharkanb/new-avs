@extends('layouts.admin')

@section('title', 'مشاهده بازدید')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-eye"></i> جزئیات بازدید</h5>
            <div>
                <a href="{{ route('dashboard.inspections') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-right"></i> بازگشت
                </a>
                <a href="{{ route('inspection.edit', $inspection->id) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil"></i> ویرایش
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- اطلاعات اصلی -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 180px">شناسه:</th>
                            <td>{{ $inspection->id }}</td>
                        </tr>
                        <tr>
                            <th>تاریخ بازدید:</th>
                            <td>{{ $inspection->jalali_date ?? $inspection->inspection_date }}</td>
                        </tr>
                        <tr>
                            <th>پیمانکار:</th>
                            <td>{{ $inspection->contractor_name ?? $inspection->contractor ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>ضریب قرارداد:</th>
                            <td>{{ $inspection->contract_coefficient ?? '-' }}</td>
                        </tr>
        		<tr>
            		         <th>امور/شهرستان:</th>
            		         <td>{{ $inspection->department->name ?? '-' }}</td>  <!-- اضافه شده -->
        			</tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 180px">شماره قرارداد:</th>
                            <td>{{ $inspection->contract_number ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>ساعت شروع:</th>
                            <td>{{ $inspection->daily_start_time ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>ساعت پایان:</th>
                            <td>{{ $inspection->daily_end_time ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>وضعیت:</th>
                            <td>
                                <span class="badge bg-{{ $inspection->status == 'completed' ? 'success' : 'warning' }}">
                                    {{ $inspection->status == 'completed' ? 'تکمیل شده' : 'پیش‌نویس' }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- هزینه کل -->
            <div class="alert alert-info text-center">
                <h5>هزینه کل بازدید: <strong>{{ number_format($inspection->total_cost ?? 0) }} ریال</strong></h5>
            </div>

            <!-- لیست تجهیزات -->
            <h5 class="mt-4"><i class="bi bi-hdd-stack"></i> لیست تجهیزات ({{ $inspection->mainEquipments->count() }})</h5>
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>نوع تجهیز</th>
                            <th>کد اسکادا</th>
                            <th>نوع نصب</th>
                            <th>برند</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inspection->mainEquipments as $index => $equipment)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $equipment->mainEquipmentType->name ?? '-' }}</td>
                            <td>{{ $equipment->scada_code ?? '-' }}</td>
                            <td>{{ $equipment->installation_type ?? '-' }}</td>
                            <td>{{ $equipment->brand->name ?? $equipment->other_brand ?? '-' }}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#equipmentModal{{ $equipment->id }}">
                                    <i class="bi bi-info-circle"></i> جزئیات
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($inspection->mainEquipments->isEmpty())
            <div class="alert alert-warning text-center">
                <i class="bi bi-exclamation-triangle"></i> هیچ تجهیزی برای این بازدید ثبت نشده است.
            </div>
            @endif
        </div>
    </div>
</div>

<!-- مودال‌های جزئیات تجهیزات -->
@foreach($inspection->mainEquipments as $equipment)
<div class="modal fade" id="equipmentModal{{ $equipment->id }}" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-gear"></i> جزئیات تجهیز</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
            </div>
            <div class="modal-body">
                @php
                    $location = $equipment->equipmentLocation;
                    $communication = $equipment->equipmentCommunication;
                    $feeders = $equipment->equipmentFeeders;
                @endphp

                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered table-sm">
                            <tr><th style="width: 40%">نوع تجهیز:</th><td>{{ $equipment->mainEquipmentType->name ?? '-' }}</td></tr>
                            <tr><th>کد اسکادا:</th><td>{{ $equipment->scada_code ?? '-' }}</td></tr>
                            <tr><th>نوع نصب:</th><td>{{ $equipment->installation_type ?? '-' }}</td></tr>
                            <tr><th>برند:</th><td>{{ $equipment->brand->name ?? $equipment->other_brand ?? '-' }}</td></tr>
                            <tr><th>امور/شهرستان:</th><td>{{ $inspection->department->name ?? '-' }}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered table-sm">
                            <tr>
                                <th style="width: 40%">موقعیت جغرافیایی:</th>
                                <td>
                                    عرض: {{ $location->latitude ?? '-' }}<br>
                                    طول: {{ $location->longitude ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th>ارتفاع:</th>
                                <td>
                                    ارتفاع اولیه: {{ $location->cabinet_initial_height ?? '-' }} متر<br>
                                    ارتفاع نهایی: {{ $location->cabinet_final_height ?? '-' }} متر
                                </td>
                            </tr>
                            <tr>
                                <th>آدرس:</th>
                                <td>{{ $location->address ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>ارتباطات:</th>
                                <td>
                                    نوع سیم‌کارت: {{ $communication->simcard_type ?? '-' }}<br>
                                    شماره سیم‌کارت: {{ $communication->simcard_number ?? '-' }}<br>
                                    IP: {{ $communication->simcard_ip ?? '-' }}<br>
                                    وضعیت آنتن: {{ $communication->antenna_status ?? '-' }}<br>
                                    وضعیت سیگنال: {{ $communication->signal_status ?? '-' }}<br>
                                    تغذیه مودم: {{ $communication->modem_power ?? '-' }}
                                </td>
                            </tr>
                        </table>
                        <table class="table table-bordered table-sm mt-2">
                            <tr>
                                <th style="width: 40%">پست/فیدر:</th>
                                <td>
                                    @forelse($feeders as $feeder)
                                        پست: {{ $feeder->post ?? '-' }}<br>
                                        فیدر: {{ $feeder->feeder ?? '-' }}
                                    @empty
                                        -
                                    @endforelse
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($equipment->checklists->count())
                <hr>
                <h6><i class="bi bi-clipboard-check"></i> چک‌لیست</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr><th>آیتم</th><th style="width: 100px">وضعیت</th><th>توضیحات</th></tr>
                        </thead>
                        <tbody>
                            @foreach($equipment->checklists as $checklist)
                            <tr>
                                <td>{{ $checklist->item }}</td>
                                <td class="{{ $checklist->status == 'OK' ? 'text-success' : 'text-danger' }} fw-bold">{{ $checklist->status }}</td>
                                <td>{{ $checklist->description ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                @if($equipment->activities->count())
                <hr>
                <h6><i class="bi bi-list-check"></i> فعالیت‌های انجام شده</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr><th>کد</th><th>عنوان</th><th>تعداد</th><th>مبلغ</th></tr>
                        </thead>
                        <tbody>
                            @foreach($equipment->activities as $activity)
                            <tr>
                                <td style="white-space: nowrap;">{{ $activity->code }}</td>
                                <td>{{ $activity->title }}</td>
                                <td class="text-center">{{ $activity->quantity }}</td>
                                <td class="text-start" style="white-space: nowrap;">{{ number_format($activity->total) }} ریال</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // رفع مشکل aria-hidden در مودال‌ها
    $('.modal').on('show.bs.modal', function() {
        $(this).removeAttr('aria-hidden');
    });
});
</script>
@endpush