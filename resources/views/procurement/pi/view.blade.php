@extends('layouts.app')
@section('content')
<form class="ajax-input-form" action="{{ route('pi.update', $pi->id) }}" method="POST" data-redirect="/purchase-indent" enctype="multipart/form-data">
@csrf
<div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header pocreate-sticky">
                <div class="row">
                    <div class="content-header-left col-md-6 mb-2">
                        <div class="row breadcrumbs-top">
                            <div class="col-12">
                                <h2 class="content-header-title float-start mb-0">Purchase Indent</h2>
                                <div class="breadcrumb-wrapper">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.html">Home</a>
                                        </li>
                                        <li class="breadcrumb-item active">Edit</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="content-header-right text-sm-end col-md-6 mb-50 mb-sm-0">
                        <div class="form-group breadcrumb-right">
                        <input type="hidden" name="document_status" id="document_status">
                            <button type="button" onClick="javascript: history.go(-1)" class="btn btn-secondary btn-sm mb-50 mb-sm-0"><i data-feather="arrow-left-circle"></i> Back</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card" id="basic_section">
                                <div class="card-body customernewsection-form">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="newheader border-bottom mb-2 pb-25 d-flex flex-wrap justify-content-between">
                                                <div>
                                                    <h4 class="card-title text-theme">Basic Information</h4>
                                                    <p class="card-text">Fill the details</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-sm-end">
                                            <span class="badge rounded-pill badge-light-secondary forminnerstatus">

                                                Status : <span class="{{$docStatusClass}}">{{$pi->display_status}}</span>
                                            </span>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row align-items-center mb-1">
                                                <div class="col-md-3">
                                                    <label class="form-label">Series <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-md-5">
                                                    <select class="form-select" id="book_id" name="book_id">
                                                    <option value="">Select</option>
                                                    @foreach($books as $book)
                                                      <option value="{{$book->id}}" {{$book->id == $pi->book_id ? 'selected' : ''}}>{{ucfirst($book->book_code)}}</option>
                                                    @endforeach
                                                    </select>
                                                    <input type="hidden" name="book_code" id="{{$pi->book->book_code}}" id="book_code">
                                                </div>
                                            </div>

                                            <div class="row align-items-center mb-1">
                                                <div class="col-md-3">
                                                    <label class="form-label">Indent No <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-md-5">
                                                    <input type="text" name="document_number" id="document_number" value="{{$pi->document_number}}" class="form-control">
                                                </div>
                                            </div>

                                            <div class="row align-items-center mb-1">
                                                <div class="col-md-3">
                                                    <label class="form-label">Indent Date <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-md-5">
                                                    <input type="date" class="form-control" value="{{ $pi->document_date }}" name="document_date">
                                                </div>
                                            </div>

                                            {{-- <div class="row align-items-center mb-1">
                                                <div class="col-md-3">
                                                    <label class="form-label">Reference No </label>
                                                </div>
                                                <div class="col-md-5">
                                                    <input type="text" value="{{$pi->reference_number}}" name="reference_number" class="form-control">
                                                </div>
                                            </div> --}}
                                            <div class="row align-items-center mb-1">
                                                <div class="col-md-3"> 
                                                    <label class="form-label">Department </label>  
                                                </div>  
                                                <div class="col-md-5">  
                                                  <input type="hidden" value="{{$pi->department_id}}" name="department_id">
                                                    <select class="form-select" id="department_id" disabled name="department_id">
                                                        <option value="">Select</option>
                                                      @foreach($departments as $department)
                                                      <option value="{{$department->id}}" {{$pi->department_id == $department->id ? 'selected' : ''}}>{{ucfirst($department->name)}}</option>
                                                      @endforeach 
                                                  </select>  
                                              </div>
                                          </div>

                                        </div>
                                        
                                        {{-- Approval History Section --}}
                                        @include('partials.approval-history', ['document_status' => $pi->document_status, 'revision_number' => $revision_number]) 
                                    </div>
                                </div>
                            </div>

                           <div class="card" id="item_section">
                           <div class="card-body customernewsection-form"> 
                            <div class="border-bottom mb-2 pb-25">
                               <div class="row">
                                <div class="col-md-6">
                                    <div class="newheader ">
                                        <h4 class="card-title text-theme">Indent Item Wise Detail</h4>
                                        <p class="card-text">Fill the details</p>
                                    </div>
                                </div>
                                <div class="col-md-6 text-sm-end">
                                    <a href="javascript:;" id="deleteBtn" class="btn btn-sm btn-outline-danger me-50">
                                        <i data-feather="x-circle"></i> Delete</a>
                                        <a href="javascript:;" id="addNewItemBtn" class="btn btn-sm btn-outline-primary">
                                            <i data-feather="plus"></i> Add Item</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                   <div class="col-md-12">
                                       <div class="table-responsive pomrnheadtffotsticky">
                                           <table id="itemTable" class="table myrequesttablecbox table-striped po-order-detail custnewpo-detail border newdesignerptable newdesignpomrnpad">
                                            <thead>
                                            <tr>
                                                <th class="customernewsection-form">
                                                    <div class="form-check form-check-primary custom-checkbox">
                                                        <input type="checkbox" class="form-check-input" id="Email">
                                                        <label class="form-check-label" for="Email"></label>
                                                    </div> 
                                                </th>
                                                <th width="150px">Item Code</th>
                                                <th width="240px">Item Name</th>
                                                <th>Attributes</th>
                                                <th>UOM</th>
                                                <th>Qty</th>
                                                <th width="150px">Preferred Vendor</th>
                                                <th width="240px">Vendor Name</th>
                                                <th width="50px">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="mrntableselectexcel">
                                            @include('procurement.pi.partials.item-row-edit')
                                        </tbody>
                                        <tfoot>
                                        <tr valign="top">
                                            <td colspan="9" rowspan="10">
                                                <table class="table border">
                                                    <tbody id="itemDetailDisplay">
                                                    <tr>
                                                        <td class="p-0">
                                                            <h6 class="text-dark mb-0 bg-light-primary py-1 px-50"><strong>Item Details</strong></h6>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="poprod-decpt">
                                                            <span class="poitemtxt mw-100"><strong>Name</strong>:</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="poprod-decpt">
                                                            <span class="badge rounded-pill badge-light-primary"><strong>HSN</strong>:</span>
                                                            <span class="badge rounded-pill badge-light-primary"><strong>Color</strong>:</span>
                                                            <span class="badge rounded-pill badge-light-primary"><strong>Size</strong>:</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="poprod-decpt">
                                                            <span class="badge rounded-pill badge-light-primary"><strong>Inv. UOM</strong>: </span>
                                                            <span class="badge rounded-pill badge-light-primary"><strong>Qty.</strong>:</span>
                                                            <span class="badge rounded-pill badge-light-primary"><strong>Exp. Date</strong>: </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="poprod-decpt">
                                                            <span class="badge rounded-pill badge-light-secondary text-wrap"><strong>Remarks</strong>: </span>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                   <div class="col-md-4">
                                    <div class="mb-1">
                                        <label class="form-label">Upload Document</label>
                                        <input type="file" name="attachment[]" class="form-control" multiple>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-1">
                                    <label class="form-label">Final Remarks</label>
                                    <textarea maxlength="250" type="text" rows="4" name="remarks" class="form-control" placeholder="Enter Remarks here...">{!! $pi->remarks !!}</textarea>

                                </div>
                            </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

