@extends('layouts.admin')

@section('title', 'داشبورد مدیریت')

@section('content')
<div class="container">
    <h2 class="mb-4">داشبورد مدیریت</h2>
    
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">کاربران کل</h5>
                    <h2>{{ $stats['total_users'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">بازدیدها</h5>
                    <h2>{{ $stats['total_inspections'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">تجهیزات</h5>
                    <h2>{{ $stats['total_equipments'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">در انتظار</h5>
                    <h2>{{ $stats['pending_inspections'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>کاربران جدید</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>نام</th>
                                <th>ایمیل</th>
                                <th>نقش</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($latest_users ?? [] as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>بازدیدهای اخیر</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>تاریخ</th>
                                <th>کاربر</th>
                                <th>وضعیت</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($latest_inspections ?? [] as $inspection)
                            <tr>
                                <td>{{ $inspection->inspection_date }}</td>
                                <td>{{ $inspection->user->name ?? '---' }}</td>
                                <td>
                                    <span class="badge bg-{{ $inspection->status == 'completed' ? 'success' : 'warning' }}">
                                        {{ $inspection->status }}
                                    </span>
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