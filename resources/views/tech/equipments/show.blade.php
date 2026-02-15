@extends('layouts.tech')

@section('title', 'مشاهده تجهیز')

@section('content')
<div class="container">
    <h2 class="mb-4"><i class="bi bi-eye"></i> مشاهده تجهیز</h2>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">اطلاعات پایه</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>نوع تجهیز:</th>
                            <td>{{ $equipment->equipment_type }}</td>
                        </tr>
                        <tr>
                            <th>کد اسکادا:</th>
                            <td>{{ $equipment->scada_code ?? '---' }}</td>
                        </tr>
                        <tr>
                            <th>پست:</th>
                            <td>{{ $equipment->post->name ?? '---' }}</td>
                        </tr>
                        <tr>
                            <th>برند کلید:</th>
                            <td>{{ $equipment->switch_brand ?? '---' }}</td>
                        </tr>
                        <tr>
                            <th>برند مودم:</th>
                            <td>{{ $equipment->modem_brand ?? '---' }}</td>
                        </tr>
                        <tr>
                            <th>برند RTU:</th>
                            <td>{{ $equipment->rtu_brand ?? '---' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">موقعیت جغرافیایی</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>عرض جغرافیایی:</th>
                            <td>{{ $equipment->latitude ?? '---' }}</td>
                        </tr>
                        <tr>
                            <th>طول جغرافیایی:</th>
                            <td>{{ $equipment->longitude ?? '---' }}</td>
                        </tr>
                        <tr>
                            <th>ارتفاع:</th>
                            <td>{{ $equipment->height ?? '---' }}</td>
                        </tr>
                        <tr>
                            <th>نوع نصب:</th>
                            <td>{{ $equipment->installation_type ?? '---' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">فیدرها</h5>
        </div>
        <div class="card-body">
            <ul>
                @forelse($equipment->feeders as $feeder)
                    <li>{{ $feeder->name }}</li>
                @empty
                    <li class="text-muted">هیچ فیدری ثبت نشده</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="mt-3 text-end">
        <a href="{{ route('tech.equipments.edit', $equipment->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> ویرایش
        </a>
        <a href="{{ route('tech.equipments.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-right"></i> بازگشت
        </a>
    </div>
</div>
@endsection