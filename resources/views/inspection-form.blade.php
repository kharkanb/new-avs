<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>سیستم مدیریت بازدید تجهیزات اتوماسیون - شرکت توزیع نیروی برق استان یزد</title>

<<<<<<< HEAD
    <!-- ============================================ -->
    <!-- CSS فایل‌های خارجی (Library)                  -->
    <!-- ============================================ -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/persian-datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery.timepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/Vazirmatn-font-face.css') }}" rel="stylesheet">
    
    <!-- ============================================ -->
    <!-- CSS فایل سفارشی (Custom)                      -->
    <!-- ============================================ -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
=======
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
            --light-bg: #f8f9fa;
            --border-color: #dee2e6;
        }
        
        body {
            font-family: 'Vazirmatn', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
        }
        
.header {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    color: #2c3e50;
    padding: 20px 0;
    margin-bottom: 30px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}
        
        .form-section {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border: 1px solid var(--border-color);
        }
        
        .form-section h4 {
            color: var(--primary-color);
            border-bottom: 2px solid var(--secondary-color);
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .required::after {
            content: " *";
            color: var(--danger-color);
        }
        
        .equipment-card {
            border: 2px solid var(--border-color);
            border-radius: 8px;
            margin-bottom: 20px;
            background: var(--light-bg);
            transition: all 0.3s ease;
        }
        
        .equipment-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .equipment-header {
            background: var(--primary-color);
            color: white;
            padding: 15px;
            border-radius: 8px 8px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .checklist-item {
            padding: 10px;
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.3s;
        }
        
        .checklist-item:hover {
            background-color: #f8f9fa;
        }
        
        .ok-btn {
            background-color: var(--success-color) !important;
            border-color: var(--success-color) !important;
            color: white !important;
        }
        
        .not-ok-btn {
            background-color: var(--danger-color) !important;
            border-color: var(--danger-color) !important;
            color: white !important;
        }
        
        .form-control:disabled, .form-select:disabled {
            background-color: #e9ecef;
            cursor: not-allowed;
        }
        
        .tab-content {
            padding: 20px;
            border: 1px solid var(--border-color);
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        
        .nav-tabs .nav-link {
            color: var(--primary-color);
            font-weight: 500;
        }
        
        .nav-tabs .nav-link.active {
            background-color: var(--secondary-color);
            color: white;
            border-color: var(--secondary-color);
        }
        
        .summary-table {
            font-size: 0.9rem;
        }
        
        .summary-table th {
            background-color: var(--primary-color);
            color: white;
        }
        
        .action-buttons {
            position: sticky;
            bottom: 0;
            background: white;
            padding: 15px 0;
            border-top: 2px solid var(--border-color);
            margin-top: 30px;
            z-index: 100;
        }
        
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }
        
        .step-indicator::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 50px;
            right: 50px;
            height: 2px;
            background-color: var(--border-color);
            z-index: 1;
        }
        
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 2;
            flex: 1;
        }
        
        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--border-color);
            color: #666;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-bottom: 10px;
            border: 3px solid white;
        }
        
        .step.active .step-circle {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .step.completed .step-circle {
            background-color: var(--success-color);
            color: white;
        }
        
        .step-label {
            color: #666;
            font-size: 0.9rem;
            text-align: center;
        }
        
        .step.active .step-label {
            color: var(--primary-color);
            font-weight: bold;
        }
        
        .validation-error {
            border-color: var(--danger-color) !important;
        }
        
        .error-message {
            color: var(--danger-color);
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        .cell-spec-row {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border: 1px solid var(--border-color);
        }
        
        .photo-preview {
            position: relative;
            margin-bottom: 15px;
        }
        
        .photo-preview img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 5px;
        }
        
        .photo-remove {
            position: absolute;
            top: 5px;
            left: 5px;
        }
        
        .form-step {
            display: none;
        }
        
        .form-step.active {
            display: block;
            animation: fadeIn 0.5s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .tab-validated::after {
            content: " ✓";
            color: var(--success-color);
            font-weight: bold;
        }
        
        .equipment-summary-row {
            border-left: 4px solid var(--secondary-color);
            margin-bottom: 10px;
        }
        
        .whatsapp-btn {
            background-color: #25D366 !important;
            border-color: #25D366 !important;
            color: white !important;
        }
        
        .equipment-tech-section {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
            border: 1px solid var(--border-color);
        }
        
        .brand-other-container {
            display: none;
        }
        
        .brand-other-container.show {
            display: block;
        }
        
        .cell-equipment-row {
            background-color: #fff;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid var(--border-color);
        }
        
        .manual-equipment-row {
            background-color: #f0f7ff;
            border-left: 3px solid var(--secondary-color);
        }
        
        .equipment-type-container {
            background-color: #eef7ff;
            border: 1px solid #cce5ff;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 10px;
        }
        
        .add-equipment-btn {
            margin-top: 10px;
            margin-bottom: 20px;
        }
        
        .select2-container--open {
            z-index: 1051 !important;
        }
        
        .select2-dropdown {
            z-index: 1052 !important;
        }
        
        .modal {
            overflow-y: auto;
        }
        
        .modal-content {
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .modal-body {
            max-height: 70vh;
            overflow-y: auto;
        }
        
        .equipment-type-select + .select2-container,
        .equipment-brand-select + .select2-container {
            position: relative;
            z-index: 1000;
        }
        
        #equipmentModal .modal-dialog {
            max-width: 95%;
            margin: 1rem auto;
        }
        
        .department-container {
            background-color: #f0f8ff;
            border: 1px solid #cce5ff;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .department-row {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
        }
        
        .select2-container .select2-selection--single {
            height: 38px;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 38px;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
        
        .select2-container {
            z-index: 10000;
        }
        
        .modal-open .select2-container {
            z-index: 1053 !important;
        }
        
        .modal-open .select2-dropdown {
            z-index: 1054 !important;
        }
        
        .cell-equipment-container {
            margin-top: 10px;
        }
        
        .add-equipment-to-cell-btn {
            margin-top: 10px;
        }
        
        .remove-equipment-from-cell-btn {
            margin-top: 8px;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-weight: 600;
        }
        
        .persian-numbers {
            font-family: 'Vazirmatn', sans-serif;
        }
        
        .date-input-container, .time-input-container {
            position: relative;
        }
        
        .date-input-container .icon, .time-input-container .icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            pointer-events: none;
        }
        
        .date-input-container input, .time-input-container input {
            padding-left: 40px !important;
        }
        
        .ui-timepicker-wrapper {
            z-index: 1055 !important;
            width: 150px !important;
        }
        
        .ui-timepicker-list {
            display: block !important;
        }
        
        .ui-timepicker-list li {
            padding: 5px 10px !important;
            text-align: right !important;
            border-radius: 0 !important;
            margin: 0 !important;
            width: auto !important;
            height: auto !important;
            display: block !important;
            cursor: pointer !important;
            transition: all 0.3s !important;
        }
        
        .changes-modal-content {
            max-width: 90%;
            margin: 2rem auto;
        }
        
        .changes-list {
            list-style-type: none;
            padding: 0;
        }
        
        .changes-list li {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        
        .changes-list li:last-child {
            border-bottom: none;
        }
        
        .change-timestamp {
            font-size: 0.85rem;
            color: #666;
            margin-right: 10px;
        }
        
        .hide-height-fields {
            display: none !important;
        }
        
        /* استایل‌های جدید برای آیکون‌ها */
        .icon-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .icon-title i {
            font-size: 1.5rem;
            color: var(--primary-color);
        }
        
        .stat-card {
            transition: transform 0.3s ease;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .stat-card .card-body i {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        
        .btn-icon {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--secondary-color), #2980b9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
        }
        
        .feature-icon i {
            font-size: 1.5rem;
            color: white;
        }
        
        .tab-icon {
            margin-left: 5px;
        }
        
        /* بهبود ظاهر فرم‌ها */
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #ced4da;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        
        /* استایل برای دکمه‌ها */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--secondary-color), #2980b9);
            border: none;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #2980b9, #21618c);
            transform: translateY(-2px);
        }
        
        .btn-success {
            background: linear-gradient(135deg, var(--success-color), #219653);
            border: none;
        }
        
        .btn-success:hover {
            background: linear-gradient(135deg, #219653, #1e8449);
            transform: translateY(-2px);
        }
        
        /* استایل برای آواتار کاربر */
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        
        /* استایل برای نوار پیشرفت */
        .progress {
            height: 10px;
            border-radius: 5px;
            margin: 10px 0;
        }
        
        .progress-bar {
            background: linear-gradient(90deg, var(--secondary-color), var(--success-color));
        }
        
        /* استایل برای پیام‌های اطلاع‌رسانی */
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--danger-color);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
        }

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.spin {
    animation: spin 1s linear infinite;
    display: inline-block;
}



/* رفع مشکل z-index برای dropdownهای داخلی */
.tab-content .select2-container--open {
    z-index: 1060 !important;
}

.tab-content .select2-dropdown {
    z-index: 1061 !important;
}

/* رفع مشکل overflow در تب‌ها */
.tab-content {
    position: relative;
    z-index: 1;
}

/* بهبود ظاهر dropdown در تَب‌های اطلاعات فنی */
#tech-info-container .select2-container {
    z-index: 1000 !important;
}

#tech-info-container .select2-container--open {
    z-index: 1062 !important;
}

#tech-info-container .select2-dropdown {
    z-index: 1063 !important;
}

/* افزایش ارتفاع تب‌های محتوا برای نمایش کامل dropdown */
.tab-pane {
    min-height: 300px;
    position: relative;
}

/* رفع مشکل overflow در تَب فعالیت‌ها */
#activities-tab- .select2-container--open {
    z-index: 1070 !important;
}

/* استایل مخصوص برای dropdownهای درون modal */
#equipmentModal .select2-container--open {
    z-index: 1080 !important;
}

#equipmentModal .select2-dropdown {
    z-index: 1081 !important;
}




    </style>

<head>
    <!-- لینک‌های CSS موجود -->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/vazirmatn@5.0.8/index.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.13.18/jquery.timepicker.min.css">

    <!-- کتابخانه‌های جاوااسکریپت -->
       <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment-jalaali@0.9.3/build/moment-jalaali.min.js"></script>
    
<<<<<<< HEAD
    <!-- کتابخانه‌های PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
       <script src="https://cdn.jsdelivr.net/npm/html2pdf.js@0.10.1/dist/html2pdf.bundle.min.js"></script>

       <script src="https://unpkg.com/jspdf-rtl-plugin@1.0.0/dist/jspdf-rtl-plugin.min.js"></script>
      <link href="https://fonts.googleapis.com/css2?family=Vazirmatn&display=swap" rel="stylesheet">
=======
    <!-- اضافه کردن این لینک برای XLSX -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment-jalaali@0.9.3/build/moment-jalaali.min.js"></script>
>>>>>>> 62fd3f93d37034b824fcce2a25a722d1470dbcc9

</head>

