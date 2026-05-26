<div class="row">
    <div class="col-md-12">
        <h6><i class="bi bi-info-circle"></i> اطلاعات کلی</h6>
        <table class="table table-bordered">
            <tr>
                <th style="width: 30%">تاریخ بازدید</th>
                <td>{{ $inspection->inspection_date }}</td>
                <th style="width: 30%">پیمانکار</th>
                <td>{{ $inspection->contractor->name ?? '-' }}</td>
            </tr>
            <tr>
                <th>شماره قرارداد</th>
                <td>{{ $inspection->contract_number ?? '-' }}</td>
                <th>ضریب قرارداد</th>
                <td>{{ $inspection->contract_coefficient ?? '-' }}</td>
            </tr>
            <tr>
                <th>زمان شروع</th>
                <td>{{ $inspection->daily_start_time ?? '-' }}</td>
                <th>زمان پایان</th>
                <td>{{ $inspection->daily_end_time ?? '-' }}</td>
            </tr>
            <tr>
                <th>کاربر ثبت‌کننده</th>
                <td>{{ $inspection->user->name ?? '-' }}</td>
                <th>هزینه کل</th>
                <td class="fw-bold">{{ number_format($inspection->total_cost ?? 0) }} ریال</td>
            </tr>
        </table>
    </div>
</div>

<h6 class="mt-4"><i class="bi bi-hdd-stack"></i> تجهیزات</h6>
@foreach($inspection->mainEquipments as $equipment)
<div class="card mb-3">
    <div class="card-header bg-light">
        <strong>{{ $loop->iteration }}. {{ $equipment->mainEquipmentType->name ?? $equipment->cellEquipmentType->name ?? 'نامشخص' }}</strong>
        <span class="badge bg-secondary float-start">کد اسکادا: {{ $equipment->scada_code ?? '-' }}</span>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>برند:</strong> {{ $equipment->brand->name ?? '-' }}</p>
                <p><strong>نوع نصب:</strong> {{ $equipment->installation_type ?? '-' }}</p>
                <p><strong>امور/شهرستان:</strong> {{ $equipment->department->name ?? '-' }}</p>
                <p><strong>GIS Code:</strong> {{ $equipment->department->city ?? '-' }}</p>
            </div>
            <div class="col-md-6">
                <p><strong>موقعیت جغرافیایی:</strong> 
                    عرض: {{ $equipment->location->latitude ?? '-' }}, 
                    طول: {{ $equipment->location->longitude ?? '-' }}
                </p>
                <p><strong>آدرس:</strong> {{ $equipment->location->address ?? '-' }}</p>
                @if($equipment->location->cabinet_final_height)
                <p><strong>ارتفاع:</strong> {{ $equipment->location->cabinet_final_height }} متر</p>
                @endif
            </div>
        </div>
        
        @if($equipment->feeders->count())
        <div class="mt-2">
            <strong>فیدرها:</strong>
            <ul>
                @foreach($equipment->feeders as $feeder)
                    <li>{{ $feeder->post }} - {{ $feeder->feeder }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        @if($equipment->checklists->count())
        <div class="mt-2">
            <strong>چک‌لیست:</strong>
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr><th>آیتم</th><th style="width: 100px">وضعیت</th><th>توضیحات</th></tr>
                    </thead>
                    <tbody>
                        @foreach($equipment->checklists as $checklist)
                        <tr>
                            <td>{{ $checklist->item }}</td>
                            <td class="{{ $checklist->status == 'OK' ? 'text-success fw-bold' : ($checklist->status == 'Not OK' ? 'text-danger fw-bold' : 'text-warning') }}">
                                {{ $checklist->status }}
                            </td>
                            <td>{{ $checklist->description ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
        
        @if($equipment->activities->count())
        <div class="mt-2">
            <strong>فعالیت‌های انجام شده:</strong>
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr><th>کد</th><th>عنوان</th><th>تعداد</th><th>فی واحد</th><th>مبلغ</th></tr>
                    </thead>
                    <tbody>
                        @foreach($equipment->activities as $activity)
                        <tr>
                            <td>{{ $activity->code }}</td>
                            <td>{{ $activity->title }}</td>
                            <td class="text-center">{{ $activity->quantity }}</td>
                            <td class="text-start">{{ number_format($activity->unit_price) }} ریال</td>
                            <td class="text-start">{{ number_format($activity->total) }} ریال</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
        
        @if($equipment->consumables->count())
        <div class="mt-2">
            <strong>اقلام مصرفی:</strong>
            <ul>
                @foreach($equipment->consumables as $consumable)
                    <li>{{ $consumable->name }}: {{ $consumable->quantity }} {{ $consumable->unit }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
</div>
@endforeach

@if($inspection->mainEquipments->isEmpty())
<div class="alert alert-warning text-center">
    <i class="bi bi-exclamation-triangle"></i> هیچ تجهیزی برای این بازدید ثبت نشده است
</div>
@endif