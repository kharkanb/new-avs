

<?php $__env->startSection('title', 'داشبورد'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- کارت‌های آماری ردیف اول -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div style="background: linear-gradient(135deg, #3498db, #2980b9); border-radius: 15px; padding: 20px; text-align: center; color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                <i class="bi bi-calendar-check" style="font-size: 35px;"></i>
                <h2 style="margin: 10px 0; font-size: 32px;"><?php echo e(number_format($stats['total_inspections'] ?? 0)); ?></h2>
                <p style="margin: 0; opacity: 0.9;">تعداد بازدیدها</p>
            </div>
        </div>
        <div class="col-md-3">
            <div style="background: linear-gradient(135deg, #f39c12, #e67e22); border-radius: 15px; padding: 20px; text-align: center; color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                <i class="bi bi-hdd-stack" style="font-size: 35px;"></i>
                <h2 style="margin: 10px 0; font-size: 32px;"><?php echo e(number_format($stats['total_equipments'] ?? 0)); ?></h2>
                <p style="margin: 0; opacity: 0.9;">تعداد تجهیزات</p>
            </div>
        </div>
        <div class="col-md-3">
            <div style="background: linear-gradient(135deg, #27ae60, #1e8449); border-radius: 15px; padding: 20px; text-align: center; color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                <i class="bi bi-list-check" style="font-size: 35px;"></i>
                <h2 style="margin: 10px 0; font-size: 32px;"><?php echo e(number_format($stats['total_activities'] ?? 0)); ?></h2>
                <p style="margin: 0; opacity: 0.9;">کل فعالیت‌ها</p>
            </div>
        </div>
        <div class="col-md-3">
            <div style="background: linear-gradient(135deg, #e74c3c, #c0392b); border-radius: 15px; padding: 20px; text-align: center; color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                <i class="bi bi-cash-stack" style="font-size: 35px;"></i>
                <h2 style="margin: 10px 0; font-size: 32px;"><?php echo e(number_format($totalCost ?? 0)); ?></h2>
                <p style="margin: 0; opacity: 0.9;">هزینه کل (ریال)</p>
            </div>
        </div>
    </div>

    <!-- کارت‌های آماری ردیف دوم -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div style="background: linear-gradient(135deg, #9b59b6, #7d3c98); border-radius: 15px; padding: 20px; text-align: center; color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                <i class="bi bi-calendar-day" style="font-size: 35px;"></i>
                <h2 style="margin: 10px 0; font-size: 32px;"><?php echo e(number_format($inspectionsToday ?? 0)); ?></h2>
                <p style="margin: 0; opacity: 0.9;">بازدیدهای امروز</p>
            </div>
        </div>
        <div class="col-md-4">
            <div style="background: linear-gradient(135deg, #1abc9c, #148f77); border-radius: 15px; padding: 20px; text-align: center; color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                <i class="bi bi-calendar-month" style="font-size: 35px;"></i>
                <h2 style="margin: 10px 0; font-size: 32px;"><?php echo e(number_format($inspectionsThisMonth ?? 0)); ?></h2>
                <p style="margin: 0; opacity: 0.9;">بازدیدهای این ماه</p>
            </div>
        </div>
        <div class="col-md-4">
            <div style="background: linear-gradient(135deg, #2c3e50, #1a252f); border-radius: 15px; padding: 20px; text-align: center; color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                <i class="bi bi-graph-up" style="font-size: 35px;"></i>
                <h2 style="margin: 10px 0; font-size: 32px;"><?php echo e(number_format(($totalCostThisMonth ?? 0) / 1000000, 1)); ?></h2>
                <p style="margin: 0; opacity: 0.9;">هزینه این ماه (میلیون ریال)</p>
            </div>
        </div>
    </div>

    <!-- نمودارها -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-graph-up"></i> آمار بازدیدهای ماهانه</h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" style="width: 100%; height: 350px;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-pie-chart"></i> وضعیت بازدیدها</h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" style="width: 100%; height: 350px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- جدول آخرین بازدیدها -->
    <div class="card mt-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-clock-history"></i> آخرین بازدیدها</h5>
            <a href="<?php echo e(route('dashboard.inspections')); ?>" class="btn btn-sm btn-primary">
                <i class="bi bi-arrow-left"></i> مشاهده همه
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>تاریخ بازدید</th>
                            <th>پیمانکار</th>
                            <th>امور/شهرستان</th>
                            <th>تعداد تجهیزات</th>
                            <th>وضعیت</th>
                            <th>هزینه (ریال)</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $recentInspections ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $inspection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $jalaliDate = $inspection->inspection_date;
                            try {
                                if (class_exists('Hekmatinasser\Verta\Verta')) {
                                    $jalaliDate = \Hekmatinasser\Verta\Verta::instance($inspection->inspection_date)->format('Y/m/d');
                                }
                            } catch (\Exception $e) {}
                            
                            $departmentName = '-';
                            if ($inspection->mainEquipments && $inspection->mainEquipments->isNotEmpty()) {
                                $firstEquipment = $inspection->mainEquipments->first();
                                if ($firstEquipment && $firstEquipment->department) {
                                    $departmentName = $firstEquipment->department->name;
                                }
                            }
                            
                            $statusClass = 'secondary';
                            $statusText = 'نامشخص';
                            if ($inspection->status == 'completed') {
                                $statusClass = 'success';
                                $statusText = 'تکمیل شده';
                            } elseif ($inspection->status == 'draft') {
                                $statusClass = 'warning';
                                $statusText = 'پیش‌نویس';
                            }
                            
                            $cost = $inspection->total_cost ?? 0;
                        ?>
                        <tr>
                            <td class="text-center"><?php echo e($loop->iteration); ?></td>
                            <td><?php echo e($jalaliDate); ?></td>
                            <td><?php echo e($inspection->contractor->name ?? $inspection->contractor ?? '-'); ?></td>
                            <td><?php echo e($departmentName); ?></td>
                            <td class="text-center"><?php echo e($inspection->mainEquipments->count() ?? 0); ?></td>
                            <td><span class="badge bg-<?php echo e($statusClass); ?>"><?php echo e($statusText); ?></span></td>
                            <td><?php echo e(number_format($cost)); ?></td>
                            <td>
                                <a href="<?php echo e(route('inspection.show', $inspection->id)); ?>" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center">هیچ بازدیدی یافت نشد</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('js/jquery.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/chart.umd.min.js')); ?>"></script>

<script>
$(document).ready(function() {
    const monthlyCtx = document.getElementById('monthlyChart');
    if (monthlyCtx) {
        new Chart(monthlyCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($monthlyLabels ?? []); ?>,
                datasets: [{
                    label: 'تعداد بازدیدها',
                    data: <?php echo json_encode($monthlyData ?? []); ?>,
                    backgroundColor: '#3498db',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });
    }

    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        new Chart(statusCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['تکمیل شده', 'پیش‌نویس', 'بایگانی شده'],
                datasets: [{
                    data: [<?php echo e($statusStats['completed'] ?? 0); ?>, <?php echo e($statusStats['draft'] ?? 0); ?>, <?php echo e($statusStats['archived'] ?? 0); ?>],
                    backgroundColor: ['#27ae60', '#f39c12', '#95a5a6']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dear-user\Desktop\new-avs\resources\views/dashboard.blade.php ENDPATH**/ ?>