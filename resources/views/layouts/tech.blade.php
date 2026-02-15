<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'پنل کارشناسی') - سیستم مدیریت بازدید</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { font-family: 'Vazirmatn', sans-serif; background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background: #27ae60; color: white; }
        .sidebar a { color: #ecf0f1; text-decoration: none; padding: 10px 20px; display: block; }
        .sidebar a:hover { background: #2ecc71; }
        .sidebar .active { background: #e67e22; }
        .main-content { padding: 20px; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 p-0 sidebar">
                <div class="p-3 text-center">
                    <h5>پنل کارشناسی</h5>
                </div>
                <hr class="bg-light">
                <a href="{{ route('tech.dashboard') }}" class="{{ request()->routeIs('tech.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> داشبورد
                </a>
                <a href="{{ route('tech.inspections.index') }}" class="{{ request()->routeIs('tech.inspections*') ? 'active' : '' }}">
                    <i class="bi bi-clipboard-check"></i> بازدیدها
                </a>
                <a href="{{ route('tech.equipments.index') }}" class="{{ request()->routeIs('tech.equipments*') ? 'active' : '' }}">
                    <i class="bi bi-hdd-stack"></i> تجهیزات
                </a>
                <a href="{{ route('inspections.create') }}">
                    <i class="bi bi-plus-circle"></i> بازدید جدید
                </a>
                <hr class="bg-light">
                <a href="{{ route('dashboard') }}">
                    <i class="bi bi-arrow-right"></i> بازگشت به سایت
                </a>
            </div>
            <div class="col-md-10 main-content">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>