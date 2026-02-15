@extends('layouts.tech')

@section('title', 'لیست بازدیدها')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-clipboard-check"></i> لیست بازدیدها</h2>
        <a href="{{ route('inspections.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> بازدید جدید
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>تاریخ بازدید</th>
                        <th>پیمانکار</th>
                        <th>تعداد تجهیزات</th>
                        <th>وضعیت</th>
                        <th>تاریخ ثبت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inspections as $index => $inspection)
                    <tr>
                        <td>{{ $inspections->firstItem() + $index }}</td>
                        <td>{{ $inspection->inspection_date }}</td>
                        <td>{{ $inspection->contractor }}</td>
                        <td class="text-center">{{ $inspection->equipments_count ?? $inspection->equipments->count() }}</td>
                        <td>
                            @if($inspection->status == 'completed')
                                <span class="badge bg-success">تکمیل شده</span>
                            @elseif($inspection->status == 'draft')
                                <span class="badge bg-warning">پیش‌نویس</span>
                            @else
                                <span class="badge bg-secondary">بایگانی</span>
                            @endif
                        </td>
                        <td>{{ $inspection->created_at->format('Y/m/d H:i') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('inspections.show', $inspection->id) }}" class="btn btn-sm btn-info" title="مشاهده">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('inspections.edit', $inspection->id) }}" class="btn btn-sm btn-warning" title="ویرایش">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button onclick="deleteInspection({{ $inspection->id }})" class="btn btn-sm btn-danger" title="حذف">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <a href="{{ route('inspections.pdf', $inspection->id) }}" class="btn btn-sm btn-secondary" title="PDF">
                                    <i class="bi bi-file-pdf"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                            <p class="mt-2">هیچ بازدیدی یافت نشد</p>
                            <a href="{{ route('inspections.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> ایجاد بازدید جدید
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="mt-3">
                {{ $inspections->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteInspection(id) {
    if (confirm('آیا از حذف این بازدید اطمینان دارید؟')) {
        fetch(`/inspections/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(response => {
            if (response.ok) {
                location.reload();
            }
        });
    }
}
</script>
@endpush