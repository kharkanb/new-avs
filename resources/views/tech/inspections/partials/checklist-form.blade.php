<div class="checklist-section card mb-3">
    <div class="card-header bg-info text-white">
        <h6 class="mb-0"><i class="bi bi-clipboard-check"></i> چک‌لیست بازدید</h6>
    </div>
    <div class="card-body">
        @php
            $checklistItems = [
                'وضعیت ظاهری تابلو کنترل',
                'وضعیت برق ورودی و اتصال PT',
                'وضعیت فیوزهای تابلو کنترل',
                'وضعیت اتصال کابل دیتا',
                'وضعیت باتری تابلو کنترل',
                'وضعیت سیم‌بندی داخل تابلو',
                'وضعیت نصب مودم',
                'وضعیت اتصال کابل تغذیه مودم',
                'بررسی ولتاژ خروجی DC',
                'وضعیت اتصال کابل آنتن',
                'وضعیت نصب آنتن مخابراتی',
                'وضعیت سیگنال ارتباطی',
                'وضعیت ارت تابلو کنترل',
                'وضعیت نظافت و تمیزی',
                'وضعیت ارسال به SCADA'
            ];
        @endphp

        @foreach($checklistItems as $index => $item)
        <div class="checklist-item mb-3 p-2 border rounded" id="checklist-item-{{ $index }}">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <p class="mb-0">{{ $index + 1 }}. {{ $item }}</p>
                </div>
                <div class="col-md-5">
                    <div class="btn-group w-100" role="group">
                        <button type="button" 
                                class="btn btn-outline-success btn-sm status-ok" 
                                onclick="setChecklistStatus(this, 'OK', {{ $index }})">
                            <i class="bi bi-check-circle"></i> OK
                        </button>
                        <button type="button" 
                                class="btn btn-outline-danger btn-sm status-not-ok" 
                                onclick="setChecklistStatus(this, 'Not OK', {{ $index }})">
                            <i class="bi bi-x-circle"></i> Not OK
                        </button>
                    </div>
                </div>
            </div>
            <div class="row mt-2 checklist-description" id="checklist-desc-{{ $index }}" style="display: none;">
                <div class="col-md-12">
                    <label class="form-label">توضیحات و اقدامات اصلاحی:</label>
                    <textarea class="form-control form-control-sm" rows="2" 
                              name="checklist[{{ $index }}][description]"
                              placeholder="توضیحات لازم را وارد کنید..."></textarea>
                </div>
            </div>
            <input type="hidden" name="checklist[{{ $index }}][item]" value="{{ $item }}">
            <input type="hidden" name="checklist[{{ $index }}][status]" class="checklist-status" value="">
        </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
function setChecklistStatus(btn, status, index) {
    const item = document.getElementById(`checklist-item-${index}`);
    const okBtn = item.querySelector('.status-ok');
    const notOkBtn = item.querySelector('.status-not-ok');
    const descDiv = document.getElementById(`checklist-desc-${index}`);
    const statusInput = item.querySelector('.checklist-status');
    
    if (status === 'OK') {
        okBtn.classList.remove('btn-outline-success');
        okBtn.classList.add('btn-success');
        notOkBtn.classList.remove('btn-danger');
        notOkBtn.classList.add('btn-outline-danger');
        descDiv.style.display = 'none';
        statusInput.value = 'OK';
    } else {
        notOkBtn.classList.remove('btn-outline-danger');
        notOkBtn.classList.add('btn-danger');
        okBtn.classList.remove('btn-success');
        okBtn.classList.add('btn-outline-success');
        descDiv.style.display = 'block';
        statusInput.value = 'Not OK';
    }
}
</script>
@endpush