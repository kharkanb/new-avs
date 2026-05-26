@extends('layouts.admin')

@section('title', isset($item) ? 'ویرایش فیدر' : 'افزودن فیدر جدید')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>{{ isset($item) ? 'ویرایش فیدر' : 'افزودن فیدر جدید' }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ isset($item) ? route('dashboard.feeders.update', $item) : route('dashboard.feeders.store') }}">
            @csrf
            @if(isset($item)) @method('PUT') @endif
            
            <div class="mb-3">
                <label for="feeder_name" class="form-label required">نام فیدر</label>
                <input type="text" name="name" id="feeder_name" class="form-control @error('name') is-invalid @enderror" 
                       value="{{ old('name', isset($feeder) ? $feeder->name : '') }}" autocomplete="off" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            
            <div class="mb-3">
                <label for="post_id" class="form-label required">پست</label>
                <select name="post_id" id="post_id" class="form-select @error('post_id') is-invalid @enderror" required>
                    <option value="">انتخاب کنید</option>
                    @foreach($posts as $post)
                        <option value="{{ $post->id }}" {{ (old('post_id', isset($feeder) ? $feeder->post_id : '') == $post->id) ? 'selected' : '' }}>
                            {{ $post->name }}
                        </option>
                    @endforeach
                </select>
                @error('post_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            
            <div class="text-center">
                <button type="submit" class="btn btn-primary">ذخیره اطلاعات</button>
                <a href="{{ route('dashboard.feeders.index') }}" class="btn btn-secondary">انصراف</a>
            </div>
        </form>
    </div>
</div>
@endsection