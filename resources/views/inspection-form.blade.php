<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>سیستم مدیریت بازدید تجهیزات اتوماسیون - شرکت توزیع نیروی برق استان یزد</title>

    <!-- ============================================ -->
    <!-- CSS فایل‌های خارجی (Library)                  -->
    <!-- ============================================ -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/persian-datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery.timepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/Vazirmatn-font-face.css') }}" rel="stylesheet">
    
<<<<<<< HEAD
    <!-- ============================================ -->
    <!-- CSS فایل سفارشی (Custom)                      -->
    <!-- ============================================ -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
=======
    <!-- کتابخانه‌های PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
       <script src="https://cdn.jsdelivr.net/npm/html2pdf.js@0.10.1/dist/html2pdf.bundle.min.js"></script>

       <script src="https://unpkg.com/jspdf-rtl-plugin@1.0.0/dist/jspdf-rtl-plugin.min.js"></script>
      <link href="https://fonts.googleapis.com/css2?family=Vazirmatn&display=swap" rel="stylesheet">

>>>>>>> e82339cac376f551a8a66da0035c095e88a5df9d
</head>
<body>

<!-- ============================================ -->
<!-- HEADER                                        -->
<!-- ============================================ -->
<div class="header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-2 text-center">
                <img src="{{ asset('logo.png') }}" alt="لوگو" class="img-fluid" style="max-height: 100px;">
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
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/moment.min.js') }}"></script>
<script src="{{ asset('js/persian-date.min.js') }}"></script>
<script src="{{ asset('js/persian-datepicker.min.js') }}"></script>
<script src="{{ asset('js/select2.min.js') }}"></script>
<script src="{{ asset('js/jquery.timepicker.min.js') }}"></script>
<script src="{{ asset('js/sweetalert2.min.js') }}"></script>
<script src="{{ asset('js/xlsx.full.min.js') }}"></script>
<script src="{{ asset('js/jspdf.umd.min.js') }}"></script>
<script src="{{ asset('js/jspdf.plugin.autotable.min.js') }}"></script>
<script src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
<script src="{{ asset('js/chart.umd.min.js') }}"></script>

<!-- Main Application JavaScript -->
<script src="{{ asset('js/inspection-form.js') }}"></script>

<!-- Fallback for missing functions -->
<script>
    if (typeof showLoginModal === 'undefined') {
        window.showLoginModal = function() {
            const modal = new bootstrap.Modal(document.getElementById('loginModal'));
            modal.show();
        };
    }
