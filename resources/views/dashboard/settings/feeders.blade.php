@extends('layouts.admin')

@section('title', 'مدیریت فیدرها')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>مدیریت فیدرها</h5>
        <a href="{{ route('dashboard.feeders.create') }}" class="btn btn-primary btn-sm">+ افزودن فیدر جدید</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>نام فیدر</th>
                        <th>پست</th>
                        <th>تاریخ ایجاد</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($feeders as $index => $feeder)
                    <tr>
                        <td>{{ $feeders->firstItem() + $index }}</td>
                        <td>{{ $feeder->name }}</td>
                        <td>{{ $feeder->post ? $feeder->post->name : 'نامشخص' }}</td>
                        <td>{{ $feeder->created_at ? $feeder->created_at->format('Y/m/d') : '-' }}</td>
                        <td>
                            <a href="{{ route('dashboard.feeders.edit', $feeder) }}" class="btn btn-sm btn-info">ویرایش</a>
                            <form action="{{ route('dashboard.feeders.destroy', $feeder) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('آیا اطمینان دارید؟')">حذف</button>
                            </form>
                         </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">هیچ فیدری ثبت نشده است</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-4">
            {{ $feeders->links() }}
        </div>
    </div>
</div>
@endsection