@extends('layouts.admin')

@section('title', isset($item) ? 'ویرایش چک‌لیست' : 'افزودن چک‌لیست جدید')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>{{ isset($item) ? 'ویرایش چک‌لیست' : 'افزودن چک‌لیست جدید' }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ isset($item) ? route('dashboard.checklist-items.update', $item->id) : route('dashboard.checklist-items.store') }}">
            @csrf
            @if(isset($item)) @method('PUT') @endif
            
            <div class="mb-3">
                <label for="title" class="form-label required">عنوان چک‌لیست</label>
                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" 
                       value="{{ old('title', $item->title ?? '') }}" required>
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            
            <div class="mb-3">
                <label for="main_equipment_type_id" class="form-label required">نوع تجهیز</label>
                <select name="main_equipment_type_id" id="main_equipment_type_id" class="form-select @error('main_equipment_type_id') is-invalid @enderror" required>
                    <option value="">انتخاب کنید</option>
                    @foreach($equipmentTypes ?? [] as $type)
                        @php
                            $hasChecklist = \App\Models\ChecklistTemplate::where('main_equipment_type_id', $type->id)->exists();
                            $isDisabled = isset($item) ? false : $hasChecklist;
                        @endphp
                        <option value="{{ $type->id }}" 
                            {{ (old('main_equipment_type_id', $item->main_equipment_type_id ?? '') == $type->id) ? 'selected' : '' }}
                            {{ $isDisabled ? 'disabled' : '' }}>
                            {{ $type->name }} 
                            @if($isDisabled && !isset($item))
                                (چک‌لیست دارد)
                            @endif
                        </option>
                    @endforeach
                </select>
                @error('main_equipment_type_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">توضیحات</label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                          rows="3">{{ old('description', $item->description ?? '') }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            
            <!-- آیتم‌های چک‌لیست -->
            <div class="mb-3">
                <label class="form-label">آیتم‌های چک‌لیست</label>

                <div id="items-container">
                    @if(isset($item) && $item->items && $item->items->count() > 0)
                        @foreach($item->items as $existingItem)
                        <div class="input-group mb-2 item-row" data-item-id="{{ $existingItem->id }}">
                            <input type="text" value="{{ $existingItem->item_text }}" class="form-control" readonly>
                            <input type="hidden" name="existing_items[]" value="{{ $existingItem->id }}">
                            <button type="button" class="btn btn-danger remove-item">حذف آیتم</button>
                        </div>
                        @endforeach
                    @endif
                </div>
                <div class="input-group mb-2">
                    <input type="text" name="items[]" class="form-control" placeholder="متن آیتم چک‌لیست">
                    <button type="button" class="btn btn-success" id="addItemBtn">+ افزودن آیتم</button>
                </div>
                <small class="text-muted">می‌توانید چندین آیتم برای این چک‌لیست تعریف کنید</small>
            </div>
            
            <div class="text-center">
                <button type="submit" class="btn btn-primary">ذخیره اطلاعات</button>
                <a href="{{ route('dashboard.checklist-items.index') }}" class="btn btn-secondary">انصراف</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // افزودن آیتم جدید
    $('#addItemBtn').click(function() {
        var newRow = `
            <div class="input-group mb-2 item-row">
                <input type="text" name="items[]" class="form-control" placeholder="متن آیتم چک‌لیست">
                <button type="button" class="btn btn-danger remove-item">حذف آیتم</button>
            </div>
        `;
        $('#items-container').append(newRow);
    });
    
    // حذف آیتم
    $(document).on('click', '.remove-item', function() {
        $(this).closest('.item-row').remove();
    });
});
</script>
@endpush