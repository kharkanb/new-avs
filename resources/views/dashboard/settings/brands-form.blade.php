@extends('layouts.admin')

@section('title', isset($item) ? 'ویرایش برند' : 'افزودن برند جدید')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>{{ isset($item) ? 'ویرایش برند' : 'افزودن برند جدید' }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ isset($item) ? route('dashboard.brands.update', $item) : route('dashboard.brands.store') }}">
            @csrf
            @if(isset($item)) @method('PUT') @endif
            
            <!-- 1. نوع تجهیز (رادیویی) -->
            <div class="mb-3">
                <label class="form-label required">نوع تجهیز</label>
                <div class="btn-group w-100 mb-2" role="group">
                    <input type="radio" class="btn-check" name="type_category" id="type_main" value="main" 
                           {{ old('type_category', isset($item) && $item->equipment_type_id ? 'main' : '') == 'main' ? 'checked' : '' }}>
                    <label class="btn btn-outline-primary" for="type_main">تجهیز اصلی</label>
                    
                    <input type="radio" class="btn-check" name="type_category" id="type_cell" value="cell"
                           {{ old('type_category', isset($item) && $item->cell_equipment_type_id ? 'cell' : '') == 'cell' ? 'checked' : '' }}>
                    <label class="btn btn-outline-primary" for="type_cell">تجهیز سلولی</label>
                </div>
                @error('type_category')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <!-- 2. انتخاب نوع تجهیز اصلی -->
            <div class="mb-3" id="main_equipment_div" style="display: none;">
                <label for="equipment_type_id" class="form-label">نوع تجهیز اصلی</label>
                <select name="equipment_type_id" id="equipment_type_id" class="form-select @error('equipment_type_id') is-invalid @enderror">
                    <option value="">انتخاب کنید</option>
                    @foreach($mainEquipmentTypes ?? [] as $type)
                        <option value="{{ $type->id }}" {{ (old('equipment_type_id', $item->equipment_type_id ?? '') == $type->id) ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
                @error('equipment_type_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- 3. انتخاب نوع تجهیز سلولی -->
            <div class="mb-3" id="cell_equipment_div" style="display: none;">
                <label for="cell_equipment_type_id" class="form-label">نوع تجهیز سلولی</label>
                <select name="cell_equipment_type_id" id="cell_equipment_type_id" class="form-select @error('cell_equipment_type_id') is-invalid @enderror">
                    <option value="">انتخاب کنید</option>
                    @foreach($cellEquipmentTypes ?? [] as $type)
                        <option value="{{ $type->id }}" {{ (old('cell_equipment_type_id', $item->cell_equipment_type_id ?? '') == $type->id) ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
                @error('cell_equipment_type_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- 4. نام برند (اصلی) -->
            <div class="mb-3">
                <label for="name" class="form-label required">نام برند</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                       value="{{ old('name', $item->name ?? '') }}" required autocomplete="off">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="text-center">
                <button type="submit" class="btn btn-primary px-4">ذخیره اطلاعات</button>
                <a href="{{ route('dashboard.brands.index') }}" class="btn btn-secondary px-4">انصراف</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    function toggleEquipmentType() {
        var selected = $('input[name="type_category"]:checked').val();
        if (selected == 'main') {
            $('#main_equipment_div').show();
            $('#cell_equipment_div').hide();
            $('#equipment_type_id').prop('required', true);
            $('#cell_equipment_type_id').prop('required', false);
        } else if (selected == 'cell') {
            $('#main_equipment_div').hide();
            $('#cell_equipment_div').show();
            $('#equipment_type_id').prop('required', false);
            $('#cell_equipment_type_id').prop('required', true);
        } else {
            $('#main_equipment_div').hide();
            $('#cell_equipment_div').hide();
        }
    }
    
    $('input[name="type_category"]').change(toggleEquipmentType);
    toggleEquipmentType();
});
</script>
@endpush