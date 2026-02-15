@extends('layouts.user')

@section('title', 'گزارش‌های من')

@section('content')
<div class="container">
    <h2 class="mb-4"><i class="bi bi-file-earmark-text"></i> گزارش‌های من</h2>

    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>تاریخ گزارش</th>
                        <th>نوع گزارش</th>
                        <th>تاریخ بازدید</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="4" class="text-center py-4">
                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                            <p class="mt-2">گزارشی یافت نشد</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection