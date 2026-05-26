@extends('layouts.admin')

@section('title', 'مدیریت پیمانکاران')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">لیست پیمانکاران</h5>
        <a href="{{ route('dashboard.contractors.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> افزودن پیمانکار جدید
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>نام پیمانکار</th>
                        <th>ضریب قرارداد</th>
                        <th>شماره قرارداد</th>
                        <th>تلفن</th>
                        <th>واتساپ</th>
                        <th>آدرس</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $index => $contractor)
                    <tr>
                        <td>{{ $items->firstItem() + $index }}</td>
                        <td>{{ $contractor->name }}</td>
                        <td>{{ $contractor->coefficient }}</td>
                        <td>{{ $contractor->contract_number ?? '---' }}</td>
                        <td>{{ $contractor->phone ?? '---' }}</td>
                        <td>{{ $contractor->whatsapp ?? '---' }}</td>
                        <td>{{ Str::limit($contractor->address ?? '---', 30) }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('dashboard.contractors.edit', $contractor) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteContractor({{ $contractor->id }}, '{{ $contractor->name }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                            <p class="mt-3">هیچ پیمانکاری ثبت نشده است</p>
                            <a href="{{ route('dashboard.contractors.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> افزودن اولین پیمانکار
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $items->links() }}
        </div>
    </div>
</div>

<script>
function deleteContractor(id, name) {
    if (confirm('آیا از حذف پیمانکار "' + name + '" اطمینان دارید؟')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/dashboard/contractors/' + id;
        form.innerHTML = '@csrf @method("DELETE")';
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection