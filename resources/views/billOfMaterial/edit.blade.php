@extends('layouts.app')
@section('content')
@php
$routeAlias = $servicesBooks['services'][0]?->alias ?? null;
if($routeAlias == 'bom') {
    $routeAlias = 'bill-of-material';
}
@endphp
<form id="BomEditForm" class="ajax-input-form" method="POST" action="{{ route('bill.of.material.update', $bom->id) }}" data-redirect="{{ url($routeAlias) }}" enctype='multipart/form-data'>
    @csrf
    <input type="hidden" name="consumption_method" id="consumption_method" value=""/>
    <input type="hidden" name="quote_bom_id" id="quote_bom_id" value=""/> 
    <input type="hidden" name="type" value="{{$serviceAlias}}">
<div class="app-content content ">
   <div class="content-overlay"></div>
   <div class="header-navbar-shadow"></div>
   <div class="content-wrapper container-xxl p-0">
      <div class="content-header pocreate-sticky">
         <div class="row">
            @include('layouts.partials.breadcrumb-add-edit', [
             'title' => $routeAlias == 'commercial-bom' ? 'Quotation BOM' : 'Production BOM',
             'menu' => 'Home', 
             'menu_url' => url('home'),
             'sub_menu' => 'Edit'
             ])
            <div class="content-header-right text-sm-end col-md-6 mb-50 mb-sm-0">
               <div class="form-group breadcrumb-right">
                  <input type="hidden" name="document_status" value="{{$bom->document_status}}" id="document_status">
                  <button type="button" onClick="javascript: history.go(-1)" class="btn btn-secondary btn-sm mb-50 mb-sm-0"><i data-feather="arrow-left-circle"></i> Back</button>
                  @if($buttons['draft'])
                     <button type="submit" class="btn btn-outline-primary btn-sm mb-50 mb-sm-0 submit-button" name="action" value="draft"><i data-feather='save'></i> Save as Draft</button>
                  @endif
                  @if(!intval(request('amendment') ?? 0))
                    <a href="{{ route('bill.of.material.generate-pdf', $bom->id) }}" target="_blank" class="btn btn-dark btn-sm mb-50 mb-sm-0 waves-effect waves-float waves-light">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline>
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                        <rect x="6" y="14" width="12" height="8"></rect></svg> Print
                    </a>
                    @endif
                  @if($buttons['submit'])
                     <button type="submit" class="btn btn-primary btn-sm submit-button" name="action" value="submitted"><i data-feather="check-circle"></i> Submit</button>
                  @endif
                  @if($buttons['approve'])
                     <button type="button" id="reject-button" class="btn btn-danger btn-sm mb-50 mb-sm-0 waves-effect waves-float waves-light"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg> Reject</button>
                     <button type="button" class="btn btn-primary btn-sm" id="approved-button" name="action" value="approved"><i data-feather="check-circle"></i> Approve</button>
                  @endif 
                  @if($buttons['amend'] && intval(request('amendment') ?? 0))
                       <button type="button" class="btn btn-primary btn-sm" id="amendmentBtn"><i data-feather="check-circle"></i> Submit</button>
                   @else
                       @if($buttons['amend'])
                        <button type="button" data-bs-toggle="modal" data-bs-target="#amendmentconfirm" class="btn btn-primary btn-sm mb-50 mb-sm-0"><i data-feather='edit'></i> Amendment</button>
                       @endif
                   @endif
                    @if($buttons['revoke'])
                        <button id = "revokeButton" type="button" class="btn btn-primary btn-sm mb-50 mb-sm-0"><i data-feather='rotate-ccw'></i> Revoke</button>
                    @endif                
               </div>
            </div>
         </div>
      </div>
      <div class="content-body">
         <section id="basic-datatable">
            <div class="row">
               <div class="col-12">
                  <div class="card">
                     <div class="card-body customernewsection-form">
                        <div class="border-bottom mb-2 pb-25">
                           <div class="row">
                                <div class="col-md-6">
                                    <div class="newheader "> 
                                        <h4 class="card-title text-theme">Basic Information</h4>
                                        <p class="card-text">Fill the details</p> 
                                    </div>
                                </div>
                                <div class="col-md-6 text-sm-end">
                                    <span class="badge rounded-pill badge-light-secondary forminnerstatus">

                                        Status : <span class="{{$docStatusClass}}">{{$bom->display_status}}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                           <div class="col-md-8">
                              <div class="">
                                 <div class="row align-items-center mb-1">
                                    <div class="col-md-3"> 
                                        <label class="form-label">Series <span class="text-danger">*</span></label>  
                                    </div>  

                                    <div class="col-md-5">
                                       <input type="hidden" name="book_id" class="form-control" id="book_id" value="{{$bom->book_id}}" readonly> 
                                       <input readonly type="text" name="book_code" class="form-control" value="{{$bom->book_code}}" id="book_code">
                                    </div>
                                 </div>

                                <div class="row align-items-center mb-1">
                                    <div class="col-md-3"> 
                                        <label class="form-label">BOM No <span class="text-danger">*</span></label>  
                                    </div>  

                                    <div class="col-md-5"> 
                                        <input readonly type="text" name="document_number" class="form-control" id="document_number" value="{{$bom->document_number}}">
                                    </div> 
                                 </div>

                                 <div class="row align-items-center mb-1">
                                    <div class="col-md-3">
                                        <label class="form-label">BOM Date <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="date" class="form-control" value="{{ $bom->document_date }}" name="document_date">
                                    </div>
                                </div>
                                <div class="row align-items-center mb-1 d-none" id="reference_from"> 
                                    <div class="col-md-3"> 
                                        <label class="form-label">Reference from</label>  
                                    </div> 
                                    <div class="col-md-5 action-button"> 
                                        <button type="button" class="btn btn-outline-primary btn-sm mb-0 prSelect" {{$bom->bomItems->count() ? 'disabled' : ''}}><i data-feather="plus-square"></i> Quotation Bom</button>
                                    </div>
                                </div>     
                                 <div class="row align-items-center mb-1">
                                    <div class="col-md-3"> 
                                       <label class="form-label">Product Code <span class="text-danger">*</span></label>  
                                    </div>
                                    <div class="col-md-5">
                                    <input type="text" value="{{$bom->item?->item_code}}" placeholder="Select" class="form-control mw-100 ledgerselecct" id="item_code" name="item_code" data-name="{{$bom->item?->item_name ?? ''}}" data-code="{{$bom->item?->item_code ?? ''}}"/> 
                                    </div>
                                 </div>

                                 <div class="row align-items-center mb-1">
                                    <div class="col-md-3"> 
                                       <label class="form-label">Product Name <span class="text-danger">*</span></label>  
                                    </div>
                                    <div class="col-md-5">  
                                       <input type="hidden" value="{{$bom->item?->id}}" name="item_id" id="head_item_id">
                                       <input type="text" value="{{$bom->item?->item_name}}" id="head_item_name" placeholder="Select" class="form-control mw-100 ledgerselecct" name="item_name"readonly />
                                    </div>
                                 </div>
                                 <div class="row align-items-center mb-1">
                                    <div class="col-md-3"> 
                                       <label class="form-label">UOM <span class="text-danger">*</span></label>  
                                    </div>
                                    <div class="col-md-5"> 
                                       <input type="hidden" id="head_uom_id" class="form-control" name="uom_id" value="{{$bom->uom?->id}}" readonly  />
                                       <input type="text" id="head_uom_name" class="form-control" name="uom_name" value="{{$bom->uom?->name}}" readonly  />
                                    </div>
                                 </div>

                                 {{-- Extra column --}}
                                 @if($servicesBooks['services'][0]?->alias == \App\Helpers\ConstantHelper::BOM_SERVICE_ALIAS)
                                 <div class="row align-items-center mb-1">
                                    <div class="col-md-3"> 
                                        <label class="form-label">Production Type <span class="text-danger">*</span></label>  
                                    </div>  
                                    <div class="col-md-5">
                                       <select class="form-select" id="production_type" name="production_type">
                                          <option value="">Select</option>
                                          @foreach($productionTypes as $productionType)
                                             <option value="{{$productionType}}" {{$bom->production_type == $productionType ? 'selected' : ''}}>{{ucfirst($productionType)}}</option>
                                          @endforeach 
                                       </select>  
                                    </div>
                                 </div>
                                 @endif
                              </div>
                           </div>
                           {{-- Approval History Section --}}
                           @include('partials.approval-history', ['document_status' => $bom->document_status, 'revision_number' => $revision_number]) 
                           <div class="col-md-12">

                              {{-- select attribute display --}}
                              @include('billOfMaterial.partials.header-attribute-edit')

                              <div class="border-bottom mb-2 mt-2 pb-25" id="componentSection">
                                 <div class="row">
                                    <div class="col-md-6">
                                       <div class="newheader ">
                                          <h4 class="card-title text-theme">Component Detail</h4>
                                          <p class="card-text">Fill the details</p>
                                       </div>
                                    </div>
                                    <div class="col-md-6 text-sm-end">
                                       <a href="javascript:;" class="btn btn-sm btn-outline-danger me-50" id="deleteBtn">
                                       <i data-feather="x-circle"></i> Delete</a>
                                       <a href="javascript:;" id="addNewItemBtn" class="btn btn-sm btn-outline-primary">
                                       <i data-feather="plus"></i> Add Component</a>
                                    </div>
                                 </div>
                              </div>
                              <div class="table-responsive pomrnheadtffotsticky">
                                 <table id="itemTable" class="table myrequesttablecbox table-striped po-order-detail custnewpo-detail border newdesignerptable newdesignpomrnpad">
                                    <thead>
                                       <tr>
                                          <th>
                                             <div class="form-check form-check-primary custom-checkbox">
                                                <input type="checkbox" class="form-check-input" id="Email">
                                                <label class="form-check-label" for="Email"></label>
                                             </div>
                                          </th>
                                          <th>Section</th>
                                          <th>Sub Section</th>
                                          <th>Item</th>
                                          <th>Attributes</th>
                                          <th>UOM</th>
                                          <th>Consumption</th>
                                          <th>Cost</th>
                                          <th>Supercede Cost</th>
                                          <th>Item Value</th>
                                          <th>Waste%</th>
                                          <th>Waste Amt</th>
                                          <th>Overheads</th>
                                          <th>Total Cost</th>
                                          <th>Station</th>
                                       </tr>
                                    </thead>
                                    <tbody class="mrntableselectexcel">
                                       @include('billOfMaterial.partials.item-row-edit')
                                    </tbody>
                                    <tfoot>
                                       <tr class="totalsubheadpodetail">
                                          <td colspan="9"></td>
                                          <td class="text-end" id="totalItemValue">{{number_format($bom->total_item_value,6)}}</td>
                                          <td class="text-end" id=""></td>
                                          <td class="text-end" id="totalWasteAmtValue">{{number_format($bom->item_waste_amount,2)}}</td>
                                          <td class="text-end" id="totalOverheadAmountValue">{{number_format($bom->item_overhead_amount,2)}}</td>
                                          <td class="text-end" id="totalCostValue">{{number_format(($bom->total_item_value + $bom->item_waste_amount + $bom->item_overhead_amount),2)}}</td>
                                          <td></td>
                                       </tr>
                                       <tr valign="top">
                                          <td colspan="11" rowspan="10">
                                             <table class="table border" id="itemDetailTable">
                                                <tr>
                                                   <td class="p-0">
                                                      <h6 class="text-dark mb-0 bg-light-primary py-1 px-50"><strong>Item Details</strong></h6>
                                                   </td>
                                                </tr>
                                                <tr>
                                                </tr>
                                                <tr>
                                                </tr>
                                             </table>
                                          </td>
                                          <td colspan="4">
                                             <table class="table border mrnsummarynewsty">
                                                <tr>
                                                   <td colspan="2" class="p-0">
                                                      <h6 class="text-dark mb-0 bg-light-primary py-1 px-50 d-flex justify-content-between">
                                                         <strong>BOM Summary</strong>
                                                         <div class="addmendisexpbtn">
                                                            <button type="button" class="btn p-25 btn-sm btn-outline-secondary addOverHeadSummaryBtn"><i data-feather="plus"></i> Overhead</button> 
                                                            <button type="button" class="btn p-25 btn-sm btn-outline-secondary wasteSummaryBtn" style="font-size: 10px"><i data-feather="plus"></i> Wastage</button>
                                                         </div>
                                                      </h6>
                                                   </td>
                                                </tr>
                                                <tr class="totalsubheadpodetail">
                                                   <td width="55%"><strong>Total Item Cost</strong></td>
                                                   <td class="text-end" amount="{{$bom->total_item_value}}" id="footerSubTotal">{{number_format($bom->total_item_value)}}</td>
                                                </tr>
                                                <tr>
                                                   <td><strong>Overheads</strong></td>
                                                   <td class="text-end" amount="{{$bom->item_overhead_amount + $bom->header_overhead_amount}}" id="footerOverhead">{{number_format(($bom->item_overhead_amount + $bom->header_overhead_amount),2)}}</td>
                                                </tr>
                                                <tr>
                                                   <td><strong>Wastage</strong></td>
                                                   <td class="text-end" amount="{{$bom->item_waste_amount + $bom->header_waste_amount}}" id="footerWasteAmount">{{number_format(($bom->item_waste_amount + $bom->header_waste_amount),2)}}</td>
                                                </tr>
                                                <tr class="voucher-tab-foot">
                                                   <td class="text-primary"><strong>Total Cost</strong></td>
                                                   <td>
                                                      <div class="quottotal-bg justify-content-end">
                                                         <h5 id="footerTotalCost" amount="{{$bom->total_item_value + $bom->item_waste_amount + $bom->header_waste_amount + $bom->item_overhead_amount + $bom->header_overhead_amount}}">{{number_format(($bom->total_item_value + $bom->item_waste_amount + $bom->header_waste_amount + $bom->item_overhead_amount + $bom->header_overhead_amount),2)}}</h5>
                                                      </div>
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
                                      <div class="row">
                                         <div class="col-md-4">
                                       <div class="mb-1">
                                           <label class="form-label">Upload Document</label>
                                           <input type="file" name="attachment[]" class="form-control" onchange = "addFiles(this,'main_bom_preview')" multiple>
                                           <span class = "text-primary small">{{__("message.attachment_caption")}}</span>
                                       </div>
                                   </div>
                                   @include('partials.document-preview',['documents' => $bom->getDocuments(), 'document_status' => $bom->document_status,'elementKey' => 'main_bom_preview'])
                                      </div>
                               </div>
                                 <div class="col-md-12">
                                    <div class="mb-1">  
                                       <label class="form-label">Final Remarks</label> 
                                       <textarea maxlength="250" name="remarks" type="text" rows="4" class="form-control" placeholder="Enter Remarks here...">{!! $bom->remarks !!}</textarea> 
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <!-- Modal to add new record -->
         </section>
      </div>
   </div>