</form>

{{-- Attribute popup --}}
<div class="modal fade" id="attribute" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
 <div class="modal-dialog  modal-dialog-centered">
  <div class="modal-content">
   <div class="modal-header p-0 bg-transparent">
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body px-sm-2 mx-50 pb-2">
    <h1 class="text-center mb-1" id="shareProjectTitle">Select Attribute</h1>
    <p class="text-center">Enter the details below.</p>
    <div class="table-responsive-md customernewsection-form">
     <table class="mt-1 table myrequesttablecbox table-striped po-order-detail custnewpo-detail">
      <thead>
       <tr>
        <th>Attribute Name</th>
        <th>Attribute Value</th>
    </tr>
</thead>
<tbody>

</tbody>
</table>
</div>
</div>
<div class="modal-footer justify-content-center">
    <button type="button" data-bs-dismiss="modal" class="btn btn-outline-secondary me-1">Cancel</button>
    <button type="button" data-bs-dismiss="modal" class="btn btn-primary">Select</button>
</div>
</div>
</div>
</div>

{{-- Delivery schedule --}}
<div class="modal fade" id="deliveryScheduleModal" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered" >
        <div class="modal-content">
            <div class="modal-header p-0 bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-sm-2 mx-50 pb-2">
                <h1 class="text-center mb-1" id="shareProjectTitle">Delivery Schedule</h1>
                {{-- <p class="text-center">Enter the details below.</p> --}}

                <div class="text-end"> <a href="javascript:;" class="text-primary add-contactpeontxt mt-50 addTaxItemRow"><i data-feather='plus'></i> Add Schedule</a></div>

                <div class="table-responsive-md customernewsection-form">
                    <table id="deliveryScheduleTable" class="mt-1 table myrequesttablecbox table-striped po-order-detail custnewpo-detail">
                        <thead>
                           <tr>
                            <th>S.No</th>
                            <th width="150px">Quantity</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr id="deliveryFooter">
                           <td class="text-dark"><strong>Total</strong></td>
                           <td class="text-dark"><strong id="total">0.00</strong></td>
                           <td></td>
                           <td></td>
                        </tr>
                    </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" data-bs-dismiss="modal"  class="btn btn-outline-secondary me-1">Cancel</button>
                <button type="button" class="btn btn-primary itemDeliveryScheduleSubmit">Submit</button>
            </div>
        </div>
    </div>
