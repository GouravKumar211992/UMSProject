@if(isset($bom))
@foreach($bom->bomOverheadItems as $bomOverKey => $bomOverheadItem)
<tr class="display_overhead_summary_row">
    <td>{{$bomOverKey+1}}</td>
    <td>{{$bomOverheadItem->overhead_description}}
    <input type="hidden" value="{{$bomOverheadItem->id}}"  name="overhead[{{$bomOverKey+1}}][id]"></td>
    <input type="hidden" value="{{$bomOverheadItem->overhead_description}}" name="overhead[{{$bomOverKey+1}}][description]"></td>
    <td class="text-end">{{$bomOverheadItem->overhead_amount}}
    <input type="hidden" value="{{$bomOverheadItem->overhead_amount}}" name="overhead[{{$bomOverKey+1}}][amnt]"></td>
    <td>{{$bomOverheadItem->ledger_name}}
        <input type="hidden" value="{{$bomOverheadItem->ledger_name}}" name="overhead[{{$bomOverKey+1}}][leadger]" />
        <input type="hidden" name="overhead[{{$bomOverKey+1}}][leadger_id]">
    </td>
    <td>
    <a href="javascript:;" data-id="{{$bomOverheadItem->id}}" class="text-danger deleteOverHeadSummary"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>
    </td>
</tr>
@endforeach
@endif
<tr id="overheadSummaryFooter">
    <td colspan="1"></td>
    <td class="text-dark"><strong>Total</strong></td>
    <td class="text-dark text-end"><strong id="total" amount="{{@$bom->header_overhead_amount}}">{{number_format(@$bom->header_overhead_amount,2)}}</strong></td>
    <td></td>
</tr>