</div>

<div class="modal fade" id="wastagePopup" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
   <div class="modal-dialog  modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-header p-0 bg-transparent">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body px-sm-2 mx-50 pb-2">
            <h1 class="text-center mb-1" id="shareProjectTitle">Wastage Details</h1>
            <p class="text-center">Enter the details below.</p>
            <div class="row">
               <div class="col-md-12 mb-1">
                  <label class="form-label">Wastage% <span class="text-danger">*</span></label>
                  <input type="number" {{intval($bom->header_waste_perc) ? '' : 'readonly'}} value="{{$bom->header_waste_perc ?? ''}}" name="waste_perc" class="form-control" placeholder="Enter Value" step="any">
               </div>
               <div class="col-md-12 mb-1">
                  <label class="form-label">Wastage Value <span class="text-danger">*</span></label>
                  <input {{intval($bom->header_waste_perc) ? 'readonly' : ''}} value="{{$bom->header_waste_amount ?? ''}}" type="number" name="waste_amount" class="form-control" placeholder="Enter Value" step="any">
               </div>
            </div>
         </div>
         <div class="modal-footer justify-content-center">  
            <button type="button" data-bs-dismiss="modal" class="btn btn-outline-secondary me-1">Cancel</button> 
            <button type="button" class="btn btn-primary wastagePopupSummaryBtn" aria-label="Close">Select</button>
         </div>
      </div>
   </div>
