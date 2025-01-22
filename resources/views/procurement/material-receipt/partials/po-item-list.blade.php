@forelse($poItems as $poItem)
    <tr>
        <td>
            <div class="form-check form-check-inline me-0">
                <input class="form-check-input po_item_checkbox" type="checkbox" name="po_item_check" value="{{$poItem->id}}">
            </div> 
        </td>
        <!-- <td class="fw-bolder text-dark">
            {{$poItem?->po?->vendor_code ?? 'NA'}} {{$poItem?->po?->type ?? 'NA'}}
        </td> -->
        <td class="fw-bolder text-dark">
            {{$poItem?->po?->vendor->display_name ?? 'NA'}}
        </td>
        @if(isset($poItem->po->type) && ($poItem->po->type == 'supplier-invoice'))   
            <td>
                {{$poItem->po_item?->po?->book?->book_name ?? 'NA'}}
            </td>
            <td>
                {{$poItem->po_item?->po?->document_number ?? 'NA'}}
            </td>
            <td>
                {{$poItem->po_item?->po?->document_date ?? 'NA'}}
            </td>
            <td>
                {{$poItem->po?->book?->book_name ?? 'NA'}}
            </td>
            <td>
                {{$poItem->po?->document_number ?? 'NA'}}
            </td>
            <td>
                {{$poItem->po?->document_date ?? 'NA'}}
            </td>
            <td>
                {{$poItem?->item?->item_name}}[{{$poItem->item_code ?? 'NA'}}]
            </td>
            <td>
                @foreach($poItem?->attributes as $index => $attribute)
                    <span class="badge rounded-pill badge-light-primary">
                        <strong data-group-id="{{$attribute->headerAttribute->id}}">
                            {{$attribute->headerAttribute->name}}
                        </strong>: 
                        {{ $attribute->headerAttributeValue->value }}
                    </span>
                @endforeach
            </td>
            <td>
                {{$poItem->po_item?->order_qty}}
            </td>
            <td>
                {{$poItem->order_qty}}
            </td>
            <td>
                {{$poItem->grn_qty}}
            </td>
            <td>
                {{ number_format(($poItem->order_qty ?? 0) - ($poItem->grn_qty ?? 0), 2) }}
            </td>
            <td>
                {{$poItem->rate}}
            </td>
            <td>
                {{ number_format((($poItem->order_qty - $poItem->grn_qty)* $poItem->rate), 2) }}
            </td>
        @else 
            <td>
                {{$poItem->po?->book?->book_name ?? 'NA'}}
            </td>
            <td>
                {{$poItem->po?->document_number ?? 'NA'}}
            </td>
            <td>
                {{$poItem->po?->document_date ?? 'NA'}}
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td>
                {{$poItem?->item?->item_name}}[{{$poItem->item_code ?? 'NA'}}]
            </td>
            <td>
                @foreach($poItem?->attributes as $index => $attribute)
                    <span class="badge rounded-pill badge-light-primary">
                        <strong data-group-id="{{$attribute->headerAttribute->id}}">
                            {{$attribute->headerAttribute->name}}
                        </strong>: 
                        {{ $attribute->headerAttributeValue->value }}
                    </span>
                @endforeach
            </td>
            <td>
                {{$poItem->order_qty}}
            </td>
            <td></td>
            <td>
                {{$poItem->grn_qty}}
            </td>
            <td>
                {{ number_format(($poItem->order_qty ?? 0) - ($poItem->grn_qty ?? 0), 2) }}
            </td>
            <td>
                {{$poItem->rate}}
            </td>
            <td>
                {{ number_format((($poItem->order_qty - $poItem->grn_qty)* $poItem->rate), 2) }}
            </td>
        @endif
    </tr>
@empty
    <tr>
        <td colspan="16" class="text-center">No record found!</td>
    </tr>
@endforelse