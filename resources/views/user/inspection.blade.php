@extends('layouts.user')

@section('title', 'مشاهده بازدید')

@section('content')
<div class="container">
    <h2 class="mb-4">جزئیات بازدید</h2>

    <div class="card mb-4">
        <div class="card-header">
            <h5>اطلاعات کلی</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>تاریخ بازدید:</strong> {{ $inspection->inspection_date }}</p>
                    <p><strong>پیمانکار:</strong> {{ $inspection->contractor }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>زمان شروع:</strong> {{ $inspection->daily_start_time }}</p>
                    <p><strong>زمان پایان:</strong> {{ $inspection->daily_end_time }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>تجهیزات بازدید شده</h5>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>نوع تجهیز</th>
                        <th>کد اسکادا</th>
                        <th>موقعیت</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inspection->equipments as $index => $equipment)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $equipment->equipment_type }}</td>
                        <td>{{ $equipment->scada_code }}</td>
                        <td>{{ $equipment->location ?? '---' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 text-end">
        <a href="{{ route('inspections.pdf', $inspection->id) }}" class="btn btn-danger">
            <i class="bi bi-file-pdf"></i> دریافت PDF
        </a>
        <a href="{{ route('user.dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-right"></i> بازگشت
        </a>
    </div>
</div>
@endsection