<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>دسترسی غیرمجاز</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/Vazirmatn-font-face.css') }}" rel="stylesheet">
    <style>
        * { font-family: 'Vazirmatn', sans-serif; }
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-card {
            background: white;
            border-radius: 30px;
            padding: 50px;
            text-align: center;
            max-width: 500px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        .error-icon {
            font-size: 80px;
            color: #e74c3c;
            margin-bottom: 20px;
        }
        .error-title {
            font-size: 2rem;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 15px;
        }
        .error-message {
            font-size: 1.2rem;
            color: #7f8c8d;
            margin-bottom: 20px;
        }
        .role-box {
            background: #f8f9fc;
            padding: 15px 20px;
            border-radius: 15px;
            margin: 20px 0;
            font-size: 1rem;
            border-right: 4px solid #e74c3c;
            text-align: right;
        }
        .role-box.green {
            border-right-color: #27ae60;
        }
        .role-label {
            color: #2c3e50;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .role-value {
            color: #e74c3c;
            font-weight: bold;
            font-size: 1.2rem;
        }
        .btn-back {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 12px 35px;
            border-radius: 10px;
            text-decoration: none;
            display: inline-block;
            font-size: 1rem;
            margin-top: 10px;
        }
        .btn-back:hover {
            background: linear-gradient(135deg, #2980b9, #21618c);
            color: white;
        }
        .required-roles {
            color: #27ae60;
            font-weight: bold;
        }
        .contact-text {
            color: #7f8c8d;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    @php
        // تابع تبدیل نقش به فارسی
        function getPersianRole($role) {
            switch($role) {
                case 'admin': return 'مدیر سیستم';
                case 'supervisor': return 'ناظر';
                case 'user': return 'کاربر عادی';
                default: return $role ?? 'نامشخص';
            }
        }
    @endphp

    <div class="error-card">
        <div class="error-icon">
            <i class="bi bi-shield-exclamation"></i>
        </div>
        
        <h1 class="error-title">⚠️ دسترسی غیرمجاز</h1>
        
        <p class="error-message">
            شما به این بخش دسترسی ندارید.
        </p>

        <div class="role-box">
            <div class="role-label">
                <i class="bi bi-person-badge"></i> نقش فعلی شما:
            </div>
            <div class="role-value">
                {{ getPersianRole(auth()->user()->role ?? 'نامشخص') }}
            </div>
        </div>

        <div class="role-box green">
            <div class="role-label">
                <i class="bi bi-info-circle"></i> نقش‌های مورد نیاز:
            </div>
            <div class="required-roles">
                مدیر سیستم / ناظر
            </div>
        </div>

        <div class="role-box green">
            <div class="role-label">
                <i class="bi bi-telephone"></i> راهنمایی:
            </div>
            <div class="contact-text">
                لطفاً با مدیر سیستم تماس بگیرید.
            </div>
        </div>

        <a href="{{ url('/dashboard') }}" class="btn-back">
            <i class="bi bi-arrow-right"></i> بازگشت به داشبورد
        </a>
    </div>
</body>
</html>