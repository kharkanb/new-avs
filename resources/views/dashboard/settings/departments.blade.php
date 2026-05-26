<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مدیریت امور | سیستم بازدید تجهیزات</title>
    
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
        .sidebar-header { text-align: center; padding-bottom: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 1.5rem; }
        .sidebar-header h3 { font-size: 1.2rem; margin-top: 0.5rem; }
        .nav-link { color: rgba(255,255,255,0.8); padding: 0.8rem 1rem; margin: 0.3rem 0;
            border-radius: 10px; transition: all 0.3s; display: flex; align-items: center; gap: 10px; }
        .nav-link:hover, .nav-link.active { background: rgba(255,255,255,0.1); color: white; transform: translateX(-5px); }
        .nav-link i { font-size: 1.2rem; width: 24px; }
        .top-bar { background: white; padding: 1rem 2rem; border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 2rem;
            display: flex; justify-content: space-between; align-items: center; }
        .user-avatar { width: 45px; height: 45px; background: linear-gradient(135deg, #3498db, #2980b9);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            color: white; font-weight: bold; font-size: 1.2rem; }
        .settings-menu { background: white; border-radius: 10px; padding: 1rem; margin-bottom: 1rem; }
        .settings-menu .nav-link { color: #2c3e50; background: #f8f9fc; margin: 5px 0; }
        .settings-menu .nav-link:hover, .settings-menu .nav-link.active { background: #3498db; color: white; }
        @media (max-width: 768px) { .main-content { margin-right: 0; } }
    </style>
</head>
<body>
    <!-- سایدبار (مثل قبل) -->
    <div class="sidebar">
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
            <a class="nav-link" href="{{ route('dashboard.contractors') }}">
                <i class="bi bi-briefcase"></i> <span>مدیریت پیمانکاران</span>
            </a>
            <a class="nav-link" href="{{ route('dashboard.departments.index') }}">
                <i class="bi bi-building"></i> <span>مدیریت امورها</span>
            </a>
            <a class="nav-link" href="{{ route('dashboard.posts.index') }}">
                <i class="bi bi-geo-alt"></i> <span>مدیریت پست‌ها</span>
            </a>
            <a class="nav-link" href="{{ route('dashboard.feeders.index') }}">
                <i class="bi bi-bezier2"></i> <span>مدیریت فیدرها</span>
            </a>
            <a class="nav-link" href="{{ route('dashboard.brands.index') }}">
                <i class="bi bi-tag"></i> <span>مدیریت برندها</span>
            </a>
            <a class="nav-link" href="{{ route('dashboard.equipment-types.index') }}">
                <i class="bi bi-hdd-stack"></i> <span>انواع تجهیزات</span>
            </a>
            <a class="nav-link" href="{{ route('dashboard.activity-prices.index') }}">
                <i class="bi bi-calculator"></i> <span>فهرست بها</span>
            </a>
            <a class="nav-link" href="{{ route('dashboard.checklist-items.index') }}">
                <i class="bi bi-clipboard-check"></i> <span>چک‌لیست‌ها</span>
            </a>
            <a class="nav-link" href="{{ route('dashboard.simcard-types.index') }}">
                <i class="bi bi-sim"></i> <span>انواع سیم‌کارت</span>
            </a>
            <a class="nav-link" href="{{ route('dashboard.users') }}">
                <i class="bi bi-people"></i> <span>مدیریت کاربران</span>
            </a>
            @endif
        </nav>        
        <div style="position: absolute; bottom: 2rem; right: 1rem; left: 1rem;">
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button type="submit" class="nav-link w-100 text-start" style="background: none; border: none;">
                    <i class="bi bi-box-arrow-left"></i> <span>خروج از سیستم</span>
                </button>
            </form>
        </div>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <div class="page-title"><h4>مدیریت امور (دپارتمان‌ها)</h4></div>
            <div class="user-info d-flex align-items-center gap-3">
                <div class="user-details text-end"><p class="user-name mb-0 fw-bold">{{ auth()->user()->name }}</p></div>
                <div class="user-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
            </div>
        </div>

        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">لیست امورها</h5>
                <a href="{{ route('dashboard.departments.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i> افزودن امور جدید</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>نام امور</th>
                                <th>شهر</th>
                                <th>کد</th>
                                <th>توضیحات</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($departments as $index => $item)
                            <tr>
                                <td>{{ $departments->firstItem() + $index }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->city ?? '---' }}</td>
                                <td>{{ $item->code ?? '---' }}</td>
                                <td>{{ Str::limit($item->description ?? '---', 30) }}</td>
                                <td>
                                    <a href="{{ route('dashboard.departments.edit', $item) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteItem('{{ route('dashboard.departments.destroy', $item) }}', '{{ $item->name }}')"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center py-4">هیچ اموری ثبت نشده است</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $departments->links() }}
            </div>
        </div>
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script>
        function deleteItem(url, name) {
            if(confirm('آیا از حذف "' + name + '" اطمینان دارید؟')) {
                const form = document.createElement('form'); form.method = 'POST'; form.action = url;
                form.innerHTML = '@csrf @method("DELETE")'; document.body.appendChild(form); form.submit();
            }
        }
    </script>
</body>
</html>