>>>>>>> 524cace2901cfcda4f022b89d64c22cc653187c1
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
<<<<<<< HEAD
                <div class="user-avatar mt-2 mx-auto" onclick="showLoginModal()" style="cursor: pointer; width: 40px; height: 40px;">
                    <i class="bi bi-person-circle"></i>
                </div>
                <span id="user-name" style="font-size: 0.8rem; display: block;">ورود</span>
=======
>>>>>>> 524cace2901cfcda4f022b89d64c22cc653187c1
            </div>

<!-- در بخش header، بعد از ستون تاریخ اضافه کنید -->
<div class="col-md-2 text-center">
    <div class="user-avatar" onclick="showLoginModal()" style="cursor: pointer;" title="ورود به سیستم">
        <i class="bi bi-person-circle"></i>
    </div>
    <span id="user-name" style="font-size: 0.9rem; display: block;">ورود</span>
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
<<<<<<< HEAD
            <div class="col-md-6 text-end">
                <button class="btn btn-primary btn-icon" onclick="goToStep(4)">
                    <i class="bi bi-arrow-left"></i> ادامه به گزارش نهایی
                </button>
=======

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

        <!-- Step 3: Technical Information -->
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

        <!-- Step 4: Final Report -->
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
                                    <div class="feature-icon" style="width: 50px; height: 50px;">
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
                                    <div class="feature-icon" style="width: 50px; height: 50px;">
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
                                    <div class="feature-icon" style="width: 50px; height: 50px;">
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
                                    <div class="feature-icon" style="width: 50px; height: 50px;">
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


>>>>>>> 524cace2901cfcda4f022b89d64c22cc653187c1
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
<<<<<<< HEAD
        
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
=======
    </div>

    <!-- Footer -->
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

    <!-- Loading Overlay -->
    <div id="loadingOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; text-align:center; padding-top:20%; color:white; font-size:20px;">
        <i class="bi bi-arrow-repeat spin" style="font-size:40px;"></i><br>
        در حال ایجاد گزارش...
    </div>

    <!-- Modal for Equipment Details -->

<!-- Modal برای ورود -->
<div class="modal fade" id="loginModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-box-arrow-in-right"></i> ورود به سیستم</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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




    <div class="modal fade" id="equipmentModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-gear"></i> ویرایش اطلاعات تجهیز</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Modal content will be loaded dynamically -->
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.13.18/jquery.timepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    
    <!-- Main JavaScript -->
    <script>

// تابع ورود به سیستم (نسخه اصلاح شده)
async function loginUser(email, password) {
    try {
        console.log('Sending login request...');
        
        const response = await fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                email: email,
                password: password
            })
        });

        console.log('Response status:', response.status);
        
        // دریافت متن پاسخ
        const responseText = await response.text();
        console.log('Raw response:', responseText);

        // اگر پاسخ خالی است
        if (!responseText.trim()) {
            throw new Error('پاسخ خالی از سرور دریافت شد');
        }

        // تلاش برای تبدیل به JSON
        let result;
        try {
            result = JSON.parse(responseText);
        } catch (jsonError) {
            console.error('JSON parse error:', jsonError);
            throw new Error('فرمت پاسخ سرور نامعتبر است: ' + responseText.substring(0, 100));
        }

        // اگر وضعیت OK نیست
        if (!response.ok) {
            throw new Error(result.message || result.error || 'خطا در ورود');
        }

        // بررسی وجود توکن
        if (!result.token) {
            throw new Error('توکن دریافتی از سرور نامعتبر است');
        }

        // ذخیره توکن و اطلاعات کاربر
        localStorage.setItem('auth_token', result.token);
        if (result.user) {
            localStorage.setItem('user', JSON.stringify(result.user));
        }
        
        return true;

    } catch (error) {
        console.error('Login error details:', error);
        throw error;
    }
}



function showLoginModal() {
    const modal = new bootstrap.Modal(document.getElementById('loginModal'));
    modal.show();
}




