@extends('layouts.admin')

@section('title', 'مدیریت برندها')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
        <h5 class="mb-0">مدیریت برندها</h5>
        <a href="{{ route('dashboard.brands.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> افزودن برند جدید
        </a>
    </div>
    <div class="card-body">
        <!-- فیلترها -->
        <form method="GET" action="{{ route('dashboard.brands.index') }}" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">دسته بندی</label>
                    <select name="category" class="form-select" id="categorySelect">
                        <option value="all" {{ request('category', 'all') == 'all' ? 'selected' : '' }}>همه</option>
                        <option value="main" {{ request('category') == 'main' ? 'selected' : '' }}>تجهیزات اصلی</option>
                        <option value="cell" {{ request('category') == 'cell' ? 'selected' : '' }}>تجهیزات سلولی</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">نوع تجهیز</label>
                    <select name="equipment_type_id" class="form-select" id="equipmentTypeSelect">
                        <option value="">همه</option>
                        @if(request('category') != 'cell')
                        <optgroup label="تجهیزات اصلی">
                            @foreach($mainEquipmentTypes as $type)
                                @if(!in_array($type->id, [5, 6, 7]))
                                    <option value="main_{{ $type->id }}" {{ request('equipment_type_id') == 'main_' . $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endif
                            @endforeach
                        </optgroup>
                        @endif
                        @if(request('category') != 'main')
                        <optgroup label="تجهیزات سلولی">
                            @foreach($cellEquipmentTypes as $type)
                                <option value="cell_{{ $type->id }}" {{ request('equipment_type_id') == 'cell_' . $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </optgroup>
                        @endif
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel"></i> فیلتر
                    </button>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <a href="{{ route('dashboard.brands.index') }}" class="btn btn-secondary w-100">
                        <i class="bi bi-x-circle"></i> حذف فیلتر
                    </a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px">#</th>
                        <th>نام تجهیز</th>
                        <th>دسته تجهیز</th>
                        <th>نام برند</th>
                        <th style="width: 150px">عملیات</th>
                    <tr>
                </thead>
                <tbody>
                    @forelse($items as $index => $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        @if($item->mainEquipmentType)
                            <td><span class="badge bg-primary">{{ $item->mainEquipmentType->name }}</span></td>
                            <td><span class="badge bg-primary">اصلی</span></td>
                        @elseif($item->cellEquipmentType)
                            <td><span class="badge bg-success">{{ $item->cellEquipmentType->name }}</span></td>
                            <td><span class="badge bg-success">سلولی</span></td>
                        @else
                            <td><span class="badge bg-secondary">---</span></td>
                            <td><span class="badge bg-secondary">نامشخص</span></td>
                        @endif
                        <td>{{ $item->name }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('dashboard.brands.edit', $item) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-pencil"></i> ویرایش
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteBrand({{ $item->id }}, '{{ $item->name }}')">
                                    <i class="bi bi-trash"></i> حذف
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="bi bi-tags" style="font-size: 3rem;"></i>
                            <p class="mt-3">هیچ برندی ثبت نشده است</p>
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
function deleteBrand(id, name) {
    if (confirm('آیا از حذف برند "' + name + '" اطمینان دارید؟')) {
        var form = document.getElementById('delete-form');
        form.action = '/dashboard/brands/' + id;
        form.submit();
    }
}

// ارسال خودکار فرم وقتی دسته بندی تغییر می‌کند
document.getElementById('categorySelect').addEventListener('change', function() {
    this.form.submit();
});
</script>
@endsection