</div>

{{-- Item Remark Modal --}}
<div class="modal fade" id="itemRemarkModal" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered" >
        <div class="modal-content">
            <div class="modal-header p-0 bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-sm-2 mx-50 pb-2">
                <h1 class="text-center mb-1" id="shareProjectTitle">Remarks</h1>
                {{-- <p class="text-center">Enter the details below.</p> --}}
                <div class="row mt-2">
                    <div class="col-md-12 mb-1">
                        <label class="form-label">Remarks <span class="text-danger">*</span></label>
                        <input type="hidden" name="row_count" id="row_count">
                        <textarea maxlength="250" class="form-control" placeholder="Enter Remarks"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" data-bs-dismiss="modal" class="btn btn-outline-secondary me-1">Cancel</button>
                <button type="button" class="btn btn-primary itemRemarkSubmit">Submit</button>
            </div>
        </div>
    </div>
</div>

{{-- Delete component modal --}}
<div class="modal fade text-start alertbackdropdisabled" id="deleteComponentModal" tabindex="-1" aria-labelledby="myModalLabel1" aria-hidden="true" data-bs-backdrop="false">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header p-0 bg-transparent">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body alertmsg text-center warning">
           <i data-feather='alert-circle'></i>
           <h2>Are you sure?</h2>
           <p>Are you sure you want to delete selected <strong>Components</strong>?</p>
           <button type="button" class="btn btn-secondary me-25" data-bs-dismiss="modal">Cancel</button>
           <button type="button" id="deleteConfirm" class="btn btn-primary" >Confirm</button>
         </div>
      </div>
   </div>
</div>

{{-- Approval Modal --}}
@include('procurement.pi.partials.approve-modal', ['id' => $pi->id])

{{-- Amendment Modal --}}
<div class="modal fade text-start alertbackdropdisabled" id="amendmentconfirm" tabindex="-1" aria-labelledby="myModalLabel1" aria-hidden="true" data-bs-backdrop="false">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header p-0 bg-transparent">
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body alertmsg text-center warning">
              <i data-feather='alert-circle'></i>
              <h2>Are you sure?</h2>
              <p>Are you sure you want to <strong>Amendment</strong> this <strong>PO</strong>? After Amendment this action cannot be undone.</p>
              <button type="button" class="btn btn-secondary me-25" data-bs-dismiss="modal">Cancel</button>
              <button type="button" id="amendmentSubmit" class="btn btn-primary">Confirm</button>
          </div> 
      </div>
  </div>
</div>

@endsection
@section('scripts')
<script type="text/javascript" src="{{asset('assets/js/modules/pi.js')}}"></script>
<script>

@if($pi->document_status != 'draft')
$(':input').prop('readonly', true);
$('select').not('.amendmentselect select').prop('disabled', true);
$("#deleteBtn").remove();
$("#addNewItemBtn").remove();
$(document).on('show.bs.modal', function (e) {
    
    if(e.target.id != 'approveModal') {
        $(e.target).find('.modal-footer').remove();
        $('select').not('.amendmentselect select').prop('disabled', true);
    }
    if(e.target.id == 'approveModal') {
        $(e.target).find(':input').prop('readonly', false);
        $(e.target).find('select').prop('readonly', false);
    }
    $('.add-contactpeontxt').remove();
    let text = $(e.target).find('thead tr:first th:last').text();
    if(text.includes("Action")){
        $(e.target).find('thead tr').each(function() {
            $(this).find('th:last').remove();
        });
        $(e.target).find('tbody tr').each(function() {
            $(this).find('td:last').remove();
        });
    }
});
$(document).on('shown.bs.modal', function (e) {
    $(".deleteItemDeliveryRow").closest('td').remove();
});
@endif

