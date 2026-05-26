<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ویرایش بازدید</title>
    <link href="<?php echo e(asset('css/bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/bootstrap-icons.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/Vazirmatn-font-face.css')); ?>" rel="stylesheet">
    <style>
        body { font-family: 'Vazirmatn', 'Tahoma', sans-serif; background: #f0f2f5; direction: rtl; }
        .card { margin-top: 30px; box-shadow: 0 0 20px rgba(0,0,0,0.1); border-radius: 15px; }
        .card-header { background: linear-gradient(135deg, #f39c12, #e67e22); color: white; border-radius: 15px 15px 0 0; padding: 20px; }
        .info-row { margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #eee; }
        .info-label { font-weight: bold; color: #2c3e50; width: 180px; display: inline-block; }
        .info-value { color: #555; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3><i class="bi bi-pencil-square"></i> ویرایش بازدید</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i>
                    <strong>توجه:</strong> برای ویرایش بازدید، لطفاً از فرم بازدید جدید استفاده کنید و سپس این بازدید را حذف نمایید.
                </div>
                
                <div class="info-row">
                    <span class="info-label"><i class="bi bi-hash"></i> شناسه:</span>
                    <span class="info-value"><?php echo e($inspection->id); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label"><i class="bi bi-calendar"></i> تاریخ بازدید:</span>
                    <span class="info-value"><?php echo e($inspection->jalali_date ?? $inspection->inspection_date); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label"><i class="bi bi-person"></i> پیمانکار:</span>
                    <span class="info-value"><?php echo e($inspection->contractor_name ?? $inspection->contractor ?? '-'); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label"><i class="bi bi-percent"></i> ضریب قرارداد:</span>
                    <span class="info-value"><?php echo e($inspection->contract_coefficient ?? '-'); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label"><i class="bi bi-flag"></i> وضعیت:</span>
                    <span class="info-value">
                        <span class="badge bg-<?php echo e($inspection->status == 'completed' ? 'success' : 'warning'); ?>">
                            <?php echo e($inspection->status == 'completed' ? 'تکمیل شده' : 'پیش‌نویس'); ?>

                        </span>
                    </span>
                </div>
                
                <hr>
                <h5><i class="bi bi-hdd-stack"></i> تجهیزات این بازدید (<?php echo e($inspection->mainEquipments->count()); ?>)</h5>
                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>نوع تجهیز</th>
                                <th>کد اسکادا</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $inspection->mainEquipments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $equipment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($index + 1); ?></td>
                                <td><?php echo e($equipment->mainEquipmentType->name ?? '-'); ?></td>
                                <td><?php echo e($equipment->scada_code ?? '-'); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4 text-center">
                    <a href="<?php echo e(route('inspection.form')); ?>" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> ثبت بازدید جدید
                    </a>
                    <a href="<?php echo e(route('inspection.show', $inspection->id)); ?>" class="btn btn-info">
                        <i class="bi bi-eye"></i> مشاهده بازدید
                    </a>
                    <a href="<?php echo e(route('dashboard.inspections')); ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-right"></i> بازگشت به لیست
                    </a>
                    <?php if(auth()->user()->role === 'admin'): ?>
                    <form action="<?php echo e(route('inspections.destroy', $inspection->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('آیا از حذف این بازدید اطمینان دارید؟')">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> حذف بازدید
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html><?php /**PATH C:\Users\dear-user\Desktop\new-avs\resources\views/inspections/edit.blade.php ENDPATH**/ ?>