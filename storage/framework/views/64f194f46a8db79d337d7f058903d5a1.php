<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>سیستم مدیریت بازدید تجهیزات اتوماسیون - شرکت توزیع نیروی برق استان یزد</title>

    <!-- ============================================ -->
    <!-- CSS فایل‌های خارجی (Library)                  -->
    <!-- ============================================ -->
    <link href="<?php echo e(asset('css/bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/bootstrap-icons.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/select2.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/persian-datepicker.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/jquery.timepicker.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/Vazirmatn-font-face.css')); ?>" rel="stylesheet">
    
    <!-- ============================================ -->
    <!-- CSS فایل سفارشی (Custom)                      -->
    <!-- ============================================ -->
    <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet">
</head>
<body>

<!-- ============================================ -->
<!-- HEADER                                        -->
<!-- ============================================ -->
<div class="header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-2 text-center">
                <img src="<?php echo e(asset('logo.png')); ?>" alt="لوگو" class="img-fluid" style="max-height: 100px;">
            </div>
            <div class="col-md-8 text-center">
                <h2 class="mb-2" style="font-size: 1.7rem;">سیستم مدیریت بازدید تجهیزات اتوماسیون</h2>
                <p class="mb-0">شرکت توزیع نیروی برق استان یزد</p>
            </div>
            <div class="col-md-2 text-center">
                <div class="badge" style="background-color: #f8f4e9; color: #2c3e50; padding: 10px 15px;">
                    <i class="bi bi-calendar-check"></i>
                    <span id="current-date" style="font-size: 1.1rem;"></span>
                </div>
                <div class="badge mt-2" style="background-color: #f8f4e9; color: #2c3e50; padding: 8px 12px;">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>F-20324-01</span>
                </div>
                <div class="user-avatar mt-2 mx-auto" onclick="showLoginModal()" style="cursor: pointer; width: 40px; height: 40px;">
                    <i class="bi bi-person-circle"></i>
                </div>
                <span id="user-name" style="font-size: 0.8rem; display: block;">ورود</span>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- MAIN CONTAINER                               -->