/*Vendor drop down*/
function initializeAutocomplete1(selector, type) {
    $(selector).autocomplete({
        minLength: 0,
        source: function(request, response) {
            $.ajax({
                url: '/search',
                method: 'GET',
                dataType: 'json',
                data: {
                    q: request.term,
                    type:'vendor_list'
                },
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            id: item.id,
                            label: item.company_name,
                            code: item.vendor_code,
                            addresses: item.addresses
                        };
                    }));
                },
                error: function(xhr) {
                    console.error('Error fetching customer data:', xhr.responseText);
                }
            });
        },
        select: function(event, ui) {
            var $input = $(this);
            var itemName = ui.item.value;
            var itemId = ui.item.id;
            var itemCode = ui.item.code;
            $input.attr('data-name', itemName);
            $input.val(itemCode);
            $input.closest('tr').find("[name*='[vendor_name]']").val(itemName);
            $input.closest('tr').find("[name*='[vendor_id]']").val(itemId);
        },
        change: function(event, ui) {
            if (!ui.item) {
                $(this).val("");
                $(this).attr('data-name', '');
                $(this).closest('tr').find("[name*='[vendor_name]']").val('');
                $(this).closest('tr').find("[name*='[vendor_id]']").val('');
            }
        }
    }).focus(function() {
        if (this.value === "") {
            $(this).autocomplete("search", "");
            $(this).closest('tr').find("[name*='[vendor_name]']").val('');
            $(this).closest('tr').find("[name*='[vendor_id]']").val('');
        }
    });
}

/*Add New Row*/
// for component item code
function initializeAutocomplete2(selector, type) {
    $(selector).autocomplete({
        minLength: 0,
        source: function(request, response) {
          let selectedAllItemIds = [];
          $("#itemTable tbody [id*='row_']").each(function(index,item) {
             if(Number($(item).find('[name*="[item_id]"]').val())) {
                selectedAllItemIds.push(Number($(item).find('[name*="[item_id]"]').val()));
            }
        });
          $.ajax({
            url: '/search',
            method: 'GET',
            dataType: 'json',
            data: {
                q: request.term,
                type:'po_item_list',
                selectedAllItemIds : JSON.stringify(selectedAllItemIds)
            },
            success: function(data) {
                response($.map(data, function(item) {
                    return {
                        id: item.id,
                        label: `${item.item_name} (${item.item_code})`,
                        code: item.item_code || '',
                        item_id: item.id,
                        item_name:item.item_name,
                        uom_name:item.uom?.name,
                        uom_id:item.uom_id,
                        hsn_id:item.hsn?.id,
                        hsn_code:item.hsn?.code,
                        alternate_u_o_ms:item.alternate_u_o_ms,
                        is_attr:item.item_attributes_count,

                    };
                }));
            },
            error: function(xhr) {
                console.error('Error fetching customer data:', xhr.responseText);
            }
        });
      },
      select: function(event, ui) {
        let $input = $(this);
        let itemCode = ui.item.code;
        let itemName = ui.item.value;
        let itemN = ui.item.item_name;
        let itemId = ui.item.item_id;
        let uomId = ui.item.uom_id;
        let uomName = ui.item.uom_name;
        let hsnId = ui.item.hsn_id;
        let hsnCode = ui.item.hsn_code;
        $input.attr('data-name', itemName);
        $input.attr('data-code', itemCode);
        $input.attr('data-id', itemId);
        $input.closest('tr').find('[name*="[item_id]"]').val(itemId);
        $input.closest('tr').find('[name*=item_code]').val(itemCode);
        $input.closest('tr').find('[name*=item_name]').val(itemN);
        $input.closest('tr').find('[name*=hsn_id]').val(hsnId);
        $input.closest('tr').find('[name*=hsn_code]').val(hsnCode);
        $input.val(itemCode);
        let uomOption = `<option value=${uomId}>${uomName}</option>`;
        if(ui.item?.alternate_u_o_ms) {
            for(let alterItem of ui.item.alternate_u_o_ms) {
            uomOption += `<option value="${alterItem.uom_id}" ${alterItem.is_purchasing ? 'selected' : ''}>${alterItem.uom?.name}</option>`;
            }
        }
        $input.closest('tr').find('[name*=uom_id]').append(uomOption);
        setTimeout(() => {
            if(ui.item.is_attr) {
                $input.closest('tr').find('.attributeBtn').trigger('click');
            } else {
                $input.closest('tr').find('[name*="[qty]"]').focus();
            }
        }, 100);
        return false;
    },
    change: function(event, ui) {
        if (!ui.item) {
            $(this).val("");
                // $('#itemId').val('');
            $(this).attr('data-name', '');
            $(this).attr('data-code', '');
        }
    }
}).focus(function() {
    if (this.value === "") {
        $(this).autocomplete("search", "");
    }
});
}

