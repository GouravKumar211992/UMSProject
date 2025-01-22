@extends('layouts.app')

@section('content')

    <!-- BEGIN: Content-->
    <form method="POST" data-completionFunction = "disableHeader" class="ajax-input-form sales_module_form" action = "{{route('sale.invoice.store')}}" data-redirect="{{ $redirect_url }}" id = "sale_invoice_form" enctype='multipart/form-data'>

    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header pocreate-sticky">
				<div class="row">
                    @include('layouts.partials.breadcrumb-add-edit', [
                        'title' => 'Invoice', 
                        'menu' => 'Home', 
                        'menu_url' => url('home'),
                        'sub_menu' => 'Add New'
                    ])
                    <input type = "hidden" value = "draft" name = "document_status" id = "document_status" />
					<div class="content-header-right text-sm-end col-md-6 mb-50 mb-sm-0">
						<div class="form-group breadcrumb-right" id = "buttonsDiv">   
                        @if(!isset(request() -> revisionNumber))
                        <button type = "button" onclick="javascript: history.go(-1)" class="btn btn-secondary btn-sm mb-50 mb-sm-0"><i data-feather="arrow-left-circle"></i> Back</button>  
                            @if (isset($order))
                                <button class="btn btn-dark btn-sm mb-50 mb-sm-0 waves-effect waves-float waves-light dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer">
                                        <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                                        <rect x="6" y="14" width="12" height="8"></rect>
                                    </svg>
                                    Print  <i class="fa-regular fa-circle-down"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    @php
                                        if($order->document_type == "si"){
                                            $options=['Tax Invoice'];
                                        }
                                        elseif($order->document_type == "dnote" && $order->invoice_required == 0)
                                        {   
                                            $options = [
                                                'Tax Invoice',
                                                'Delivery Note',
                                            ];
                                        }
                                        else{
                                            $options = ['Delivery Note'];
                                        }
                                        
                                    @endphp
                                    @foreach ($options as $key)
                                        <li>
                                            <a class="dropdown-item" href="{{ route('sale.invoice.generate-pdf', [$order->id, $key]) }}" target="_blank">{{ $key }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                                @if($buttons['draft'])
                                    <button type="button" onclick = "submitForm('draft');" name="action" value="draft" class="btn btn-outline-primary btn-sm mb-50 mb-sm-0" id="save-draft-button" name="action" value="draft"><i data-feather='save'></i> Save as Draft</button>
                                @endif
                                @if($buttons['submit'])
                                    <button type="button" onclick = "submitForm('submitted');" name="action" value="submitted" class="btn btn-primary btn-sm" id="submit-button" name="action" value="submitted"><i data-feather="check-circle"></i> Submit</button>
                                @endif
                                @if($buttons['approve'])
                                    <button type="button" id="reject-button" data-bs-toggle="modal" data-bs-target="#approveModal" onclick = "setReject();" class="btn btn-danger btn-sm mb-50 mb-sm-0 waves-effect waves-float waves-light"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg> Reject</button>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#approveModal" onclick = "setApproval();" ><i data-feather="check-circle"></i> Approve</button>
                                @endif
                                @if($buttons['amend'])
                                    <button id = "amendShowButton" type="button" onclick = "openModal('amendmentconfirm')" class="btn btn-primary btn-sm mb-50 mb-sm-0"><i data-feather='edit'></i> Amendment</button>
                                @endif
                                @if($buttons['post'])
                                <button id = "postButton" onclick = "onPostVoucherOpen();" type = "button" class="btn btn-warning btn-sm mb-50 mb-sm-0 waves-effect waves-float waves-light"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> Post</button>
                                @endif
                                @if($buttons['voucher'])
                                <button type = "button" onclick = "onPostVoucherOpen('posted');" class="btn btn-dark btn-sm mb-50 mb-sm-0 waves-effect waves-float waves-light"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg> Voucher</button>                                
                                @endif
                                @if($buttons['revoke'])
                                    <button id = "revokeButton" type="button" onclick = "revokeDocument();" class="btn btn-primary btn-sm mb-50 mb-sm-0"><i data-feather='rotate-ccw'></i> Revoke</button>
                                @endif
                            @else
                                <button type = "button" name="action" value="draft" id = "save-draft-button" onclick = "submitForm('draft');" class="btn btn-outline-primary btn-sm mb-50 mb-sm-0"><i data-feather='save'></i> Save as Draft</button>  
                                <button type = "button" name="action" value="submitted"  id = "submit-button" onclick = "submitForm('submitted');" class="btn btn-primary btn-sm mb-50 mb-sm-0"><i data-feather="check-circle"></i> Submit</button> 
                            @endif
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
								 <div class="card-body customernewsection-form" id ="main_so_form">  
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="newheader border-bottom mb-2 pb-25 d-flex flex-wrap justify-content-between"> 
                                                    <div>
                                                        <h4 class="card-title text-theme">Basic Information</h4>
                                                        <p class="card-text">Fill the details</p>
                                                    </div> 
                                                </div> 
                                            </div> 
                                            @if (isset($order) && isset($docStatusClass))
                                            <div class="col-md-6 text-sm-end">
                                                <span class="badge rounded-pill badge-light-{{$order->display_status === 'Posted' ? 'info' : 'secondary'}} forminnerstatus">
                                                    <span class = "text-dark" >Status</span> : <span class="{{$docStatusClass}}">{{$order->display_status}}</span>
                                                </span>
                                            </div>
                                                
                                            @endif
                                            <div class="col-md-8"> 
                                                <input type = "hidden" name = "type" id = "type_hidden_input"></input>
                                            @if (isset($order))
                                                <input type = "hidden" value = "{{$order -> id}}" name = "sale_invoice_id"></input>
                                            @endif

                                                    <div class="row align-items-center mb-1">
                                                        <div class="col-md-3"> 
                                                            <label class="form-label">Document Type <span class="text-danger">*</span></label>  
                                                        </div>
                                                        <div class="col-md-5">  
                                                            <select class="form-select disable_on_edit" id = "service_id_input" {{isset($order) ? 'disabled' : ''}} onchange = "onSeriesChange(this);">
                                                                @foreach ($services as $currentService)
                                                                    <option value = "{{$currentService -> alias}}" {{isset($selectedService) ? ($selectedService == $currentService -> alias ? 'selected' : '') : ''}}>{{$currentService -> name}}</option> 
                                                                @endforeach
                                                            </select>
                                                            <input type = "hidden" value = "yes" id = "invoice_to_follow_input" />
                                                        </div>
                                                        
                                                    </div>


                                                    <div class="row align-items-center mb-1">
                                                        <div class="col-md-3"> 
                                                            <label class="form-label">Series <span class="text-danger">*</span></label>  
                                                        </div>
                                                        <div class="col-md-5">  
                                                            <select class="form-select disable_on_edit" onChange = "getDocNumberByBookId(this);" name = "book_id" id = "series_id_input">
                                                                @foreach ($series as $currentSeries)
                                                                    <option value = "{{$currentSeries -> id}}" {{isset($order) ? ($order -> book_id == $currentSeries -> id ? 'selected' : '') : ''}}>{{$currentSeries -> book_code}}</option> 
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        
                                                        <input type = "hidden" name = "book_code" id = "book_code_input" value = "{{isset($order) ? $order -> book_code : ''}}"></input>
                                                     </div>

                                                    <div class="row align-items-center mb-1">
                                                        <div class="col-md-3"> 
                                                            <label class="form-label">Document No <span class="text-danger">*</span></label>  
                                                        </div>  

                                                        <div class="col-md-5"> 
                                                            <input type="text" value = "{{isset($order) ? $order -> document_number : ''}}" class="form-control disable_on_edit" readonly id = "order_no_input" name = "document_no">
                                                        </div> 
                                                     </div>  

                                                    <div class="row align-items-center mb-1">
                                                        <div class="col-md-3"> 
                                                            <label class="form-label">Document Date <span class="text-danger">*</span></label>  
                                                        </div>  

                                                        <div class="col-md-5"> 
                                                            <input type="date" value = "{{isset($order) ? $order -> document_date : Carbon\Carbon::now() -> format('Y-m-d')}}" class="form-control" name = "document_date" id = "order_date_input" oninput = "onDocDateChange();">
                                                        </div> 
                                                     </div>  

                                                    <div class="row align-items-center mb-1">
                                                        <div class="col-md-3"> 
                                                            <label class="form-label">Reference No </label>  
                                                        </div>  

                                                        <div class="col-md-5"> 
                                                            <input type="text" value = "{{isset($order) ? $order -> reference_number : ''}}" name = "reference_no" class="form-control" id = "reference_no_input">
                                                        </div> 
                                                     </div>

                                                    <div class="row align-items-center mb-1" id = "selection_section" style = "display:none;"> 
                                                        <div class="col-md-3"> 
                                                            <label class="form-label">Reference From</label>  
                                                        </div>
                                                            <div class="col-md-2 action-button" id = "sales_order_selection"> 
                                                                <button onclick = "openHeaderPullModal();" disabled type = "button" id = "select_order_button" data-bs-toggle="modal" data-bs-target="#rescdule" class="btn btn-outline-primary btn-sm mb-0"><i data-feather="plus-square"></i>
                                                                Sales Order
                                                            </button>
                                                            </div>
                                                            <div class="col-md-2 action-button" id = "sales_invoice_selection"> 
                                                                <button onclick = "openHeaderPullModal('inv');" disabled type = "button" id = "select_si_button" data-bs-toggle="modal" data-bs-target="#rescdule" class="btn btn-outline-primary btn-sm mb-0"><i data-feather="plus-square"></i>
                                                                 Sales Invoice
                                                            </button>
                                                            </div>
                                                            <div class="col-md-2 action-button" id = "delivery_note_selection"> 
                                                                <button onclick = "openHeaderPullModal('dnote');" disabled type = "button" id = "select_dn_button" data-bs-toggle="modal" data-bs-target="#rescdule" class="btn btn-outline-primary btn-sm mb-0"><i data-feather="plus-square"></i>
                                                                 Delivery Note
                                                            </button>
                                                            </div>
                                                            <div class="col-md-2 action-button" id = "land_lease_selection"> 
                                                                <button onclick = "openHeaderPullModal('land-lease');" disabled type = "button" id = "select_lease_button" data-bs-toggle="modal" data-bs-target="#rescdule2" class="btn btn-outline-primary btn-sm mb-0"><i data-feather="plus-square"></i>
                                                                Land Lease
                                                            </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                            
                                            
                                                    @if(isset($order) && ($order -> document_status !== "draft"))
                            @if((isset($approvalHistory) && count($approvalHistory) > 0) || isset($revision_number))
                           <div class="col-md-4">
                               <div class="step-custhomapp bg-light p-1 customerapptimelines customerapptimelinesapprovalpo">
                                   <h5 class="mb-2 text-dark border-bottom pb-50 d-flex align-items-center justify-content-between">
                                       <strong><i data-feather="arrow-right-circle"></i> Approval History</strong>
                                       @if(!isset(request() -> revisionNumber) && $order -> document_status !== 'draft')
                                       <strong class="badge rounded-pill badge-light-secondary amendmentselect">Rev. No.
                                           <select class="form-select" id="revisionNumber">
                                            @for($i=$revision_number; $i >= 0; $i--)
                                               <option value="{{$i}}" {{request('revisionNumber',$order->revision_number) == $i ? 'selected' : ''}}>{{$i}}</option>
                                            @endfor
                                           </select>
                                       </strong>
                                       @else
                                       @if ($order -> document_status !== 'draft')
                                       <strong class="badge rounded-pill badge-light-secondary amendmentselect">
                                        Rev. No.{{request() -> revisionNumber}}
                                        </strong>
                                       @endif
                                       
                                       @endif
                                   </h5>
                                   <ul class="timeline ms-50 newdashtimline ">
                                        @foreach($approvalHistory as $approvalHist)
                                        <li class="timeline-item">
                                           <span class="timeline-point timeline-point-indicator"></span>
                                           <div class="timeline-event">
                                               <div class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                                                   <h6>{{ucfirst($approvalHist->name ?? $approvalHist?->user?->name ?? 'NA')}}</h6>
                                                   @if($approvalHist->approval_type == 'approve')
                                                   <span class="badge rounded-pill badge-light-success">{{ucfirst($approvalHist->approval_type)}}</span>
                                                   @elseif($approvalHist->approval_type == 'submit')
                                                   <span class="badge rounded-pill badge-light-primary">{{ucfirst($approvalHist->approval_type)}}</span>
                                                   @elseif($approvalHist->approval_type == 'reject')
                                                   <span class="badge rounded-pill badge-light-danger">{{ucfirst($approvalHist->approval_type)}}</span>
                                                   @elseif($approvalHist->approval_type == 'posted')
                                                   <span class="badge rounded-pill badge-light-info">{{ucfirst($approvalHist->approval_type)}}</span>
                                                   @else
                                                   <span class="badge rounded-pill badge-light-danger">{{ucfirst($approvalHist->approval_type)}}</span>
                                                   @endif
                                               </div>
                                                @if($approvalHist->approval_date)
                                               <h6>
                                                {{ \Carbon\Carbon::parse($approvalHist->approval_date)->format('d-m-Y') }}
                                                </h6>
                                                @endif
                                                @if($approvalHist->remarks)
                                                <p>{!! $approvalHist->remarks !!}</p>
                                                @endif
                                                @if ($approvalHist -> media && count($approvalHist -> media) > 0)
                                                    @foreach ($approvalHist -> media as $mediaFile)
                                                        <p><a href="{{$mediaFile -> file_url}}" target = "_blank"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg></a></p>
                                                    @endforeach
                                                @endif
                                           </div>
                                        </li>
                                       @endforeach
                                       
                                   </ul>
                               </div>
                           </div>
                           @endif
                           @endif
                                        </div> 
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                        <div class="card quation-card">
                                            <div class="card-header newheader">
                                                <div>
                                                    <h4 class="card-title">Customer Details</h4> 
                                                </div>
                                            </div>
                                            <div class="card-body"> 
                                                <div class="row">

                                                    <div class="col-md-3">
                                                        <div class="mb-1">
                                                            <label class="form-label">Customer <span class="text-danger">*</span></label> 
                                                        <input type="text" id = "customer_code_input" disabled placeholder="Select" class="form-control mw-100 ledgerselecct ui-autocomplete-input disable_on_edit" autocomplete="off" value = "{{isset($order) ? $order -> customer_code : ''}}" onblur = "onChangeCustomer('customer_code_input', true)" >
                                                        <input type = "hidden" name = "customer_id" id = "customer_id_input" value = "{{isset($order) ? $order -> customer_id : ''}}"></input>
                                                        <input type = "hidden" name = "customer_code" id = "customer_code_input_hidden" value = "{{isset($order) ? $order -> customer_code : ''}}"></input>
                                                        </div>
                                                    </div>
                                                    
                                                    

                                                    <div class="col-md-3">
                                                        <div class="mb-1">
                                                            <label class="form-label">Currency <span class="text-danger">*</span></label>
                                                             <select class="form-select disable_on_edit" id = "currency_dropdown" name = "currency_id" readonly>
                                                                @if (isset($order) && isset($order -> customer))
                                                                    <option value = "{{$order -> customer -> currency_id}}">{{$order -> customer ?-> currency ?-> name}}</option>
                                                                @else
                                                                    <option value = "">Select</option> 
                                                                @endif
                                                            </select> 
                                                        </div>
                                                        <input type = "hidden" name = "currency_code" value = "{{isset($order) ? $order -> currency_code : ''}}" id = "currency_code_input"></input>
                                                    </div>

                                                    
                                                    <div class="col-md-3">
                                                        <div class="mb-1">
                                                            <label class="form-label">Payment Terms <span class="text-danger">*</span></label>
                                                            <select class="form-select disable_on_edit" id = "payment_terms_dropdown" name = "payment_terms_id" readonly>
                                                                @if (isset($order) && isset($order -> customer))
                                                                    <option value = "{{$order -> customer -> payment_terms_id}}">{{$order -> customer ?-> payment_terms ?-> name}}</option>
                                                                @else
                                                                    <option value = "">Select</option> 
                                                                @endif
                                                            </select>  
                                                        </div>
                                                        <input type = "hidden" name = "payment_terms_code" value = "{{isset($order) ? $order -> payment_terms_code : ''}}" id = "payment_terms_code_input"></input>
                                                    </div>  
                                                 </div>

 
                                                <div class="row"> 
                                                    <div class="col-md-6">
                                                        <div class="customer-billing-section h-100">
                                                            <p>Billing Address&nbsp;<span class="text-danger">*</span>
                                                            @if (!isset($order)) 
                                                            <a href="javascript:;" id="billAddressEditBtn" class="float-end"><i data-feather='edit-3'></i></a>
                                                            @endif
                                                        </p>
                                                            <div class="bilnbody">  
                                                                <div class="genertedvariables genertedvariablesnone">
                                                                    <div class="mrnaddedd-prim" id = "current_billing_address">{{isset($order) ? $order -> billing_address_details ?-> display_address : ''}}</div>
                                                                    <input type = "hidden" id = "current_billing_address_id"></input>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div> 
                                                    <div class="col-md-6">
                                                        <div class="customer-billing-section">
                                                            <p>Shipping Address&nbsp;<span class="text-danger">*</span>
                                                            @if (!isset($order))
                                                            <a href="javascript:;" id="shipAddressEditBtn" data-bs-toggle="modal" class="float-end"><i data-feather='edit-3'></i></a>
                                                            @endif
                                                        </p>
                                                            <div class="bilnbody"> 

                                                                <div class="genertedvariables genertedvariablesnone">
                                                                    <div class="mrnaddedd-prim" id = "current_shipping_address">{{isset($order) ? $order -> shipping_address_details ?-> display_address : ''}}</div>
                                                                    <input type = "hidden" id = "current_shipping_address_id"></input>
                                                                    <input type = "hidden" id = "current_shipping_country_id" name = "shipping_country_id" value = "{{isset($order) && isset($order -> shipping_address_details) ? $order -> shipping_address_details -> country_id : ''}}"></input>
                                                                    <input type = "hidden" id = "current_shipping_state_id" name = "shipping_state_id" value = "{{isset($order) && isset($order -> shipping_address_details) ? $order -> shipping_address_details -> state_id : ''}}"></input>
                                                                </div> 
                                                            </div>
                                                        </div>
                                               </div>


                                                </div>                                                                                                
                                            </div>
                                        </div>
                                    </div>    

                                    <div class="col-md-12" id = "general_information_tab">
									<div class="card quation-card">
										<div class="card-header newheader">
											<div>
												<h4 class="card-title">General Information</h4> 
											</div>
										</div>
										<div class="card-body"> 
											<div class="row">

                                            <div class="col-md-3">
                                                        <div class="mb-1">
                                                            <label class="form-label">E-Way Bill No.</label>
                                                            <input type="text" class="form-control" id = "eway_bill_no_input" name = "eway_bill_no" value = "{{isset($order) ? $order -> eway_bill_no : ''}}" /> 
                                                        </div>
                                                </div> 

												 
												<div class="col-md-3">
                                                        <div class="mb-1">
                                                            <label class="form-label">Transporter Name</label>
                                                            <input type="text" class="form-control" id = "transporter_name_input" name = "transporter_name" value = "{{isset($order) ? $order -> transporter_name : ''}}" /> 
                                                        </div>
                                                </div> 
                                                
                                                <div class="col-md-3">
                                                        <div class="mb-1">
                                                            <label class="form-label">Vehicle Number</label>
                                                            <input type="text" class="form-control" id = "vehicle_no_input" name = "vehicle_no" value = "{{isset($order) ? $order -> vehicle_no : ''}}" /> 
                                                        </div>
                                                    </div> 

                                                <div class="col-md-3">
                                                        <div class="mb-1">
                                                            <label class="form-label">Consignee Name</label>
                                                            <input type="text" class="form-control" id = "consignee_name_input" name = "consignee_name" value = "{{isset($order) ? $order -> consignee_name : ''}}" /> 
                                                        </div>
                                                    </div> 
											 </div>
										</div>
									</div>
								
								</div>
                            </div>
                            
                            <div class="card">
								 <div class="card-body customernewsection-form"> 
                                            <div class="border-bottom mb-2 pb-25">
                                                     <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="newheader "> 
                                                                <h4 class="card-title text-theme">Item Detail</h4>
                                                                <p class="card-text">Fill the details</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 text-sm-end" id = "add_delete_item_section">
                                                            <a href="#" onclick = "deleteItemRows();" class="btn btn-sm btn-outline-danger me-50">
                                                                <i data-feather="x-circle"></i> Delete</a>
                                                            <a href="#" onclick = "addItemRow();" id = "add_item_section" style = "display:none;" class="btn btn-sm btn-outline-primary">
                                                                <i data-feather="plus"></i> Add Item</a>
                                                         </div>
                                                    </div> 
                                             </div>

											<div class="row"> 
                                                
                                                 <div class="col-md-12">
                                                     
                                                     
                                                 <div class="table-responsive pomrnheadtffotsticky">
                                                         <table class="table myrequesttablecbox table-striped po-order-detail custnewpo-detail border newdesignerptable newdesignpomrnpad"> 
                                                            <thead>
                                                                 <tr>
                                                                    <th class="customernewsection-form">
                                                                        <div class="form-check form-check-primary custom-checkbox">
                                                                            <input type="checkbox" class="form-check-input" id="select_all_items_checkbox" oninput = "checkOrRecheckAllItems(this);">
                                                                            <label class="form-check-label" for="select_all_items_checkbox" ></label>
                                                                        </div> 
                                                                    </th>
                                                                    <th width="150px">Item Code</th>
                                                                    <th width="240px">Item Name</th>
                                                                    <th>Attributes</th>
                                                                    <th>UOM</th>
                                                                    <th class = "numeric-alignment">Qty</th>
                                                                    <th class = "numeric-alignment">Rate</th>
                                                                    <th class = "numeric-alignment">Value</th> 
                                                                    <th class = "numeric-alignment">Discount</th>
                                                                    <th class = "numeric-alignment" width = "150px">Total</th> 
                                                                    <th width="50px">Action</th>
                                                                  </tr>
                                                                </thead>
                                                                <tbody class="mrntableselectexcel" id = "item_header">
                                                                @if (isset($order))
                                                                    @php
                                                                        $docType = $order -> document_type;
                                                                    @endphp
                                                                    @foreach ($order -> items as $orderItemIndex => $orderItem)
                                                                        <tr id = "item_row_{{$orderItemIndex}}" class = "item_header_rows" onclick = "onItemClick('{{$orderItemIndex}}');" data-detail-id = "{{$orderItem -> id}}" data-id = "{{$orderItem -> id}}">
                                                                        <input type = 'hidden' name = "so_item_id[]" value = "{{$orderItem -> id}}" {{$orderItem -> is_editable ? '' : 'readonly'}}>
                                                                         <td class="customernewsection-form">
                                                                            <div class="form-check form-check-primary custom-checkbox">
                                                                                <input type="checkbox" class="form-check-input item_row_checks" id="item_checkbox_{{$orderItemIndex}}" del-index = "{{$orderItemIndex}}">
                                                                                <label class="form-check-label" for="item_checkbox_{{$orderItemIndex}}"></label>
                                                                            </div> 
                                                                        </td>
                                                                         <td class="poprod-decpt"> 

                                                                         @if (isset($orderItem -> sale_order_id))

                                                                            <input type = "hidden" id = "qt_book_id_{{$orderItemIndex}}" value = "{{$orderItem -> sale_order ?-> book_id}}" />
                                                                            <input type = "hidden" id = "qt_book_code_{{$orderItemIndex}}" value = "{{$orderItem -> sale_order ?-> book_code}}" />

                                                                            <input type = "hidden" id = "qt_document_no_{{$orderItemIndex}}" value = "{{$orderItem -> sale_order ?-> document_number}}" />
                                                                            <input type = "hidden" id = "qt_document_date_{{$orderItemIndex}}" value = "{{$orderItem -> sale_order ?-> document_date}}" />

                                                                            <input type = "hidden" id = "qt_id_{{$orderItemIndex}}" value = "{{$orderItem -> sale_order ?-> document_number}}" />
                                                                            
                                                                        @endif

                                                                        @if (isset($orderItem -> land_lease_id) || isset($orderItem -> lease_schedule_id))

                                                                        <input type = "hidden" id = "qt_id_{{$orderItemIndex}}" value = "{{$orderItem -> lease_schedule_id}}" name = "quotation_item_ids[]"/>
                                                                        <input type = "hidden" id = "qt_id_header_{{$orderItemIndex}}" value = "{{$orderItem -> land_lease_id}}" name = "quotation_item_ids_header[]"/>

                                                                        <input type = "hidden" id = "qt_type_id_{{$orderItemIndex}}" value = "land-lease" name = "quotation_item_type[]"/>

                                                                        <input type = "hidden" id = "qt_book_id_{{$orderItemIndex}}" value = "{{$orderItem -> lease ?-> book_id}}" />
                                                                        <input type = "hidden" id = "qt_book_code_{{$orderItemIndex}}" value = "{{$orderItem -> lease ?-> series ?-> book_code}}" />

                                                                        <input type = "hidden" id = "qt_document_no_{{$orderItemIndex}}" value = "{{$orderItem -> lease ?-> document_no}}" />
                                                                        <input type = "hidden" id = "qt_document_date_{{$orderItemIndex}}" value = "{{$orderItem -> lease ?-> document_date}}" />

                                                                        <input type = "hidden" id = "qt_id_{{$orderItemIndex}}" value = "{{$orderItem -> lease ?-> document_no}}" />

                                                                        <input type = "hidden" value = "{{$orderItem -> lease ?-> agreement_no}}" id = "land_lease_agreement_no_{{$orderItemIndex}}" />
                                                                        <input type = "hidden" value = "{{$orderItem -> lease ?-> lease_end_date}}" id = "land_lease_end_date_{{$orderItemIndex}}" />
                                                                        <input type = "hidden" value = "{{$orderItem -> lease_schedule ?-> due_date ?? $orderItem ?-> lease ?-> document_date }}" id = "land_lease_due_date_{{$orderItemIndex}}" />
                                                                        <input type = "hidden" value = "{{$orderItem -> lease ?-> repayment_period_type}}" id = "land_lease_repayment_period_{{$orderItemIndex}}" />
                                                                        <input type = "hidden" id = "land_lease_land_parcel_{{$orderItemIndex}}" value = "{{$orderItem ?-> lease ?-> plots() -> first() ?-> land ?-> name}}" />
                                                                        <input type = "hidden" id = "land_lease_land_plots_{{$orderItemIndex}}" value = "{{$orderItem ?-> lease ?-> plots_display()}}"/>
                                                                            
                                                                        @endif

                                                                        
                                                                            
                                                                            <input type="text" id = "items_dropdown_{{$orderItemIndex}}" name="item_code[{{$orderItemIndex}}]" placeholder="Select" class="form-control mw-100 ledgerselecct comp_item_code ui-autocomplete-input {{$orderItem -> is_editable ? '' : 'restrict'}}" autocomplete="off" data-name="{{$orderItem -> item ?-> item_name}}" data-code="{{$orderItem -> item ?-> item_code}}" data-id="{{$orderItem -> item ?-> id}}" hsn_code = "{{$orderItem -> item ?-> hsn ?-> code}}" item-name = "{{$orderItem -> item ?-> item_name}}" specs = "{{$orderItem -> item ?-> specifications}}" attribute-array = "{{$orderItem -> item_attributes_array()}}"  value = "{{$orderItem -> item ?-> item_code}}" {{$orderItem -> is_editable ? '' : 'readonly'}} item-location = "[]">
                                                                            <input type = "hidden" name = "item_id[]" id = "items_dropdown_{{$orderItemIndex}}_value" value = "{{$orderItem -> item_id}}"></input>
                                                                        </td>
                                                                        <td class="poprod-decpt">
                                                                            <input type="text" id = "items_name_{{$orderItemIndex}}" class="form-control mw-100"   value = "{{$orderItem -> item ?-> item_name}}" name = "item_name[{{$orderItemIndex}}]" readonly>
                                                                        </td>
                                                                        <td class="poprod-decpt"> 
                                                                            <button id = "attribute_button_{{$orderItemIndex}}" {{count($orderItem -> item_attributes_array()) > 0 ? '' : 'disabled'}} type = "button" data-bs-toggle="modal" onclick = "setItemAttributes('items_dropdown_{{$orderItemIndex}}', '{{$orderItemIndex}}', {{ json_encode(!$orderItem->is_editable) }});" data-bs-target="#attribute" class="btn p-25 btn-sm btn-outline-secondary" style="font-size: 10px">Attributes</button>
                                                                            <input type = "hidden" name = "attribute_value_{{$orderItemIndex}}" />

                                                                         </td>
                                                                        <td>
                                                                            <select class="form-select" name = "uom_id[]" id = "uom_dropdown_{{$orderItemIndex}}">
                                                                                
                                                                            </select> 
                                                                        </td>
                                                                        <td><input type="text" id = "item_qty_{{$orderItemIndex}}" name = "item_qty[{{$orderItemIndex}}]" oninput = "changeItemQty(this, '{{$orderItemIndex}}');" value = "{{$orderItem -> order_qty}}" class="form-control mw-100 text-end" onblur = "setFormattedNumericValue(this);" max = "{{($orderItem -> max_attribute)}}" /></td>
                                                                       <td><input type="text" id = "item_rate_{{$orderItemIndex}}" name = "item_rate[]" {{$docType == 'dnote' ? 'readonly' : ''}} oninput = "changeItemRate(this, '{{$orderItemIndex}}');" value = "{{$orderItem -> rate}}" class="form-control mw-100 text-end" onblur = "setFormattedNumericValue(this);" /></td> 
                                                                        <td><input type="text" id = "item_value_{{$orderItemIndex}}" disabled class="form-control mw-100 text-end item_values_input" value = "{{$orderItem -> order_qty * $orderItem -> rate}}" /></td>
                                                                        <input type = "hidden" id = "header_discount_{{$orderItemIndex}}" value = "{{$orderItem -> header_discount_amount}}" ></input>
                                                                        <input type = "hidden" id = "header_expense_{{$orderItemIndex}}" value = "{{$orderItem -> header_expense_amount}}"></input>
                                                                         <td>
                                                                            <div class="position-relative d-flex align-items-center">
                                                                                <input type="text" id = "item_discount_{{$orderItemIndex}}" disabled class="form-control mw-100 text-end item_discounts_input" style="width: 70px" value = "{{$orderItem -> item_discount_amount}}"/>
                                                                                <div class="ms-50">
                                                                                    <button type = "button"  onclick = "onDiscountClick('item_value_{{$orderItemIndex}}', '{{$orderItemIndex}}')" data-bs-toggle="modal" data-bs-target="#discount" class="btn p-25 btn-sm btn-outline-secondary" style="font-size: 10px">Add</button>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                                <input type="hidden" id = "item_tax_{{$orderItemIndex}}" value = "{{$orderItem -> tax_amount}}" class="form-control mw-100 text-end item_taxes_input" style="width: 70px" />
                                                                        
                                                                        <td><input type="text" id = "value_after_discount_{{$orderItemIndex}}" value = "{{($orderItem -> order_qty * $orderItem -> rate) - $orderItem -> item_discount_amount}}" disabled class="form-control mw-100 text-end item_val_after_discounts_input" /></td>
                                                                        <input type = "hidden" id = "value_after_header_discount_{{$orderItemIndex}}" class = "item_val_after_header_discounts_input" value = "{{($orderItem -> order_qty * $orderItem -> rate) - $orderItem -> item_discount_amount - $orderItem -> header_discount_amount}}" ></input>
                                                                        <input type="hidden" id = "item_total_{{$orderItemIndex}}" value = "{{($orderItem -> order_qty * $orderItem -> rate) - $orderItem -> item_discount_amount - $orderItem -> header_discount_amount + ($orderItem -> tax_amount)}}" disabled class="form-control mw-100 text-end item_totals_input" />
                                                                         <td>
                                                                             <div class="d-flex">
                                                                                 <div style = "{{$docType == 'dnote' || $docType === 'sinvdnote' ? '' : 'display:none;'}}" class="me-50 cursor-pointer item_store_locations" data-bs-toggle="modal" data-bs-target="#location" onclick = "openStoreLocationModal('{{$orderItemIndex}}')" data-stores = '[]' id = "data_stores_{{$orderItemIndex}}">    <span data-bs-toggle="tooltip" data-bs-placement="top" title="Store Location" class="text-primary"><i data-feather="map-pin"></i></span></div>
                                                                                 <div class="me-50 cursor-pointer" data-bs-toggle="modal" data-bs-target="#Remarks" onclick = "setItemRemarks('item_remarks_{{$orderItemIndex}}');">        <span data-bs-toggle="tooltip" data-bs-placement="top" title="Remarks" class="text-primary"><i data-feather="file-text"></i></span></div>
                                                                            </div>
                                                                         </td>
                                                                         <input type="hidden" id = "item_remarks_{{$orderItemIndex}}" name = "item_remarks[]" value = "{{$orderItem -> remarks}}"/>

                                                                      </tr>
                                                                    @endforeach
                                                                @else
                                                                @endif
                                                             </tbody>
                                                             
                                                             <tfoot>
                                                                 
                                                                 <tr class="totalsubheadpodetail"> 
                                                                    <td colspan="7"></td>
                                                                    <td class="text-end" id = "all_items_total_value">00.00</td>
                                                                    <td class="text-end" id = "all_items_total_discount">00.00</td>
                                                                    <input type = "hidden" id = "all_items_total_tax"></input>
                                                                    <td class="text-end all_tems_total_common" id = "all_items_total_total">00.00</td>
                                                                    <td></td>
                                                                </tr>
                                                                 
                                                                 <tr valign="top">
                                                                    <td id = "item_details_td" colspan="7" rowspan="10">
                                                                        <table class="table border">
                                                                            <tr>
                                                                                <td class="p-0">
                                                                                    <h6 class="text-dark mb-0 bg-light-primary py-1 px-50"><strong>Item Details</strong></h6>
                                                                                </td>
                                                                            </tr>   
                                                                            <tr> 
                                                                                <td class="poprod-decpt">
                                                                                    <div id ="current_item_cat_hsn">

                                                                                    </div>
                                                                                </td> 
                                                                            </tr>
                                                                            <tr id = "current_item_specs_row"> 
                                                                                <td class="poprod-decpt">
                                                                                    <div id ="current_item_specs">

                                                                                    </div>
                                                                                </td> 
                                                                            </tr> 
                                                                            <tr id = "current_item_attribute_row"> 
                                                                                <td class="poprod-decpt">
                                                                                    <div id ="current_item_attributes">

                                                                                    </div>
                                                                                </td> 
                                                                            </tr> 
                                                                            <tr id = "current_item_stocks_row"> 
                                                                                <td class="poprod-decpt">
                                                                                    <div id ="current_item_stocks">

                                                                                    </div>
                                                                                </td> 
                                                                            </tr> 
                                                                            
                                                                            <tr id = "current_item_inventory"> 
                                                                                <td class="poprod-decpt">
                                                                                    <div id ="current_item_inventory_details">

                                                                                    </div>
                                                                                </td> 
                                                                            </tr> 

                                                                            

                                                                            <tr id = "current_item_qt_no_row"> 
                                                                                <td class="poprod-decpt">
                                                                                    <div id ="current_item_qt_no">

                                                                                    </div>
                                                                                </td> 
                                                                            </tr>

                                                                            <tr id = "current_item_store_location_row"> 
                                                                                <td class="poprod-decpt">
                                                                                    <div id ="current_item_store_location">

                                                                                    </div>
                                                                                </td> 
                                                                            </tr>

                                                                            <tr id = "current_item_description_row">
                                                                                <td class="poprod-decpt">
                                                                                    <span class="badge rounded-pill badge-light-secondary"><strong>Remarks</strong>: <span style = "text-wrap:auto;" id = "current_item_description"></span></span>
                                                                                 </td>
                                                                            </tr>

                                                                            <tr id = "current_item_land_lease_agreement_row">
                                                                                <td class="poprod-decpt">
                                                                                    <div id ="current_item_land_lease_agreement">

                                                                                    </div>
                                                                                 </td>
                                                                            </tr>
                                                                        </table> 
                                                                    </td>

                                                                    <td colspan="4" id = "invoice_summary_td">
                                                                        <table class="table border mrnsummarynewsty" id = "summary_table">
                                                                            <tr>
                                                                                <td colspan="2" class="p-0">
                                                                                    <h6 class="text-dark mb-0 bg-light-primary py-1 px-50 d-flex justify-content-between"><strong>Invoice Summary</strong>
                                                                                        <div class="addmendisexpbtn">
                                                                                            <button type = "button" id = "taxes_button" data-bs-toggle="modal" data-bs-target="#orderTaxes" class="btn p-25 btn-sm btn-outline-secondary" onclick = "onOrderTaxClick();" >Taxes</button>
                                                                                            <button type = "button" id = "order_discount_button" data-bs-toggle="modal" data-bs-target="#discountOrder" class="btn p-25 btn-sm btn-outline-secondary" onclick = "onOrderDiscountModalOpen();"><i data-feather="plus"></i> Discount</button>
                                                                                            <button type = "button" id = "order_expense_button" data-bs-toggle="modal" data-bs-target="#expenses" class="btn p-25 btn-sm btn-outline-secondary" onclick = "onOrderExpenseModalOpen();"><i data-feather="plus"></i> Expenses</button>
                                                                                        </div>                                   
                                                                                    </h6>
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="totalsubheadpodetail"> 
                                                                                <td width="55%"><strong>Item Total</strong></td>  
                                                                                <td class="text-end" id = "all_items_total_value_summary">00.00</td>
                                                                            </tr>
                                                                            <tr class=""> 
                                                                                <td width="55%">Item Discount</td>  
                                                                                <td class="text-end" id = "all_items_total_discount_summary">00.00</td>
                                                                            </tr>
                                                                            <tr class="totalsubheadpodetail"> 
                                                                                <td width="55%"><strong>Taxable Value</strong></td>  
                                                                                <td class="text-end" id = "all_items_total_total_summary">00.00</td>
                                                                            </tr>
                                                                            <tr class=""> 
                                                                                <td width="55%">Taxes</td>  
                                                                                <td class="text-end" id = "all_items_total_tax_summary">00.00</td>
                                                                            </tr>
                                                                            <tr class="totalsubheadpodetail"> 
                                                                                <td width="55%"><strong>Total After Tax</strong></td>  
                                                                                <td class="text-end" id = "all_items_total_after_tax_summary">00.00</td>
                                                                            </tr>
                                                                            <tr class=""> 
                                                                                <td width="55%">Expenses</td>  
                                                                                <td class="text-end" id = "all_items_total_expenses_summary">00.00</td>
                                                                            </tr>
                                                                            <input type = "hidden" name = "sub_total" value = "0.00"></input>
                                                                            <input type = "hidden" name = "discount" value = "0.00"></input>
                                                                            <input type = "hidden" name = "discount_amount" value = "0.00"></input>
                                                                            <input type = "hidden" name = "other_expenses" value = "0.00"></input>
                                                                            <input type = "hidden" name = "total_amount" value = "0.00"></input>
                                                                            <!-- <tr>
                                                                                <td><strong>Discount 1</strong></td>
                                                                                <td class="text-end">1,000.00</td>
                                                                            </tr> -->
                                                                            <!-- <tr class="totalsubheadpodetail">
                                                                                <td><strong>Taxable Value</strong></td>  
                                                                                <td class="text-end">38,000.00</td>
                                                                            </tr>
                                                                            <tr> 
                                                                                <td><strong>6% SGST</strong></td>  
                                                                                <td class="text-end">2,280.00</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><strong>6% CGST</strong></td>  
                                                                                <td class="text-end">2,280.00</td>
                                                                            </tr> -->

                                                                            <!-- <tr class="totalsubheadpodetail"> 
                                                                                <td><strong>Total After Tax</strong></td>  
                                                                                <td class="text-end">42,560.00</td>
                                                                            </tr> -->

                                                                            <!-- <tr> 
                                                                                <td><strong>Parking Exp.</strong></td>  
                                                                                <td class="text-end">240.00</td>
                                                                            </tr> -->
                                                                            <tr class="voucher-tab-foot">
                                                                                <td class="text-primary"><strong>Grand Total</strong></td>  
                                                                                <td>
                                                                                    <div class="quottotal-bg justify-content-end"> 
                                                                                        <h5 id = "grand_total">00.00</h5>
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
                                                            <div class = "row">
                                                             <div class="col-md-4">
                                                                <div class="mb-1">
                                                                    <label class="form-label">Upload Document</label>
                                                                    <input type="file" class="form-control" name = "attachments[]" onchange = "addFiles(this,'main_order_file_preview')" max_file_count = "{{isset($maxFileCount) ? $maxFileCount : 10}}" multiple >
                                                                    <span class = "text-primary small">{{__("message.attachment_caption")}}</span>
                                                                </div>
                                                            </div> 
                                                            <div class = "col-md-6" style = "margin-top:19px;">
                                                                <div class = "row" id = "main_order_file_preview">
                                                                </div>
                                                            </div>
                                                            </div>
                                                     </div>
                                                        <div class="col-md-12">
                                                            <div class="mb-1">  
                                                                <label class="form-label">Final Remarks</label> 
                                                                <textarea type="text" rows="4" class="form-control" placeholder="Enter Remarks here..." name = "final_remarks">{{isset($order) ? $order -> remarks : '' }}</textarea> 
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

    <div class="modal fade text-start" id="rescdule" tabindex="-1" aria-labelledby="header_pull_label" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 1250px">
			<div class="modal-content">
				<div class="modal-header">
					<div>
                        <h4 class="modal-title fw-bolder text-dark namefont-sizenewmodal" id="header_pull_label">Select Document</h4>
                        <p class="mb-0">Select from the below list</p>
                    </div>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					 <div class="row">

                     <div class="col">
                            <div class="mb-1">
                            <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                                <input type="text" id="customer_code_input_qt" placeholder="Select" class="form-control mw-100 ledgerselecct ui-autocomplete-input" autocomplete="off" value="">
                                <input type = "hidden" id = "customer_id_qt_val"></input>
                            </div>
                        </div>

                        <div class="col">
                            <div class="mb-1">
                                <label class="form-label">Series <span class="text-danger">*</span></label>
                                <input type="text" id="book_code_input_qt" placeholder="Select" class="form-control mw-100 ledgerselecct ui-autocomplete-input" autocomplete="off" value="">
                                <input type = "hidden" id = "book_id_qt_val"></input>
                            </div>
                        </div>
                         
                         
                         <div class="col">
                            <div class="mb-1">
                                <label class="form-label">Document No. <span class="text-danger">*</span></label>
                                <input type="text" id="document_no_input_qt" placeholder="Select" class="form-control mw-100 ledgerselecct ui-autocomplete-input" autocomplete="off" value="">
                                <input type = "hidden" id = "document_id_qt_val"></input>
                            </div>
                        </div>

                         <div class="col">
                            <div class="mb-1">
                                <label class="form-label">Item Name <span class="text-danger">*</span></label>
                                <input type="text" id="item_name_input_qt" placeholder="Select" class="form-control mw-100 ledgerselecct ui-autocomplete-input" autocomplete="off" value="">
                                <input type = "hidden" id = "item_id_qt_val"></input>
                            </div>
                        </div>
                         
                         <div class="col  mb-1">
                              <label class="form-label">&nbsp;</label><br/>
                             <button onclick = "getOrders();" type = "button" class="btn btn-warning btn-sm"><i data-feather="search"></i> Search</button>
                         </div>

						 <div class="col-md-12">
							<div class="table-responsive">
								<table class="mt-1 table myrequesttablecbox table-striped po-order-detail"> 
									<thead>
										 <tr>
											<th>
												<!-- <div class="form-check form-check-inline me-0">
													<input class="form-check-input" type="checkbox" name="podetail" id="inlineCheckbox1">
												</div>  -->
											</th>  
											<th>Series</th>
											<th>Document No.</th>
											<th>Document Date</th>
                                            <th>Currency</th>
                                            <th>Customer Name</th>
											<th>Item</th>
											<th>Attributes</th>
											<th>UOM</th>
											<th>Quantity</th> 
											<th>Balance Qty</th> 
											<th>Avl Stock</th> 
											<th>Rate</th> 
										  </tr>
										</thead>
										<tbody id = "qts_data_table">
                                            
									   </tbody>


								</table>
							</div>
						</div>


					 </div>
				</div>
				<div class="modal-footer text-end">
					<button type = "button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal"><i data-feather="x-circle"></i> Cancel</button>
					<button type = "button" class="btn btn-primary btn-sm" onclick = "processOrder();" data-bs-dismiss="modal"><i data-feather="check-circle"></i> Process</button>
				</div>
			</div>
		</div>
	</div>
    <div class="modal fade text-start" id="rescdule2" tabindex="-1" aria-labelledby="header_pull_label" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 1000px">
			<div class="modal-content">
				<div class="modal-header">
					<div>
                        <h4 class="modal-title fw-bolder text-dark namefont-sizenewmodal" id="header_pull_label">Select Document</h4>
                        <p class="mb-0">Select from the below list</p>
                    </div>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					 <div class="row">

                     <div class="col">
                            <div class="mb-1">
                            <label class="form-label">Customer <span class="text-danger">*</span></label>
                                <input type="text" id="customer_code_input_qt_land" placeholder="Select" class="form-control mw-100 ledgerselecct ui-autocomplete-input" autocomplete="off" value="">
                                <input type = "hidden" id = "customer_id_qt_val_land"></input>
                            </div>
                        </div>

                        <div class="col">
                            <div class="mb-1">
                                <label class="form-label">Series <span class="text-danger">*</span></label>
                                <input type="text" id="book_code_input_qt_land" placeholder="Select" class="form-control mw-100 ledgerselecct ui-autocomplete-input" autocomplete="off" value="">
                                <input type = "hidden" id = "book_id_qt_val_land"></input>
                            </div>
                        </div>
                         
                         
                         <div class="col">
                            <div class="mb-1">
                                <label class="form-label">Document No. <span class="text-danger">*</span></label>
                                <input type="text" id="document_no_input_qt_land" placeholder="Select" class="form-control mw-100 ledgerselecct ui-autocomplete-input" autocomplete="off" value="">
                                <input type = "hidden" id = "document_id_qt_val_land"></input>
                            </div>
                        </div>

                         <div class="col">
                            <div class="mb-1">
                                <label class="form-label">Land Parcel <span class="text-danger">*</span></label>
                                <input type="text" id="land_parcel_input_qt_land" placeholder="Select" class="form-control mw-100 ledgerselecct ui-autocomplete-input" autocomplete="off" value="">
                                <input type = "hidden" id = "land_parcel_id_qt_val_land"></input>
                            </div>
                        </div>

                         <div class="col">
                            <div class="mb-1">
                                <label class="form-label">Land Plots <span class="text-danger">*</span></label>
                                <input type="text" id="land_plot_input_qt_land" placeholder="Select" class="form-control mw-100 ledgerselecct ui-autocomplete-input" autocomplete="off" value="">
                                <input type = "hidden" id = "land_plot_id_qt_val_land"></input>
                            </div>
                        </div>
                         
                         <div class="col  mb-1">
                              <label class="form-label">&nbsp;</label><br/>
                             <button onclick = "getOrders('land-lease');" type = "button" class="btn btn-warning btn-sm"><i data-feather="search"></i> Search</button>
                         </div>

						 <div class="col-md-12">
							<div class="table-responsive">
								<table class="mt-1 table myrequesttablecbox table-striped po-order-detail"> 
									<thead>
										 <tr>
											<th>
											</th>  
											<th>Series</th>
											<th>Document No.</th>
											<th>Document Date</th>
                                            <th>Customer</th>
											<th>Land Parcel</th>
											<th>Plots</th> 
                                            <th>Service Type</th>
											<th>Amount</th> 
											<th>Due Date</th> 
										  </tr>
										</thead>
										<tbody id = "qts_data_table_land">
                                            
									   </tbody>


								</table>
							</div>
						</div>


					 </div>
				</div>
				<div class="modal-footer text-end">
					<button type = "button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal"><i data-feather="x-circle"></i> Cancel</button>
					<button type = "button" class="btn btn-primary btn-sm" onclick = "processOrder('land-lease');" data-bs-dismiss="modal"><i data-feather="check-circle"></i> Process</button>
				</div>
			</div>
		</div>
	</div>
    
    <div class="modal fade" id="discount" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
		<div class="modal-dialog  modal-dialog-centered" style="max-width: 700px">
			<div class="modal-content">
				<div class="modal-header p-0 bg-transparent">
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body px-sm-2 mx-50 pb-2">
					<h1 class="text-center mb-1" id="shareProjectTitle">Discount</h1>

                    <div class = "row">
                        <div class="col-md-4" style = "padding-right:0px">
                            <div class="">
                                <label class="form-label">Type<span class="text-danger">*</span></label> 
                                <input type="text" id="new_discount_name" placeholder="Select" class="form-control mw-100 ledgerselecct ui-autocomplete-input" autocomplete="off" value=""  onblur = "resetDiscountOrExpense(this,'new_discount_percentage')">
                                <input type = "hidden" id = "new_discount_id" />
                            </div>
                        </div>
                        <div class="col-md-2" style = "padding-right:0px">
                            <div class="">
                                <label class="form-label">Percentage <span class="text-danger">*</span></label> 
                                <input id = "new_discount_percentage" oninput = "onChangeDiscountPercentage(this);" type="text" class="form-control mw-100 text-end" placeholder = "Discount Percentage"/>
                            </div>
                        </div>
                        <div class="col-md-4" style = "padding-right:0px">
                            <div class="">
                                <label class="form-label">Value <span class="text-danger">*</span></label> 
                                <input id = "new_discount_value" type="text" class="form-control mw-100 text-end" oninput = "onChangeDiscountValue(this);" placeholder = "Discount Value"/>
                            </div>
                        </div>
                        <div class="col-md-auto mt-1 d-flex align-items-center justify-content-center" style = "padding-right:0px">
                            <div>
                            <a href="#" onclick = "addDiscount();" class="text-primary can_hide"><i data-feather="plus-square"></i></a>
                            </div>
                        </div>
                    </div>
                    
                    
                    <!-- <div class="text-end"><a href="#" class="text-primary add-contactpeontxt mt-50"><i data-feather='plus'></i> Add Discount</a></div> -->

					<div class="table-responsive-md customernewsection-form">
								<table class="mt-1 table myrequesttablecbox table-striped po-order-detail custnewpo-detail" id = "discount_main_table" total-value = "0"> 
									<thead>
										 <tr>
                                            <th>S.No.</th>
											<th width="150px">Discount Name</th>
											<th>Discount %</th>
											<th>Discount Value</th>
											<th>Action</th>
										  </tr>
										</thead>
										<tbody >
											 <tr>
                                                
											</tr>
                                            
                                            <tr>
                                                 <td colspan="2"></td>
                                                 <td class="text-dark"><strong>Total</strong></td>
                                                 <td class="text-dark" id = "total_item_discount"><strong>0.00</strong></td>
                                                 <td></td>
											</tr>
											 

									   </tbody>


								</table>
							</div>
                    
				</div>
				
				<div class="modal-footer justify-content-center">  
						<button type="button" class="btn btn-outline-secondary me-1" onclick = "closeModal('discount');">Cancel</button> 
					    <button type="button" class="btn btn-primary" onclick = "closeModal('discount');">Submit</button>
				</div>
			</div>
		</div>
	</div>

    <div class="modal fade" id="tax" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
		<div class="modal-dialog  modal-dialog-centered" style="max-width: 700px">
			<div class="modal-content">
				<div class="modal-header p-0 bg-transparent">
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body px-sm-2 mx-50 pb-2">
					<h1 class="text-center mb-1" id="shareProjectTitle">Taxes</h1>
                    
                    
                    <!-- <div class="text-end"><a href="#" class="text-primary add-contactpeontxt mt-50"><i data-feather='plus'></i> Add Discount</a></div> -->

					<div class="table-responsive-md customernewsection-form">
								<table class="mt-1 table myrequesttablecbox table-striped po-order-detail custnewpo-detail" id = "tax_main_table"> 
									<thead>
										 <tr>
                                            <th>S.No.</th>
											<th width="150px">Tax Name</th>
											<th>Tax %</th>
											<th>Tax Value</th>
										  </tr>
										</thead>
										<tbody>

									   </tbody>


								</table>
							</div>
                    
				</div>
				
				<div class="modal-footer justify-content-center">  
						<button type="button" class="btn btn-outline-secondary me-1" onclick = "closeModal('tax');">Cancel</button> 
					    <button type="button" class="btn btn-primary" onclick = "closeModal('tax');">Submit</button>
				</div>
			</div>
		</div>
	</div>


    <div class="modal fade" id="discountOrder" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
		<div class="modal-dialog  modal-dialog-centered" style="max-width: 700px">
			<div class="modal-content">
				<div class="modal-header p-0 bg-transparent">
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body px-sm-2 mx-50 pb-2">
					<h1 class="text-center mb-1" id="shareProjectTitle">Discount</h1>

                    <div class = "row">
                        <div class="col-md-4" style = "padding-right:0px">
                            <div class="">
                                <label class="form-label">Type<span class="text-danger">*</span></label> 
                                <input type="text" id="new_order_discount_name" placeholder="Select" class="form-control mw-100 ledgerselecct ui-autocomplete-input" autocomplete="off" value=""  onblur = "resetDiscountOrExpense(this, 'new_order_discount_percentage')">
                                <input type = "hidden" id = "new_order_discount_id" />
                            </div>
                        </div>
                        <div class="col-md-2" style = "padding-right:0px">
                            <div class="">
                                <label class="form-label">Percentage <span class="text-danger">*</span></label> 
                                <input id = "new_order_discount_percentage" oninput = "onChangeOrderDiscountPercentage(this);" type="text" class="form-control mw-100 text-end" />
                            </div>
                        </div>
                        <div class="col-md-4" style = "padding-right:0px">
                            <div class="">
                                <label class="form-label">Value <span class="text-danger">*</span></label> 
                                <input id = "new_order_discount_value" type="text" class="form-control mw-100 text-end" oninput = "onChangeOrderDiscountValue(this);"/>
                            </div>
                        </div>
                        <div class="col-md-auto mt-1 d-flex align-items-center justify-content-center" style = "padding-right:0px">
                            <div>
                                <a  href="#" onclick = "addOrderDiscount();" class="text-primary can_hide"><i data-feather="plus-square"></i></a>
                            </div>
                        </div>
                    </div>
                    

					<div class="table-responsive-md customernewsection-form">
								<table class="mt-1 table myrequesttablecbox table-striped po-order-detail custnewpo-detail" id = "order_discount_main_table"> 
									<thead>
										 <tr>
                                            <th>S.No.</th>
											<th width="150px">Discount Name</th>
											<th>Discount %</th>
											<th>Discount Value</th>
											<th>Action</th>
										  </tr>
										</thead>
										<tbody >
											 <tr>
                                                
											</tr>
                                            
                                            <tr>
                                                 <td colspan="2"></td>
                                                 <td class="text-dark"><strong>Total</strong></td>
                                                 <td class="text-dark" id = "total_order_discount"><strong>0.00</strong></td>
                                                 <td></td>
											</tr>
									   </tbody>


								</table>
							</div>
                    
				</div>
				
				<div class="modal-footer justify-content-center">  
						<button type="button" class="btn btn-outline-secondary me-1">Cancel</button> 
					<button type="button" class="btn btn-primary" onclick = "closeModal('discountOrder');">Submit</button>
				</div>
			</div>
		</div>
	</div>
    <div class="modal fade" id="orderTaxes" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
		<div class="modal-dialog  modal-dialog-centered" style="max-width: 700px">
			<div class="modal-content">
				<div class="modal-header p-0 bg-transparent">
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body px-sm-2 mx-50 pb-2">
					<h1 class="text-center mb-1" id="shareProjectTitle">Taxes</h1>                    
					<div class="table-responsive-md customernewsection-form">
								<table class="mt-1 table myrequesttablecbox table-striped po-order-detail custnewpo-detail" id = "order_tax_main_table"> 
									<thead>
										 <tr>
                                            <th>S.No.</th>
											<th width="150px">Tax</th>
											<th>Taxable Amount</th>
                                            <th>Tax %</th>
											<th>Tax Value</th>
										  </tr>
										</thead>
										<tbody id = "order_tax_details_table">
									   </tbody>


								</table>
							</div>
                    
				</div>
			</div>
		</div>
	</div>
    
    <div class="modal fade" id="edit-address-shipping" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
		<div class="modal-dialog  modal-dialog-centered" style="max-width: 700px">
			<div class="modal-content">
				<div class="modal-header p-0 bg-transparent">
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body px-sm-2 mx-50 pb-2">
					<h1 class="text-center mb-1" id="shareProjectTitle">Edit Address</h1>
					<p class="text-center">Enter the details below.</p>
                    
                    
                     <div class="row mt-2">
                        <div class = "col-md-12 mb-1">
                        <select class="select2 form-select vendor_dependent" id = "shipping_address_dropdown" name = "shipping_address" oninput = "onShippingAddressChange(this);">
                                                                        @if (isset($order) && isset($shipping_addresses))
                                                                            @foreach ($shipping_addresses as $shipping_address)
                                                                                <option value = "{{$shipping_address -> value}}" {{$order -> shipping_to === $shipping_address -> id}}>{{$shipping_address -> label}}</option>
                                                                            @endforeach
                                                                        @else
                                                                            <option value = "">Select</option>
                                                                        @endif
                                                                    </select>
                        </div>
                       <div class="col-md-6 mb-1">
							<label class="form-label">Country <span class="text-danger">*</span></label>
							<select class="select2 form-select" id = "shipping_country_id_input"  onchange = "changeDropdownOptions(this, ['shipping_state_id_input'], ['states'], '/states/', null, ['shipping_city_id_input'])">
								@foreach ($countries as $country)
                                    <option value = "{{$country -> value}}">{{$country -> label}}</option>
                                @endforeach                                
							</select>
						</div>
						 
						
						<div class="col-md-6 mb-1">
							<label class="form-label">State <span class="text-danger">*</span></label>
							<select class="select2 form-select" id = "shipping_state_id_input"  onchange = "changeDropdownOptions(this, ['shipping_city_id_input'], ['cities'], '/cities/', null, [])">                        
							</select>
						</div>
                         
                         <div class="col-md-6 mb-1">
							<label class="form-label">City <span class="text-danger">*</span></label>
							<select class="select2 form-select" name = "shipping_city_id" id = "shipping_city_id_input">
							</select>
						</div>
						
						 
						<div class="col-md-6 mb-1">
							<label class="form-label w-100">Pincode <span class="text-danger">*</span></label>
							<input type="text" class="form-control" value="" placeholder="Enter Pincode" name ="shipping_pincode" id = "shipping_pincode_input"/>
						</div> 
						
						<div class="col-md-12 mb-1">
							<label class="form-label">Address <span class="text-danger">*</span></label>
							<textarea class="form-control" placeholder="Enter Address" name = "shipping_address_text" id = "shipping_address_input"></textarea>
						</div> 
                    
                    </div>

					 
                    
				</div>
				
				<div class="modal-footer justify-content-center">  
						<button type="button" class="btn btn-outline-secondary me-1">Cancel</button> 
                        <button type="button" onclick = "saveAddressShipping();" class="btn btn-primary">Submit</button>
				</div>
			</div>
		</div>
	</div>
    <div class="modal fade" id="edit-address-billing" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
		<div class="modal-dialog  modal-dialog-centered" style="max-width: 700px">
			<div class="modal-content">
				<div class="modal-header p-0 bg-transparent">
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body px-sm-2 mx-50 pb-2">
					<h1 class="text-center mb-1" id="shareProjectTitle">Edit Address</h1>
					<p class="text-center">Enter the details below.</p>
                    
                    
                     <div class="row mt-2">
                     <div class = "col-md-12 mb-1">
                     <select class="select2 form-select vendor_dependent" id = "billing_address_dropdown" name = "billing_address" oninput = "onBillingAddressChange(this);"> 
                                                                        @if (isset($order) && isset($billing_addresses))
                                                                            @foreach ($billing_addresses as $billing_address)
                                                                                <option value = "{{$billing_address -> value}}" {{$order -> billing_to === $billing_address -> id}}>{{$billing_address -> label}}</option>
                                                                            @endforeach
                                                                        @else
                                                                            <option value = "">Select</option>
                                                                        @endif
                                                                    </select>
                        </div>

                        <div class="col-md-6 mb-1">
							<label class="form-label">Country <span class="text-danger">*</span></label>
							<select class="select2 form-select" name = "billing_country_id" id = "billing_country_id_input" onchange = "changeDropdownOptions(this, ['billing_state_id_input'], ['states'], '/states/', null, ['billing_city_id_input'])">
								@foreach ($countries as $country)
                                    <option value = "{{$country -> value}}">{{$country -> label}}</option>
                                @endforeach                                
							</select>
						</div>
						 
						
						<div class="col-md-6 mb-1">
							<label class="form-label">State <span class="text-danger">*</span></label>
							<select class="select2 form-select" name = "billing_state_id" id = "billing_state_id_input" onchange = "changeDropdownOptions(this, ['billing_city_id_input'], ['cities'], '/cities/', null, [])">                        
							</select>
						</div>
                         
                         <div class="col-md-6 mb-1">
							<label class="form-label">City <span class="text-danger">*</span></label>
							<select class="select2 form-select" name = "billing_city_id" id = "billing_city_id_input">
							</select>
						</div>
						
						 
						<div class="col-md-6 mb-1">
							<label class="form-label w-100">Pincode <span class="text-danger">*</span></label>
							<input type="text" class="form-control" value="" placeholder="Enter Pincode" name ="billing_pincode" id = "billing_pincode_input"/>
						</div> 
						
						<div class="col-md-12 mb-1">
							<label class="form-label">Address <span class="text-danger">*</span></label>
							<textarea class="form-control" placeholder="Enter Address" name = "billing_address_text" id = "billing_address_input"></textarea>
						</div> 
                    
                    </div>

					 
                    
				</div>
				
				<div class="modal-footer justify-content-center">  
						<button type="button" class="btn btn-outline-secondary me-1">Cancel</button> 
                        <button type="button" onclick = "saveAddressBilling();" class="btn btn-primary">Submit</button>
				</div>
			</div>
		</div>
	</div>
    
    <div class="modal fade" id="Remarks" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
		<div class="modal-dialog  modal-dialog-centered" >
			<div class="modal-content">
				<div class="modal-header p-0 bg-transparent">
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body px-sm-2 mx-50 pb-2">
					<h1 class="text-center mb-1" id="shareProjectTitle">Add/Edit Remarks</h1>
					<p class="text-center">Enter the details below.</p>
                    
                    
                     <div class="row mt-2">
                         
						
						<div class="col-md-12 mb-1">
							<label class="form-label">Remarks</label>
							<textarea class="form-control" current-item = "item_remarks_0" onchange = "changeItemRemarks(this);" id ="current_item_remarks_input" placeholder="Enter Remarks"></textarea>
						</div> 
                    
                    </div>

					 
                    
				</div>
				
				<div class="modal-footer justify-content-center">  
						<button type="button" class="btn btn-outline-secondary me-1" onclick="closeModal('Remarks');">Cancel</button> 
					<button type="button" class="btn btn-primary" onclick="closeModal('Remarks');">Submit</button>
				</div>
			</div>
		</div>
	</div>
    
    <div class="modal fade" id="expenses" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
		<div class="modal-dialog  modal-dialog-centered" style="max-width: 700px">
			<div class="modal-content">
				<div class="modal-header p-0 bg-transparent">
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body px-sm-2 mx-50 pb-2">
					<h1 class="text-center mb-1" id="shareProjectTitle">Expenses</h1>

                    <div class = "row">
                        <div class="col-md-4" style = "padding-right:0px">
                            <div class="">
                                <label class="form-label">Type<span class="text-danger">*</span></label> 
                                <input type="text" id="order_expense_name" placeholder="Select" class="form-control mw-100 ledgerselecct ui-autocomplete-input" autocomplete="off" value=""  onblur = "resetDiscountOrExpense(this, 'order_expense_percentage')">
                                <input type = "hidden" id = "order_expense_id" />
                            </div>
                        </div>
                        <div class="col-md-2" style = "padding-right:0px">
                            <div class="">
                                <label class="form-label">Percentage <span class="text-danger">*</span></label> 
                                <input type="text" id = "order_expense_percentage" oninput = "onChangeOrderExpensePercentage(this);" class="form-control mw-100 text-end" />
                            </div>
                        </div>
                        <div class="col-md-4" style = "padding-right:0px">
                            <div class="">
                                <label class="form-label">Value <span class="text-danger">*</span></label> 
                                <input type="text" id = "order_expense_value" oninput = "onChangeOrderExpenseValue(this);" class="form-control mw-100 text-end" />
                            </div>
                        </div>
                        <div class="col-md-auto mt-1 d-flex align-items-center justify-content-center" style = "padding-right:0px">
                            <div>
                            <a href="#" onclick = "addOrderExpense();" class="text-primary can_hide"><i data-feather="plus-square"></i></a>
                            </div>
                        </div>
                    </div>
                    
					<div class="table-responsive-md customernewsection-form">
								<table class="mt-1 table myrequesttablecbox table-striped po-order-detail custnewpo-detail" id = "order_expense_main_table"> 
									<thead>
										 <tr>
                                            <th>S.No.</th>
											<th width="150px">Expense Name</th>
											<th>Expense %</th>
											<th>Expense Value</th>
											<th>Action</th>
										  </tr>
										</thead>
										<tbody>
											 <tr>
                                                
											</tr>
                                            
                                            
                                            <tr>
                                                 <td colspan="2"></td>
                                                 <td class="text-dark"><strong>Total</strong></td>
                                                 <td class="text-dark" id = "total_order_expense" ><strong>00.00</strong></td>
                                                 <td></td>
											</tr>
											 

									   </tbody>


								</table>
							</div>
                           
				</div>
				
				<div class="modal-footer justify-content-center">  
						<button type="button" class="btn btn-outline-secondary me-1" onclick="closeModal('expenses');">Cancel</button> 
					<button type="button" class="btn btn-primary" onclick="closeModal('expenses');">Submit</button>
				</div>
			</div>
		</div>
	</div>

    <div class="modal fade" id="location" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
		<div class="modal-dialog  modal-dialog-centered" style="max-width: 900px">
			<div class="modal-content">
				<div class="modal-header p-0 bg-transparent">
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body px-sm-2 mx-50 pb-2">
					<h1 class="text-center mb-1" id="shareProjectTitle">Store Location</h1>
					<p class="text-center">Enter the details below.</p>
                    
                    
                    <table class="mt-1 table myrequesttablecbox table-striped po-order-detail custnewpo-detail" style = "display:none;"> 
						<tbody>
                            <tr>
                                <td></td> 
                                <td>
                                    <input type="text" id = "new_store_code_input" placeholder="Select Store" class="form-control mw-100 ledgerselecct ui-autocomplete-input" autocomplete="off">
                                    <input type = "hidden" id = "new_store_id_input"></input>
                                </td>
                                <td>
                                    <input type="text" id = "new_rack_code_input" placeholder="Select Rack" class="form-control mw-100 ledgerselecct ui-autocomplete-input" autocomplete="off">
                                    <input type = "hidden" id = "new_rack_id_input"></input>
                                </td>
                                <td>
                                    <input type="text" id = "new_shelf_code_input" placeholder="Select Shelf" class="form-control mw-100 ledgerselecct ui-autocomplete-input" autocomplete="off">
                                    <input type = "hidden" id = "new_shelf_id_input"></input>
                                </td>
                                <td>
                                    <input type="text" id = "new_bin_code_input" placeholder="Select Bin" class="form-control mw-100 ledgerselecct ui-autocomplete-input" autocomplete="off">
                                    <input type = "hidden" id = "new_bin_id_input"></input> 
                                </td>
                                <td><input type="text" id = "new_location_qty" class="form-control mw-100" /></td>
                                <td>
                                    <a href="#" class="text-primary" onclick = "addItemStore();"><i data-feather="plus-square"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>

					<div class="table-responsive-md customernewsection-form">
								<table class="mt-1 table myrequesttablecbox table-striped po-order-detail custnewpo-detail"> 
									<thead>
										 <tr>
                                            <th width="80px">#</th> 
											<th>Store</th>
											<th>Rack</th>
											<th>Shelf</th>
											<th>Bin</th>
                                            <th width="50px">Qty</th>
										  </tr>
										</thead>
										<tbody id = "item_location_table" current-item-index = '0'>
                                            

									   </tbody>


								</table>
							</div>
                    
				</div>
				
				<div class="modal-footer justify-content-center">  
						<button type="button" class="btn btn-outline-secondary me-1" onclick="closeModal('location');">Cancel</button> 
					<button type="button" class="btn btn-primary" onclick="closeModal('location');">Submit</button>
				</div>
			</div>
		</div>
	</div>
    
    <div class="modal fade" id="delivery" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
		<div class="modal-dialog  modal-dialog-centered" >
			<div class="modal-content">
				<div class="modal-header p-0 bg-transparent">
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body px-sm-2 mx-50 pb-2">
					<h1 class="text-center mb-1" id="shareProjectTitle">Delivery Schedule</h1>
					<p class="text-center">Enter the details below.</p>
                    
                    <table class="mt-1 table myrequesttablecbox table-striped po-order-detail custnewpo-detail"> 
									<thead>
										 <tr>
                                         <td>#</td>
                                                <td><input type="text" id = "new_item_delivery_qty_input" class="form-control mw-100" /></td>
                                                <td><input type="date" id = "new_item_delivery_date_input" value="{{Carbon\Carbon::now() -> format('Y-m-d')}}" class="form-control mw-100" /></td>
                                                <td>
                                                    <a href="#" onclick = "addDeliveryScheduleRow();" class="text-primary"><i data-feather="plus-square"></i></a>
                                                </td>
										  </tr>
										</thead>
										<tbody >

									   </tbody>


								</table>
					<div class="table-responsive-md customernewsection-form">
								<table class="mt-1 table myrequesttablecbox table-striped po-order-detail custnewpo-detail" id = "delivery_schedule_main_table"> 
									<thead>
										 <tr>
                                            <th>S.No.</th>
											<th width="150px">Quantity</th>
											<th>Date</th>
											<th>Action</th>
										  </tr>
										</thead>
										<tbody>
											 <tr>
                                                
											</tr>
                                            <tr>
                                                 <td class="text-dark"><strong>Total</strong></td>
                                                 <td class="text-dark"><strong id = "item_delivery_qty"></strong></td>
                                                 <td></td>
											</tr>
											 

									   </tbody>


								</table>
							</div>
                           
				</div>
				
				<div class="modal-footer justify-content-center">  
						<button type="button" class="btn btn-outline-secondary me-1" onclick="closeModal('delivery');">Cancel</button> 
					<button type="button" class="btn btn-primary" onclick="closeModal('delivery');">Submit</button>
				</div>
			</div>
		</div>
	</div>

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
								<table class="mt-1 table myrequesttablecbox table-striped po-order-detail custnewpo-detail" id = "attributes_table_modal" item-index = ""> 
									<thead>
										 <tr>  
											<th>Attribute Name</th>
											<th>Attribute Value</th>
										  </tr>
										</thead>
										<tbody id = "attribute_table">	 

									   </tbody>


								</table>
							</div>
				</div>
				
				<div class="modal-footer justify-content-center">  
						<button type="button" class="btn btn-outline-secondary me-1" onclick = "closeModal('attribute');">Cancel</button> 
					    <button type="button" class="btn btn-primary" onclick = "closeModal('attribute');">Select</button>
				</div>
			</div>
		</div>
	</div>

    <div class="modal fade" id="amendConfirmPopup" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-header">
            <div>
               <h4 class="modal-title fw-bolder text-dark namefont-sizenewmodal" id="myModalLabel17">Amend
               Invoice
               </h4>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <input type="hidden" name="action_type" id="action_type_main">

         </div>
         <div class="modal-body pb-2">
            <div class="row mt-1">
               <div class="col-md-12">
                  <div class="mb-1">
                     <label class="form-label">Remarks</label>
                     <textarea name="amend_remarks" class="form-control cannot_disable"></textarea>
                  </div>
                  <div class = "row">
                    <div class = "col-md-8">
                        <div class="mb-1">
                            <label class="form-label">Upload Document</label>
                            <input name = "amend_attachments[]" onchange = "addFiles(this, 'amend_files_preview')" type="file" class="form-control cannot_disable" max_file_count = "2" multiple/>
                        </div>
                    </div>
                    <div class = "col-md-4" style = "margin-top:19px;">
                        <div class="row" id = "amend_files_preview">
                        </div>
                    </div>
                  </div>
                  <span class = "text-primary small">{{__("message.attachment_caption")}}</span>
               </div>
            </div>
         </div>
         <div class="modal-footer justify-content-center">  
            <button type="button" class="btn btn-outline-secondary me-1" onclick = "closeModal('amendConfirmPopup');">Cancel</button> 
            <button type="button" class="btn btn-primary" onclick = "submitAmend();">Submit</button>
         </div>
      </div>
   </div>
</div>
</form>

<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form class="ajax-submit-2" method="POST" action="{{ route('document.approval.saleInvoice') }}" data-redirect="{{ $redirect_url }}" enctype='multipart/form-data'>
          @csrf
          <input type="hidden" name="action_type" id="action_type">
          <input type="hidden" name="id" value="{{isset($order) ? $order -> id : ''}}">
         <div class="modal-header">
            <div>
               <h4 class="modal-title fw-bolder text-dark namefont-sizenewmodal" id="approve_reject_heading_label">
               </h4>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body pb-2">
            <div class="row mt-1">
               <div class="col-md-12">
                  <div class="mb-1">
                     <label class="form-label">Remarks</label>
                     <textarea name="remarks" class="form-control cannot_disable"></textarea>
                  </div>
                  <div class="row">
                    <div class = "col-md-8">
                        <div class="mb-1">
                            <label class="form-label">Upload Document</label>
                            <input type="file" name = "attachments[]" multiple class="form-control cannot_disable" onchange = "addFiles(this, 'approval_files_preview');" max_file_count = "2"/>
                        </div>
                    </div>
                    <div class = "col-md-4" style = "margin-top:19px;">
                        <div class = "row" id = "approval_files_preview">

                        </div>
                    </div>
                  </div>
                  <span class = "text-primary small">{{__("message.attachment_caption")}}</span>
                  
               </div>
            </div>
         </div>
         <div class="modal-footer justify-content-center">  
            <button type="reset" class="btn btn-outline-secondary me-1" onclick = "closeModal('approveModal');">Cancel</button> 
            <button type="submit" class="btn btn-primary">Submit</button>
         </div>
       </form>
      </div>
   </div>
</div>

<div class="modal fade text-start alertbackdropdisabled" id="amendmentconfirm" tabindex="-1" aria-labelledby="myModalLabel1" aria-hidden="true" data-bs-backdrop="false">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header p-0 bg-transparent">
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body alertmsg text-center warning">
              <i data-feather='alert-circle'></i>
              <h2>Are you sure?</h2>
              <p>Are you sure you want to <strong>Amend</strong> this <strong>Invoice</strong>?</p>
              <button type="button" class="btn btn-secondary me-25" data-bs-dismiss="modal">Cancel</button>
              <button type="button" data-bs-dismiss="modal" onclick = "amendConfirm();" class="btn btn-primary">Confirm</button>
          </div> 
      </div>
  </div>
</div>

<div class="modal fade text-start show" id="postvoucher" tabindex="-1" aria-labelledby="postVoucherModal" aria-modal="true" role="dialog">
		<div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 1000px">
			<div class="modal-content">
				<div class="modal-header">
					<div>
                        <h4 class="modal-title fw-bolder text-dark namefont-sizenewmodal" id="postVoucherModal"> Voucher Details</h4>
                    </div>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					 <div class="row">
                         
                         <div class="col-md-3">
                            <div class="mb-1">
                                <label class="form-label">Series <span class="text-danger">*</span></label>
                                <input id = "voucher_book_code" class="form-control" disabled="" >
                            </div>
                        </div>
                         
                         <div class="col-md-3">
                            <div class="mb-1">
                                <label class="form-label">Voucher No <span class="text-danger">*</span></label>
                                <input id = "voucher_doc_no" class="form-control" disabled="" value="">
                            </div>
                        </div>
                         <div class="col-md-3">
                            <div class="mb-1">
                                <label class="form-label">Voucher Date <span class="text-danger">*</span></label>
                                <input id = "voucher_date" class="form-control" disabled="" value="">
                            </div>
                        </div>
                         <div class="col-md-3">
                            <div class="mb-1">
                                <label class="form-label">Currency <span class="text-danger">*</span></label>
                                <input id = "voucher_currency" class="form-control" disabled="" value="">
                            </div>
                        </div>
                          
						 <div class="col-md-12">
 

							<div class="table-responsive">
								<table class="mt-1 table table-striped po-order-detail custnewpo-detail border newdesignerptable newdesignpomrnpad"> 
									<thead>
										 <tr>
											<th>Type</th>  
											<th>Group</th>
											<th>Leadger Code</th>
											<th>Leadger Name</th>
                                            <th class="text-end">Debit</th>
                                            <th class="text-end">Credit</th>
										  </tr>
										</thead>
										<tbody id = "posting-table">
											  

									   </tbody>


								</table>
							</div>
						</div>


					 </div>
				</div>
				<div class="modal-footer text-end">
					<button onclick = "postVoucher(this);" id = "posting_button" type = "button" class="btn btn-primary btn-sm waves-effect waves-float waves-light"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> Submit</button>
				</div>
			</div>
		</div>
	</div>

    
@section('scripts')
<script type="text/javascript" src="{{asset('app-assets/js/file-uploader.js')}}"></script>

<script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })

        $('#issues').on('change', function() {
            var issue_id = $(this).val();
            var seriesSelect = $('#series');

            seriesSelect.empty(); // Clear any existing options
            seriesSelect.append('<option value="">Select</option>');

            if (issue_id) {
                $.ajax({
                    url: "{{ url('get-series') }}/" + issue_id,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $.each(data, function(key, value) {
                            seriesSelect.append('<option value="' + key + '">' + value + '</option>');
                        });
                    }
                });
            }
        });

        $('#series').on('change', function() {
            var book_id = $(this).val();
            var request = $('#requestno');

            request.val(''); // Clear any existing options
            
            if (book_id) {
                $.ajax({
                    url: "{{ url('get-request') }}/" + book_id,
                    type: "GET",
                    dataType: "json",
                    success: function(data) 
                        {
                            if (data.requestno) {
                            request.val(data.requestno);
                        }
                    }
                });
            }
        });

        function onChangeSeries(element)
        {
            document.getElementById("order_no_input").value = 12345;
        }

        function onChangeCustomer(selectElementId, reset = false) 
        {
            const selectedOption = document.getElementById(selectElementId);
            const paymentTermsDropdown = document.getElementById('payment_terms_dropdown');
            const currencyDropdown = document.getElementById('currency_dropdown');
            if (reset && !selectedOption.value) {
                selectedOption.setAttribute('currency_id', '');
                selectedOption.setAttribute('currency', '');
                selectedOption.setAttribute('currency_code', '');

                selectedOption.setAttribute('payment_terms_id', '');
                selectedOption.setAttribute('payment_terms', '');
                selectedOption.setAttribute('payment_terms_code', '');

                document.getElementById('customer_id_input').value = "";
                document.getElementById('customer_code_input_hidden').value = "";
            }
            //Set Currency
            const currencyId = selectedOption.getAttribute('currency_id');
            const currency = selectedOption.getAttribute('currency');
            const currencyCode = selectedOption.getAttribute('currency_code');
            if (currencyId && currency) {
                const newCurrencyValues = `
                    <option value = '${currencyId}' > ${currency} </option>
                `;
                currencyDropdown.innerHTML = newCurrencyValues;
                $("#currency_code_input").val(currencyCode);
            }
            else {
                currencyDropdown.innerHTML = '';
                $("#currency_code_input").val("");
            }
            //Set Payment Terms
            const paymentTermsId = selectedOption.getAttribute('payment_terms_id');
            const paymentTerms = selectedOption.getAttribute('payment_terms');
            const paymentTermsCode = selectedOption.getAttribute('payment_terms_code');
            if (paymentTermsId && paymentTerms) {
                const newPaymentTermsValues = `
                    <option value = '${paymentTermsId}' > ${paymentTerms} </option>
                `;
                paymentTermsDropdown.innerHTML = newPaymentTermsValues;
                $("#payment_terms_code_input").val(paymentTermsCode);
            }
            else {
                paymentTermsDropdown.innerHTML = '';
                $("#payment_terms_code_input").val("");
            }
            //Get Addresses (Billing + Shipping)
            changeDropdownOptions(document.getElementById('customer_id_input'), ['billing_address_dropdown','shipping_address_dropdown'], ['billing_addresses', 'shipping_addresses'], '/customer/addresses/', 'vendor_dependent');
        }

        function changeDropdownOptions(mainDropdownElement, dependentDropdownIds, dataKeyNames, routeUrl, resetDropdowns = null, resetDropdownIdsArray = [])
        {
            const mainDropdown = mainDropdownElement;
            const secondDropdowns = [];
            const dataKeysForApi = [];
            if (Array.isArray(dependentDropdownIds)) {
                dependentDropdownIds.forEach(elementId => {
                    if (elementId.type && elementId.type == "class") {
                        const multipleUiDropDowns = document.getElementsByClassName(elementId.value);
                        const secondDropdownInternal = [];
                        for (let idx = 0; idx < multipleUiDropDowns.length; idx++) {
                            secondDropdownInternal.push(document.getElementById(multipleUiDropDowns[idx].id));
                        }
                        secondDropdowns.push(secondDropdownInternal);
                    } else {
                        secondDropdowns.push(document.getElementById(elementId));
                    }
                });
            } else {
                secondDropdowns.push(document.getElementById(dependentDropdownIds))
            }

            if (Array.isArray(dataKeyNames)) {
                dataKeyNames.forEach(key => {
                    dataKeysForApi.push(key);
                })
            } else {
                dataKeysForApi.push(dataKeyNames);
            }

            if (dataKeysForApi.length !== secondDropdowns.length) {
                console.log("Dropdown function error");
                return;
            }

            if (resetDropdowns) {
                const resetDropdownsElement = document.getElementsByClassName(resetDropdowns);
                for (let index = 0; index < resetDropdownsElement.length; index++) {
                    resetDropdownsElement[index].innerHTML = `<option value = '0'>Select</option>`;
                }
            }

            if (resetDropdownIdsArray) {
                if (Array.isArray(resetDropdownIdsArray)) {
                    resetDropdownIdsArray.forEach(elementId => {
                        let currentResetElement = document.getElementById(elementId);
                        if (currentResetElement) {
                            currentResetElement.innerHTML = `<option value = '0'>Select</option>`;
                        }
                    });
                } else {
                    const singleResetElement = document.getElementById(resetDropdownIdsArray);
                    if (singleResetElement) {
                        singleResetElement.innerHTML = `<option value = '0'>Select</option>`;
                    }            
                }
            }

            const apiRequestValue = mainDropdown?.value;
            const apiUrl = routeUrl + apiRequestValue;
            fetch(apiUrl, {
                method : "GET",
                headers : {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
            }).then(response => response.json()).then(data => {
                if (mainDropdownElement.id == "customer_id_input") {
                    if (data?.data?.currency_exchange?.status == false || data?.data?.error_message) {
                        Swal.fire({
                            title: 'Error!',
                            text: data?.data?.currency_exchange?.message ? data?.data?.currency_exchange?.message : data?.data?.error_message,
                            icon: 'error',
                        });
                        mainDropdownElement.value = "";
                        document.getElementById('currency_dropdown').innerHTML = "";
                        document.getElementById('currency_dropdown').value = "";
                        document.getElementById('payment_terms_dropdown').innerHTML = "";
                        document.getElementById('payment_terms_dropdown').value = "";
                        document.getElementById('current_billing_address_id').value = "";
                        document.getElementById('current_shipping_address_id').value = "";
                        document.getElementById('current_billing_address').textContent = "";
                        document.getElementById('current_shipping_address').textContent = "";
                        document.getElementById('customer_id_input').value = "";
                        document.getElementById('customer_code_input').value = "";
                        return;
                    }
                    
                }
                // console.clear();
                // console.log(data);
                // return false;
                secondDropdowns.forEach((currentElement, idx) => {
                    if (Array.isArray(currentElement)) {
                        currentElement.forEach(currentElementInternal => {
                            currentElementInternal.innerHTML = `<option value = '0'>Select</option>`;
                            const response = data.data;
                            response?.[dataKeysForApi[idx]]?.forEach(item => {
                                const option = document.createElement('option');
                                option.value = item.value;
                                option.textContent = item.label;
                                currentElementInternal.appendChild(option);
                            })
                        });
                    } else {
                        
                        currentElement.innerHTML = `<option value = '0'>Select</option>`;
                        const response = data.data;
                        response?.[dataKeysForApi[idx]]?.forEach((item, idxx) => {
                            if (idxx == 0) {
                                if (currentElement.id == "billing_address_dropdown") {
                                    document.getElementById('current_billing_address').textContent = item.label;
                                    document.getElementById('current_billing_address_id').value = item.id;
                                    // $('#billing_country_id_input').val(item.country_id).trigger('change');
                                    // changeDropdownOptions(document.getElementById('billing_country_id_input'), ['billing_state_id_input'], ['states'], '/states/', null, ['billing_city_id_input']);
                                }
                                if (currentElement.id == "shipping_address_dropdown") {
                                    document.getElementById('current_shipping_address').textContent = item.label;
                                    document.getElementById('current_shipping_address_id').value = item.id;
                                    document.getElementById('current_shipping_country_id').value = item.country_id;
                                    document.getElementById('current_shipping_state_id').value = item.state_id;
                                    // $('#shipping_country_id_input').val(item.country_id).trigger('change');
                                    // changeDropdownOptions(document.getElementById('shipping_country_id_input'), ['shipping_state_id_input'], ['states'], '/states/', null, ['shipping_city_id_input']);
                                }
                                // if (currentElement.id == "billing_state_id_input") {
                                //     changeDropdownOptions(document.getElementById('billing_state_id_input'), ['billing_city_id_input'], ['cities'], '/cities/', null, []);
                                //     $('#billing_state_id_input').val(item.state_id).trigger('change');
                                //     console.log("STATEID", item);

                                // }
                                // if (currentElement.id == "shipping_state_id_input") {
                                //     changeDropdownOptions(document.getElementById('shipping_state_id_input'), ['shipping_city_id_input'], ['cities'], '/cities/', null, []);
                                //     $('#shipping_state_id_input').val(item.state_id).trigger('change');
                                //     console.log("STATEID", item);

                                // }
                            }
                            const option = document.createElement('option');
                            option.value = item.value;
                            option.textContent = item.label;
                            if (idxx == 0 && (currentElement.id == "billing_address_dropdown" || currentElement.id == "shipping_address_dropdown")) {
                                option.selected = true;
                            }
                            currentElement.appendChild(option);
                        })
                    }
                });
            }).catch(error => {
                mainDropdownElement.value = "";
                document.getElementById('currency_dropdown').innerHTML = "";
                document.getElementById('currency_dropdown').value = "";
                document.getElementById('payment_terms_dropdown').innerHTML = "";
                document.getElementById('payment_terms_dropdown').value = "";
                document.getElementById('current_billing_address_id').value = "";
                document.getElementById('current_shipping_address_id').value = "";
                document.getElementById('current_billing_address').textContent = "";
                document.getElementById('current_shipping_address').textContent = "";
                document.getElementById('customer_id_input').value = "";
                document.getElementById('customer_code_input').value = "";
                console.log("Error : ", error);
                return;
            })
        }

        function itemOnChange(selectedElementId, index, routeUrl) // Retrieve element and set item attiributes
        {
            const selectedElement = document.getElementById(selectedElementId);
            const ItemIdDocument = document.getElementById(selectedElementId + "_value");
            if (selectedElement && ItemIdDocument) {
                ItemIdDocument.value = selectedElement.dataset?.id;
                const apiRequestValue = selectedElement.dataset?.id;
                const apiUrl = routeUrl + apiRequestValue;
                fetch(apiUrl, {
                    method : "GET",
                    headers : {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                }).then(response => response.json()).then(data => {
                    const response = data.data;
                    selectedElement.setAttribute('attribute-array', JSON.stringify(response.attributes));
                    selectedElement.setAttribute('item-name', response.item.item_name);
                    document.getElementById('items_name_' + index).value = response.item.item_name;
                    selectedElement.setAttribute('hsn_code', (response.item_hsn));

                    setItemAttributes('items_dropdown_' + index, index);

                    onItemClick(index);
                    
                }).catch(error => {
                    console.log("Error : ", error);
                })
            }
        }

        function setItemAttributes(elementId, index, disabled = false)
        {
            document.getElementById('attributes_table_modal').setAttribute('item-index',index);
            var elementIdForDropdown = elementId;
            const dropdown = document.getElementById(elementId);
            const attributesTable = document.getElementById('attribute_table');
            if (dropdown) {
                const attributesJSON = JSON.parse(dropdown.getAttribute('attribute-array'));
                var innerHtml = ``;
                attributesJSON.forEach((element, index) => {
                    var optionsHtml = ``;
                    element.values_data.forEach(value => {
                        optionsHtml += `
                        <option value = '${value.id}' ${value.selected ? 'selected' : ''}>${value.value}</option>
                        `
                    });
                    innerHtml += `
                    <tr>
                    <td>
                    ${element.group_name}
                    </td>
                    <td>
                    <select ${disabled ? 'disabled' : ''} class="form-select select2" id = "attribute_val_${index}" style = "max-width:100% !important;" onchange = "changeAttributeVal(this, ${elementIdForDropdown}, ${index});">
                        <option>Select</option>
                        ${optionsHtml}
                    </select> 
                    </td>
                    </tr>
                    `
                });
                attributesTable.innerHTML = innerHtml;
                if (attributesJSON.length == 0) {
                    document.getElementById('item_qty_' + index).focus();
                    document.getElementById('attribute_button_' + index).disabled = true;
                } else {
                    $("#attribute").modal("show");
                    document.getElementById('attribute_button_' + index).disabled = false;
                }
            }

        }

        function changeAttributeVal(selectedElement, elementId, index)
        {
            const attributesJSON = JSON.parse(elementId.getAttribute('attribute-array'));
            const selectedVal = selectedElement.value;
            attributesJSON.forEach((element, currIdx) => {
                if (currIdx == index) {
                    element.values_data.forEach(value => {
                    if (value.id == selectedVal) {
                        value.selected = true;
                    }
                });
                }
            });
            elementId.setAttribute('attribute-array', JSON.stringify(attributesJSON));
        }

        function addItemRow()
        {
            var docType = $("#service_id_input").val();
            var invoiceToFollow = $("#service_id_input").val() == "yes";
            const tableElementBody = document.getElementById('item_header');
            const previousElements = document.getElementsByClassName('item_header_rows');
            const newIndex = previousElements.length ? previousElements.length : 0;
            if (newIndex == 0) {
                let addRow = $('#series_id_input').val() && $("#order_no_input").val() &&  $('#order_no_input').val() && $('#order_date_input').val() && $('#customer_code_input').val();
                if (!addRow) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Please fill all the header details first',
                    icon: 'error',
                });
                return;
                }
            } else {
                let addRow = $('#items_dropdown_' + (newIndex - 1)).val() &&  parseFloat($('#item_qty_' + (newIndex - 1)).val()) > 0;
                if (!addRow) {
                    Swal.fire({
                    title: 'Error!',
                    text: 'Please fill all the previous item details first',
                    icon: 'error',
                });
                return;
                }
            }
            const newItemRow = document.createElement('tr');
            newItemRow.className = 'item_header_rows';
            newItemRow.id = "item_row_" + newIndex;
            newItemRow.onclick = function () {
                onItemClick(newIndex);
            };
            newItemRow.innerHTML = `
            <tr id = "item_row_${newIndex}">
                <td class="customernewsection-form">
                   <div class="form-check form-check-primary custom-checkbox">
                       <input type="checkbox" class="form-check-input item_row_checks" id="item_row_check_${newIndex}" del-index = "${newIndex}">
                       <label class="form-check-label" for="Email"></label>
                   </div> 
               </td>
                <td class="poprod-decpt"> 
                   
                   <input type="text" id = "items_dropdown_${newIndex}" name="item_code[${newIndex}]" placeholder="Select" class="form-control mw-100 ledgerselecct comp_item_code ui-autocomplete-input" autocomplete="off" data-name="" data-code="" data-id="" hsn_code = "" item_name = "" attribute-array = "[]" specs = "[]" item-locations = "[]">
                   <input type = "hidden" name = "item_id[]" id = "items_dropdown_${newIndex}_value"></input>

               </td>
               
               <td class="poprod-decpt">
                    <input type="text" id = "items_name_${newIndex}" name = "item_name[${newIndex}]" class="form-control mw-100"   value = "" readonly>
                </td>
               <td class="poprod-decpt"> 
                   <button id = "attribute_button_${newIndex}" type = "button" data-bs-toggle="modal" onclick = "setItemAttributes('items_dropdown_${newIndex}', ${newIndex});" data-bs-target="#attribute" class="btn p-25 btn-sm btn-outline-secondary" style="font-size: 10px">Attributes</button>
                   <input type = "hidden" name = "attribute_value_${newIndex}" />
                </td>
               <td>
                   <select class="form-select" name = "uom_id[]" id = "uom_dropdown_${newIndex}">
                       
                   </select> 
               </td>
               <td><input type="text" id = "item_qty_${newIndex}" name = "item_qty[${newIndex}]" oninput = "changeItemQty(this, ${newIndex});" class="form-control mw-100 text-end" onblur = "setFormattedNumericValue(this);"/></td>
              <td><input type="text" id = "item_rate_${newIndex}" name = "item_rate[]" ${docType == 'dnote' ? 'readonly' : ''} oninput = "changeItemRate(this, ${newIndex});" class="form-control mw-100 text-end" onblur = "setFormattedNumericValue(this);"/></td> 
               <td><input type="text" id = "item_value_${newIndex}" disabled class="form-control mw-100 text-end item_values_input" /></td>
               <input type = "hidden" id = "header_discount_${newIndex}" value = "0" ></input>
               <input type = "hidden" id = "header_expense_${newIndex}" ></input>
                <td>
                   <div class="position-relative d-flex align-items-center">
                       <input type="text" id = "item_discount_${newIndex}" disabled class="form-control mw-100 text-end item_discounts_input" style="width: 70px" />
                       <div class="ms-50">
                           <button type = "button"  onclick = "onDiscountClick('item_value_${newIndex}', ${newIndex})" data-bs-toggle="modal" data-bs-target="#discount" class="btn p-25 btn-sm btn-outline-secondary" style="font-size: 10px">Add</button>
                       </div>
                   </div>
               </td>
               <input type="hidden" id = "item_tax_${newIndex}" class="form-control mw-100 text-end item_taxes_input" style="width: 70px" />
               <td><input type="text" id = "value_after_discount_${newIndex}"  disabled class="form-control mw-100 text-end item_val_after_discounts_input" /></td>
               <input type = "hidden" id = "value_after_header_discount_${newIndex}" class = "item_val_after_header_discounts_input" ></input>

                    <input type="hidden" id = "item_total_${newIndex}"  disabled class="form-control mw-100 text-end item_totals_input" />                
                    <td>
                    <div class="d-flex">
                        <div ${docType === 'dnote' || docType === 'sinvdnote' ? '' : 'style = "display:none;"'} class="me-50 cursor-pointer item_store_locations" data-bs-toggle="modal" data-bs-target="#location" onclick = "openStoreLocationModal(${newIndex})" data-stores = '[]' id = 'data_stores_${newIndex}'>    <span data-bs-toggle="tooltip" data-bs-placement="top" title="Store Location" class="text-primary"><i data-feather="map-pin"></i></span></div>
                        <div class="me-50 cursor-pointer" data-bs-toggle="modal" data-bs-target="#Remarks" onclick = "setItemRemarks('item_remarks_${newIndex}');">        <span data-bs-toggle="tooltip" data-bs-placement="top" title="Remarks" class="text-primary"><i data-feather="file-text"></i></span></div>
                   </div>
                </td>
                <input type="hidden" id = "item_remarks_${newIndex}" name = "item_remarks[]"/>
             </tr>
            `;
            tableElementBody.appendChild(newItemRow);
            initializeAutocomplete1("items_dropdown_" + newIndex, newIndex);
            renderIcons();
            disableHeader();

            const rateInput = document.getElementById('item_rate_' + newIndex);
            const qtyInput = document.getElementById('item_qty_' + newIndex);
            
            rateInput.addEventListener('input', function() {
                getStoresData(newIndex);
            });
            // qtyInput.addEventListener('input', function() {
            //     const newQty = this.value;
            //     getStoresData(newIndex, newQty);
            // });
        }

        function deleteItemRows()
        {
            var deletedItemIds = JSON.parse(localStorage.getItem('deletedSiItemIds'));
            const allRowsCheck = document.getElementsByClassName('item_row_checks');
            let deleteableElementsId = [];
            for (let index = allRowsCheck.length - 1; index >= 0; index--) {  // Loop in reverse order
                if (allRowsCheck[index].checked) {
                    const currentRowIndex = allRowsCheck[index].getAttribute('del-index');
                    const currentRow = document.getElementById('item_row_' + index);
                    if (currentRow) {
                        if (currentRow.getAttribute('data-id')) {
                            deletedItemIds.push(currentRow.getAttribute('data-id'));
                        }
                        deleteableElementsId.push('item_row_' + currentRowIndex);
                    }
                }
            }
            for (let index = 0; index < deleteableElementsId.length; index++) {
                document.getElementById(deleteableElementsId[index])?.remove();
            }
            localStorage.setItem('deletedSiItemIds', JSON.stringify(deletedItemIds));
            const allRowsNew = document.getElementsByClassName('item_row_checks');
            if (allRowsNew.length > 0) {
                for (let idx = 0; idx < allRowsNew.length; idx++) {
                    const currentRowIndex = allRowsCheck[idx].getAttribute('del-index');
                    if (document.getElementById('item_row_' + currentRowIndex)) {
                        itemRowCalculation(currentRowIndex);
                    }
                }
                disableHeader();
            } else {
                setAllTotalFields();
                enableHeader();
            }
            
        }

        function setItemRemarks(elementId) {
            const currentRemarksValue = document.getElementById(elementId).value;
            const modalInput = document.getElementById('current_item_remarks_input');
            modalInput.value = currentRemarksValue;
            modalInput.setAttribute('current-item', elementId);
        }

        function changeItemRemarks(element)
        {
            var newVal = element.value;
            newVal = newVal.substring(0,255);
            element.value = newVal;
            const elementToBeChanged = document.getElementById(element.getAttribute('current-item'));
            if (elementToBeChanged) {
                elementToBeChanged.value = newVal;
            }
        }

        function changeItemValue(index) // Single Item Value
        {
            const currentElement = document.getElementById('item_value_' + index);
            if (currentElement) {
                const currentQty = document.getElementById('item_qty_' + index).value;
                const currentRate = document.getElementById('item_rate_' + index).value;
                currentElement.value = (parseFloat(currentRate ? currentRate : 0) * parseFloat(currentQty ? currentQty : 0)).toFixed(2);
            }
            getItemTax(index);
            changeItemTotal(index);
            changeAllItemsTotal();
            changeAllItemsTotalTotal();
        }

        function changeItemTotal(index) //Single Item Total
        {
            const currentElementValue = document.getElementById('item_value_' + index).value;
            const currentElementDiscount = document.getElementById('item_discount_' + index).value;
            const newItemTotal = (parseFloat(currentElementValue ? currentElementValue : 0) - parseFloat(currentElementDiscount ? currentElementDiscount : 0)).toFixed(2);
            document.getElementById('item_total_' + index).value = newItemTotal;

        }

        function changeAllItemsValue()
        {

        }

        function changeAllItemsTotal() //All items total value
        {
            const elements = document.getElementsByClassName('item_values_input');
            var totalValue = 0;
            for (let index = 0; index < elements.length; index++) {
                totalValue += parseFloat(elements[index].value ? elements[index].value : 0);
            }
            document.getElementById('all_items_total_value').innerText = (totalValue).toFixed(2);
        }
        function changeAllItemsDiscount() //All items total discount
        {
            const elements = document.getElementsByClassName('item_discounts_input');
            var totalValue = 0;
            for (let index = 0; index < elements.length; index++) {
                totalValue += parseFloat(elements[index].value ? elements[index].value : 0);
            }
            document.getElementById('all_items_total_discount').innerText = (totalValue).toFixed(2);
            changeAllItemsTotalTotal();
        }
        function changeAllItemsTotalTotal() //All items total
        {
            const elements = document.getElementsByClassName('item_totals_input');
            var totalValue = 0;
            for (let index = 0; index < elements.length; index++) {
                totalValue += parseFloat(elements[index].value ? elements[index].value : 0);
            }
            const totalElements = document.getElementsByClassName('all_tems_total_common');
            for (let index = 0; index < totalElements.length; index++) {
                totalElements[index].innerText = (totalValue).toFixed(2);
            }
        }

        function changeItemRate(element, index)
        {
            var inputNumValue = parseFloat(element.value ? element.value  : 0);
            // if (element.hasAttribute('max'))
            // {
            //     var maxInputVal = parseFloat(element.getAttribute('max'));
            //     if (inputNumValue > maxInputVal) {
            //         Swal.fire({
            //             title: 'Error!',
            //             text: 'Amount cannot be greater than ' + maxInputVal,
            //             icon: 'error',
            //         });
            //         element.value = (parseFloat(maxInputVal ? maxInputVal  : 0)).toFixed(2);
            //         itemRowCalculation(index);
            //         return;
            //     }
            // } 
            itemRowCalculation(index);
        }

        function changeItemQty(element, index)
        {
            const docType = $("#service_id_input").val();
            const invoiceToFollow = $("#invoice_to_follow_input").val() == "yes";
            var inputNumValue = parseFloat(element.value ? element.value  : 0);
            if (element.hasAttribute('max'))
            {
                var maxInputVal = parseFloat(element.getAttribute('max'));
                if (inputNumValue > maxInputVal) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Quantity cannot be greater than ' + maxInputVal,
                        icon: 'error',
                    });
                    element.value = (parseFloat(maxInputVal ? maxInputVal  : 0)).toFixed(2)
                    // return;
                }
            }
            itemRowCalculation(index);
            if (docType === 'dnote' || docType === 'sinvdnote') {
                getStoresData(index, element.value);
            }
        }

        function addDiscount(render = true)
        {
            const discountName = document.getElementById('new_discount_name').value;
            const discountId = document.getElementById('new_discount_id').value;
            // const discountType = document.getElementById('new_discount_type').value;
            const discountPercentage = document.getElementById('new_discount_percentage').value;
            const discountValue = document.getElementById('new_discount_value').value;

            const itemRowIndex = document.getElementById('discount_main_table').getAttribute('item-row-index');

            //Check if newly added discount is greater than actual item value
            var existingItemDiscount = document.getElementById('item_discount_' + itemRowIndex).value;
            existingItemDiscount = parseFloat(existingItemDiscount ? existingItemDiscount : 0);
            var newDiscountVal = parseFloat(discountValue ? discountValue : 0);
            const newItemDiscountTotal = existingItemDiscount + newDiscountVal;
            var itemValueTotal = document.getElementById('item_value_' + itemRowIndex).value;
            itemValueTotal = parseFloat(itemValueTotal ? itemValueTotal : 0);
            if (newItemDiscountTotal > itemValueTotal) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Discount cannot be greater than Item Value',
                    icon: 'error',
                });
                return;
            }

            if (discountName && discountId && (discountPercentage || discountValue)) //All fields filled
            {
                // const previousElements = document.getElementsByClassName('item_discounts');
                // const newIndex = previousElements.length ? previousElements.length: 0;
                const ItemRowIndexVal = document.getElementById('discount_main_table').getAttribute('item-row-index');

                const previousHiddenFields = document.getElementsByClassName('discount_names_hidden_' + ItemRowIndexVal);
                addDiscountHiddenInput(document.getElementById('discount_main_table').getAttribute('item-row'), ItemRowIndexVal, previousHiddenFields.length ? previousHiddenFields.length : 0, render);
            }
            else
            {
                console.log("HERE 3");
                Swal.fire({
                    title: 'Warning!',
                    text: 'Please enter all the discount details',
                    icon: 'warning',
                });
            }
            
        }

        function addOrderDiscount(dataId = null, enableExceedCheck = true)
        {
            const discountName = document.getElementById('new_order_discount_name').value;
            const discountId = document.getElementById('new_order_discount_id').value;
            const discountPercentage = document.getElementById('new_order_discount_percentage').value;
            const discountValue = document.getElementById('new_order_discount_value').value;
            if (discountName && discountId && (discountPercentage || discountValue)) //All fields filled
            {
                var existingOrderDiscount = document.getElementById('order_discount_summary') ? document.getElementById('order_discount_summary').textContent : 0;
                existingOrderDiscount = parseFloat(existingOrderDiscount ? existingOrderDiscount : 0);
                var newOrderDiscountVal = parseFloat(discountValue ? discountValue : 0);

                var actualNewOrderDiscount = existingOrderDiscount + newOrderDiscountVal;
                var totalItemsValue = document.getElementById('all_items_total_total') ? document.getElementById('all_items_total_total').textContent : 0;
                totalItemsValue = parseFloat(totalItemsValue ? totalItemsValue : 0);
                if (actualNewOrderDiscount > totalItemsValue && enableExceedCheck) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Discount cannot be greater than Total Item Value',
                        icon: 'error',
                    });
                    return;
                }
                const previousHiddenFields = document.getElementsByClassName('order_discount_value_hidden');
                addOrderDiscountHiddenInput(previousHiddenFields.length ? previousHiddenFields.length : 0, dataId);
            } else {
                console.log("HERE 1");
                Swal.fire({
                    title: 'Warning!',
                    text: 'Please enter all the discount details',
                    icon: 'warning',
                });
                return;
            }
            
        }
        function addOrderExpense(dataId = null, enableExceedCheck = true)
        {
            const expenseName = document.getElementById('order_expense_name').value;
            const expenseId = document.getElementById('order_expense_id').value;
            const expensePercentage = document.getElementById('order_expense_percentage').value;
            const expenseValue = document.getElementById('order_expense_value').value;


            if (expenseName && expenseId && (expensePercentage || expenseValue)) //All fields filled
            {
                var existingOrderExpense = document.getElementById('all_items_total_expenses_summary') ? document.getElementById('all_items_total_expenses_summary').textContent : 0;
                existingOrderExpense = parseFloat(existingOrderExpense ? existingOrderExpense : 0);
                var newOrderExpenseVal = parseFloat(expenseValue ? expenseValue : 0);

                var actualNewOrderExpense = existingOrderExpense + newOrderExpenseVal;
                var totalItemsValueAfterTax = document.getElementById('all_items_total_after_tax_summary') ? document.getElementById('all_items_total_after_tax_summary').textContent : 0;
                totalItemsValueAfterTax = parseFloat(totalItemsValueAfterTax ? totalItemsValueAfterTax : 0);
                // if (actualNewOrderExpense > totalItemsValueAfterTax && enableExceedCheck) {
                //     Swal.fire({
                //         title: 'Error!',
                //         text: 'Expense cannot exceed 100%',
                //         icon: 'error',
                //     });
                //     return;
                // }

                const previousHiddenFields = document.getElementsByClassName('order_expense_value_hidden');
                addOrderExpenseHiddenInput(previousHiddenFields.length ? previousHiddenFields.length : 0, dataId);
            } else {
                console.log("HERE 2");
                Swal.fire({
                    title: 'Warning!',
                    text: 'Please enter all the expense details',
                    icon: 'warning',
                });
                return;
            }
            
        }

        function addDiscountInTable(ItemRowIndexVal, render = true)
        {
                const previousHiddenNameFields = document.getElementsByClassName('discount_names_hidden_' + ItemRowIndexVal);
                const previousHiddenPercentageFields = document.getElementsByClassName('discount_percentages_hidden_' + ItemRowIndexVal);
                const previousHiddenValuesFields = document.getElementsByClassName('discount_values_hidden_' + ItemRowIndexVal);

                const newIndex = previousHiddenNameFields.length ? previousHiddenNameFields.length : 0;

                var newData = ``;
                for (let index = newIndex- 1; index < previousHiddenNameFields.length; index++) {
                    const newHTML = document.getElementById('discount_main_table').insertRow(index + 2);
                    newHTML.className = "item_discounts";
                    newHTML.id = "item_discount_modal_" + newIndex;
                    newData = `
                        <td>${index+1}</td>
                        <td>${previousHiddenNameFields[index].value}</td>
                        <td>${previousHiddenPercentageFields[index].value? parseFloat(previousHiddenPercentageFields[index].value).toFixed(2) : ''}</td>
                        <td class = "dynamic_discount_val_${ItemRowIndexVal}">${parseFloat(previousHiddenValuesFields[index].value).toFixed(2)}</td>
                        <td>
                            <a href="#" class="text-danger" onclick = "removeDiscount(${index}, ${ItemRowIndexVal});"><i data-feather="trash-2"></i></a>
                        </td>
                    `;
                    newHTML.innerHTML = newData;
                }
                
                document.getElementById('new_discount_name').value = "";
                document.getElementById('new_discount_id').value = "";
                // document.getElementById('new_discount_type').value = "";
                document.getElementById('new_discount_percentage').value = "";
                document.getElementById('new_discount_percentage').disabled = false;
                document.getElementById('new_discount_value').value = "";
                document.getElementById('new_discount_value').disabled = false;
                if (render) {
                    // setModalDiscountTotal('item_discount_' + ItemRowIndexVal, ItemRowIndexVal);
                    itemRowCalculation(ItemRowIndexVal);
                }



                renderIcons();
        }

        function getTotalorderDiscounts(itemCalculation = true)
        {
            const values = document.getElementsByClassName('order_discount_value_hidden');
            let discount = 0;
            for (let index = 0; index < values.length; index++) {
                discount += parseFloat(values[index].value ? values[index].value : 0);
            }
            const summaryTable = document.getElementById('summary_table');
            if (discount > 0) { //Add in summary
                const discountRow = document.getElementById('order_discount_row');
                if (discountRow) {
                    discountRow.innerHTML = `
                        <td width="55%">Header Discount</td>  
                        <td class="text-end" id = "order_discount_summary" >${discount}</td>
                    `
                } else {
                    const newRow = summaryTable.insertRow(3);
                    newRow.id = "order_discount_row";
                    newRow.innerHTML = `
                    <td width="55%">Header Discount</td>  
                        <td class="text-end" id = "order_discount_summary" >${discount}</td>
                    `;
                }
            } else {// Remove from summary
                let lastDiscountRow = document.getElementById('order_discount_row');
                if (lastDiscountRow) {
                    lastDiscountRow.remove();
                }
            }
            document.getElementById('total_order_discount').textContent = parseFloat(discount ? discount : 0);
            if (itemCalculation)
            {
                const itemData = document.getElementsByClassName('item_header_rows');
                for (let ix = 0; ix < itemData.length; ix++) {
                    itemRowCalculation(ix);
                }
            }
        }

        function getTotalOrderExpenses()
        {
            const values = document.getElementsByClassName('order_expense_value_hidden');
            let expense = 0;
            for (let index = 0; index < values.length; index++) {
                expense += parseFloat(values[index].value ? values[index].value : 0);
            }            
            document.getElementById('all_items_total_expenses_summary').textContent = parseFloat(expense ? expense : 0);
            document.getElementById('total_order_expense').textContent = parseFloat(expense ? expense : 0);
            setAllTotalFields();
        }

        function addOrderDiscountInTable(index)
        {
                const previousHiddenNameFields = document.getElementsByClassName('order_discount_name_hidden');
                const previousHiddenPercentageFields = document.getElementsByClassName('order_discount_percentage_hidden');
                const previousHiddenValuesFields = document.getElementsByClassName('order_discount_value_hidden');

                const newIndex = previousHiddenNameFields.length ? previousHiddenNameFields.length : 0;
                
                var newData = ``;
                var totalSummaryDiscount = 0;
                var total = parseFloat(document.getElementById('total_order_discount').textContent ? document.getElementById('total_order_discount').textContent : 0);
                for (let index = newIndex - 1; index < previousHiddenNameFields.length; index++) {
                    const newHTML = document.getElementById('order_discount_main_table').insertRow(index+2);
                    newHTML.className = "order_discounts";
                    newHTML.id = "order_discount_modal_" + (parseInt(newIndex - 1));
                    newData = `
                        <td>${index+1}</td>
                        <td>${previousHiddenNameFields[index].value}</td>
                        <td>${previousHiddenPercentageFields[index].value ? parseFloat(previousHiddenPercentageFields[index].value).toFixed(2) : ''}</td>
                        <td id = "order_discount_input_val_${index}">${parseFloat(previousHiddenValuesFields[index].value).toFixed(2)}</td>
                        <td>
                            <a href="#" class="text-danger" onclick = "removeOrderDiscount(${newIndex - 1});"><i data-feather="trash-2"></i></a>
                        </td>
                    `;
                    newHTML.innerHTML = newData;
                    total+= parseFloat(previousHiddenValuesFields[index].value ? previousHiddenValuesFields[index].value : 0);
                    totalSummaryDiscount += parseFloat(previousHiddenValuesFields[index].value ? previousHiddenValuesFields[index].value : 0);
                }

                document.getElementById('new_order_discount_name').value = "";
                document.getElementById('new_order_discount_id').value = "";
                document.getElementById('new_order_discount_percentage').value = "";
                document.getElementById('new_order_discount_percentage').disabled = false;
                document.getElementById('new_order_discount_value').value = "";
                document.getElementById('new_order_discount_value').disabled = false;
                document.getElementById('total_order_discount').textContent = total;
                renderIcons();

                getTotalorderDiscounts();
        }


        function addOrderExpenseInTable(index)
        {
                const previousHiddenNameFields = document.getElementsByClassName('order_expense_name_hidden');
                const previousHiddenPercentageFields = document.getElementsByClassName('order_expense_percentage_hidden');
                const previousHiddenValuesFields = document.getElementsByClassName('order_expense_value_hidden');

                const newIndex = previousHiddenNameFields.length ? previousHiddenNameFields.length : 0;
                
                var newData = ``;
                var totalSummaryExpense = 0;
                var total = parseFloat(document.getElementById('total_order_expense').textContent ? document.getElementById('total_order_expense').textContent : 0);
                for (let index = newIndex - 1; index < previousHiddenNameFields.length; index++) {
                    const newHTML = document.getElementById('order_expense_main_table').insertRow(index+2);
                    newHTML.className = "order_expenses";
                    newHTML.id = "order_expense_modal_" + (parseInt(newIndex - 1));
                    newData = `
                        <td>${index+1}</td>
                        <td>${previousHiddenNameFields[index].value}</td>
                        <td>${previousHiddenPercentageFields[index].value ? parseFloat(previousHiddenPercentageFields[index].value).toFixed(2) : ''}</td>
                        <td id = "order_expense_input_val_${index}">${parseFloat(previousHiddenValuesFields[index].value).toFixed(2)}</td>
                        <td>
                            <a href="#" class="text-danger" onclick = "removeOrderExpense(${newIndex - 1});"><i data-feather="trash-2"></i></a>
                        </td>
                    `;
                    total+= parseFloat(previousHiddenValuesFields[index].value ? previousHiddenValuesFields[index].value : 0);
                    newHTML.innerHTML = newData;
                    totalSummaryExpense += parseFloat(previousHiddenValuesFields[index].value ? previousHiddenValuesFields[index].value : 0);
                }
                
                document.getElementById('order_expense_name').value = "";
                document.getElementById('order_expense_id').value = "";
                document.getElementById('order_expense_percentage').value = "";
                document.getElementById('order_expense_percentage').disabled = false;
                document.getElementById('order_expense_value').value = "";
                document.getElementById('order_expense_value').disabled = false;
                document.getElementById('total_order_expense').textContent = total;

                renderIcons();

                getTotalOrderExpenses();
        }

        function removeDiscount(index, itemIndex)
        {
            let deletedDiscountTedIds = JSON.parse(localStorage.getItem('deletedItemDiscTedIds'));
            const removableElement = document.getElementById('item_discount_modal_' + index);
            if (removableElement) {
                if (removableElement.getAttribute('data-id')) {
                    deletedDiscountTedIds.push(removableElement.getAttribute('data-id'));
                }
                removableElement.remove();
            }
            document.getElementById("item_discount_name_" + itemIndex + "_" + index)?.remove();
            document.getElementById("item_discount_percentage_" + itemIndex + "_" + index)?.remove();
            document.getElementById("item_discount_value_" + itemIndex + "_" + index)?.remove();
            document.getElementById("item_discount_master_id_" + itemIndex + "_" + index)?.remove();
            localStorage.setItem('deletedItemDiscTedIds', JSON.stringify(deletedDiscountTedIds));
            renderPreviousDiscount(itemIndex);
            // setModalDiscountTotal('item_discount_' + itemIndex, itemIndex);
            itemRowCalculation(itemIndex);
        }
        function removeOrderDiscount(index)
        {
            let deletedHeaderDiscTedIds = JSON.parse(localStorage.getItem('deletedHeaderDiscTedIds'));
            const removableElement = document.getElementById('order_discount_modal_' + index);
            if (removableElement) {
                removableElement.remove();
                if (removableElement.getAttribute('data-id')) {
                    deletedHeaderDiscTedIds.push(removableElement.getAttribute('data-id'));
                }
            }
            document.getElementById("order_discount_name_" + index).remove();
            document.getElementById("order_discount_percentage_" + index).remove();
            document.getElementById("order_discount_value_" + index).remove();
            document.getElementById("order_discount_master_id_" + index)?.remove();
            localStorage.setItem('deletedHeaderDiscTedIds', JSON.stringify(deletedHeaderDiscTedIds));
            // renderPreviousDiscount(itemIndex);
            // setModalDiscountTotal('item_discount_' + itemIndex, itemIndex);
            getTotalorderDiscounts();
        }
        function removeOrderExpense(index)
        {
            let deletedHeaderExpTedIds = JSON.parse(localStorage.getItem('deletedHeaderExpTedIds'));
            const removableElement = document.getElementById('order_expense_modal_' + index);
            if (removableElement) {
                removableElement.remove();
                if (removableElement.getAttribute('data-id')) {
                    deletedHeaderExpTedIds.push(removableElement.getAttribute('data-id'));
                }
            }
           
            document.getElementById("order_expense_name_" + index)?.remove();
            document.getElementById("order_expense_percentage_" + index)?.remove();
            document.getElementById("order_expense_value_" + index)?.remove();
            document.getElementById("order_expense_master_id_" + index)?.remove();
            localStorage.setItem('deletedHeaderExpTedIds', JSON.stringify(deletedHeaderExpTedIds));
            getTotalOrderExpenses();
            // renderPreviousDiscount(itemIndex);
            // setModalDiscountTotal('item_discount_' + itemIndex, itemIndex);
        }

        function changeDiscountType(element)
        {
            if (element.value === "Fixed") {
                document.getElementById('new_discount_percentage').disabled = true;
                document.getElementById('new_discount_percentage').value = "";
                document.getElementById('new_discount_value').disabled = false;
                document.getElementById('new_discount_value').value = "";
            } else {
                document.getElementById('new_discount_percentage').disabled = false;
                document.getElementById('new_discount_percentage').value = "";
                document.getElementById('new_discount_value').disabled = true;
                document.getElementById('new_discount_value').value = "";
            }
        }

        function onDiscountClick(elementId, itemRowIndex)
        {
            const totalValue = document.getElementById(elementId).value;
            document.getElementById('discount_main_table').setAttribute('total-value', totalValue);
            document.getElementById('discount_main_table').setAttribute('item-row', elementId);
            document.getElementById('discount_main_table').setAttribute('item-row-index', itemRowIndex);
            renderPreviousDiscount(itemRowIndex);
            initializeAutocompleteTed("new_discount_name", "new_discount_id", 'sales_module_discount', 'new_discount_percentage');
        }

        function renderPreviousDiscount(ItemRowIndexVal)
        {
                const previousHiddenNameFields = document.getElementsByClassName('discount_names_hidden_' + ItemRowIndexVal);
                const previousHiddenPercentageFields = document.getElementsByClassName('discount_percentages_hidden_' + ItemRowIndexVal);
                const previousHiddenValuesFields = document.getElementsByClassName('discount_values_hidden_' + ItemRowIndexVal);
                                    
                    const oldDiscounts = document.getElementsByClassName('item_discounts');
                    if (oldDiscounts && oldDiscounts.length > 0)
                    {
                        while (oldDiscounts.length > 0) {
                            oldDiscounts[0].remove();
                        }
                    }

                var newData = ``;
                for (let index = 0; index < previousHiddenNameFields.length; index++) {
                    const newHTML = document.getElementById('discount_main_table').insertRow(index + 2);
                    newHTML.id = "item_discount_modal_" + index;
                    newHTML.className = "item_discounts";
                    newData = `
                        <td>${index+1}</td>
                        <td>${previousHiddenNameFields[index].value}</td>
                        <td>${previousHiddenPercentageFields[index].value ? parseFloat(previousHiddenPercentageFields[index].value).toFixed(2) : ''}</td>
                        <td class = "dynamic_discount_val_${ItemRowIndexVal}">${parseFloat(previousHiddenValuesFields[index].value).toFixed(2)}</td>
                        <td>
                            <a href="#" class="text-danger" onclick = "removeDiscount(${index}, ${ItemRowIndexVal});"><i data-feather="trash-2"></i></a>
                        </td>
                    `;
                    newHTML.innerHTML = newData;
                }
                
                document.getElementById('new_discount_name').value = "";
                document.getElementById('new_discount_id').value = "";
                // document.getElementById('new_discount_type').value = "";
                document.getElementById('new_discount_percentage').value = "";
                document.getElementById('new_discount_percentage').disabled = false;
                document.getElementById('new_discount_value').value = "";
                document.getElementById('new_discount_value').disabled = false;
                setModalDiscountTotal('item_discount_' + ItemRowIndexVal, ItemRowIndexVal);
                itemRowCalculation(ItemRowIndexVal);

                renderIcons();
        }

        function onChangeDiscountPercentage(element)
        {
            document.getElementById('new_discount_value').disabled = element.value ? true : false;
            const totalValue = document.getElementById('item_value_' + document.getElementById('discount_main_table').getAttribute('item-row-index')).value;
            if (totalValue) {
                document.getElementById('new_discount_value').value = (parseFloat(totalValue) * parseFloat(element.value/100)).toFixed(2);
            }
        }
        function onChangeOrderDiscountPercentage(element)
        {
            document.getElementById('new_order_discount_value').disabled = element.value ? true : false;
            const totalValue = document.getElementById('all_items_total_value_summary').textContent;
            const totalItemDiscount = document.getElementById('all_items_total_discount_summary').textContent;
            let totalAfterItemDiscount = parseFloat(totalValue ? totalValue : 0) - parseFloat(totalItemDiscount ? totalItemDiscount : 0);
            if (totalAfterItemDiscount) {
                document.getElementById('new_order_discount_value').value = (parseFloat(totalAfterItemDiscount ? totalAfterItemDiscount : 0) * parseFloat(element.value/100)).toFixed(2);
            }
        }
        function onChangeOrderExpensePercentage(element)
        {
            document.getElementById('order_expense_value').disabled = element.value ? true : false;
            const totalValue = document.getElementById('all_items_total_after_tax_summary').textContent;
            if (totalValue) {
                document.getElementById('order_expense_value').value = (parseFloat(totalValue ? totalValue : 0) * parseFloat(element.value/100)).toFixed(2);
            }
        }
        function onChangeDiscountValue(element)
        {
            document.getElementById('new_discount_percentage').disabled = element.value ? true : false;
        }

        function onChangeOrderDiscountValue(element)
        {
            document.getElementById('new_order_discount_percentage').disabled = element.value ? true : false;
        }
        function onChangeOrderExpenseValue(element)
        {
            document.getElementById('order_expense_percentage').disabled = element.value ? true : false;
        }

        function setModalDiscountTotal(elementId, index)
        {
            var totalDiscountModalVal = 0;
            const docs = document.getElementsByClassName('discount_values_hidden_' + index);
            for (let index = 0; index < docs.length; index++) {
                totalDiscountModalVal += parseFloat(docs[index].value ? docs[index].value : 0);
            }
            document.getElementById('total_item_discount').textContent = totalDiscountModalVal.toFixed(2);
            document.getElementById(elementId).value = totalDiscountModalVal.toFixed(2);
            // changeItemTotal(index);
            // changeAllItemsDiscount();
            // itemRowCalculation(index);
        }

        function addDiscountHiddenInput(itemRow, index, discountIndex, render = true)
        {
            addHiddenInput("item_discount_name_" + index + "_" + discountIndex, document.getElementById('new_discount_name').value, `item_discount_name[${index}][${discountIndex}]`, 'discount_names_hidden_' + index, itemRow);
            addHiddenInput("item_discount_master_id_" + index + "_" + discountIndex, document.getElementById('new_discount_id').value, `item_discount_master_id[${index}][${discountIndex}]`, 'discount_master_id_hidden_' + index, itemRow);
            // addHiddenInput("item_discount_type_" + index + "_" + discountIndex, document.getElementById('new_discount_type').value, `item_discount_type[${index}][${discountIndex}]`, 'discount_types_hidden_' + index, itemRow);
            addHiddenInput("item_discount_percentage_" + index + "_" + discountIndex, document.getElementById('new_discount_percentage').value, `item_discount_percentage[${index}][${discountIndex}]`, 'discount_percentages_hidden_' + index,  itemRow);
            addHiddenInput("item_discount_value_" + index + "_" + discountIndex, document.getElementById('new_discount_value').value, `item_discount_value[${index}][${discountIndex}]`, 'discount_values_hidden_' + index, itemRow);
            addDiscountInTable(index, render);
        }

        function addOrderDiscountHiddenInput(index, dataId = null)
        {
            addHiddenInput("order_discount_name_" + index, document.getElementById('new_order_discount_name').value, `order_discount_name[${index}]`, 'order_discount_hidden_fields order_discount_name_hidden', 'main_so_form', dataId);
            addHiddenInput("order_discount_master_id_" + index, document.getElementById('new_order_discount_id').value, `order_discount_master_id[${index}]`, 'order_discount_hidden_fields order_discount_master_id_hidden', 'main_so_form', dataId);
            addHiddenInput("order_discount_percentage_" + index, document.getElementById('new_order_discount_percentage').value, `order_discount_percentage[${index}]`, 'order_discount_hidden_fields order_discount_percentage_hidden', 'main_so_form', dataId);
            addHiddenInput("order_discount_value_" + index, document.getElementById('new_order_discount_value').value, `order_discount_value[${index}]`, 'order_discount_hidden_fields order_discount_value_hidden', 'main_so_form', dataId);
            if (dataId) {
                addHiddenInput("order_discount_id_" + index, dataId, `order_discount_id[${index}]`, 'order_discount_hidden_fields order_discount_id_hidden', 'main_so_form');
            }
            addOrderDiscountInTable(index);
        }

        function addOrderExpenseHiddenInput(index, dataId = null)
        {
            addHiddenInput("order_expense_name_" + index, document.getElementById('order_expense_name').value, `order_expense_name[${index}]`, 'order_expense_hidden_fields order_expense_name_hidden', 'main_so_form', dataId);
            addHiddenInput("order_expense_master_id_" + index, document.getElementById('order_expense_id').value, `order_expense_master_id[${index}]`, 'order_expense_hidden_fields order_expense_master_id_hidden', 'main_so_form', dataId);
            addHiddenInput("order_expense_percentage_" + index, document.getElementById('order_expense_percentage').value, `order_expense_percentage[${index}]`, 'order_expense_hidden_fields order_expense_percentage_hidden', 'main_so_form', dataId);
            addHiddenInput("order_expense_value_" + index, document.getElementById('order_expense_value').value, `order_expense_value[${index}]`, 'order_expense_hidden_fields order_expense_value_hidden', 'main_so_form', dataId);
            if (dataId) {
                addHiddenInput("order_expense_id_" + index, dataId, `order_expense_id[${index}]`, 'order_expense_hidden_fields order_expense_id_hidden', 'main_so_form');
            }
            addOrderExpenseInTable(index);
        }

        function addHiddenInput(id, val, name, classname, docId, dataId = null)
        {
            const newHiddenInput = document.createElement("input");
            newHiddenInput.setAttribute("type", "hidden");
            newHiddenInput.setAttribute("name", name);
            newHiddenInput.setAttribute("id", id);
            newHiddenInput.setAttribute("value", val);
            newHiddenInput.setAttribute("class", classname);
            newHiddenInput.setAttribute('data-id', dataId ? dataId : '');
            document.getElementById(docId).appendChild(newHiddenInput);
        }

        function renderIcons()
        {
            feather.replace()
        }

        function onItemClick(itemRowId)
        {
            const docType = $("#service_id_input").val();
            const invoiceToFollowParam = $("invoice_to_follow_input").val() == "yes";

            const hsn_code = document.getElementById('items_dropdown_'+ itemRowId).getAttribute('hsn_code');
            const item_name = document.getElementById('items_dropdown_'+ itemRowId).getAttribute('item-name');
            const attributes = JSON.parse(document.getElementById('items_dropdown_'+ itemRowId).getAttribute('attribute-array'));
            const specs = JSON.parse(document.getElementById('items_dropdown_'+ itemRowId).getAttribute('specs'));
            const locations = JSON.parse(decodeURIComponent(document.getElementById('data_stores_'+ itemRowId).getAttribute('data-stores')));

            const qtDetailsRow = document.getElementById('current_item_qt_no_row');
            const qtDetails = document.getElementById('current_item_qt_no');

            //Reference From 
            const referenceFromLabels = document.getElementsByClassName("reference_from_label_" + itemRowId);
            if (referenceFromLabels && referenceFromLabels.length > 0)
            {
                qtDetailsRow.style.display = "table-row";
                referenceFromLabelsHTML = `<strong style = "font-size:11px; color : #6a6a6a;">Reference From</strong>`;
                for (let index = 0; index < referenceFromLabels.length; index++) {
                    referenceFromLabelsHTML += `<span class="badge rounded-pill badge-light-primary">${referenceFromLabels[index].value}</span>`
                }
                qtDetails.innerHTML = referenceFromLabelsHTML;
            }
            else 
            {
                qtDetailsRow.style.display = "none";
                qtDetails.innerHTML = ``;
            }
            

            const leaseAgreementDetailsRow = document.getElementById('current_item_land_lease_agreement_row');
            const leaseAgreementDetails = document.getElementById('current_item_land_lease_agreement');
            //assign agreement details
            let agreementNo = document.getElementById('land_lease_agreement_no_' + itemRowId)?.value;
            let leaseEndDate = document.getElementById('land_lease_end_date_' + itemRowId)?.value;
            let leaseDueDate = document.getElementById('land_lease_due_date_' + itemRowId)?.value;
            let repaymentPeriodType = document.getElementById('land_lease_repayment_period_' + itemRowId)?.value;

            if (agreementNo && leaseEndDate && leaseDueDate && repaymentPeriodType) {
                leaseAgreementDetails.style.display = "table-row";
                leaseAgreementDetails.innerHTML = `<strong style = "font-size:11px; color : #6a6a6a;">Agreement Details</strong>:<span class="badge rounded-pill badge-light-primary"><strong>Agreement No</strong>: ${agreementNo}</span><span class="badge rounded-pill badge-light-primary"><strong>Lease End Date</strong>: ${leaseEndDate}</span><span class="badge rounded-pill badge-light-primary"><strong>Repayment Schedule</strong>: ${repaymentPeriodType}</span><span class="badge rounded-pill badge-light-primary"><strong>Due Date</strong>: ${leaseDueDate}</span>`;
            } else {
                leaseAgreementDetails.style.display = "none";
                leaseAgreementDetails.innerHTML = "";
            }
            //assign land plot details
            let parcelName = document.getElementById('land_lease_land_parcel_' + itemRowId)?.value;
            let plotsName = document.getElementById('land_lease_land_plots_' + itemRowId)?.value;

            let qtDocumentNo = document.getElementById('qt_document_no_'+ itemRowId);
            let qtBookCode = document.getElementById('qt_book_code_'+ itemRowId);
            let qtDocumentDate = document.getElementById('qt_document_date_'+ itemRowId);

            const storeDivRow = document.getElementById('current_item_store_location_row');
            const storeDiv = document.getElementById('current_item_store_location');
            let locationsHTML = ``;
            if (locations && locations.length > 0) {
                locations.forEach((loc) => {
                    locationsHTML += `<span class="badge rounded-pill badge-light-primary"><strong>Location</strong>: ${loc.store_code}</span><span class="badge rounded-pill badge-light-primary"><strong>Rack</strong>: ${loc.rack_code}</span><span class="badge rounded-pill badge-light-primary"><strong>Shelf</strong>: ${loc.shelf_code}</span><span class="badge rounded-pill badge-light-primary"><strong>Bin</strong>: ${loc.bin_code}</span><span class="badge rounded-pill badge-light-primary"><strong>Qty</strong>: ${loc.qty}</span><br/><br/>`;
                });
                storeDivRow.style.display = "table-row";
                storeDiv.innerHTML = locationsHTML;
            } else {
                storeDivRow.style.display = "none";
                storeDiv.innerHTML = locationsHTML;
            }

            qtDocumentNo = qtDocumentNo?.value ? qtDocumentNo.value : '';
            qtBookCode = qtBookCode?.value ? qtBookCode.value : '';
            qtDocumentDate = qtDocumentDate?.value ? qtDocumentDate.value : '';

            // if (qtDocumentNo && qtBookCode && qtDocumentDate) {
            //     qtDetailsRow.style.display = "table-row";
            //     qtDetails.innerHTML = `<strong style = "font-size:11px; color : #6a6a6a;">Reference From</strong>:<span class="badge rounded-pill badge-light-primary"><strong>Document No: </strong>: ${qtBookCode + "-" + qtDocumentNo}</span><span class="badge rounded-pill badge-light-primary"><strong>Document Date: </strong>: ${qtDocumentDate}</span>`;

            //     if (parcelName && plotsName) {
            //         qtDetails.innerHTML =  qtDetails.innerHTML + `<span class="badge rounded-pill badge-light-primary"><strong>Land Parcel</strong>: ${parcelName}</span><span class="badge rounded-pill badge-light-primary"><strong>Plots</strong>: ${plotsName}</span>`;
            //     }
            // } else {
            //     qtDetailsRow.style.display = "none";
            //     qtDetails.innerHTML = ``;
            // }
            // document.getElementById('current_item_hsn_code').innerText = hsn_code;
            var innerHTMLAttributes = ``;
            attributes.forEach(element => {
                var currentOption = '';
                element.values_data.forEach(subElement => {
                    if (subElement.selected) {
                        currentOption = subElement.value;
                    }
                });
                innerHTMLAttributes +=  `<span class="badge rounded-pill badge-light-primary"><strong>${element.group_name}</strong>: ${currentOption}</span>`;
            });
            var specsInnerHTML = ``;
            specs.forEach(spec => {
                specsInnerHTML +=  `<span class="badge rounded-pill badge-light-primary "><strong>${spec.specification_name}</strong>: ${spec.description}</span>`;
            });

            document.getElementById('current_item_attributes').innerHTML = `<strong style = "font-size:11px; color : #6a6a6a;">Attributes</strong>:` + innerHTMLAttributes;
            if (innerHTMLAttributes) {
                document.getElementById('current_item_attribute_row').style.display = "table-row";
            } else {
                document.getElementById('current_item_attribute_row').style.display = "none";
            }
            document.getElementById('current_item_specs').innerHTML = `<strong style = "font-size:11px; color : #6a6a6a;">Specifications</strong>:` + specsInnerHTML;
            if (specsInnerHTML) {
                document.getElementById('current_item_specs_row').style.display = "table-row";
            } else {
                document.getElementById('current_item_specs_row').style.display = "none";
            }
            const remarks = document.getElementById('item_remarks_' + itemRowId).value;
            if (specsInnerHTML) {
                document.getElementById('current_item_specs_row').style.display = "table-row";
            } else {
                document.getElementById('current_item_specs_row').style.display = "none";
            }
            document.getElementById('current_item_description').textContent = remarks;
            if (remarks) {
                document.getElementById('current_item_description_row').style.display = "table-row";
            } else {
                document.getElementById('current_item_description_row').style.display = "none";
            }

            let itemAttributes = JSON.parse(document.getElementById(`items_dropdown_${itemRowId}`).getAttribute('attribute-array'));
                    let selectedItemAttr = [];
                    if (itemAttributes && itemAttributes.length > 0) {
                        itemAttributes.forEach(element => {
                        element.values_data.forEach(subElement => {
                            if (subElement.selected) {
                                selectedItemAttr.push(subElement.id);
                            }
                        });
                    });
                    }
                        $.ajax({
                            url: "{{route('get_item_inventory_details')}}",
                            method: 'GET',
                            dataType: 'json',
                            data: {
                                quantity: document.getElementById('item_qty_' + itemRowId).value,
                                item_id: document.getElementById('items_dropdown_'+ itemRowId + '_value').value,
                                uom_id : document.getElementById('uom_dropdown_' + itemRowId).value,
                                selectedAttr : selectedItemAttr
                            },
                            success: function(data) {
                                if (data.inv_qty && data.inv_uom) {
                                    document.getElementById('current_item_inventory').style.display = 'table-row';
                                    document.getElementById('current_item_inventory_details').innerHTML = `
                                    <span class="badge rounded-pill badge-light-primary"><strong>Inv. UOM</strong>: ${data.inv_uom}</span>
                                    <span class="badge rounded-pill badge-light-primary"><strong>Qty in ${data.inv_uom}</strong>: ${data.inv_qty}</span>
                                    `;
                                } else {
                                    document.getElementById('current_item_inventory').style.display = 'none';
                                    document.getElementById('current_item_inventory_details').innerHTML = ``;
                                }
                                
                                if (data?.item && data?.item?.category && data?.item?.sub_category) {
                                    document.getElementById('current_item_cat_hsn').innerHTML = `
                                    <span class="badge rounded-pill badge-light-primary"><strong>Category</strong>: <span id = "item_category">${ data?.item?.category?.name}</span></span>
                                    <span class="badge rounded-pill badge-light-primary"><strong>Sub Category</strong>: <span id = "item_sub_category">${ data?.item?.sub_category?.name}</span></span>
                                    <span class="badge rounded-pill badge-light-primary"><strong>HSN</strong>: <span id = "current_item_hsn_code">${hsn_code}</span></span>
                                    `;
                                }
                                //Stocks
                                console.log(docType, "DOCC TYPPE");
                                if (docType === 'dnote' || docType === 'sinvdnote') {
                                    if (data?.stocks && docType == "dnote") {
                                    document.getElementById('current_item_stocks_row').style.display = "table-row";
                                    document.getElementById('current_item_stocks').innerHTML = `
                                    <span class="badge rounded-pill badge-light-primary"><strong>Confirmed Stocks</strong>: <span id = "item_sub_category">${data?.stocks?.confirmedStocks}</span></span>
                                    <span class="badge rounded-pill badge-light-primary"><strong>Pending Stocks</strong>: <span id = "item_category">${data?.stocks?.pendingStocks}</span></span>
                                    `;
                                    var inputQtyBox = document.getElementById('item_qty_' + itemRowId);
                                    if (!inputQtyBox.value) {
                                        inputQtyBox.value = parseFloat(data.stocks.confirmedStocks).toFixed(2);
                                    }
                                    inputQtyBox.addEventListener('input', function() {
                                        var value = parseFloat(inputQtyBox.value);
                                        if (value > data.stocks.confirmedStocks) {
                                            inputQtyBox.value = parseFloat(data.stocks.confirmedStocks).toFixed(2);
                                            Swal.fire({
                                                title: 'Error!',
                                                text: 'Qty cannot be greater than confirmed stocks',
                                                icon: 'error',
                                            });
                                        }
                                    });
                                    
                                    } else {
                                        document.getElementById('current_item_stocks_row').style.display = "none";
                                    }  
                                } else {
                                        document.getElementById('current_item_stocks_row').style.display = "none";
                                    } 
                                //Stores
                                // if (data?.stores && data?.stores?.length > 0) {
                                //     var storesInnerHTML = ``;
                                //     data?.stores.forEach(storeData => {
                                //         storesInnerHTML += `
                                //         <span class="badge rounded-pill badge-light-primary"><strong>Store</strong>: ${storeData.store_code}</span>
                                //         <span class="badge rounded-pill badge-light-primary"><strong>Rack</strong>: ${storeData.rack_code}</span>
                                //         <span class="badge rounded-pill badge-light-primary"><strong>Bin</strong>: ${storeData.bin_code}</span>
                                //         <span class="badge rounded-pill badge-light-primary"><strong>Shelf</strong>: ${storeData.shelf_code}</span>
                                //         <span class="badge rounded-pill badge-light-primary"><strong>Qty</strong>: ${storeData.receipt_qty}</span>
                                //         <span class="badge rounded-pill badge-light-primary mb-1"><strong>Date</strong>: ${storeData.document_date}</span>
                                //         <br/>
                                //         `;
                                //     });
                                //     document.getElementById('current_item_stores').innerHTML = storesInnerHTML;
                                // }
                            },
                            error: function(xhr) {
                                console.error('Error fetching customer data:', xhr.responseText);
                            }
                        });

                    var rateInput = document.getElementById('item_rate_'+ itemRowId);
                    var qtyInput = document.getElementById('item_qty_'+ itemRowId);

                    
        }

        function getStoresData(itemRowId, qty = null, callOnClick = true)
        {
            const docType = $("#service_id_input").val();
            const invoiceToFollow = $("#invoice_to_follow_input").val() == "yes";
            if (docType === 'dnote' || docType === 'sinvdnote') {
                const itemDetailId = document.getElementById('item_row_' + itemRowId).getAttribute('data-detail-id');
            const itemId = document.getElementById('items_dropdown_'+ itemRowId).getAttribute('data-id');
            let itemAttributes = JSON.parse(document.getElementById(`items_dropdown_${itemRowId}`).getAttribute('attribute-array'));
                    let selectedItemAttr = [];
                    if (itemAttributes && itemAttributes.length > 0) {
                        itemAttributes.forEach(element => {
                        element.values_data.forEach(subElement => {
                            if (subElement.selected) {
                                selectedItemAttr.push(subElement.id);
                            }
                        });
                    });
                    }
                        const storeElement = document.getElementById('data_stores_' + itemRowId);

                        // document.getElementById('current_item_stores').innerHTML = ``;
                        $.ajax({
                        url: "{{route('get_item_store_details')}}",
                            method: 'GET',
                            dataType: 'json',
                            data : {
                                item_id : itemId,
                                uom_id : $("#uom_dropdown_" + itemRowId).val(),
                                selectedAttr : selectedItemAttr,
                                quantity : qty ? qty : document.getElementById('item_qty_' + itemRowId).value,
                                is_edit : "{{isset($order) ? 1 : 0}}",
                                header_id : "{{isset($order) ? $order -> id : null}}",
                                detail_id : itemDetailId
                            },
                            success: function(data) {
                                if (data?.stores && data?.stores?.records && data?.stores?.records?.length > 0 && data.stores.code == 200) {
                                    var storesArray = [];
                                    var dataRecords = data?.stores?.records;
                                    dataRecords.forEach(storeData => {
                                        storesArray.push({
                                            store_id : storeData.store_id,
                                            store_code : storeData.store,
                                            rack_id : storeData.rack_id,
                                            rack_code : storeData.rack,
                                            shelf_id : storeData.shelf_id,
                                            shelf_code : storeData.shelf,
                                            bin_id : storeData.bin_id,
                                            bin_code : storeData.bin,
                                            qty : parseFloat(storeData.alt_uom_qty).toFixed(2),
                                            inventory_uom_qty : parseFloat(storeData.allocated_quantity).toFixed(2)
                                        })
                                    });
                                    storeElement.setAttribute('data-stores', encodeURIComponent(JSON.stringify(storesArray)));
                                    if (callOnClick) {
                                        onItemClick(itemRowId);
                                    }
                                } else if (data?.stores?.code == 202) {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: data?.stores?.message,
                                        icon: 'error',
                                    });
                                    storeElement.setAttribute('data-stores', encodeURIComponent(JSON.stringify([])));
                                    document.getElementById('item_qty_' + itemRowId).value = 0.00;
                                    if (callOnClick) {
                                        onItemClick(itemRowId);
                                    }
                                } else {
                                    storeElement.setAttribute('data-stores', encodeURIComponent(JSON.stringify([])));
                                    if (callOnClick) {
                                        onItemClick(itemRowId);
                                    }
                                }
                                
                            },
                            error: function(xhr) {
                                console.error('Error fetching customer data:', xhr.responseText);
                                storeElement.setAttribute('data-stores', encodeURIComponent(JSON.stringify([])));
                            }
                        });
            }
        }

        function openDeliverySchedule(itemRowIndex)
        {
            document.getElementById('delivery_schedule_main_table').setAttribute('item-row-index', itemRowIndex);
            renderPreviousDeliverySchedule(itemRowIndex);
        }

        function renderPreviousDeliverySchedule(itemRowIndex)
        {
                const previousHiddenQtyFields = document.getElementsByClassName('delivery_schedule_qties_hidden_' + itemRowIndex);
                const previousHiddenDateFields = document.getElementsByClassName('delivery_schedule_dates_hidden_' + itemRowIndex);
                    
                    const oldDelivery = document.getElementsByClassName('item_deliveries');
                    if (oldDelivery && oldDelivery.length > 0)
                    {
                        while (oldDelivery.length > 0) {
                            oldDelivery[0].remove();
                        }
                    }
                var isNew = true;
                var newData = ``;
                for (let index = 0; index < previousHiddenQtyFields.length; index++) {
                    const newHTML = document.getElementById('delivery_schedule_main_table').insertRow(index + 2);
                    newHTML.id = "item_delivery_schedule_modal_" + index;
                    newHTML.className = "item_deliveries";
                    newData = `
                        <td>${index+1}</td>
                        <td>${previousHiddenQtyFields[index].value}</td>
                        <td>${previousHiddenDateFields[index].value}</td>
                        <td>
                            <a href="#" class="text-danger" onclick = "removeDeliverySchedule(${index}, ${itemRowIndex});"><i data-feather="trash-2"></i></a>
                        </td>
                    `;
                    newHTML.innerHTML = newData;
                    isNew = false;
                }

                document.getElementById('new_item_delivery_date_input').value = "{{Carbon\Carbon::now() -> format('Y-m-d')}}";

                if (isNew) {
                    document.getElementById('new_item_delivery_qty_input').value = document.getElementById("item_qty_"+itemRowIndex).value;
                } else {
                    document.getElementById('new_item_delivery_qty_input').value = "";
                }
                renderIcons();
        }

        function removeDeliverySchedule(index, itemIndex)
        {
            const removableElement = document.getElementById('item_delivery_schedule_modal_' + index);
            if (removableElement) {
                removableElement.remove();
            }
            document.getElementById("item_delivery_schedule_qty_" + itemIndex + "_" + index)?.remove();
            document.getElementById("item_delivery_schedule_date_" + itemIndex + "_" + index)?.remove();

            renderPreviousDeliverySchedule(itemIndex);
        }

        function addDeliveryScheduleRow()
        {
            const deliveryQty = document.getElementById('new_item_delivery_qty_input').value;
            const deliverySchedule = document.getElementById('new_item_delivery_date_input').value;
            if (deliveryQty && deliverySchedule) //All fields filled
            {
                const ItemRowIndexVal = document.getElementById('delivery_schedule_main_table').getAttribute('item-row-index');

                const previousHiddenFields = document.getElementsByClassName('delivery_schedule_qties_hidden_' + ItemRowIndexVal);

                addDeliveryHiddenInput(ItemRowIndexVal, previousHiddenFields.length ? previousHiddenFields.length : 0);
                
                
            }
        }

        function addDeliveryHiddenInput(itemRow, deliveryIndex)
        {
            addHiddenInput("item_delivery_schedule_qty_" + itemRow + "_" + deliveryIndex, document.getElementById('new_item_delivery_qty_input').value, `item_delivery_schedule_qty[${itemRow}][${deliveryIndex}]`, 'delivery_schedule_qties_hidden_' + itemRow, "item_row_" + itemRow);
            addHiddenInput("item_delivery_schedule_date" + itemRow + "_" + deliveryIndex, document.getElementById('new_item_delivery_date_input').value, `item_delivery_schedule_date[${itemRow}][${deliveryIndex}]`, 'delivery_schedule_dates_hidden_' + itemRow, "item_row_" + itemRow);

            addDeliveryScheduleInTable(itemRow);
        }

        function addDeliveryScheduleInTable(itemRowIndex)
        {
                const previousHiddenQtyFields = document.getElementsByClassName('delivery_schedule_qties_hidden_' + itemRowIndex);
                const previousHiddenDateFields = document.getElementsByClassName('delivery_schedule_dates_hidden_' + itemRowIndex);

                const newIndex = previousHiddenQtyFields.length ? previousHiddenQtyFields.length : 0;

                var newData = ``;
                for (let index = newIndex- 1; index < previousHiddenQtyFields.length; index++) {
                    const newHTML = document.getElementById('delivery_schedule_main_table').insertRow(index + 2);
                    newHTML.className = "item_deliveries";
                    newHTML.id = "item_delivery_schedule_modal_" + newIndex;
                    newData = `
                        <td>${index+1}</td>
                        <td>${previousHiddenQtyFields[index].value}</td>
                        <td>${previousHiddenDateFields[index].value}</td>
                        <td>
                            <a href="#" class="text-danger" onclick = "removeDeliverySchedule(${newIndex}, ${itemRowIndex});"><i data-feather="trash-2"></i></a>
                        </td>
                    `;
                    newHTML.innerHTML = newData;
                }
                
                document.getElementById('new_item_delivery_date_input').value = "{{Carbon\Carbon::now() -> format('Y-m-d')}}";
                document.getElementById('new_item_delivery_qty_input').value = "";
                renderIcons();
        }

        function openModal(id)
        {
            $('#' + id).modal('show');
        }

        function closeModal(id)
        {
            $('#' + id).modal('hide');
            if (id === 'attribute') {
                getStoresData(document.getElementById('attributes_table_modal').getAttribute('item-index'))
            }
        }

        function submitForm(status) {
            // Create FormData object
            enableHeader();
            // const form = document.getElementById('sale_order_form');
            // const formData = new FormData(form);

            // // Append a new key-value pair to the form data
            // const items = document.getElementsByClassName('comp_item_code');
            // for (let index = 0; index < items.length; index++) {
            //     formData.append(`item_attributes[${index}]`, items[index].getAttribute('attribute-array'));
            // }
            // // Append a new key-value pair to the form data (for store locations)
            // const storeItems = document.getElementsByClassName('item_store_locations');
            // for (let index = 0; index < storeItems.length; index++) {
            //     formData.append(`item_locations[${index}]`, (decodeURIComponent(storeItems[index].getAttribute('data-stores'))));
            // }
            // formData.append('_token', "{{csrf_token()}}");
            // formData.append('document_status', status);

            // // Submit the form using Fetch API or XMLHttpRequest
            // fetch("{{route('sale.invoice.store')}}", {
            //     method: 'POST',
            //     body: formData
            // })
            // .then(response => response.json())
            // .then(data => {
            //     if (data.redirect_url) {
            //         Swal.fire({
            //             title: 'Success!',
            //             text: data.message,
            //             icon: 'success',
            //         });
            //         window.location.href = data.redirect_url;
            //     } else {
            //         Swal.fire({
            //             title: 'Error!',
            //             text: data?.message ? data.message : 'Internal Server Error',
            //             icon: 'error',
            //         });
            //     }
            // })
            // .catch(error => console.error(error));
        }

        function initializeAutocomplete1(selector, index) {
            $("#" + selector).autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: '/search',
                        method: 'GET',
                        dataType: 'json',
                        data: {
                            q: request.term,
                            type:'sale_module_items',
                            customer_id : $("#customer_id_input").val(),
                            header_book_id : $("#series_id_input").val()
                        },
                        success: function(data) {
                            response($.map(data, function(item) {
                                return {
                                    id: item.id,
                                    label: `${item.item_name} (${item.item_code})`,
                                    code: item.item_code || '', 
                                    item_id: item.id,
                                    uom : item.uom,
                                    alternateUoms : item.alternate_u_o_ms,
                                    specifications : item.specifications
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
                    $input.attr('data-id', itemId);
                    $input.attr('specs', JSON.stringify(ui.item.specifications));
                    $input.val(itemCode);

                    const uomDropdown = document.getElementById('uom_dropdown_' + index);
                    var uomInnerHTML = ``;
                    if (uomDropdown) {
                        uomInnerHTML += `<option value = '${ui.item.uom.id}'>${ui.item.uom.alias}</option>`;
                    }
                    if (ui.item.alternateUoms && ui.item.alternateUoms.length > 0) {
                        var selected = false;
                        ui.item.alternateUoms.forEach((saleUom) => {
                            if (saleUom.is_selling) {
                                uomInnerHTML += `<option value = '${saleUom.uom?.id}' ${selected == false ? "selected" : ""}>${saleUom.uom?.alias}</option>`;
                                selected = true;
                            }
                        });
                    }
                    uomDropdown.innerHTML = uomInnerHTML;
                    document.getElementById('')

                    itemOnChange(selector, index, '/item/attributes/');
                    getItemTax(index);
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
    initializeAutocomplete1("items_dropdown_0", 0);
    

    function initializeAutocompleteCustomer(selector) {
            $("#" + selector).autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: '/search',
                        method: 'GET',
                        dataType: 'json',
                        data: {
                            q: request.term,
                            type:'customer'
                        },
                        success: function(data) {
                            response($.map(data, function(item) {
                                return {
                                    id: item.id,
                                    label: `${item.company_name} (${item.customer_code})`,
                                    code: item.customer_code || '', 
                                    item_id: item.id,
                                    payment_terms_id : item?.payment_terms?.id,
                                    payment_terms : item?.payment_terms?.name,
                                    payment_terms_code : item?.payment_terms?.name,
                                    currency_id : item?.currency?.id,
                                    currency : item?.currency?.name,
                                    currency_code : item?.currency?.short_name,
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
                    var paymentTermsId = ui.item.payment_terms_id;
                    var paymentTerms = ui.item.payment_terms;
                    var paymentTermsCode = ui.item.payment_terms_code;
                    var currencyId = ui.item.currency_id;
                    var currency = ui.item.currency;
                    var currencyCode = ui.item.currency_code;
                    $input.attr('payment_terms_id', paymentTermsId);
                    $input.attr('payment_terms', paymentTerms);
                    $input.attr('payment_terms_code', paymentTermsCode);
                    $input.attr('currency_id', currencyId);
                    $input.attr('currency', currency);
                    $input.attr('currency_code', currencyCode);
                    $input.val(ui.item.label);
                    $("#customer_code_input_hidden").val(ui.item.code);
                    document.getElementById('customer_id_input').value = ui.item.id;
                    onChangeCustomer(selector);
                    return false;
                },
                change: function(event, ui) {
                    if (!ui.item) {
                        $(this).val("");
                        $("#customer_code_input_hidden").val("");
                    }
                }
            }).focus(function() {
                if (this.value === "") {
                    $(this).autocomplete("search", "");
                }
            });
    }

    initializeAutocompleteCustomer('customer_code_input');

    function disableHeader()
    {
        const disabledFields = document.getElementsByClassName('disable_on_edit');
            for (let disabledIndex = 0; disabledIndex < disabledFields.length; disabledIndex++) {
                disabledFields[disabledIndex].disabled = true;
            }
        const editBillButton = document.getElementById('billAddressEditBtn');
        if (editBillButton) {
            editBillButton.style.display = "none"
        }
        const editShipButton = document.getElementById('shipAddressEditBtn');
        if (editShipButton) {
            editShipButton.style.display = "none";
        }
        let siButton = document.getElementById('select_si_button');
        if (siButton) {
            siButton.disabled = true;
        }
        let dnButton = document.getElementById('select_dn_button');
        if (dnButton) {
            dnButton.disabled = true;
        }
        let leaseButton = document.getElementById('select_lease_button');
        if (leaseButton) {
            leaseButton.disabled = true;
        }
        let orderButton = document.getElementById('select_order_button');
        if (orderButton) {
            orderButton.disabled = true;
        }
        document.getElementById('customer_code_input').disabled = true;
    }

    function enableHeader()
    {
        const disabledFields = document.getElementsByClassName('disable_on_edit');
            for (let disabledIndex = 0; disabledIndex < disabledFields.length; disabledIndex++) {
                disabledFields[disabledIndex].disabled = false;
            }
        const editBillButton = document.getElementById('billAddressEditBtn');
        if (editBillButton) {
            editBillButton.style.display = "block"
        }
        const editShipButton = document.getElementById('shipAddressEditBtn');
        if (editShipButton) {
            editShipButton.style.display = "block";
        }
        let siButton = document.getElementById('select_si_button');
        if (siButton) {
            siButton.disabled = false;
        }
        let dnButton = document.getElementById('select_dn_button');
        if (dnButton) {
            dnButton.disabled = false;
        }
        let leaseButton = document.getElementById('select_lease_button');
        if (leaseButton) {
            leaseButton.disabled = false;
        }
        let orderButton = document.getElementById('select_order_button');
        if (orderButton) {
            orderButton.disabled = false;
        }
        document.getElementById('customer_code_input').disabled = false;
    }

    //Function to set values for edit form
    function editScript()
    {
        localStorage.setItem('deletedItemDiscTedIds', JSON.stringify([]));
        localStorage.setItem('deletedHeaderDiscTedIds', JSON.stringify([]));
        localStorage.setItem('deletedHeaderExpTedIds', JSON.stringify([]));
        localStorage.setItem('deletedSiItemIds', JSON.stringify([]));
        localStorage.setItem('deletedAttachmentIds', JSON.stringify([]));

        const order = @json(isset($order) ? $order : null);
        if (order) {
            //Disable header fields which cannot be changed
            disableHeader();
            //Item Discount
            order.items.forEach((item, itemIndex) => {
                const totalValue = item.item_discount_amount;
                document.getElementById('discount_main_table').setAttribute('total-value', totalValue);
                document.getElementById('discount_main_table').setAttribute('item-row', 'item_value_' + itemIndex);
                document.getElementById('discount_main_table').setAttribute('item-row-index', itemIndex);

                item.discount_ted.forEach((ted, tedIndex) => {
                    addHiddenInput("item_discount_name_" + itemIndex + "_" + tedIndex, ted.ted_name, `item_discount_name[${itemIndex}][${tedIndex}]`, 'discount_names_hidden_' + itemIndex, 'item_value_' + itemIndex, ted.id);
                    addHiddenInput("item_discount_master_id_" + itemIndex + "_" + tedIndex, ted.ted_name, `item_discount_master_id[${itemIndex}][${tedIndex}]`, 'discount_names_hidden_' + itemIndex, 'item_value_' + itemIndex, ted.id);
                    addHiddenInput("item_discount_percentage_" + itemIndex + "_" + tedIndex, ted.ted_percentage ? ted.ted_percentage : '', `item_discount_percentage[${itemIndex}][${tedIndex}]`, 'discount_percentages_hidden_' + itemIndex,  'item_value_' + itemIndex, ted.id);
                    addHiddenInput("item_discount_value_" + itemIndex + "_" + tedIndex, ted.ted_amount, `item_discount_value[${itemIndex}][${tedIndex}]`, 'discount_values_hidden_' + itemIndex, 'item_value_' + itemIndex, ted.id);
                    addHiddenInput("item_discount_id_" + itemIndex + "_" + tedIndex, ted.id, `item_discount_id[${itemIndex}][${tedIndex}]`, 'discount_ids_hidden_' + itemIndex, 'item_value_' + itemIndex);
                });
                //Item Locations
                itemLocations = [];
                item.item_locations.forEach((itemLoc, itemLocIndex) => {
                    itemLocations.push({
                        store_id : itemLoc.store_id,
                        store_code : itemLoc.store_code,
                        rack_id : itemLoc.rack_id,
                        rack_code : itemLoc.rack_code,
                        shelf_id : itemLoc.shelf_id,
                        shelf_code : itemLoc.shelf_code,
                        bin_id : itemLoc.bin_id,
                        bin_code : itemLoc.bin_code,
                        qty : itemLoc.quantity
                    });
                });
                document.getElementById('data_stores_' + itemIndex).setAttribute('data-stores', encodeURIComponent(JSON.stringify(itemLocations)))
                
                itemUomsHTML = ``;
                if (item.item.uom && item.item.uom.id) {
                    itemUomsHTML += `<option value = '${item.item.uom.id}' ${item.item.uom.id == item.uom_id ? "selected" : ""}>${item.item.uom.alias}</option>`;
                }
                item.item.alternate_uoms.forEach(singleUom => {
                    if (singleUom.is_selling) {
                        itemUomsHTML += `<option value = '${singleUom.uom.id}' ${singleUom.uom.id == item.uom_id ? "selected" : ""} >${singleUom.uom?.alias}</option>`;
                    }
                });
                document.getElementById('uom_dropdown_' + itemIndex).innerHTML = itemUomsHTML;
                getItemTax(itemIndex);
                if (itemIndex==0){
                    onItemClick(itemIndex);
                }
            });
            //Order Discount
            order.discount_ted.forEach((orderDiscount, orderDiscountIndex) => {
                document.getElementById('new_order_discount_name').value = orderDiscount.ted_name;
                document.getElementById('new_order_discount_id').value = orderDiscount.ted_id;
                document.getElementById('new_order_discount_percentage').value = orderDiscount.ted_percentage ? orderDiscount.ted_percentage : "";
                document.getElementById('new_order_discount_value').value = orderDiscount.ted_amount;
                addOrderDiscount(orderDiscount.id, false);
            });
            //Order Expense
            order.expense_ted.forEach((orderExpense, orderExpenseIndex) => {
                document.getElementById('order_expense_name').value = orderExpense.ted_name;
                document.getElementById('order_expense_id').value = orderExpense.ted_id;
                document.getElementById('order_expense_percentage').value = orderExpense.ted_percentage ? orderExpense.ted_percentage : "";
                document.getElementById('order_expense_value').value = orderExpense.ted_amount;
                addOrderExpense(orderExpense.id, false);
            });
            
            setAllTotalFields();
            //Disable header fields which cannot be changed
            disableHeader();
            //Set all documents
            order.media_files.forEach((mediaFile, mediaIndex) => {
                appendFilePreviews(mediaFile.file_url, 'main_order_file_preview', mediaIndex, mediaFile.id, order.document_status == 'draft' ? false : true);
            });
        }
        renderIcons();
        
        let finalAmendSubmitButton = document.getElementById("amend-submit-button");

        viewModeScript(finalAmendSubmitButton ? false : true);

    }

    document.addEventListener('DOMContentLoaded', function() {
        const order = @json(isset($order) ? $order : null);
        // if (order == null) {
            // getDocNumberByBookId(document.getElementById('series_id_input'), order ? false : true);
            onSeriesChange(document.getElementById('service_id_input'), order ? false : true);

        // }
    });

    function resetParametersDependentElements()
    {
        var selectionSection = document.getElementById('selection_section');
        if (selectionSection) {
            selectionSection.style.display = "none";
        }
        var selectionSectionSO = document.getElementById('sales_order_selection');
        if (selectionSectionSO) {
            selectionSectionSO.style.display = "none";
        }
        var selectionSectionSI = document.getElementById('sales_invoice_selection');
        if (selectionSectionSI) {
            selectionSectionSI.style.display = "none";
        }
        var selectionSectionDN = document.getElementById('delivery_note_selection');
        if (selectionSectionDN) {
            selectionSectionDN.style.display = "none";
        }
        var selectionSectionLease = document.getElementById('land_lease_selection');
        if (selectionSectionLease) {
            selectionSectionLease.style.display = "none";
        }
        document.getElementById('add_item_section').style.display = "none";
        $("#order_date_input").attr('max', "<?php echo date('Y-m-d'); ?>");
        $("#order_date_input").attr('min', "<?php echo date('Y-m-d'); ?>");
        $("#order_date_input").off('input');
        $("#order_date_input").val(moment().format("YYYY-MM-DD"));
        $('#order_date_input').on('input', function() {
            restrictBothFutureAndPastDates(this);
        });
    }

    function getDocNumberByBookId(element, reset = true) 
    {
        resetParametersDependentElements();
        let bookId = element.value;
        let actionUrl = '{{route("book.get.doc_no_and_parameters")}}'+'?book_id='+bookId + "&document_date=" + $("#order_date_input").val();
        fetch(actionUrl).then(response => {
            return response.json().then(data => {
                if (data.status == 200) {
                  $("#book_code_input").val(data.data.book_code);
                  if(!data.data.doc.document_number) {
                    if (reset) {
                        $("#order_no_input").val('');
                    }
                  }
                  if (reset) {
                    $("#order_no_input").val(data.data.doc.document_number);
                  }
                  if(data.data.doc.type == 'Manually') {
                     $("#order_no_input").attr('readonly', false);
                  } else {
                     $("#order_no_input").attr('readonly', true);
                  }
                  enableDisableQtButton();
                  if (data.data.parameters)
                  {
                    implementBookParameters(data.data.parameters);
                  }
                }
                if(data.status == 404) {
                    if (reset) {
                        $("#book_code_input").val("");
                        // alert(data.message);
                    }
                    enableDisableQtButton();
                }
                if (reset == false) {
                    viewModeScript();
                }
            });
        }); 
    }

    function onDocDateChange()
    {
        let bookId = $("#series_id_input").val();
        let actionUrl = '{{route("book.get.doc_no_and_parameters")}}'+'?book_id='+bookId + "&document_date=" + $("#order_date_input").val();
        fetch(actionUrl).then(response => {
            return response.json().then(data => {
                if (data.status == 200) {
                  $("#book_code_input").val(data.data.book_code);
                  if(!data.data.doc.document_number) {
                     $("#order_no_input").val('');
                  }
                  $("#order_no_input").val(data.data.doc.document_number);
                  if(data.data.doc.type == 'Manually') {
                     $("#order_no_input").attr('readonly', false);
                  } else {
                     $("#order_no_input").attr('readonly', true);
                  }
                }
                if(data.status == 404) {
                    $("#book_code_input").val("");
                    alert(data.message);
                }
            });
        });
    }

    function implementBookParameters(paramData)
    {

        var selectedRefFromServiceOption = paramData.reference_from_service;
        var selectedBackDateOption = paramData.back_date_allowed;
        var selectedFutureDateOption = paramData.future_date_allowed;
        var invoiceToFollowParam = paramData?.invoice_to_follow;
        const breadCrumbHeadingElement = document.getElementById('breadcrumb-document-heading');
        console.log(invoiceToFollowParam, "INVOID TO FOLLOW");
        if (invoiceToFollowParam && invoiceToFollowParam.length > 0) {
            if (invoiceToFollowParam[0] == "no") {
                //add print related statement
                $("#invoice_to_follow_input").val("no");
                
                breadCrumbHeadingElement.textContent = "Delivery Note cum Invoice";
                //Disable--
                document.getElementById('order_discount_button').disabled = false;
                document.getElementById('order_expense_button').disabled = false;
            } else {
                $("#invoice_to_follow_input").val("yes");
                breadCrumbHeadingElement.textContent = "Delivery Note";
                document.getElementById('order_discount_button').disabled = true;
                document.getElementById('order_expense_button').disabled = true;
            }
        } else {
            $("#invoice_to_follow_input").val("");
            breadCrumbHeadingElement.textContent = "Sales Invoice";
        }
        
        // Reference From
        if (selectedRefFromServiceOption) {
            var selectVal = selectedRefFromServiceOption;
            if (selectVal && selectVal.length > 0) {
                selectVal.forEach(selectSingleVal => {
                    if (selectSingleVal == 'so') {
                        var selectionSectionElement = document.getElementById('selection_section');
                        if (selectionSectionElement) {
                            selectionSectionElement.style.display = "";
                        }
                        var selectionPopupElement = document.getElementById('sales_order_selection');
                        if (selectionPopupElement)
                        {
                            selectionPopupElement.style.display = ""
                        }
                    }
                    if (selectSingleVal == 'd') {
                        document.getElementById('add_item_section').style.display = "";
                    }
                    if (selectSingleVal == 'si') {
                        var selectionSectionElement = document.getElementById('selection_section');
                        if (selectionSectionElement) {
                            selectionSectionElement.style.display = "";
                        }
                        var selectionPopupElement = document.getElementById('sales_invoice_selection');
                        if (selectionPopupElement)
                        {
                            selectionPopupElement.style.display = ""
                        }
                    }
                    if (selectSingleVal == 'dnote') {
                        var selectionSectionElement = document.getElementById('selection_section');
                        if (selectionSectionElement) {
                            selectionSectionElement.style.display = "";
                        }
                        var selectionPopupElement = document.getElementById('delivery_note_selection');
                        if (selectionPopupElement)
                        {
                            selectionPopupElement.style.display = ""
                        }
                    }
                    if (selectSingleVal == 'land-lease') {
                        var selectionSectionElement = document.getElementById('selection_section');
                        if (selectionSectionElement) {
                            selectionSectionElement.style.display = "";
                        }
                        var selectionPopupElement = document.getElementById('land_lease_selection');
                        if (selectionPopupElement)
                        {
                            selectionPopupElement.style.display = ""
                        }
                    }
                    
                });
            }
        }

        var backDateAllow = false;
        var futureDateAllow = false;

        //Back Date Allow
        if (selectedBackDateOption) {
            var selectVal = selectedBackDateOption;
            if (selectVal && selectVal.length > 0) {
                if (selectVal[0] == "yes") {
                    backDateAllow = true;
                } else {
                    backDateAllow = false;
                }
            }
        }

        //Future Date Allow
        if (selectedFutureDateOption) {
            var selectVal = selectedFutureDateOption;
            if (selectVal && selectVal.length > 0) {
                if (selectVal[0] == "yes") {
                    futureDateAllow = true;
                } else {
                    futureDateAllow = false;
                }
            }
        }

        if (backDateAllow && futureDateAllow) { // Allow both ways (future and past)
            $("#order_date_input").removeAttr('max');
            $("#order_date_input").removeAttr('min');
            $("#order_date_input").off('input');
        } 
        if (backDateAllow && !futureDateAllow) { // Allow only back date
            $("#order_date_input").removeAttr('min');
            $("#order_date_input").attr('max', "<?php echo date('Y-m-d'); ?>");
            $("#order_date_input").off('input');
            $('#order_date_input').on('input', function() {
                restrictFutureDates(this);
            });
        } 
        if (!backDateAllow && futureDateAllow) { // Allow only future date
            $("#order_date_input").removeAttr('max');
            $("#order_date_input").attr('min', "<?php echo date('Y-m-d'); ?>");
            $("#order_date_input").off('input');
            $('#order_date_input').on('input', function() {
                restrictPastDates(this);
            });
        }
    }

    function enableDisableQtButton()
    {
        const bookId = document.getElementById('series_id_input').value;
        const bookCode = document.getElementById('book_code_input').value;
        const documentDate = document.getElementById('order_date_input').value;

        if (bookId && bookCode && documentDate) {
            let siButton = document.getElementById('select_si_button');
            if (siButton) {
                siButton.disabled = false;
            }
            let dnButton = document.getElementById('select_dn_button');
            if (dnButton) {
                dnButton.disabled = false;
            }
            let leaseButton = document.getElementById('select_lease_button');
            if (leaseButton) {
                leaseButton.disabled = false;
            }
            let orderButton = document.getElementById('select_order_button');
            if (orderButton) {
                orderButton.disabled = false;
            }
            document.getElementById('customer_code_input').disabled = false;
        } else {
            let siButton = document.getElementById('select_si_button');
            if (siButton) {
                siButton.disabled = true;
            }
            let dnButton = document.getElementById('select_dn_button');
            if (dnButton) {
                dnButton.disabled = true;
            }
            let leaseButton = document.getElementById('select_lease_button');
            if (leaseButton) {
                leaseButton.disabled = true;
            }
            let orderButton = document.getElementById('select_order_button');
            if (orderButton) {
                orderButton.disabled = true;
            }
            document.getElementById('customer_code_input').disabled = true;
        }
    }

    editScript();

    $(document).on('click','#billAddressEditBtn',(e) => {
                const addressId = document.getElementById('current_billing_address_id').value;
                const apiRequestValue = addressId;
                const apiUrl = "/customer/address/" + apiRequestValue;
                fetch(apiUrl, {
                    method : "GET",
                    headers : {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                }).then(response => response.json()).then(data => {
                    if (data) {
                        $('#billing_country_id_input').val(data.address.country_id).trigger('change');
                        $("#current_billing_address_id").val(data.address.id);
                        $("#current_billing_country_id").val(data.address.country_id);
                        $("#current_billing_state_id").val(data.address.state_id);
                        $("#current_billing_address").text(data.address.display_address);
                        setTimeout(() => {
                            
                            $('#billing_state_id_input').val(data.address.state_id).trigger('change');

                            setTimeout(() => {
                            
                                $('#billing_city_id_input').val(data.address.city_id).trigger('change');
                            }, 1000);
                        }, 1000);
                        $('#billing_pincode_input').val(data.address.pincode)
                        $('#billing_address_input').val(data.address.address);

                    }

                }).catch(error => {
                    console.log("Error : ", error);
                })
        $("#edit-address-billing").modal('show');
    });

    $(document).on('click','#shipAddressEditBtn',(e) => {
                const addressId = document.getElementById('current_shipping_address_id').value;
                const apiRequestValue = addressId;
                const apiUrl = "/customer/address/" + apiRequestValue;
                fetch(apiUrl, {
                    method : "GET",
                    headers : {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                }).then(response => response.json()).then(data => {
                    if (data) {
                        $('#shipping_country_id_input').val(data.address.country_id).trigger('change');
                        $("#current_shipping_address_id").val(data.address.id);
                        $("#current_shipping_country_id").val(data.address.country_id);
                        $("#current_shipping_state_id").val(data.address.state_id);
                        $("#current_shipping_address").text(data.address.display_address);
                        setTimeout(() => {
                            
                            $('#shipping_state_id_input').val(data.address.state_id).trigger('change');

                            setTimeout(() => {
                            
                                $('#shipping_city_id_input').val(data.address.city_id).trigger('change');
                            }, 1000);
                        }, 1000);
                        $('#shipping_pincode_input').val(data.address.pincode)
                        $('#shipping_address_input').val(data.address.address);

                    }

                }).catch(error => {
                    console.log("Error : ", error);
                })
        $("#edit-address-shipping").modal('show');
    });

    function checkItemAddValidation()
    {
        let addRow = $('#series_id_input').val &&  $('#order_no_input').val && $('#order_date_input').val && $('#customer_code_input').val;
        return addRow;
    }

    var taxInputs = [];

    function getItemTax(itemIndex)
    {
        const itemId = document.getElementById(`items_dropdown_${itemIndex}_value`).value;
        const itemQty = document.getElementById('item_qty_' + itemIndex).value;
        const itemValue = document.getElementById('item_value_' + itemIndex).value;
        const discountAmount = document.getElementById('item_discount_' + itemIndex).value;
        const headerDiscountAmount = document.getElementById('header_discount_' + itemIndex).value;
        const totalItemDiscount = parseFloat(discountAmount ? discountAmount : 0) + parseFloat(headerDiscountAmount ? headerDiscountAmount : 0);
        // const totalItemDiscount = parseFloat(discountAmount ? discountAmount : 0);
        const shipToCountryId = $("#current_shipping_country_id").val();
        const shipToStateId = $("#current_shipping_state_id").val();
        let itemPrice = 0;
        if (itemQty > 0) {
            itemPrice = (parseFloat(itemValue ? itemValue : 0) + parseFloat(totalItemDiscount ? totalItemDiscount : 0)) / parseFloat(itemQty);
        }
        $.ajax({
            url: "{{route('tax.calculate.sales')}}",
                        method: 'GET',
                        dataType: 'json',
                        data : {
                            item_id : itemId,
                            price : itemPrice,
                            transaction_type : 'sale',
                            party_country_id : shipToCountryId,
                            party_state_id : shipToStateId,
                            customer_id : $("#customer_id_input").val(),
                            header_book_id : $("#series_id_input").val()
                        },
                        success: function(data) {
                            const taxInput = document.getElementById('item_tax_' + itemIndex);
                            const valueAfterDiscount = document.getElementById('value_after_discount_' + itemIndex).value;
                            // const valueAfterHeaderDiscount = parseFloat(valueAfterDiscount ? valueAfterDiscount : 0) - parseFloat(headerDiscountAmount ? headerDiscountAmount : 0);
                            const valueAfterHeaderDiscount = document.getElementById('value_after_header_discount_' + itemIndex).value;
                            let TotalItemTax = 0;
                            let taxDetails = [];
                            data.forEach((tax, taxIndex) => {
                                const currentTaxValue = ((parseFloat(tax.tax_percentage ? tax.tax_percentage : 0)/100) * parseFloat(valueAfterHeaderDiscount ? valueAfterHeaderDiscount : 0));
                                TotalItemTax = TotalItemTax + currentTaxValue;
                                taxDetails.push({
                                    'tax_index' : taxIndex,
                                    'tax_name' : tax.tax_type,
                                    'tax_group' : tax.tax_group,
                                    'tax_type' : tax.tax_type,
                                    'taxable_value' : valueAfterHeaderDiscount,
                                    'tax_percentage' : tax.tax_percentage,
                                    'tax_value' : (currentTaxValue).toFixed(2)
                                });
                            });

                            taxInput.setAttribute('tax_details', JSON.stringify(taxDetails))
                            taxInput.value = (TotalItemTax).toFixed(2);
                            //Total
                            // let valueAfterDiscountInput = document.getElementById('value_after_discount_' + itemIndex);
                            // const itemTotalInput = document.getElementById('item_total_' + itemIndex);
                            // console.log(valueAfterDiscountInput.value, TotalItemTax, "TAX");
                            // itemTotalInput.value = parseFloat(valueAfterDiscountInput.value ? valueAfterDiscountInput.value : 0) +  parseFloat(TotalItemTax ? TotalItemTax : 0);

                            //
                            const itemTotalInput = document.getElementById('item_total_' + itemIndex);
                            itemTotalInput.value = parseFloat(valueAfterHeaderDiscount ? valueAfterHeaderDiscount : 0) +  parseFloat(TotalItemTax ? TotalItemTax : 0);

                        //    if (parseFloat(valueAfterDiscountInput.value ? valueAfterDiscountInput.value : 0) !== parseFloat(valueAfterDiscountInput ? valueAfterDiscountInput : 0)) {
                        //     getItemTax(itemIndex);
                        //    }
                            //Get All Total Values
                            setAllTotalFields();
                            updateHeaderExpenses();
                        },
                        error: function(xhr) {
                            console.error('Error fetching customer data:', xhr.responseText);
                        }
                    });

            //Stores Data
            
        
    }

    function itemRowCalculation(itemRowIndex)
    {
        const itemQtyInput = document.getElementById('item_qty_' + itemRowIndex);
        const itemRateInput = document.getElementById('item_rate_' + itemRowIndex);
        const itemValueInput = document.getElementById('item_value_' + itemRowIndex);
        const itemDiscountInput = document.getElementById('item_discount_' + itemRowIndex);
        const itemTotalInput = document.getElementById('item_total_' + itemRowIndex);
        //ItemValue
        const itemValue = parseFloat(itemQtyInput.value ? itemQtyInput.value : 0) * parseFloat(itemRateInput.value ? itemRateInput.value : 0);
        itemValueInput.value = (itemValue).toFixed(2);
        //Discount
        let discountAmount = 0;
        const discountHiddenPercentageFields = document.getElementsByClassName('discount_percentages_hidden_' + itemRowIndex);
        const discountHiddenValuesFields = document.getElementsByClassName('discount_values_hidden_' + itemRowIndex);
        const mainDiscountInput = document.getElementsByClassName('item_discount_' + itemRowIndex);
        //Multiple Discount
        for (let index = 0; index < discountHiddenPercentageFields.length; index++) {
            if (discountHiddenPercentageFields[index].value) 
            {
                let currentDiscountVal = parseFloat(itemValue ? itemValue : 0) * (parseFloat(discountHiddenPercentageFields[index].value ? discountHiddenPercentageFields[index].value : 0)/100);
                discountHiddenValuesFields[index].value = currentDiscountVal.toFixed(2);
                discountAmount+= currentDiscountVal;
            }
            else 
            {
                discountAmount+= parseFloat(discountHiddenValuesFields[index].value ? discountHiddenValuesFields[index].value : 0);
            }
        }
        mainDiscountInput.value = discountAmount;
        //Value after discount
        const valueAfterDiscount = document.getElementById('value_after_discount_' + itemRowIndex);
        const valueAfterDiscountValue = (itemValue - mainDiscountInput.value).toFixed(2);
        valueAfterDiscount.value = valueAfterDiscountValue;
        //Get exact discount amount from order
        // let totalHeaderDiscountAmount = 0;
        // const orderDiscountSummary = document.getElementById('order_discount_summary');
        // if (orderDiscountSummary) {
        //     totalHeaderDiscountAmount = parseFloat(orderDiscountSummary.textContent ? orderDiscountSummary.textContent : 0);
        // }

        //Get total for calculating header discount for each item
        const itemTotalValueAfterDiscount = document.getElementsByClassName('item_val_after_discounts_input');
        let totalValueAfterDiscount = 0;
        for (let index = 0; index < itemTotalValueAfterDiscount.length; index++) {
            totalValueAfterDiscount += parseFloat(itemTotalValueAfterDiscount[index].value ? itemTotalValueAfterDiscount[index].value : 0);
        }

        setModalDiscountTotal('item_discount_' + itemRowIndex, itemRowIndex);

        //Set Header Discount
        updateHeaderDiscounts();
        updateHeaderExpenses();

        //Get exact discount amount from order
        totalHeaderDiscountAmount = 0;
        const orderDiscountSummary = document.getElementById('order_discount_summary');
        if (orderDiscountSummary) {
            totalHeaderDiscountAmount = parseFloat(orderDiscountSummary.textContent ? orderDiscountSummary.textContent : 0);
        }
        let itemHeaderDiscount = (parseFloat(valueAfterDiscountValue ? valueAfterDiscountValue : 0)/ totalValueAfterDiscount) * totalHeaderDiscountAmount;
        itemHeaderDiscount = (parseFloat(itemHeaderDiscount ? itemHeaderDiscount : 0)).toFixed(2);
        //Done
        const headerDiscountInput = document.getElementById('header_discount_' + itemRowIndex);
        headerDiscountInput.value = itemHeaderDiscount;

        const valueAfterHeaderDiscount = document.getElementById('value_after_header_discount_' + itemRowIndex);
        valueAfterHeaderDiscount.value = parseFloat(valueAfterDiscountValue ? valueAfterDiscountValue : 0) - itemHeaderDiscount;

        setModalDiscountTotal('item_discount_' + itemRowIndex, itemRowIndex);

        //Set Header Discount
        updateHeaderDiscounts();

        //Tax
        getItemTax(itemRowIndex);

    }

    function updateHeaderDiscounts()
    {
        const headerPercentages = document.getElementsByClassName('order_discount_percentage_hidden');
        const headerValues = document.getElementsByClassName('order_discount_value_hidden');
        var allItemTotalValue = 0;
        var allItemTotalValueInputs = document.getElementsByClassName('item_values_input');
        for (let idx1 = 0; idx1 < allItemTotalValueInputs.length; idx1++) {
            allItemTotalValue += parseFloat(allItemTotalValueInputs[idx1].value ? allItemTotalValueInputs[idx1].value : 0);
        }
        var totalItemDiscount = 0;
        var totalItemDiscountInputs = document.getElementsByClassName('item_discounts_input');
        for (let idx1 = 0; idx1 < totalItemDiscountInputs.length; idx1++) {
            totalItemDiscount += parseFloat(totalItemDiscountInputs[idx1].value ? totalItemDiscountInputs[idx1].value : 0);
        }
        let totalAfterItemDiscount = parseFloat(allItemTotalValue ? allItemTotalValue : 0) - parseFloat(totalItemDiscount ? totalItemDiscount : 0);

        let discountAmount = 0;
        
        for (let index = 0; index < headerValues.length; index++) {
            if (headerPercentages[index].value) {
                let currentDiscountVal = totalAfterItemDiscount * (parseFloat(headerPercentages[index].value ? headerPercentages[index].value : 0)/100);
                headerValues[index].value = currentDiscountVal.toFixed(2);
                const tableOrderDiscountValue = document.getElementById('order_discount_input_val_' + index);
                if (tableOrderDiscountValue) {
                    tableOrderDiscountValue.textContent = parseFloat(currentDiscountVal).toFixed(2);
                }
                discountAmount+= currentDiscountVal;
            } else {
                discountAmount+= parseFloat(headerValues[index].value ? headerValues[index].value : 0);
            }
        }
        getTotalorderDiscounts(false);

    }

    function updateHeaderExpenses()
    {
        const headerPercentages = document.getElementsByClassName('order_expense_percentage_hidden');
        const headerValues = document.getElementsByClassName('order_expense_value_hidden');
        var totalAfterTax = parseFloat(document.getElementById('all_items_total_after_tax_summary').textContent);

        let expenseAmount = 0;
        
        for (let index = 0; index < headerValues.length; index++) {
            if (headerPercentages[index].value) {
                let currentExpenseVal = totalAfterTax * (parseFloat(headerPercentages[index].value ? headerPercentages[index].value : 0)/100);
                headerValues[index].value = currentExpenseVal.toFixed(2);
                const tableOrderExpenseValue = document.getElementById('order_expense_input_val_' + index);
                if (tableOrderExpenseValue) {
                    tableOrderExpenseValue.textContent = (currentExpenseVal).toFixed(2);
                }
                expenseAmount+= currentExpenseVal;
            } else {
                expenseAmount+= parseFloat(headerValues[index].value ? headerValues[index].value : 0);
            }
        }
        getTotalOrderExpenses();

    }


    function setAllTotalFields()
    {
        //Item value
        const itemTotalInputs = document.getElementsByClassName('item_values_input');
        let totalValue = 0;
        for (let index = 0; index < itemTotalInputs.length; index++) {
            totalValue += parseFloat(itemTotalInputs[index].value ? itemTotalInputs[index].value : 0);
        }
        document.getElementById('all_items_total_value').textContent = (totalValue).toFixed(2);
        document.getElementById('all_items_total_value_summary').textContent = (totalValue).toFixed(2);
        if (totalValue < 0) {
            document.getElementById('all_items_total_value_summary').setAttribute('style', 'color : red !important;');
        } else {
            document.getElementById('all_items_total_value_summary').setAttribute('style', '');
        }
        //Item Discount
        const itemTotalDiscounts = document.getElementsByClassName('item_discounts_input');
        let totalDiscount = 0;
        for (let index = 0; index < itemTotalDiscounts.length; index++) {
            totalDiscount += parseFloat(itemTotalDiscounts[index].value ? itemTotalDiscounts[index].value : 0);
        }
        document.getElementById('all_items_total_discount').textContent = (totalDiscount).toFixed(2);
        document.getElementById('all_items_total_discount_summary').textContent = (totalDiscount).toFixed(2);
        if (totalDiscount < 0) {
            document.getElementById('all_items_total_discount_summary').setAttribute('style', 'color : red !important;');
        } else {
            document.getElementById('all_items_total_discount_summary').setAttribute('style', '');
        }
        //Item Tax
        const itemTotalTaxes = document.getElementsByClassName('item_taxes_input');
        let totalTaxes = 0;
        for (let index = 0; index < itemTotalTaxes.length; index++) {
            totalTaxes += parseFloat(itemTotalTaxes[index].value ? itemTotalTaxes[index].value : 0);
        }
        document.getElementById('all_items_total_tax').value = (totalTaxes).toFixed(2);
        document.getElementById('all_items_total_tax_summary').textContent = (totalTaxes).toFixed(2);
        if (totalTaxes < 0) {
            document.getElementById('all_items_total_tax_summary').setAttribute('style', 'color : red !important;')
        } else {
            document.getElementById('all_items_total_tax_summary').setAttribute('style', '');
        }
        //Item Total Value After Discount
        const itemDiscountTotal = document.getElementsByClassName('item_val_after_header_discounts_input');
        let itemDiscountTotalValue = 0;
        for (let index = 0; index < itemDiscountTotal.length; index++) {
            itemDiscountTotalValue += parseFloat(itemDiscountTotal[index].value ? itemDiscountTotal[index].value : 0);
        }
        //Item Total Value 
        const itemValueAfterDiscount = document.getElementsByClassName('item_val_after_discounts_input');
        let itemValueAfterDiscountValue = 0;
        for (let index = 0; index < itemValueAfterDiscount.length; index++) {
            itemValueAfterDiscountValue += parseFloat(itemValueAfterDiscount[index].value ? itemValueAfterDiscount[index].value : 0);
        }
        //Order Discount
        const orderDiscountContainer = document.getElementById('order_discount_summary');
        let orderDiscount = orderDiscountContainer ? orderDiscountContainer.textContent : null;
        orderDiscount = parseFloat(orderDiscount ? orderDiscount : 0);
        let taxableValue = itemValueAfterDiscountValue - orderDiscount;
        document.getElementById('all_items_total_total').textContent = (itemValueAfterDiscountValue).toFixed(2);
        document.getElementById('all_items_total_total_summary').textContent = (taxableValue).toFixed(2);
        if (taxableValue < 0) {
            document.getElementById('all_items_total_total_summary').setAttribute('style', 'color : red !important;')
        } else {
            document.getElementById('all_items_total_total_summary').setAttribute('style', '');
        }
        //Taxable total value 
        const totalAfterTax = (totalTaxes + itemDiscountTotalValue).toFixed(2);
        document.getElementById('all_items_total_after_tax_summary').textContent = totalAfterTax;
        if (totalAfterTax < 0) {
            document.getElementById('all_items_total_after_tax_summary').setAttribute('style', 'color : red !important;')
        } else {
            document.getElementById('all_items_total_after_tax_summary').setAttribute('style', '')
        }
        //Expenses
        const expensesInput = document.getElementById('all_items_total_expenses_summary');
        const expense = parseFloat(expensesInput.textContent ? expensesInput.textContent : 0);
        //Grand Total
        const grandTotalContainer = document.getElementById('grand_total');
        grandTotalContainer.textContent = (parseFloat(totalAfterTax) + parseFloat(expense)).toFixed(2);
        if (grandTotalContainer.textContent < 0) {
            document.getElementById('grand_total').setAttribute('style', 'color : red !important;')
        } else {
            document.getElementById('grand_total').setAttribute('style', '')
        }
    }

    // function changeAllItemsTotal() //All items total
    // {
    //     const itemTotalInputs = document.getElementsByClassName('item_totals_input');
    //     let itemTotal = 0;
    //     for (let index = 0; index < itemTotalInputs.length; index++) {
    //         itemTotal += parseFloat(itemTotalInputs[index].value ? itemTotalInputs[index].value : 0);
    //     }
    //     //Item Total
    //     const totalTextContainers = document.getElementsByClassName('all_tems_total_common');
    //     for (let indx = 0; indx < itemTotalInputs.length; indx++) {
    //         totalTextContainers.textContent = itemTotal.toFixed(2);
    //     }
    //     //Item value
    //     const itemTotalInputs = document.getElementsByClassName('item_totals_input');
    //     let itemTotal = 0;
    //     for (let index = 0; index < itemTotalInputs.length; index++) {
    //         itemTotal += parseFloat(itemTotalInputs[index].value ? itemTotalInputs[index].value : 0);
    //     }

        
    // }

    function onTaxClick(itemIndex)
    {
        const taxInput = document.getElementById('item_tax_' + itemIndex);
        const taxDetails = JSON.parse(taxInput.getAttribute('tax_details'));
        const taxTable = document.getElementById('tax_main_table');
        //Remove previous Taxes
        const oldTaxes = document.getElementsByClassName('item_taxes');
        if (oldTaxes && oldTaxes.length > 0)
        {
            while (oldTaxes.length > 0) {
                oldTaxes[0].remove();
            }
        }
        //Add New Tax
        let newHtml = ``;
        taxDetails.forEach((element, index) => {
            let newDoc = taxTable.insertRow(index + 1);
            newDoc.id = "item_tax_modal_" + itemIndex;
            newDoc.className = "item_taxes";
            newHtml = `
                <td>${index+1}</td>
                <td>${element.tax_name}</td>
                <td>${element.tax_percentage}</td>
                <td class = "dynamic_tax_val_${itemIndex}">${element.tax_value}</td>
            `;
            newDoc.innerHTML = newHtml;
        });

    }

    function onOrderTaxClick()
    {
        const taxesHiddenFields = document.getElementsByClassName('item_taxes_input');
        orderTaxes = [];
        for (let index = 0; index < taxesHiddenFields.length; index++) {
            let itemLevelTaxes = JSON.parse(taxesHiddenFields[index].getAttribute('tax_details'));
            if (Array.isArray(itemLevelTaxes) && itemLevelTaxes.length > 0) {
                itemLevelTaxes.forEach(itemLevelTax => {
                    let existingIndex = orderTaxes.findIndex(tax => tax.tax_type == itemLevelTax.tax_type && tax.tax_percentage == itemLevelTax.tax_percentage);
                    if (existingIndex > -1) { //Exists
                        orderTaxes[existingIndex]['taxable_amount'] = parseFloat(orderTaxes[existingIndex]['taxable_amount']) + parseFloat(itemLevelTax.taxable_value);
                        orderTaxes[existingIndex]['tax_value'] = parseFloat(orderTaxes[existingIndex]['tax_value']) + parseFloat(itemLevelTax.tax_value);
                    } else { //Push
                        orderTaxes.push({
                            'index' : orderTaxes.length ? orderTaxes.length : 0,
                            'tax_type' : itemLevelTax.tax_type,
                            'taxable_amount' : itemLevelTax.taxable_value,
                            'tax_percentage' : itemLevelTax.tax_percentage,
                            'tax_value' : itemLevelTax.tax_value
                        });
                    }
                });
            }
        }
        const mainTableBody = document.getElementById('order_tax_details_table');
        let newTaxesHtml = ``;
        orderTaxes.forEach(taxDetail => {
            newTaxesHtml += `
                        <tr>
                        <td>${taxDetail.index+1}</td>
                        <td>${taxDetail.tax_type}</td>
                        <td>${taxDetail.taxable_amount}</td>
                        <td>${taxDetail.tax_percentage}</td>
                        <td>${taxDetail.tax_value}</td>
                        </tr>
            `
        });
        mainTableBody.innerHTML = newTaxesHtml;
    }

    function setApproval()
    {
        document.getElementById('action_type').value = "approve";
        document.getElementById('approve_reject_heading_label').textContent = "Approve " + "Invoice";

    }
    function setReject()
    {
        document.getElementById('action_type').value = "reject";
        document.getElementById('approve_reject_heading_label').textContent = "Reject " + "Invoice";
    }
    function setFormattedNumericValue(element)
    {
        element.value = (parseFloat(element.value ? element.value  : 0)).toFixed(2)
    }
    function processOrder(landLease = '')
    {
        const allCheckBoxes = document.getElementsByClassName('po_checkbox');
        const docType = $("#service_id_input").val();
        const invoiceToFollowParam = $("#invoice_to_follow_input").val() == "yes";
        const apiUrl = "{{route('sale.invoice.process.items')}}";
        let docId = [];
        let soItemsId = [];
        let qties = [];
        let documentDetails = [];
        for (let index = 0; index < allCheckBoxes.length; index++) {
            if (allCheckBoxes[index].checked) {
                docId.push(allCheckBoxes[index].getAttribute('document-id'));
                soItemsId.push(allCheckBoxes[index].getAttribute('so-item-id'));
                qties.push(allCheckBoxes[index].getAttribute('balance_qty'));
                documentDetails.push({
                    'order_id' : allCheckBoxes[index].getAttribute('document-id'),
                    'quantity' : allCheckBoxes[index].getAttribute('balance_qty'),
                    'item_id' : allCheckBoxes[index].getAttribute('so-item-id')
                });
            }
        }
        if (docId && soItemsId.length > 0) {
            $.ajax({
                url: apiUrl,
                method: 'GET',
                dataType: 'json',
                data: {
                    order_id: docId,
                    quantities : qties,
                    items_id: soItemsId,
                    doc_type: openPullType,
                    document_details : JSON.stringify(documentDetails)
                },
                success: function(data) {
                    const currentOrders = data.data;
                    let currentOrderIndexVal = 0;
                    currentOrders.forEach((currentOrder) => {
                        if (currentOrder) { //Set all data
                        //Disable Header
                            disableHeader();
                            //Basic Details
                            $("#customer_code_input").val(currentOrder.customer_code);
                            $("#customer_id_input").val(currentOrder.customer_id);
                            $("#customer_code_input_hidden").val(currentOrder.customer_code);
                            $("#consignee_name_input").val(currentOrder.consignee_name);
                            //First add options also
                            $("#currency_dropdown").empty(); // Clear existing options
                            $("#currency_dropdown").append(new Option(
                                currentOrder.customer ? currentOrder.customer.currency?.name || 'Default Currency Name' : 'Default Currency Name',
                                currentOrder.currency_id || 0
                            ));                        
                            $("#currency_code_input").val(currentOrder.currency_code);
                            //First add options also
                            $("#payment_terms_dropdown").empty(); // Clear existing options
                            $("#payment_terms_dropdown").append(new Option(
                                currentOrder.customer ? currentOrder.customer.payment_terms?.name || 'Default Payment Terms' : 'Default Payment Name',
                                currentOrder.payment_term_id || 0
                            ));
                            $("#payment_terms_code_input").val(currentOrder.payment_term_code);
                            //Address
                            $("#current_billing_address").text(currentOrder.billing_address_details?.display_address);
                            $("#current_shipping_address").text(currentOrder.shipping_address_details?.display_address);
                            $("#current_shipping_country_id").val(currentOrder.shipping_address_details?.country_id);
                            $("#current_shipping_state_id").val(currentOrder.shipping_address_details?.state_id);
                            const mainTableItem = document.getElementById('item_header');
                            //Remove previous items if any
                            // const allRowsCheck = document.getElementsByClassName('item_row_checks');
                            // for (let index = 0; index < allRowsCheck.length; index++) {
                            //     allRowsCheck[index].checked = true;  
                            // }
                            // deleteItemRows();
                            if (true) {
                                currentOrder.items.forEach((item, itemIndex) => {
                                    console.log(item, "ITEM DETAILS");
                                    item.balance_qty = item.actual_qty ? item.actual_qty : item.balance_qty;
                                    var avl_qty = item.balance_qty;
                                    if (docType != "si") {
                                        avl_qty = Math.min(item.balance_qty, item.stock_qty ? item.stock_qty : 0);
                                    }
                                    item.balance_qty = avl_qty;
                                    item.max_qty = avl_qty;
                                    const itemRemarks = item.remarks ? item.remarks : '';
                                    let amountMax = ``;
                                    if (landLease) {
                                        amountMax = `max = '${item.rate}'`;
                                    }

                                    let agreement_no = '';
                                    let lease_end_date = '';
                                    let due_date = '';
                                    let repayment_period = '';

                                    let land_parcel = '';
                                    let land_plots = '';

                                    let landLeasePullHtml = '';

                                    if (landLease) {
                                        agreement_no = currentOrder?.agreement_no;
                                        lease_end_date = moment(currentOrder?.lease_end_date).format('D/M/Y');
                                        due_date = moment(item?.due_date).format('D/M/Y');
                                        repayment_period = currentOrder.repayment_period_type;
                                        land_parcel = item?.land_parcel_display;
                                        land_plots = item?.land_plots_display;

                                        landLeasePullHtml = `
                                            <input type = "hidden" value = ${agreement_no} id = "land_lease_agreement_no_${currentOrderIndexVal}" />
                                            <input type = "hidden" value = ${lease_end_date} id = "land_lease_end_date_${currentOrderIndexVal}" />
                                            <input type = "hidden" value = ${due_date} id = "land_lease_due_date_${currentOrderIndexVal}" />
                                            <input type = "hidden" value = ${repayment_period} id = "land_lease_repayment_period_${currentOrderIndexVal}" />
                                            <input type = "hidden" value = ${land_parcel} id = "land_lease_land_parcel_${currentOrderIndexVal}" />
                                            <input type = "hidden" value = ${land_plots} id = "land_lease_land_plots_${currentOrderIndexVal}" />
                                        `;
                                    } else {
                                        landLeasePullHtml = '';
                                    } 
                                var discountAmtPrev = 0;

                                item.discount_ted.forEach((ted, tedIndex) => {
                                    
                                    var percentage = ted.ted_percentage;
                                    var itemValue = (item.rate * item.balance_qty).toFixed(2);
                                    if (!percentage) {
                                        percentage = ted.ted_amount/(ted.assessment_amount ? ted.assessment_amount : itemValue) * 100;
                                    }
                                    console.log("PRECENTAGE", percentage, itemValue);

                                    var itemDiscountValuePrev = ((itemValue * percentage)/100).toFixed(2);

                                    discountAmtPrev += parseFloat(itemDiscountValuePrev ? itemDiscountValuePrev : 0);
                                });

                                console.log(discountAmtPrev, currentOrderIndexVal, "INDEX FOR DISCOUNT AMT");

                                //Reference from labels
                                var referenceLabelFields = ``;
                                // item.so_details.forEach((soDetail, index) => {
                                //     referenceLabelFields += `<input type = "hidden" class = "reference_from_label_${currentOrderIndexVal}" value = "${soDetail.book_code + "-" + soDetail.document_number + " : " + soDetail.balance_qty}"/>`; 
                                // });

                                // var soItemIds = [];
                                // item.so_details.forEach((soDetail) => {
                                //     soItemIds.push(soDetail.id);
                                // });

                                mainTableItem.innerHTML += `
                                <tr id = "item_row_${currentOrderIndexVal}" class = "item_header_rows" onclick = "onItemClick('${currentOrderIndexVal}');">
                                    <td class="customernewsection-form">
                                    <div class="form-check form-check-primary custom-checkbox">
                                        <input type="checkbox" class="form-check-input item_row_checks" id="item_row_check_${currentOrderIndexVal}" del-index = "${currentOrderIndexVal}">
                                        <label class="form-check-label" for="item_row_check_${currentOrderIndexVal}"></label>
                                    </div> 
                                                                            </td>
                                    <td class="poprod-decpt"> 

                                        <input type = "hidden" id = "qt_id_${currentOrderIndexVal}" value = "${item?.id}" name = "quotation_item_ids[]"/>
                                        <input type = "hidden" id = "qt_id_header_${currentOrderIndexVal}" value = "${item?.id == 0 ? currentOrder?.id : ''}" name = "quotation_item_ids_header[]"/>

                                        <input type = "hidden" id = "qt_type_id_${currentOrderIndexVal}" value = "${currentOrder.document_type}" name = "quotation_item_type[]"/>

                                        <input type = "hidden" id = "qt_book_id_${currentOrderIndexVal}" value = "${currentOrder?.book_id}" />
                                        <input type = "hidden" id = "qt_book_code_${currentOrderIndexVal}" value = "${currentOrder?.book_code}" />

                                        <input type = "hidden" id = "qt_document_no_${currentOrderIndexVal}" value = "${currentOrder?.document_number}" />
                                        <input type = "hidden" id = "qt_document_date_${currentOrderIndexVal}" value = "${currentOrder?.document_date}" />

                                        <input type = "hidden" id = "qt_id_${currentOrderIndexVal}" value = "${currentOrder?.document_number}" />


                                        ${landLeasePullHtml}

                                    <input type="text" readonly id = "items_dropdown_${currentOrderIndexVal}" name="item_code[${currentOrderIndexVal}]" placeholder="Select" class="form-control mw-100 ledgerselecct comp_item_code ui-autocomplete-input" autocomplete="off" data-name="${item?.item?.item_name}" data-code="${item?.item?.item_code}" data-id="${item?.item?.id}" hsn_code = "${item?.item?.hsn?.code}" item-name = "${item?.item?.item_name}" specs = '${JSON.stringify(item?.item?.specifications)}' attribute-array = '${JSON.stringify(item?.item_attributes_array)}'  value = "${item?.item?.item_code}"  item-locations = "${JSON.stringify([])}">
                                    <input type = "hidden" name = "item_id[]" id = "items_dropdown_${currentOrderIndexVal}_value" value = "${item?.item_id}"></input>
                                                                            </td>
                                                                            <td class="poprod-decpt">
                                                                            <input type="text" id = "items_name_${currentOrderIndexVal}" name = "item_name[${currentOrderIndexVal}]" class="form-control mw-100"   value = "${item?.item?.item_name}" readonly>
                                                                        </td>
                                                                            <td class="poprod-decpt"> 
                                    <button id = "attribute_button_${currentOrderIndexVal}" ${item?.item_attributes_array?.length > 0 ? '' : 'disabled'} type = "button" data-bs-toggle="modal" onclick = "setItemAttributes('items_dropdown_${currentOrderIndexVal}', '${currentOrderIndexVal}', true);" data-bs-target="#attribute" class="btn p-25 btn-sm btn-outline-secondary" style="font-size: 10px">Attributes</button>
                                    <input type = "hidden" name = "attribute_value_${currentOrderIndexVal}" />

                                    </td>
                                                                            <td>
                                    <select class="form-select" name = "uom_id[]" id = "uom_dropdown_${currentOrderIndexVal}">

                                    </select> 
                                        </td>
                                        <td><input type="text" id = "item_qty_${currentOrderIndexVal}" name = "item_qty[${currentOrderIndexVal}]" oninput = "changeItemQty(this, '${currentOrderIndexVal}');" value = "${item?.balance_qty}" class="form-control mw-100 text-end" onblur = "setFormattedNumericValue(this);" max = "${item?.balance_qty}"/></td>
                                        <td><input type="text" id = "item_rate_${currentOrderIndexVal}" name = "item_rate[]" oninput = "changeItemRate(this, '${currentOrderIndexVal}');" ${amountMax} value = "${item?.rate}" class="form-control mw-100 text-end" onblur = "setFormattedNumericValue(this);" ${invoiceToFollowParam ? 'readonly' : ''} /></td> 
                                        <td><input type="text" id = "item_value_${currentOrderIndexVal}" disabled class="form-control mw-100 text-end item_values_input" value = "${(item?.balance_qty ? item?.balance_qty : 0) * (item?.rate ? item?.rate : 0)}" /></td>
                                        <input type = "hidden" id = "header_discount_${currentOrderIndexVal}" value = "${item?.header_discount_amount}" ></input>
                                        <input type = "hidden" id = "header_expense_${currentOrderIndexVal}" value = "${item?.header_expense_amount}"></input>
                                    <td>
                                    <div class="position-relative d-flex align-items-center">
                                        <input type="text" id = "item_discount_${currentOrderIndexVal}" disabled class="form-control mw-100 text-end item_discounts_input" style="width: 70px" value = "${discountAmtPrev}"/>
                                        <div class="ms-50">
                                            <button type = "button" ${invoiceToFollowParam ? 'disabled' : ''} onclick = "onDiscountClick('item_value_${currentOrderIndexVal}', '${currentOrderIndexVal}')" data-bs-toggle="modal" data-bs-target="#discount" class="btn p-25 btn-sm btn-outline-secondary" style="font-size: 10px">Add</button>
                                        </div>
                                    </div>
                                    </td>
                                        <input type="hidden" id = "item_tax_${currentOrderIndexVal}" value = "${item?.tax_amount}" class="form-control mw-100 text-end item_taxes_input" style="width: 70px" />
                                        <td><input type="text" id = "value_after_discount_${currentOrderIndexVal}" value = "${(item?.balance_qty * item?.rate) - item?.item_discount_amount}" disabled class="form-control mw-100 text-end item_val_after_discounts_input" /></td>
                                        <input type = "hidden" id = "value_after_header_discount_${currentOrderIndexVal}" class = "item_val_after_header_discounts_input" value = "${(item?.balance_qty * item?.rate) - item?.item_discount_amount - item?.header_discount_amount}" ></input>
                                        <input type="hidden" id = "item_total_${currentOrderIndexVal}" value = "${(item?.balance_qty * item?.rate) - item?.item_discount_amount - item?.header_discount_amount + (item?.tax_amount)}" disabled class="form-control mw-100 text-end item_totals_input" />
                                    <td>
                                        
                                        <div class="d-flex">
                                            <div ${docType === 'dnote' || docType === 'sinvdnote' ? '' : 'style = "display:none;"'} class="me-50 cursor-pointer item_store_locations" data-bs-toggle="modal" data-bs-target="#location" onclick = "openStoreLocationModal(${currentOrderIndexVal})" data-stores = '[]' id = 'data_stores_${currentOrderIndexVal}'>    <span data-bs-toggle="tooltip" data-bs-placement="top" title="Store Location" class="text-primary"><i data-feather="map-pin"></i></span></div>
                                            <div class="me-50 cursor-pointer" data-bs-toggle="modal" data-bs-target="#Remarks" onclick = "setItemRemarks('item_remarks_${currentOrderIndexVal}');">        <span data-bs-toggle="tooltip" data-bs-placement="top" title="Remarks" class="text-primary"><i data-feather="file-text"></i></span></div>
                                        </div>
                                    </td>
                                    <input type="hidden" id = "item_remarks_${currentOrderIndexVal}" name = "item_remarks[]" value = "${itemRemarks}"/>

                                                                        </tr>
                                `;
                                initializeAutocomplete1("items_dropdown_" + currentOrderIndexVal, currentOrderIndexVal);
                                renderIcons();
                                const totalValue = item.item_discount_amount;
                                document.getElementById('discount_main_table').setAttribute('total-value', totalValue);
                                document.getElementById('discount_main_table').setAttribute('item-row', 'item_value_' + currentOrderIndexVal);
                                document.getElementById('discount_main_table').setAttribute('item-row-index', currentOrderIndexVal);

                                item.discount_ted.forEach((ted, tedIndex) => {
                                    
                                    addHiddenInput("item_discount_name_" + currentOrderIndexVal + "_" + tedIndex, ted.ted_name, `item_discount_name[${currentOrderIndexVal}][${tedIndex}]`, 'discount_names_hidden_' + currentOrderIndexVal, 'item_row_' + currentOrderIndexVal);
                                    var percentage = ted.ted_percentage;
                                    var itemValue = document.getElementById('item_value_' + currentOrderIndexVal).value;
                                    if (!percentage) {
                                        percentage = ted.ted_amount/itemValue*100;
                                    }
                                    addHiddenInput("item_discount_percentage_" + currentOrderIndexVal + "_" + tedIndex, percentage, `item_discount_percentage[${currentOrderIndexVal}][${tedIndex}]`, 'discount_percentages_hidden_' + currentOrderIndexVal,  'item_row_' + currentOrderIndexVal);
                                    var itemDiscountValue = ((itemValue * percentage)/100).toFixed(2);

                                    addHiddenInput("item_discount_value_" + currentOrderIndexVal + "_" + tedIndex, itemDiscountValue, `item_discount_value[${currentOrderIndexVal}][${tedIndex}]`, 'discount_values_hidden_' + currentOrderIndexVal, 'item_row_' + currentOrderIndexVal);
                                    
                                });
                                //Item Delivery Schedule
                                if (item.item_deliveries) {
                                    item.item_deliveries.forEach((delivery, deliveryIndex) => {
                                        addHiddenInput("item_delivery_schedule_qty_" + currentOrderIndexVal + "_" + deliveryIndex, delivery.qty, `item_delivery_schedule_qty[${currentOrderIndexVal}][${deliveryIndex}]`, 'delivery_schedule_qties_hidden_' + currentOrderIndexVal, "item_row_" + currentOrderIndexVal);
                                        addHiddenInput("item_delivery_schedule_date" + currentOrderIndexVal + "_" + deliveryIndex, delivery.delivery_date, `item_delivery_schedule_date[${currentOrderIndexVal}][${deliveryIndex}]`, 'delivery_schedule_dates_hidden_' + currentOrderIndexVal, "item_row_" + currentOrderIndexVal);
                                    });
                                }
                                
                                var itemUomsHTML = ``;
                                if (item.item.uom && item.item.uom.id) {
                                    itemUomsHTML += `<option value = '${item.item.uom.id}' ${item.item.uom.id == item.uom_id ? "selected" : ""}>${item.item.uom.alias}</option>`;
                                }
                                item.item.alternate_uoms.forEach(singleUom => {
                                    if (singleUom.is_selling) {
                                        itemUomsHTML += `<option value = '${singleUom.uom.id}' ${singleUom.uom.id == item.uom_id ? "selected" : ""} >${singleUom.uom?.alias}</option>`;
                                    }
                                });
                                document.getElementById('uom_dropdown_' + currentOrderIndexVal).innerHTML = itemUomsHTML;
                                // getStoresData(currentOrderIndexVal,null,false);
                                getItemTax(currentOrderIndexVal);
                                currentOrderIndexVal += 1;

                                });
                            } 
                            // else {

                            //     currentOrder.items.forEach((item, itemIndex) => {
                            //         console.log(currentOrderIndexVal, "ITEMINDEX");
                            //         const avl_qty = Math.min(item.balance_qty, item.stock_qty);
                            //         item.balance_qty = avl_qty;
                            //         const itemRemarks = item.remarks ? item.remarks : '';
                            //     mainTableItem.innerHTML += `
                            //     <tr id = "item_row_${currentOrderIndexVal}" class = "item_header_rows" onclick = "onItemClick('${currentOrderIndexVal}');">
                            //         <td class="customernewsection-form">
                            //         <div class="form-check form-check-primary custom-checkbox">
                            //             <input type="checkbox" class="form-check-input item_row_checks" id="Email">
                            //             <label class="form-check-label" for="Email"></label>
                            //         </div> 
                            //                                                 </td>
                            //         <td class="poprod-decpt"> 

                            //             <input type = "hidden" id = "qt_id_${currentOrderIndexVal}" value = "${item?.id}" name = "quotation_item_ids[]"/>

                            //             <input type = "hidden" id = "qt_type_id_${currentOrderIndexVal}" value = "${currentOrder.document_type}" name = "quotation_item_type[]"/>

                            //             <input type = "hidden" id = "qt_book_id_${currentOrderIndexVal}" value = "${currentOrder?.book_id}" />
                            //             <input type = "hidden" id = "qt_book_code_${currentOrderIndexVal}" value = "${currentOrder?.book_code}" />

                            //             <input type = "hidden" id = "qt_document_no_${currentOrderIndexVal}" value = "${currentOrder?.document_number}" />
                            //             <input type = "hidden" id = "qt_document_date_${currentOrderIndexVal}" value = "${currentOrder?.document_date}" />

                            //             <input type = "hidden" id = "qt_id_${currentOrderIndexVal}" value = "${currentOrder?.document_number}" />

                            //         <input readonly type="text" id = "items_dropdown_${currentOrderIndexVal}" name="item_code[]" placeholder="Select" class="form-control mw-100 ledgerselecct comp_item_code ui-autocomplete-input" autocomplete="off" data-name="${item?.item?.item_name}" data-code="${item?.item?.item_code}" data-id="${item?.item?.id}" hsn_code = "${item?.item?.hsn?.code}" item-name = "${item?.item?.item_name}" specs = '${JSON.stringify(item?.item?.specifications)}' attribute-array = '${JSON.stringify(item?.item_attributes_array)}'  value = "${item?.item?.item_code}" >
                            //         <input type = "hidden" name = "item_id[]" id = "items_dropdown_${currentOrderIndexVal}_value" value = "${item?.item_id}"></input>
                            //                                                 </td>
                            //                                                 <td class="poprod-decpt"> 
                            //         <button id = "attribute_button_${currentOrderIndexVal}" ${item?.item_attributes_array?.length > 0 ? '' : 'disabled'} type = "button" data-bs-toggle="modal" onclick = "setItemAttributes('items_dropdown_${currentOrderIndexVal}', '${currentOrderIndexVal}');" data-bs-target="#attribute" class="btn p-25 btn-sm btn-outline-secondary" style="font-size: 10px">Attributes</button>
                            //         <input type = "hidden" name = "attribute_value_${currentOrderIndexVal}" />
                            //         </td>
                            //                                                 <td>
                            //         <select class="form-select" name = "uom_id[]" id = "uom_dropdown_${currentOrderIndexVal}">

                            //         </select> 
                            //             </td>
                            //             <td><input type="text" id = "item_qty_${currentOrderIndexVal}" name = "item_qty[]" oninput = "changeItemQty(this, '${currentOrderIndexVal}');" value = "${item.balance_qty}" max = "${item?.balance_qty}" class="form-control mw-100 text-end" onblur = "setFormattedNumericValue(this);" /></td>
                            //             <td><input type="text" id = "item_rate_${currentOrderIndexVal}" ${docType === 'dnote' ? 'readonly' : ''} name = "item_rate[]" oninput = "changeItemRate(this, '${currentOrderIndexVal}');" value = "0.00" class="form-control mw-100 text-end" onblur = "setFormattedNumericValue(this);" /></td> 
                            //             <td><input type="text" id = "item_value_${currentOrderIndexVal}" disabled class="form-control mw-100 text-end item_values_input" value = "0.00" /></td>
                            //             <input type = "hidden" id = "header_discount_${currentOrderIndexVal}" value = "0.00" ></input>
                            //             <input type = "hidden" id = "header_expense_${currentOrderIndexVal}" value = "0.00"></input>
                            //         <td>
                            //         <div class="position-relative d-flex align-items-center">
                            //             <input type="text" id = "item_discount_${currentOrderIndexVal}" disabled class="form-control mw-100 text-end item_discounts_input" style="width: 70px" value = "0.00"/>
                            //             <div class="ms-50">
                            //                 <button type = "button" onclick = "onDiscountClick('item_value_${currentOrderIndexVal}', '${currentOrderIndexVal}')" data-bs-toggle="modal" data-bs-target="#discount" class="btn p-25 btn-sm btn-outline-secondary" style="font-size: 10px">Discount</button>
                            //             </div>
                            //         </div>
                            //         </td>
                            //             <input type="hidden" id = "item_tax_${currentOrderIndexVal}" value = "0.00" class="form-control mw-100 text-end item_taxes_input" style="width: 70px" />
                            //             <td><input type="text" id = "value_after_discount_${currentOrderIndexVal}" value = "0.00" disabled class="form-control mw-100 text-end item_val_after_discounts_input" /></td>
                            //             <input type = "hidden" id = "value_after_header_discount_${currentOrderIndexVal}" class = "item_val_after_header_discounts_input" value = "0" ></input>
                            //             <input type="hidden" id = "item_total_${currentOrderIndexVal}" value = "0.00" disabled class="form-control mw-100 text-end item_totals_input" />
                            //         <td>
                            //             <div class="d-flex">
                            //                 <div ${docType === 'dnote' || docType === 'sinvdnote' ? '' : 'style = "display:none;"'} class="me-50 cursor-pointer item_store_locations" data-bs-toggle="modal" data-bs-target="#location" onclick = "openStoreLocationModal(${currentOrderIndexVal})" data-stores = '[]' id = 'data_stores_${currentOrderIndexVal}'>    <span data-bs-toggle="tooltip" data-bs-placement="top" title="Store Location" class="text-primary"><i data-feather="map-pin"></i></span></div>
                            //                 <div class="me-50 cursor-pointer" data-bs-toggle="modal" data-bs-target="#Remarks" onclick = "setItemRemarks('item_remarks_${currentOrderIndexVal}');">        <span data-bs-toggle="tooltip" data-bs-placement="top" title="Remarks" class="text-primary"><i data-feather="file-text"></i></span></div>
                            //         </div>
                            //         </td>
                            //         <input type="hidden" id = "item_remarks_${currentOrderIndexVal}" name = "item_remarks[]" value = "${itemRemarks}"/>

                            //                                             </tr>
                            //     `;
                            //     initializeAutocomplete1("items_dropdown_" + currentOrderIndexVal, currentOrderIndexVal);
                            //     renderIcons();
                            //     const totalValue = 0;
                            //     document.getElementById('discount_main_table').setAttribute('total-value', totalValue);
                            //     document.getElementById('discount_main_table').setAttribute('item-row', 'item_value_' + currentOrderIndexVal);
                            //     document.getElementById('discount_main_table').setAttribute('item-row-index', currentOrderIndexVal);

                            //     var itemUomsHTML = ``;
                            //     console.log(item.item.uom, item.item.alternate_uoms, "UOM");
                            //     if (item.item.uom && item.item.uom.id) {
                            //         itemUomsHTML += `<option value = '${item.item.uom.id}' ${item.item.uom.id == item.uom_id ? "selected" : ""}>${item.item.uom.alias}</option>`;
                            //     }
                            //     item.item.alternate_uoms.forEach(singleUom => {
                            //         if (singleUom.is_selling) {
                            //             itemUomsHTML += `<option value = '${singleUom.uom.id}' ${singleUom.uom.id == item.uom_id ? "selected" : ""} >${singleUom.uom?.alias}</option>`;
                            //         }
                            //     });
                            //     document.getElementById('uom_dropdown_' + currentOrderIndexVal).innerHTML = itemUomsHTML;
                            //     // getStoresData(currentOrderIndexVal, null, false);
                            //     currentOrderIndexVal += 1;

                            //     });
                            // }
                        //Order Discount
                        currentOrder.discount_ted.forEach((orderDiscount, orderDiscountIndex) => {
                            document.getElementById('new_order_discount_name').value = orderDiscount.ted_name;
                            document.getElementById('new_order_discount_id').value = orderDiscount.ted_id;
                            var currentOrderDiscountPercentage = orderDiscount.ted_percentage;
                            if (!currentOrderDiscountPercentage) {
                                currentOrderDiscountPercentage = orderDiscount.ted_amount/ orderDiscount.assessment_amount * 100;
                            }
                            document.getElementById('new_order_discount_percentage').value = currentOrderDiscountPercentage;
                            document.getElementById('new_order_discount_value').value = orderDiscount.ted_amount;
                            addOrderDiscount(null, false);
                        });
                        //Order Expense
                        currentOrder.expense_ted.forEach((orderExpense, orderExpenseIndex) => {
                            document.getElementById('order_expense_name').value = orderExpense.ted_name;
                            document.getElementById('order_expense_id').value = orderExpense.ted_id;
                            var currentOrderExpensePercentage = orderExpense.ted_percentage;
                            if (!currentOrderExpensePercentage) {
                                currentOrderExpensePercentage = orderExpense.ted_amount/ orderExpense.assessment_amount * 100;
                            }
                            document.getElementById('order_expense_percentage').value = currentOrderExpensePercentage;
                            document.getElementById('order_expense_value').value = orderExpense.ted_amount;
                            addOrderExpense(null, false);
                        });
                            
                            setAllTotalFields();
                            
                            changeDropdownOptions(document.getElementById('customer_id_input'), ['billing_address_dropdown','shipping_address_dropdown'], ['billing_addresses', 'shipping_addresses'], '/customer/addresses/', 'vendor_dependent');

                            // $("#shipping_address_dropdown").select2();
                            // $("#shipping_address_dropdown").prop('disabled', false);
                            // $("#shipping_address_dropdown").val(currentOrder.shipping_address_details.id).trigger('change');
                            // $("#billing_address_dropdown").select2();
                            // $("#billing_address_dropdown").prop('disabled', false);
                            // $("#billing_address_dropdown").val(currentOrder.billing_address_details.id).trigger('change');

                            // console.log(currentOrder.billing_address_details?.id || 0);
                            // console.log($("#shipping_address_dropdown").val(), "AD1");
                            // console.log($("#billing_address_dropdown").val(), "AD2");
                        }
                    });
                    if (docType != "si") {
                        for (let index = 0; index < currentOrderIndexVal; index++) {
                            getStoresData(index,null,false);
                        }
                    }
                    
                },
                error: function(xhr) {
                    console.error('Error fetching customer data:', xhr.responseText);
                }
            });
        } else {
            Swal.fire({
                title: 'Error!',
                text: 'Please select at least one one document',
                icon: 'error',
            });
        }
    }
    function getOrders(landLease = '')
    {
        var qtsHTML = ``;
        const targetTable = landLease ? document.getElementById('qts_data_table_land') : document.getElementById('qts_data_table');
        const customer_id = landLease ? $("#customer_id_qt_val_land").val() : $("#customer_id_qt_val").val();
        const book_id = landLease ? $("#book_id_qt_val_land").val() : $("#book_id_qt_val").val();
        const document_id = landLease ? $("#document_id_qt_val_land").val() : $("#document_id_qt_val").val();
        const landParcelId = $("#land_parcel_id_qt_val_land").val();
        const landPlotId = $("#land_plot_id_qt_val_land").val();
        const item_id = $("#item_id_qt_val").val();
        const apiUrl = "{{route('sale.invoice.pull.items')}}";
        $.ajax({
            url: apiUrl,
            method: 'GET',
            dataType: 'json',
            data : {
                customer_id : customer_id,
                book_id : book_id,
                document_id : document_id,
                item_id : item_id,
                doc_type : openPullType,
                header_book_id : $("#series_id_input").val(),
                land_plot_id : landPlotId,
                land_parcel_id :landParcelId
            },
            success: function(data) {
                if (Array.isArray(data.data) && data.data.length > 0) {
                    if (landLease) {
                        data.data.forEach((qt, qtIndex) => {
                            let parcelName = qt?.header?.plots?.length > 0 ? qt?.header?.plots[0].land?.name : '';
                            let plots = '';
                            qt?.header?.plots?.forEach((singlePlot) => {
                                if (singlePlot && singlePlot?.plot?.plot_name) {
                                    plots += singlePlot.plot.plot_name;
                                }
                            });
                            qtsHTML += `
                                <tr>
                                    <td>
                                        <div class="form-check form-check-inline me-0">
                                            <input class="form-check-input po_checkbox" type="checkbox" name="po_check" id="po_checkbox_${qtIndex}" oninput = "checkQuotation(this);" can-check-message = '${qt?.can_check_message}' doc-id = "${qt?.header.id}" current-doc-id = "0" document-id = "${qt?.header?.id}" so-item-id = "${qt.id}" balance_qty = "${qt.balance_qty}">
                                        </div> 
                                    </td>   
                                    <td>${qt?.header?.series?.book_code}</td>
                                    <td>${qt?.header?.document_no}</td>
                                    <td>${moment(qt?.header?.document_date).format('D/M/Y')}</td>
                                    <td>${qt?.header?.customer?.company_name}</td>
                                    <td>${parcelName}</td>
                                    <td>${plots}</td>
                                    <td>${qt?.type}</td>
                                    <td>${qt?.installment_cost}</td>
                                    <td>${moment(qt?.due_date).format('D/M/Y')}</td>
                                </tr>
                            `
                        });
                    } else {
                        data.data.forEach((qt, qtIndex) => {
                            var attributesHTML = ``;
                            qt.attributes.forEach(attribute => {
                                attributesHTML += `<span class="badge rounded-pill badge-light-primary" > ${attribute.attribute_name} : ${attribute.attribute_value} </span>`;
                            });
                            qtsHTML += `
                                <tr>
                                    <td>
                                        <div class="form-check form-check-inline me-0">
                                            <input ${qt?.stock_qty > 0 ? '' : 'disabled'} class="form-check-input po_checkbox" type="checkbox" name="po_check" id="po_checkbox_${qtIndex}" oninput = "checkQuotation(this);" doc-id = "${qt?.header.id}" current-doc-id = "0" document-id = "${qt?.header?.id}" so-item-id = "${qt.id}" balance_qty = "${qt.balance_qty}">
                                        </div> 
                                    </td>   
                                    <td>${qt?.header?.book_code}</td>
                                    <td>${qt?.header?.document_number}</td>
                                    <td>${qt?.header?.document_date}</td>
                                    <td>${qt?.header?.currency_code}</td>
                                    <td>${qt?.header?.customer?.company_name}</td>
                                    <td>${qt?.item_code}</td>
                                    <td>${attributesHTML}</td>
                                    <td>${qt?.uom?.name}</td>
                                    <td>${qt?.order_qty}</td>
                                    <td>${qt?.balance_qty}</td>
                                    <td>${qt?.stock_qty}</td>
                                    <td>${qt?.rate}</td>
                                </tr>
                            `
                        });
                    } 
                }
                console.log(qtsHTML, "HTTMMLL");
                targetTable.innerHTML = qtsHTML;
            },
            error: function(xhr) {
                console.error('Error fetching customer data:', xhr.responseText);
                targetTable.innerHTML = '';
            }
        });
     
    }

    function initializeAutocompleteQt(selector, selectorSibling, typeVal, labelKey1, labelKey2 = "") {
            $("#" + selector).autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: '/search',
                        method: 'GET',
                        dataType: 'json',
                        data: {
                            q: request.term,
                            type: typeVal,
                            customer_id : $("#customer_id_qt_val").val(),
                            header_book_id : $("#series_id_input").val()
                        },
                        success: function(data) {
                            response($.map(data, function(item) {
                                return {
                                    id: item.id,
                                    label: `${item[labelKey1]} (${item[labelKey2] ? item[labelKey2] : ''})`,
                                    code: item[labelKey1] || '', 
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
    var openPullType = "so";

    function openHeaderPullModal(type = null)
    {
        document.getElementById('qts_data_table').innerHTML = '';
        document.getElementById('qts_data_table_land').innerHTML = '';
        if (type == "si") {
            openPullType = "so";
            initializeAutocompleteQt("book_code_input_qt", "book_id_qt_val", "book_so", "book_code", "book_name");
            initializeAutocompleteQt("document_no_input_qt", "document_id_qt_val", "sale_order_document", "document_number", "document_number");
            
        } else if (type == 'dnote') {
            openPullType = "dnote";
            initializeAutocompleteQt("book_code_input_qt", "book_id_qt_val", "book_din", "book_code", "book_name");
            initializeAutocompleteQt("document_no_input_qt", "document_id_qt_val", "din_document", "document_number", "document_number");
        } 
        // else if (type === "dnote") {
        //     openPullType = "so";
        //     initializeAutocompleteQt("book_code_input_qt", "book_id_qt_val", "book_so", "book_code", "book_name");
        //     initializeAutocompleteQt("document_no_input_qt", "document_id_qt_val", "sale_order_document", "document_number", "document_number");
        // } 
        else if (type === 'land-lease') {
            openPullType = "land-lease";
            initializeAutocompleteQt("book_code_input_qt_land", "book_id_qt_val_land", "book_land_lease", "book_code", "book_name");
            initializeAutocompleteQt("document_no_input_qt_land", "document_id_qt_val_land", "land_lease_document", "document_number", "document_number");
            initializeAutocompleteQt("land_parcel_input_qt_land", "land_parcel_id_qt_val_land", "land_lease_parcel", "name", "name");
            initializeAutocompleteQt("land_plot_input_qt_land", "land_plot_id_qt_val_land", "land_lease_plots", "plot_name", "plot_name");
        } else {
            openPullType = "so";
            initializeAutocompleteQt("book_code_input_qt", "book_id_qt_val", "book_so", "book_code", "book_name");
            initializeAutocompleteQt("document_no_input_qt", "document_id_qt_val", "sale_order_document", "document_number", "document_number");
        }
        initializeAutocompleteQt("customer_code_input_qt", "customer_id_qt_val", "customer", "customer_code", "company_name");
        initializeAutocompleteQt("customer_code_input_qt_land", "customer_id_qt_val_land", "customer", "customer_code", "company_name");
        initializeAutocompleteQt("item_name_input_qt", "item_id_qt_val", "sale_module_items", "item_code", "item_name");
        if (type === 'land-lease') {
            getOrders('land-lease');
        } else {
            getOrders();
        }
    }

    let current_doc_id = 0;

    function checkQuotation(element, message = '')
    {
        if (element.getAttribute('can-check-message')) {
            Swal.fire({
                title: 'Error!',
                text: element.getAttribute('can-check-message'),
                icon: 'error',
            });
            element.checked = false;
            return;
        }
        const docId = element.getAttribute('doc-id');
        if (current_doc_id != 0) {
            if (element.checked == true) {
                if (current_doc_id != docId) {
                    element.checked = false;
                }
            } else {
                const otherElementsSameDoc = document.getElementsByClassName('po_checkbox');
                let resetFlag = true;
                for (let index = 0; index < otherElementsSameDoc.length; index++) {
                    if (otherElementsSameDoc[index].getAttribute('doc-id') == current_doc_id && otherElementsSameDoc[index].checked) {
                        resetFlag = false;
                        break;
                    }
                }
                if (resetFlag) {
                    current_doc_id = 0;
                }
            }   
        } else {
            current_doc_id = element.getAttribute('doc-id');
        }
        
    }

    //Disable form submit on enter button
    document.querySelector("form").addEventListener("keydown", function(event) {
        if (event.key === "Enter") {
            event.preventDefault();  // Prevent form submission
        }
    });
    $("input[type='text']").on("keydown", function(event) {
        if (event.key === "Enter") {
            event.preventDefault();  // Prevent form submission
        }
    });

        initializeAutocompleteStores("new_store_code_input", "new_store_id_input", 'store', 'store_code');
        initializeAutocompleteStores("new_rack_code_input", "new_rack_id_input", 'store_rack', 'rack_code');
        initializeAutocompleteStores("new_shelf_code_input", "new_shelf_id_input", 'rack_shelf', 'shelf_code');
        initializeAutocompleteStores("new_bin_code_input", "new_bin_id_input", 'shelf_bin', 'bin_code');

    function openStoreLocationModal(index)
    {
        const storeElement = document.getElementById('data_stores_' + index);
        const storeTable = document.getElementById('item_location_table');
        let storeFooter = `
        <tr> 
            <td colspan="4"></td>
            <td class="text-dark"><strong>Total</strong></td>
            <td class="text-dark" id = "total_item_store_qty"><strong>0.00</strong></td>                                   
		</tr>
        `;
        if (storeElement) {
            storeTable.setAttribute('current-item-index', index);
            let storesInnerHtml = ``;
            let totalStoreQty = 0;
            const storesData = JSON.parse(decodeURIComponent(storeElement.getAttribute('data-stores')));
            if (storesData && storesData.length > 0)
            {
                storesData.forEach((store, storeIndex) => {
                    storesInnerHtml += `
                    <tr id = "item_store_${storeIndex}">
                        <td>${storeIndex}</td> 
                        <td>${store.store_code}</td>
                        <td>${store.rack_code}</td>
                        <td>${store.shelf_code}</td>
                        <td>${store.bin_code}</td>
                        <td>${store.qty}</td>
					</tr>
                    `;
                    totalStoreQty += (parseFloat(store.qty ? store.qty : 0))
                });

                storeTable.innerHTML = storesInnerHtml + storeFooter;
                document.getElementById('total_item_store_qty').textContent = totalStoreQty.toFixed(2);

            } else {
                storeTable.innerHTML = storesInnerHtml + storeFooter;
                document.getElementById('total_item_store_qty').textContent = "0.00";
            }
        } else {
            return;
        }
        renderIcons();
    }

    function removeItemStore(index, itemIndex)
    {
        const storeElement = document.getElementById('data_stores_' + itemIndex);
        if (storeElement) {
            const storesData = JSON.parse(decodeURIComponent(storeElement.getAttribute('data-stores')));
            if (storesData && storesData.length > 0) {
                storesData.splice(index, 1);
                storeElement.setAttribute('data-stores', encodeURIComponent(JSON.stringify(storesData)));
                openStoreLocationModal(itemIndex);
            }
        } 
    }

    function addItemStore()
    {
        const itemIndex = document.getElementById('item_location_table').getAttribute('current-item-index');

        const itemStoreId = $("#new_store_id_input").val();
        const itemStoreCode = $("#new_store_code_input").val();

        const itemRackId = $("#new_rack_id_input").val();
        const itemRackCode = $("#new_rack_code_input").val();

        const itemShelfId = $("#new_shelf_id_input").val();
        const itemShelfCode = $("#new_shelf_code_input").val();

        const itemBinId = $("#new_bin_id_input").val();
        const itemBinCode = $("#new_bin_code_input").val();

        const itemStoreQty = $("#new_location_qty").val();

        if (itemStoreId && itemStoreCode && itemRackId && itemRackCode && itemShelfId && itemShelfCode && itemBinId && itemBinCode && itemStoreQty) {
            const newStoreItem = {
                store_id : itemStoreId,
                store_code : itemStoreCode,
                rack_id : itemRackId,
                rack_code : itemRackCode,
                shelf_id : itemShelfId,
                shelf_code : itemShelfCode,
                bin_id : itemBinId,
                bin_code : itemBinCode,
                qty : itemStoreQty
            };
            const storeElement = document.getElementById('data_stores_' + itemIndex);
            if (storeElement) {
                const storesData = JSON.parse(decodeURIComponent(storeElement.getAttribute('data-stores')));
                if (storesData) {
                    storesData.push(newStoreItem);
                    storeElement.setAttribute('data-stores', encodeURIComponent(JSON.stringify(storesData)));
                    openStoreLocationModal(itemIndex);
                    resetStoreFields();
                }
            }
        }
    }

    function initializeAutocompleteStores(selector, siblingSelector, type, labelField) {
            $("#" + selector).autocomplete({
                source: function(request, response) {
                    let dataPayload = {
                        q:request.term,
                        type : type
                    };
                    if (type == "store_rack") {
                        dataPayload.store_id = $("#new_store_id_input").val()
                    }
                    if (type == "rack_shelf") {
                        dataPayload.rack_id = $("#new_rack_id_input").val()
                    }
                    if (type == "shelf_bin") {
                        dataPayload.shelf_id = $("#new_shelf_id_input").val()
                    }
                    $.ajax({
                        url: '/search',
                        method: 'GET',
                        dataType: 'json',
                        data: dataPayload,
                        success: function(data) {
                            response($.map(data, function(item) {
                                return {
                                    id: item.id,
                                    label: item[labelField],
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
                    var itemCode = ui.item.label;
                    var itemId = ui.item.id;
                    $input.val(itemCode);
                    $("#" + siblingSelector).val(itemId);
                    return false;
                },
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
    }

    function resetStoreFields()
    {
        $("#new_store_id_input").val("")
        $("#new_store_code_input").val("")

        $("#new_rack_id_input").val("")
        $("#new_rack_code_input").val("")

        $("#new_shelf_id_input").val("")
        $("#new_shelf_code_input").val("")

        $("#new_bin_id_input").val("")
        $("#new_bin_code_input").val("")

        $("#new_location_qty").val("")
    }

    $(document).ready(function() {
        // Event delegation to handle dynamically added input fields
        $(document).on('input', '.decimal-input', function() {
            // Allow only numbers and a single decimal point
            this.value = this.value.replace(/[^0-9.]/g, ''); // Remove non-numeric characters
            
            // Prevent more than one decimal point
            if ((this.value.match(/\./g) || []).length > 1) {
                this.value = this.value.substring(0, this.value.length - 1);
            }

            // Optional: limit decimal places to 2
            if (this.value.indexOf('.') !== -1) {
                this.value = this.value.substring(0, this.value.indexOf('.') + 3);
            }
        });
    });

    function saveAddressShipping()
    {
        $.ajax({
            url: "{{route('sales_order.add.address')}}",
            method: 'POST',
            dataType: 'json',
            data: {
                type: 'shipping',
                country_id: $("#shipping_country_id_input").val(),
                state_id: $("#shipping_state_id_input").val(),
                city_id: $("#shipping_city_id_input").val(),
                address: $("#shipping_address_input").val(),
                pincode: $("#shipping_pincode_input").val(),
                phone: '',
                fax: '',
                customer_id : $("#customer_id_input").val()
            },
            success: function(data) {
                if (data && data.data) {
                    $("#edit-address-shipping").modal("hide");
                    $("#current_shipping_address_id").val(data.data.id);
                    $("#current_shipping_country_id").val(data.data.country_id);
                    $("#current_shipping_state_id").val(data.data.state_id);
                    $("#current_shipping_address").text(data.data.display_address);
                    var newOption = new Option(data.data.display_address, data.data.id, false, false);
                    $('#shipping_address_dropdown').append(newOption).trigger('change');
                    $("#shipping_address_dropdown").val(data.data.id).trigger('change');
                }
            },
            error: function(xhr) {
                console.error('Error fetching customer data:', xhr.responseText);
            }
        });
    }

    function saveAddressBilling(type)
    {
        $.ajax({
            url: "{{route('sales_order.add.address')}}",
            method: 'POST',
            dataType: 'json',
            data: {
                type: 'billing',
                country_id: $("#billing_country_id_input").val(),
                state_id: $("#billing_state_id_input").val(),
                city_id: $("#billing_city_id_input").val(),
                address: $("#billing_address_input").val(),
                pincode: $("#billing_pincode_input").val(),
                phone: '',
                fax: '',
                customer_id : $("#customer_id_input").val()
            },
            success: function(data) {
                if (data && data.data) {
                    $("#edit-address-billing").modal("hide");
                    $("#current_billing_address_id").val(data.data.id);
                    $("#current_billing_country_id").val(data.data.country_id);
                    $("#current_billing_state_id").val(data.data.state_id);
                    $("#current_billing_address").text(data.data.display_address);
                    var newOption = new Option(data.data.display_address, data.data.id, false, false);
                    $('#billing_address_dropdown').append(newOption).trigger('change');
                    $("#billing_address_dropdown").val(data.data.id).trigger('change');
                }
            },
            error: function(xhr) {
                console.error('Error fetching customer data:', xhr.responseText);
            }
        });
    }

    function onBillingAddressChange(element)
    {
        $("#current_billing_address_id").val(element.value);
        $('#billAddressEditBtn').click();
    }

    function onShippingAddressChange(element)
    {
        $("#current_shipping_address_id").val(element.value);
        $('#shipAddressEditBtn').click();
    }
    $(document).on('click', '#amendmentSubmit', (e) => {
   let actionUrl = "{{ route('sale.order.amend', isset($order) ? $order -> id : 0) }}";
   fetch(actionUrl).then(response => {
      return response.json().then(data => {
         if (data.status == 200) {
            Swal.fire({
                title: 'Success!',
                text: data.message,
                icon: 'success'
            });
            location.reload();
         } else {
            Swal.fire({
                title: 'Error!',
                text: data.message,
                icon: 'error'
            });
        }
      });
   });
});

var currentRevNo = $("#revisionNumber").val();

// # Revision Number On Change
$(document).on('change', '#revisionNumber', (e) => {
    e.preventDefault();
    let actionUrl = location.pathname + '?type=' + "{{request() -> type ?? 'si'}}" + '&revisionNumber=' + e.target.value;
    $("#revisionNumber").val(currentRevNo);
    window.open(actionUrl, '_blank'); // Opens in a new tab
    console.log(actionUrl);
});

$(document).on('submit', '.ajax-submit-2', function (e) {
    e.preventDefault();
     var submitButton = (e.originalEvent && e.originalEvent.submitter) 
                        || $(this).find(':submit');
    var submitButtonHtml = submitButton.innerHTML; 
    submitButton.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
    submitButton.disabled = true;
    var method = $(this).attr('method');
    var url = $(this).attr('action');
    var redirectUrl = $(this).data('redirect');
    var data = new FormData($(this)[0]);

    var formObj = $(this);
    
    $.ajax({
        url,
        type: method,
        data,
        contentType: false,
        processData: false,
        success: function (res) {
            submitButton.disabled = false;
            submitButton.innerHTML = submitButtonHtml;
            $('.ajax-validation-error-span').remove();
            $(".is-invalid").removeClass("is-invalid");
            $(".help-block").remove();
            $(".waves-ripple").remove();
            Swal.fire({
                title: 'Success!',
                text: res.message,
                icon: 'success',
            });
            setTimeout(() => {
                if (res.store_id) {
                    location.href = `/stores/${res.store_id}/edit`;
                } else if (redirectUrl) {
                    location.href = redirectUrl;
                } else {
                    location.reload();
                }
            }, 1500);
            
        },
        error: function (error) {
            submitButton.disabled = false;
            submitButton.innerHTML = submitButtonHtml;
            $('.ajax-validation-error-span').remove();
            $(".is-invalid").removeClass("is-invalid");
            $(".help-block").remove();
            $(".waves-ripple").remove();
            let res = error.responseJSON || {};
            if (error.status === 422 && res.errors) {
                if (
                    Object.size(res) > 0 &&
                    Object.size(res.errors) > 0
                ) {
                    show_validation_error(res.errors);
                }
                // let errors = res.errors;
                // for (const [key, errorMessages] of Object.entries(errors)) {
                //     var name = key.replace(/\./g, "][").replace(/\]$/, "");
                //     formObj.find(`[name="${name}"]`).parent().append(
                //         `<span class="ajax-validation-error-span form-label text-danger" style="font-size:12px">${errorMessages[0]}</span>`
                //     );
                // }

            } else {
                Swal.fire({
                    title: 'Error!',
                    text: res.message || 'An unexpected error occurred.',
                    icon: 'error',
                });
            }
        }
    });
});



function viewModeScript(disable = true)
{
    const currentOrder = @json(isset($order) ? $order : null);
    const editOrder = "{{( isset($buttons) && ($buttons['draft'] || $buttons['submit'])) ? false : true}}";
    const revNoQuery = "{{ isset(request() -> revisionNumber) ? true : false }}";

    if ((editOrder || revNoQuery) && currentOrder) {
        document.querySelectorAll('input, textarea, select').forEach(element => {
            if (element.id !== 'revisionNumber' && element.type !== 'hidden' && !element.classList.contains('cannot_disable')) {
                // element.disabled = disable;
                element.style.pointerEvents = disable ? "none" : "auto";
                if (disable) {
                    element.setAttribute('readonly', true);
                } else {
                    element.removeAttribute('readonly');
                }
            }
        });
        //Disable all submit and cancel buttons
        document.querySelectorAll('.can_hide').forEach(element => {
            element.style.display = disable ? "none" : "";
        });
        //Remove add delete button
        document.getElementById('add_delete_item_section').style.display = disable ? "none" : "";
    } else {
        return;
    }
}

function amendConfirm()
{
    viewModeScript(false);
    disableHeader();
    const amendButton = document.getElementById('amendShowButton');
    if (amendButton) {
        amendButton.style.display = "none";
    }
    //disable other buttons
    var printButton = document.getElementById('dropdownMenuButton');
    if (printButton) {
        printButton.style.display = "none";
    }
    var postButton = document.getElementById('postButton');
    if (postButton) {
        postButton.style.display = "none";
    }
    const buttonParentDiv = document.getElementById('buttonsDiv');
    const newSubmitButton = document.createElement('button');
    newSubmitButton.type = "button";
    newSubmitButton.id = "amend-submit-button";
    newSubmitButton.className = "btn btn-primary btn-sm mb-50 mb-sm-0";
    newSubmitButton.innerHTML = `<i data-feather="check-circle"></i> Submit`;
    newSubmitButton.onclick = function() {
        openAmendConfirmModal();
    };

    if (buttonParentDiv) {
        buttonParentDiv.appendChild(newSubmitButton);
    }

    if (feather) {
        feather.replace({
            width: 14,
            height: 14
        });
    }

    reCheckEditScript();
}

function reCheckEditScript()
    {
        const currentOrder = @json(isset($order) ? $order : null);
        if (currentOrder) {
            currentOrder.items.forEach((item, index) => {
                document.getElementById('item_checkbox_' + index).disabled = item?.is_editable ? false : true;
                document.getElementById('items_dropdown_' + index).readonly = item?.is_editable ? false : true;
                document.getElementById('attribute_button_' + index).disabled = item?.is_editable ? false : true;
            });
        }
    }

function openAmendConfirmModal()
{
    $("#amendConfirmPopup").modal("show");
}

function submitAmend()
{
    enableHeader();
    let remark = $("#amendConfirmPopup").find('[name="amend_remarks"]').val();
    $("#action_type_main").val("amendment");
    $("#amendConfirmPopup").modal('hide');
    $("#sale_invoice_form").submit();
}

let isProgrammaticChange = false; // Flag to prevent recursion

document.addEventListener('input', function (e) {
    if (e.target.classList.contains('text-end')) {
        if (isProgrammaticChange) {
            return; // Prevent recursion
        }
        let value = e.target.value;

        // Remove invalid characters (anything other than digits and a single decimal)
        value = value.replace(/[^0-9.]/g, '');

        // Prevent more than one decimal point
        const parts = value.split('.');
        if (parts.length > 2) {
            value = parts[0] + '.' + parts[1];
        }

        // Prevent starting with a decimal (e.g., ".5" -> "0.5")
        if (value.startsWith('.')) {
            value = '0' + value;
        }

        // Limit to 2 decimal places
        if (parts[1]?.length > 2) {
            value = parts[0] + '.' + parts[1].substring(0, 2);
        }

        // Prevent exceeding the max limit
        const maxNumericLimit = 9999999; // Define your max limit here
        if (value && Number(value) > maxNumericLimit) {
            value = maxNumericLimit.toString();
        }
        isProgrammaticChange = true; // Set flag before making a programmatic change
        // Update the input's value
        e.target.value = value;

        // Manually trigger the change event
        const event = new Event('input', { bubbles: true });
        e.target.dispatchEvent(event);
        const event2 = new Event('change', { bubbles: true });
        e.target.dispatchEvent(event2);
        isProgrammaticChange = false; // Reset flag after programmatic change
    }
});

    document.addEventListener('keydown', function (e) {
        if (e.target.classList.contains('text-end')) {
            if ( e.key === 'Tab' ||
                ['Backspace', 'ArrowLeft', 'ArrowRight', 'Delete', '.'].includes(e.key) || 
                /^[0-9]$/.test(e.key)
            ) {
                // Allow numbers, navigation keys, and a single decimal point
                return;
            }
            e.preventDefault(); // Block everything else
        }
    });


function resetPostVoucher()
{
    document.getElementById('voucher_doc_no').value = '';
    document.getElementById('voucher_date').value = '';
    document.getElementById('voucher_book_code').value = '';
    document.getElementById('voucher_currency').value = '';
    document.getElementById('posting-table').innerHTML = '';
    document.getElementById('posting_button').style.display = 'none';
}

function onPostVoucherOpen(type = "not_posted")
{
    resetPostVoucher();
    const apiURL = "{{route('sale.invoice.posting.get')}}";
    $.ajax({
        url: apiURL + "?book_id=" + $("#series_id_input").val() + "&document_id=" + "{{isset($order) ? $order -> id : ''}}",
        type: "GET",
        dataType: "json",
        success: function(data) {
            if (!data.data.status) {
                Swal.fire({
                    title: 'Error!',
                    text: data.data.message,
                    icon: 'error',
                });
                return;
            }
            const voucherEntries = data.data.data;
            var voucherEntriesHTML = ``;
            Object.keys(voucherEntries.ledgers).forEach((voucher) => {
                voucherEntries.ledgers[voucher].forEach((voucherDetail, index) => {
                    voucherEntriesHTML += `
                    <tr>
                    <td>${voucher}</td>   
                    <td class="fw-bolder text-dark">${voucherDetail.ledger_group_code ? voucherDetail.ledger_group_code : ''}</td> 
                    <td>${voucherDetail.ledger_code ? voucherDetail.ledger_code : ''}</td>
                    <td>${voucherDetail.ledger_name ? voucherDetail.ledger_name : ''}</td>
                    <td class="text-end">${voucherDetail.debit_amount > 0 ? parseFloat(voucherDetail.debit_amount).toFixed(2) : ''}</td>
                    <td class="text-end">${voucherDetail.credit_amount > 0 ? parseFloat(voucherDetail.credit_amount).toFixed(2) : ''}</td>
					</tr>
                    `
                });
            });
            voucherEntriesHTML+= `
            <tr>
                <td colspan="4" class="fw-bolder text-dark text-end">Total</td>   
                <td class="fw-bolder text-dark text-end">${voucherEntries.total_debit.toFixed(2)}</td> 
                <td class="fw-bolder text-dark text-end">${voucherEntries.total_credit.toFixed(2)}</td>
			</tr>
            `;
            document.getElementById('posting-table').innerHTML = voucherEntriesHTML;
            document.getElementById('voucher_doc_no').value = voucherEntries.document_number;
            document.getElementById('voucher_date').value = moment(voucherEntries.document_date).format('D/M/Y');
            document.getElementById('voucher_book_code').value = voucherEntries.book_code;
            document.getElementById('voucher_currency').value = voucherEntries.currency_code;
            if (type === "posted") {
                document.getElementById('posting_button').style.display = 'none';
            } else {
                document.getElementById('posting_button').style.removeProperty('display');
            }
            $('#postvoucher').modal('show');
        }
    });
    
}

function postVoucher(element)
{
    const bookId = "{{isset($order) ? $order -> book_id : ''}}";
    const documentId = "{{isset($order) ? $order -> id : ''}}";
    const postingApiUrl = "{{route('sale.invoice.post')}}"
    if (bookId && documentId) {
        $.ajax({
            url: postingApiUrl,
            type: "POST",
            dataType: "json",
            contentType: "application/json", // Specifies the request payload type
            data: JSON.stringify({
                // Your JSON request data here
                book_id: bookId,
                document_id: documentId,
            }),
            success: function(data) {
                const response = data.data;
                if (response.status) {
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                    });
                    location.reload();
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: response.message,
                        icon: 'error',
                    });
                }
            }, 
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Some internal error occured',
                    icon: 'error',
                });
            }
        });

    }
}

function initializeAutocompleteTed(selector, idSelector, type, percentageVal) {
            $("#" + selector).autocomplete({
                source: function(request, response) {
                    var selectedDiscountIds = [];
                    if (type === "sales_module_discount") {
                        if (selector == "new_order_discount_name") {
                            var salesDiscountIdElement = document.getElementsByClassName('order_discount_master_id_hidden');
                            for (let index = 0; index < salesDiscountIdElement.length; index++) {
                                selectedDiscountIds.push(salesDiscountIdElement[index].value);
                            }
                        } else if (selector == "new_discount_name") {
                            var itemIndex = document.getElementById('discount_main_table').getAttribute('item-row-index');
                            var salesDiscountIdElement = document.getElementsByClassName('discount_master_ids_hidden_' + itemIndex);
                            for (let index = 0; index < salesDiscountIdElement.length; index++) {
                                selectedDiscountIds.push(salesDiscountIdElement[index].value);
                            }
                        }
                    }
                    $.ajax({
                        url: '/search',
                        method: 'GET',
                        dataType: 'json',
                        data: {
                            q: request.term,
                            type:type,
                            selected_discount_ids : selectedDiscountIds
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
                    $("#" + percentageVal).val(itemPercentage).trigger('input');
                    return false;
                },
                change: function(event, ui) {
                    if (!ui.item) {
                        $(this).val("");
                        $("#" + idSelector).val("");
                    }
                }
            }).focus(function() {
                if (this.value === "") {
                    $(this).autocomplete("search", "");
                }
            });
    } 
    
    function resetDiscountOrExpense(element, percentageFieldId)
    {
        if(!element.value) {
            $("#" + percentageFieldId).val('').trigger('input');
        }
    }

    function onOrderDiscountModalOpen()
    {
        initializeAutocompleteTed("new_order_discount_name", "new_order_discount_id", 'sales_module_discount', 'new_order_discount_percentage');
    }

    function onOrderExpenseModalOpen()
    {
        initializeAutocompleteTed("order_expense_name", "order_expense_id", 'sales_module_expense', 'order_expense_percentage');
    }
    function checkOrRecheckAllItems(element)
    {
        const allRowsCheck = document.getElementsByClassName('item_row_checks');
        const checkedStatus = element.checked;
        for (let index = 0; index < allRowsCheck.length; index++) {
            allRowsCheck[index].checked = checkedStatus;
        }
    }

    function resetSeries()
    {
        document.getElementById('series_id_input').innerHTML = '';
    }

    function implementSeriesChange(val)
    {
        //COMMON CHANGES
        document.getElementById("type_hidden_input").value = val;
        const generalInfoTab = document.getElementById('general_information_tab');
        const itemDetailTd = document.getElementById('item_details_td');
        const invoiceSummaryTd = document.getElementById('invoice_summary_td');
        const breadCrumbHeading = document.getElementById('breadcrumb-document-heading');

        if (val === 'si') //SALES INVOICE
        {
            generalInfoTab.style.display = 'none';
            itemDetailTd.setAttribute('colspan', 7);
            // invoiceSummaryTd.style.removeProperty('display');
            breadCrumbHeading.textContent = "Sales Invoice";
        }
        else if (val === 'dnote') //DELIVERY NOTE
        {
            generalInfoTab.style.removeProperty('display');
            itemDetailTd.setAttribute('colspan', 7);
            // invoiceSummaryTd.style.display = 'none';
            breadCrumbHeading.textContent = "Delivery Note";
        }
        else if (val === 'lease-invoice')// LEASE INVOICE
        {
            generalInfoTab.style.display = 'none';
            itemDetailTd.setAttribute('colspan', 7);
            // invoiceSummaryTd.style.removeProperty('display');
            breadCrumbHeading.textContent = "Lease Invoice";
        }
        else //DEFAULT BEHAVIOUR
        {
            generalInfoTab.style.display = "none";
            itemDetailTd.setAttribute('colspan', 7);
            // invoiceSummaryTd.style.removeProperty('display');
            breadCrumbHeading.textContent = "Invoice";
        }
    }

    
    function onSeriesChange(element, reset = true)
    {
        resetSeries();
        implementSeriesChange(element.value);
        $.ajax({
            url: "{{route('book.service-series.get')}}",
            method: 'GET',
            dataType: 'json',
            data: {
                menu_alias: "{{request() -> segments()[0]}}",
                service_alias: element.value,
                book_id : reset ? null : "{{isset($order) ? $order -> book_id : null}}"
            },
            success: function(data) {
                if (data.status == 'success') {
                    let newSeriesHTML = ``;
                    data.data.forEach((book, bookIndex) => {
                        newSeriesHTML += `<option value = "${book.id}" ${bookIndex == 0 ? 'selected' : ''} >${book.book_code}</option>`;
                    });
                    document.getElementById('series_id_input').innerHTML = newSeriesHTML;
                    getDocNumberByBookId(document.getElementById('series_id_input'), reset);
                } else {
                    document.getElementById('series_id_input').innerHTML = '';
                }
            },
            error: function(xhr) {
                console.error('Error fetching customer data:', xhr.responseText);
                document.getElementById('series_id_input').innerHTML = '';
            }
        });
    }

    function revokeDocument()
    {
        const orderId = "{{isset($order) ? $order -> id : null}}";
        if (orderId) {
            $.ajax({
            url: "{{route('sale.invoice.revoke')}}",
            method: 'POST',
            dataType: 'json',
            data: {
                id : orderId
            },
            success: function(data) {
                if (data.status == 'success') {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                    });
                    location.reload();
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message,
                        icon: 'error',
                    });
                    window.location.href = "{{$redirect_url}}";
                }
            },
            error: function(xhr) {
                console.error('Error fetching customer data:', xhr.responseText);
                Swal.fire({
                    title: 'Error!',
                    text: 'Some internal error occured',
                    icon: 'error',
                });
            }
        });
        }
    }
</script>
@endsection
@endsection