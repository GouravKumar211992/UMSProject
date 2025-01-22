<h1 class="text-center mb-1" id="shareProjectTitle">Wastage Details</h1>
<p class="text-center">Enter the details below.</p>
<div class="row">
    <div class="col-md-12 mb-1">
        <label class="form-label">Wastage% <span class="text-danger">*</span></label>
        <input type="number" {{intval($bom->header_waste_perc) ? '' : 'readonly'}} value="{{$bom->header_waste_perc ?? ''}}" name="waste_perc" class="form-control" placeholder="Enter Value" step="any">
    </div>
    <div class="col-md-12 mb-1">
        <label class="form-label">Wastage Value <span class="text-danger">*</span></label>
        <input {{intval($bom->header_waste_perc) ? 'readonly' : ''}} value="{{$bom->header_waste_amount ?? ''}}" type="number" name="waste_amount" class="form-control" placeholder="Enter Value" step="any">
    </div>
</div>