</div>

{{-- Overhead summary popup --}}
@include('billOfMaterial.partials.overhead-modal')
@include('procurement.po.partials.amendment-modal', ['id' => $bom->id])
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
            <button type="button" {{-- data-bs-dismiss="modal" --}} class="btn btn-primary submit_attribute">Select</button>
         </div>
      </div>
   </div>
</div>

{{-- Overhead row popup --}}
@include('billOfMaterial.partials.item-overhead-model')

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
@include('billOfMaterial.partials.approve-modal', ['id' => $bom->id])

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
                        <textarea class="form-control" placeholder="Enter Remarks"></textarea>
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
              <p>Are you sure you want to <strong>Amendment</strong> this <strong>BOM</strong>? After Amendment this action cannot be undone.</p>
              <button type="button" class="btn btn-secondary me-25" data-bs-dismiss="modal">Cancel</button>
              <button type="button" id="amendmentSubmit" class="btn btn-primary">Confirm</button>
          </div> 
      </div>
  </div>
</div>

{{-- Delete overhead row modal --}}
<div class="modal fade text-start alertbackdropdisabled" id="deleteOverheadModal" tabindex="-1" aria-labelledby="myModalLabel1" aria-hidden="true" data-bs-backdrop="false">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header p-0 bg-transparent">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body alertmsg text-center warning">
           <i data-feather='alert-circle'></i>
           <h2>Are you sure?</h2>
           <p>Are you sure you want to delete selected <strong>Overhead</strong>?</p>
           <button type="button" class="btn btn-secondary me-25" data-bs-dismiss="modal">Cancel</button>
           <button type="button" id="deleteConfirmOverhead" class="btn btn-primary" >Confirm</button>
         </div>
      </div>
   </div>
</div>

<div class="modal fade text-start alertbackdropdisabled" id="deleteItemOverheadModal" tabindex="-1" aria-labelledby="myModalLabel1" aria-hidden="true" data-bs-backdrop="false">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header p-0 bg-transparent">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body alertmsg text-center warning">
           <i data-feather='alert-circle'></i>
           <h2>Are you sure?</h2>
           <p>Are you sure you want to delete selected <strong>Overhead</strong>?</p>
           <button type="button" class="btn btn-secondary me-25" data-bs-dismiss="modal">Cancel</button>
           <button type="button" id="deleteItemConfirmOverhead" class="btn btn-primary" >Confirm</button>
         </div>
      </div>
   </div>
</div>
@include('billOfMaterial.partials.consumption-modal')
@include('billOfMaterial.partials.q-bom-modal')
@endsection
@section('scripts')
<script type="text/javascript" src="{{asset('assets/js/modules/bom.js')}}"></script>
<script type="text/javascript" src="{{asset('app-assets/js/file-uploader.js')}}"></script>
<script type="text/javascript">
/*Clear local storage*/
setTimeout(() => {
    localStorage.removeItem('deletedItemOverheadIds');
    localStorage.removeItem('deletedHeaderOverheadIds');
    localStorage.removeItem('deletedBomItemIds');
},0);

@if($buttons['amend'] && intval(request('amendment') ?? 0))

@else
   @if($bom->document_status != 'draft' && $bom->document_status != 'rejected')
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
   @endif
@endif

$(function(){
   /*Bind button value*/
   initializeAutocomplete2(".comp_item_code");
   // For product code
    function initializeAutocomplete1(selector, type) {
            $(selector).autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: '/search',
                        method: 'GET',
                        dataType: 'json',
                        data: {
                            q: request.term,
                            type:'header_item'
                        },
                        success: function(data) {
                            response($.map(data, function(item) {
                                return {
                                    id: item.id,
                                    label: `${item.item_name} (${item.item_code})`,
                                    code: item.item_code || '', 
                                    item_id: item.id
                                };
                            }));
                        },
                        error: function(xhr) {
                            console.error('Error fetching customer data:', xhr.responseText);
                        }
                    });
                },
                minLength: 0,
                select: function(event, ui) {
                    var $input = $(this);
                    var itemCode = ui.item.code;
                    var itemName = ui.item.value;
                    var itemId = ui.item.item_id;
                    $input.attr('data-name', itemName);
                    $input.attr('data-code', itemCode);
                    $input.val(itemCode);
                    itemCodeChange(itemId);
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
    initializeAutocomplete1("#item_code");

    $(document).on('change','#book_id',(e) => {
      let bookId = e.target.value;
      if (bookId) {
         getDocNumberByBookId(bookId); 
      } else {
         $("#document_number").val('');
         $("#book_id").val('');
         $("#document_number").attr('readonly', false);
      }
    });

    function getDocNumberByBookId(bookId) {
      let document_date = $("[name='document_date']").val();
      let actionUrl = '{{route("book.get.doc_no_and_parameters")}}'+'?book_id='+bookId+'&document_date='+document_date;
        fetch(actionUrl).then(response => {
            return response.json().then(data => {
                if (data.status == 200) {
                  $("#book_code").val(data.data.book_code);
                  if(!data.data.doc.document_number) {
                     $("#document_number").val('');
                  }
                //   $("#document_number").val(data.data.doc.document_number);
                  if(data.data.doc.type == 'Manually') {
                     $("#document_number").attr('readonly', false);
                  } else {
                     $("#document_number").attr('readonly', true);
                  }
                  const parameters = data.data.parameters;
                  setServiceParameters(parameters);
                  setTableCalculation();
                }
                if(data.status == 404) {
                    $("#book_code").val('');
                    $("#document_number").val('');
                    const docDateInput = $("[name='document_date']");
                    docDateInput.removeAttr('min');
                    docDateInput.removeAttr('max');
                    docDateInput.val(new Date().toISOString().split('T')[0]);
                    alert(data.message);
                }
            });
        }); 
    }

   /*for trigger on edit cases*/
   setTimeout(() => {
       let bookId = $("#book_id").val();
       getDocNumberByBookId(bookId);
   },0);
   /*Set Service Parameter*/
   function setServiceParameters(parameters) {
       /*Date Validation*/
       const docDateInput = $("[name='document_date']");
       let isFeature = false;
       let isPast = false;
       if (parameters.future_date_allowed && parameters.future_date_allowed.includes('yes')) {
           let futureDate = new Date();
           futureDate.setDate(futureDate.getDate() /*+ (parameters.future_date_days || 1)*/);
           docDateInput.val(futureDate.toISOString().split('T')[0]);
           docDateInput.attr("min", new Date().toISOString().split('T')[0]);
           isFeature = true;
       } else {
           isFeature = false;
           docDateInput.attr("max", new Date().toISOString().split('T')[0]);
       }
       if (parameters.back_date_allowed && parameters.back_date_allowed.includes('yes')) {
           let backDate = new Date();
           backDate.setDate(backDate.getDate() /*- (parameters.back_date_days || 1)*/);
           docDateInput.val(backDate.toISOString().split('T')[0]);
           // docDateInput.attr("max", "");
           isPast = true;
       } else {
           isPast = false;
           docDateInput.attr("min", new Date().toISOString().split('T')[0]);
       }
       /*Date Validation*/
       if(isFeature && isPast) {
           docDateInput.removeAttr('min');
           docDateInput.removeAttr('max');
       }

        // Cunsumption Method
        if(parameters.consumption_method.includes('manual')) {
            $("#consumption_method").val('manual');
            $(".consumption_btn").addClass('d-none');
        }
        if(parameters.consumption_method.includes('norms')) {
            $("#consumption_method").val('norms');
            $(".consumption_btn").removeClass('d-none');
       }

        let reference_from_service = parameters.reference_from_service;
        if(reference_from_service.length) {
            let c_bom = '{{\App\Helpers\ConstantHelper::COMMERCIAL_BOM_SERVICE_ALIAS}}';
            if(reference_from_service.includes(c_bom)) {
                $("#reference_from").removeClass('d-none');
            } else {
                $("#reference_from").addClass('d-none');
            }
            if(reference_from_service.includes('d')) {
                $("#addNewItemBtn").removeClass('d-none');
            } else {
                $("#addNewItemBtn").addClass('d-none');
            }
        } else {
            // Swal.fire({
            //     title: 'Error!',
            //     text: "Please update first reference from service param.",
            //     icon: 'error',
            // });
            // setTimeout(() => {
            //     // location.href = "{{url($routeAlias)}}";
            // },1500);
        }
   }

    function itemCodeChange(itemId){
        let actionUrl = '{{route("bill.of.material.item.code")}}'+'?item_id='+itemId;
        fetch(actionUrl).then(response => {
            return response.json().then(data => {
                if (data.status == 200) {
                  let item_name = data.data.item?.item_name || ''; 
                  let item_id = data.data.item?.id || ''; 
                  let uom_id = data.data.item?.uom_id || ''; 
                  let uom_name = data.data.item?.uom?.name || '';
                  $("#head_item_name").val(item_name);
                  $("#head_item_id").val(item_id);
                  $("#head_uom_id").val(uom_id);
                  $("#head_uom_name").val(uom_name);
                  $(".heaer_item").remove();
                  $("#componentSection").before(data.data.html);
                  // $("#head_uom_name").closest('.row').after(data.data.html);
                }
            });
        });
    }

   $(document).on('blur', '#item_code', (e) => {
       if(!e.target.value) {
           itemCodeChange(null)
       }
   });

});

