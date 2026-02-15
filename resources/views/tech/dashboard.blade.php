@extends('layouts.tech')

@section('title', 'داشبورد کارشناسی')

@section('content')
<div class="container">
    <h2 class="mb-4">داشبورد کارشناسی</h2>
    
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">بازدیدهای من</h5>
                    <h2>{{ $stats['my_inspections'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">تجهیزات ثبت شده</h5>
                    <h2>{{ $stats['my_equipments'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">بازدیدهای این ماه</h5>
                    <h2>{{ $stats['this_month'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>لیست بازدیدهای من</h5>
                    <a href="{{ route('inspections.create') }}" class="btn btn-sm btn-primary float-end">
                        <i class="bi bi-plus-circle"></i> بازدید جدید
                    </a>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>تاریخ</th>
                                <th>پیمانکار</th>
                                <th>تعداد تجهیزات</th>
                                <th>وضعیت</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($my_inspections ?? [] as $inspection)
                            <tr>
                                <td>{{ $inspection->inspection_date }}</td>
                                <td>{{ $inspection->contractor }}</td>
                                <td>{{ $inspection->equipments->count() }}</td>
                                <td>
                                    <span class="badge bg-{{ $inspection->status == 'completed' ? 'success' : 'warning' }}">
                                        {{ $inspection->status }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('inspections.show', $inspection->id) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('inspections.edit', $inspection->id) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection