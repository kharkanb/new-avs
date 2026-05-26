<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مدیریت فهرست بها</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/Vazirmatn-font-face.css') }}" rel="stylesheet">
    <style>
        * { font-family: 'Vazirmatn', sans-serif; }
        body { background-color: #f8f9fc; }
        .main-content { margin-right: 280px; padding: 2rem; }
        .sidebar { position: fixed; right: 0; top: 0; bottom: 0; width: 280px;
            background: linear-gradient(180deg, #2c3e50 0%, #1e2b37 100%); color: white; padding: 2rem 1rem;
            overflow-y: auto; box-shadow: 2px 0 10px rgba(0,0,0,0.1); z-index: 1000; }
        .sidebar-header { text-align: center; padding-bottom: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .nav-link { color: rgba(255,255,255,0.8); padding: 0.8rem 1rem; margin: 0.3rem 0;
            border-radius: 10px; display: flex; align-items: center; gap: 10px; }
        .nav-link:hover, .nav-link.active { background: rgba(255,255,255,0.1); color: white; }
        .top-bar { background: white; padding: 1rem 2rem; border-radius: 15px; margin-bottom: 2rem;
            display: flex; justify-content: space-between; align-items: center; }
        .user-avatar { width: 45px; height: 45px; background: linear-gradient(135deg, #3498db, #2980b9);
            border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; }
        .form-card { background: white; border-radius: 15px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .required::after { content: " *"; color: #dc3545; }
        @media (max-width: 768px) { .main-content { margin-right: 0; } }
    </style>
</head>
<body>
    <!-- سایدبار -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <i class="bi bi-grid-3x3-gap-fill" style="font-size: 3rem; color: #3498db;"></i>
            <h3>سیستم بازدید تجهیزات</h3>
            <p class="small text-white-50">شرکت توزیع برق یزد</p>
        </div>
        
        <nav class="nav flex-column">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="bi bi-speedometer2"></i> <span>داشبورد</span>
            </a>
            <a class="nav-link" href="{{ route('inspection.form') }}">
                <i class="bi bi-clipboard-check"></i> <span>فرم بازدید</span>
            </a>
            <a class="nav-link" href="{{ route('dashboard.inspections') }}">
                <i class="bi bi-list-check"></i> <span>لیست بازدیدها</span>
            </a>
            <a class="nav-link" href="{{ route('reports.index') }}">
                <i class="bi bi-file-text"></i> <span>گزارش‌ها</span>
            </a>
            
            @if(auth()->user()->role === 'admin')
            <div style="border-top: 1px solid rgba(255,255,255,0.1); margin: 1rem 0;"></div>
            <a class="nav-link" href="{{ route('dashboard.contractors') }}"><i class="bi bi-briefcase"></i> <span>مدیریت پیمانکاران</span></a>
            <a class="nav-link" href="{{ route('dashboard.departments.index') }}"><i class="bi bi-building"></i> <span>مدیریت امورها</span></a>
            <a class="nav-link" href="{{ route('dashboard.posts.index') }}"><i class="bi bi-geo-alt"></i> <span>مدیریت پست‌ها</span></a>
            <a class="nav-link" href="{{ route('dashboard.feeders.index') }}"><i class="bi bi-bezier2"></i> <span>مدیریت فیدرها</span></a>
            <a class="nav-link" href="{{ route('dashboard.brands.index') }}"><i class="bi bi-tag"></i> <span>مدیریت برندها</span></a>
            <a class="nav-link" href="{{ route('dashboard.equipment-types.index') }}"><i class="bi bi-hdd-stack"></i> <span>انواع تجهیزات</span></a>
            <a class="nav-link" href="{{ route('dashboard.activity-prices.index') }}"><i class="bi bi-calculator"></i> <span>فهرست بها</span></a>
            <a class="nav-link" href="{{ route('dashboard.checklist-items.index') }}"><i class="bi bi-clipboard-check"></i> <span>چک‌لیست‌ها</span></a>
            <a class="nav-link" href="{{ route('dashboard.simcard-types.index') }}"><i class="bi bi-sim"></i> <span>انواع سیم‌کارت</span></a>
            <a class="nav-link" href="{{ route('dashboard.users') }}"><i class="bi bi-people"></i> <span>مدیریت کاربران</span></a>
            @endif
        </nav>
        
        <div style="position: absolute; bottom: 2rem; right: 1rem; left: 1rem;">
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button type="submit" class="nav-link w-100 text-start" style="background: none; border: none;">
                    <i class="bi bi-box-arrow-left"></i> <span>خروج</span>
                </button>
            </form>
        </div>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <div class="page-title"><h4>{{ isset($item) ? 'ویرایش' : 'افزودن جدید' }}</h4></div>
            <div class="user-info d-flex align-items-center gap-3">
                <div class="user-details text-end"><p class="user-name mb-0 fw-bold">{{ auth()->user()->name }}</p></div>
                <div class="user-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
            </div>
        </div>

        <div class="form-card">
            <form method="POST" action="{{ isset($item) ? route('dashboard.' . Request::segment(2) . '.update', $item) : route('dashboard.' . Request::segment(2) . '.store') }}">
                @csrf
                @if(isset($item)) @method('PUT') @endif
                
                <!-- فیلدها باید اینجا اضافه شوند -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label required">کد فعالیت</label>
                        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" 
                               value="{{ old('code', $item->code ?? '') }}" required>
                        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label required">عنوان فعالیت</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                               value="{{ old('title', $item->title ?? '') }}" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label required">واحد</label>
                        <input type="text" name="unit" class="form-control @error('unit') is-invalid @enderror" 
                               value="{{ old('unit', $item->unit ?? '') }}" required>
                        @error('unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label required">قیمت واحد (ریال)</label>
                        <input type="number" name="unit_price" class="form-control @error('unit_price') is-invalid @enderror" 
                               value="{{ old('unit_price', $item->unit_price ?? '') }}" required>
                        @error('unit_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                
                <div class="text-center">
                    <button type="submit" class="btn btn-primary px-4">ذخیره اطلاعات</button>
                    <a href="{{ route('dashboard.' . Request::segment(2) . '.index') }}" class="btn btn-secondary px-4">انصراف</a>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>