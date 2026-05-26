<!-- resources/views/partials/filters.blade.php -->
<div class="filter-box">
    <form method="GET" action="{{ route($route) }}" class="row g-3">
        <div class="col-md-3">
            <label class="form-label">از تاریخ (شمسی)</label>
            <div class="input-group">
                <input type="text" 
                       name="start_date" 
                       class="form-control persian-date-input" 
                       id="start_date_{{ str_replace('.', '_', $route) }}"
                       placeholder="1404/01/01" 
                       value="{{ request('start_date') }}"
                       autocomplete="off">
                <span class="input-group-text" onclick="showPersianCalendar('start_date_{{ str_replace('.', '_', $route) }}')" style="cursor: pointer;">
                    <i class="bi bi-calendar3"></i>
                </span>
            </div>
        </div>
        <div class="col-md-3">
            <label class="form-label">تا تاریخ (شمسی)</label>
            <div class="input-group">
                <input type="text" 
                       name="end_date" 
                       class="form-control persian-date-input" 
                       id="end_date_{{ str_replace('.', '_', $route) }}"
                       placeholder="1404/12/29" 
                       value="{{ request('end_date') }}"
                       autocomplete="off">
                <span class="input-group-text" onclick="showPersianCalendar('end_date_{{ str_replace('.', '_', $route) }}')" style="cursor: pointer;">
                    <i class="bi bi-calendar3"></i>
                </span>
            </div>
        </div>

        @if(isset($showContractorFilter) && $showContractorFilter)
        <div class="col-md-3">
            <label class="form-label">پیمانکار</label>
            <select name="contractor_id" class="form-select">
                <option value="">همه پیمانکاران</option>
                @foreach($contractors ?? [] as $id => $name)
                <option value="{{ $id }}" {{ request('contractor_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>
        @endif

        @if(isset($showDepartmentFilter) && $showDepartmentFilter)
        <div class="col-md-3">
            <label class="form-label"><i class="bi bi-building"></i> دپارتمان/امور</label>
            <select name="department_id" class="form-select">
                <option value="">همه دپارتمان‌ها</option>
                @foreach($departments ?? [] as $id => $name)
                <option value="{{ $id }}" {{ request('department_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>
        @endif

        @if(isset($showStatusFilter) && $showStatusFilter)
        <div class="col-md-3">
            <label class="form-label">وضعیت</label>
            <select name="status" class="form-select">
                <option value="all">همه وضعیت‌ها</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>تکمیل شده</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>پیش‌نویس</option>
                <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>بایگانی شده</option>
            </select>
        </div>
        @endif

        @if(isset($showEquipmentFilter) && $showEquipmentFilter)
        <div class="col-md-3">
            <label class="form-label">نوع تجهیز</label>
            <select name="equipment_type" class="form-select">
                <option value="">همه</option>
                @foreach($equipmentTypes ?? [] as $id => $name)
                <option value="{{ $id }}" {{ request('equipment_type') == $id ? 'selected' : '' }}>
                    {{ $name }}
                </option>
                @endforeach
            </select>
        </div>
        @endif

        @if(isset($showUserFilter) && $showUserFilter)
        <div class="col-md-3">
            <label class="form-label">کاربر</label>
            <select name="user_id" class="form-select">
                <option value="">همه</option>
                @foreach($users ?? [] as $user)
                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
                @endforeach
            </select>
        </div>
        @endif

        @if(isset($showMinEquipments) && $showMinEquipments)
        <div class="col-md-2">
            <label class="form-label">حداقل تجهیزات</label>
            <input type="number" name="min_equipments" class="form-control" 
                   value="{{ request('min_equipments') }}" min="1">
        </div>
        @endif

        <div class="col-12 text-center mt-4">
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-search"></i> اعمال فیلتر
            </button>
            <a href="{{ route($route) }}" class="btn btn-secondary px-4">
                <i class="bi bi-eraser"></i> پاک کردن
            </a>
        </div>
    </form>
</div>

<!-- اضافه کردن تقویم شمسی -->
@include('partials.persian-calendar')