<!-- ============================================ -->
<div class="container">
    <!-- Step Indicator -->
    <div class="step-indicator">
        <div class="step active" data-step="1">
            <div class="step-circle"><i class="bi bi-calendar-week"></i></div>
            <div class="step-label">اطلاعات روزانه</div>
        </div>
        <div class="step" data-step="2">
            <div class="step-circle"><i class="bi bi-hdd-rack"></i></div>
            <div class="step-label">انتخاب تجهیز</div>
        </div>
        <div class="step" data-step="3">
            <div class="step-circle"><i class="bi bi-gear"></i></div>
            <div class="step-label">اطلاعات فنی</div>
        </div>
        <div class="step" data-step="4">
            <div class="step-circle"><i class="bi bi-file-earmark-text"></i></div>
            <div class="step-label">گزارش نهایی</div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- STEP 1: DAILY INFORMATION                    -->
    <!-- ============================================ -->
    <div class="form-section form-step active" id="step-1">
        <div class="icon-title">
            <i class="bi bi-calendar-week"></i>
            <h4>اطلاعات بازدید روزانه</h4>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label required"><i class="bi bi-calendar"></i> تاریخ بازدید</label>
                <input type="text" class="form-control" id="inspection-date" placeholder="1403/01/01" readonly>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label required"><i class="bi bi-clock"></i> زمان شروع روزانه</label>
                <input type="time" class="form-control" id="daily-start-time" value="08:00">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label required"><i class="bi bi-clock-fill"></i> زمان پایان روزانه</label>
                <input type="time" class="form-control" id="daily-end-time" value="14:00">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label required"><i class="bi bi-briefcase"></i> پیمانکار اتوماسیون</label>
                <select class="form-control" id="contractor">
                    <option value="1" selected>سام سرمد کویر</option>
                    <option value="2">مانا نیرو</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label required"><i class="bi bi-percent"></i> ضریب قرارداد</label>
                <input type="number" class="form-control" id="contract-coefficient" value="2.35" step="0.01" min="1">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label"><i class="bi bi-file-earmark-text"></i> شماره قرارداد</label>
                <input type="text" class="form-control" id="contract-number" value=".../.../.../...">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label"><i class="bi bi-whatsapp"></i> شماره واتساپ برای ارسال گزارش</label>
                <input type="text" class="form-control" id="whatsapp-number" placeholder="مثال: 989123456789">
            </div>
        </div>
        <div class="text-end">
            <button class="btn btn-primary btn-icon" onclick="goToStep(2)">
                <i class="bi bi-arrow-left"></i> ادامه به انتخاب تجهیز
            </button>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- STEP 2: EQUIPMENT SELECTION                  -->
    <!-- ============================================ -->
    <div class="form-section form-step" id="step-2">
        <div class="icon-title">
            <i class="bi bi-hdd-rack"></i>
            <h4>انتخاب تجهیزات</h4>
        </div>
        
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i>
            <strong>توجه:</strong> برای سکسیونر و سکشنالایزر باید نوع نصب (بین‌فیدری یا مانوری) انتخاب شود. برای سایر تجهیزات، نوع نصب بر اساس تعداد فیدرها تعیین می‌شود.
        </div>
        
        <!-- Equipment List Container -->
        <div id="equipment-container">
            <!-- Equipment cards will be added here dynamically -->
        </div>

        <!-- Add Equipment Button -->
        <div class="text-center mb-4">
            <button class="btn btn-success btn-icon" onclick="addNewEquipment()">
                <i class="bi bi-plus-circle"></i> افزودن تجهیز جدید
            </button>
        </div>

        <div class="row">
            <div class="col-md-6">
                <button class="btn btn-secondary btn-icon" onclick="goToStep(1)">
                    <i class="bi bi-arrow-right"></i> بازگشت
                </button>
            </div>
            <div class="col-md-6 text-end">
                <button class="btn btn-primary btn-icon" onclick="goToStep(3)">
                    <i class="bi bi-arrow-left"></i> ادامه به اطلاعات فنی
                </button>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- STEP 3: TECHNICAL INFORMATION                -->
    <!-- ============================================ -->
    <div class="form-section form-step" id="step-3">
        <div class="icon-title">
            <i class="bi bi-gear"></i>
            <h4>اطلاعات فنی تجهیزات</h4>
        </div>
        
        <div id="tech-info-container">
            <!-- Technical info for each equipment will be loaded here -->
        </div>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <button class="btn btn-secondary btn-icon" onclick="goToStep(2)">
                    <i class="bi bi-arrow-right"></i> بازگشت
                </button>
            </div>
            <div class="col-md-6 text-end">
                <button class="btn btn-primary btn-icon" onclick="goToStep(4)">
                    <i class="bi bi-arrow-left"></i> ادامه به گزارش نهایی
                </button>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- STEP 4: FINAL REPORT (کامل)                  -->
    <!-- ============================================ -->
    <div class="form-section form-step" id="step-4">
        <div class="icon-title">
            <i class="bi bi-file-earmark-text"></i>
            <h4>گزارش نهایی و جمع‌بندی</h4>
        </div>
        
        <!-- Financial Summary -->
        <div class="row mb-4">
            <div class="col-md-12">
                <h5 class="border-bottom pb-2 mb-3"><i class="bi bi-calculator"></i> خلاصه مالی</h5>
                <div class="row">
                    <div class="col-md-3">
                        <div class="card stat-card text-center">
                            <div class="card-body">
                                <div class="feature-icon mx-auto">
                                    <i class="bi bi-percent"></i>
                                </div>
                                <h6 class="card-subtitle mb-2 text-muted">ضریب قرارداد</h6>
                                <h3 id="summary-coefficient" class="persian-numbers">۲.۳۵</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card text-center">
                            <div class="card-body">
                                <div class="feature-icon mx-auto">
                                    <i class="bi bi-hdd-stack"></i>
                                </div>
                                <h6 class="card-subtitle mb-2 text-muted">تعداد تجهیزات</h6>
                                <h3 id="summary-equipment-count" class="persian-numbers">۰</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card text-center">
                            <div class="card-body">
                                <div class="feature-icon mx-auto">
                                    <i class="bi bi-list-check"></i>
                                </div>
                                <h6 class="card-subtitle mb-2 text-muted">کل فعالیت‌ها</h6>
                                <h3 id="summary-activity-count" class="persian-numbers">۰</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card text-center">
                            <div class="card-body">
                                <div class="feature-icon mx-auto">
                                    <i class="bi bi-cash-coin"></i>
                                </div>
                                <h6 class="card-subtitle mb-2 text-muted">هزینه بدون ضریب</h6>
                                <h3 id="summary-total-cost" class="persian-numbers">۰ ریال</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="card stat-card">
                            <div class="card-body text-center">
                                <h5 class="card-title"><i class="bi bi-graph-up"></i> هزینه نهایی با اعمال ضریب قرارداد</h5>
                                <h1 id="summary-final-cost" class="text-success persian-numbers">۰ ریال</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activities Summary Table -->
        <div class="row mb-4">
            <div class="col-md-12">
                <h5 class="border-bottom pb-2 mb-3"><i class="bi bi-list-check"></i> جمع‌بندی فعالیت‌های فهرست بها</h5>
                <div class="table-responsive">
                    <table class="table table-bordered summary-table" id="final-activities-summary">
                        <thead>
                            <tr>
                                <th><i class="bi bi-hash"></i> کد فهرست بها</th>
                                <th><i class="bi bi-card-text"></i> عنوان فعالیت</th>
                                <th><i class="bi bi-123"></i> تعداد کل</th>
                                <th><i class="bi bi-currency-exchange"></i> فی واحد (ریال)</th>
                                <th><i class="bi bi-cash-stack"></i> مبلغ کل (بدون ضریب)</th>
                                <th><i class="bi bi-calculator"></i> مبلغ با ضریب</th>
                            </tr>
                        </thead>
                        <tbody id="activities-summary-body">
                            <!-- Will be populated by JavaScript -->
                        </tbody>
                        <tfoot class="table-dark">
                            <tr>
                                <td colspan="4" class="text-end"><strong><i class="bi bi-calculator"></i> جمع کل:</strong></td>
                                <td id="final-activities-total" class="persian-numbers">۰ ریال</td>
                                <td id="final-activities-total-coefficient" class="persian-numbers">۰ ریال</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Consumables Summary Table -->
        <div class="row mb-4">
            <div class="col-md-12">
                <h5 class="border-bottom pb-2 mb-3"><i class="bi bi-box-seam"></i> جمع‌بندی اقلام مصرفی</h5>
                <div class="table-responsive">
                    <table class="table table-bordered summary-table" id="final-consumables-summary">
                        <thead>
                            <tr>
                                <th><i class="bi bi-box"></i> نام قلم مصرفی</th>
                                <th><i class="bi bi-123"></i> تعداد کل</th>
                                <th><i class="bi bi-rulers"></i> واحد</th>
                                <th><i class="bi bi-chat-left-text"></i> توضیحات</th>
                            </tr>
                        </thead>
                        <tbody id="consumables-summary-body">
                            <!-- Will be populated by JavaScript -->
                        </tbody>
                        <tfoot class="table-dark">
                            <tr>
                                <td class="text-end"><strong><i class="bi bi-calculator"></i> جمع کل اقلام مصرفی:</strong></td>
                                <td id="final-consumables-total" class="persian-numbers">۰</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Equipment Details -->
        <div class="row mb-4">
            <div class="col-md-12">
                <h5 class="border-bottom pb-2 mb-3"><i class="bi bi-hdd-stack"></i> جزئیات تجهیزات بازدید شده</h5>
                <div id="equipment-details-summary">
                    <!-- Equipment details will be loaded here -->
                </div>
            </div>
        </div>

        <!-- Export Buttons -->
        <div class="row">
            <div class="col-md-3">
                <button class="btn btn-secondary btn-icon" onclick="goToStep(3)">
                    <i class="bi bi-arrow-right"></i> بازگشت به ویرایش
                </button>
            </div>
            <div class="col-md-2 text-center">
                <button class="btn btn-info btn-icon" onclick="generatePDFReport()">
                    <i class="bi bi-file-pdf"></i> تولید گزارش PDF
                </button>
            </div>
            <div class="col-md-2 text-center">
                <button class="btn btn-success btn-icon" onclick="generateExcelReport()">
                    <i class="bi bi-file-excel"></i> خروجی Excel
                </button>
            </div>
            <div class="col-md-2 text-center">
                <button class="btn btn-warning btn-icon" onclick="generateWordReport()">
                    <i class="bi bi-file-word"></i> گزارش Word
                </button>
            </div>
            <div class="col-md-3 text-end">
                <button class="btn whatsapp-btn btn-icon" onclick="sendToWhatsApp()">
                    <i class="bi bi-whatsapp"></i> ارسال به واتساپ
                </button>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12 text-center">
                <button class="btn btn-lg btn-success btn-icon" onclick="submitFinalInspection()" style="padding: 15px 30px; font-size: 1.2rem;">
                    <i class="bi bi-check-circle-fill"></i> ثبت نهایی بازدید در سامانه
                </button>
                <p class="text-muted mt-2">
                    <i class="bi bi-info-circle"></i> با کلیک روی این دکمه، تمام اطلاعات بازدید در دیتابیس ذخیره شده و قابل بازیابی خواهد بود.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- ACTION BUTTONS (STICKY)                      -->
<!-- ============================================ -->
<div class="action-buttons">
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <button class="btn btn-outline-secondary btn-icon" onclick="clearForm()">
                    <i class="bi bi-trash"></i> پاک کردن فرم
                </button>
            </div>
            <div class="col-md-3">
                <button class="btn btn-outline-primary btn-icon" onclick="saveDraft()">
                    <i class="bi bi-save"></i> ذخیره پیش‌نویس
                </button>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-info btn-icon" onclick="loadDraft()">
                    <i class="bi bi-upload"></i> بارگذاری پیش‌نویس
                </button>
            </div>
            <div class="col-md-2 text-center">
                <button class="btn btn-outline-warning btn-icon" id="auto-save-toggle">
                    <i class="bi bi-check-circle"></i> ذخیره خودکار
                </button>
            </div>
            <div class="col-md-3 text-end">
                <button class="btn btn-outline-success btn-icon" onclick="printForm()">
                    <i class="bi bi-printer"></i> چاپ فرم
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- FOOTER                                       -->
<!-- ============================================ -->
<footer class="bg-dark text-light py-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h6><i class="bi bi-building"></i> شرکت توزیع نیروی برق استان یزد</h6>
                <p class="mb-0"><i class="bi bi-clipboard-data"></i> سیستم مدیریت بازدید تجهیزات اتوماسیون</p>
                <p class="mb-0"><i class="bi bi-file-earmark-text"></i> نسخه: 2.0 - شماره فرم: F-20324-01</p>
            </div>
            <div class="col-md-6 text-end">
                <p class="mb-0"><i class="bi bi-headset"></i> پشتیبانی فنی: ۷-۳۱۶۷۲۰۲۶-۰۳۵</p>
                <p class="mb-0"><i class="bi bi-calendar-check"></i> آخرین به‌روزرسانی: ۱۴۰۴/۱۱/۰۴</p>
                <p class="mb-0"><i class="bi bi-people"></i> Developers: M.m. & B.K.</p>
            </div>
        </div>
    </div>
