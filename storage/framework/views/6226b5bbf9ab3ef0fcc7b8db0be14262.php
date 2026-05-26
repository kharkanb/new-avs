

<?php $__env->startSection('title', 'گزارش ماهانه'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- فیلترها -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-funnel"></i> فیلترهای گزارش ماهانه</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('reports.monthly')); ?>" id="filterForm">
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
                        <select name="contractor_id" class="form-select">
                            <option value="">همه پیمانکاران</option>
                            <?php $__currentLoopData = $contractors ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contractor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($contractor->id); ?>" <?php echo e(request('contractor_id') == $contractor->id ? 'selected' : ''); ?>>
                                    <?php echo e($contractor->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">امور/شهرستان</label>
                        <select name="department_id" class="form-select">
                            <option value="">همه امورها</option>
                            <?php $__currentLoopData = $departments ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($department->id); ?>" <?php echo e(request('department_id') == $department->id ? 'selected' : ''); ?>>
                                    <?php echo e($department->name); ?>

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
                    <div class="col-md-3 mb-3">
                        <label class="form-label">وضعیت</label>
                        <select name="status" class="form-select">
                            <option value="all">همه</option>
                            <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>تکمیل شده</option>
                            <option value="draft" <?php echo e(request('status') == 'draft' ? 'selected' : ''); ?>>پیش‌نویس</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> اعمال فیلتر
                        </button>
                        <a href="<?php echo e(route('reports.monthly')); ?>" class="btn btn-secondary">
                            <i class="bi bi-eraser"></i> حذف فیلترها
                        </a>
                        <button type="button" class="btn btn-success" onclick="window.print()">
                            <i class="bi bi-printer"></i> چاپ
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php
        use App\Models\Inspection;
        use Hekmatinasser\Verta\Verta;
        
        $startDate = request('start_date');
        $endDate = request('end_date');
        $contractorId = request('contractor_id');
        $equipmentType = request('equipment_type');
        $departmentId = request('department_id');
        $status = request('status');
        
        $query = Inspection::with(['user', 'contractor', 'mainEquipments.activities', 'mainEquipments.type', 'mainEquipments.department']);
        
        if ($startDate) {
            try {
                $start = Verta::parse($startDate)->datetime();
                $query->whereDate('inspection_date', '>=', $start);
            } catch (\Exception $e) {
                $query->whereDate('inspection_date', '>=', $startDate);
            }
        } else {
            $now = Verta::now();
            $startOfMonth = $now->startMonth()->datetime();
            $endOfMonth = $now->endMonth()->datetime();
            $query->whereBetween('inspection_date', [$startOfMonth, $endOfMonth]);
        }
        
        if ($endDate) {
            try {
                $end = Verta::parse($endDate)->datetime();
                $query->whereDate('inspection_date', '<=', $end);
            } catch (\Exception $e) {
                $query->whereDate('inspection_date', '<=', $endDate);
            }
        }
        
        if ($contractorId) {
            $query->where('contractor_id', $contractorId);
        }
        
        if ($departmentId) {
            $query->whereHas('mainEquipments', function($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }
        
        if ($status && $status != 'all') {
            $query->where('status', $status);
        }
        
        if ($equipmentType) {
            $query->whereHas('mainEquipments', function($q) use ($equipmentType) {
                $q->where('main_equipment_type_id', $equipmentType);
            });
        }
        
        $inspections = $query->get();
        
        $persianMonths = [
            1 => 'فروردین', 2 => 'اردیبهشت', 3 => 'خرداد', 4 => 'تیر',
            5 => 'مرداد', 6 => 'شهریور', 7 => 'مهر', 8 => 'آبان',
            9 => 'آذر', 10 => 'دی', 11 => 'بهمن', 12 => 'اسفند'
        ];
        
        if ($startDate && $endDate) {
            $displayDate = "از $startDate تا $endDate";
        } elseif ($startDate) {
            $displayDate = "از $startDate به بعد";
        } else {
            $now = Verta::now();
            $displayDate = $persianMonths[$now->month] . " " . $now->year;
        }
        
        $totalInspections = $inspections->count();
        $totalEquipments = $inspections->sum(function($inspection) {
            return $inspection->mainEquipments->count();
        });
        
        $activeContractors = $inspections->pluck('contractor_id')->unique()->filter()->count();
        
        $totalCost = $inspections->flatMap->mainEquipments
            ->flatMap->activities
            ->sum('total');
        
        $contractorStats = $inspections->groupBy(function($inspection) {
            return $inspection->contractor->name ?? 'نامشخص';
        })->map(function($items) {
            return [
                'count' => $items->count(),
                'equipments' => $items->sum(function($i) {
                    return $i->mainEquipments->count();
                }),
                'cost' => $items->sum(function($i) {
                    return $i->mainEquipments->sum(function($e) {
                        return $e->activities->sum('total');
                    });
                })
            ];
        });
        
        $weeklyStats = [0, 0, 0, 0];
        foreach ($inspections as $inspection) {
            $date = verta($inspection->inspection_date);
            $day = $date->day;
            
            if ($day <= 7) $weeklyStats[0]++;
            elseif ($day <= 14) $weeklyStats[1]++;
            elseif ($day <= 21) $weeklyStats[2]++;
            else $weeklyStats[3]++;
        }
        
        function toPersianNumber($num) {
            if (!$num && $num !== 0) return '۰';
            $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
            $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
            return str_replace($english, $persian, number_format($num));
        }
    ?>

    <!-- گزارش اصلی -->
    <div class="card">
        <div class="card-header text-center">
            <h4>گزارش ماهانه بازدیدها</h4>
            <p class="text-muted"><?php echo e($displayDate); ?></p>
        </div>
        <div class="card-body">
            <!-- آمار کلی -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-white bg-primary">
                        <div class="card-body text-center">
                            <i class="bi bi-calendar-check" style="font-size: 2rem;"></i>
                            <h2 class="mb-0"><?php echo e(toPersianNumber($totalInspections)); ?></h2>
                            <p>تعداد بازدیدها</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success">
                        <div class="card-body text-center">
                            <i class="bi bi-hdd-stack" style="font-size: 2rem;"></i>
                            <h2 class="mb-0"><?php echo e(toPersianNumber($totalEquipments)); ?></h2>
                            <p>تعداد تجهیزات بازدید شده</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-warning">
                        <div class="card-body text-center">
                            <i class="bi bi-people" style="font-size: 2rem;"></i>
                            <h2 class="mb-0"><?php echo e(toPersianNumber($activeContractors)); ?></h2>
                            <p>پیمانکاران فعال</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-info">
                        <div class="card-body text-center">
                            <i class="bi bi-cash-coin" style="font-size: 2rem;"></i>
                            <h2 class="mb-0"><?php echo e(toPersianNumber($totalCost)); ?></h2>
                            <p>هزینه کل (ریال)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- نمودار -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <h5 class="mb-3">توزیع بازدیدها در هفته‌های ماه</h5>
                    <div style="position: relative; height: 300px;">
                        <canvas id="weeklyChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- جدول پیمانکاران -->
            <h5 class="mb-3">آمار بازدیدها بر اساس پیمانکار</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>پیمانکار</th>
                            <th>تعداد بازدید</th>
                            <th>تعداد تجهیزات</th>
                            <th>هزینه (ریال)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $contractorStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contractorName => $stats): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($contractorName); ?></td>
                            <td class="text-center"><?php echo e(toPersianNumber($stats['count'])); ?></td>
                            <td class="text-center"><?php echo e(toPersianNumber($stats['equipments'])); ?></td>
                            <td class="text-start"><?php echo e(toPersianNumber($stats['cost'])); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <p class="mt-3">هیچ بازدیدی با این فیلترها یافت نشد</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('js/jquery.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/persian-date.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/persian-datepicker.min.js')); ?>"></script>
<link href="<?php echo e(asset('css/persian-datepicker.min.css')); ?>" rel="stylesheet">
<script src="<?php echo e(asset('js/chart.umd.min.js')); ?>"></script>

<script>
$(document).ready(function() {
    $('.persian-date').persianDatepicker({
        format: 'YYYY/MM/DD',
        autoClose: true,
        initialValue: false,
        calendar: { persian: { locale: 'fa' } }
    });
});

const ctx = document.getElementById('weeklyChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['هفته اول', 'هفته دوم', 'هفته سوم', 'هفته چهارم'],
        datasets: [{
            label: 'تعداد بازدیدها',
            data: [<?php echo e($weeklyStats[0]); ?>, <?php echo e($weeklyStats[1]); ?>, <?php echo e($weeklyStats[2]); ?>, <?php echo e($weeklyStats[3]); ?>],
            backgroundColor: '#3498db',
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'top' }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1,
                    callback: function(value) {
                        return value.toLocaleString('fa-IR');
                    }
                }
            }
        }
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dear-user\Desktop\new-avs\resources\views/reports/monthly.blade.php ENDPATH**/ ?>