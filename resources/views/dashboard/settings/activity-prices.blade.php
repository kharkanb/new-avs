@extends("layouts.admin")

@section('title', 'مدیریت فهرست بها')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5>مدیریت آیتم‌های فهرست بها</h5>
        <a href="{{ route('dashboard.activity-prices.create') }}" class="btn btn-primary btn-sm">+ افزودن آیتم جدید</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>کد</th>
                        <th>عنوان</th>
                        <th>واحد</th>
                        <th>قیمت (ریال)</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $index => $item)
                    <tr>
                        <td>{{ $items->firstItem() + $index }}</td>
                        <td>{{ $item->code }}</td>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->unit }}</td>
                        <td>{{ number_format($item->unit_price) }} ریال</td>
                        <td>
                            <a href="{{ route('dashboard.activity-prices.edit', $item) }}" class="btn btn-sm btn-warning">ویرایش</a>
                            <button onclick="deleteItem('{{ route('dashboard.activity-prices.destroy', $item) }}', '{{ $item->code }}')" class="btn btn-sm btn-danger">حذف</button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center">هیچ آیتمی در فهرست بها وجود ندارد</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $items->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteItem(url, code) {
    if (confirm('آیا از حذف آیتم "' + code + '" اطمینان دارید؟')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = url;
        form.innerHTML = '@csrf @method("DELETE")';
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush