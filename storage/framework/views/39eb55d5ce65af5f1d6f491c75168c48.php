

<?php $__env->startSection('title', 'لیست بازدیدها'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-list-check"></i> لیست بازدیدها</h5>
            <a href="<?php echo e(route('inspection.form')); ?>" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> بازدید جدید
            </a>
        </div>
        <div class="card-body">
            <!-- فیلترها -->
<!-- فیلترها -->
<!-- فیلترها -->
<form method="GET" action="<?php echo e(route('dashboard.inspections')); ?>" class="mb-4">
    <div class="row">

        <div class="col-md-3 mb-2">
            <label class="form-label">از تاریخ (شمسی)</label>
            <input type="text" name="start_date" class="form-control persian-date" value="<?php echo e(request('start_date')); ?>" autocomplete="off" placeholder="1404/01/01">
        </div>
        <div class="col-md-3 mb-2">
            <label class="form-label">تا تاریخ (شمسی)</label>
            <input type="text" name="end_date" class="form-control persian-date" value="<?php echo e(request('end_date')); ?>" autocomplete="off" placeholder="1404/12/29">
        </div>

        
        <div class="col-md-3 mb-2">
            <label class="form-label">پیمانکار</label>
            <select name="contractor" class="form-select">
                <option value="">همه</option>
                <?php $__currentLoopData = $contractors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contractor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($contractor->id); ?>" <?php echo e(request('contractor') == $contractor->id ? 'selected' : ''); ?>>
                        <?php echo e($contractor->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        
        <div class="col-md-3 mb-2">
            <label class="form-label">امور/شهرستان</label>
            <select name="department" class="form-select">
                <option value="">همه</option>
                <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($department->id); ?>" <?php echo e(request('department') == $department->id ? 'selected' : ''); ?>>
                        <?php echo e($department->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        
        <div class="col-md-3 mb-2">
            <label class="form-label">نوع تجهیز</label>
            <select name="equipment_type" class="form-select">
                <option value="">همه</option>
                <?php $__currentLoopData = $equipmentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($type->id); ?>" <?php echo e(request('equipment_type') == $type->id ? 'selected' : ''); ?>>
                        <?php echo e($type->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        
        <!-- دکمه‌ها -->
        <div class="col-md-3 mb-2 d-flex align-items-end">
            <div class="d-flex gap-2 w-100">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="bi bi-search"></i> فیلتر
                </button>
                <a href="<?php echo e(route('dashboard.inspections')); ?>" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> حذف فیلتر
                </a>
            </div>
        </div>
    </div>
</form>  
          <div class="table-responsive">
                <table class="table table-bordered table-hover" style="min-width: 800px;">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px">#</th>
                            <th style="width: 110px">تاریخ بازدید</th>
                            <th style="width: 150px">پیمانکار</th>
                            <th style="width: 130px">امور/شهرستان</th>
                            <th style="width: 100px">تعداد تجهیزات</th>
                            <th style="width: 120px">کاربر</th>
                            <th style="width: 100px">وضعیت</th>
                            <th style="width: 110px">عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $inspections ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $inspection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<?php
    // تبدیل تاریخ به شمسی
    try {
        if (class_exists(\Hekmatinasser\Verta\Verta::class)) {
            $v = new \Hekmatinasser\Verta\Verta($inspection->inspection_date);
            $jalaliDate = $v->format('Y/m/d');
        } else {
            $jalaliDate = $inspection->inspection_date ?? '-';
        }
    } catch (\Exception $e) {
        $jalaliDate = $inspection->inspection_date ?? '-';
    }
    
    // نام پیمانکار - اصلاح شده کامل
    $contractorName = '-';
    
    // روش 1: contractor_name مستقیم
    if (!empty($inspection->contractor_name)) {
        $contractorName = $inspection->contractor_name;
    }
    // روش 2: contractor به صورت آبجکت
    elseif (is_object($inspection->contractor) && isset($inspection->contractor->name)) {
        $contractorName = $inspection->contractor->name;
    }
    // روش 3: contractor به صورت JSON string
    elseif (is_string($inspection->contractor) && !empty($inspection->contractor)) {
        try {
            $data = json_decode($inspection->contractor, true);
            if (isset($data['name'])) {
                $contractorName = $data['name'];
            } elseif (isset($data[0]['name'])) {
                $contractorName = $data[0]['name'];
            }
        } catch (\Exception $e) {}
    }
    // روش 4: از طریق رابطه contractor_id
    elseif ($inspection->contractor_id && isset($inspection->contractor) && is_object($inspection->contractor)) {
        $contractorName = $inspection->contractor->name ?? '-';
    }
    
    // نام امور/شهرستان
    $departmentName = '-';
    if ($inspection->department_id && $inspection->department) {
        $departmentName = $inspection->department->name;
    } elseif ($inspection->mainEquipments && $inspection->mainEquipments->isNotEmpty()) {
        $firstEquipment = $inspection->mainEquipments->first();
        if ($firstEquipment && $firstEquipment->department) {
            $departmentName = $firstEquipment->department->name;
        }
    }
    
    // تعیین رنگ و متن وضعیت
    $statusClass = 'secondary';
    $statusText = 'نامشخص';
    if ($inspection->status == 'completed') {
        $statusClass = 'success';
        $statusText = 'تکمیل شده';
    } elseif ($inspection->status == 'draft') {
        $statusClass = 'warning';
        $statusText = 'پیش‌نویس';
    } elseif ($inspection->status == 'archived') {
        $statusClass = 'secondary';
        $statusText = 'بایگانی';
    }
?>


                        <tr>
                            <td class="text-center"><?php echo e(($inspections->currentPage() - 1) * $inspections->perPage() + $loop->iteration); ?></td>
                            <td class="text-nowrap"><?php echo e($jalaliDate); ?></td>
                            <td><?php echo e($contractorName); ?></td>
                            <td><?php echo e($departmentName); ?></td>
                            <td class="text-center"><?php echo e($inspection->mainEquipments->count() ?? 0); ?></td>
                            <td><?php echo e($inspection->user->name ?? '-'); ?></td>
                            <td>
                                <span class="badge bg-<?php echo e($statusClass); ?>"><?php echo e($statusText); ?></span>
                            </td>
                            <td class="text-center">
    <div class="btn-group" role="group">
        <!-- دکمه مشاهده - اصلاح شد -->
        <a href="<?php echo e(route('inspection.show', $inspection->id)); ?>" class="btn btn-sm btn-info" title="مشاهده">
            <i class="bi bi-eye"></i>
        </a>
        
<a href="<?php echo e(route('inspection.edit', $inspection->id)); ?>" class="btn btn-sm btn-warning" title="ویرایش">
    <i class="bi bi-pencil"></i>
</a>
        
        <?php if(auth()->user()->role === 'admin'): ?>
        <button type="button" class="btn btn-sm btn-danger" onclick="deleteInspection(<?php echo e($inspection->id); ?>)" title="حذف">
            <i class="bi bi-trash"></i>
        </button>
        <?php endif; ?>
    </div>
</td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <p class="mt-3">هیچ بازدیدی یافت نشد</p>
                                <a href="<?php echo e(route('inspection.form')); ?>" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-circle"></i> ثبت بازدید جدید
                                </a>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                <?php echo e($inspections->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .persian-date {
        direction: ltr;
        text-align: right;
    }
    .table td {
        vertical-align: middle;
    }
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }
    .table th, .table td {
        white-space: nowrap;
    }
    .table {
        width: 100%;
        margin-bottom: 1rem;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('js/jquery.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/persian-date.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/persian-datepicker.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/select2.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/bootstrap.bundle.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/sweetalert2.min.js')); ?>"></script>
<link href="<?php echo e(asset('css/persian-datepicker.min.css')); ?>" rel="stylesheet">

<script>
$(document).ready(function() {
    // فعال‌سازی تقویم شمسی
    $('.persian-date').persianDatepicker({
        format: 'YYYY/MM/DD',
        autoClose: true,
        initialValue: false,
        calendar: {
            persian: {
                locale: 'fa'
            }
        }
    });
});

// تابع حذف بازدید (برای ادمین)
function deleteInspection(id) {
    if (confirm('آیا از حذف این بازدید اطمینان دارید؟')) {
        fetch(`/api/inspections/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success || data.message) {
                alert('بازدید با موفقیت حذف شد');
                location.reload();
            } else {
                alert('خطا در حذف بازدید');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('خطا در ارتباط با سرور');
        });
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dear-user\Desktop\new-avs\resources\views/dashboard/inspections.blade.php ENDPATH**/ ?>