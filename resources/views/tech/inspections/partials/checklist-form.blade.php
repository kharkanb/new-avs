<div class="checklist-section">
    <h6 class="mb-3"><i class="bi bi-clipboard-check"></i> چک‌لیست بازدید</h6>
    
    @foreach($checklistItems as $index => $item)
    <div class="checklist-item mb-2 p-2 border rounded">
        <div class="row align-items-center">
            <div class="col-md-8">
                <p class="mb-0">{{ $index + 1 }}. {{ $item }}</p>
            </div>
            <div class="col-md-4">
                <div class="btn-group w-100">
                    <button type="button" class="btn btn-outline-success checklist-ok" onclick="setChecklistStatus(this, 'OK')">
                        <i class="bi bi-check-circle"></i> OK
                    </button>
                    <button type="button" class="btn btn-outline-danger checklist-not-ok" onclick="setChecklistStatus(this, 'Not OK')">
                        <i class="bi bi-x-circle"></i> Not OK
                    </button>
                </div>
            </div>
        </div>
        <div class="row mt-2 checklist-description" style="display: none;">
            <div class="col-md-12">
                <textarea class="form-control" rows="2" placeholder="توضیحات..."></textarea>
            </div>
        </div>
    </div>
    @endforeach
</div>