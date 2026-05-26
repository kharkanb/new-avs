@extends('layouts.admin')

@section('title', 'مدیریت کاربران')

@section('header', 'مدیریت کاربران')

@section('content')
<div class="container-fluid">
    <!-- دکمه کاربر جدید -->
    <div class="mb-4">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="bi bi-person-plus"></i> کاربر جدید
        </button>
    </div>

    <!-- جدول کاربران -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">لیست کاربران</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>نام</th>
                            <th>ایمیل</th>
                            <th>نقش</th>
                            <th>تاریخ عضویت</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody id="users-table-body">
                        @forelse($users ?? [] as $index => $user)
                        <tr data-user-id="{{ $user->id }}">
                            <td class="text-center">{{ $users->firstItem() + $index }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="role-badge {{ $user->role }}">
                                    @if($user->role == 'admin')
                                        مدیر سیستم
                                    @elseif($user->role == 'supervisor')
                                        ناظر
                                    @else
                                        کاربر عادی
                                    @endif
                                </span>
                            </td>
                            <td>{{ $user->created_at ? $user->created_at->format('Y/m/d') : '-' }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-info edit-user" data-id="{{ $user->id }}" data-name="{{ $user->name }}" data-email="{{ $user->email }}" data-role="{{ $user->role }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-user" data-id="{{ $user->id }}" data-name="{{ $user->name }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-people" style="font-size: 3rem;"></i>
                                <p class="mt-3">هیچ کاربری یافت نشد</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- پیجینیشن -->
            <div class="d-flex justify-content-center mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>

<!-- مودال افزودن کاربر جدید -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-person-plus"></i> افزودن کاربر جدید</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addUserForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label required">نام کامل</label>
                        <input type="text" class="form-control" id="name" name="name" required autocomplete="name">
                        <div class="invalid-feedback" id="name-error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label required">ایمیل</label>
                        <input type="email" class="form-control" id="email" name="email" required autocomplete="email">
                        <div class="invalid-feedback" id="email-error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label required">رمز عبور</label>
                        <input type="password" class="form-control" id="password" name="password" required autocomplete="new-password">
                        <div class="invalid-feedback" id="password-error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label required">نقش</label>
                        <select class="form-select" id="role" name="role">
                            <option value="user">کاربر عادی</option>
                            <option value="supervisor">ناظر</option>
                            <option value="admin">مدیر</option>
                        </select>
                        <div class="invalid-feedback" id="role-error"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                    <button type="submit" class="btn btn-primary" id="submitUserBtn">ذخیره</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- مودال ویرایش کاربر -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil-square"></i> ویرایش کاربر</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editUserForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_user_id" name="user_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label required">نام کامل</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required autocomplete="name">
                        <div class="invalid-feedback" id="edit-name-error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label required">ایمیل</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required autocomplete="email">
                        <div class="invalid-feedback" id="edit-email-error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_password" class="form-label">رمز عبور جدید (اختیاری)</label>
                        <input type="password" class="form-control" id="edit_password" name="password" autocomplete="new-password">
                        <div class="invalid-feedback" id="edit-password-error"></div>
                        <small class="text-muted">در صورت تمایل به تغییر رمز عبور، وارد کنید</small>
                    </div>
                    <div class="mb-3">
                        <label for="edit_role" class="form-label required">نقش</label>
                        <select class="form-select" id="edit_role" name="role">
                            <option value="user">کاربر عادی</option>
                            <option value="supervisor">ناظر</option>
                            <option value="admin">مدیر</option>
                        </select>
                        <div class="invalid-feedback" id="edit-role-error"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                    <button type="submit" class="btn btn-primary">به‌روزرسانی</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- مودال حذف کاربر -->
<div class="modal fade" id="deleteUserModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle"></i> حذف کاربر</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>آیا از حذف کاربر <strong id="delete-user-name"></strong> اطمینان دارید؟</p>
                <input type="hidden" id="delete_user_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">حذف</button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .role-badge {
        background: #e8f4fd;
        color: #3498db;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }
    .role-badge.admin {
        background: #fee2e2;
        color: #dc3545;
    }
    .role-badge.supervisor {
        background: #fff3cd;
        color: #856404;
    }
    .btn-group .btn {
        margin: 0 2px;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // افزودن کاربر جدید
    $('#addUserForm').on('submit', async function(e) {
        e.preventDefault();
        clearErrors('');
        
        const submitBtn = $('#submitUserBtn');
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> در حال ثبت...');
        
        const formData = {
            name: $('#name').val(),
            email: $('#email').val(),
            password: $('#password').val(),
            role: $('#role').val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        
        try {
            const response = await fetch('{{ route("dashboard.users.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: JSON.stringify(formData)
            });
            
            const result = await response.json();
            
            if (response.ok && result.success) {
                $('#addUserModal').modal('hide');
                $('#addUserForm')[0].reset();
                Swal.fire({
                    icon: 'success',
                    title: 'موفق!',
                    text: result.message || 'کاربر با موفقیت اضافه شد',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                if (result.errors) {
                    showErrors(result.errors, '');
                } else {
                    Swal.fire('خطا', result.message || 'خطا در ثبت اطلاعات', 'error');
                }
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire('خطا', 'خطا در ارتباط با سرور', 'error');
        } finally {
            submitBtn.prop('disabled', false).html('ذخیره');
        }
    });

    // ویرایش کاربر
    $('.edit-user').on('click', function() {
        const userId = $(this).data('id');
        const userName = $(this).data('name');
        const userEmail = $(this).data('email');
        const userRole = $(this).data('role');
        
        $('#edit_user_id').val(userId);
        $('#edit_name').val(userName);
        $('#edit_email').val(userEmail);
        $('#edit_role').val(userRole);
        $('#edit_password').val('');
        
        clearErrors('edit-');
        $('#editUserModal').modal('show');
    });

    // ارسال ویرایش
    $('#editUserForm').on('submit', async function(e) {
        e.preventDefault();
        clearErrors('edit-');
        
        const userId = $('#edit_user_id').val();
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> در حال به‌روزرسانی...');
        
        const formData = {
            name: $('#edit_name').val(),
            email: $('#edit_email').val(),
            password: $('#edit_password').val(),
            role: $('#edit_role').val(),
            _token: $('meta[name="csrf-token"]').attr('content'),
            _method: 'PUT'
        };
        
        try {
            const response = await fetch(`/dashboard/users/${userId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: JSON.stringify(formData)
            });
            
            const result = await response.json();
            
            if (response.ok && result.success) {
                $('#editUserModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'موفق!',
                    text: result.message || 'کاربر با موفقیت ویرایش شد',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                if (result.errors) {
                    showErrors(result.errors, 'edit-');
                } else {
                    Swal.fire('خطا', result.message || 'خطا در ویرایش اطلاعات', 'error');
                }
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire('خطا', 'خطا در ارتباط با سرور', 'error');
        } finally {
            submitBtn.prop('disabled', false).html('به‌روزرسانی');
        }
    });

    // حذف کاربر
    $('.delete-user').on('click', function() {
        const userId = $(this).data('id');
        const userName = $(this).data('name');
        
        $('#delete-user-name').text(userName);
        $('#delete_user_id').val(userId);
        $('#deleteUserModal').modal('show');
    });

    $('#confirmDeleteBtn').on('click', async function() {
        const userId = $('#delete_user_id').val();
        const deleteBtn = $(this);
        deleteBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> در حال حذف...');
        
        try {
            const response = await fetch(`/dashboard/users/${userId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            const result = await response.json();
            
            if (response.ok && result.success) {
                $('#deleteUserModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'حذف شد!',
                    text: result.message || 'کاربر با موفقیت حذف شد',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('خطا', result.message || 'خطا در حذف کاربر', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire('خطا', 'خطا در ارتباط با سرور', 'error');
        } finally {
            deleteBtn.prop('disabled', false).html('حذف');
        }
    });
});

// تابع نمایش خطاها
function showErrors(errors, prefix = '') {
    for (let field in errors) {
        let errorElement = document.getElementById(`${prefix}${field}-error`);
        let inputElement = document.getElementById(`${prefix}${field}`);
        if (errorElement) {
            errorElement.textContent = errors[field][0];
        }
        if (inputElement) {
            inputElement.classList.add('is-invalid');
        }
    }
}

// تابع پاک کردن خطاها
function clearErrors(prefix = '') {
    document.querySelectorAll(`[id$="-error"]`).forEach(el => {
        el.textContent = '';
    });
    document.querySelectorAll('.is-invalid').forEach(el => {
        el.classList.remove('is-invalid');
    });
}

// پاک کردن خطاها هنگام تایپ در فیلدها
$('input, select').on('input change', function() {
    $(this).removeClass('is-invalid');
    $(`#${this.id}-error`).text('');
});
</script>
@endpush
@endsection