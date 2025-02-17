@foreach($bom->bomItems as $key => $bomDetail)
@php
   $rowCount = $key + 1;
   $selectedAttributes = array_map(function ($item) {
       return ['attr_id' => $item['attribute_name'], 'attr_value' => $item['attribute_value']];
   }, $bomDetail->attributes?->toArray() ?? []);
   $cost = \App\Helpers\Helper::getChildBomItemCost($bomDetail->item_id,$selectedAttributes);

   $itemCost = $cost['cost'];
   if(!floatval($itemCost)) {
      $uomId = $request->uom_id ?? $bomDetail->uom_id ?? null;
      $currency =  \App\Helpers\CurrencyHelper::getOrganizationCurrency();
      $currencyId = $currency->id ?? null; 
      $transactionDate = $bom->document_date ?? date('Y-m-d');
      if($bom->type == \App\Helpers\ConstantHelper::BOM_SERVICE_ALIAS) {
         $itemCost = \App\Helpers\ItemHelper::getItemCostPrice($bomDetail->item_id, $selectedAttributes, $bomDetail->uom_id, $currencyId, $transactionDate);
      } else {
         $itemCost = \App\Helpers\ItemHelper::getItemSalePrice($bomDetail->item_id, $selectedAttributes, $bomDetail->uom_id, $currencyId, $transactionDate);
      }
   }