initializeAutocomplete2(".comp_item_code");
initializeAutocomplete1("[name*='[vendor_code]']");
/*Check attrubute*/
$(document).on('click', '.attributeBtn', (e) => {
    let tr = e.target.closest('tr');
    let item_name = tr.querySelector('[name*=item_code]').value;
    let item_id = tr.querySelector('[name*="[item_id]"]').value;
    let selectedAttr = [];
    const attrElements = tr.querySelectorAll('[name*=attr_name]');
    if (attrElements.length > 0) {
        selectedAttr = Array.from(attrElements).map(element => element.value);
        selectedAttr = JSON.stringify(selectedAttr);
    }
    if (item_name && item_id) {
        let rowCount = e.target.getAttribute('data-row-count');
        getItemAttribute(item_id, rowCount, selectedAttr, tr);
    } else {
        alert("Please select first item name.");
    }
});

/*For comp attr*/
function getItemAttribute(itemId, rowCount, selectedAttr, tr){
    let piItemId = $(tr).find('[name*="[pi_item_id]"]').length ? $(tr).find('[name*="[pi_item_id]"]').val() : '';
    let isSo = $(tr).find('[name*="so_item_id"]').length ? 1 : 0;
    if(!isSo) {
        isSo = $(tr).find('[name*="so_pi_mapping_item_id"]').length ? 1 : 0;
    }
    let actionUrl = '{{route("pi.item.attr")}}'+'?item_id='+itemId+`&rowCount=${rowCount}&selectedAttr=${selectedAttr}&pi_item_id=${piItemId}&isSo=${isSo}`;
    fetch(actionUrl).then(response => {
        return response.json().then(data => {
            if (data.status == 200) {
                $("#attribute tbody").empty();
                $("#attribute table tbody").append(data.data.html)
                $(tr).find('td:nth-child(2)').find("[name*='[attr_name]']").remove();
                $(tr).find('td:nth-child(2)').append(data.data.hiddenHtml)
                if (data.data.attr) {
                    $("#attribute").modal('show');
                    $(".select2").select2();
                }
            }
        });
    });
}

/*Display item detail*/
$(document).on('input change focus', '#itemTable tr input', (e) => {
   let currentTr = e.target.closest('tr');
   let rowCount = $(currentTr).attr('data-index');
   let pName = $(currentTr).find("[name*='component_item_name']").val();
   let itemId = $(currentTr).find("[name*='[item_id]']").val();
   let remark = '';
   if($(currentTr).find("[name*='remark']")) {
    remark = $(currentTr).find("[name*='remark']").val() || '';
   }
   if (itemId) {
      let selectedAttr = [];
      $(currentTr).find("[name*='attr_name']").each(function(index, item) {
         if($(item).val()) {
            selectedAttr.push($(item).val());
         }
      });

      let selectedDelivery = [];
      $(currentTr).find("[name*='[d_qty]']").each(function(index, item) {
        let dDate = $(item).closest('td').find(`[name*="components[${rowCount}][delivery][${index+1}][d_date]"]`).val();
        let dQty = $(item).closest('td').find(`[name*="components[${rowCount}][delivery][${index+1}][d_qty]"]`).val();
           selectedDelivery.push({"dDate": dDate, "dQty": dQty});
      });

      let uomId = $(currentTr).find("[name*='[uom_id]']").val() || '';
      let qty = $(currentTr).find("[name*='[qty]']").val() || '';
      let actionUrl = '{{route("pi.get.itemdetail")}}'+'?item_id='+itemId+'&selectedAttr='+JSON.stringify(selectedAttr)+'&remark='+remark+'&uom_id='+uomId+'&qty='+qty+'&delivery='+JSON.stringify(selectedDelivery);;
      fetch(actionUrl).then(response => {
         return response.json().then(data => {
            if(data.status == 200) {
                selectedDelivery = [];
               $("#itemDetailDisplay").html(data.data.html);
            }
         });
      });
   }
});
</script>
@endsection
