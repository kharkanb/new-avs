@extends('layouts.admin')

@section('title', 'مدیریت انواع تجهیزات')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>مدیریت انواع تجهیزات</h5>
        <a href="{{ route('dashboard.equipment-types.create') }}" class="btn btn-primary btn-sm">+ افزودن نوع جدید</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>نام</th>
                        <th>کد</th>
                        <th>توضیحات</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $index => $item)
                    <tr>
                        <td>{{ $items->firstItem() + $index }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->code ?? '-' }}</td>
                        <td>{{ Str::limit($item->description ?? '-', 50) }}</td>
                        <td>
                            <a href="{{ route('dashboard.equipment-types.edit', $item) }}" class="btn btn-sm btn-info">ویرایش</a>
                            <form action="{{ route('dashboard.equipment-types.destroy', $item) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('آیا اطمینان دارید؟')">حذف</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <td>
                        <td colspan="5" class="text-center">هیچ نوع تجهیزی ثبت نشده است</td>
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