@endphp
<tr id="row_{{$rowCount}}" data-index="{{$rowCount}}" @if($rowCount < 2 ) class="trselected" @endif>
   <input type="hidden" name="components[{{$rowCount}}][bom_detail_id]" value="{{$bomDetail->id}}">
   <td class="customernewsection-form">
      <div class="form-check form-check-primary custom-checkbox">
         <input type="checkbox" class="form-check-input" id="Email_{{$rowCount}}" data-id="{{$bomDetail->id}}" value="{{$rowCount}}">
         <label class="form-check-label" for="Email_{{$rowCount}}"></label>
      </div>
   </td>
   <td>
      <input type="text" placeholder="Select" class="form-control mw-100 ledgerselecct" name="product_section" value="{{$bomDetail?->section_name}}" />
      <input type="hidden" name="components[{{$rowCount}}][section_id]" value="{{$bomDetail?->subSection?->section_id}}">
      <input type="hidden" name="components[{{$rowCount}}][section_name]" value="{{$bomDetail?->section_name}}">
   </td>
   <td>
      <input type="text" placeholder="Select" class="form-control mw-100 ledgerselecct" name="product_sub_section" value="{{$bomDetail?->sub_section_name}}" />
      <input type="hidden" name="components[{{$rowCount}}][sub_section_id]" value="{{$bomDetail?->sub_section_id}}">
      <input type="hidden" name="components[{{$rowCount}}][sub_section_name]" value="{{$bomDetail?->sub_section_name}}">
   </td>
   <td>
      <input type="text" name="component_item_name[{{$rowCount}}]" placeholder="Select" class="form-control mw-100 ledgerselecct comp_item_code" value="{{$bomDetail->item?->item_code}}" />
      <input type="hidden" name="components[{{$rowCount}}][item_id]" value="{{$bomDetail->item?->id}}" />
      <input type="hidden" name="components[{{$rowCount}}][item_code]" value="{{$bomDetail->item?->item_code}}" />
      @php
      $selectedAttr = $bomDetail->attributes ? $bomDetail->attributes()->pluck('attribute_value')->all() : []; 
      @endphp
      @foreach($bomDetail->attributes as $attributeHidden)
         <input type="hidden" name="components[{{$rowCount}}][attr_group_id][{{$attributeHidden->attribute_name}}][attr_id]" value="{{$attributeHidden->id}}">
      @endforeach
      @foreach($bomDetail->item?->itemAttributes as $itemAttribute)
         @foreach ($itemAttribute->attributes() as $value)
            @if(in_array($value->id, $selectedAttr))
            <input type="hidden" name="components[{{$rowCount}}][attr_group_id][{{$itemAttribute->attribute_group_id}}][attr_name]" value="{{$value->id}}">
            @endif
         @endforeach
      @endforeach
   </td>
   <td class="poprod-decpt"> 
      <button type="button" {{-- data-bs-toggle="modal" data-bs-target="#attribute" --}} class="btn p-25 btn-sm btn-outline-secondary attributeBtn" data-row-count="{{$rowCount}}" style="font-size: 10px">Attributes</button>
   </td>
   <td>
      <select class="form-select mw-100 " name="components[{{$rowCount}}][uom_id]">
         <option value="{{$bomDetail->uom?->id}}">{{ucfirst($bomDetail->uom?->name)}}</option>
      </select>
   </td>
   <td>
      <div class="position-relative d-flex align-items-center">
      <input @readonly(true) type="number" class="form-control mw-100 text-end" value="{{$bomDetail->qty}}"  name="components[{{$rowCount}}][qty]" step="any"/>
      @if($bomDetail?->norm)
         <input type="hidden" name="components[{{$rowCount}}][qty_per_unit]" value="{{$bomDetail?->norm?->qty_per_unit}}">
         <input type="hidden" name="components[{{$rowCount}}][total_qty]" value="{{$bomDetail?->norm?->total_qty}}">
         <input type="hidden" name="components[{{$rowCount}}][std_qty]" value="{{$bomDetail?->norm?->std_qty}}">
      @endif
      <div class="ms-50 consumption_btn">
         <button type="button" data-row-count="{{$rowCount}}" class="btn p-25 btn-sm btn-outline-secondary addConsumptionBtn" style="font-size: 10px">F</button>
      </div>
   </div>
   </td>
   <td><input type="number" value="{{isset($itemCost) ? $itemCost : '' }}" name="components[{{$rowCount}}][item_cost]" readonly class="form-control mw-100 text-end" step="any" /></td>
   <td>
      <input type="number" value="{{$bomDetail->superceeded_cost}}" name="components[{{$rowCount}}][superceeded_cost]" class="form-control mw-100 text-end" step="any"/>
   </td>
   <td>
      <input type="number" value="{{$bomDetail->item_value}}" name="components[{{$rowCount}}][item_value]" class="form-control mw-100 text-end" readonly step="any" />
   </td>
   <td>
      <input type="number" value="{{$bomDetail->waste_perc}}" name="components[{{$rowCount}}][waste_perc]" class="form-control mw-100 text-end" step="any" />
      {{-- <select class="form-select mw-100" name="components[{{$rowCount}}][waste_type]">
         @foreach($wasteTypes as $wasteType)
         <option value="{{$wasteType}}">{{$wasteType}}</option>
         @endforeach
      </select> --}}
   </td>
   <td>
      <input type="number" value="{{$bomDetail->waste_amount ?? ''}}" name="components[{{$rowCount}}][waste_amount]" class="form-control mw-100 text-end" step="any" />
   </td>
   <td>
      <div class="position-relative d-flex align-items-center">
         <input type="number" value="{{$bomDetail->overhead_amount ?? ''}}" name="components[{{$rowCount}}][overhead_amount]" readonly class="form-control mw-100 text-end" style="width: 70px" step="any" />
         <div class="ms-50">
            <button type="button" class="btn p-25 btn-sm btn-outline-secondary addOverHeadItemBtn" style="font-size: 10px" data-row-count="{{$rowCount}}">Add</button>
         </div>
      </div>
      @foreach($bomDetail->overheads()->where('type','D')->get() as $over_key => $overhead)
      <input type="hidden" name="components[{{$rowCount}}][overhead][{{$over_key+1}}][id]" value="{{$overhead->id}}">
      <input type="hidden" name="components[{{$rowCount}}][overhead][{{$over_key+1}}][description]" value="{{$overhead->overhead_description}}">
      <input type="hidden" value="{{$overhead->overhead_amount ?? ''}}" name="components[{{$rowCount}}][overhead][{{$over_key+1}}][amnt]">
      <input type="hidden" value="{{$overhead->ledger_name}}" name="components[{{$rowCount}}][overhead][{{$over_key+1}}][leadger]">
      <input type="hidden" name="components[{{$rowCount}}][overhead][{{$over_key+1}}][leadger_id]">
      @endforeach
   </td>
   <td>  
         <input type="text" value="{{$bomDetail->total_amount}}" name="components[{{$rowCount}}][item_total_cost]" readonly class="form-control mw-100 text-end" />
         <input type="hidden" name="components[{{$rowCount}}][remark]" value="{{$bomDetail->remark}}" />
   </td>
   <td>
      <div class="d-flex align-items-center justify-content-center">
         <input type="text" placeholder="Select" class="form-control mw-100 ledgerselecct" name="product_station" value="{{$bomDetail?->station_name}}" />
         <input type="hidden" name="components[{{$rowCount}}][station_id]" value="{{$bomDetail?->station_id}}">
         <input type="hidden" name="components[{{$rowCount}}][station_name]" value="{{$bomDetail?->station_name}}">

         <div class="me-50 mx-1 cursor-pointer addRemarkBtn" data-row-count="{{$rowCount}}">
            <span data-bs-toggle="tooltip" data-bs-placement="top" title="" class="text-primary" data-bs-original-title="Remarks" aria-label="Remarks"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
            </span>
         </div>

         <div class="me-50 cursor-pointer linkAppend {{isset($cost['cost']) && isset($cost['route']) ? '' : 'd-none'}}">
            <a href="{{$cost['route'] ?? ''}}" target="_blank" class="">
               <span data-bs-toggle="tooltip" data-bs-placement="top" title="" class="text-primary" data-bs-original-title="Link" aria-label="Link">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-link">
                    <path d="M10 13a5 5 0 0 1 7 7l-1.5 1.5a5 5 0 0 1-7-7"></path>
                    <path d="M14 11a5 5 0 0 0-7-7l-1.5 1.5a5 5 0 0 0 7 7"></path>
                 </svg>
              </span>
           </a>
        </div>
     </div>
   </td>
</tr>
@endforeach