// تابع handleLogin اصلاح شده
async function handleLogin(event) {
    event.preventDefault();
    
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    
    if (!email || !password) {
        Swal.fire({
            icon: 'warning',
            title: 'خطا',
            text: 'ایمیل و رمز عبور را وارد کنید'
        });
        return;
    }
    
    try {
        Swal.fire({
            title: 'در حال ورود...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        await loginUser(email, password);
        
        Swal.close();
        
        // بستن مودال
        const modal = bootstrap.Modal.getInstance(document.getElementById('loginModal'));
        if (modal) {
            modal.hide();
        }
        
        // به‌روزرسانی نمایش نام کاربری
        document.getElementById('user-name').textContent = email.split('@')[0]; // نمایش بخش اول ایمیل
        
        Swal.fire({
            icon: 'success',
            title: 'ورود موفق',
            text: 'به سیستم خوش آمدید',
            timer: 2000,
            showConfirmButton: false
        });
        
    } catch (error) {
        console.error('Login error:', error);
        
        Swal.fire({
            icon: 'error',
            title: 'خطا در ورود',
            text: error.message || 'خطایی رخ داده است. لطفا دوباره تلاش کنید.'
        });
    }
}



        function checkLibraries() {
            if (typeof XLSX === 'undefined') {
                console.error('XLSX library not loaded!');
                return false;
            }
            if (typeof saveAs === 'undefined') {
                console.error('FileSaver library not loaded!');
                return false;
            }
            return true;
        }




        // Global variables
        let equipmentCount = 0;
        let currentEquipmentIndex = -1;
        let equipments = [];
        let autoSaveEnabled = true;
        let autoSaveTimer = null;

        // الگوریتم تبدیل میلادی به شمسی
        function gregorianToJalali(gy, gm, gd) {
            var g_d_m = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
            var jy = (gy <= 1600) ? 0 : 979;
            gy -= (gy <= 1600) ? 621 : 1600;
            var gy2 = (gm > 2) ? (gy + 1) : gy;
            var days = (365 * gy) + parseInt((gy2 + 3) / 4) - parseInt((gy2 + 99) / 100) + parseInt((gy2 + 399) / 400) - 80 + gd + g_d_m[gm - 1];
            jy += 33 * parseInt(days / 12053);
            days %= 12053;
            jy += 4 * parseInt(days / 1461);
            days %= 1461;
            jy += parseInt((days - 1) / 365);
            if (days > 365) days = (days - 1) % 365;
            var jm = (days < 186) ? 1 + parseInt(days / 31) : 7 + parseInt((days - 186) / 30);
            var jd = 1 + ((days < 186) ? (days % 31) : ((days - 186) % 30));
            return [jy, jm, jd];
        }

        function jalaliToGregorian(jy, jm, jd) {
            var gy = (jy <= 979) ? 621 : 1600;
            jy -= (jy <= 979) ? 0 : 979;
            var days = (365 * jy) + (parseInt(jy / 33) * 8) + parseInt(((jy % 33) + 3) / 4) + 78 + jd + ((jm < 7) ? (jm - 1) * 31 : ((jm - 7) * 30) + 186);
            gy += 400 * parseInt(days / 146097);
            days %= 146097;
            if (days > 36524) {
                gy += 100 * parseInt(--days / 36524);
                days %= 36524;
                if (days >= 365) days++;
            }
            gy += 4 * parseInt(days / 1461);
            days %= 1461;
            if (days > 365) {
                gy += parseInt((days - 1) / 365);
                days = (days - 1) % 365;
            }
            var gd = days + 1;
            var sal_a = [0, 31, ((gy % 4 === 0 && gy % 100 !== 0) || (gy % 400 === 0)) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
            var gm = 0;
            while (gm < 13 && gd > sal_a[gm]) {
                gd -= sal_a[gm];
                gm++;
            }
            return [gy, gm, gd];
        }

        function formatJalaliDate(dateArray) {
            if (!dateArray || dateArray.length < 3) return '';
                     // تبدیل اعداد انگلیسی به فارسی
                     const toPersian = (num) => {
                  return num.toString().replace(/\d/g, d => '۰۱۲۳۴۵۶۷۸۹'[d]);
                    };
    
                    const year = toPersian(dateArray[0]);
                    const month = toPersian(('0' + dateArray[1]).slice(-2));
                    const day = toPersian(('0' + dateArray[2]).slice(-2));
    
                   return year + '/' + month + '/' + day;
                }


        function getCurrentJalaliDate() {
            const now = new Date();
            return gregorianToJalali(now.getFullYear(), now.getMonth() + 1, now.getDate());
        }

        // تابع برای ایجاد تقویم شمسی
        function createPersianDatepicker() {
            const input = $('#inspection-date');
            const currentDate = getCurrentJalaliDate();
            const formattedDate = formatJalaliDate(currentDate);
            
            input.val(formattedDate);
            
            input.on('click', function() {
                createDatepickerModal(this);
            });
        }

        function createDatepickerModal(inputElement) {
            const now = getCurrentJalaliDate();
            const currentYear = now[0];
            const currentMonth = now[1];
            
            const persianMonths = [
                'فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور',
                'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'
            ];
            
            const weekDays = ['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'];
            
            let calendarHTML = `
                <div class="modal fade" id="persianCalendarModal">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><i class="bi bi-calendar"></i> انتخاب تاریخ</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-center">
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <select class="form-select" id="calendarYear">
                                            ${generateYearOptions(currentYear)}
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <select class="form-select" id="calendarMonth">
                                            ${generateMonthOptions(currentMonth)}
                                        </select>
                                    </div>
>>>>>>> 524cace2901cfcda4f022b89d64c22cc653187c1
                                </div>
                                <h6 class="card-subtitle mb-2 text-muted">ضریب قرارداد</h6>
                                <h3 id="summary-coefficient" class="persian-numbers">۲.۳۵</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
<<<<<<< HEAD
                        <div class="card stat-card text-center">
=======
                        <label class="form-label">عملیات</label>
                        <button type="button" class="btn btn-sm btn-outline-danger w-100" 
                                onclick="removeEquipmentFromCell(this, '${equipmentId}', '${cellType}', ${cellNumber})">
                            <i class="bi bi-trash"></i> حذف
                        </button>
                    </div>
                </div>
                <div class="row mt-2 manual-equipment-section" style="display: none;" id="${cellType}-cell-${cellNumber}-equipment-${equipmentCount}-manual-${equipmentId}">
                    <div class="col-md-12">
                        <label class="form-label">نام تجهیز (سایر)</label>
                        <input type="text" class="form-control manual-equipment-name" 
                               placeholder="نام تجهیز را وارد کنید">
                    </div>
                </div>
            `;
            
            container.appendChild(row);
            
            setTimeout(() => {
                const typeSelect = row.querySelector('.equipment-type-select');
                if (typeSelect) {
                    $(typeSelect).select2({
                        placeholder: 'انتخاب کنید',
                        allowClear: true,
                        width: '100%',
                        dropdownParent: $('#equipmentModal'),
                        dir: 'rtl'
                    }).on('change', function() {
                        handleEquipmentTypeChange(this, equipmentId, cellType, cellNumber, equipmentCount);
                    });
                }
            }, 100);
        }

        function handleEquipmentTypeChange(select, equipmentId, cellType, cellNumber, equipmentIndex) {
            const equipmentType = select.value;
            
            const brandContainer = document.getElementById(`${cellType}-cell-${cellNumber}-equipment-${equipmentIndex}-brand-container-${equipmentId}`);
            const manualSection = document.getElementById(`${cellType}-cell-${cellNumber}-equipment-${equipmentIndex}-manual-${equipmentId}`);
            
            if (!brandContainer) return;
            
            if (manualSection) {
                manualSection.style.display = equipmentType === 'سایر' ? 'block' : 'none';
            }
            
            const brandSelect = brandContainer.querySelector('.equipment-brand-select');
            const otherContainer = brandContainer.querySelector('.brand-other-container');
            
            if (otherContainer) {
                otherContainer.style.display = 'none';
            }
            
            let brandsHTML = '<option value="">انتخاب کنید</option>';
            
            if (equipmentBrands[equipmentType]) {
                brandsHTML += equipmentBrands[equipmentType].map(brand => `<option value="${brand}">${brand}</option>`).join('');
            }
            
            brandSelect.innerHTML = brandsHTML;
            
            setTimeout(() => {
                $(brandSelect).select2({
                    placeholder: 'انتخاب کنید',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('#equipmentModal'),
                    dir: 'rtl'
                }).on('change', function() {
                    if (otherContainer) {
                        otherContainer.style.display = this.value === 'سایر' ? 'block' : 'none';
                    }
                });
            }, 100);
        }

        function removeEquipmentFromCell(button, equipmentId, cellType, cellNumber) {
            const container = button.closest('.equipment-type-container');
            if (container) {
                container.remove();
            }
        }

        function saveEquipment(equipmentId) {
            const form = document.getElementById(`equipment-form-${equipmentId}`);
            const equipment = equipments.find(e => e.id === equipmentId);
            
            if (!equipment) return;
            
            equipment.equipmentType = $(form.querySelector('.equipment-type-select')).val() || '';
            equipment.scadaCode = form.querySelector('.scada-code').value || '';
            equipment.startTime = form.querySelector('.start-time')?.value || '';
            equipment.endTime = form.querySelector('.end-time')?.value || '';
            
            equipment.departmentData = {
                department: $(form.querySelector('.department-select')).val() || '',
                city: form.querySelector('.city').value || ''
            };
            
            if (equipmentWithBrands.includes(equipment.equipmentType)) {
                equipment.switchBrand = $(form.querySelector('.switch-brand-select')).val() || '';
                equipment.modemBrand = $(form.querySelector('.modem-brand-select')).val() || '';
                equipment.rtuBrand = $(form.querySelector('.rtu-brand-select')).val() || '';
                
                equipment.otherSwitchBrand = document.getElementById(`switch-other-input-${equipmentId}`)?.value || '';
                equipment.otherModemBrand = document.getElementById(`modem-other-input-${equipmentId}`)?.value || '';
                equipment.otherRTUBrand = document.getElementById(`rtu-other-input-${equipmentId}`)?.value || '';
            } else {
                equipment.switchBrand = '';
                equipment.modemBrand = '';
                equipment.rtuBrand = '';
                equipment.otherSwitchBrand = '';
                equipment.otherModemBrand = '';
                equipment.otherRTUBrand = '';
            }
            
            const installationTypeSelect = form.querySelector('.installation-type-select');
            if (installationTypeSelect) {
                equipment.installationType = $(installationTypeSelect).val() || '';
            }
            
            equipment.feeders = [];
            const feederPosts = form.querySelectorAll('.feeder-post-select');
            
            feederPosts.forEach((post, index) => {
                const postValue = $(post).val();
                const feederSelect = document.getElementById(`feeder-select-${equipmentId}-${index + 1}`);
                const feederValue = feederSelect ? $(feederSelect).val() : '';
                
                if (postValue && feederValue) {
                    equipment.feeders.push({
                        post: postValue,
                        feeder: feederValue
                    });
                }
            });
            
            const inputCellsCount = form.querySelector('.input-cells-count');
            if (inputCellsCount) {
                equipment.cellSpecs = {
                    inputCellsCount: inputCellsCount.value || 0,
                    outputCellsCount: form.querySelector('.output-cells-count')?.value || 0,
                    measurementCellsCount: form.querySelector('.measurement-cells-count')?.value || 0,
                    otherEquipmentsNotes: form.querySelector('.other-equipments-notes')?.value || '',
                    inputCells: [],
                    outputCells: [],
                    measurementCells: []
                };
                
                ['input', 'output', 'measurement'].forEach(cellType => {
                    const count = parseInt(equipment.cellSpecs[`${cellType}CellsCount`]) || 0;
                    
                    for (let i = 1; i <= count; i++) {
                        const cellNotes = document.getElementById(`${cellType}-cell-${i}-notes-${equipmentId}`)?.value || '';
                        const cellContainer = document.getElementById(`${cellType}-cell-${i}-equipments-${equipmentId}`);
                        
                        const cell = {
                            cellNumber: i,
                            notes: cellNotes,
                            equipments: []
                        };
                        
                        if (cellContainer) {
                            const equipmentContainers = cellContainer.querySelectorAll('.equipment-type-container');
                            equipmentContainers.forEach(container => {
                                const typeSelect = container.querySelector('.equipment-type-select');
                                const brandSelect = container.querySelector('.equipment-brand-select');
                                const otherBrandInput = container.querySelector('.other-brand-input');
                                const manualNameInput = container.querySelector('.manual-equipment-name');
                                
                                const equipmentType = typeSelect ? $(typeSelect).val() : '';
                                const brand = brandSelect ? $(brandSelect).val() : '';
                                const otherBrand = otherBrandInput ? otherBrandInput.value : '';
                                const manualName = manualNameInput ? manualNameInput.value : '';
                                
                                if (equipmentType) {
                                    const eqData = {
                                        type: equipmentType,
                                        brand: brand,
                                        otherBrand: otherBrand
                                    };
                                    
                                    if (equipmentType === 'سایر' && manualName) {
                                        eqData.name = manualName;
                                    }
                                    
                                    cell.equipments.push(eqData);
                                }
                            });
                        }
                        
                        equipment.cellSpecs[`${cellType}Cells`].push(cell);
                    }
                });
            }
            
            updateEquipmentCard(equipmentId);
            
            const modal = bootstrap.Modal.getInstance(document.getElementById('equipmentModal'));
            if (modal) {
                modal.hide();
            }
            
            updateTechnicalInfoSections();
            triggerAutoSave();
            
            alert('اطلاعات تجهیز با موفقیت ذخیره شد.');
        }

        function updateEquipmentCard(equipmentId) {
            const equipment = equipments.find(e => e.id === equipmentId);
            if (!equipment) return;
            
            const card = document.getElementById(equipmentId);
            if (!card) return;
            
            card.querySelector(`#${equipmentId}-type`).textContent = equipment.equipmentType || '---';
            card.querySelector(`#${equipmentId}-scada`).textContent = equipment.scadaCode || '---';
            
            if (equipmentWithBrands.includes(equipment.equipmentType)) {
                const switchBrandDisplay = equipment.switchBrand === 'سایر' ? equipment.otherSwitchBrand : equipment.switchBrand;
                card.querySelector(`#${equipmentId}-switch-brand`).textContent = switchBrandDisplay || '---';
            } else {
                card.querySelector(`#${equipmentId}-switch-brand`).textContent = 'بدون برند';
            }
            
            const feedersText = equipment.feeders && equipment.feeders.length > 0 
                ? equipment.feeders.map(f => `${f.post} (${f.feeder})`).join('، ')
                : '---';
            card.querySelector(`#${equipmentId}-feeders`).textContent = feedersText;
            
            const validatedCount = equipment.tabsValidated ? Object.keys(equipment.tabsValidated).length : 0;
            const statusBadge = card.querySelector(`#${equipmentId}-status`);
            
            let statusText = 'فاقد اطلاعات';
            let statusClass = 'bg-danger';
            
            if (validatedCount >= 5) {
                statusText = 'اطلاعات کامل';
                statusClass = 'bg-success';
            } else if (validatedCount >= 3) {
                statusText = 'اطلاعات متوسط';
                statusClass = 'bg-warning';
            } else if (validatedCount > 0) {
                statusText = 'اطلاعات جزئی';
                statusClass = 'bg-info';
            }
            
            statusBadge.className = `badge ${statusClass}`;
            statusBadge.textContent = statusText;
        }

        function removeEquipment(equipmentId) {
            if (confirm('آیا از حذف این تجهیز اطمینان دارید؟')) {
                const index = equipments.findIndex(e => e.id === equipmentId);
                if (index !== -1) {
                    const equipment = equipments[index];
                    equipments.splice(index, 1);
                    
                    const card = document.getElementById(equipmentId);
                    if (card) {
                        card.remove();
                    }
                    
                    updateTechnicalInfoSections();
                    triggerAutoSave();
                    
                    alert('تجهیز با موفقیت حذف شد.');
                }
            }
        }



        // Technical Tab Functions
        function updateTechnicalInfoSections() {
            const container = document.getElementById('tech-info-container');
            container.innerHTML = '';
            
            if (equipments.length === 0) {
                container.innerHTML = `
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        هنوز هیچ تجهیزی اضافه نشده است. لطفا در مرحله قبل تجهیزات را اضافه کنید.
                    </div>
                `;
                return;
            }
            
            equipments.forEach((equipment, index) => {
                const section = document.createElement('div');
                section.className = 'equipment-tech-section';
                section.id = `tech-section-${equipment.id}`;
                
                section.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5><i class="bi bi-gear"></i> اطلاعات فنی تجهیز ${index + 1}: ${equipment.equipmentType}</h5>
                    </div>
                    
                    <ul class="nav nav-tabs" id="techInfoTabs-${equipment.id}">
                        <li class="nav-item">
                            <button class="nav-link ${equipment.tabsValidated?.location ? 'tab-validated' : ''} ${index === 0 ? 'active' : ''}" 
                                    data-bs-toggle="tab" data-bs-target="#location-tab-${equipment.id}" 
                                    id="tab-location-${equipment.id}">
                                <i class="bi bi-geo-alt tab-icon"></i> موقعیت جغرافیایی
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link ${equipment.tabsValidated?.communication ? 'tab-validated' : ''}" 
                                    data-bs-toggle="tab" data-bs-target="#communication-tab-${equipment.id}" 
                                    id="tab-communication-${equipment.id}">
                                <i class="bi bi-wifi tab-icon"></i> ارتباطات
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link ${equipment.tabsValidated?.checklist ? 'tab-validated' : ''}" 
                                    data-bs-toggle="tab" data-bs-target="#checklist-tab-${equipment.id}" 
                                    id="tab-checklist-${equipment.id}">
                                <i class="bi bi-clipboard-check tab-icon"></i> چک‌لیست بازدید
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link ${equipment.tabsValidated?.activities ? 'tab-validated' : ''}" 
                                    data-bs-toggle="tab" data-bs-target="#activities-tab-${equipment.id}" 
                                    id="tab-activities-${equipment.id}">
                                <i class="bi bi-list-check tab-icon"></i> فعالیت‌ها و مصارف
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link ${equipment.tabsValidated?.photos ? 'tab-validated' : ''}" 
                                    data-bs-toggle="tab" data-bs-target="#photos-tab-${equipment.id}" 
                                    id="tab-photos-${equipment.id}">
                                <i class="bi bi-camera tab-icon"></i> مستندات تصویری
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="techInfoTabsContent-${equipment.id}">
                        <div class="tab-pane fade ${index === 0 ? 'show active' : ''}" id="location-tab-${equipment.id}">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required"><i class="bi bi-globe"></i> عرض جغرافیایی (Latitude)</label>
                                    <input type="text" class="form-control" id="latitude-${equipment.id}" 
                                           placeholder="مثال: 31.8974" 
                                           pattern="^-?([1-8]?[0-9]\.\d+|90\.0+)$" 
                                           title="فرمت صحیح: 31.8974"
                                           value="${equipment.locationData?.latitude || ''}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required"><i class="bi bi-globe-americas"></i> طول جغرافیایی (Longitude)</label>
                                    <input type="text" class="form-control" id="longitude-${equipment.id}" 
                                           placeholder="مثال: 54.3569"
                                           pattern="^-?([1-9]?[0-9]\.\d+|1[0-7][0-9]\.\d+|180\.0+)$"
                                           title="فرمت صحیح: 54.3569"
                                           value="${equipment.locationData?.longitude || ''}">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label required"><i class="bi bi-geo-alt"></i> آدرس نصب</label>
                                    <textarea class="form-control" id="installation-address-${equipment.id}" rows="2">${equipment.locationData?.address || ''}</textarea>
                                </div>
                                ${!equipmentWithoutHeight.includes(equipment.equipmentType) ? `
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required"><i class="bi bi-rulers"></i> ارتفاع اولیه تابلو (متر)</label>
                                    <input type="number" class="form-control cabinet-height-field" 
                                           step="0.1" min="0" value="${equipment.locationData?.cabinetInitialHeight || ''}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label"><i class="bi bi-rulers"></i> ارتفاع نهایی زیر تابلو تا زمین (متر)</label>
                                    <input type="number" class="form-control cabinet-height-field" 
                                           step="0.1" min="0" value="${equipment.locationData?.cabinetFinalHeight || ''}">
                                </div>
                                ` : ''}
                            </div>
                            <div class="text-end">
                                <button class="btn btn-primary btn-icon" onclick="saveTechnicalTabData('${equipment.id}', 'location')">
                                    <i class="bi bi-save"></i> ذخیره اطلاعات موقعیت
                                </button>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="communication-tab-${equipment.id}">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label required"><i class="bi bi-sim"></i> نوع سیم‌کارت</label>
                                    <select class="form-select tech-tab-select" id="simcard-type-${equipment.id}">
                                        <option value="">انتخاب کنید</option>
                                        <option value="ایرانسل" ${equipment.communicationData?.simcardType === 'ایرانسل' ? 'selected' : ''}>ایرانسل</option>
                                        <option value="همراه اول" ${equipment.communicationData?.simcardType === 'همراه اول' ? 'selected' : ''}>همراه اول</option>
                                        <option value="رایتل" ${equipment.communicationData?.simcardType === 'رایتل' ? 'selected' : ''}>رایتل</option>
                                        <option value="شاتل" ${equipment.communicationData?.simcardType === 'شاتل' ? 'selected' : ''}>شاتل</option>
                                        <option value="سایر" ${equipment.communicationData?.simcardType === 'سایر' ? 'selected' : ''}>سایر</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label required"><i class="bi bi-phone"></i> شماره سیم‌کارت</label>
                                    <input type="text" class="form-control" id="simcard-number-${equipment.id}" 
                                           placeholder="مثال: 09106545840"
                                           pattern="09[0-9]{9}" title="فرمت صحیح: 09123456789"
                                           value="${equipment.communicationData?.simcardNumber || ''}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label required"><i class="bi bi-hdd-network"></i> IP سیم‌کارت</label>
                                    <input type="text" class="form-control" id="simcard-ip-${equipment.id}" 
                                           placeholder="مثال: 10.213.77.5"
                                           pattern="^((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$"
                                           title="فرمت صحیح IP: 10.213.77.5"
                                           value="${equipment.communicationData?.simcardIp || ''}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required"><i class="bi bi-wifi"></i> وضعیت نصب آنتن</label>
                                    <select class="form-select tech-tab-select" id="antenna-status-${equipment.id}">
                                        <option value="">انتخاب کنید</option>
                                        <option value="ندارد" ${equipment.communicationData?.antennaStatus === 'ندارد' ? 'selected' : ''}>ندارد</option>
                                        <option value="داخل تابلو" ${equipment.communicationData?.antennaStatus === 'داخل تابلو' ? 'selected' : ''}>داخل تابلو</option>
                                        <option value="خارج تابلو" ${equipment.communicationData?.antennaStatus === 'خارج تابلو' ? 'selected' : ''}>خارج تابلو</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required"><i class="bi bi-bar-chart"></i> وضعیت سیگنال ارتباطی</label>
                                    <select class="form-select tech-tab-select" id="signal-status-${equipment.id}">
                                        <option value="">انتخاب کنید</option>
                                        <option value="خوب" ${equipment.communicationData?.signalStatus === 'خوب' ? 'selected' : ''}>خوب</option>
                                        <option value="ضعیف" ${equipment.communicationData?.signalStatus === 'ضعیف' ? 'selected' : ''}>ضعیف</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required"><i class="bi bi-battery-charging"></i> تغذیه مودم</label>
                                    <select class="form-select tech-tab-select" id="modem-power-${equipment.id}">
                                        <option value="">انتخاب کنید</option>
                                        <option value="پنل" ${equipment.communicationData?.modemPower === 'پنل' ? 'selected' : ''}>پنل</option>
                                        <option value="باتری" ${equipment.communicationData?.modemPower === 'باتری' ? 'selected' : ''}>باتری</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="reset-possible-${equipment.id}" ${equipment.communicationData?.resetPossible ? 'checked' : ''}>
                                        <label class="form-check-label" for="reset-possible-${equipment.id}">
                                            <i class="bi bi-arrow-clockwise"></i> تجهیز قابلیت ریست دارد
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <button class="btn btn-primary btn-icon" onclick="saveTechnicalTabData('${equipment.id}', 'communication')">
                                    <i class="bi bi-save"></i> ذخیره اطلاعات ارتباطی
                                </button>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="checklist-tab-${equipment.id}">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i>
                                <strong>توجه:</strong> برای هر آیتم وضعیت "OK" یا "Not OK" را انتخاب کنید. در صورت Not OK توضیحات الزامی است.
                            </div>
                            
                            <div id="checklist-items-${equipment.id}">
                                ${getChecklistItemsHTML(equipment)}
                            </div>
                            <div class="mt-3 text-end">
                                <button class="btn btn-primary btn-icon" onclick="saveTechnicalTabData('${equipment.id}', 'checklist')">
                                    <i class="bi bi-save"></i> ذخیره چک‌لیست
                                </button>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="activities-tab-${equipment.id}">
                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <h5 class="border-bottom pb-2"><i class="bi bi-list-check"></i> فعالیت‌های انجام شده (فهرست بها)</h5>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm" id="activities-table-${equipment.id}">
                                            <thead class="table-light">
                                                <tr>
                                                    <th><i class="bi bi-hash"></i> ردیف</th>
                                                    <th><i class="bi bi-code-slash"></i> کد فهرست بها</th>
                                                    <th><i class="bi bi-card-text"></i> عنوان فعالیت</th>
                                                    <th><i class="bi bi-rulers"></i> واحد</th>
                                                    <th><i class="bi bi-currency-exchange"></i> فی واحد (ریال)</th>
                                                    <th><i class="bi bi-123"></i> تعداد</th>
                                                    <th><i class="bi bi-cash-stack"></i> مبلغ (بدون ضریب)</th>
                                                    <th><i class="bi bi-trash"></i> حذف</th>
                                                </tr>
                                            </thead>
                                            <tbody id="activities-tbody-${equipment.id}">
                                                <!-- Will be populated dynamically -->
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="6" class="text-end"><strong><i class="bi bi-calculator"></i> جمع کل:</strong></td>
                                                    <td id="activities-total-${equipment.id}" class="persian-numbers">۰</td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <button class="btn btn-sm btn-outline-primary btn-icon" onclick="addActivityRow('${equipment.id}')">
                                        <i class="bi bi-plus-circle"></i> افزودن فعالیت
                                    </button>
                                </div>

                                <div class="col-md-12">
                                    <h5 class="border-bottom pb-2"><i class="bi bi-box-seam"></i> تجهیزات مصرفی</h5>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm" id="consumables-table-${equipment.id}">
                                            <thead class="table-light">
                                                <tr>
                                                    <th><i class="bi bi-hash"></i> ردیف</th>
                                                    <th><i class="bi bi-box"></i> نام قلم مصرفی</th>
                                                    <th><i class="bi bi-123"></i> تعداد</th>
                                                    <th><i class="bi bi-rulers"></i> واحد</th>
                                                    <th><i class="bi bi-chat-left-text"></i> توضیحات</th>
                                                    <th><i class="bi bi-trash"></i> حذف</th>
                                                </tr>
                                            </thead>
                                            <tbody id="consumables-tbody-${equipment.id}">
                                                <!-- Will be populated dynamically -->
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="2" class="text-end"><strong><i class="bi bi-calculator"></i> جمع اقلام مصرفی:</strong></td>
                                                    <td id="consumables-total-${equipment.id}" class="persian-numbers">۰</td>
                                                    <td colspan="3"></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <button class="btn btn-sm btn-outline-success btn-icon" onclick="addConsumableRow('${equipment.id}')">
                                        <i class="bi bi-plus-circle"></i> افزودن قلم مصرفی
                                    </button>
                                </div>
                            </div>
                            <div class="text-end mt-3">
                                <button class="btn btn-primary btn-icon" onclick="saveActivitiesTab('${equipment.id}')">
                                    <i class="bi bi-save"></i> ذخیره فعالیت‌ها و مصارف
                                </button>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="photos-tab-${equipment.id}">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label"><i class="bi bi-cloud-arrow-up"></i> آپلود تصاویر</label>
                                    <input type="file" class="form-control" id="photo-upload-${equipment.id}" 
                                           accept="image/*" multiple onchange="handlePhotoUpload('${equipment.id}', this)">
                                    <small class="text-muted"><i class="bi bi-info-circle"></i> می‌توانید چندین عکس را انتخاب کنید (حداکثر 10 مگابایت برای هر عکس)</small>
                                </div>
                            </div>
                            
                            <div class="row" id="photos-preview-${equipment.id}">
                                ${getPhotosPreviewHTML(equipment)}
                            </div>
                            <div class="text-end mt-3">
                                <button class="btn btn-primary btn-icon" onclick="savePhotosTab('${equipment.id}')">
                                    <i class="bi bi-save"></i> ذخیره تصاویر
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                
                container.appendChild(section);
                loadExistingActivitiesAndConsumables(equipment.id);
            });



    setTimeout(() => {
        equipments.forEach(equip => {
            if (document.getElementById(`simcard-type-${equip.id}`)) {
                try {
                    $(`#simcard-type-${equip.id}, #antenna-status-${equip.id}, #signal-status-${equip.id}, #modem-power-${equip.id}`).select2({
                        placeholder: 'انتخاب کنید',
                        allowClear: true,
                        width: '100%',
                        dir: 'rtl',
                        dropdownParent: $(`#communication-tab-${equip.id}`)
                    });
                    console.log(`Select2 initialized for equipment ${equip.id}`);
                } catch (e) {
                    console.log(`Select2 error for equipment ${equip.id}:`, e);
                }
            }
        });
    }, 500);
}


        function loadExistingActivitiesAndConsumables(equipmentId) {
            const equipment = equipments.find(e => e.id === equipmentId);
            if (!equipment) return;
            
            if (equipment.activitiesData && equipment.activitiesData.length > 0) {
                equipment.activitiesData.forEach((activity, index) => {
                    addActivityRow(equipmentId);
                    
                    setTimeout(() => {
                        const tbody = document.getElementById(`activities-tbody-${equipmentId}`);
                        const rows = tbody.querySelectorAll('tr');
                        const row = rows[rows.length - 1];
                        
                        if (row) {
                            row.querySelector('.activity-code').value = activity.code;
                            $(row.querySelector('.activity-code')).trigger('change');
                            row.querySelector('.activity-quantity').value = activity.quantity || 1;
                            updateActivityTotal(equipmentId);
                        }
                    }, 50);
                });
            } else {
                addActivityRow(equipmentId);
            }
            
            if (equipment.consumablesData && equipment.consumablesData.length > 0) {
                equipment.consumablesData.forEach((consumable, index) => {
                    addConsumableRow(equipmentId);
                    
                    setTimeout(() => {
                        const tbody = document.getElementById(`consumables-tbody-${equipmentId}`);
                        const rows = tbody.querySelectorAll('tr');
                        const row = rows[rows.length - 1];
                        
                        if (row) {
                            row.querySelector('.consumable-name').value = consumable.name;
                            row.querySelector('.consumable-quantity').value = consumable.quantity || 1;
                            row.querySelector('.consumable-unit').value = consumable.unit || 'عدد';
                            row.querySelector('.consumable-description').value = consumable.description || '';
                            
                            if (consumable.name === 'سایر اقلام') {
                                const otherInput = row.querySelector('.other-consumable');
                                if (otherInput) {
                                    otherInput.style.display = 'block';
                                    otherInput.value = consumable.otherName || '';
                                }
                            }
                            
                            updateConsumablesTotal(equipmentId);
                        }
                    }, 50);
                });
            } else {
                addConsumableRow(equipmentId);
            }
        }

        function getChecklistItemsHTML(equipment) {
            const checklist = equipmentChecklists[equipment.equipmentType] || [];
            let checklistHTML = '';
            
            checklist.forEach((item, index) => {
                const existingItem = equipment.checklistData && equipment.checklistData[index];
                const itemId = `checklist-${equipment.id}-${index}`;
                const isOK = existingItem?.status === 'OK';
                const isNotOK = existingItem?.status === 'Not OK';
                
                checklistHTML += `
                    <div class="checklist-item" id="${itemId}">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p class="mb-0">${index + 1}. ${item}</p>
                            </div>
                            <div class="col-md-4">
                                <div class="btn-group w-100" role="group">
                                    <button type="button" 
                                            class="btn ${isOK ? 'ok-btn' : 'btn-outline-success'} btn-icon" 
                                            onclick="setChecklistStatus('${equipment.id}', ${index}, 'OK')">
                                        <i class="bi bi-check-circle"></i> OK
                                    </button>
                                    <button type="button" 
                                            class="btn ${isNotOK ? 'not-ok-btn' : 'btn-outline-danger'} btn-icon" 
                                            onclick="setChecklistStatus('${equipment.id}', ${index}, 'Not OK')">
                                        <i class="bi bi-x-circle"></i> Not OK
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2" id="${itemId}-description" style="${isNotOK ? 'display: block;' : 'display: none;'}">
                            <div class="col-md-12">
                                <label class="form-label"><i class="bi bi-chat-left-text"></i> توضیحات و اقدامات اصلاحی</label>
                                <textarea class="form-control" rows="2" id="${itemId}-description-text">${existingItem?.description || ''}</textarea>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            return checklistHTML;
        }

        function getPhotosPreviewHTML(equipment) {
            let html = '';
            const photos = equipment.photosData || [];
            
            if (photos.length === 0) {
                html = '<div class="col-12 text-center"><p class="text-muted"><i class="bi bi-images"></i> هنوز تصویری آپلود نشده است</p></div>';
            } else {
                photos.forEach((photo, index) => {
                    html += `
                        <div class="col-md-4 mb-3">
                            <div class="photo-preview">
                                <button type="button" class="btn btn-sm btn-danger photo-remove" onclick="removePhoto(this, '${equipment.id}')">
                                    <i class="bi bi-x"></i>
                                </button>
                                <img src="${photo.dataUrl}" class="img-fluid rounded" alt="تصویر ${index + 1}">
                                <div class="mt-2">
                                    <input type="text" class="form-control form-control-sm mb-1 scan-code" 
                                           placeholder="کد عکس" value="${photo.scanCode || ''}">
                                    <textarea class="form-control form-control-sm photo-description" rows="2" 
                                              placeholder="توضیحات">${photo.description || ''}</textarea>
                                </div>
                            </div>
                        </div>
                    `;
                });
            }
            
            return html;
        }

        function setChecklistStatus(equipmentId, index, status) {
            const item = document.getElementById(`checklist-${equipmentId}-${index}`);
            const descriptionDiv = document.getElementById(`checklist-${equipmentId}-${index}-description`);
            
            if (!item) return;
            
            const okBtn = item.querySelector('.btn:first-child');
            const notOkBtn = item.querySelector('.btn:last-child');
            
            if (status === 'OK') {
                okBtn.className = 'btn ok-btn btn-icon';
                notOkBtn.className = 'btn btn-outline-danger btn-icon';
                if (descriptionDiv) descriptionDiv.style.display = 'none';
            } else {
                okBtn.className = 'btn btn-outline-success btn-icon';
                notOkBtn.className = 'btn not-ok-btn btn-icon';
                if (descriptionDiv) descriptionDiv.style.display = 'block';
            }
        }

        function saveTechnicalTabData(equipmentId, tabName) {
            const equipment = equipments.find(e => e.id === equipmentId);
            if (!equipment) return;
            
            if (tabName === 'location') {
                const shouldHideHeight = equipmentWithoutHeight.includes(equipment.equipmentType);
                
                equipment.locationData = {
                    latitude: document.getElementById(`latitude-${equipmentId}`).value,
                    longitude: document.getElementById(`longitude-${equipmentId}`).value,
                    address: document.getElementById(`installation-address-${equipmentId}`).value
                };
                
                if (!shouldHideHeight) {
                    const heightFields = document.querySelectorAll(`#location-tab-${equipmentId} .cabinet-height-field`);
                    if (heightFields.length >= 2) {
                        equipment.locationData.cabinetInitialHeight = heightFields[0].value;
                        equipment.locationData.cabinetFinalHeight = heightFields[1].value;
                    }
                } else {
                    equipment.locationData.cabinetInitialHeight = '';
                    equipment.locationData.cabinetFinalHeight = '';
                }
                
            } else if (tabName === 'communication') {
                equipment.communicationData = {
                    simcardType: document.getElementById(`simcard-type-${equipmentId}`).value,
                    simcardNumber: document.getElementById(`simcard-number-${equipmentId}`).value,
                    simcardIp: document.getElementById(`simcard-ip-${equipmentId}`).value,
                    antennaStatus: document.getElementById(`antenna-status-${equipmentId}`).value,
                    signalStatus: document.getElementById(`signal-status-${equipmentId}`).value,
                    modemPower: document.getElementById(`modem-power-${equipmentId}`).value,
                    resetPossible: document.getElementById(`reset-possible-${equipmentId}`).checked
                };
            } else if (tabName === 'checklist') {
                const checklist = [];
                const items = document.querySelectorAll(`#checklist-items-${equipmentId} .checklist-item`);
                
                items.forEach((item, index) => {
                    const okBtn = item.querySelector('.btn:first-child');
                    const notOkBtn = item.querySelector('.btn:last-child');
                    const descriptionText = item.querySelector('textarea')?.value || '';
                    
                    let status = '';
                    if (okBtn.classList.contains('ok-btn')) {
                        status = 'OK';
                    } else if (notOkBtn.classList.contains('not-ok-btn')) {
                        status = 'Not OK';
                    }
                    
                    if (status) {
                        checklist.push({
                            item: item.querySelector('p').textContent.replace(`${index + 1}. `, ''),
                            status: status,
                            description: descriptionText
                        });
                    }
                });
                
                equipment.checklistData = checklist;
            }
            
            markTabAsValidated(equipmentId, tabName);
            updateEquipmentCard(equipmentId);
            triggerAutoSave();
            
            alert(`اطلاعات ${tabName} برای تجهیز ${equipment.index} با موفقیت ذخیره شد.`);
            return true;
        }

        // Activities and Consumables Functions



function addActivityRow(equipmentId) {
    const tbody = document.getElementById(`activities-tbody-${equipmentId}`);
    const rowCount = tbody.children.length + 1;
    
    const row = document.createElement('tr');
    row.innerHTML = `
        <td class="persian-numbers">${rowCount}</td>
        <td>
            <select class="form-select form-select-sm activity-code" onchange="updateActivityRow('${equipmentId}', this)">
                <option value="">انتخاب کنید</option>
                ${priceList.map(item => 
                    `<option value="${item.code}" data-title="${item.title}" data-unit="${item.unit}" data-price="${item.price}">
                        ${item.code}
                    </option>`
                ).join('')}
            </select>
        </td>
        <td class="activity-title">---</td>
        <td class="activity-unit">---</td>
        <td class="activity-price text-end persian-numbers">---</td>
        <td>
            <input type="number" class="form-control form-control-sm activity-quantity persian-numbers" 
                   min="1" value="1" onchange="updateActivityTotal('${equipmentId}')">
        </td>
        <td class="activity-total text-end persian-numbers">۰</td>
        <td>
            <button class="btn btn-sm btn-outline-danger" onclick="removeActivityRow('${equipmentId}', this)">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(row);
    
    setTimeout(() => {
        // تنظیم dropdownParent برای نمایش صحیح در تب
        const tabContent = document.querySelector(`#activities-tab-${equipmentId}`);
        $(row.querySelector('.activity-code')).select2({
            placeholder: 'انتخاب کنید',
            allowClear: true,
            width: '100%',
            dir: 'rtl',
            dropdownParent: tabContent || document.body
        });
    }, 100);
    
    updateActivityTotal(equipmentId);
}






        function updateActivityRow(equipmentId, select) {
            const row = select.closest('tr');
            const selectedOption = select.options[select.selectedIndex];
            
            row.querySelector('.activity-title').textContent = selectedOption.dataset.title || '---';
            row.querySelector('.activity-unit').textContent = selectedOption.dataset.unit || '---';
            row.querySelector('.activity-price').textContent = 
                selectedOption.dataset.price ? formatNumber(selectedOption.dataset.price) + ' ریال' : '---';
            
            updateActivityTotal(equipmentId);
        }

        function updateActivityTotal(equipmentId) {
            let total = 0;
            const rows = document.querySelectorAll(`#activities-tbody-${equipmentId} tr`);
            
            rows.forEach((row, index) => {
                const quantity = parseInt(row.querySelector('.activity-quantity').value) || 0;
                const price = parseInt(row.querySelector('.activity-code').selectedOptions[0]?.dataset.price) || 0;
                const rowTotal = quantity * price;
                
                row.querySelector('.activity-total').textContent = formatNumber(rowTotal);
                row.querySelector('td:first-child').textContent = index + 1;
                total += rowTotal;
            });
            
            document.getElementById(`activities-total-${equipmentId}`).textContent = formatNumber(total);
        }

        function addConsumableRow(equipmentId) {
            const tbody = document.getElementById(`consumables-tbody-${equipmentId}`);
            const rowCount = tbody.children.length + 1;
            
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="persian-numbers">${rowCount}</td>
                <td>
                    <select class="form-select form-select-sm consumable-name" onchange="toggleOtherConsumable('${equipmentId}', this)">
                        <option value="">انتخاب کنید</option>
                        ${consumablesList.map(item => `<option value="${item.name}">${item.name}</option>`).join('')}
                    </select>
                    <input type="text" class="form-control form-control-sm mt-1 other-consumable" 
                           placeholder="نام قلم مصرفی" style="display: none;">
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm consumable-quantity persian-numbers" min="1" value="1">
                </td>
                <td>
                    <select class="form-select form-select-sm consumable-unit">
                        <option value="عدد">عدد</option>
                        <option value="متر">متر</option>
                        <option value="کیلوگرم">کیلوگرم</option>
                        <option value="لیتر">لیتر</option>
                        <option value="بسته">بسته</option>
                        <option value="رول">رول</option>
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm consumable-description" 
                           placeholder="توضیحات (اختیاری)">
                </td>
                <td>
                    <button class="btn btn-sm btn-outline-danger" onclick="removeConsumableRow('${equipmentId}', this)">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;
            
            tbody.appendChild(row);
            
            setTimeout(() => {
                $(row.querySelector('.consumable-name')).select2({
                    placeholder: 'انتخاب کنید',
                    allowClear: true,
                    width: '100%',
                    dir: 'rtl'
                });
                
                $(row.querySelector('.consumable-unit')).select2({
                    placeholder: 'انتخاب کنید',
                    allowClear: true,
                    width: '100%',
                    dir: 'rtl'
                });
            }, 50);
            
            updateConsumablesTotal(equipmentId);
        }

        function toggleOtherConsumable(equipmentId, select) {
            const row = select.closest('tr');
            const otherInput = row.querySelector('.other-consumable');
            otherInput.style.display = select.value === 'سایر اقلام' ? 'block' : 'none';
        }

        function updateConsumablesTotal(equipmentId) {
            let total = 0;
            const rows = document.querySelectorAll(`#consumables-tbody-${equipmentId} tr`);
            
            rows.forEach((row, index) => {
                const quantity = parseInt(row.querySelector('.consumable-quantity').value) || 0;
                total += quantity;
                row.querySelector('td:first-child').textContent = index + 1;
            });
            
            document.getElementById(`consumables-total-${equipmentId}`).textContent = total;
        }

        function removeActivityRow(equipmentId, button) {
            const row = button.closest('tr');
            row.remove();
            updateActivityTotal(equipmentId);
        }

        function removeConsumableRow(equipmentId, button) {
            const row = button.closest('tr');
            row.remove();
            updateConsumablesTotal(equipmentId);
        }

        function saveActivitiesTab(equipmentId) {
            const equipment = equipments.find(e => e.id === equipmentId);
            
            if (equipment) {
                const activities = [];
                const rows = document.querySelectorAll(`#activities-tbody-${equipmentId} tr`);
                
                rows.forEach(row => {
                    const code = row.querySelector('.activity-code').value;
                    const selectedOption = row.querySelector('.activity-code').selectedOptions[0];
                    
                    if (code) {
                        activities.push({
                            code: code,
                            title: selectedOption?.dataset.title || '',
                            unit: selectedOption?.dataset.unit || '',
                            unitPrice: parseInt(selectedOption?.dataset.price) || 0,
                            quantity: parseInt(row.querySelector('.activity-quantity').value) || 0,
                            total: parseInt(row.querySelector('.activity-quantity').value) * (parseInt(selectedOption?.dataset.price) || 0)
                        });
                    }
                });
                
                const consumables = [];
                const consumableRows = document.querySelectorAll(`#consumables-tbody-${equipmentId} tr`);
                
                consumableRows.forEach(row => {
                    const name = row.querySelector('.consumable-name').value;
                    const otherName = row.querySelector('.other-consumable').value;
                    const finalName = name === 'سایر اقلام' ? otherName : name;
                    const unit = row.querySelector('.consumable-unit').value;
                    
                    if (finalName) {
                        consumables.push({
                            name: finalName,
                            quantity: parseInt(row.querySelector('.consumable-quantity').value) || 0,
                            unit: unit || 'عدد',
                            description: row.querySelector('.consumable-description').value || '',
                            otherName: name === 'سایر اقلام' ? otherName : ''
                        });
                    }
                });
                
                equipment.activitiesData = activities;
                equipment.consumablesData = consumables;
                markTabAsValidated(equipmentId, 'activities');
                triggerAutoSave();
                
                alert('فعالیت‌ها و مصارف با موفقیت ذخیره شد.');
            }
        }

        // Photos Functions
        function handlePhotoUpload(equipmentId, input) {
            const files = input.files;
            const previewContainer = document.getElementById(`photos-preview-${equipmentId}`);
            
            Array.from(files).forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const col = document.createElement('div');
                        col.className = 'col-md-4 mb-3';
                        col.innerHTML = `
                            <div class="photo-preview">
                                <button type="button" class="btn btn-sm btn-danger photo-remove" onclick="removePhoto(this, '${equipmentId}')">
                                    <i class="bi bi-x"></i>
                                </button>
                                <img src="${e.target.result}" class="img-fluid rounded" alt="تصویر ${index + 1}">
                                <div class="mt-2">
                                    <input type="text" class="form-control form-control-sm mb-1 scan-code" 
                                           placeholder="کد عکس">
                                    <textarea class="form-control form-control-sm photo-description" rows="2" 
                                              placeholder="توضیحات"></textarea>
                                </div>
                            </div>
                        `;
                        previewContainer.appendChild(col);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        function removePhoto(button, equipmentId) {
            const preview = button.closest('.photo-preview');
            preview.remove();
        }

        function savePhotosTab(equipmentId) {
            const equipment = equipments.find(e => e.id === equipmentId);
            const photosData = getPhotosData(equipmentId);
            
            if (equipment) {
                equipment.photosData = photosData;
                markTabAsValidated(equipmentId, 'photos');
                triggerAutoSave();
                
                alert('تصاویر با موفقیت ذخیره شد.');
            }
        }

        function getPhotosData(equipmentId) {
            const photos = [];
            const previewContainer = document.getElementById(`photos-preview-${equipmentId}`);
            const photoElements = previewContainer.querySelectorAll('.photo-preview');
            
            photoElements.forEach(photoEl => {
                const scanCode = photoEl.querySelector('.scan-code')?.value || '';
                const description = photoEl.querySelector('.photo-description')?.value || '';
                const imgSrc = photoEl.querySelector('img').src;
                
                photos.push({
                    scanCode: scanCode,
                    description: description,
                    dataUrl: imgSrc
                });
            });
            
            return photos;
        }

        function markTabAsValidated(equipmentId, tabId) {
            const equipment = equipments.find(e => e.id === equipmentId);
            if (equipment) {
                if (!equipment.tabsValidated) {
                    equipment.tabsValidated = {};
                }
                equipment.tabsValidated[tabId] = true;
                
                const tabElement = document.getElementById(`tab-${tabId}-${equipmentId}`);
                if (tabElement && !tabElement.classList.contains('tab-validated')) {
                    tabElement.classList.add('tab-validated');
                }
            }
        }

        // Summary and final report
        function updateSummary() {
            document.getElementById('summary-equipment-count').textContent = formatNumber(equipments.length);
            document.getElementById('summary-coefficient').textContent = 
                document.getElementById('contract-coefficient').value || '2.35';
            
            let totalActivityCount = 0;
            let totalCost = 0;
            let activitiesSummary = {};
            let consumablesSummary = {};
            
            equipments.forEach(equipment => {
                if (equipment.activitiesData) {
                    equipment.activitiesData.forEach(activity => {
                        totalActivityCount += activity.quantity;
                        totalCost += activity.total;
                        
                        if (!activitiesSummary[activity.code]) {
                            activitiesSummary[activity.code] = {
                                title: activity.title,
                                unit: activity.unit,
                                unitPrice: activity.unitPrice,
                                totalQuantity: 0,
                                totalAmount: 0
                            };
                        }
                        activitiesSummary[activity.code].totalQuantity += activity.quantity;
                        activitiesSummary[activity.code].totalAmount += activity.total;
                    });
                }
                
                if (equipment.consumablesData) {
                    equipment.consumablesData.forEach(consumable => {
                        if (!consumablesSummary[consumable.name]) {
                            consumablesSummary[consumable.name] = {
                                totalQuantity: 0,
                                unit: consumable.unit || 'عدد',
                                descriptions: []
                            };
                        }
                        consumablesSummary[consumable.name].totalQuantity += consumable.quantity;
                        if (consumable.description) {
                            consumablesSummary[consumable.name].descriptions.push(consumable.description);
                        }
                    });
                }
            });
            
            document.getElementById('summary-activity-count').textContent = formatNumber(totalActivityCount);
            document.getElementById('summary-total-cost').textContent = formatNumber(totalCost) + ' ریال';
            
            const coefficient = parseFloat(document.getElementById('contract-coefficient').value) || 2.35;
            const finalCost = totalCost * coefficient;
            document.getElementById('summary-final-cost').textContent = formatNumber(finalCost) + ' ریال';
            
            updateActivitiesSummaryTable(activitiesSummary, coefficient);
            updateConsumablesSummaryTable(consumablesSummary);
            updateEquipmentDetailsSummary();
        }

        function updateActivitiesSummaryTable(activitiesSummary, coefficient) {
            const tbody = document.getElementById('activities-summary-body');
            tbody.innerHTML = '';
            
            let totalAmount = 0;
            
            Object.entries(activitiesSummary).forEach(([code, data]) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${code}</td>
                    <td>${data.title}</td>
                    <td class="persian-numbers">${formatNumber(data.totalQuantity)}</td>
                    <td class="persian-numbers">${formatNumber(data.unitPrice)} ریال</td>
                    <td class="persian-numbers">${formatNumber(data.totalAmount)} ریال</td>
                    <td class="persian-numbers">${formatNumber(data.totalAmount * coefficient)} ریال</td>
                `;
                tbody.appendChild(row);
                totalAmount += data.totalAmount;
            });
            
            document.getElementById('final-activities-total').textContent = formatNumber(totalAmount) + ' ریال';
            document.getElementById('final-activities-total-coefficient').textContent = formatNumber(totalAmount * coefficient) + ' ریال';
        }

        function updateConsumablesSummaryTable(consumablesSummary) {
            const tbody = document.getElementById('consumables-summary-body');
            tbody.innerHTML = '';
            
            let totalQuantity = 0;
            
            Object.entries(consumablesSummary).forEach(([name, data]) => {
                const row = document.createElement('tr');
                const description = data.descriptions.join('، ');
                row.innerHTML = `
                    <td>${name}</td>
                    <td class="persian-numbers">${formatNumber(data.totalQuantity)}</td>
                    <td>${data.unit}</td>
                    <td>${description}</td>
                `;
                tbody.appendChild(row);
                totalQuantity += data.totalQuantity;
            });
            
            document.getElementById('final-consumables-total').textContent = formatNumber(totalQuantity);
        }

        function updateEquipmentDetailsSummary() {
            const container = document.getElementById('equipment-details-summary');
            let html = '<div class="row">';
            
            equipments.forEach((equipment, index) => {
                const hasBrands = equipmentWithBrands.includes(equipment.equipmentType);
                const switchBrandDisplay = hasBrands ? 
                    (equipment.switchBrand === 'سایر' ? equipment.otherSwitchBrand : equipment.switchBrand) : 
                    'بدون برند';
                    
                const feedersText = equipment.feeders && equipment.feeders.length > 0 
                    ? equipment.feeders.map(f => `${f.post} (${f.feeder})`).join('، ')
                    : 'ثبت نشده';
                    
                html += `
                    <div class="col-md-6 mb-3">
                        <div class="card equipment-summary-row">
>>>>>>> 524cace2901cfcda4f022b89d64c22cc653187c1
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
<<<<<<< HEAD
=======
                `;
            });
            
            html += '</div>';
            container.innerHTML = html;
        }

function generateExcelReport() {



            if (!checkLibraries()) {
                alert('کتابخانه‌های مورد نیاز بارگذاری نشده‌اند. لطفا صفحه را رفرش کنید.');
                return;
            }



    try {
        // Create workbook
        const wb = XLSX.utils.book_new();
        const inspectionDate = document.getElementById('inspection-date').value;
        const contractor = document.getElementById('contractor').value;
        const coefficient = parseFloat(document.getElementById('contract-coefficient').value) || 2.35;
        const cityDepartment = equipments.length > 0 ? equipments[0].departmentData?.department || 'ثبت نشده' : 'ثبت نشده';
        
        // Sheet 1: Daily Summary
        const dailyData = [
            ['شرکت توزیع نیروی برق استان یزد', '', '', '', '', '', ''],
            ['سیستم مدیریت بازدید تجهیزات اتوماسیون', '', '', '', '', '', ''],
            ['فرم شماره: F-20324-01', '', '', '', '', '', ''],
            ['', '', '', '', '', '', ''],
            ['خلاصه روزانه', '', '', '', '', '', ''],
            ['تاریخ بازدید', inspectionDate, '', '', '', '', ''],
            ['امور شهرستان', cityDepartment, '', '', '', '', ''],
            ['پیمانکار', contractor, '', '', '', '', ''],
            ['ضریب قرارداد', coefficient, '', '', '', '', ''],
            ['', '', '', '', '', '', ''],
            ['آمار کلی', '', '', '', '', '', ''],
            ['تعداد تجهیزات', equipments.length, '', '', '', '', ''],
            ['کل فعالیت‌ها', document.getElementById('summary-activity-count').textContent, '', '', '', '', ''],
            ['هزینه بدون ضریب', document.getElementById('summary-total-cost').textContent, '', '', '', '', ''],
            ['هزینه نهایی', document.getElementById('summary-final-cost').textContent, '', '', '', '', '']
        ];
        
        const ws1 = XLSX.utils.aoa_to_sheet(dailyData);
        XLSX.utils.book_append_sheet(wb, ws1, "خلاصه روزانه");
        
        // Sheet 2-...: Individual Equipment Sheets
        equipments.forEach((equipment, eqIndex) => {
            const sheetData = [];
            
            // Header
            sheetData.push(['جزئیات تجهیز ' + (eqIndex + 1)]);
            sheetData.push(['نوع تجهیز', equipment.equipmentType || 'ثبت نشده']);
            sheetData.push(['کد اسکادا', equipment.scadaCode || 'ثبت نشده']);
            
            // Brand info
            if (equipmentWithBrands.includes(equipment.equipmentType)) {
                const switchBrandDisplay = equipment.switchBrand === 'سایر' ? 
                    equipment.otherSwitchBrand || 'ثبت نشده' : 
                    equipment.switchBrand || 'ثبت نشده';
                sheetData.push(['برند کلید', switchBrandDisplay]);
            }
            
            sheetData.push(['زمان فعالیت', `${equipment.startTime || 'ثبت نشده'} - ${equipment.endTime || 'ثبت نشده'}`]);
            
            // Department Data
            if (equipment.departmentData) {
                sheetData.push(['امور شهرستان', equipment.departmentData.department || 'ثبت نشده']);
                sheetData.push(['GIS Code', equipment.departmentData.city || 'ثبت نشده']);
            }
            
            // Feeders
            if (equipment.feeders && equipment.feeders.length > 0) {
                sheetData.push([]);
                sheetData.push(['فیدرها']);
                equipment.feeders.forEach((feeder, idx) => {
                    sheetData.push([`فیدر ${idx + 1}`, `${feeder.post || 'ثبت نشده'} (${feeder.feeder || 'ثبت نشده'})`]);
                });
            }
            
            // Location Data
            if (equipment.locationData) {
                sheetData.push([]);
                sheetData.push(['اطلاعات موقعیت جغرافیایی']);
                sheetData.push(['عرض جغرافیایی', equipment.locationData.latitude || 'ثبت نشده']);
                sheetData.push(['طول جغرافیایی', equipment.locationData.longitude || 'ثبت نشده']);
                sheetData.push(['آدرس نصب', equipment.locationData.address || 'ثبت نشده']);
                if (equipment.locationData.cabinetInitialHeight) {
                    sheetData.push(['ارتفاع اولیه تابلو', equipment.locationData.cabinetInitialHeight]);
                }
                if (equipment.locationData.cabinetFinalHeight) {
                    sheetData.push(['ارتفاع نهایی تابلو', equipment.locationData.cabinetFinalHeight]);
                }
            }
            
            // Communication Data
            if (equipment.communicationData) {
                sheetData.push([]);
                sheetData.push(['اطلاعات ارتباطی']);
                sheetData.push(['نوع سیم‌کارت', equipment.communicationData.simcardType || 'ثبت نشده']);
                sheetData.push(['شماره سیم‌کارت', equipment.communicationData.simcardNumber || 'ثبت نشده']);
                sheetData.push(['IP سیم‌کارت', equipment.communicationData.simcardIp || 'ثبت نشده']);
                sheetData.push(['وضعیت آنتن', equipment.communicationData.antennaStatus || 'ثبت نشده']);
                sheetData.push(['وضعیت سیگنال', equipment.communicationData.signalStatus || 'ثبت نشده']);
                sheetData.push(['تغذیه مودم', equipment.communicationData.modemPower || 'ثبت نشده']);
                sheetData.push(['قابلیت ریست', equipment.communicationData.resetPossible ? 'دارد' : 'ندارد']);
            }
            
            // Cell Specifications for two-way power supplies
            if (equipment.cellSpecs) {
                sheetData.push([]);
                sheetData.push(['مشخصات سلول‌ها']);
                if (equipment.cellSpecs.equipmentsNotes) {
                    sheetData.push(['توضیحات تجهیزات', equipment.cellSpecs.equipmentsNotes]);
                }
                if (equipment.cellSpecs.inputCellsCount) {
                    sheetData.push(['تعداد سلول‌های ورودی', equipment.cellSpecs.inputCellsCount]);
                }
                if (equipment.cellSpecs.outputCellsCount) {
                    sheetData.push(['تعداد سلول‌های خروجی', equipment.cellSpecs.outputCellsCount]);
                }
                if (equipment.cellSpecs.otherEquipmentsNotes) {
                    sheetData.push(['توضیحات سایر تجهیزات', equipment.cellSpecs.otherEquipmentsNotes]);
                }
            }
            
            // Checklist Data
            if (equipment.checklistData && equipment.checklistData.length > 0) {
                sheetData.push([]);
                sheetData.push(['چک‌لیست بازدید']);
                equipment.checklistData.forEach((item, idx) => {
                    sheetData.push([`${idx + 1}. ${item.item}`, item.status, item.description || '']);
                });
            }
            
            // Activities Data
            if (equipment.activitiesData && equipment.activitiesData.length > 0) {
                sheetData.push([]);
                sheetData.push(['فعالیت‌های فهرست بها']);
                sheetData.push(['ردیف', 'کد', 'عنوان', 'واحد', 'فی واحد', 'تعداد', 'مبلغ']);
                equipment.activitiesData.forEach((activity, idx) => {
                    sheetData.push([
                        idx + 1,
                        activity.code || '',
                        activity.title || '',
                        activity.unit || '',
                        formatNumber(activity.unitPrice || 0),
                        activity.quantity || 0,
                        formatNumber(activity.total || 0)
                    ]);
                });
            }
            
            // Consumables Data
            if (equipment.consumablesData && equipment.consumablesData.length > 0) {
                sheetData.push([]);
                sheetData.push(['اقلام مصرفی']);
                sheetData.push(['ردیف', 'نام', 'تعداد', 'توضیحات']);
                equipment.consumablesData.forEach((consumable, idx) => {
                    sheetData.push([
                        idx + 1,
                        consumable.name || '',
                        consumable.quantity || 0,
                        consumable.description || ''
                    ]);
                });
            }
            
            const ws = XLSX.utils.aoa_to_sheet(sheetData);
            XLSX.utils.book_append_sheet(wb, ws, `تجهیز ${eqIndex + 1}`);
        });
        
        // Last Sheet: Daily Financial Summary (صورت وضعیت کلی روز)
        const financialData = [
            ['صورت وضعیت کلی روز'],
            ['تاریخ', inspectionDate],
            ['امور شهرستان', cityDepartment],
            ['پیمانکار', contractor],
            ['ضریب قرارداد', coefficient],
            ['', '', '', '', '', '', ''],
            ['خلاصه مالی روز', '', '', '', '', '', ''],
            ['تعداد تجهیزات', equipments.length],
            ['کل فعالیت‌ها', document.getElementById('summary-activity-count').textContent],
            ['هزینه بدون ضریب', document.getElementById('summary-total-cost').textContent],
            ['هزینه نهایی', document.getElementById('summary-final-cost').textContent],
            ['', '', '', '', '', '', ''],
            ['جمع‌بندی فعالیت‌های فهرست بها', '', '', '', '', '', ''],
            ['کد فهرست بها', 'عنوان فعالیت', 'تعداد کل', 'فی واحد (ریال)', 'مبلغ کل (بدون ضریب)', 'مبلغ با ضریب']
        ];
        
        // Aggregate all activities
        const activitiesSummary = {};
        equipments.forEach(equipment => {
            if (equipment.activitiesData) {
                equipment.activitiesData.forEach(activity => {
                    if (!activitiesSummary[activity.code]) {
                        activitiesSummary[activity.code] = {
                            title: activity.title,
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
        
        let totalDailyAmount = 0;
        Object.entries(activitiesSummary).forEach(([code, data]) => {
            financialData.push([
                code,
                data.title || '',
                data.totalQuantity || 0,
                formatNumber(data.unitPrice || 0),
                formatNumber(data.totalAmount || 0) + ' ریال',
                formatNumber((data.totalAmount || 0) * coefficient) + ' ریال'
            ]);
            totalDailyAmount += (data.totalAmount || 0);
        });
        
        financialData.push(['', '', '', '', '', '']);
        financialData.push(['جمع کل فعالیت‌ها', '', '', '', 
            formatNumber(totalDailyAmount) + ' ریال', 
            formatNumber(totalDailyAmount * coefficient) + ' ریال']);
        
        // Consumables Summary
        financialData.push(['', '', '', '', '', '', '']);
        financialData.push(['جمع‌بندی اقلام مصرفی روز', '', '', '', '', '', '']);
        financialData.push(['نام قلم مصرفی', 'تعداد کل', 'توضیحات']);
        
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
        
        Object.entries(consumablesSummary).forEach(([name, data]) => {
            financialData.push([name, data.totalQuantity, data.descriptions.join('، ') || '-']);
        });
        
        const wsFinancial = XLSX.utils.aoa_to_sheet(financialData);
        XLSX.utils.book_append_sheet(wb, wsFinancial, "صورت وضعیت روز");
        
        // Generate Excel file
        const filename = `بازدید_اتوماسیون_${inspectionDate}_${Date.now()}.xlsx`;
        XLSX.writeFile(wb, filename);
        
        alert('فایل Excel با موفقیت ایجاد شد.');
        
    } catch (error) {
        console.error('Error generating Excel:', error);
        alert('خطا در ایجاد فایل Excel: ' + error.message);

    }
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
>>>>>>> 524cace2901cfcda4f022b89d64c22cc653187c1
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

<<<<<<< HEAD
=======



<<<<<<< HEAD
// تابع ثبت نهایی بازدید - نسخه نهایی با JSON.stringify برای همه فیلدها
async function submitFinalInspection() {
    try {
        if (!confirm('آیا از ثبت نهایی این بازدید اطمینان دارید؟')) {
            return;
        }

=======
// تابع ثبت نهایی بازدید در دیتابیس - نسخه نهایی با تبدیل تاریخ
async function submitFinalInspection() {
    try {
        // نمایش confirmation
        if (!confirm('آیا از ثبت نهایی این بازدید اطمینان دارید؟ پس از ثبت، امکان ویرایش وجود نخواهد داشت.')) {
            return;
        }

        // بررسی وجود حداقل یک تجهیز
>>>>>>> 62fd3f93d37034b824fcce2a25a722d1470dbcc9
        if (equipments.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'خطا',
                text: 'حداقل یک تجهیز باید اضافه کنید'
            });
            return;
        }

<<<<<<< HEAD
=======
        // نمایش لودینگ
>>>>>>> 62fd3f93d37034b824fcce2a25a722d1470dbcc9
        Swal.fire({
            title: 'در حال ثبت اطلاعات...',
            text: 'لطفا صبر کنید',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

<<<<<<< HEAD
=======
        // دریافت توکن از localStorage
>>>>>>> 62fd3f93d37034b824fcce2a25a722d1470dbcc9
        const token = localStorage.getItem('auth_token');
        
        if (!token) {
            Swal.fire({
                icon: 'error',
                title: 'خطا',
                text: 'لطفا ابتدا وارد سیستم شوید'
            });
            return;
        }

<<<<<<< HEAD
        // تبدیل تاریخ شمسی به میلادی
        const jalaliDate = document.getElementById('inspection-date').value;
        
        function convertJalaliToGregorian(jalaliDateStr) {
            try {
                if (typeof window.moment !== 'undefined') {
=======
        // دریافت تاریخ شمسی
        const jalaliDate = document.getElementById('inspection-date').value;
        console.log('Jalali date:', jalaliDate);

        // تبدیل تاریخ شمسی به میلادی با استفاده از moment-jalaali
        function convertJalaliToGregorian(jalaliDateStr) {
            try {
                // اگر کتابخانه moment-jalaali وجود دارد
                if (typeof window.moment !== 'undefined') {
                    // حذف اعداد فارسی و تبدیل به اعداد انگلیسی
>>>>>>> 62fd3f93d37034b824fcce2a25a722d1470dbcc9
                    const persianNumbers = {
                        '۰': '0', '۱': '1', '۲': '2', '۳': '3', '۴': '4',
                        '۵': '5', '۶': '6', '۷': '7', '۸': '8', '۹': '9'
                    };
                    
                    let englishDate = jalaliDateStr;
                    for (let [persian, english] of Object.entries(persianNumbers)) {
                        englishDate = englishDate.replace(new RegExp(persian, 'g'), english);
                    }
                    
<<<<<<< HEAD
                    const m = window.moment(englishDate, 'jYYYY/jMM/jDD');
                    if (m.isValid()) {
                        return m.format('YYYY-MM-DD');
=======
                    console.log('English digits date:', englishDate);
                    
                    // تبدیل به میلادی
                    const m = window.moment(englishDate, 'jYYYY/jMM/jDD');
                    if (m.isValid()) {
                        const gregorianDate = m.format('YYYY-MM-DD');
                        console.log('Converted to Gregorian:', gregorianDate);
                        return gregorianDate;
>>>>>>> 62fd3f93d37034b824fcce2a25a722d1470dbcc9
                    }
                }
            } catch (e) {
                console.error('Date conversion error:', e);
            }
<<<<<<< HEAD
=======
            
            // اگر تبدیل ناموفق بود، تاریخ را بدون تغییر برگردان
>>>>>>> 62fd3f93d37034b824fcce2a25a722d1470dbcc9
            return jalaliDateStr;
        }

        const gregorianDate = convertJalaliToGregorian(jalaliDate);
<<<<<<< HEAD

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
=======
        console.log('Final date to send:', gregorianDate);

        // آماده‌سازی داده‌ها - تاریخ میلادی ارسال می‌شود
        const inspectionData = {
            inspection_date: gregorianDate, // تاریخ میلادی
            daily_start_time: document.getElementById('daily-start-time').value,
            daily_end_time: document.getElementById('daily-end-time').value,
            contractor: document.getElementById('contractor').value,
            contract_coefficient: parseFloat(document.getElementById('contract-coefficient').value) || 2.35,
            contract_number: document.getElementById('contract-number').value,
            whatsapp_number: document.getElementById('whatsapp-number').value,
            total_equipment_count: equipments.length,
            total_activities_count: parseInt(document.getElementById('summary-activity-count').textContent.replace(/,/g, '')) || 0,
            total_cost_without_coefficient: parseFloat(document.getElementById('summary-total-cost').textContent.replace(/[^0-9]/g, '')) || 0,
            total_cost_with_coefficient: parseFloat(document.getElementById('summary-final-cost').textContent.replace(/[^0-9]/g, '')) || 0,
            
            // اطلاعات تجهیزات
            equipments: equipments.map(equipment => ({
                equipment_type: equipment.equipmentType,
                scada_code: equipment.scadaCode,
                installation_type: equipment.installationType || '',
                switch_brand: equipment.switchBrand || '',
                modem_brand: equipment.modemBrand || '',
                rtu_brand: equipment.rtuBrand || '',
                other_switch_brand: equipment.otherSwitchBrand || '',
                other_modem_brand: equipment.otherModemBrand || '',
                other_rtu_brand: equipment.otherRTUBrand || '',
                start_time: equipment.startTime || '',
                end_time: equipment.endTime || '',
                
                // اطلاعات امور
                department_data: equipment.departmentData || {},
                
                // فیدرها
                feeders: equipment.feeders || [],
                
                // اطلاعات موقعیت
                location_data: equipment.locationData || {},
                
                // اطلاعات ارتباطی
                communication_data: equipment.communicationData || {},
                
                // چک‌لیست
                checklist_data: equipment.checklistData || [],
                
                // فعالیت‌ها
                activities_data: equipment.activitiesData || [],
                
                // مصارف
                consumables_data: equipment.consumablesData || [],
                
                // سلول‌ها (برای پست‌ها)
                cell_specs: equipment.cellSpecs || {},
                
                // عکس‌ها
                photos_data: equipment.photosData || [],
                
                // وضعیت تب‌ها
                tabs_validated: equipment.tabsValidated || {}
            }))
>>>>>>> 62fd3f93d37034b824fcce2a25a722d1470dbcc9
        };

        console.log('Sending inspection data:', JSON.stringify(inspectionData, null, 2));

<<<<<<< HEAD
=======
        // ارسال به سرور
>>>>>>> 62fd3f93d37034b824fcce2a25a722d1470dbcc9
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
<<<<<<< HEAD
            throw new Error('پاسخ سرور نامعتبر است');
        }

        if (!response.ok) {
=======
            throw new Error('پاسخ سرور نامعتبر است: ' + responseText.substring(0, 100));
        }

        if (!response.ok) {
            // اگر خطای اعتبارسنجی باشد (422)
>>>>>>> 62fd3f93d37034b824fcce2a25a722d1470dbcc9
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
<<<<<<< HEAD
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
=======
        Swal.fire({
            icon: 'success',
            title: 'ثبت با موفقیت انجام شد',
            text: `بازدید با کد پیگیری ${result.inspection_code || result.id} ثبت شد.`,
            confirmButtonText: 'باشه'
        }).then(() => {
            // پاک کردن localStorage
            localStorage.removeItem('automationInspectionDraft');
            
            // پرس و جو برای شروع بازدید جدید
>>>>>>> 62fd3f93d37034b824fcce2a25a722d1470dbcc9
            if (confirm('آیا می‌خواهید یک بازدید جدید شروع کنید؟')) {
                clearForm();
            }
        });

<<<<<<< HEAD
=======
        // لاگ موفقیت در کنسول
>>>>>>> 62fd3f93d37034b824fcce2a25a722d1470dbcc9
        console.log('Inspection saved successfully:', result);

    } catch (error) {
        console.error('Error submitting inspection:', error);
        
        Swal.fire({
            icon: 'error',
<<<<<<< HEAD
            title: '❌ خطا در ثبت اطلاعات',
            text: error.message || 'خطایی رخ داده است',
=======
            title: 'خطا در ثبت اطلاعات',
            text: error.message || 'خطایی رخ داده است. لطفا دوباره تلاش کنید.',
>>>>>>> 62fd3f93d37034b824fcce2a25a722d1470dbcc9
            confirmButtonText: 'باشه'
        });
    }
}
<<<<<<< HEAD



=======
>>>>>>> 62fd3f93d37034b824fcce2a25a722d1470dbcc9
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
>>>>>>> 524cace2901cfcda4f022b89d64c22cc653187c1
</body>
</html>