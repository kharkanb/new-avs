<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لینک‌های سیستم | بازدید تجهیزات</title>
    
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/Vazirmatn-font-face.css') }}" rel="stylesheet">

    <!-- PWA Meta Tags -->
    <link rel="manifest" href="/manifest.json">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="بازدید تجهیزات">
    <link rel="apple-touch-icon" href="/icons/icon-152x152.png">
    <meta name="msapplication-TileImage" content="/icons/icon-144x144.png">
    <meta name="msapplication-TileColor" content="#2c3e50">
    <meta name="theme-color" content="#2c3e50">
    
    <style>
        * { font-family: 'Vazirmatn', sans-serif; }
        body { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem;
        }
        .links-container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f0f0f0;
        }
        .header h1 {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .header p {
            color: #7f8c8d;
        }
        .link-card {
            background: #f8f9fc;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s;
            border: 1px solid #e9ecef;
        }
        .link-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-color: #3498db;
        }
        .link-path {
            font-family: monospace;
            background: #e9ecef;
            padding: 0.2rem 0.5rem;
            border-radius: 5px;
            font-size: 0.85rem;
            color: #2c3e50;
        }
        .link-name {
            color: #3498db;
            font-weight: 600;
            font-size: 0.85rem;
        }
        .link-desc {
            color: #7f8c8d;
            font-size: 0.85rem;
        }
        .badge-role {
            background: #e8f4fd;
            color: #3498db;
            padding: 0.2rem 0.8rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        .badge-role.admin { background: #fee2e2; color: #dc3545; }
        .badge-role.supervisor { background: #fff3cd; color: #856404; }
        .btn-copy {
            background: none;
            border: 1px solid #3498db;
            color: #3498db;
            border-radius: 5px;
            padding: 0.2rem 0.8rem;
            font-size: 0.75rem;
            transition: all 0.3s;
        }
        .btn-copy:hover {
            background: #3498db;
            color: white;
        }
        .footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid #f0f0f0;
            color: #95a5a6;
        }
        .status-badge {
            background: #d4edda;
            color: #155724;
            padding: 0.2rem 0.8rem;
            border-radius: 20px;
            font-size: 0.75rem;
        }
        .quick-access {
            background: #f8f9fc;
            border-radius: 12px;
            padding: 1rem;
            margin-top: 1.5rem;
        }
        .quick-access h5 {
            font-size: 1rem;
            margin-bottom: 1rem;
        }
        .quick-access .btn-group-sm .btn {
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
        }
    </style>
</head>
<body>
    <div class="links-container">
        <!-- هدر -->
        <div class="header">
            <i class="bi bi-link-45deg" style="font-size: 3rem; color: #3498db;"></i>
            <h1>لینک‌های سیستم</h1>
            <p>مدیریت بازدید تجهیزات اتوماسیون - شرکت توزیع برق یزد</p>
            <div class="mt-3">
                <span class="status-badge">
                    <i class="bi bi-check-circle"></i> {{ count($links) }} لینک فعال
                </span>
            </div>
        </div>

        <!-- لیست لینک‌ها -->
        <div class="links-list">
            @forelse($links as $link)
            <div class="link-card">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                            <span class="link-path">{{ $link['url'] }}</span>
                            <span class="link-name">{{ $link['name'] }}</span>
                        </div>
                        <p class="link-desc mb-2">{{ $link['desc'] }}</p>
                        <div>
                            @if($link['role'] == 'all')
                                <span class="badge-role"><i class="bi bi-globe"></i> عمومی</span>
                            @elseif($link['role'] == 'auth')
                                <span class="badge-role"><i class="bi bi-person-check"></i> نیاز به لاگین</span>
                            @else
                                @foreach(explode(',', $link['role']) as $role)
                                    <span class="badge-role {{ trim($role) }}">
                                        <i class="bi bi-{{ trim($role) == 'admin' ? 'shield' : (trim($role) == 'supervisor' ? 'eye' : 'person') }}"></i>
                                        {{ trim($role) == 'admin' ? 'مدیر' : (trim($role) == 'supervisor' ? 'ناظر' : 'کاربر') }}
                                    </span>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <a href="{{ url($link['url']) }}" class="btn btn-primary btn-sm" target="_blank">
                            <i class="bi bi-box-arrow-up-right"></i> بازدید
                        </a>
                        <button class="btn btn-copy btn-sm" onclick="copyToClipboard(this, '{{ $link['url'] }}')">
                            <i class="bi bi-files"></i> کپی
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                <p class="mt-3">هیچ لینکی یافت نشد</p>
            </div>
            @endforelse
        </div>

        <!-- دسترسی سریع -->
        <div class="quick-access">
            <h5><i class="bi bi-speedometer2"></i> دسترسی سریع</h5>
            <div class="d-flex flex-wrap gap-2">
                <a href="/" class="btn btn-outline-secondary btn-sm">خانه</a>
                <a href="/inspection-form" class="btn btn-outline-primary btn-sm">فرم بازدید</a>
                <a href="/dashboard" class="btn btn-outline-success btn-sm">داشبورد</a>
                <a href="/dashboard/inspections" class="btn btn-outline-info btn-sm">لیست بازدیدها</a>
                <a href="/dashboard/reports" class="btn btn-outline-warning btn-sm">گزارش‌ها</a>
                <a href="/dashboard/users" class="btn btn-outline-danger btn-sm">کاربران</a>
            </div>
        </div>

        <!-- فوتر -->
        <div class="footer">
            <p class="mb-0">
                <i class="bi bi-calendar-check"></i> 
                آخرین به‌روزرسانی: {{ now()->format('Y/m/d - H:i') }}
            </p>
            <p class="mb-0 mt-2">
                <small>برای تست دسترسی‌ها، با کاربران مختلف لاگین کنید</small>
            </p>
        </div>
    </div>

    <!-- Toast برای کپی شدن -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="copyToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-check-circle"></i> لینک کپی شد!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script>
        function copyToClipboard(btn, url) {
            navigator.clipboard.writeText(window.location.origin + url).then(function() {
                // نمایش toast
                var toastEl = document.getElementById('copyToast');
                var toast = new bootstrap.Toast(toastEl);
                toast.show();
                
                // تغییر موقت رنگ دکمه
                btn.classList.add('btn-success');
                btn.classList.remove('btn-copy');
                setTimeout(() => {
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-copy');
                }, 1000);
            });
        }
    </script>

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js').then(function(registration) {
                    console.log('ServiceWorker registered with scope:', registration.scope);
                }, function(err) {
                    console.log('ServiceWorker registration failed:', err);
                });
            });
        }
    </script>
</body>
</html>