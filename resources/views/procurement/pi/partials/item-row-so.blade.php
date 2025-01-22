@foreach($soItems as $key => $so_item)
@php
   $rowCount = $key + 1;
   $item = \App\Models\Item::find(@$so_item['item_id']);
   $selectedAttr = [];
   $selAttributes = @$so_item['attributes'] ? json_decode($so_item['attributes'], TRUE) : [];
    if(count($selAttributes)) {
       $selectedAttr = array_column($selAttributes, 'attribute_value');
    }
@endphp
<tr id="row_{{$rowCount}}" data-index="{{$rowCount}}">
    <input type="hidden" name="components[{{$rowCount}}][so_pi_mapping_item_id]" value="{{@$so_item['item_id']}}">
  <td class="customernewsection-form">
      <div class="form-check form-check-primary custom-checkbox">
         <input type="checkbox" class="form-check-input" id="Email_{{$rowCount}}" value="{{$rowCount}}" data-id="{{@$so_item['item_id']}}">
         <label class="form-check-label" for="Email_{{$rowCount}}"></label>
     </div>
 </td>
 <td class="poprod-decpt"> 
    <input @readonly(true) type="text" name="component_item_name[{{$rowCount}}]" placeholder="Select" class="form-control mw-100 mb-25 ledgerselecct comp_item_code " value="{{$item?->item_code}}" />
    <input type="hidden" name="components[{{$rowCount}}][item_id]" value="{{$item?->id}}" />
    <input type="hidden" name="components[{{$rowCount}}][item_code]" value="{{$item?->item_code}}" /> 
    <input type="hidden" name="components[{{$rowCount}}][item_name]" value="{{$item?->name}}" />
    <input type="hidden" name="components[{{$rowCount}}][hsn_id]" value="{{$item?->hsn?->id}}" /> 
    <input type="hidden" name="components[{{$rowCount}}][hsn_code]" value="{{$item?->hsn?->hsn_code}}" />
      @foreach($item?->itemAttributes ?? [] as $itemAttribute)
            @if(count($selectedAttr))
                @foreach ($itemAttribute->attributes() as $value)
                @if(in_array($value->id, $selectedAttr))
                <input type="hidden" name="components[{{$rowCount}}][attr_group_id][{{$itemAttribute?->attribute_group_id}}][attr_name]" value="{{$value?->id}}">
                @endif
                @endforeach
            @else
                <input type="hidden" name="components[{{$rowCount}}][attr_group_id][{{$itemAttribute?->attribute_group_id}}][attr_name]" value="">
            @endif
      @endforeach
</td>
<td>
    <input type="text" name="components[{{$rowCount}}][item_name]" class="form-control mw-100 mb-25" readonly value="{{$item?->item_name}}" />
</td>
<td class="poprod-decpt"> 
    <button type="button" class="btn p-25 btn-sm btn-outline-secondary attributeBtn" data-row-count="{{$rowCount}}" style="font-size: 10px">Attributes</button>
</td>
<td>
    <input type="hidden" name="components[{{$rowCount}}][inventoty_uom_id]" value="" @readonly(true)>
    <select class="form-select mw-100 " name="components[{{$rowCount}}][uom_id]">
        <option value="{{$item?->uom?->id}}">{{ucfirst($item?->uom?->name)}}</option>
        {{-- @foreach($item?->alternateUOMs ?? [] as $alternateUOM)
         <option value="{{$alternateUOM?->uom?->id}}" {{$alternateUOM?->uom?->id == @$item?->inventory_uom_id ? 'selected' : '' }}>{{$alternateUOM?->uom?->name}}</option>
         @endforeach --}}
    </select>
</td>
<td>
    <input type="number" step="any" class="form-control mw-100 text-end"  name="components[{{$rowCount}}][qty]" value="{{@$so_item['total_qty']}}">
</td>
<td>
    <input type="hidden" name="components[{{$rowCount}}][vendor_id]" />
    <input type="text" placeholder="Select" class="form-control mw-100 ledgerselecct" name="components[{{$rowCount}}][vendor_code]" />
</td>
<td><input type="text" class="form-control mw-100" name="components[{{$rowCount}}][vendor_name]" readonly/></td>
<td>
   <div class="d-flex">
    <div class="me-50 cursor-pointer addDeliveryScheduleBtn" data-row-count="{{$rowCount}}"{{--  data-bs-toggle="modal" data-bs-target="#delivery" --}}>    <span data-bs-toggle="tooltip" data-bs-placement="top" title="" class="text-primary" data-bs-original-title="Delivery Schedule" aria-label="Delivery Schedule"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg></span>
    </div>
    <div class="me-50 cursor-pointer addRemarkBtn" data-row-count="{{$rowCount}}" {{-- data-bs-toggle="modal" data-bs-target="#Remarks" --}}>        <span data-bs-toggle="tooltip" data-bs-placement="top" title="" class="text-primary" data-bs-original-title="Remarks" aria-label="Remarks"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg></span></div>
</div>
</td>
</tr>
@endforeach