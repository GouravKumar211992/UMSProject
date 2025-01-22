@forelse($soProcessItems as $soProcessItem)
@php
$attributes = json_decode($soProcessItem->attributes, true);
$html = '';
foreach($attributes as $attribute) {
$attr = \App\Models\ItemAttribute::where('id', @$attribute['attribute_id'])->first();
$attrValue = \App\Models\Attribute::where('id', @$attribute['attribute_value'])->first();
    if ($attr && $attrValue) { 
        $html .= "<span class='badge rounded-pill badge-light-primary'><strong>{$attr?->attributeGroup?->name}</strong>: {$attrValue->value}</span>";
    } else {
        $html .= "<span class='badge rounded-pill badge-light-secondary'><strong>Attribute not found</strong></span>";
    }
}
@endphp
<tr>
    <td>
        <div class="form-check form-check-inline me-0">
            <input class="form-check-input pi_item_checkbox" type="checkbox" name="pi_item_check" value="{{$soProcessItem->item_id}}" data-item="{{json_encode($soProcessItem)}}">

        </div> 
    </td>   
    <td>{{ $soProcessItem?->item?->item_code ?? 'NA'}}</td>
    <td>{{ $soProcessItem?->item?->item_name ?? 'NA'}}</td>
    <td>{!!  $html ?? 'NA' !!}</td>
    <td>{{  $soProcessItem?->item?->uom?->name ?? 'NA'}}</td>
    <td class="text-end">{{floatval($soProcessItem->total_qty)}}</td>
</tr>
@empty
<tr>
    <td colspan="6" class="text-center">No record found!</td>
</tr>
@endforelse