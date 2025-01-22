@if(isset($prevSelectedData[0]) && $prevSelectedData[0]['data'])
   @foreach($prevSelectedData[0]['data'] as $key => $amount)
   @php
   if(isset($prevSelectedData[0]) && $prevSelectedData[1]['key'] == 'description') {
      $description = $prevSelectedData[1]['data'][$key];
   }
   if(isset($prevSelectedData[0]) && $prevSelectedData[2]['key'] == 'leadger') {
      $leadger = $prevSelectedData[2]['data'][$key];
   }
   if(isset($prevSelectedData[0]) && $prevSelectedData[3]['key'] == 'id') {
      $id = $prevSelectedData[3]['data'][$key];
   }
   $key = $key+1;
   @endphp
      <tr class="display_overhead_row">
         <td>{{$key}}</td>
         <td>
         <input type="hidden" class="form-control mw-100" name="components[{{$rowCount}}][overhead][{{$key}}][id]" value="{{@$id}}">
            <input type="hidden" name="row_count_hidden" value="{{$rowCount}}"><input type="text" class="form-control mw-100" name="components[{{$rowCount}}][overhead][{{$key}}][description]" value="{{@$description}}">
         </td>
         <td><input type="number" class="form-control mw-100" name="components[{{$rowCount}}][overhead][{{$key}}][amnt]" value="{{@$amount}}" step="any"></td>
         <td>
            <input type="text" placeholder="Select" class="form-control mw-100 ledgerselecct" name="components[{{$rowCount}}][overhead][{{$key}}][leadger]" value="{{@$leadger}}" />
            <input type="hidden" name="components[{{$rowCount}}][overhead][{{$key}}][leadger_id]">
         </td>
         <td>
           <a href="javascript:;" data-id="{{@$id}}" data-index="{{$key}}" class="text-danger deleteOverHeadItem"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>
         </td>
      </tr>
   @endforeach
@else
<tr class="display_overhead_row">
   <td>1</td>
   <td><input type="hidden" name="row_count_hidden" value="1"><input type="text" class="form-control mw-100" name="components[{{$rowCount}}][overhead][1][description]"></td>
   <td><input type="number" step="any" class="form-control mw-100" name="components[{{$rowCount}}][overhead][1][amnt]"></td>
   <td>
      <input type="text" placeholder="Select" class="form-control mw-100 ledgerselecct" name="components[{{$rowCount}}][overhead][1][leadger]"  />
      <input type="hidden" name="components[{{$rowCount}}][overhead][1][leadger_id]">
   </td>
   <td>
     <a href="javascript:;" data-index="1" class="text-danger deleteOverHeadItem"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>
   </td>
</tr>
@endif