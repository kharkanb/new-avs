@extends('layouts.admin')

@section('title', isset($item) ? 'ویرایش پست' : 'افزودن پست جدید')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>{{ isset($item) ? 'ویرایش پست' : 'افزودن پست جدید' }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ isset($item) ? route('dashboard.posts.update', $item) : route('dashboard.posts.store') }}" id="postForm">
            @csrf
            @if(isset($item)) @method('PUT') @endif
            
            <div class="mb-3">
                <label for="post_name" class="form-label required">نام پست</label>
                <input type="text" name="name" id="post_name" class="form-control @error('name') is-invalid @enderror" 
                       value="{{ old('name', $item->name ?? '') }}" autocomplete="off" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            
            <div class="mb-3">
                <label class="form-label">فیدرها</label>
                <div id="feeders-container">
                    @if(isset($item) && $item->feeders->count() > 0)
                        @foreach($item->feeders as $feeder)
                        <div class="input-group mb-2 feeder-row" data-feeder-id="{{ $feeder->id }}">
                            <input type="text" name="existing_feeders[]" value="{{ $feeder->name }}" 
                                   class="form-control" readonly autocomplete="off">
                            <input type="hidden" name="existing_feeders[]" value="{{ $feeder->id }}">
                            <button type="button" class="btn btn-danger remove-feeder">حذف</button>
                        </div>
                        @endforeach
                    @endif
                </div>
                
                <div class="input-group mb-2">
                    <input type="text" name="feeders[]" class="form-control" 
                           placeholder="نام فیدر جدید" autocomplete="off">
                    <button type="button" class="btn btn-success" id="addFeederBtn">افزودن فیدر</button>
                </div>
                <small class="text-muted">می‌توانید چندین فیدر برای این پست تعریف کنید</small>
            </div>
            
            <div class="text-center">
                <button type="submit" class="btn btn-primary" id="submitBtn">ذخیره اطلاعات</button>
                <a href="{{ route('dashboard.posts.index') }}" class="btn btn-secondary">انصراف</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // افزودن فیدر جدید
    $('#addFeederBtn').click(function() {
        var newRow = `
            <div class="input-group mb-2 feeder-row">
                <input type="text" name="feeders[]" class="form-control" placeholder="نام فیدر جدید" autocomplete="off">
                <button type="button" class="btn btn-danger remove-feeder">حذف</button>
            </div>
        `;
        $('#feeders-container').append(newRow);
        
        // پاک کردن فیلد ورودی
        $('.input-group.mb-2:not(.feeder-row) input[name="feeders[]"]').val('');
    });
    
    // حذف فیدر
    $(document).on('click', '.remove-feeder', function() {
        $(this).closest('.feeder-row').remove();
    });
    
    // نمایش خطاهای اعتبارسنجی (اختیاری)
    @if($errors->any())
        @foreach($errors->all() as $error)
            Swal.fire('خطا', '{{ $error }}', 'error');
        @endforeach
    @endif
});
</script>
@endpush