<<<<<<< HEAD
    
    if (typeof handleLogin === 'undefined') {
        window.handleLogin = async function(event) {
            event.preventDefault();
=======
}


// تابع کمکی برای تبدیل متن به یونیکد
function fixPersianText(text) {
    if (!text) return '';
    return text;
}

function generatePDFReport() {
    try {
        // ایجاد یک پنجره جدید
        const printWindow = window.open('', '_blank');
        
        // جمع‌آوری اطلاعات
        const inspectionDate = document.getElementById('inspection-date').value;
        const contractor = document.getElementById('contractor').value;
        const coefficient = parseFloat(document.getElementById('contract-coefficient').value) || 2.35;
        const contractNumber = document.getElementById('contract-number').value;
        const dailyStartTime = document.getElementById('daily-start-time').value;
        const dailyEndTime = document.getElementById('daily-end-time').value;
        const cityDepartment = equipments.length > 0 ? equipments[0].departmentData?.department || 'ثبت نشده' : 'ثبت نشده';
        
        // محاسبه آمار کل
        let totalActivities = 0;
        let totalCost = 0;
        
        equipments.forEach(equipment => {
            if (equipment.activitiesData && equipment.activitiesData.length > 0) {
                equipment.activitiesData.forEach(activity => {
                    totalActivities += activity.quantity || 0;
                    totalCost += activity.total || 0;
                });
            }
        });
        
        const finalCost = totalCost * coefficient;
        
        // ساخت HTML تجهیزات با جزئیات کامل
        let equipmentHTML = '';
        
        equipments.forEach((equipment, index) => {
            const hasBrands = equipmentWithBrands.includes(equipment.equipmentType);
            const switchBrandDisplay = hasBrands ? 
                (equipment.switchBrand === 'سایر' ? equipment.otherSwitchBrand : equipment.switchBrand) : 
                'بدون برند';
                
            const feedersText = equipment.feeders && equipment.feeders.length > 0 
                ? equipment.feeders.map(f => `${f.post} (${f.feeder})`).join('، ')
                : 'ثبت نشده';
            
            // اطلاعات چک‌لیست
            let checklistHTML = '';
            if (equipment.checklistData && equipment.checklistData.length > 0) {
                checklistHTML = `
                    <div style="margin-top: 15px;">
                        <h4 style="color: #2c3e50; border-bottom: 1px solid #3498db; padding-bottom: 5px;">چک‌لیست بازدید</h4>
                        <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                            <thead>
                                <tr style="background-color: #2c3e50; color: white;">
                                    <th style="padding: 8px; border: 1px solid #ddd;">ردیف</th>
                                    <th style="padding: 8px; border: 1px solid #ddd;">آیتم</th>
                                    <th style="padding: 8px; border: 1px solid #ddd;">وضعیت</th>
                                    <th style="padding: 8px; border: 1px solid #ddd;">توضیحات</th>
                                </tr>
                            </thead>
                            <tbody>
                `;
                
                equipment.checklistData.forEach((item, idx) => {
                    const statusColor = item.status === 'OK' ? '#27ae60' : '#e74c3c';
                    checklistHTML += `
                        <tr>
                            <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">${idx + 1}</td>
                            <td style="padding: 8px; border: 1px solid #ddd;">${item.item || ''}</td>
                            <td style="padding: 8px; border: 1px solid #ddd; color: ${statusColor}; font-weight: bold;">${item.status || ''}</td>
                            <td style="padding: 8px; border: 1px solid #ddd;">${item.description || '-'}</td>
                        </tr>
                    `;
                });
                
                checklistHTML += `
                            </tbody>
                        </table>
                    </div>
                `;
            }
            
            // اطلاعات فعالیت‌ها
            let activitiesHTML = '';
            if (equipment.activitiesData && equipment.activitiesData.length > 0) {
                let activityTotal = 0;
                activitiesHTML = `
                    <div style="margin-top: 15px;">
                        <h4 style="color: #2c3e50; border-bottom: 1px solid #3498db; padding-bottom: 5px;">فعالیت‌های انجام شده</h4>
                        <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                            <thead>
                                <tr style="background-color: #2c3e50; color: white;">
                                    <th style="padding: 8px; border: 1px solid #ddd;">ردیف</th>
                                    <th style="padding: 8px; border: 1px solid #ddd;">کد</th>
                                    <th style="padding: 8px; border: 1px solid #ddd;">عنوان</th>
                                    <th style="padding: 8px; border: 1px solid #ddd;">تعداد</th>
                                    <th style="padding: 8px; border: 1px solid #ddd;">فی واحد</th>
                                    <th style="padding: 8px; border: 1px solid #ddd;">مبلغ</th>
                                </tr>
                            </thead>
                            <tbody>
                `;
                
                equipment.activitiesData.forEach((activity, idx) => {
                    activityTotal += activity.total || 0;
                    activitiesHTML += `
                        <tr>
                            <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">${idx + 1}</td>
                            <td style="padding: 8px; border: 1px solid #ddd;">${activity.code || ''}</td>
                            <td style="padding: 8px; border: 1px solid #ddd;">${activity.title || ''}</td>
                            <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">${activity.quantity || 0}</td>
                            <td style="padding: 8px; border: 1px solid #ddd; text-align: left;">${(activity.unitPrice || 0).toLocaleString()} ریال</td>
                            <td style="padding: 8px; border: 1px solid #ddd; text-align: left;">${(activity.total || 0).toLocaleString()} ریال</td>
                        </tr>
                    `;
                });
                
                activitiesHTML += `
                            </tbody>
                            <tfoot>
                                <tr style="background-color: #f2f2f2; font-weight: bold;">
                                    <td colspan="5" style="padding: 8px; border: 1px solid #ddd; text-align: left;">جمع کل:</td>
                                    <td style="padding: 8px; border: 1px solid #ddd; text-align: left;">${activityTotal.toLocaleString()} ریال</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                `;
            }
            
            // اطلاعات اقلام مصرفی
            let consumablesHTML = '';
            if (equipment.consumablesData && equipment.consumablesData.length > 0) {
                consumablesHTML = `
                    <div style="margin-top: 15px;">
                        <h4 style="color: #2c3e50; border-bottom: 1px solid #3498db; padding-bottom: 5px;">اقلام مصرفی</h4>
                        <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                            <thead>
                                <tr style="background-color: #2c3e50; color: white;">
                                    <th style="padding: 8px; border: 1px solid #ddd;">ردیف</th>
                                    <th style="padding: 8px; border: 1px solid #ddd;">نام قلم</th>
                                    <th style="padding: 8px; border: 1px solid #ddd;">تعداد</th>
                                    <th style="padding: 8px; border: 1px solid #ddd;">واحد</th>
                                    <th style="padding: 8px; border: 1px solid #ddd;">توضیحات</th>
                                </tr>
                            </thead>
                            <tbody>
                `;
                
                equipment.consumablesData.forEach((consumable, idx) => {
                    consumablesHTML += `
                        <tr>
                            <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">${idx + 1}</td>
                            <td style="padding: 8px; border: 1px solid #ddd;">${consumable.name || ''}</td>
                            <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">${consumable.quantity || 0}</td>
                            <td style="padding: 8px; border: 1px solid #ddd;">${consumable.unit || 'عدد'}</td>
                            <td style="padding: 8px; border: 1px solid #ddd;">${consumable.description || '-'}</td>
                        </tr>
                    `;
                });
                
                consumablesHTML += `
                            </tbody>
                        </table>
                    </div>
                `;
            }
            
            // اطلاعات عکس‌ها
            let photosHTML = '';
            if (equipment.photosData && equipment.photosData.length > 0) {
                photosHTML = `
                    <div style="margin-top: 15px;">
                        <h4 style="color: #2c3e50; border-bottom: 1px solid #3498db; padding-bottom: 5px;">مستندات تصویری</h4>
                        <p>تعداد عکس‌ها: ${equipment.photosData.length}</p>
                        <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                `;
                
                equipment.photosData.forEach((photo, idx) => {
                    photosHTML += `
                        <div style="border: 1px solid #ddd; padding: 10px; border-radius: 5px; width: 200px;">
                            <p><strong>عکس ${idx + 1}</strong></p>
                            <p>کد اسکن: ${photo.scanCode || '-'}</p>
                            <p>توضیحات: ${photo.description || '-'}</p>
                        </div>
                    `;
                });
                
                photosHTML += `</div></div>`;
            }
            
            // اطلاعات اصلی تجهیز
            equipmentHTML += `
                <div style="margin-bottom: 30px; padding: 20px; border: 2px solid #ddd; border-radius: 10px; background-color: #fff; page-break-inside: avoid;">
                    <h3 style="color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px; margin-top: 0;">
                        تجهیز ${index + 1}: ${equipment.equipmentType || 'ثبت نشده'}
                    </h3>
                    
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
                        <tr>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2; width: 25%;">کد اسکادا:</th>
                            <td style="padding: 10px; border: 1px solid #ddd;">${equipment.scadaCode || 'ثبت نشده'}</td>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2; width: 25%;">زمان فعالیت:</th>
                            <td style="padding: 10px; border: 1px solid #ddd;">${equipment.startTime || '---'} - ${equipment.endTime || '---'}</td>
                        </tr>
                        ${hasBrands ? `
                        <tr>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2;">برند کلید:</th>
                            <td style="padding: 10px; border: 1px solid #ddd;">${switchBrandDisplay}</td>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2;">برند مودم:</th>
                            <td style="padding: 10px; border: 1px solid #ddd;">${equipment.modemBrand === 'سایر' ? equipment.otherModemBrand : equipment.modemBrand}</td>
                        </tr>
                        <tr>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2;">برند RTU:</th>
                            <td style="padding: 10px; border: 1px solid #ddd;">${equipment.rtuBrand === 'سایر' ? equipment.otherRTUBrand : equipment.rtuBrand}</td>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2;">نوع نصب:</th>
                            <td style="padding: 10px; border: 1px solid #ddd;">${equipment.installationType || '---'}</td>
                        </tr>
                        ` : ''}
                        <tr>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2;">امور شهرستان:</th>
                            <td style="padding: 10px; border: 1px solid #ddd;">${equipment.departmentData?.department || 'ثبت نشده'}</td>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2;">GIS Code:</th>
                            <td style="padding: 10px; border: 1px solid #ddd;">${equipment.departmentData?.city || 'ثبت نشده'}</td>
                        </tr>
                        <tr>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2;">فیدرها:</th>
                            <td style="padding: 10px; border: 1px solid #ddd;" colspan="3">${feedersText}</td>
                        </tr>
                        <tr>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2;">موقعیت جغرافیایی:</th>
                            <td style="padding: 10px; border: 1px solid #ddd;" colspan="3">
                                عرض: ${equipment.locationData?.latitude || 'ثبت نشده'} - طول: ${equipment.locationData?.longitude || 'ثبت نشده'}
                            </td>
                        </tr>
                        <tr>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2;">آدرس:</th>
                            <td style="padding: 10px; border: 1px solid #ddd;" colspan="3">${equipment.locationData?.address || 'ثبت نشده'}</td>
                        </tr>
                        ${equipment.locationData?.cabinetInitialHeight ? `
                        <tr>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2;">ارتفاع اولیه:</th>
                            <td style="padding: 10px; border: 1px solid #ddd;">${equipment.locationData.cabinetInitialHeight} متر</td>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2;">ارتفاع نهایی:</th>
                            <td style="padding: 10px; border: 1px solid #ddd;">${equipment.locationData.cabinetFinalHeight || 'ثبت نشده'} متر</td>
                        </tr>
                        ` : ''}
                        <tr>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2;">اطلاعات ارتباطی:</th>
                            <td style="padding: 10px; border: 1px solid #ddd;" colspan="3">
                                سیم‌کارت: ${equipment.communicationData?.simcardType || 'ثبت نشده'} - 
                                شماره: ${equipment.communicationData?.simcardNumber || 'ثبت نشده'} - 
                                IP: ${equipment.communicationData?.simcardIp || 'ثبت نشده'}
                            </td>
                        </tr>
                        <tr>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2;">وضعیت آنتن:</th>
                            <td style="padding: 10px; border: 1px solid #ddd;">${equipment.communicationData?.antennaStatus || 'ثبت نشده'}</td>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2;">وضعیت سیگنال:</th>
                            <td style="padding: 10px; border: 1px solid #ddd;">${equipment.communicationData?.signalStatus || 'ثبت نشده'}</td>
                        </tr>
                        <tr>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2;">تغذیه مودم:</th>
                            <td style="padding: 10px; border: 1px solid #ddd;">${equipment.communicationData?.modemPower || 'ثبت نشده'}</td>
                            <th style="padding: 10px; border: 1px solid #ddd; background-color: #f2f2f2;">قابلیت ریست:</th>
                            <td style="padding: 10px; border: 1px solid #ddd;">${equipment.communicationData?.resetPossible ? 'دارد' : 'ندارد'}</td>
                        </tr>
                    </table>
                    
                    ${checklistHTML}
                    ${activitiesHTML}
                    ${consumablesHTML}
                    ${photosHTML}
                </div>
            `;
        });
        
        // ایجاد HTML کامل
        const htmlContent = `
            <!DOCTYPE html>
            <html dir="rtl" lang="fa">
            <head>
                <meta charset="UTF-8">
                <title>گزارش بازدید تجهیزات اتوماسیون</title>
                <style>
                    body {
                        font-family: 'Vazirmatn', Tahoma, Arial, sans-serif;
                        direction: rtl;
                        text-align: right;
                        margin: 2cm;
                        line-height: 1.6;
                        color: #333;
                    }
                    .header {
                        text-align: center;
                        margin-bottom: 30px;
                        border-bottom: 3px solid #2c3e50;
                        padding-bottom: 20px;
                    }
                    .header h1 {
                        color: #2c3e50;
                        margin: 0;
                        font-size: 24px;
                    }
                    .header h2 {
                        color: #3498db;
                        margin: 10px 0;
                        font-size: 20px;
                    }
                    .section {
                        margin-bottom: 25px;
                    }
                    .section-title {
                        background-color: #2c3e50;
                        color: white;
                        padding: 10px 15px;
                        border-radius: 5px;
                        margin-bottom: 15px;
                        font-size: 18px;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-bottom: 20px;
                    }
                    table, th, td {
                        border: 1px solid #ddd;
                    }
                    th, td {
                        padding: 10px;
                        text-align: right;
                    }
                    th {
                        background-color: #f2f2f2;
                        font-weight: bold;
                    }
                    .financial-summary {
                        background-color: #f8f9fa;
                        padding: 15px;
                        border-radius: 5px;
                    }
                    .footer {
                        margin-top: 50px;
                        border-top: 1px solid #ddd;
                        padding-top: 20px;
                        text-align: center;
                        font-size: 11px;
                        color: #666;
                    }
                    @media print {
                        body {
                            margin: 1cm;
                        }
                        .no-print {
                            display: none;
                        }
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>شرکت توزیع نیروی برق استان یزد</h1>
                    <h2>گزارش بازدید تجهیزات اتوماسیون</h2>
                    <p>فرم شماره: F-20324-01</p>
                    <p>تاریخ بازدید: ${inspectionDate}</p>
                </div>
                
                <div class="section">
                    <div class="section-title">اطلاعات روزانه بازدید</div>
                    <table>
                        <tr>
                            <th style="width: 20%;">امور شهرستان:</th>
                            <td style="width: 30%;">${cityDepartment}</td>
                            <th style="width: 20%;">پیمانکار:</th>
                            <td style="width: 30%;">${contractor}</td>
                        </tr>
                        <tr>
                            <th>شماره قرارداد:</th>
                            <td>${contractNumber}</td>
                            <th>ضریب قرارداد:</th>
                            <td>${coefficient}</td>
                        </tr>
                        <tr>
                            <th>تاریخ بازدید:</th>
                            <td>${inspectionDate}</td>
                            <th>تعداد تجهیزات:</th>
                            <td>${equipments.length}</td>
                        </tr>
                        <tr>
                            <th>زمان شروع:</th>
                            <td>${dailyStartTime || '---'}</td>
                            <th>زمان پایان:</th>
                            <td>${dailyEndTime || '---'}</td>
                        </tr>
                    </table>
                </div>
                
                <div class="section">
                    <div class="section-title">خلاصه مالی</div>
                    <div class="financial-summary">
                        <table>
                            <tr>
                                <th style="width: 30%;">کل فعالیت‌ها:</th>
                                <td style="width: 20%;">${totalActivities}</td>
                                <th style="width: 30%;">هزینه بدون ضریب:</th>
                                <td style="width: 20%;">${totalCost.toLocaleString()} ریال</td>
                            </tr>
                            <tr>
                                <th>هزینه نهایی (با ضریب ${coefficient}):</th>
                                <td colspan="3"><strong>${finalCost.toLocaleString()} ریال</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="section">
                    <div class="section-title">جزئیات تجهیزات بازدید شده</div>
                    ${equipmentHTML}
                </div>
                
                <div class="footer">
                    <p>سیستم مدیریت بازدید تجهیزات اتوماسیون - شرکت توزیع نیروی برق استان یزد</p>
                    <p>فرم شماره: F-20324-01 - تاریخ تولید: ${new Date().toLocaleDateString('fa-IR')}</p>
                </div>
                
                <div class="no-print" style="text-align: center; margin-top: 20px;">
                    <button onclick="window.print()" style="padding: 10px 20px; background-color: #3498db; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px;">
                        <i class="bi bi-printer"></i> چاپ / ذخیره PDF
                    </button>
                    <button onclick="window.close()" style="padding: 10px 20px; background-color: #e74c3c; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; margin-right: 10px;">
                        <i class="bi bi-x"></i> بستن
                    </button>
                </div>
            </body>
            </html>
        `;
        
        // نوشتن محتوا در پنجره جدید
        printWindow.document.write(htmlContent);
        printWindow.document.close();
        
        // تمرکز روی پنجره جدید
        printWindow.focus();
        
    } catch (error) {
        console.error('Error generating PDF report:', error);
        alert('خطا در ایجاد گزارش: ' + error.message);
    }
}


function generateWordReport() {
    try {
        // جمع‌آوری اطلاعات اصلی
        const inspectionDate = document.getElementById('inspection-date').value;
        const contractor = document.getElementById('contractor').value;
        const coefficient = parseFloat(document.getElementById('contract-coefficient').value) || 2.35;
        const cityDepartment = equipments.length > 0 ? equipments[0].departmentData?.department || 'ثبت نشده' : 'ثبت نشده';
        
        // شروع ساخت محتوای HTML برای Word
        let wordContent = `
            <!DOCTYPE html>
            <html dir="rtl" lang="fa">
            <head>
                <meta charset="UTF-8">
                <style>
                    body {
                        font-family: 'B Nazanin', 'B Lotus', Tahoma, sans-serif;
                        direction: rtl;
                        text-align: right;
                        margin: 2cm;
                        line-height: 1.6;
                    }
                    .header {
                        text-align: center;
                        margin-bottom: 30px;
                        border-bottom: 3px solid #2c3e50;
                        padding-bottom: 20px;
                    }
                    .header h1 {
                        color: #2c3e50;
                        margin: 0;
                    }
                    .header h2 {
                        color: #3498db;
                        margin: 10px 0;
                    }
                    .section {
                        margin-bottom: 25px;
                        page-break-inside: avoid;
                    }
                    .section-title {
                        background-color: #2c3e50;
                        color: white;
                        padding: 10px 15px;
                        border-radius: 5px;
                        margin-bottom: 15px;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-bottom: 20px;
                        font-size: 12px;
                    }
                    table th {
                        background-color: #2c3e50;
                        color: white;
                        padding: 10px;
                        text-align: center;
                        border: 1px solid #ddd;
                    }
                    table td {
                        padding: 8px 10px;
                        border: 1px solid #ddd;
                        text-align: center;
                    }
                    .photo-container {
                        display: inline-block;
                        margin: 10px;
                        text-align: center;
                        vertical-align: top;
                        width: 45%;
                    }
                    .photo-container img {
                        max-width: 100%;
                        max-height: 300px;
                        border: 1px solid #ddd;
                        border-radius: 5px;
                    }
                    .footer {
                        margin-top: 50px;
                        border-top: 1px solid #ddd;
                        padding-top: 20px;
                        text-align: center;
                        font-size: 11px;
                        color: #666;
                    }
                    .financial-summary {
                        background-color: #f8f9fa;
                        padding: 15px;
                        border-radius: 5px;
                        margin: 20px 0;
                    }
                    .equipment-details {
                        background-color: #e8f4fc;
                        padding: 15px;
                        border-radius: 5px;
                        margin: 10px 0;
                    }
                    .checklist-item-ok {
                        color: #27ae60;
                    }
                    .checklist-item-not-ok {
                        color: #e74c3c;
                    }
                    @page {
                        size: A4;
                        margin: 2cm;
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>شرکت توزیع نیروی برق استان یزد</h1>
                    <h2>گزارش بازدید تجهیزات اتوماسیون</h2>
                    <p>فرم شماره: F-20324-01</p>
                    <p>تاریخ بازدید: ${inspectionDate}</p>
                </div>
        `;
        
        // بخش اطلاعات روزانه
        wordContent += `
            <div class="section">
                <div class="section-title">
                    <h3>اطلاعات روزانه بازدید</h3>
                </div>
                <table>
                    <tr>
                        <th>امور شهرستان</th>
                        <td colspan="3">${cityDepartment}</td>
                    </tr>
                    <tr>
                        <th>پیمانکار اتوماسیون</th>
                        <td>${contractor}</td>
                        <th>ضریب قرارداد</th>
                        <td>${coefficient}</td>
                    </tr>
                    <tr>
                        <th>تاریخ بازدید</th>
                        <td>${inspectionDate}</td>
                        <th>تعداد تجهیزات</th>
                        <td>${equipments.length}</td>
                    </tr>
                </table>
            </div>
        `;
        
        // خلاصه مالی
        const totalActivityCount = document.getElementById('summary-activity-count').textContent;
        const totalCost = document.getElementById('summary-total-cost').textContent;
        const finalCost = document.getElementById('summary-final-cost').textContent;
        
        wordContent += `
            <div class="section">
                <div class="section-title">
                    <h3>خلاصه مالی</h3>
                </div>
                <div class="financial-summary">
                    <table>
                        <tr>
                            <th>ضریب قرارداد</th>
                            <th>تعداد تجهیزات</th>
                            <th>کل فعالیت‌ها</th>
                            <th>هزینه بدون ضریب</th>
                            <th>هزینه نهایی</th>
                        </tr>
                        <tr>
                            <td>${coefficient}</td>
                            <td>${equipments.length}</td>
                            <td>${totalActivityCount}</td>
                            <td>${totalCost}</td>
                            <td>${finalCost}</td>
                        </tr>
                    </table>
                </div>
            </div>
        `;
        
        // جمع‌بندی فعالیت‌ها
        const activitiesSummary = {};
        equipments.forEach(equipment => {
            if (equipment.activitiesData) {
                equipment.activitiesData.forEach(activity => {
                    if (!activitiesSummary[activity.code]) {
                        activitiesSummary[activity.code] = {
                            title: activity.title,
                            unit: activity.unit,
                            unitPrice: activity.unitPrice,
                            totalQuantity: 0,
                            totalAmount: 0
                        };
                    }
                    activitiesSummary[activity.code].totalQuantity += (activity.quantity || 0);
                    activitiesSummary[activity.code].totalAmount += (activity.total || 0);
                });
            }
        });
        
        if (Object.keys(activitiesSummary).length > 0) {
            wordContent += `
                <div class="section">
                    <div class="section-title">
                        <h3>جمع‌بندی فعالیت‌های فهرست بها</h3>
                    </div>
                    <table>
                        <tr>
                            <th>کد فهرست بها</th>
                            <th>عنوان فعالیت</th>
                            <th>تعداد کل</th>
                            <th>فی واحد (ریال)</th>
                            <th>مبلغ کل (بدون ضریب)</th>
                            <th>مبلغ با ضریب</th>
                        </tr>
            `;
            
            let totalAmount = 0;
            Object.entries(activitiesSummary).forEach(([code, data]) => {
                totalAmount += (data.totalAmount || 0);
                wordContent += `
                    <tr>
                        <td>${code}</td>
                        <td>${data.title || ''}</td>
                        <td>${formatNumber(data.totalQuantity || 0)}</td>
                        <td>${formatNumber(data.unitPrice || 0)} ریال</td>
                        <td>${formatNumber(data.totalAmount || 0)} ریال</td>
                        <td>${formatNumber((data.totalAmount || 0) * coefficient)} ریال</td>
                    </tr>
                `;
            });
            
            wordContent += `
                    <tr style="font-weight: bold; background-color: #f1f1f1;">
                        <td colspan="4" style="text-align: left;">جمع کل:</td>
                        <td>${formatNumber(totalAmount)} ریال</td>
                        <td>${formatNumber(totalAmount * coefficient)} ریال</td>
                    </tr>
                </table>
            </div>
            `;
        }
        
        // جمع‌بندی مصارف
        const consumablesSummary = {};
        equipments.forEach(equipment => {
            if (equipment.consumablesData) {
                equipment.consumablesData.forEach(consumable => {
                    if (!consumablesSummary[consumable.name]) {
                        consumablesSummary[consumable.name] = {
                            totalQuantity: 0,
                            descriptions: []
                        };
                    }
                    consumablesSummary[consumable.name].totalQuantity += (consumable.quantity || 0);
                    if (consumable.description) {
                        consumablesSummary[consumable.name].descriptions.push(consumable.description);
                    }
                });
            }
        });
        
        if (Object.keys(consumablesSummary).length > 0) {
            wordContent += `
                <div class="section">
                    <div class="section-title">
                        <h3>جمع‌بندی اقلام مصرفی</h3>
                    </div>
                    <table>
                        <tr>
                            <th>نام قلم مصرفی</th>
                            <th>تعداد کل</th>
                            <th>توضیحات</th>
                        </tr>
            `;
            
            let totalConsumables = 0;
            Object.entries(consumablesSummary).forEach(([name, data]) => {
                totalConsumables += (data.totalQuantity || 0);
                const description = data.descriptions.join('، ') || '-';
                wordContent += `
                    <tr>
                        <td>${name}</td>
                        <td>${formatNumber(data.totalQuantity || 0)}</td>
                        <td>${description}</td>
                    </tr>
                `;
            });
            
            wordContent += `
                    <tr style="font-weight: bold; background-color: #f1f1f1;">
                        <td style="text-align: left;">جمع کل اقلام مصرفی:</td>
                        <td>${formatNumber(totalConsumables)}</td>
                        <td></td>
                    </tr>
                </table>
            </div>
            `;
        }
        
        // جزئیات هر تجهیز
        equipments.forEach((equipment, index) => {
            const hasBrands = equipmentWithBrands.includes(equipment.equipmentType);
            const switchBrandDisplay = hasBrands ? 
                (equipment.switchBrand === 'سایر' ? equipment.otherSwitchBrand : equipment.switchBrand) : 
                'بدون برند';
            const feedersText = equipment.feeders && equipment.feeders.length > 0 
                ? equipment.feeders.map(f => `${f.post || 'ثبت نشده'} (${f.feeder || 'ثبت نشده'})`).join('، ')
                : 'ثبت نشده';
            
            wordContent += `
                <div class="section" style="page-break-before: ${index > 0 ? 'always' : 'auto'};">
                    <div class="section-title">
                        <h3>جزئیات تجهیز ${index + 1}: ${equipment.equipmentType || 'ثبت نشده'}</h3>
                    </div>
                    
                    <div class="equipment-details">
                        <table>
                            <tr>
                                <th>نوع تجهیز</th>
                                <td>${equipment.equipmentType || 'ثبت نشده'}</td>
                                <th>کد اسکادا</th>
                                <td>${equipment.scadaCode || 'ثبت نشده'}</td>
                            </tr>
                            ${hasBrands ? `
                            <tr>
                                <th>برند کلید</th>
                                <td>${switchBrandDisplay || 'ثبت نشده'}</td>
                                <th>برند مودم</th>
                                <td>${equipment.modemBrand === 'سایر' ? equipment.otherModemBrand : equipment.modemBrand || 'ثبت نشده'}</td>
                            </tr>
                            <tr>
                                <th>برند RTU</th>
                                <td>${equipment.rtuBrand === 'سایر' ? equipment.otherRTUBrand : equipment.rtuBrand || 'ثبت نشده'}</td>
                                <th>زمان فعالیت</th>
                                <td>${equipment.startTime || 'ثبت نشده'} - ${equipment.endTime || 'ثبت نشده'}</td>
                            </tr>
                            ` : `
                            <tr>
                                <th>زمان فعالیت</th>
                                <td colspan="3">${equipment.startTime || 'ثبت نشده'} - ${equipment.endTime || 'ثبت نشده'}</td>
                            </tr>
                            `}
                            <tr>
                                <th>فیدرها</th>
                                <td colspan="3">${feedersText}</td>
                            </tr>
                            ${equipment.departmentData?.department ? `
                            <tr>
                                <th>امور شهرستان</th>
                                <td>${equipment.departmentData.department}</td>
                                <th>GIS Code</th>
                                <td>${equipment.departmentData.city || 'ثبت نشده'}</td>
                            </tr>
                            ` : ''}
                        </table>
            `;
            
            // اطلاعات موقعیت
            if (equipment.locationData) {
                wordContent += `
                        <table>
                            <tr>
                                <th colspan="4">اطلاعات موقعیت جغرافیایی</th>
                            </tr>
                            <tr>
                                <th>عرض جغرافیایی</th>
                                <td>${equipment.locationData.latitude || 'ثبت نشده'}</td>
                                <th>طول جغرافیایی</th>
                                <td>${equipment.locationData.longitude || 'ثبت نشده'}</td>
                            </tr>
                            <tr>
                                <th>آدرس نصب</th>
                                <td colspan="3">${equipment.locationData.address || 'ثبت نشده'}</td>
                            </tr>
                            ${equipment.locationData.cabinetInitialHeight ? `
                            <tr>
                                <th>ارتفاع اولیه تابلو</th>
                                <td>${equipment.locationData.cabinetInitialHeight} متر</td>
                                <th>ارتفاع نهایی تابلو</th>
                                <td>${equipment.locationData.cabinetFinalHeight || 'ثبت نشده'} متر</td>
                            </tr>
                            ` : ''}
                        </table>
                `;
            }
            
            // اطلاعات ارتباطی
            if (equipment.communicationData) {
                wordContent += `
                        <table>
                            <tr>
                                <th colspan="4">اطلاعات ارتباطی</th>
                            </tr>
                            <tr>
                                <th>نوع سیم‌کارت</th>
                                <td>${equipment.communicationData.simcardType || 'ثبت نشده'}</td>
                                <th>شماره سیم‌کارت</th>
                                <td>${equipment.communicationData.simcardNumber || 'ثبت نشده'}</td>
                            </tr>
                            <tr>
                                <th>IP سیم‌کارت</th>
                                <td>${equipment.communicationData.simcardIp || 'ثبت نشده'}</td>
                                <th>وضعیت آنتن</th>
                                <td>${equipment.communicationData.antennaStatus || 'ثبت نشده'}</td>
                            </tr>
                            <tr>
                                <th>وضعیت سیگنال</th>
                                <td>${equipment.communicationData.signalStatus || 'ثبت نشده'}</td>
                                <th>تغذیه مودم</th>
                                <td>${equipment.communicationData.modemPower || 'ثبت نشده'}</td>
                            </tr>
                            <tr>
                                <th>قابلیت ریست</th>
                                <td colspan="3">${equipment.communicationData.resetPossible ? 'دارد' : 'ندارد'}</td>
                            </tr>
                        </table>
                `;
            }
            
            wordContent += `</div>`; // بستن equipment-details
            
            // چک‌لیست
            if (equipment.checklistData && equipment.checklistData.length > 0) {
                wordContent += `
                    <div style="margin-top: 20px;">
                        <h4>چک‌لیست بازدید</h4>
                        <table>
                            <tr>
                                <th>ردیف</th>
                                <th>آیتم</th>
                                <th>وضعیت</th>
                                <th>توضیحات</th>
                            </tr>
                `;
                
                equipment.checklistData.forEach((item, idx) => {
                    const statusClass = item.status === 'OK' ? 'checklist-item-ok' : 'checklist-item-not-ok';
                    wordContent += `
                        <tr>
                            <td>${idx + 1}</td>
                            <td>${item.item || ''}</td>
                            <td class="${statusClass}">${item.status || ''}</td>
                            <td>${item.description || '-'}</td>
                        </tr>
                    `;
                });
                
                wordContent += `</table></div>`;
            }
            
            // فعالیت‌ها
            if (equipment.activitiesData && equipment.activitiesData.length > 0) {
                wordContent += `
                    <div style="margin-top: 20px;">
                        <h4>فعالیت‌های انجام شده</h4>
                        <table>
                            <tr>
                                <th>ردیف</th>
                                <th>کد</th>
                                <th>عنوان</th>
                                <th>واحد</th>
                                <th>فی واحد</th>
                                <th>تعداد</th>
                                <th>مبلغ</th>
                            </tr>
                `;
                
                equipment.activitiesData.forEach((activity, idx) => {
                    wordContent += `
                        <tr>
                            <td>${idx + 1}</td>
                            <td>${activity.code || ''}</td>
                            <td>${activity.title || ''}</td>
                            <td>${activity.unit || ''}</td>
                            <td>${formatNumber(activity.unitPrice || 0)} ریال</td>
                            <td>${activity.quantity || 0}</td>
                            <td>${formatNumber(activity.total || 0)} ریال</td>
                        </tr>
                    `;
                });
                
                wordContent += `</table></div>`;
            }
            
            // مصارف
            if (equipment.consumablesData && equipment.consumablesData.length > 0) {
                wordContent += `
                    <div style="margin-top: 20px;">
                        <h4>اقلام مصرفی</h4>
                        <table>
                            <tr>
                                <th>ردیف</th>
                                <th>نام</th>
                                <th>تعداد</th>
                                <th>توضیحات</th>
                            </tr>
                `;
                
                equipment.consumablesData.forEach((consumable, idx) => {
                    wordContent += `
                        <tr>
                            <td>${idx + 1}</td>
                            <td>${consumable.name || ''}</td>
                            <td>${consumable.quantity || 0}</td>
                            <td>${consumable.description || '-'}</td>
                        </tr>
                    `;
                });
                
                wordContent += `</table></div>`;
            }
            
            // عکس‌ها (در Word فقط لینک می‌دهیم چون inline کردن عکس پیچیده است)
            if (equipment.photosData && equipment.photosData.length > 0) {
                wordContent += `
                    <div style="margin-top: 20px;">
                        <h4>مستندات تصویری</h4>
                        <p>تعداد عکس‌ها: ${equipment.photosData.length}</p>
                `;
                
                equipment.photosData.forEach((photo, idx) => {
                    wordContent += `
                        <p>عکس ${idx + 1}: کد اسکن: ${photo.scanCode || '-'} - توضیحات: ${photo.description || '-'}</p>
                    `;
                });
                
                wordContent += `</div>`;
            }
            
            wordContent += `</div>`; // بستن section تجهیز
        });
        
        // فوتر
        wordContent += `
            <div class="footer">
                <p>سیستم مدیریت بازدید تجهیزات اتوماسیون - شرکت توزیع نیروی برق استان یزد</p>
                <p>فرم شماره: F-20324-01 - تاریخ تولید: ${new Date().toLocaleDateString('fa-IR')}</p>
                <p>پشتیبانی فنی: ۰۳۵-۳۷۲۷۱۰۰۰</p>
            </div>
        `;
        
        wordContent += `</body></html>`;
        
        // تبدیل HTML به Blob
        const blob = new Blob([wordContent], { type: 'application/msword' });
        
        // ذخیره فایل
        const filename = `گزارش_بازدید_اتوماسیون_${inspectionDate}_${Date.now()}.doc`;
        
        // استفاده از FileSaver.js
        if (typeof saveAs !== 'undefined') {
            saveAs(blob, filename);
        } else {
            // Fallback برای مرورگرهای قدیمی
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = filename;
            link.click();
        }
        
        alert('گزارش Word با موفقیت ایجاد شد.');
        
    } catch (error) {
        console.error('Error generating Word report:', error);
        alert('خطا در ایجاد گزارش Word: ' + error.message);
    }
}






        // WhatsApp functions
        function sendToWhatsApp() {
            const whatsappNumber = document.getElementById('whatsapp-number').value;
            
            if (!whatsappNumber) {
                alert('لطفا شماره واتساپ را وارد کنید.');
                return;
            }
            
            const reportMessage = generateWhatsAppReport();
            const encodedMessage = encodeURIComponent(reportMessage);
            const whatsappUrl = `https://wa.me/${whatsappNumber}?text=${encodedMessage}`;
            
            window.open(whatsappUrl, '_blank');
        }

        function generateWhatsAppReport() {
            const inspectionDate = document.getElementById('inspection-date').value;
            const contractor = document.getElementById('contractor').value;
            const coefficient = document.getElementById('contract-coefficient').value || '2.35';
            const number = document.getElementById('contract-number').value;
            const equipmentCount = equipments.length;
            const activityCount = document.getElementById('summary-activity-count').textContent;
            const totalCost = document.getElementById('summary-total-cost').textContent;
            const finalCost = document.getElementById('summary-final-cost').textContent;
            
            let message = `📋 *گزارش بازدید تجهیزات اتوماسیون*\n`;
            message += `📅 تاریخ: ${inspectionDate}\n`;
            message += `👷 پیمانکار: ${contractor}\n`;
            message += `💰 ضریب قرارداد: ${coefficient}\n`;
            message += `📄 شماره قرارداد: ${number}\n`;
            message += `⚙️ تعداد تجهیزات: ${equipmentCount}\n`;
            message += `📊 کل فعالیت‌ها: ${activityCount}\n`;
            message += `💵 هزینه بدون ضریب: ${totalCost}\n`;
            message += `🏆 هزینه نهایی: ${finalCost}\n`;
            message += `\n`;
            message += `🔗 گزارش کامل در سیستم ثبت شده است.\n`;
            message += `شرکت توزیع نیروی برق استان یزد`;
            
            return message;
        }

        // Utility functions
        function formatNumber(num) {
            if (!num && num !== 0) return '۰';
            return new Intl.NumberFormat('fa-IR').format(num);
        }

        // Auto-save functionality
        function triggerAutoSave() {
            if (!autoSaveEnabled) return;
            
            if (autoSaveTimer) {
                clearTimeout(autoSaveTimer);
            }
            
            autoSaveTimer = setTimeout(() => {
                saveDraftSilently();
            }, 5000);
        }

        function saveDraftSilently() {
            try {
                const draftData = {
                    inspectionDate: document.getElementById('inspection-date').value,
                    contractor: document.getElementById('contractor').value,
                    coefficient: document.getElementById('contract-coefficient').value,
                    contractNumber: document.getElementById('contract-number').value,
                    whatsappNumber: document.getElementById('whatsapp-number').value,
                    dailyStartTime: document.getElementById('daily-start-time').value,
                    dailyEndTime: document.getElementById('daily-end-time').value,
                    equipments: equipments
                };
                
                localStorage.setItem('automationInspectionDraft', JSON.stringify(draftData));
                console.log('پیش‌نویس به صورت خودکار ذخیره شد.');
            } catch (error) {
                console.error('Error in auto-save:', error);
            }
        }

        function setupAutoSaveToggle() {
            const toggleBtn = document.getElementById('auto-save-toggle');
            if (toggleBtn) {
                updateAutoSaveButton();
                
                toggleBtn.addEventListener('click', function() {
                    autoSaveEnabled = !autoSaveEnabled;
                    updateAutoSaveButton();
                    
                    if (autoSaveEnabled) {
                        saveDraftSilently();
                        alert('ذخیره خودکار فعال شد.');
                    } else {
                        alert('ذخیره خودکار غیرفعال شد.');
                    }
                });
            }
        }

        function updateAutoSaveButton() {
            const toggleBtn = document.getElementById('auto-save-toggle');
            if (toggleBtn) {
                if (autoSaveEnabled) {
                    toggleBtn.innerHTML = '<i class="bi bi-check-circle"></i> ذخیره خودکار';
                    toggleBtn.className = 'btn btn-outline-success';
                } else {
                    toggleBtn.innerHTML = '<i class="bi bi-x-circle"></i> ذخیره خودکار';
                    toggleBtn.className = 'btn btn-outline-danger';
                }
            }
        }

        function saveDraft() {
            try {
                const draftData = {
                    inspectionDate: document.getElementById('inspection-date').value,
                    contractor: document.getElementById('contractor').value,
                    coefficient: document.getElementById('contract-coefficient').value,
                    contractNumber: document.getElementById('contract-number').value,
                    whatsappNumber: document.getElementById('whatsapp-number').value,
                    dailyStartTime: document.getElementById('daily-start-time').value,
                    dailyEndTime: document.getElementById('daily-end-time').value,
                    equipments: equipments
                };
                
                localStorage.setItem('automationInspectionDraft', JSON.stringify(draftData));
                alert('پیش‌نویس با موفقیت ذخیره شد.');
            } catch (error) {
                console.error('Error saving draft:', error);
                alert('خطا در ذخیره پیش‌نویس: ' + error.message);
            }
        }

        function loadDraft() {
            try {
                if (!confirm('آیا مایل به بارگذاری پیش‌نویس ذخیره‌شده هستید؟')) {
                    return;
                }
                
                const draftData = JSON.parse(localStorage.getItem('automationInspectionDraft'));
                if (!draftData) {
                    alert('پیش‌نویس ذخیره‌شده‌ای یافت نشد.');
                    return;
                }
                
                document.getElementById('inspection-date').value = draftData.inspectionDate || '';
                document.getElementById('contractor').value = draftData.contractor || 'سام سرمد کویر';
                document.getElementById('contract-coefficient').value = draftData.coefficient || '2.35';
                document.getElementById('contract-number').value = draftData.contractNumber || '.../.../.../...';
                document.getElementById('whatsapp-number').value = draftData.whatsappNumber || '';
                document.getElementById('daily-start-time').value = draftData.dailyStartTime || '';
                document.getElementById('daily-end-time').value = draftData.dailyEndTime || '';
                
                if (draftData.equipments && draftData.equipments.length > 0) {
                    equipments = draftData.equipments;
                    equipmentCount = equipments.length;
                    
                    document.getElementById('equipment-container').innerHTML = '';
                    
                    equipments.forEach(equipment => {
                        const equipmentCard = document.createElement('div');
                        equipmentCard.className = 'equipment-card';
                        equipmentCard.id = equipment.id;
                        
                        equipmentCard.innerHTML = `
                            <div class="equipment-header">
                                <div>
                                    <i class="bi bi-hdd"></i>
                                    <span class="ms-2">تجهیز ${equipment.index}</span>
                                </div>
                                <div>
                                    <button class="btn btn-sm btn-outline-light me-2" onclick="editEquipment('${equipment.id}')">
                                        <i class="bi bi-pencil"></i> ویرایش
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="removeEquipment('${equipment.id}')">
                                        <i class="bi bi-trash"></i> حذف
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <p><strong>نوع تجهیز:</strong> <span id="${equipment.id}-type">${equipment.equipmentType || '---'}</span></p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>کد اسکادا:</strong> <span id="${equipment.id}-scada">${equipment.scadaCode || '---'}</span></p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>برند کلید:</strong> <span id="${equipment.id}-switch-brand">${equipment.switchBrand || '---'}</span></p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>وضعیت اطلاعات فنی:</strong> <span id="${equipment.id}-status" class="badge ${getStatusBadgeClass(equipment)}">${getStatusText(equipment)}</span></p>
                                    </div>
                                    <div class="col-md-8">
                                        <p><strong>فیدرها:</strong> <span id="${equipment.id}-feeders">${getFeedersText(equipment)}</span></p>
                                    </div>
                                </div>
                            </div>
                        `;
                        
                        document.getElementById('equipment-container').appendChild(equipmentCard);
                    });
                }
                
                alert('پیش‌نویس با موفقیت بارگذاری شد.');
                
            } catch (error) {
                console.error('Error loading draft:', error);
                alert('خطا در بارگذاری پیش‌نویس: ' + error.message);
            }
        }

        function getStatusBadgeClass(equipment) {
            const validatedCount = equipment.tabsValidated ? Object.keys(equipment.tabsValidated).length : 0;
            
            if (validatedCount >= 5) {
                return 'bg-success';
            } else if (validatedCount >= 3) {
                return 'bg-warning';
            } else if (validatedCount > 0) {
                return 'bg-info';
            } else {
                return 'bg-danger';
            }
        }

        function getStatusText(equipment) {
            const validatedCount = equipment.tabsValidated ? Object.keys(equipment.tabsValidated).length : 0;
            
            if (validatedCount >= 5) {
                return 'اطلاعات کامل';
            } else if (validatedCount >= 3) {
                return 'اطلاعات متوسط';
            } else if (validatedCount > 0) {
                return 'اطلاعات جزئی';
            } else {
                return 'فاقد اطلاعات';
            }
        }

        function getFeedersText(equipment) {
            return equipment.feeders && equipment.feeders.length > 0 
                ? equipment.feeders.map(f => `${f.post} (${f.feeder})`).join('، ')
                : '---';
        }

        function clearForm() {
            if (confirm('آیا از پاک کردن فرم اطمینان دارید؟ تمام اطلاعات حذف خواهند شد.')) {
                equipments = [];
                equipmentCount = 0;
                
                document.getElementById('equipment-container').innerHTML = '';
                document.getElementById('tech-info-container').innerHTML = '';
                document.getElementById('inspection-date').value = formatJalaliDate(getCurrentJalaliDate());
                document.getElementById('daily-start-time').value = '';
                document.getElementById('daily-end-time').value = '';
                document.getElementById('contractor').value = 'سام سرمد کویر';
                document.getElementById('contract-coefficient').value = '2.35';
                document.getElementById('contract-number').value = '.../.../.../...';
                document.getElementById('whatsapp-number').value = '';
                
                updateStepIndicator(1);
                goToStep(1);
                
                alert('فرم با موفقیت پاک شد.');
            }
        }

        function printForm() {
            window.print();
        }



    // در اینجا اضافه کنید - خارج از $(document).ready اما داخل تگ <script>
    // در صورت عدم وجود FileSaver.js، یک polyfill ساده اضافه می‌کنیم
    if (typeof saveAs === 'undefined') {
        window.saveAs = function(blob, filename) {
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.href = url;
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            setTimeout(() => URL.revokeObjectURL(url), 100);
        };
    } 




// تابع ثبت نهایی بازدید - نسخه نهایی با JSON.stringify برای همه فیلدها
async function submitFinalInspection() {
    try {
        if (!confirm('آیا از ثبت نهایی این بازدید اطمینان دارید؟')) {
            return;
        }

        if (equipments.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'خطا',
                text: 'حداقل یک تجهیز باید اضافه کنید'
            });
            return;
        }

        Swal.fire({
            title: 'در حال ثبت اطلاعات...',
            text: 'لطفا صبر کنید',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const token = localStorage.getItem('auth_token');
        
        if (!token) {
>>>>>>> e82339cac376f551a8a66da0035c095e88a5df9d
            Swal.fire({
                icon: 'error',
                title: 'خطا',
                text: 'فایل جاوااسکریپت به درستی بارگذاری نشده است. لطفا صفحه را رفرش کنید.'
            });
<<<<<<< HEAD
        };
    }
</script>

=======
            return;
        }

        // تبدیل تاریخ شمسی به میلادی
        const jalaliDate = document.getElementById('inspection-date').value;
        
        function convertJalaliToGregorian(jalaliDateStr) {
            try {
                if (typeof window.moment !== 'undefined') {
                    const persianNumbers = {
                        '۰': '0', '۱': '1', '۲': '2', '۳': '3', '۴': '4',
                        '۵': '5', '۶': '6', '۷': '7', '۸': '8', '۹': '9'
                    };
                    
                    let englishDate = jalaliDateStr;
                    for (let [persian, english] of Object.entries(persianNumbers)) {
                        englishDate = englishDate.replace(new RegExp(persian, 'g'), english);
                    }
                    
                    const m = window.moment(englishDate, 'jYYYY/jMM/jDD');
                    if (m.isValid()) {
                        return m.format('YYYY-MM-DD');
                    }
                }
            } catch (e) {
                console.error('Date conversion error:', e);
            }
            return jalaliDateStr;
        }

        const gregorianDate = convertJalaliToGregorian(jalaliDate);

        // آماده‌سازی داده‌های تجهیزات با JSON.stringify برای همه فیلدهای غیراساسی
        const processedEquipments = equipments.map(equipment => {
            // ایجاد یک شیء جدید با فیلدهای اصلی
            const equipmentData = {
                // فیلدهای اسکالر (نیاز به JSON.stringify ندارند)
                equipmentType: equipment.equipmentType || '',
                scadaCode: equipment.scadaCode || '',
                installationType: equipment.installationType || '',
                switchBrand: equipment.switchBrand || '',
                modemBrand: equipment.modemBrand || '',
                rtuBrand: equipment.rtuBrand || '',
                otherSwitchBrand: equipment.otherSwitchBrand || '',
                otherModemBrand: equipment.otherModemBrand || '',
                otherRTUBrand: equipment.otherRTUBrand || '',
                startTime: equipment.startTime || '',
                endTime: equipment.endTime || '',
                
                // فیلدهای پیچیده - باید JSON.stringify شوند
                feeders: JSON.stringify(equipment.feeders || []),
                departmentData: JSON.stringify(equipment.departmentData || {}),
                locationData: JSON.stringify(equipment.locationData || {}),
                communicationData: JSON.stringify(equipment.communicationData || {}),
                checklistData: JSON.stringify(equipment.checklistData || []),
                activitiesData: JSON.stringify(equipment.activitiesData || []),
                consumablesData: JSON.stringify(equipment.consumablesData || []),
                photosData: JSON.stringify(equipment.photosData || []),
                cellSpecs: JSON.stringify(equipment.cellSpecs || {}),
                tabsValidated: JSON.stringify(equipment.tabsValidated || {})
            };
            
            return equipmentData;
        });

        // محاسبه آمار کل
        let totalActivities = 0;
        let totalCost = 0;
        
        equipments.forEach(equipment => {
            if (equipment.activitiesData && equipment.activitiesData.length > 0) {
                equipment.activitiesData.forEach(activity => {
                    totalActivities += activity.quantity || 0;
                    totalCost += activity.total || 0;
                });
            }
        });

        const coefficient = parseFloat(document.getElementById('contract-coefficient').value) || 2.35;

        const inspectionData = {
            inspection_date: gregorianDate,
            daily_start_time: document.getElementById('daily-start-time').value,
            daily_end_time: document.getElementById('daily-end-time').value,
            contractor: document.getElementById('contractor').value,
            contract_coefficient: coefficient,
            contract_number: document.getElementById('contract-number').value,
            whatsapp_number: document.getElementById('whatsapp-number').value,
            equipments: processedEquipments
        };

        console.log('Sending inspection data:', JSON.stringify(inspectionData, null, 2));

        const response = await fetch('/api/inspections', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            },
            body: JSON.stringify(inspectionData)
        });

        const responseText = await response.text();
        console.log('Raw response:', responseText);

        let result;
        try {
            result = JSON.parse(responseText);
        } catch (e) {
            console.error('JSON parse error:', e);
            throw new Error('پاسخ سرور نامعتبر است');
        }

        if (!response.ok) {
            if (response.status === 422) {
                const errorMessages = [];
                if (result.errors) {
                    Object.keys(result.errors).forEach(key => {
                        errorMessages.push(`${key}: ${result.errors[key].join(', ')}`);
                    });
                }
                throw new Error(errorMessages.join('\n') || result.message || 'خطای اعتبارسنجی');
            }
            throw new Error(result.message || result.error || 'خطا در ثبت اطلاعات');
        }

        // موفقیت
        const trackingCode = result.data?.id || result.id || 'ثبت شده';
        
        Swal.fire({
            icon: 'success',
            title: '✅ ثبت با موفقیت انجام شد',
            html: `
                <div style="text-align: right;">
                    <p>بازدید با موفقیت در سیستم ثبت شد.</p>
                    <p><strong>کد پیگیری:</strong> ${trackingCode}</p>
                    <p><strong>تاریخ:</strong> ${jalaliDate}</p>
                    <p><strong>تعداد تجهیزات:</strong> ${equipments.length}</p>
                    <p><strong>هزینه نهایی:</strong> ${formatNumber(totalCost * coefficient)} ریال</p>
                </div>
            `,
            confirmButtonText: 'باشه'
        }).then(() => {
            localStorage.removeItem('automationInspectionDraft');
            if (confirm('آیا می‌خواهید یک بازدید جدید شروع کنید؟')) {
                clearForm();
            }
        });

        console.log('Inspection saved successfully:', result);

    } catch (error) {
        console.error('Error submitting inspection:', error);
        
        Swal.fire({
            icon: 'error',
            title: '❌ خطا در ثبت اطلاعات',
            text: error.message || 'خطایی رخ داده است',
            confirmButtonText: 'باشه'
        });
    }
}
// همچنین یک تابع برای نمایش وضعیت احراز هویت اضافه کنید
function checkAuthStatus() {
    const token = localStorage.getItem('auth_token');
    if (!token) {
        Swal.fire({
            icon: 'warning',
            title: 'ورود به سیستم',
            text: 'برای ثبت اطلاعات باید وارد سیستم شوید. به صفحه ورود هدایت می‌شوید.',
            confirmButtonText: 'ورود'
        }).then(() => {
            window.location.href = '/login';
        });
        return false;
    }
    return true;
}

    </script>
>>>>>>> e82339cac376f551a8a66da0035c095e88a5df9d
</body>
</html>