/*Add New Row*/
// for component item code
function initializeAutocomplete2(selector, type) {
   $(selector).autocomplete({
       source: function(request, response) {
         let headItemId = $("#head_item_id").val();
         let selectedAllItemIds = [];
         if(Number(headItemId)) {
            selectedAllItemIds.push(headItemId);
         }
          $("#itemTable tbody [id*='row_']").each(function(index,item) {
            if(Number($(item).find('[name*="item_id"]').val())) {
               selectedAllItemIds.push(Number($(item).find('[name*="item_id"]').val()));
            }
          });
           $.ajax({
               url: '/search',
               method: 'GET',
               dataType: 'json',
               data: {
                   q: request.term,
                   type:'comp_item',
                   selectedAllItemIds : JSON.stringify(selectedAllItemIds)
               },
               success: function(data) {
                   response($.map(data, function(item) {
                       return {
                           id: item.id,
                           label: `${item.item_name} (${item.item_code})`,
                           code: item.item_code || '', 
                           item_id: item.id,
                           uom_name:item.uom?.name,
                           uom_id:item.uom_id,
                           is_attr:item.item_attributes_count,
                       };
                   }));
               },
               error: function(xhr) {
                   console.error('Error fetching customer data:', xhr.responseText);
               }
           });
       },
       minLength: 0,
       select: function(event, ui) {
           let $input = $(this);
           let itemCode = ui.item.code;
           let itemName = ui.item.value;
           let itemId = ui.item.item_id;
           let uomId = ui.item.uom_id;
           let uomName = ui.item.uom_name;
           $input.attr('data-name', itemName);
           $input.attr('data-code', itemCode);
           $input.attr('data-id', itemId);
           $input.val(itemCode);
           $input.closest('tr').find('[name*=item_id]').val(itemId);
           $input.closest('tr').find('[name*=item_code]').val(itemCode);
           let uomOption = `<option value=${uomId}>${uomName}</option>`;
           $input.closest('tr').find('[name*=uom_id]').empty().append(uomOption);
           setTimeout(() => {
                if(ui.item.is_attr) {
                    $input.closest('tr').find('.attributeBtn').trigger('click');
                } else {
                    $input.closest('tr').find('.attributeBtn').trigger('click');
                    if(!$("#consumption_method").val().includes('manual')) {
                    $input.closest('tr').find('.consumption_btn button').trigger('click');
                    } else {
                    $input.closest('tr').find('[name*="[qty]"]').val('').focus();
                    }
                }
            }, 100);
            getBomItemCost(itemId, itemAttributes = []);
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
$(document).on('click','#addNewItemBtn', (e) => {
    let rowsLength = $("#itemTable > tbody > tr").length;
    /*Check header attribute required*/
    let itemCode = $("#item_code").val();
    let selectedAttrRequired = false; 
    let a = $("select[name*='[attr_name]']").filter(function () {
        return !$(this).val();
    });
    if(a.length) {
        selectedAttrRequired = true;
    }
    if(!$(".heaer_item").length) {
      selectedAttrRequired = true;
    }
    let head_item_id = $("#head_item_id").val();
    let itemObj = {
      item_code : itemCode,
      item_id : head_item_id,
      selectedAttrRequired : selectedAttrRequired
    };
    if($("[name*='attributes[1][attr_group_id]']").length == 0 && itemCode) {
      itemObj.selectedAttrRequired = false;
    }
    /*Check last tr data shoud be required*/
    let lastRow = $('#itemTable .mrntableselectexcel tr:last');
    let lastTrObj = {
      item_id : "",
      attr_require : true,
      row_length : lastRow.length
    };

    if(lastRow.length == 0) {
      lastTrObj.attr_require = false;
      lastTrObj.item_id = "0";
    }

    if(lastRow.length > 0) {
       let item_id = lastRow.find("[name*='item_id']").val();
       if(lastRow.find("[name*='attr_name']").length) {
          var emptyElements = lastRow.find("[name*='attr_name']").filter(function() {
              return $(this).val().trim() === '';
          });
         attr_require = emptyElements?.length ? true : false;
       } else {
         attr_require = true;
       }

       lastTrObj = {
         item_id : item_id,
         attr_require : attr_require,
         row_length : lastRow.length
       };

       if($("tr[id*='row_']:last").find("[name*='[attr_group_id]']").length == 0 && item_id) {
          lastTrObj.attr_require = false;
        }
    }
    
    let headerSelectedAttr = [];
    if($(".heaer_item").find("input[name*='[attr_group_id]']").length) {
        $(".heaer_item").find("input[name*='[attr_group_id]']").each(function(index1,item){
            let attr_group_id = $(item).val();
            let attr_val = $(`select[name="attributes[${index1+1}][attr_group_id][${attr_group_id}][attr_name]"]`).val();
            headerSelectedAttr.push({
                'attr_name' : attr_group_id,
                'attr_value' : attr_val
            });
        });
    }
    
    let componentAttr = [];
    if($("tr input[type='hidden'][name*='[attr_group_id]']").length) {
        $("tr input[type='hidden'][name*='[attr_group_id]']").each(function () {
            const nameAttr = $(this).attr("name"); // Get the name attribute
            const value = $(this).val();
            const attributeIdMatch = nameAttr.match(/\[attr_group_id]\[(\d+)]/);
            const attributeId = attributeIdMatch ? attributeIdMatch[1] : null;
            componentAttr.push({
                'attr_name' : attributeId,
                'attr_value' : value
            });
        });
    }
    let type = '{{ $servicesBooks['services'][0]?->alias }}';
    let actionUrl = '{{route("bill.of.material.item.row")}}'+'?count='+rowsLength+'&item='+JSON.stringify(itemObj)+'&component_item='+JSON.stringify(lastTrObj)+'&header_attr='+JSON.stringify(headerSelectedAttr)+'&comp_attr='+JSON.stringify(componentAttr)+'&type='+type; 
    fetch(actionUrl).then(response => {
        return response.json().then(data => {
            if (data.status == 200) {
               // $("#submit-button").click();
                if (rowsLength) {
                    $("#itemTable > tbody > tr:last").after(data.data.html);
                } else {
                    $("#itemTable > tbody").html(data.data.html);
                }
                initializeAutocomplete2(".comp_item_code");
                initializeStationAutocomplete();
                initializeProductSectionAutocomplete();
                $(".prSelect").prop('disabled',true);
            } else if(data.status == 422) {
               Swal.fire({
                    title: 'Error!',
                    text: data.message || 'An unexpected error occurred.',
                    icon: 'error',
                });
            } else {
               console.log("Someting went wrong!");
            }

            if(!$("#consumption_method").val().includes('manual')) {
                $("#itemTable > tbody > tr:last").find('.consumption_btn').removeClass('d-none');
            } else {
                $("#itemTable > tbody > tr:last").find('.consumption_btn').addClass('d-none');
            }
        });
    });
});

/*Delete server side rows*/
$(document).on('click','#deleteConfirm', (e) => {
   let ids = e.target.getAttribute('data-ids');
   ids = JSON.parse(ids);
    localStorage.setItem('deletedBomItemIds', JSON.stringify(ids));
    $("#deleteComponentModal").modal('hide');

    if(ids.length) {
        ids.forEach((id,index) => {
            $(`.form-check-input[data-id='${id}']`).closest('tr').remove();
        });
    }
    setTableCalculation();
    if(!$("#itemTable [id*=row_]").length) {
        $("th .form-check-input").prop('checked',false);
        $("#itemTable > thead .form-check-input").prop('checked',false);
    }
});

/*Check attrubute*/
$(document).on('click', '.attributeBtn', (e) => {
    let tr = e.target.closest('tr');
    let item_name = tr.querySelector('[name*=item_code]').value;
    let item_id = tr.querySelector('[name*=item_id]').value;
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

   let bom_detail_id = 0;
   if($(tr).find('[name*="bom_detail_id"]').length) {
      bom_detail_id = Number($(tr).find('[name*="bom_detail_id"]').val()) || 0;
   }
    let actionUrl = '{{route("bill.of.material.item.attr")}}'+'?item_id='+itemId+`&rowCount=${rowCount}&selectedAttr=${selectedAttr}&bom_detail_id=${bom_detail_id}`;
    fetch(actionUrl).then(response => {
        return response.json().then(data => {
            if (data.status == 200) {
                  $("#attribute tbody").empty();
                  $("#attribute table tbody").append(data.data.html);
                  $(tr).find('td:nth-child(4)').find("[name*='[attr_name]']").remove();
                  $(tr).find('td:nth-child(4)').append(data.data.hiddenHtml)
                  if (data.data.attr) {
                   $("#attribute").modal('show');
                   $(".select2").select2();
                  }
                  qtyEnabledDisabled();
            }
        });
    });
}

/*addOverHeadItemBtn*/
$(document).on('click', '.addOverHeadItemBtn', (e) => {
   e.preventDefault();
    let rowCount = e.target.getAttribute('data-row-count');
    let td = e.target.closest('td');
    let totalAmnt = 0;
    let tr = '';
    if ($(td).find('[name*=amnt]').length) {
        $(td).find('[name*=amnt]').each(function(index, item) {
         let tbl_row_count = index + 1;
         let amnt = Number(item.value).toFixed(2);
         totalAmnt+= Number(amnt);
         let description = $(`[name*="components[${rowCount}][overhead][${index+1}][description]"]`).val();
         let ledger = $(`[name*="components[${rowCount}][overhead][${index+1}][leadger]"]`).val();
         let ledger_id = $(`[name*="components[${rowCount}][overhead][${index+1}][leadger]"]`).val();
         let id = $(`[name*="components[${rowCount}][overhead][${index+1}][id]"]`).val();
         tr+= `<tr class="display_overhead_row">
                 <td>${tbl_row_count}</td>
                 <td>${description}
                 <input type="hidden" value="${id}"  name="components[${rowCount}][overhead][${tbl_row_count}][id]">
                 <input type="hidden" value="${description}" name="components[${rowCount}][overhead][${tbl_row_count}][description]"></td>
                 <td class="text-end">${amnt}
                 <input type="hidden" value="${amnt}" name="components[${rowCount}][overhead][${tbl_row_count}][amnt]"></td>
                 <td>${ledger}
                     <input type="hidden" value="${ledger}" name="components[${rowCount}][overhead][${tbl_row_count}][leadger]" />
                     <input type="hidden" value="${ledger_id}" name="components[${rowCount}][overhead][${tbl_row_count}][leadger_id]">
                  </td>
                  <td>
                  <a href="javascript:;" data-id="${id}" class="text-danger deleteOverHeadItem"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>
                  </td>
               </tr>`;
        });
    }
   $("#overheadItemPopup .display_overhead_row").remove();
   $("#itemOverheadSummaryFooter").before(tr);
   $("#itemOverheadSummaryFooter #total").text(totalAmnt.toFixed(2));
   $("#itemOverheadSummaryFooter").find("[name='row_count']").val(rowCount);

   $("#overheadItemPopup").modal('show');
});

setTimeout(() => {
   initLedger();
},10);

/*Ledger Select*/
function initLedger()
{
   if($("[name='overhead_ledger']").length || $("[name='item_overhead_ledger']").length) {
      $("[name='overhead_ledger'], [name='item_overhead_ledger']").each(function(index,itemEle) {
      let appendToSelector = $(itemEle).closest("#overheadItemPopup").length ? "#overheadItemPopup" : "#overheadSummaryPopup";
      $(itemEle).autocomplete({
       source: function(request, response) {
           $.ajax({
               url: '/search',
               method: 'GET',
               dataType: 'json',
               data: {
                   q: request.term,
                   type:'ledger',
               },
               success: function(data) {
                   response($.map(data, function(item) {
                       return {
                           id: item.id,
                           label: item.name,
                           code: item.code,
                           name: item.name,
                       };
                   }));
               },
               error: function(xhr) {
                   console.error('Error fetching customer data:', xhr.responseText);
               }
           });
       },
       minLength: 0,
       select: function(event, ui) {
           let $input = $(this);
           let itemName = ui.item.label;
           $input.val(itemName);
       },
       appendTo: appendToSelector,
       change: function(event, ui) {
           if (!ui.item) {
               $(this).val("");
           }
       }
      }).focus(function() {
          if (this.value === "") {
              $(this).autocomplete("search", "");
          }
      });
      });
   }
}

/*Display item detail*/
$(document).on('input change focus', '#itemTable tr input', (e) => {
   let currentTr = e.target.closest('tr'); 
   let pName = $(currentTr).find("[name*='component_item_name']").val();
   let itemId = $(currentTr).find("[name*='item_id']").val();
   if (itemId) {
      let selectedAttr = [];
      $(currentTr).find("[name*='attr_name']").each(function(index, item) {
         if($(item).val()) {
            selectedAttr.push($(item).val());
         }
      });

      let sectionName = $(currentTr).find("[name*='[section_name]']").val() || '';
      let subSectionName = $(currentTr).find("[name*='[sub_section_name]']").val() || '';
      let stationName = $(currentTr).find("[name*='[station_name]']").val() || '';
      
      let remark = '';
      if($(currentTr).find("[name*='remark']")) {
       remark = $(currentTr).find("[name*='remark']").val() || '';
      }

      let actionUrl = '{{route("bill.of.material.get.itemdetail")}}'+'?item_id='+itemId+'&selectedAttr='+JSON.stringify(selectedAttr)+'&remark='+remark+'&section_name='+sectionName+'&sub_section_name='+subSectionName+'&station_name='+stationName;
      fetch(actionUrl).then(response => {
         return response.json().then(data => {
            if(data.status == 200) {
               $(".item_detail_row").remove();
               if($("#itemDetailTable tbody tr").length > 2) {
                  $("#itemDetailTable tbody tr").slice(-2).remove();
               }
               $("#itemDetailTable tbody tr:first").after(data.data.html);
            }
         });
      });
   }
});

setTimeout(() => {
   initializeStationAutocomplete();
   initializeProductSectionAutocomplete();

   $("[name*='[section_id]']").each(function(index, item){
      subSection($(item).val(), item);
   });

},10);
// Function to initialize the product section autocomplete
function initializeProductSectionAutocomplete() {
    $("[name*='product_section']").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: '/search',
                method: 'GET',
                dataType: 'json',
                data: {
                    q: request.term,
                    type: 'product_section'
                },
                success: function (data) {
                    const mappedData = $.map(data, function (item) {
                        return {
                            id: item.id,
                            label: item.name,
                        };
                    });
                    response(mappedData);

                },
                error: function (xhr) {
                    console.error('Error fetching data:', xhr.responseText);
                }
            });
        },
        minLength: 0,
        select: function (event, ui) {
            $(this).val(ui.item.label);
            $(this).closest('td').find("[name*='[section_id]']").val(ui.item.id);
            $(this).closest('td').find("[name*='[section_name]']").val(ui.item.label);
            subSection(ui.item.id, this);
            return false;
        },
        change: function (event, ui) {
            if (!ui.item) {
               $(this).val("");
               $(this).closest('td').find("[name*='[section_id]']").val("");
               $(this).closest('td').find("[name*='[section_name]']").val("");
               $(this).closest('tr').find("[name*='[sub_section_id]']").val('');
               $(this).closest('tr').find("[name*='[sub_section_name]']").val('');
            }
        },
        // appendTo: "#addSectionItemPopup"
    }).focus(function () {
        if (this.value === "") {
            $(this).val("");
            $(this).closest('td').find("[name*='[section_id]']").val("");
            $(this).closest('td').find("[name*='[section_name]']").val("");
            $(this).closest('tr').find("[name*='[sub_section_id]']").val('');
            $(this).closest('tr').find("[name*='[sub_section_name]']").val('');
            $(this).autocomplete("search", "");
        }
    });
}

