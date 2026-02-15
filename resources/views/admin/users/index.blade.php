@extends('layouts.admin')

@section('title', 'مدیریت کاربران')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>مدیریت کاربران</h2>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> کاربر جدید
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>نام</th>
                        <th>ایمیل</th>
                        <th>نقش</th>
                        <th>تاریخ عضویت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users ?? [] as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'tech' ? 'success' : 'info') }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td>{{ $user->created_at->format('Y/m/d') }}</td>
                        <td>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button onclick="deleteUser({{ $user->id }})" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection