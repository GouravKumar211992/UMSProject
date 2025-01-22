@forelse($mrnItems as $mrnItem)
    <tr>
        <td>
            <div class="form-check form-check-inline me-0">
                <input class="form-check-input po_item_checkbox" type="checkbox" name="po_item_check" value="{{$mrnItem->id}}">
            </div> 
        </td>
        <td class="fw-bolder text-dark">
            {{$mrnItem?->mrnHeader?->vendor_code ?? 'NA'}} {{$mrnItem?->mrnHeader?->type ?? 'NA'}}
        </td>
        <td class="fw-bolder text-dark">
            {{$mrnItem?->mrnHeader?->vendor->display_name ?? 'NA'}}
        </td>
        <td>
            {{$mrnItem->mrnHeader?->book?->book_name ?? 'NA'}}
        </td>
        <td>
            {{$mrnItem->mrnHeader?->document_number ?? 'NA'}}
        </td>
        <td>
            {{$mrnItem->mrnHeader?->document_date ?? 'NA'}}
        </td>
        <td>
            {{$mrnItem->item_code ?? 'NA'}}
        </td>
        <td>
            {{$mrnItem?->item?->item_name}}
        </td>
        <td>
            {{number_format($mrnItem->accepted_qty, 2)}}
        </td>
        <td>
            {{number_format($mrnItem->rejected_qty, 2)}}
        </td>
        <td>
            {{number_format($mrnItem->pr_qty, 2)}}
        </td>
        @if($qtyTypeRequired && ($qtyTypeRequired == 'rejected'))
            <td>
                {{ number_format(($mrnItem->rejected_qty ?? 0) - ($mrnItem->pr_qty ?? 0), 2) }}
            </td>
        @else 
            <td>
                {{ number_format(($mrnItem->accepted_qty ?? 0) - ($mrnItem->pr_qty ?? 0), 2) }}
            </td>
        @endif
        <td>
            {{number_format($mrnItem->rate, 2)}}
        </td>
        @if($qtyTypeRequired && ($qtyTypeRequired == 'rejected'))
            <td>
                {{ number_format(($mrnItem->rejected_qty ?? 0)*($mrnItem->rate ?? 0), 2) }}
            </td>
        @else 
            <td>
                {{ number_format(($mrnItem->accepted_qty ?? 0)*($mrnItem->rate ?? 0), 2) }}
            </td>
        @endif
    </tr>
@empty
    <tr>
        <td colspan="13" class="text-center">No record found!</td>
    </tr>
@endforelse