// Function to initialize sub-section autocomplete
function subSection(id, thisObj) {
    $(thisObj).closest('tr').find("[name='product_sub_section']").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: '/search',
                method: 'GET',
                dataType: 'json',
                data: {
                    q: request.term,
                    type: 'product_sub_section',
                    id: id
                },
                success: function (data) {
                  const mappedData = $.map(data, function (item) {
                        return {
                            id: item.id,
                            label: item.name,
                        };
                    });
                    response(mappedData);
                },
                error: function (xhr) {
                    console.error('Error fetching data:', xhr.responseText);
                }
            });
        },
        minLength: 0,
        select: function (event, ui) {
            $(this).val(ui.item.label);
            $(this).closest('td').find("[name*='[sub_section_id]']").val(ui.item.id);
            $(this).closest('td').find("[name*='[sub_section_name]']").val(ui.item.label);
            return false;
        },
        change: function (event, ui) {
            if (!ui.item) {
                $(this).val("");
                $(this).closest('td').find("[name*='[sub_section_id]']").val("");
                $(this).closest('td').find("[name*='[sub_section_name]']").val("");
            }
        },
        // appendTo: "#addSectionItemPopup"
    }).focus(function () {
        if (this.value === "") {
            $(this).val("");
            $(this).closest('td').find("[name*='[sub_section_id]']").val("");
            $(this).closest('td').find("[name*='[sub_section_name]']").val("");
            $(this).autocomplete("search", "");
        }
    });
}
// Function to initialize the product section autocomplete
function initializeStationAutocomplete() {
    $("[name*='product_station']").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: '/search',
                method: 'GET',
                dataType: 'json',
                data: {
                    q: request.term,
                    type: 'station'
                },
                success: function (data) {
                    const mappedData = $.map(data, function (item) {
                        return {
                            id: item.id,
                            label: item.name,
                        };
                    });
                    response(mappedData);

                },
                error: function (xhr) {
                    console.error('Error fetching data:', xhr.responseText);
                }
            });
        },
        minLength: 0,
        select: function (event, ui) {
            $(this).val(ui.item.label);
            $(this).closest('td').find("[name*='[station_id]']").val(ui.item.id);
            $(this).closest('td').find("[name*='[station_name]']").val(ui.item.label);
            return false;
        },
        change: function (event, ui) {
            if (!ui.item) {
               $(this).val("");
               $(this).closest('td').find("[name*='[station_id]']").val("");
               $(this).closest('td').find("[name*='[station_name]']").val("");
            }
        },
        // appendTo: "#addSectionItemPopup"
    }).focus(function () {
        if (this.value === "") {
            $(this).closest('td').find("[name*='[station_id]']").val("");
            $(this).closest('td').find("[name*='[station_name]']").val("");
            $(this).autocomplete("search", "");
        }
    });
}

