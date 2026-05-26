@extends('layouts.admin')

@section('title', 'لیست بازدیدها')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-list-check"></i> لیست بازدیدها</h5>
            <a href="{{ route('inspection.form') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> بازدید جدید
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px">#</th>
                            <th>تاریخ بازدید</th>
                            <th>پیمانکار</th>
                            <th>تعداد تجهیزات</th>
                            <th>وضعیت</th>
                            <th>هزینه (ریال)</th>
                            <th style="width: 150px">عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inspections ?? [] as $index => $inspection)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-nowrap">
                                @php
                                    try {
                                        use Hekmatinasser\Verta\Verta;
                                        $date = verta($inspection->inspection_date)->format('Y/m/d');
                                    } catch (\Exception $e) {
                                        $date = $inspection->inspection_date ?? '---';
                                    }
                                @endphp
                                {{ $date }}
                            </td>
                            <td>{{ $inspection->contractor_name ?? $inspection->contractor ?? '-' }}</td>
                            <td class="text-center">{{ $inspection->mainEquipments->count() ?? 0 }}</td>
                            <td class="text-center">
                                @if(($inspection->status ?? '') == 'completed')
                                    <span class="badge bg-success">تکمیل شده</span>
                                @elseif(($inspection->status ?? '') == 'draft')
                                    <span class="badge bg-warning">پیش‌نویس</span>
                                @elseif(($inspection->status ?? '') == 'archived')
                                    <span class="badge bg-secondary">بایگانی</span>
                                @else
                                    <span class="badge bg-info">{{ $inspection->status ?? 'نامشخص' }}</span>
                                @endif
                            </td>
                            <td class="text-start">{{ number_format($inspection->total_cost ?? 0) }}</td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('inspection.show', $inspection->id) }}" class="btn btn-sm btn-info" target="_blank">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('inspection.edit', $inspection->id) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteInspection({{ $inspection->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <p class="mt-3">هیچ بازدیدی یافت نشد</p>
                                <a href="{{ route('inspection.form') }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-circle"></i> ثبت بازدید جدید
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(isset($inspections) && method_exists($inspections, 'links'))
            <div class="d-flex justify-content-center mt-4">
                {{ $inspections->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function deleteInspection(id) {
    if (confirm('آیا از حذف این بازدید اطمینان دارید؟')) {
        var form = document.getElementById('delete-form');
        form.action = '/dashboard/inspections/' + id;
        form.submit();
    }
}
</script>
@endsection