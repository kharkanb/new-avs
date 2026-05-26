@extends('layouts.admin')

@section('title', 'گزارش فعالیت‌ها')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-clock-history"></i> تاریخچه فعالیت‌های کاربران</h5>
            @if(auth()->user()->role === 'admin')
            <button class="btn btn-sm btn-outline-danger" onclick="clearAllLogs()">
                <i class="bi bi-trash"></i> پاک کردن همه
            </button>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>کاربر</th>
                            <th>عملیات</th>
                            <th>رویداد</th>
                            <th>IP Address</th>
                            <th>تاریخ و زمان</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activities as $activity)
                        <tr>
                            <td>{{ $activity->id }}</td>
                            <td>{{ $activity->causer?->name ?? $activity->properties['user_name'] ?? 'سیستم' }}</td>
                            <td>{{ $activity->description }}</td>
                            <td>
                                @if($activity->event == 'login')
                                    <span class="badge bg-info">ورود</span>
                                @elseif($activity->event == 'logout')
                                    <span class="badge bg-secondary">خروج</span>
                                @elseif($activity->event == 'created')
                                    <span class="badge bg-success">ایجاد</span>
                                @elseif($activity->event == 'updated')
                                    <span class="badge bg-warning">ویرایش</span>
                                @elseif($activity->event == 'deleted')
                                    <span class="badge bg-danger">حذف</span>
                                @else
                                    <span class="badge bg-primary">سایر</span>
                                @endif
                            </td>
                            <td>{{ $activity->properties['ip_address'] ?? $activity->properties['ip'] ?? '-' }}</td>
                            <td>{{ \Hekmatinasser\Verta\Verta::instance($activity->created_at)->format('Y/m/d H:i') }}</td>
                            <td>
                                <button class="btn btn-sm btn-info" onclick="showLogDetails({{ $activity->id }})" title="جزئیات">
                                    <i class="bi bi-eye"></i>
                                </button>
                                @if(auth()->user()->role === 'admin')
                                <button class="btn btn-sm btn-danger" onclick="deleteLog({{ $activity->id }})" title="حذف">
                                    <i class="bi bi-trash"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <p class="mt-3">هیچ فعالیتی ثبت نشده است</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $activities->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// گرفتن توکن از localStorage
function getAuthToken() {
    return localStorage.getItem('auth_token');
}

function showLogDetails(id) {
    const token = getAuthToken();
    
    if (!token) {
        Swal.fire({
            icon: 'error',
            title: 'خطا',
            text: 'لطفاً دوباره وارد سیستم شوید',
            confirmButtonText: 'باشه'
        }).then(() => {
            window.location.href = '/login';
        });
        return;
    }
    
    Swal.fire({
        title: 'در حال بارگذاری...',
        text: 'لطفاً صبر کنید',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    fetch('/dashboard/activity-logs/' + id, {
        method: 'GET',
        headers: {
            'Authorization': 'Bearer ' + token,
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (response.status === 401) {
            throw new Error('Unauthorized');
        }
        return response.json();
    })
    .then(data => {
        var oldData = data.old_data ? JSON.stringify(data.old_data, null, 2) : '---';
        var newData = data.new_data ? JSON.stringify(data.new_data, null, 2) : '---';
        
        Swal.fire({
            title: 'جزئیات فعالیت',
            html: `
                <div style="text-align: right; direction: rtl;">
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
                        <tr><td style="padding: 5px; border: 1px solid #ddd; background: #f5f5f5; width: 30%;">کاربر:<\/td><td style="padding: 5px; border: 1px solid #ddd;">${data.user_name || '-'}<\/td></tr>
                        <tr><td style="padding: 5px; border: 1px solid #ddd; background: #f5f5f5;">فعالیت:<\/td><td style="padding: 5px; border: 1px solid #ddd;">${data.action || '-'}<\/td></tr>
                        <tr><td style="padding: 5px; border: 1px solid #ddd; background: #f5f5f5;">نوع رویداد:<\/td><td style="padding: 5px; border: 1px solid #ddd;">${data.event || '-'}<\/td></tr>
                        <tr><td style="padding: 5px; border: 1px solid #ddd; background: #f5f5f5;">IP:<\/td><td style="padding: 5px; border: 1px solid #ddd;">${data.ip_address || '-'}<\/td></tr>
                        <tr><td style="padding: 5px; border: 1px solid #ddd; background: #f5f5f5;">تاریخ:<\/td><td style="padding: 5px; border: 1px solid #ddd;">${data.created_at || '-'}<\/td></tr>
                    </table>
                    <hr>
                    <p><strong>داده‌های قبلی:</strong></p>
                    <pre style="background: #f4f4f4; padding: 10px; border-radius: 5px; max-height: 200px; overflow: auto; font-size: 12px; text-align: left;">${oldData}</pre>
                    <p><strong>داده‌های جدید:</strong></p>
                    <pre style="background: #f4f4f4; padding: 10px; border-radius: 5px; max-height: 200px; overflow: auto; font-size: 12px; text-align: left;">${newData}</pre>
                </div>
            `,
            width: '800px',
            confirmButtonText: 'بستن',
            confirmButtonColor: '#3498db'
        });
    })
    .catch(error => {
        if (error.message === 'Unauthorized') {
            Swal.fire({
                icon: 'error',
                title: 'عدم احراز هویت',
                text: 'لطفاً دوباره وارد سیستم شوید',
                confirmButtonText: 'ورود'
            }).then(() => {
                window.location.href = '/login';
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'خطا',
                text: 'مشکلی در دریافت جزئیات پیش آمد',
                confirmButtonText: 'باشه'
            });
        }
        console.error('Error:', error);
    });
}

function deleteLog(id) {
    const token = getAuthToken();
    
    Swal.fire({
        title: 'آیا از حذف این فعالیت اطمینان دارید؟',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'بله، حذف کن',
        cancelButtonText: 'انصراف',
        confirmButtonColor: '#dc3545'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('/dashboard/activity-logs/' + id, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'حذف شد!',
                        text: 'فعالیت با موفقیت حذف شد',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطا',
                        text: data.message || 'خطا در حذف فعالیت'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'خطا',
                    text: 'خطا در ارتباط با سرور'
                });
                console.error('Error:', error);
            });
        }
    });
}

function clearAllLogs() {
    Swal.fire({
        title: 'آیا از پاک کردن تمام فعالیت‌ها اطمینان دارید؟',
        text: 'این عمل قابل بازگشت نیست!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'بله، پاک کن',
        cancelButtonText: 'انصراف',
        confirmButtonColor: '#dc3545'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('/dashboard/activity-logs/clear', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'پاک شد!',
                        text: 'همه فعالیت‌ها با موفقیت حذف شدند',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطا',
                        text: data.message || 'خطا در حذف فعالیت‌ها'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'خطا',
                    text: 'خطا در ارتباط با سرور'
                });
                console.error('Error:', error);
            });
        }
    });
}
</script>
@endpush