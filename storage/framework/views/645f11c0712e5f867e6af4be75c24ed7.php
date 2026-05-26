

<?php $__env->startSection('title', 'مشاهده بازدید'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-eye"></i> جزئیات بازدید</h5>
            <div>
                <a href="<?php echo e(route('dashboard.inspections')); ?>" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-right"></i> بازگشت
                </a>
                <a href="<?php echo e(route('inspection.edit', $inspection->id)); ?>" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil"></i> ویرایش
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- اطلاعات اصلی -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 180px">شناسه:</th>
                            <td><?php echo e($inspection->id); ?></td>
                        </tr>
                        <tr>
                            <th>تاریخ بازدید:</th>
                            <td><?php echo e($inspection->jalali_date ?? $inspection->inspection_date); ?></td>
                        </tr>
                        <tr>
                            <th>پیمانکار:</th>
                            <td><?php echo e($inspection->contractor_name ?? $inspection->contractor ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <th>ضریب قرارداد:</th>
                            <td><?php echo e($inspection->contract_coefficient ?? '-'); ?></td>
                        </tr>
        		<tr>
            		         <th>امور/شهرستان:</th>
            		         <td><?php echo e($inspection->department->name ?? '-'); ?></td>  <!-- اضافه شده -->
        			</tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 180px">شماره قرارداد:</th>
                            <td><?php echo e($inspection->contract_number ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <th>ساعت شروع:</th>
                            <td><?php echo e($inspection->daily_start_time ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <th>ساعت پایان:</th>
                            <td><?php echo e($inspection->daily_end_time ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <th>وضعیت:</th>
                            <td>
                                <span class="badge bg-<?php echo e($inspection->status == 'completed' ? 'success' : 'warning'); ?>">
                                    <?php echo e($inspection->status == 'completed' ? 'تکمیل شده' : 'پیش‌نویس'); ?>

                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- هزینه کل -->
            <div class="alert alert-info text-center">
                <h5>هزینه کل بازدید: <strong><?php echo e(number_format($inspection->total_cost ?? 0)); ?> ریال</strong></h5>
            </div>

            <!-- لیست تجهیزات -->
            <h5 class="mt-4"><i class="bi bi-hdd-stack"></i> لیست تجهیزات (<?php echo e($inspection->mainEquipments->count()); ?>)</h5>
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>نوع تجهیز</th>
                            <th>کد اسکادا</th>
                            <th>نوع نصب</th>
                            <th>برند</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $inspection->mainEquipments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $equipment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="text-center"><?php echo e($index + 1); ?></td>
                            <td><?php echo e($equipment->mainEquipmentType->name ?? '-'); ?></td>
                            <td><?php echo e($equipment->scada_code ?? '-'); ?></td>
                            <td><?php echo e($equipment->installation_type ?? '-'); ?></td>
                            <td><?php echo e($equipment->brand->name ?? $equipment->other_brand ?? '-'); ?></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#equipmentModal<?php echo e($equipment->id); ?>">
                                    <i class="bi bi-info-circle"></i> جزئیات
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <?php if($inspection->mainEquipments->isEmpty()): ?>
            <div class="alert alert-warning text-center">
                <i class="bi bi-exclamation-triangle"></i> هیچ تجهیزی برای این بازدید ثبت نشده است.
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- مودال‌های جزئیات تجهیزات -->
<?php $__currentLoopData = $inspection->mainEquipments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $equipment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="equipmentModal<?php echo e($equipment->id); ?>" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-gear"></i> جزئیات تجهیز</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
            </div>
            <div class="modal-body">
                <?php
                    $location = $equipment->equipmentLocation;
                    $communication = $equipment->equipmentCommunication;
                    $feeders = $equipment->equipmentFeeders;
                ?>

                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered table-sm">
                            <tr><th style="width: 40%">نوع تجهیز:</th><td><?php echo e($equipment->mainEquipmentType->name ?? '-'); ?></td></tr>
                            <tr><th>کد اسکادا:</th><td><?php echo e($equipment->scada_code ?? '-'); ?></td></tr>
                            <tr><th>نوع نصب:</th><td><?php echo e($equipment->installation_type ?? '-'); ?></td></tr>
                            <tr><th>برند:</th><td><?php echo e($equipment->brand->name ?? $equipment->other_brand ?? '-'); ?></td></tr>
                            <tr><th>امور/شهرستان:</th><td><?php echo e($inspection->department->name ?? '-'); ?></td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered table-sm">
                            <tr>
                                <th style="width: 40%">موقعیت جغرافیایی:</th>
                                <td>
                                    عرض: <?php echo e($location->latitude ?? '-'); ?><br>
                                    طول: <?php echo e($location->longitude ?? '-'); ?>

                                </td>
                            </tr>
                            <tr>
                                <th>ارتفاع:</th>
                                <td>
                                    ارتفاع اولیه: <?php echo e($location->cabinet_initial_height ?? '-'); ?> متر<br>
                                    ارتفاع نهایی: <?php echo e($location->cabinet_final_height ?? '-'); ?> متر
                                </td>
                            </tr>
                            <tr>
                                <th>آدرس:</th>
                                <td><?php echo e($location->address ?? '-'); ?></td>
                            </tr>
                            <tr>
                                <th>ارتباطات:</th>
                                <td>
                                    نوع سیم‌کارت: <?php echo e($communication->simcard_type ?? '-'); ?><br>
                                    شماره سیم‌کارت: <?php echo e($communication->simcard_number ?? '-'); ?><br>
                                    IP: <?php echo e($communication->simcard_ip ?? '-'); ?><br>
                                    وضعیت آنتن: <?php echo e($communication->antenna_status ?? '-'); ?><br>
                                    وضعیت سیگنال: <?php echo e($communication->signal_status ?? '-'); ?><br>
                                    تغذیه مودم: <?php echo e($communication->modem_power ?? '-'); ?>

                                </td>
                            </tr>
                        </table>
                        <table class="table table-bordered table-sm mt-2">
                            <tr>
                                <th style="width: 40%">پست/فیدر:</th>
                                <td>
                                    <?php $__empty_1 = true; $__currentLoopData = $feeders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feeder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        پست: <?php echo e($feeder->post ?? '-'); ?><br>
                                        فیدر: <?php echo e($feeder->feeder ?? '-'); ?>

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        -
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <?php if($equipment->checklists->count()): ?>
                <hr>
                <h6><i class="bi bi-clipboard-check"></i> چک‌لیست</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr><th>آیتم</th><th style="width: 100px">وضعیت</th><th>توضیحات</th></tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $equipment->checklists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $checklist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($checklist->item); ?></td>
                                <td class="<?php echo e($checklist->status == 'OK' ? 'text-success' : 'text-danger'); ?> fw-bold"><?php echo e($checklist->status); ?></td>
                                <td><?php echo e($checklist->description ?? '-'); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>

                <?php if($equipment->activities->count()): ?>
                <hr>
                <h6><i class="bi bi-list-check"></i> فعالیت‌های انجام شده</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr><th>کد</th><th>عنوان</th><th>تعداد</th><th>مبلغ</th></tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $equipment->activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td style="white-space: nowrap;"><?php echo e($activity->code); ?></td>
                                <td><?php echo e($activity->title); ?></td>
                                <td class="text-center"><?php echo e($activity->quantity); ?></td>
                                <td class="text-start" style="white-space: nowrap;"><?php echo e(number_format($activity->total)); ?> ریال</td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // رفع مشکل aria-hidden در مودال‌ها
    $('.modal').on('show.bs.modal', function() {
        $(this).removeAttr('aria-hidden');
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dear-user\Desktop\new-avs\resources\views/inspections/show.blade.php ENDPATH**/ ?>