/*Delete header orderhead*/
$(document).on('click', '.deleteOverHeadSummary', (e) => {
   let id = $(e.target).closest('a').attr('data-id');
   if(id) {
      let editItemIds = [id]; 
      $("#deleteOverheadModal").find("#deleteConfirmOverhead").attr('data-ids',JSON.stringify(editItemIds));
      $("#deleteOverheadModal").modal('show');
   }
});

/*Delete item over head*/
$(document).on('click', '.deleteOverHeadItem', (e) => {
   let dataId = $(e.target).closest('a').attr('data-id');
   let rowCount = $("#itemOverheadSummaryFooter [name='row_count']").val();
   if(dataId) {
      let editItemIds = [dataId]; 
      $("#deleteItemOverheadModal").find("#deleteItemConfirmOverhead").attr('data-ids',JSON.stringify(editItemIds));
      $("#deleteItemOverheadModal").modal('show');
   } else {
         $(e.target).closest('tr').remove();
          let html = '';
          let totalOverheadAmount = 0;
          $("#overheadItemPopup .display_overhead_row").each(function(index, item) {
              let id = $(item).find('[name*=id]').val();
              let description = $(item).find('[name*=description]').val();
              let amnt = $(item).find('[name*=amnt]').val();
              let leadger = $(item).find('[name*=leadger]').val();
              let leadgerId = $(item).find('[name*=leadger_id]').val();
               
               html += `
               <input type="hidden" name="components[${rowCount}][overhead][${index+1}][id]" value="${id}">
                  <input type="hidden" name="components[${rowCount}][overhead][${index+1}][description]" value="${description}">
                  <input type="hidden" name="components[${rowCount}][overhead][${index+1}][amnt]" value="${amnt}">
                  <input type="hidden" name="components[${rowCount}][overhead][${index+1}][leadger]" value="${leadger}">
                  <input type="hidden" name="components[${rowCount}][overhead][${index+1}][leadger_id]" value="${leadgerId}">`;
              
              totalOverheadAmount += Number(amnt) || 0;
          });
         let itemRow = $("#row_"+rowCount);
         itemRow.find('[name*="[overhead_amount]"]').val(totalOverheadAmount.toFixed(2));
         itemRow.find(`[name*="components[${rowCount}][overhead]"]`).remove();
         itemRow.find('[name*="[overhead_amount]"]').after(html);
         $("#itemOverheadSummaryFooter #total").text(totalOverheadAmount.toFixed(2));
         totalCostEachRow(rowCount);
   }
});

