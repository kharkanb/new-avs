@extends('layouts.admin')

@section('title', 'مرکز گزارشات پیشرفته')

@section('header', 'مرکز گزارشات')

@section('content')
<div class="container-fluid">
    <!-- هدر صفحه -->
    <div class="page-header" data-aos="fade-down">
        <h1><i class="bi bi-file-earmark-text"></i> مرکز گزارشات پیشرفته</h1>
        <p>از میان گزارش‌های متنوع، گزارش مورد نظر خود را انتخاب کنید</p>
    </div>
    
    <!-- کارت‌های گزارشات -->
    <div class="row">
        <!-- گزارش جامع داشبورد -->
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <a href="{{ route('reports.comprehensive') }}" class="text-decoration-none">
                <div class="report-card">
                    <div class="report-icon primary">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <h3>گزارش جامع داشبورد</h3>
                    <p>تحلیل کامل بازدیدهای تجهیزات اتوماسیون و عملکرد پیمانکاران در قالب نمودارهای پیشرفته</p>
                    <span class="report-badge">مشاهده گزارش</span>
                </div>
            </a>
        </div>
        
        <!-- گزارش مالی صورت وضعیت -->
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
            <a href="{{ route('reports.financial') }}" class="text-decoration-none">
                <div class="report-card">
                    <div class="report-icon success">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <h3>گزارش مالی</h3>
                    <p>صورت وضعیت مالی بر اساس پیمانکار، شامل هزینه‌های بدون ضریب و نهایی با اعمال ضریب قرارداد</p>
                    <span class="report-badge">مشاهده گزارش</span>
                </div>
            </a>
        </div>
        
        <!-- گزارش خرابی‌ها -->
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
            <a href="{{ route('reports.failures') }}" class="text-decoration-none">
                <div class="report-card">
                    <div class="report-icon danger">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <h3>گزارش خرابی‌ها</h3>
                    <p>لیست کامل آیتم‌های Not OK در چک‌لیست‌ها به همراه آمار و تحلیل بیشترین خرابی‌ها</p>
                    <span class="report-badge">مشاهده گزارش</span>
                </div>
            </a>
        </div>
        
        <!-- گزارش عملکرد پیمانکاران -->
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="400">
            <a href="{{ route('reports.contractors-report') }}" class="text-decoration-none">
                <div class="report-card">
                    <div class="report-icon warning">
                        <i class="bi bi-people"></i>
                    </div>
                    <h3>گزارش پیمانکاران</h3>
                    <p>عملکرد پیمانکاران در بازه‌های زمانی مختلف، تعداد بازدیدها و هزینه‌های هر پیمانکار</p>
                    <span class="report-badge">مشاهده گزارش</span>
                </div>
            </a>
        </div>
        
        <!-- گزارش امورها -->
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="500">
            <a href="{{ route('reports.departments-report') }}" class="text-decoration-none">
                <div class="report-card">
                    <div class="report-icon info">
                        <i class="bi bi-building"></i>
                    </div>
                    <h3>گزارش امورها</h3>
                    <p>آمار وضعیت امورهای مختلف، تعداد تجهیزات بازدید شده و هزینه‌های هر امور</p>
                    <span class="report-badge">مشاهده گزارش</span>
                </div>
            </a>
        </div>
        
        <!-- گزارش تجهیزات -->
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="600">
            <a href="{{ route('reports.equipment-report') }}" class="text-decoration-none">
                <div class="report-card">
                    <div class="report-icon primary">
                        <i class="bi bi-hdd-stack"></i>
                    </div>
                    <h3>گزارش تجهیزات</h3>
                    <p>لیست کامل تجهیزات بازدید شده به همراه مشخصات فنی، موقعیت مکانی و وضعیت چک‌لیست</p>
                    <span class="report-badge">مشاهده گزارش</span>
                </div>
            </a>
        </div>
        
        <!-- گزارش نتایج چک‌لیست -->
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="700">
            <a href="{{ route('reports.checklist-results') }}" class="text-decoration-none">
                <div class="report-card">
                    <div class="report-icon success">
                        <i class="bi bi-clipboard-check"></i>
                    </div>
                    <h3>گزارش چک‌لیست</h3>
                    <p>نتایج کامل چک‌لیست‌ها با فیلترهای پیشرفته و آمار وضعیت OK/Not OK</p>
                    <span class="report-badge">مشاهده گزارش</span>
                </div>
            </a>
        </div>
        
        <!-- گزارش روزانه -->
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="800">
            <a href="{{ route('reports.daily') }}" class="text-decoration-none">
                <div class="report-card">
                    <div class="report-icon info">
                        <i class="bi bi-calendar-day"></i>
                    </div>
                    <h3>گزارش روزانه</h3>
                    <p>بازدیدهای روزانه با قابلیت فیلتر بر اساس تاریخ، پیمانکار و نوع تجهیز</p>
                    <span class="report-badge">مشاهده گزارش</span>
                </div>
            </a>
        </div>
        
        <!-- گزارش ماهانه -->
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="900">
            <a href="{{ route('reports.monthly') }}" class="text-decoration-none">
                <div class="report-card">
                    <div class="report-icon warning">
                        <i class="bi bi-calendar-month"></i>
                    </div>
                    <h3>گزارش ماهانه</h3>
                    <p>تحلیل ماهانه بازدیدها، آمار پیمانکاران و هزینه‌ها به تفکیک ماه</p>
                    <span class="report-badge">مشاهده گزارش</span>
                </div>
            </a>
        </div>
        
        <!-- گزارش نموداری پیشرفته -->
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="1000">
            <a href="{{ route('reports.charts') }}" class="text-decoration-none">
                <div class="report-card">
                    <div class="report-icon danger">
                        <i class="bi bi-bar-chart"></i>
                    </div>
                    <h3>گزارش نموداری</h3>
                    <p>نمایش گرافیکی داده‌ها شامل روند بازدیدها، توزیع تجهیزات و عملکرد پیمانکاران</p>
                    <span class="report-badge">مشاهده گزارش</span>
                </div>
            </a>
        </div>
        
        <!-- گزارش پیشرفته -->
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="1100">
            <a href="{{ route('reports.advanced') }}" class="text-decoration-none">
                <div class="report-card">
                    <div class="report-icon primary">
                        <i class="bi bi-funnel"></i>
                    </div>
                    <h3>گزارش پیشرفته</h3>
                    <p>گزارش سفارشی با فیلترهای ترکیبی و خروجی Excel/PDF</p>
                    <span class="report-badge">مشاهده گزارش</span>
                </div>
            </a>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* استایل کارت‌های گزارش */
    .report-card {
        background: white;
        border-radius: 20px;
        padding: 40px 20px;
        margin-bottom: 25px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        cursor: pointer;
        text-align: center;
        height: 100%;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .report-card:hover { 
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 25px 50px rgba(0,0,0,0.15);
    }
    .report-icon { 
        font-size: 60px; 
        margin-bottom: 20px;
        transition: all 0.3s;
        display: inline-block;
    }
    .report-card:hover .report-icon {
        transform: scale(1.1) rotate(5deg);
    }
    .report-icon.primary { color: #3498db; }
    .report-icon.warning { color: #f39c12; }
    .report-icon.success { color: #27ae60; }
    .report-icon.danger { color: #e74c3c; }
    .report-icon.info { color: #00bcd4; }
    .report-card h3 {
        font-size: 1.3rem;
        font-weight: bold;
        margin-bottom: 15px;
        color: #2c3e50;
    }
    .report-card p {
        font-size: 0.9rem;
        color: #7f8c8d;
        margin-bottom: 20px;
        line-height: 1.6;
    }
    .report-badge {
        display: inline-block;
        padding: 8px 20px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: bold;
        transition: all 0.3s;
    }
    .report-card:hover .report-badge {
        background: linear-gradient(135deg, #764ba2, #667eea);
        transform: scale(1.05);
    }
    
    /* استایل هدر صفحه */
    .page-header {
        background: linear-gradient(135deg, white, #f8f9fa);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        text-align: center;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .page-header h1 {
        font-size: 2rem;
        font-weight: 800;
        background: linear-gradient(135deg, #2c3e50, #3498db);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 10px;
    }
    .page-header p {
        font-size: 1rem;
        color: #7f8c8d;
    }
    
    @media (max-width: 768px) {
        .page-header h1 { font-size: 1.5rem; }
        .report-card { padding: 25px 15px; }
        .report-icon { font-size: 45px; }
        .report-card h3 { font-size: 1.1rem; }
    }
</style>
@endpush
@endsection