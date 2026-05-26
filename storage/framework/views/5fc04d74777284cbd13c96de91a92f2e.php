

<?php $__env->startSection('title', 'گزارش روزانه بازدیدها'); ?>

<?php $__env->startSection('header', 'گزارش روزانه'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- فیلترها -->
    <div class="filter-section">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-funnel"></i> فیلترهای پیشرفته</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="<?php echo e(route('reports.daily')); ?>" id="filterForm">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">از تاریخ</label>
                            <input type="text" class="form-control persian-date" name="start_date" 
                                   id="start_date" value="<?php echo e(request('start_date')); ?>" autocomplete="off">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">تا تاریخ</label>
                            <input type="text" class="form-control persian-date" name="end_date" 
                                   id="end_date" value="<?php echo e(request('end_date')); ?>" autocomplete="off">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">پیمانکار</label>
                            <select name="contractor" class="form-select">
                                <option value="">همه پیمانکاران</option>
                                <?php $__currentLoopData = $contractors ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contractorItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($contractorItem); ?>" <?php echo e(request('contractor') == $contractorItem ? 'selected' : ''); ?>>
                                        <?php echo e($contractorItem); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">نوع تجهیز</label>
                            <select name="equipment_type" class="form-select">
                                <option value="">همه تجهیزات</option>
                                <?php $__currentLoopData = $equipmentTypes ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($type->id); ?>" <?php echo e(request('equipment_type') == $type->id ? 'selected' : ''); ?>>
                                        <?php echo e($type->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">وضعیت</label>
                            <select name="status" class="form-select">
                                <option value="all">همه</option>
                                <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>تکمیل شده</option>
                                <option value="draft" <?php echo e(request('status') == 'draft' ? 'selected' : ''); ?>>پیش‌نویس</option>
                                <option value="archived" <?php echo e(request('status') == 'archived' ? 'selected' : ''); ?>>بایگانی</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i> اعمال فیلتر
                            </button>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <a href="<?php echo e(route('reports.daily')); ?>" class="btn btn-secondary w-100">
                                <i class="bi bi-x-circle"></i> حذف فیلترها
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- خلاصه آمار -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="summary-box">
                <h5>تعداد بازدیدها</h5>
                <h2><?php echo e(number_format($todayInspections ?? 0)); ?></h2>
                <?php if(isset($compareText)): ?>
                <small class="text-<?php echo e(($todayInspections ?? 0) > ($yesterdayInspections ?? 0) ? 'success' : 'danger'); ?>">
                    <i class="bi bi-arrow-<?php echo e(($todayInspections ?? 0) > ($yesterdayInspections ?? 0) ? 'up' : 'down'); ?>"></i>
                    <?php echo e($compareText ?? ''); ?>

                </small>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-4">
            <div class="summary-box">
                <h5>تعداد تجهیزات بازدید شده</h5>
                <h2><?php echo e(number_format($todayEquipments ?? 0)); ?></h2>
                <small>میانگین <?php echo e(number_format($avgEquipments ?? 0)); ?> تجهیز در هر بازدید</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="summary-box">
                <h5>پیمانکاران فعال</h5>
                <h2><?php echo e(number_format($activeContractors ?? 0)); ?></h2>
                <small><?php echo e($contractorList ?? '---'); ?></small>
            </div>
        </div>
    </div>

    <!-- جدول بازدیدها -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">لیست بازدیدها</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>تاریخ</th>
                            <th>پیمانکار</th>
                            <th>تعداد تجهیزات</th>
                            <th>زمان شروع</th>
                            <th>زمان پایان</th>
                            <th>کاربر ثبت‌کننده</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $inspectionsList ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $inspection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="text-center"><?php echo e($loop->iteration); ?></td>
                            <td><?php echo e($inspection->inspection_date); ?></td>
                            <td><?php echo e($inspection->contractor); ?></td>
                            <td class="text-center"><?php echo e($inspection->mainEquipments->count()); ?></td>
                            <td><?php echo e($inspection->daily_start_time ?? '---'); ?></td>
                            <td><?php echo e($inspection->daily_end_time ?? '---'); ?></td>
                            <td><?php echo e($inspection->user->name ?? 'نامشخص'); ?></td>
                            <td>
                                <a href="<?php echo e(route('inspections.show', $inspection->id)); ?>" class="btn btn-sm btn-info" target="_blank">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <p class="mt-3">هیچ بازدیدی در بازه زمانی انتخاب شده یافت نشد</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- دکمه‌های پایین صفحه -->
    <div class="text-center mt-4">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="bi bi-printer"></i> چاپ گزارش
        </button>
        <a href="<?php echo e(route('reports.index')); ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-right"></i> بازگشت به گزارشات
        </a>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('js/jquery.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/moment.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/moment-jalaali.js')); ?>"></script>
<script src="<?php echo e(asset('js/persian-date.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/persian-datepicker.min.js')); ?>"></script>
<link href="<?php echo e(asset('css/persian-datepicker.min.css')); ?>" rel="stylesheet">

<script>
$(document).ready(function() {
    // تنظیم تاریخ‌های پیش‌فرض (امروز)
    var today = new Date();
    var persianToday = new persianDate(today);
    var todayFormatted = persianToday.format('YYYY/MM/DD');
    
    if (!$('#start_date').val()) {
        $('#start_date').val(todayFormatted);
    }
    if (!$('#end_date').val()) {
        $('#end_date').val(todayFormatted);
    }
    
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
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dear-user\Desktop\new-avs\resources\views/reports/daily.blade.php ENDPATH**/ ?>