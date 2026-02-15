<div class="equipment-card card mb-3">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h6 class="mb-0">تجهیز <span class="equipment-number"></span></h6>
        <button type="button" class="btn btn-sm btn-danger remove-equipment">
            <i class="bi bi-trash"></i> حذف
        </button>
    </div>
    <div class="card-body">
        <div class="row mb-2">
            <div class="col-md-4">
                <label class="form-label required">نوع تجهیز</label>
                <select name="equipments[{{ $index }}][type]" class="form-control equipment-type" required>
                    <option value="">انتخاب کنید</option>
                    @foreach($equipmentTypes as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label required">کد اسکادا</label>
                <input type="text" name="equipments[{{ $index }}][scada_code]" class="form-control" maxlength="4" pattern="[0-9]{4}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label required">پست</label>
                <select name="equipments[{{ $index }}][post_id]" class="form-control" required>
                    <option value="">انتخاب کنید</option>
                    @foreach($posts as $post)
                        <option value="{{ $post->id }}">{{ $post->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>