</footer>

<!-- ============================================ -->
<!-- LOADING OVERLAY                              -->
<!-- ============================================ -->
<div id="loadingOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:9999; text-align:center; padding-top:20%; color:white; font-size:20px;">
    <div class="spinner-border text-light" style="width: 3rem; height: 3rem;" role="status">
        <span class="visually-hidden">در حال بارگذاری...</span>
    </div>
    <br>
    <div>در حال ایجاد گزارش...</div>
</div>

<!-- ============================================ -->
<!-- LOGIN MODAL                                  -->
<!-- ============================================ -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-box-arrow-in-right"></i> ورود به سیستم</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
            </div>
            <div class="modal-body">
                <form id="loginForm" onsubmit="handleLogin(event)">
                    <div class="mb-3">
                        <label class="form-label required">ایمیل</label>
                        <input type="email" class="form-control" id="email" required placeholder="admin@example.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">رمز عبور</label>
                        <input type="password" class="form-control" id="password" required placeholder="********">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">ورود</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- EQUIPMENT MODAL                              -->
<!-- ============================================ -->
<div class="modal fade" id="equipmentModal" tabindex="-1" aria-hidden="true" style="overflow-y: auto;">
    <div class="modal-dialog modal-xl" style="max-width: 95%; margin: 1rem auto;">
        <div class="modal-content" style="max-height: 90vh; overflow-y: auto;">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-gear"></i> ویرایش اطلاعات تجهیز</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
            </div>
            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                <!-- Modal content will be loaded dynamically -->
            </div>
        </div>
    </div>
</div>


<!-- ============================================ -->
<!-- SCRIPTS                                      -->
<!-- ============================================ -->

<!-- JavaScript Library Files (وابسته به هم) -->
<script src="<?php echo e(asset('js/jquery.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/bootstrap.bundle.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/moment.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/persian-date.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/persian-datepicker.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/select2.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/jquery.timepicker.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/sweetalert2.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/xlsx.full.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/jspdf.umd.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/jspdf.plugin.autotable.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/html2pdf.bundle.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/chart.umd.min.js')); ?>"></script>

<!-- Main Application JavaScript -->
<script src="<?php echo e(asset('js/inspection-form.js')); ?>"></script>

<!-- Fallback for missing functions -->
<script>
    if (typeof showLoginModal === 'undefined') {
        window.showLoginModal = function() {
            const modal = new bootstrap.Modal(document.getElementById('loginModal'));
            modal.show();
        };
    }
    
    if (typeof handleLogin === 'undefined') {
        window.handleLogin = async function(event) {
            event.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'خطا',
                text: 'فایل جاوااسکریپت به درستی بارگذاری نشده است. لطفا صفحه را رفرش کنید.'
            });
        };
    }
</script>

</body>
</html><?php /**PATH C:\Users\dear-user\Desktop\new-avs\resources\views/inspection-form.blade.php ENDPATH**/ ?>