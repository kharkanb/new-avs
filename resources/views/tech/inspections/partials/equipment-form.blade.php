<div class="equipment-card card mb-3" id="equipment-{{ $index }}">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <h6 class="mb-0">
            <i class="bi bi-hdd-stack"></i> 
            تجهیز <span class="equipment-number">{{ $index + 1 }}</span>
        </h6>
        <button type="button" class="btn btn-sm btn-danger remove-equipment" onclick="removeEquipment({{ $index }})">
            <i class="bi bi-trash"></i> حذف
        </button>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label required">نوع تجهیز</label>
                <select name="equipments[{{ $index }}][type]" class="form-control equipment-type-select" required>
                    <option value="">انتخاب کنید</option>
                    <option value="ریکلوزر">ریکلوزر</option>
                    <option value="سکسیونر">سکسیونر</option>
                    <option value="سکشنالایزر">سکشنالایزر</option>
                    <option value="فالت دتکتور">فالت دتکتور</option>
                    <option value="پست دو سو تغذیه (مشترک حساس)">پست دو سو تغذیه (مشترک حساس)</option>
                    <option value="پست دو سو تغذیه (بیمارستانی)">پست دو سو تغذیه (بیمارستانی)</option>
                    <option value="مشترک ولتاژ اولیه">مشترک ولتاژ اولیه</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label required">کد اسکادا (۴ رقم)</label>
                <input type="text" name="equipments[{{ $index }}][scada_code]" 
                       class="form-control" maxlength="4" pattern="[0-9]{4}" 
                       placeholder="مثال: 1234" required>
            </div>
            <div class="col-md-4">
                <label class="form-label required">پست</label>
                <select name="equipments[{{ $index }}][post_id]" class="form-control post-select" required>
                    <option value="">انتخاب کنید</option>
                    @foreach($posts ?? [] as $post)
                        <option value="{{ $post->id }}">{{ $post->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">برند کلید</label>
                <select name="equipments[{{ $index }}][switch_brand]" class="form-control switch-brand-select">
                    <option value="">انتخاب کنید</option>
                    <option value="ABB">ABB</option>
                    <option value="Schneider">Schneider</option>
                    <option value="Siemens">Siemens</option>
                    <option value="NOJA">NOJA</option>
                    <option value="Tavrida">Tavrida</option>
                    <option value="Eaton">Eaton</option>
                    <option value="سایر">سایر</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">برند مودم</label>
                <select name="equipments[{{ $index }}][modem_brand]" class="form-control modem-brand-select">
                    <option value="">انتخاب کنید</option>
                    <option value="Fortak">Fortak</option>
                    <option value="FSK">FSK</option>
                    <option value="Quadric">Quadric</option>
                    <option value="جهان ویستا">جهان ویستا</option>
                    <option value="TELTONIKA">TELTONIKA</option>
                    <option value="HUAWEI">HUAWEI</option>
                    <option value="سایر">سایر</option>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">برند RTU</label>
                <select name="equipments[{{ $index }}][rtu_brand]" class="form-control rtu-brand-select">
                    <option value="">انتخاب کنید</option>
                    <option value="PNC">PNC</option>
                    <option value="حافظ">حافظ</option>
                    <option value="اشنایدر">اشنایدر</option>
                    <option value="PPEP">PPEP</option>
                    <option value="Fanox">Fanox</option>
                    <option value="سایر">سایر</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">فیدرها</label>
                <select name="equipments[{{ $index }}][feeders][]" class="form-control feeders-select" multiple>
                    @foreach($feeders ?? [] as $feeder)
                        <option value="{{ $feeder->id }}">{{ $feeder->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label required">زمان شروع</label>
                <input type="time" name="equipments[{{ $index }}][start_time]" class="form-control" value="08:00" required>
            </div>
            <div class="col-md-6">
                <label class="form-label required">زمان پایان</label>
                <input type="time" name="equipments[{{ $index }}][end_time]" class="form-control" value="16:00" required>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('.equipment-type-select').select2({
            placeholder: 'انتخاب کنید',
            allowClear: true,
            width: '100%',
            dir: 'rtl'
        });
        
        $('.post-select, .switch-brand-select, .modem-brand-select, .rtu-brand-select').select2({
            placeholder: 'انتخاب کنید',
            allowClear: true,
            width: '100%',
            dir: 'rtl'
        });
        
        $('.feeders-select').select2({
            placeholder: 'انتخاب کنید',
            allowClear: true,
            width: '100%',
            dir: 'rtl',
            multiple: true
        });
    });
</script>
@endpush