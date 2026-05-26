@extends('layouts.admin')

@section('title', 'مدیریت چک‌لیست‌ها')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">مدیریت چک‌لیست‌ها</h5>
        <a href="{{ route('dashboard.checklist-items.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> افزودن چک‌لیست جدید
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px">#</th>
                        <th>عنوان چک‌لیست</th>
                        <th>نوع تجهیز</th>
                        <th style="width: 100px">تعداد آیتم</th>
                        <th style="width: 150px">عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $index => $item)
                    <tr>
                        <td class="text-center">{{ $items->firstItem() + $index }}</td>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->mainEquipmentType ? $item->mainEquipmentType->name : '-' }}</td>
                        <td class="text-center">
                            <span class="badge bg-primary">{{ $item->items_count ?? $item->items->count() }}</span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('dashboard.checklist-items.edit', $item) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-pencil"></i> ویرایش
                                </a>
                                <form action="{{ route('dashboard.checklist-items.destroy', $item) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('آیا از حذف این چک‌لیست اطمینان دارید؟')">
                                        <i class="bi bi-trash"></i> حذف
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="bi bi-clipboard-x" style="font-size: 3rem;"></i>
                            <p class="mt-3">هیچ چک‌لیستی ثبت نشده است</p>
                        </td>
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