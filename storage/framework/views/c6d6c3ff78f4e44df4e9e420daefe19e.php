

<?php $__env->startSection('title', 'گزارش‌ها'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">گزارش‌های سیستم</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- گزارش روزانه -->
                        <div class="col-md-4 mb-3">
                            <a href="<?php echo e(route('reports.daily')); ?>" class="text-decoration-none">
                                <div class="card text-center h-100 shadow-sm">
                                    <div class="card-body">
                                        <i class="bi bi-calendar-day" style="font-size: 48px; color: #3498db;"></i>
                                        <h5 class="mt-3">گزارش روزانه</h5>
                                        <p class="text-muted small mb-0">آمار بازدیدهای امروز</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        <!-- گزارش ماهانه -->
                        <div class="col-md-4 mb-3">
                            <a href="<?php echo e(route('reports.monthly')); ?>" class="text-decoration-none">
                                <div class="card text-center h-100 shadow-sm">
                                    <div class="card-body">
                                        <i class="bi bi-calendar-month" style="font-size: 48px; color: #3498db;"></i>
                                        <h5 class="mt-3">گزارش ماهانه</h5>
                                        <p class="text-muted small mb-0">آمار بازدیدهای ماه جاری</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        <!-- گزارش خرابی‌ها -->
                        <div class="col-md-4 mb-3">
                            <a href="<?php echo e(route('reports.failures')); ?>" class="text-decoration-none">
                                <div class="card text-center h-100 shadow-sm">
                                    <div class="card-body">
                                        <i class="bi bi-exclamation-triangle" style="font-size: 48px; color: #e74c3c;"></i>
                                        <h5 class="mt-3">گزارش خرابی‌ها</h5>
                                        <p class="text-muted small mb-0">موارد Not OK چک‌لیست</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        <!-- صورت وضعیت مالی -->
                        <div class="col-md-4 mb-3">
                            <a href="<?php echo e(route('reports.financial')); ?>" class="text-decoration-none">
                                <div class="card text-center h-100 shadow-sm">
                                    <div class="card-body">
                                        <i class="bi bi-cash-stack" style="font-size: 48px; color: #27ae60;"></i>
                                        <h5 class="mt-3">صورت وضعیت مالی</h5>
                                        <p class="text-muted small mb-0">محاسبه هزینه به تفکیک پیمانکار</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        <!-- نتایج چک‌لیست‌ها -->
                        <div class="col-md-4 mb-3">
                            <a href="<?php echo e(route('reports.checklist-results')); ?>" class="text-decoration-none">
                                <div class="card text-center h-100 shadow-sm">
                                    <div class="card-body">
                                        <i class="bi bi-check2-square" style="font-size: 48px; color: #3498db;"></i>
                                        <h5 class="mt-3">نتایج چک‌لیست</h5>
                                        <p class="text-muted small mb-0">آمار OK/Not OK چک‌لیست‌ها</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        <!-- خروجی PDF -->
                        <div class="col-md-4 mb-3">
                            <a href="<?php echo e(route('reports.export', 'pdf')); ?>" class="text-decoration-none">
                                <div class="card text-center h-100 shadow-sm">
                                    <div class="card-body">
                                        <i class="bi bi-file-pdf" style="font-size: 48px; color: #e74c3c;"></i>
                                        <h5 class="mt-3">خروجی PDF</h5>
                                        <p class="text-muted small mb-0">دریافت گزارش PDF</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        <!-- خروجی Excel -->
                        <div class="col-md-4 mb-3">
                            <a href="<?php echo e(route('reports.export', 'excel')); ?>" class="text-decoration-none">
                                <div class="card text-center h-100 shadow-sm">
                                    <div class="card-body">
                                        <i class="bi bi-file-excel" style="font-size: 48px; color: #27ae60;"></i>
                                        <h5 class="mt-3">خروجی Excel</h5>
                                        <p class="text-muted small mb-0">دریافت گزارش Excel</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        <!-- نمودارها -->
                        <div class="col-md-4 mb-3">
                            <a href="<?php echo e(route('reports.charts')); ?>" class="text-decoration-none">
                                <div class="card text-center h-100 shadow-sm">
                                    <div class="card-body">
                                        <i class="bi bi-graph-up" style="font-size: 48px; color: #9b59b6;"></i>
                                        <h5 class="mt-3">نمودارها</h5>
                                        <p class="text-muted small mb-0">نمودارهای آماری</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dear-user\Desktop\new-avs\resources\views/reports/index.blade.php ENDPATH**/ ?>