/*Delete server side rows*/
$(document).on('click', '#deleteItemConfirmOverhead', (e) => {
    let ids = JSON.parse(e.target.getAttribute('data-ids'));
    let storedIds = JSON.parse(localStorage.getItem('deletedItemOverheadIds')) || [];
    let mergedIds = [...new Set([...ids, ...storedIds])];
    localStorage.setItem('deletedItemOverheadIds', JSON.stringify(mergedIds));
    $("#deleteItemOverheadModal").modal('hide');
    if (ids.length) {
        ids.forEach((id) => {
            $(`#overheadItemPopup [value='${id}']`).closest('tr').remove();
        });
    }
   let rowCount = $("#itemOverheadSummaryFooter").find("[name='row_count']").val();
   $(`[id*='row_'] [name*='components[${rowCount}][overhead]']`).remove();
   let hiddenOverheadRow = '';
   $("#itemOverheadTbl .display_overhead_row").each(function(index, item) {
      let _id = $(item).find('[name*="[id]"]').val();      
      let _description = $(item).find('[name*="[description]"]').val();  

      let _amnt = $(item).find('[name*="[amnt]"]').val();      
      let _leadger = $(item).find('[name*="[leadger]"]').val();      
      let _leadger_id = $(item).find('[name*="[leadger_id]"]').val();
      hiddenOverheadRow+=`<input type="hidden" name="components[${rowCount}][overhead][${index + 1}][id]" value="${_id}">
      <input type="hidden" name="components[${rowCount}][overhead][${index + 1}][description]" value="${_description}">
      <input type="hidden" value="${_amnt}" name="components[${rowCount}][overhead][${index + 1}][amnt]">
      <input type="hidden" value="${_leadger}" name="components[${rowCount}][overhead][${index + 1}][leadger]">
      <input type="hidden" value="${_leadger_id}" name="components[${rowCount}][overhead][${index + 1}][leadger_id]">`;
    });

   $(`[name*='components[${rowCount}][overhead_amount]']`).closest('div').after(hiddenOverheadRow);
   let totalAmount = 0;
   $("#itemOverheadTbl").find('[name*="[amnt]"]').each(function(index,item){
      totalAmount+= Number($(item).val()) || 0;  
   });
    $(`[name*='components[${rowCount}][overhead_amount]']`).val(totalAmount.toFixed(2));
    $("#itemOverheadSummaryFooter #total").text(totalAmount.toFixed(2));
    setTableCalculation();
});



/*Delete server side rows*/
$(document).on('click','#deleteConfirmOverhead', (e) => {
   let ids = e.target.getAttribute('data-ids');
   ids = JSON.parse(ids);

   let storedIds = JSON.parse(localStorage.getItem('deletedHeaderOverheadIds')) || [];
   let mergedIds = [...new Set([...ids, ...storedIds])];

    localStorage.setItem('deletedHeaderOverheadIds', JSON.stringify(mergedIds));
    $("#deleteOverheadModal").modal('hide');
    if(ids.length) {
        ids.forEach((id,index) => {
            $(`#overheadSummaryPopup [value='${id}']`).closest('tr').remove();
        });
    }
    setTableCalculation();
});

/*Amendment modal open*/
$(document).on('click', '.amendmentBtn', (e) => {
   $("#amendmentconfirm").modal('show');
});

/*Open amendment popup*/
$(document).on('click', '#amendmentBtn', (e) => {
    $("#amendmentModal").modal('show');
});

$(document).on('click', '#amendmentSubmit', (e) => {    
let url = new URL(window.location.href);
url.search = '';
url.searchParams.set('amendment', 1);
let amendmentUrl = url.toString();
window.location.replace(amendmentUrl);
});

/*Get Item Cost*/
function getBomItemCost(itemId,itemAttributes)
{
    let type = '{{$servicesBooks['services'][0]?->alias}}';
    let rowCount = $("tr[id*='row_'].trselected").attr('data-index');
    let uom_id = $(`select[name='components[${rowCount}][uom_id]']`).val();
    let actionUrl = '{{route("bill.of.material.get.item.cost")}}'+'?item_id='+itemId+'&itemAttributes='+JSON.stringify(itemAttributes)+'&uom_id='+uom_id+'&type='+type;
   fetch(actionUrl).then(response => {
      return response.json().then(data => {
         if (data.status == 200) {
            if(data.data.cost) {
               $("tr.trselected").find("[name*='[item_cost]']").val((data.data.cost).toFixed(2));
               $("tr.trselected .linkAppend").removeClass('d-none');
               $("tr.trselected .linkAppend a").attr('href', data.data.route);
            } else {
               $("tr.trselected .linkAppend").addClass('d-none');
               $("tr.trselected").find("[name*='[item_cost]']").val(''); 
            }
         } else {
            $("tr.trselected .linkAppend").addClass('d-none');
            $("tr.trselected").find("[name*='[item_cost]']").val('');  
         }
        //  $("#attribute").modal("hide");
      });
   });
}

$(document).on('click', '.submit_attribute', (e) => {
    $("#attribute").modal('hide');
//    let itemId = $("#attribute tbody tr").find('[name*="[item_id]"]').val();
//    let itemAttributes = [];
//    $("#attribute tbody tr").each(function(index, item) {
//       let attr_id = $(item).find('[name*="[attribute_id]"]').val();
//       let attr_value = $(item).find('[name*="[attribute_value]"]').val();
//       itemAttributes.push({
//             'attr_id': attr_id,
//             'attr_value': attr_value
//         });
//    });
//    getBomItemCost(itemId,itemAttributes);
});
// # Revision Number On Chage
$(document).on('change', '#revisionNumber', (e) => {
    let actionUrl = location.pathname + '?revisionNumber='+e.target.value;
    let revision_number = Number("{{$revision_number}}");
    let revisionNumber = Number(e.target.value);
    if(revision_number == revisionNumber) {
        location.href = actionUrl;
    } else {
        window.open(actionUrl, '_blank');
    }
});

/*Amendment btn submit*/
$(document).on('click', '#amendmentBtnSubmit', (e) => {
    let remark = $("#amendmentModal").find('[name="amend_remarks"]').val();
    if(!remark) {
        e.preventDefault();
        $("#amendRemarkError").removeClass("d-none");
        return false;
    } else {
        $("#amendmentModal").modal('hide');
        $("#amendRemarkError").addClass("d-none");
        e.preventDefault();
        $("#BomEditForm").submit();
    }
});

