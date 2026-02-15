@extends('layouts.tech')

@section('title', 'ویرایش تجهیز')

@section('content')
<div class="container">
    <h2 class="mb-4"><i class="bi bi-pencil"></i> ویرایش تجهیز</h2>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('tech.equipments.update', $equipment->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">کد اسکادا</label>
                        <input type="text" name="scada_code" class="form-control" value="{{ $equipment->scada_code }}" maxlength="4">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">عرض جغرافیایی</label>
                        <input type="text" name="latitude" class="form-control" value="{{ $equipment->latitude }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">طول جغرافیایی</label>
                        <input type="text" name="longitude" class="form-control" value="{{ $equipment->longitude }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">ارتفاع</label>
                        <input type="number" name="height" class="form-control" value="{{ $equipment->height }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">نوع نصب</label>
                        <select name="installation_type" class="form-control">
                            <option value="">انتخاب کنید</option>
                            <option value="هوایی" {{ $equipment->installation_type == 'هوایی' ? 'selected' : '' }}>هوایی</option>
                            <option value="زمینی" {{ $equipment->installation_type == 'زمینی' ? 'selected' : '' }}>زمینی</option>
                            <option value="داخلی" {{ $equipment->installation_type == 'داخلی' ? 'selected' : '' }}>داخلی</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">وضعیت</label>
                        <select name="is_active" class="form-control">
                            <option value="1" {{ $equipment->is_active ? 'selected' : '' }}>فعال</option>
                            <option value="0" {{ !$equipment->is_active ? 'selected' : '' }}>غیرفعال</option>
                        </select>
                    </div>
                </div>

                <hr>

                <h5 class="mb-3">فیدرها</h5>
                <div id="feeders-container">
                    @foreach($equipment->feeders as $feeder)
                    <div class="row mb-2 feeder-row">
                        <div class="col-md-10">
                            <input type="text" name="feeders[]" class="form-control" value="{{ $feeder->name }}">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-sm btn-danger" onclick="this.closest('.feeder-row').remove()">
                                <i class="bi bi-trash"></i> حذف
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="text-center my-2">
                    <button type="button" class="btn btn-sm btn-success" onclick="addFeeder()">
                        <i class="bi bi-plus-circle"></i> افزودن فیدر
                    </button>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> بروزرسانی
                    </button>
                    <a href="{{ route('tech.equipments.show', $equipment->id) }}" class="btn btn-secondary">
                        <i class="bi bi-x"></i> انصراف
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function addFeeder() {
    const container = document.getElementById('feeders-container');
    const row = document.createElement('div');
    row.className = 'row mb-2 feeder-row';
    row.innerHTML = `
        <div class="col-md-10">
            <input type="text" name="feeders[]" class="form-control" placeholder="نام فیدر">
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-sm btn-danger" onclick="this.closest('.feeder-row').remove()">
                <i class="bi bi-trash"></i> حذف
            </button>
        </div>
    `;
    container.appendChild(row);
}
</script>
@endpush