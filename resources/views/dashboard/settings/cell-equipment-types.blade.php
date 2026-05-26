@extends('layouts.admin')

@section('title', 'مدیریت انواع تجهیزات سلولی')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">انواع تجهیزات سلولی</h5>
        <a href="{{ route('dashboard.cell-equipment-types.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> افزودن نوع جدید
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px">#</th>
                        <th>نام</th>
                        <th>کد</th>
                        <th>توضیحات</th>
                        <th style="width: 150px">عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $index => $item)
                    <tr>
                        <td class="text-center">{{ $items->firstItem() + $index }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->code ?? '-' }}</td>
                        <td>{{ Str::limit($item->description ?? '-', 50) }}</td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('dashboard.cell-equipment-types.edit', $item) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-pencil"></i> ویرایش
                                </a>
                                <form action="{{ route('dashboard.cell-equipment-types.destroy', $item) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('آیا از حذف این نوع تجهیز اطمینان دارید؟')">
                                        <i class="bi bi-trash"></i> حذف
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="bi bi-grid" style="font-size: 3rem;"></i>
                            <p class="mt-3">هیچ نوع تجهیز سلولی یافت نشد</p>
                            <a href="{{ route('dashboard.cell-equipment-types.create') }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-circle"></i> افزودن نوع جدید
                            </a>
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

<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function deleteItem(id, name) {
    if (confirm('آیا از حذف "' + name + '" اطمینان دارید؟')) {
        var form = document.getElementById('delete-form');
        form.action = '/dashboard/cell-equipment-types/' + id;
        form.submit();
    }
}
</script>
@endsection