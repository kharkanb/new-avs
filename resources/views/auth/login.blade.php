<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود | سیستم مدیریت بازدید تجهیزات</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet">
    
    <style>
        * { font-family: 'Vazirmatn', sans-serif; }
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-card {
            background: white;
            border-radius: 30px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
            max-width: 450px;
            width: 100%;
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #2c3e50, #1e2b37);
            padding: 35px 25px;
            text-align: center;
            color: white;
        }
        .login-header i { font-size: 60px; color: #3498db; margin-bottom: 15px; }
        .login-header h2 { font-size: 1.5rem; font-weight: 600; margin-bottom: 5px; }
        .login-header p { font-size: 0.85rem; opacity: 0.8; }
        .login-body { padding: 35px 30px; }
        .form-group { margin-bottom: 25px; }
        .form-group label { font-weight: 500; color: #2c3e50; margin-bottom: 8px; display: block; }
        .form-group label i { margin-left: 8px; color: #3498db; }
        .form-control {
            border-radius: 12px;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52,152,219,0.1);
        }
        .btn-login {
            background: linear-gradient(135deg, #3498db, #2980b9);
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-size: 1rem;
            font-weight: 600;
            width: 100%;
            color: white;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(52,152,219,0.4);
        }
        .btn-login i { margin-left: 8px; }
        .form-footer { text-align: center; margin-top: 25px; padding-top: 20px; border-top: 1px solid #e9ecef; }
        .form-footer a { color: #3498db; text-decoration: none; font-size: 0.85rem; }
        .form-footer a:hover { text-decoration: underline; }
        .alert-custom { border-radius: 12px; padding: 12px 15px; margin-bottom: 20px; border: none; }
        .company-info { text-align: center; margin-top: 20px; color: rgba(255,255,255,0.7); font-size: 0.8rem; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <i class="bi bi-grid-3x3-gap-fill"></i>
            <h2>سیستم بازدید تجهیزات</h2>
            <p>شرکت توزیع نیروی برق استان یزد</p>
        </div>
        
        <div class="login-body">
            @if ($errors->any())
                <div class="alert alert-danger alert-custom">
                    <i class="bi bi-exclamation-triangle-fill"></i> اطلاعات وارد شده صحیح نمی‌باشد.
                </div>
            @endif
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="form-group">
                    <label><i class="bi bi-envelope"></i> ایمیل</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="admin@example.com">
                </div>
                
                <div class="form-group">
                    <label><i class="bi bi-lock"></i> رمز عبور</label>
                    <input type="password" name="password" class="form-control" required placeholder="********">
                </div>
                
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">مرا به خاطر بسپار</label>
                    </div>
                </div>
                
                <button type="submit" class="btn-login">
                    <i class="bi bi-box-arrow-in-left"></i> ورود به سیستم
                </button>
                
                <div class="form-footer">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"><i class="bi bi-key"></i> رمز عبور خود را فراموش کرده‌اید؟</a>
                    @endif
                </div>
            </form>
        </div>
    </div>
    
    <div class="company-info">
        <i class="bi bi-building"></i> شرکت توزیع نیروی برق استان یزد | <i class="bi bi-file-text"></i> نسخه 2.0
    </div>
</body>
</html>