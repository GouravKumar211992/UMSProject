@if(isset($summary_row) && $summary_row != false)
<tr class="display_overhead_summary_row">
   <td>{{$tblRowCount}}</td>
   <td>
      <input type="hidden" name="row_count_hidden" value="{{$rowCount}}">
      <input type="text" class="form-control mw-100" name="overhead[{{$tblRowCount}}][description]">
   </td>
   <td><input type="number" step="any" class="form-control mw-100" name="overhead[{{$tblRowCount}}][amnt]">
   </td>
   <td>
      <input type="text" placeholder="Select" class="form-control mw-100 ledgerselecct" name="overhead[{{$tblRowCount}}][leadger]" />
      <input type="hidden" name="overhead[{{$tblRowCount}}][leadger_id]">
   </td>
   <td>
     <a href="javascript:;" class="text-danger deleteOverHeadSummary"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>
   </td>
</tr>
@else
<tr class="display_overhead_row">
   <td>{{$tblRowCount}}</td>
   <td>
      <input type="hidden" name="row_count_hidden" value="{{$rowCount}}">
      <input type="text" class="form-control mw-100" name="components[{{$rowCount}}][overhead][{{$tblRowCount}}][description]">
   </td>
   <td><input type="number" class="form-control mw-100" name="components[{{$rowCount}}][overhead][{{$tblRowCount}}][amnt]" step="any">
   </td>
   <td>
      <input type="text" placeholder="Select" class="form-control mw-100 ledgerselecct" name="components[{{$rowCount}}][overhead][{{$tblRowCount}}][leadger]" />
      <input type="hidden" name="components[{{$rowCount}}][overhead][{{$tblRowCount}}][leadger_id]">
   </td>
   <td>
     <a href="javascript:;" data-index="{{$tblRowCount}}" class="text-danger deleteOverHeadItem"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>
   </td>
</tr>
@endif