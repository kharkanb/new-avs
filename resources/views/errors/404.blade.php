<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صفحه یافت نشد</title>
<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/bootstrap-icons.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/Vazirmatn-font-face.css') }}" rel="stylesheet"
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
            color: #f39c12;
            margin-bottom: 20px;
        }
        .error-title {
            font-size: 2rem;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 15px;
        }
        .error-message {
            color: #7f8c8d;
            margin-bottom: 20px;
        }
        .btn-back {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 12px 35px;
            border-radius: 10px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-back:hover {
            background: linear-gradient(135deg, #2980b9, #21618c);
            color: white;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-icon">
            <i class="bi bi-compass"></i>
        </div>
        <h1 class="error-title">🔍 صفحه یافت نشد</h1>
        <p class="error-message">
            صفحه‌ای که به دنبال آن هستید وجود ندارد.
        </p>
        <a href="{{ url('/dashboard') }}" class="btn-back">
            <i class="bi bi-arrow-right"></i> بازگشت به داشبورد
        </a>
    </div>
</body>
</html>