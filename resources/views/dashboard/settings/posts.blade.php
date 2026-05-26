@extends('layouts.admin')

@section('title', 'مدیریت پست‌ها')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>مدیریت پست‌ها</h5>
        <a href="{{ route('dashboard.posts.create') }}" class="btn btn-primary btn-sm">افزودن پست جدید</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>نام پست</th>
                        <th>تعداد فیدرها</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $index => $item)
                    <tr>
                        <td>{{ $items->firstItem() + $index }}</td>
                        <td>{{ $item->name }}</td>
                        <td>
                            <span class="badge bg-primary">{{ $item->feeders_count ?? $item->feeders()->count() }}</span>
                        </td>
                        <td>
                            <a href="{{ route('dashboard.posts.edit', $item) }}" class="btn btn-sm btn-info">ویرایش</a>
                            <form action="{{ route('dashboard.posts.destroy', $item) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('حذف شود؟')">حذف</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">هیچ پستی یافت نشد</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-4">
            {{ $items->links() }}
        </div>
    </div>
</div>
@endsection