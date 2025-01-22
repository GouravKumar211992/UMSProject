<div class="modal fade text-start" id="overheadSummaryPopup" tabindex="-1" aria-labelledby="myModalLabel17" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 1000px">
      <div class="modal-content">
         <div class="modal-header p-0 bg-transparent">
             <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <h1 class="text-center mb-1" id="shareProjectTitle">Overhead</h1>
            <div class="text-end">
            </div>
            <table class="mt-1 table myrequesttablecbox table-striped po-order-detail custnewpo-detail"> 
               <thead>
                  <tr>
                      <td>#</td>
                      <td>
                          <input type="text" name="overhead_description" id="overhead_description" class="form-control mw-100" />
                      </td>
                      <td>
                          <input step="any" type="number" name="overhead_amount" id="overhead_amount" class="form-control mw-100" />
                      </td>
                      <td>
                        <input type="text" placeholder="Select" class="form-control mw-100 ledgerselecct" name="overhead_ledger" id="overhead_ledger" />
                        <input type="hidden" id="overhead_ledger_id" name="overhead_ledger_id" />
                      </td>
                      <td>
                          <a href="javascript:;" id="add_new_overhead" class="text-primary can_hide">
                              <i data-feather="plus-square"></i>
                          </a>
                      </td>
                  </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
               <div class="table-responsive-md">
                  <table class="mt-1 table myrequesttablecbox table-striped po-order-detail custnewpo-detail border" id="headerOverheadTbl">
                     <thead>
                        <tr>
                           <th>S.No</th>
                           <th>Description</th>
                           <th>Amount</th>
                           <th width="400px">Leadger</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody>
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
                     </tbody>
                  </table>
               </div>
         </div>
         <div class="modal-footer text-end">
            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal"><i data-feather="x-circle"></i> Cancel</button>
            <button type="button" class="btn btn-primary btn-sm overheadSummaryBtn"><i data-feather="check-circle"></i> Submit</button>
         </div>
      </div>
   </div>
</div>