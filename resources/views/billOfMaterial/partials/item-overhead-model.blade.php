<div class="modal fade text-start" id="overheadItemPopup" tabindex="-1" aria-labelledby="myModalLabel17" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" style="max-width: 1000px">
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
                   <input type="text" name="item_overhead_description" id="item_overhead_description" class="form-control mw-100" />
                </td>
                <td>
                   <input step="any" type="number" name="item_overhead_amount" id="item_overhead_amount" class="form-control mw-100" />
                </td>
                <td>
                  <input type="text" placeholder="Select" class="form-control mw-100 ledgerselecct" name="item_overhead_ledger" id="item_overhead_ledger" />
                  <input type="hidden" id="item_overhead_ledger_id" name="item_overhead_ledger_id" />
               </td>
               <td>
                <a href="javascript:;" id="add_new_item_overhead" class="text-primary can_hide">
                  <i data-feather="plus-square"></i>
               </a>
            </td>
         </tr>
      </thead>
      <tbody>
      </tbody>
   </table>
   <div class="table-responsive-md">
      <table id="itemOverheadTbl" class="mt-1 table myrequesttablecbox table-striped po-order-detail custnewpo-detail border">
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

         <tr id="itemOverheadSummaryFooter">
            <input type="hidden" name="row_count" value="">
             <td colspan="1"></td>
             <td class="text-dark"><strong>Total</strong></td>
             <td class="text-dark text-end"><strong id="total"></strong></td>
             <td></td>
         </tr>
         </tbody>
      </table>
   </div>
</div>
<div class="modal-footer text-end">
   <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal"><i data-feather="x-circle"></i> Cancel</button>
   <button type="button" class="btn btn-primary btn-sm overheadItemBtn"><i data-feather="check-circle"></i> Submit</button>
</div>
</div>
</div>
</div>