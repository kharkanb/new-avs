@extends('layouts.tech')

@section('title', 'مشاهده بازدید')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-eye"></i> مشاهده بازدید</h2>
        <div>
            <a href="{{ route('inspections.edit', $inspection->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> ویرایش
            </a>
            <a href="{{ route('inspections.pdf', $inspection->id) }}" class="btn btn-secondary">
                <i class="bi bi-file-pdf"></i> PDF
            </a>
            <a href="{{ route('tech.inspections.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-right"></i> بازگشت
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> اطلاعات کلی</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <p><strong>تاریخ بازدید:</strong></p>
                            <p class="text-muted">{{ $inspection->inspection_date }}</p>
                        </div>
                        <div class="col-md-3">
                            <p><strong>زمان شروع:</strong></p>
                            <p class="text-muted">{{ $inspection->daily_start_time }}</p>
                        </div>
                        <div class="col-md-3">
                            <p><strong>زمان پایان:</strong></p>
                            <p class="text-muted">{{ $inspection->daily_end_time }}</p>
                        </div>
                        <div class="col-md-3">
                            <p><strong>وضعیت:</strong></p>
                            <p>
                                @if($inspection->status == 'completed')
                                    <span class="badge bg-success">تکمیل شده</span>
                                @elseif($inspection->status == 'draft')
                                    <span class="badge bg-warning">پیش‌نویس</span>
                                @else
                                    <span class="badge bg-secondary">بایگانی</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <p><strong>پیمانکار:</strong></p>
                            <p class="text-muted">{{ $inspection->contractor }}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>ضریب قرارداد:</strong></p>
                            <p class="text-muted">{{ $inspection->contract_coefficient }}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>شماره قرارداد:</strong></p>
                            <p class="text-muted">{{ $inspection->contract_number ?? '---' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-hdd-stack"></i> تجهیزات بازدید شده ({{ $inspection->equipments->count() }})</h5>
                </div>
                <div class="card-body">
                    @foreach($inspection->equipments as $index => $equipment)
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">تجهیز {{ $index + 1 }}: {{ $equipment->equipment_type }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <p><strong>کد اسکادا:</strong></p>
                                    <p>{{ $equipment->scada_code ?? '---' }}</p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>برند کلید:</strong></p>
                                    <p>{{ $equipment->switch_brand ?? '---' }}</p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>برند مودم:</strong></p>
                                    <p>{{ $equipment->modem_brand ?? '---' }}</p>
                                </div>
                                <div class="col-md-3">
                                    <p><strong>برند RTU:</strong></p>
                                    <p>{{ $equipment->rtu_brand ?? '---' }}</p>
                                </div>
                            </div>
                            
                            @if($equipment->feeders->count() > 0)
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <p><strong>فیدرها:</strong></p>
                                    <ul>
                                        @foreach($equipment->feeders as $feeder)
                                            <li>{{ $feeder->name }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="bi bi-list-check"></i> خلاصه فعالیت‌ها</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>کد</th>
                                        <th>عنوان</th>
                                        <th>تعداد</th>
                                        <th>فی واحد</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($inspection->activities as $activity)
                                    <tr>
                                        <td>{{ $activity->code }}</td>
                                        <td>{{ $activity->title }}</td>
                                        <td>{{ $activity->quantity }}</td>
                                        <td>{{ number_format($activity->unit_price) }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">فعالیتی ثبت نشده</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-warning text-white">
                            <h5 class="mb-0"><i class="bi bi-box-seam"></i> اقلام مصرفی</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>نام</th>
                                        <th>تعداد</th>
                                        <th>واحد</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($inspection->consumables as $consumable)
                                    <tr>
                                        <td>{{ $consumable->name }}</td>
                                        <td>{{ $consumable->quantity }}</td>
                                        <td>{{ $consumable->unit }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center">قلم مصرفی ثبت نشده</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="bi bi-calculator"></i> جمع‌بندی مالی</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <p><strong>جمع فعالیت‌ها:</strong></p>
                            <h4>{{ number_format($inspection->activities_total ?? 0) }} ریال</h4>
                        </div>
                        <div class="col-md-4">
                            <p><strong>ضریب قرارداد:</strong></p>
                            <h4>{{ $inspection->contract_coefficient }}</h4>
                        </div>
                        <div class="col-md-4">
                            <p><strong>مبلغ نهایی:</strong></p>
                            <h4 class="text-success">{{ number_format(($inspection->activities_total ?? 0) * $inspection->contract_coefficient) }} ریال</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection