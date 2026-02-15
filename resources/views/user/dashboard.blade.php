@extends('layouts.user')

@section('title', 'داشبورد کاربری')

@section('content')
<div class="container">
    <h2 class="mb-4">خوش آمدید، {{ auth()->user()->name }}</h2>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>بازدیدهای من</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>تاریخ</th>
                                <th>پیمانکار</th>
                                <th>تجهیزات</th>
                                <th>وضعیت</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inspections ?? [] as $inspection)
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
                                    <a href="{{ route('user.inspection.show', $inspection->id) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i> مشاهده
                                    </a>
                                    <a href="{{ route('inspections.pdf', $inspection->id) }}" class="btn btn-sm btn-danger">
                                        <i class="bi bi-file-pdf"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">هیچ بازدیدی ثبت نشده است</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection