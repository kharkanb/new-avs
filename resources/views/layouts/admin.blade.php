<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'سیستم بازدید تجهیزات')</title>
    
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/Vazirmatn-font-face.css') }}" rel="stylesheet">
    
    <style>
        * { font-family: 'Vazirmatn', sans-serif; }
        body { background-color: #f8f9fc; }
        .main-content { margin-right: 280px; padding: 2rem; min-height: 100vh; }
        
        .sidebar {
            position: fixed;
            right: 0;
            top: 0;
            bottom: 0;
            width: 280px;
            background: linear-gradient(180deg, #2c3e50 0%, #1e2b37 100%);
            color: white;
            padding: 2rem 1rem;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }
        
        .nav { flex: 1; }
        
        .sidebar-header { 
            text-align: center; 
            padding-bottom: 1.5rem; 
            border-bottom: 1px solid rgba(255,255,255,0.1); 
        }
        .sidebar-header h3 { font-size: 1.2rem; margin-top: 0.5rem; }
        
        .nav-link { 
            color: rgba(255,255,255,0.8); 
            padding: 0.8rem 1rem; 
            margin: 0.3rem 0;
            border-radius: 10px; 
            display: flex; 
            align-items: center; 
            gap: 10px; 
            text-decoration: none; 
            transition: all 0.3s;
        }
        .nav-link:hover, .nav-link.active { 
            background: rgba(255,255,255,0.1); 
            color: white; 
        }
        
        .nav-dropdown { list-style: none; margin: 0; padding: 0; }
        .nav-dropdown > .nav-link { cursor: pointer; position: relative; }
        .nav-dropdown > .nav-link i.bi-chevron-down { 
            margin-right: auto; 
            transition: transform 0.3s; 
        }
        .nav-dropdown.open > .nav-link i.bi-chevron-down { transform: rotate(180deg); }
        .nav-submenu {
            display: none;
            padding-right: 1.5rem;
            margin-top: 0.3rem;
            margin-bottom: 0.3rem;
        }
        .nav-dropdown.open .nav-submenu { display: block; }
        .nav-submenu .nav-link {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            margin: 0.2rem 0;
        }
        
        .top-bar { 
            background: white; 
            padding: 1rem 2rem; 
            border-radius: 15px; 
            margin-bottom: 2rem;
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .page-title h4 { margin: 0; color: #2c3e50; }
        
        .user-avatar { 
            width: 45px; 
            height: 45px; 
            background: linear-gradient(135deg, #3498db, #2980b9);
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            color: white; 
            font-weight: bold; 
            font-size: 1.2rem;
        }
        .user-name { margin: 0; font-weight: bold; }
        
        .card { border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .btn { border-radius: 8px; padding: 0.5rem 1.2rem; }
        .btn-primary { 
            background: linear-gradient(135deg, #3498db, #2980b9); 
            border: none; 
        }
        .btn-primary:hover { background: linear-gradient(135deg, #2980b9, #21618c); }
        
        @media (max-width: 768px) { 
            .main-content { margin-right: 0; padding: 1rem; }
            .sidebar { transform: translateX(100%); transition: transform 0.3s; }
            .sidebar.open { transform: translateX(0); }
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <i class="bi bi-grid-3x3-gap-fill" style="font-size: 3rem; color: #3498db;"></i>
            <h3>سیستم بازدید تجهیزات</h3>
            <p class="small text-white-50">شرکت توزیع برق یزد</p>
        </div>
        
        <nav class="nav flex-column">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-speedometer2"></i> <span>داشبورد</span>
            </a>

<li class="nav-item">
    <a class="nav-link" href="{{ route('advanced.dashboard') }}">
        <i class="bi bi-graph-up"></i> <span>داشبورد پیشرفته</span>
    </a>
</li>
            <a class="nav-link {{ request()->routeIs('inspection.form') ? 'active' : '' }}" href="{{ route('inspection.form') }}">
                <i class="bi bi-clipboard-check"></i> <span>فرم بازدید</span>
            </a>
            <a class="nav-link {{ request()->routeIs('dashboard.inspections') ? 'active' : '' }}" href="{{ route('dashboard.inspections') }}">
                <i class="bi bi-list-check"></i> <span>لیست بازدیدها</span>
            </a>
            <a class="nav-link {{ request()->routeIs('reports.index') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                <i class="bi bi-file-text"></i> <span>گزارش‌ها</span>
            </a>
            
            @if(auth()->user()->role === 'admin')
            <div style="border-top: 1px solid rgba(255,255,255,0.1); margin: 1rem 0;"></div>
            
            <div class="nav-dropdown" id="dataBankDropdown">
                <div class="nav-link">
                    <i class="bi bi-database"></i> <span>بانک داده‌های اصلی</span>
                    <i class="bi bi-chevron-down"></i>
                </div>
                <div class="nav-submenu">
                    <a class="nav-link" href="{{ route('dashboard.contractors.index') }}">مدیریت پیمانکاران</a>
                    <a class="nav-link" href="{{ route('dashboard.departments.index') }}">مدیریت امورها</a>
                    <a class="nav-link" href="{{ route('dashboard.posts.index') }}">مدیریت پست‌ها</a>
                    <a class="nav-link" href="{{ route('dashboard.feeders.index') }}">مدیریت فیدرها</a>
                    <a class="nav-link" href="{{ route('dashboard.brands.index') }}">مدیریت برندها</a>
                    <a class="nav-link" href="{{ route('dashboard.equipment-types.index') }}">انواع تجهیزات اصلی</a>
                    <a class="nav-link" href="{{ route('dashboard.cell-equipment-types.index') }}">انواع تجهیزات سلولی</a>
                    <a class="nav-link" href="{{ route('dashboard.activity-prices.index') }}">فهرست بها</a>
                    <a class="nav-link" href="{{ route('dashboard.checklist-items.index') }}">چک‌لیست‌ها</a>
                    <a class="nav-link" href="{{ route('dashboard.simcard-types.index') }}">انواع سیم‌کارت</a>
                    <a class="nav-link" href="{{ route('dashboard.users.index') }}">مدیریت کاربران</a>
                    <a class="nav-link" href="{{ route('dashboard.activity-logs') }}">گزارش فعالیت‌ها</a>
                </div>
            </div>
            @endif
        </nav>
        
        <div class="mt-auto pt-3 border-top border-secondary">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link w-100 text-start" style="background: none; border: none; cursor: pointer;">
                    <i class="bi bi-box-arrow-left"></i> <span>خروج</span>
                </button>
            </form>
        </div>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <div class="page-title">
                <h4>@yield('title')</h4>
            </div>
            <div class="user-info d-flex align-items-center gap-3">
                <div class="user-details text-end">
                    <p class="user-name mb-0 fw-bold">{{ auth()->user()->name }}</p>
                    <small class="text-muted">
                        @if(auth()->user()->role === 'admin')
                            مدیر سیستم
                        @elseif(auth()->user()->role === 'supervisor')
                            ناظر
                        @else
                            کاربر عادی
                        @endif
                    </small>
                </div>
                <div class="user-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
            </div>
        </div>

        @yield('content')
    </div>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/chart.umd.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
    
    <script>
        // منوی کشویی
        document.addEventListener('DOMContentLoaded', function() {
            var currentUrl = window.location.pathname;
            
            document.querySelectorAll('.nav-link').forEach(function(link) {
                var href = link.getAttribute('href');
                if (href && href !== '#' && currentUrl.indexOf(href) !== -1) {
                    link.classList.add('active');
                }
            });
            
            var dropdowns = document.querySelectorAll('.nav-dropdown > .nav-link');
            dropdowns.forEach(function(dropdown) {
                dropdown.addEventListener('click', function(e) {
                    e.preventDefault();
                    var parent = this.parentElement;
                    parent.classList.toggle('open');
                    
                    document.querySelectorAll('.nav-dropdown').forEach(function(item) {
                        if (item !== parent && item.classList.contains('open')) {
                            item.classList.remove('open');
                        }
                    });
                });
            });
            
            document.querySelectorAll('.nav-submenu .nav-link').forEach(function(link) {
                var href = link.getAttribute('href');
                if (href && currentUrl.indexOf(href) !== -1) {
                    var dropdown = link.closest('.nav-dropdown');
                    if (dropdown) dropdown.classList.add('open');
                }
            });
        });
        
        // رفع کامل خطای aria-hidden در مودال‌ها
        document.addEventListener('DOMContentLoaded', function() {
            var modals = document.querySelectorAll('.modal');
            modals.forEach(function(modal) {
                modal.removeAttribute('aria-hidden');
                
                modal.addEventListener('hidden.bs.modal', function() {
                    this.removeAttribute('aria-hidden');
                    document.body.classList.add('modal-open');
                    document.querySelectorAll('.modal-backdrop').forEach(function(backdrop) {
                        backdrop.remove();
                    });
                });
                
                modal.addEventListener('shown.bs.modal', function() {
                    this.removeAttribute('aria-hidden');
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>