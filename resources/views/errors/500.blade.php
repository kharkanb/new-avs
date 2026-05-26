<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>خطای سرور</title>
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
            color: #7f8c8d;
            margin-bottom: 20px;
            font-size: 1rem;
        }
        .error-suggestion {
            background: #f8f9fc;
            padding: 15px;
            border-radius: 15px;
            margin: 20px 0;
            font-size: 0.9rem;
            text-align: right;
        }
        .btn-back {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 12px 35px;
            border-radius: 10px;
            text-decoration: none;
            display: inline-block;
            margin: 5px;
        }
        .btn-back:hover {
            background: linear-gradient(135deg, #2980b9, #21618c);
            color: white;
        }
        .btn-refresh {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            color: white;
            padding: 12px 35px;
            border-radius: 10px;
            text-decoration: none;
            display: inline-block;
            margin: 5px;
        }
        .btn-refresh:hover {
            background: linear-gradient(135deg, #e67e22, #d35400);
            color: white;
        }
        .support-info {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #e9ecef;
            font-size: 0.8rem;
            color: #95a5a6;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-icon">
            <i class="bi bi-bug"></i>
        </div>
        <h1 class="error-title">🐛 خطای سرور</h1>
        <p class="error-message">
            متأسفانه خطایی در سرور رخ داده است.
        </p>
        <div class="error-suggestion">
            <i class="bi bi-lightbulb"></i>
            <strong>راهکارهای پیشنهادی:</strong>
            <ul class="mt-2 text-end" style="list-style-position: inside;">
                <li>صفحه را refresh کنید</li>
                <li>چند دقیقه دیگر مجدد تلاش کنید</li>
                <li>اگر مشکل ادامه داشت، با پشتیبانی تماس بگیرید</li>
            </ul>
        </div>
        <div>
            <a href="javascript:location.reload()" class="btn-refresh">
                <i class="bi bi-arrow-repeat"></i> تلاش مجدد
            </a>
            <a href="{{ url('/dashboard') }}" class="btn-back">
                <i class="bi bi-arrow-right"></i> بازگشت به داشبورد
            </a>
        </div>
        <div class="support-info">
            <i class="bi bi-headset"></i> در صورت تکرار خطا، با پشتیبانی فنی تماس بگیرید: ۷-۳۱۶۷۲۰۲۶-۰۳۵
        </div>
    </div>
</body>
</html>