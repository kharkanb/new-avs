@extends('layouts.admin')

@section('title', 'تنظیمات سیستم')

@section('content')
<div class="container">
    <h2 class="mb-4"><i class="bi bi-gear"></i> تنظیمات سیستم</h2>

    <ul class="nav nav-tabs mb-4" id="settingsTabs">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#general">
                <i class="bi bi-sliders2"></i> عمومی
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#equipmentTypes">
                <i class="bi bi-hdd-stack"></i> انواع تجهیزات
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#brands">
                <i class="bi bi-tag"></i> برندها
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#checklist">
                <i class="bi bi-clipboard-check"></i> چک‌لیست‌ها
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#prices">
                <i class="bi bi-cash-coin"></i> قیمت‌ها
            </button>
        </li>
    </ul>

    <div class="tab-content">
        <!-- تب تنظیمات عمومی -->
        <div class="tab-pane fade show active" id="general">
            <div class="card">
                <div class="card-header">
                    <h5>تنظیمات عمومی</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">نام شرکت</label>
                                <input type="text" name="company_name" class="form-control" 
                                       value="{{ config('app.company_name', 'شرکت توزیع نیروی برق استان یزد') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">تلفن پشتیبانی</label>
                                <input type="text" name="support_phone" class="form-control" 
                                       value="{{ config('app.support_phone', '035-37271000') }}">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">ایمیل پشتیبانی</label>
                                <input type="email" name="support_email" class="form-control" 
                                       value="{{ config('app.support_email', 'support@yazdedc.ir') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">ضریب پیش‌فرض قرارداد</label>
                                <input type="number" step="0.01" name="default_coefficient" class="form-control" 
                                       value="{{ config('app.default_coefficient', '2.35') }}">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">آدرس</label>
                            <textarea name="address" class="form-control" rows="2">{{ config('app.address', 'یزد، بلوار جمهوری') }}</textarea>
                        </div>
                        
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> ذخیره تنظیمات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- تب انواع تجهیزات -->
        <div class="tab-pane fade" id="equipmentTypes">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>انواع تجهیزات</h5>
                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addTypeModal">
                        <i class="bi bi-plus-circle"></i> نوع جدید
                    </button>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>نام نوع</th>
                                <th>دسته‌بندی</th>
                                <th>نیاز به برند</th>
                                <th>ارتفاع</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($equipmentTypes ?? [] as $type)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $type->name }}</td>
                                <td>{{ $type->category }}</td>
                                <td>
                                    @if($type->has_brand)
                                        <span class="badge bg-success">دارد</span>
                                    @else
                                        <span class="badge bg-secondary">ندارد</span>
                                    @endif
                                </td>
                                <td>
                                    @if($type->has_height)
                                        <span class="badge bg-success">دارد</span>
                                    @else
                                        <span class="badge bg-secondary">ندارد</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning edit-type" data-id="{{ $type->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-type" data-id="{{ $type->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- تب برندها -->
        <div class="tab-pane fade" id="brands">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>مدیریت برندها</h5>
                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addBrandModal">
                        <i class="bi bi-plus-circle"></i> برند جدید
                    </button>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>نام برند</th>
                                <th>دسته‌بندی</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($brands ?? [] as $brand)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $brand->name }}</td>
                                <td>{{ $brand->category ?? 'عمومی' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning edit-brand" data-id="{{ $brand->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-brand" data-id="{{ $brand->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- تب چک‌لیست‌ها -->
        <div class="tab-pane fade" id="checklist">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>مدیریت چک‌لیست‌ها</h5>
                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addChecklistModal">
                        <i class="bi bi-plus-circle"></i> چک‌لیست جدید
                    </button>
                </div>
                <div class="card-body">
                    @foreach($checklists ?? [] as $checklist)
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6>{{ $checklist->name }}</h6>
                        </div>
                        <div class="card-body">
                            <ol>
                                @foreach($checklist->items as $item)
                                <li>{{ $item }}</li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- تب قیمت‌ها -->
        <div class="tab-pane fade" id="prices">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>مدیریت قیمت‌ها</h5>
                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addPriceModal">
                        <i class="bi bi-plus-circle"></i> قیمت جدید
                    </button>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>کد</th>
                                <th>عنوان فعالیت</th>
                                <th>واحد</th>
                                <th>قیمت (ریال)</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($prices ?? [] as $price)
                            <tr>
                                <td>{{ $price->code }}</td>
                                <td>{{ $price->title }}</td>
                                <td>{{ $price->unit }}</td>
                                <td class="text-end">{{ number_format($price->price) }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning edit-price" data-id="{{ $price->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger delete-price" data-id="{{ $price->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection