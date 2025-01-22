@if($specifications->count())
<div class="row heaer_item">
   <div class="col-md-12">
       <div class="newheader border-bottom pb-50 mb-1"> 
           <h4 class="card-title text-theme">Specifications</h4> 
       </div>
   </div>
</div>
<div class="row align-items-center mb-2 heaer_item">
   @foreach($specifications as $specification)
   <div class="col-md-3"> 
       <label class="form-label">{{$specification->specification_name ?? 'NA'}} <span class="text-danger">*</span></label>
       <input type="text" value="{{$specification->value ?? ''}}" class="form-control" readonly="">
   </div> 
   @endforeach
</div> 
@endif
@if($item?->itemAttributes->count())
<div class="row heaer_item">
   <div class="col-md-12">
       <div class="newheader border-bottom pb-50 mb-1"> 
           <h4 class="card-title text-theme">Attributes</h4> 
       </div>
   </div>
</div>
<div class="row align-items-center mb-1 heaer_item header_attr">
@php
   $itemAttIds = $bom->bomAttributes()->pluck('item_attribute_id')->toArray();
   $itemAttributes = $item?->itemAttributes()->whereIn('id',$itemAttIds)->get();
@endphp
@foreach($itemAttributes as $index => $attribute)
@php
$headerAttribute = $bom->bomAttributes()->where('attribute_name',$attribute->attribute_group_id)->first(); 
@endphp
@if(isset($headerAttribute)) 
<input type="hidden" name="attributes[{{$index + 1 }}][attr_group_id][{{$headerAttribute->attribute_name}}][attr_id]" value="{{$headerAttribute->id}}">
@endif
   <div class="col-md-3"> 
      <label class="form-label">{{$attribute->attributeGroup->name}} <span class="text-danger">*</span></label>  
      <input type="hidden" name="attributes[{{ $index + 1 }}][attr_group_id][{{$attribute->attribute_group_id}}][attr_group_id]" value="{{$attribute->attributeGroup->id}}">
      <select class="form-select" name="attributes[{{ $index + 1 }}][attr_group_id][{{$attribute->attribute_group_id}}][attr_name]">
         <option value="">Select</option>
         @foreach ($attribute->attributeGroup->attributes as $value)
         @if(in_array($value->id, $selectedAttributes))
            <option value="{{ $value->id }}" selected>
                {{ $value->value }}
            </option>
         @else
         <option value="{{ $value->id }}">
                {{ $value->value }}
            </option>
         @endif
         @endforeach
      </select>
   </div>
@endforeach
</div>
@endif