// Revoke Document
$(document).on('click', '#revokeButton', (e) => {
    let actionUrl = '{{ route("bill.of.material.revoke.document") }}'+ '?id='+'{{$bom->id}}';
    fetch(actionUrl).then(response => {
        return response.json().then(data => {
            if(data.status == 'error') {
                Swal.fire({
                    title: 'Error!',
                    text: data.message,
                    icon: 'error',
                });
            } else {
                Swal.fire({
                    title: 'Success!',
                    text: data.message,
                    icon: 'success',
                });
            }
            location.reload();
        });
    }); 
});

/*Open Pr model*/
$(document).on('click', '.prSelect', (e) => {
    $("#prModal").modal('show');
    openBomRequest();
    getBoms();
});

/*searchPiBtn*/
$(document).on('click', '.searchPiBtn', (e) => {
    getBoms();
});

function openBomRequest()
{
    initializeAutocompleteQt("book_code_input_qt", "book_id_qt_val", "book_bom", "book_code", "");
    initializeAutocompleteQt("document_no_input_qt", "document_id_qt_val", "bom_document_qt", "document_number", "");
    initializeAutocompleteQt("item_name_input_qt", "item_id_qt_val", "header_item", "item_code", "item_name");
    initializeAutocompleteQt("department_po", "department_id_po", "department", "name", "");

}
function initializeAutocompleteQt(selector, selectorSibling, typeVal, labelKey1, labelKey2 = "") 
{
    $("#" + selector).autocomplete({
        source: function(request, response) {
            $.ajax({
                url: '/search',
                method: 'GET',
                dataType: 'json',
                data: {
                    q: request.term,
                    type: typeVal,
                    vendor_id : $("#vendor_id_qt_val").val(),
                    header_book_id : $("#book_id").val()
                },
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            id: item.id,
                            label: `${item[labelKey1]} ${labelKey2 ? (item[labelKey2] ? '(' + item[labelKey2] + ')' : '') : ''}`,
                            code: item[labelKey1] || '', 
                        };
                    }));
                },
                error: function(xhr) {
                    console.error('Error fetching customer data:', xhr.responseText);
                }
            });
        },
        appendTo : '#prModal',
        minLength: 0,
        select: function(event, ui) {
            var $input = $(this);
            $input.val(ui.item.label);
            $("#" + selectorSibling).val(ui.item.id);
            return false;
        },
        change: function(event, ui) {
            if (!ui.item) {
                $(this).val("");
                $("#" + selectorSibling).val("");
            }
        }
    }).focus(function() {
        if (this.value === "") {
            $(this).autocomplete("search", "");
        }
    });
}

function getBoms() 
{
    let header_book_id = $("#book_id").val() || '';
    let series_id = $("#book_id_qt_val").val() || '';
    let document_number = $("#document_no_input_qt").val() || '';
    let item_id = $("#item_id_qt_val").val() || '';
    let department_id = $("#department_id_po").val() || '';
    let actionUrl = '{{ route("bill.of.material.get.quote.bom") }}';
    let fullUrl = `${actionUrl}?series_id=${encodeURIComponent(series_id)}&document_number=${encodeURIComponent(document_number)}&item_id=${encodeURIComponent(item_id)}&header_book_id=${encodeURIComponent(header_book_id)}&department_id=${encodeURIComponent(department_id)}`;
    fetch(fullUrl).then(response => {
        return response.json().then(data => {
            $(".po-order-detail #prDataTable").empty().append(data.data.pis);
        });
    });
}

/*Checkbox for pi item list*/
$(document).on('change','.po-order-detail > thead .form-check-input',(e) => {
  if (e.target.checked) {
      $(".po-order-detail > tbody .form-check-input").each(function() {
          $(this).prop('checked',true);
      });
  } else {
      $(".po-order-detail > tbody .form-check-input").each(function() {
          $(this).prop('checked',false);
      });
  }
});
$(document).on('change','.po-order-detail > tbody .form-check-input',(e) => {
  if(!$(".po-order-detail > tbody .form-check-input:not(:checked)").length) {
      $('.po-order-detail > thead .form-check-input').prop('checked', true);
  } else {
      $('.po-order-detail > thead .form-check-input').prop('checked', false);
  }
});


function getSelectedPiIDS()
{
    let ids = [];
    $('.pi_item_checkbox:checked').each(function() {
        ids.push($(this).val());
    });
    return ids;
}

$(document).on('click', '.prProcess', (e) => {
    let ids = getSelectedPiIDS();
    if (!ids.length) {
        $("[name='quote_bom_id']").val('');
        $("#prModal").modal('hide');
        Swal.fire({
            title: 'Error!',
            text: 'Please select at least one one quotation',
            icon: 'error',
        });
        return false;
    }
    if (ids.length > 1) {
        $("[name='quote_bom_id']").val('');
        $("#prModal").modal('hide');
        Swal.fire({
            title: 'Error!',
            text: 'One time you can one process.',
            icon: 'error',
        });
        return false;
    }
    $("[name='quote_bom_id']").val(ids);

    ids = JSON.stringify(ids);
    let actionUrl = '{{ route("bill.of.material.process.bom-item") }}'+'?ids=' + ids;
    fetch(actionUrl).then(response => {
        return response.json().then(data => {
            if(data.status == 200) {
                $("#item_code").val(data.data.bom.item_code);
                $("#head_item_name").val(data.data.bom.item_name);
                $("#head_item_id").val(data.data.bom.item_id);
                $("#head_uom_id").val(data.data.bom.uom.id);
                $("#head_uom_name").val(data.data.bom.uom.name);
                $(".heaer_item").remove();
                $("#componentSection").before(data.data.headerAttrHtml);
                $("#overheadSummaryFooter").remove();
                $("#headerOverheadTbl tbody").html(data.data.headerOverhead);
                $("#wastagePopup .modal-body").html(data.data.headerWaste);
                $("#itemTable .mrntableselectexcel").empty().append(data.data.pos);
                $("#prModal").modal('hide');
                $(".prSelect").prop('disabled',true);
                $("table input[name*='superceeded_cost']").trigger('change');
                $('#itemTable > tbody .form-check-input').removeAttr('data-id');
                setTableCalculation();
            }
            if(data.status == 422) {
                Swal.fire({
                    title: 'Error!',
                    text: data.message,
                    icon: 'error',
                });
                return false;
            }
        });
    });
});

function initializeAutocompleteTED(selector, idSelector, nameSelector, type, percentageVal) {
    $("#" + selector).autocomplete({
        source: function(request, response) {
            let ids = [];
            $('.modal.show').find("tbody tr").each(function(index,item){
            let tedId = $(item).find("input[name*='ted_']").val();
            if(tedId) {
                ids.push(tedId);
            }
            });
            $.ajax({
                url: '/search',
                method: 'GET',
                dataType: 'json',
                data: {
                    q: request.term,
                    type:type,
                    ids: JSON.stringify(ids)
                },
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            id: item.id,
                            label: `${item.name}`,
                            percentage: `${item.percentage}`,
                        };
                    }));
                },
                error: function(xhr) {
                    console.error('Error fetching customer data:', xhr.responseText);
                }
            });
        },
        minLength: 0,
        select: function(event, ui) {
            var $input = $(this);
            var itemName = ui.item.label;
            var itemId = ui.item.id;
            var itemPercentage = ui.item.percentage;

            $input.val(itemName);
            $("#" + idSelector).val(itemId);
            $("#" + nameSelector).val(itemName);
            $("#" + percentageVal).val(itemPercentage).trigger('keyup');
            return false;
        },
        change: function(event, ui) {
            if (!ui.item) {
                $(this).val("");
                $("#" + idSelector).val("");
                $("#" + nameSelector).val("");
            }
        }
    }).focus(function() {
        if (this.value === "") {
            $(this).autocomplete("search", "");
        }
    });
} 
</script>
@endsection