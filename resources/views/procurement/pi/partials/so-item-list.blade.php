@forelse($soItems as $soItem)
    @php
    // $isRow = false;
    // $a = [];
    // foreach($soItem->attributes as $soAttribute) {
    //     $a[] = ['attribute_id' => $soAttribute->item_attribute_id, 'attribute_value' => $soAttribute->attr_value];
    // }
    // $checkBomExitHere = \App\Helpers\ItemHelper::checkItemBomExists($soItem->item_id, $a);
    // if(in_array($checkBomExitHere['status'],['bom_exists','bom_not_required'])) {
        $isRow = true;
        $attributes = $soItem->attributes;
        $html = '';
        foreach($attributes as $attribute) {
        $attr = \App\Models\AttributeGroup::where('id', @$attribute->attr_name)->first();
        $attrValue = \App\Models\Attribute::where('id', @$attribute->attr_value)->first();
            if ($attr && $attrValue) { 
                $html .= "<span class='badge rounded-pill badge-light-primary'><strong>{$attr->name}</strong>: {$attrValue->value}</span>";
            } else {
                $html .= "<span class='badge rounded-pill badge-light-secondary'><strong>Attribute not found</strong></span>";
            }
        }
    // }
    @endphp
    @if($isRow)
    <tr>
        <td>
            <div class="form-check form-check-inline me-0">
                <input class="form-check-input pi_item_checkbox" type="checkbox" name="so_item_id" value="{{$soItem->id}}">
            </div> 
        </td>   
        <td>{{$soItem->header?->book_code ?? 'NA'}}</td>
        <td>{{$soItem->header?->document_number ?? 'NA'}}</td>
        <td>{{$soItem->header?->getFormattedDate('document_date')  ?? 'NA'}}</td>
        <td>{{$soItem->item_code ?? 'NA'}}</td>
        <td>{{$soItem->item_name ?? 'NA'}}</td>
        <td>{!! $html ? $html : 'NA' !!}</td>
        <td>{{$soItem->order_qty}}</td>
        <td class="fw-bolder text-dark">{{$soItem?->header?->customer_code ?? 'NA'}}</td>
        {{-- <td>{{$soItem->bomHeader ? 'YES' : 'NO'}}</td> --}}
    </tr>
    @endif
@empty
    <tr>
        <td colspan="9" class="text-center">No record found!</td>
    </tr>
@endforelse