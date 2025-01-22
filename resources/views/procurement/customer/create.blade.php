@extends('layouts.app')

@section('content')
    <!-- BEGIN: Content-->
  <form class="ajax-input-form" method="POST" action="{{ route('customer.store') }}" data-redirect="{{ url('/customers') }}"  enctype="multipart/form-data">
   @csrf
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header pocreate-sticky">
				<div class="row">
					<div class="content-header-left col-md-6 col-6 mb-2">
						<div class="row breadcrumbs-top">
							<div class="col-12">
								<h2 class="content-header-title float-start mb-0">Customer</h2>
								<div class="breadcrumb-wrapper">
									<ol class="breadcrumb">
										<li class="breadcrumb-item"><a href="#">Home</a>
										</li> 
                                        <li class="breadcrumb-item"><a href="{{route('customer.index')}}">Customer</a>
										</li>  
										<li class="breadcrumb-item active">Add</li>
									</ol>
								</div>
							</div>
						</div>
					</div>
                    <div class="content-header-right text-end col-md-6 col-6 mb-2 mb-sm-0">
                        <input type="hidden" id="document_status" name="document_status">
                        <div class="form-group breadcrumb-right">
                            <a href="{{ route('customer.index') }}" class="btn btn-secondary btn-sm">
                              <i data-feather="arrow-left-circle"></i> Back
                            </a>
                            <button type="button" class="btn btn-warning btn-sm" id="save-draft-button">
                                <i data-feather="save"></i> Save as Draft
                            </button>
                            <button type="button" class="btn btn-primary btn-sm" id="submit-button">
                                <i data-feather="check-circle"></i> Submit
                            </button>
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
                                   <!--Start customer -->
                                          <div class="row">
                                                <div class="col-md-12">
                                                    <div class="newheader border-bottom mb-2 pb-25"> 
                                                        <h4 class="card-title text-theme">Basic Information</h4>
                                                        <p class="card-text">Fill the details</p> 
                                                    </div>
                                                </div> 

                                                <div class="col-md-9"> 

                                                    <div class="row align-items-center mb-1">
                                                        <div class="col-md-3"> 
                                                            <label class="form-label">Customer Code <span class="text-danger">*</span></label>  
                                                        </div>
                                                        <div class="col-md-6"> 
                                                            <input type="text" name="customer_code" class="form-control"/>
                                                        </div> 
                                                    </div>

                                                    <div class="row align-items-center mb-1"> 
                                                        <div class="col-md-3"> 
                                                            <label class="form-label">Customer Type <span class="text-danger">*</span></label>  
                                                        </div> 
                                                        <div class="col-md-5"> 
                                                            <div class="demo-inline-spacing">
                                                                @foreach ($customerTypes as $type)
                                                                    <div class="form-check form-check-primary mt-25">
                                                                        <input
                                                                            type="radio"
                                                                            id="customer_type_{{ strtolower($type) }}"
                                                                            name="customer_type"
                                                                            value="{{ $type }}"
                                                                            class="form-check-input"
                                                                            {{ $type === 'Organisation' ? 'checked' : '' }}
                                                                        >
                                                                        <label class="form-check-label fw-bolder" for="customer_type_{{ strtolower($type) }}">
                                                                            {{ $type }}
                                                                        </label>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row align-items-center mb-1"> 
                                                        <div class="col-md-3"> 
                                                            <label class="form-label">Organization Type <span class="text-danger">*</span></label>  
                                                        </div> 
                                                        <div class="col-md-5">  
                                                            <select name="organization_type_id" class="form-select select2">
                                                                <option value="">Select</option>
                                                                @foreach ($organizationTypes as $type)
                                                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                                @endforeach
                                                            </select>  
                                                        </div>
                                                    </div>

                                                    <div class="row align-items-center mb-1">
                                                        <div class="col-md-3"> 
                                                            <label class="form-label">Company Name <span class="text-danger">*</span></label>  
                                                        </div>  
                                                        <div class="col-md-6"> 
                                                            <input type="text" name="company_name" class="form-control"/>
                                                        </div> 
                                                    </div>

                                                    <div class="row align-items-center mb-1">
                                                        <div class="col-md-3"> 
                                                            <label class="form-label">Customer Display Name</label>  
                                                        </div>  
                                                        <div class="col-md-6"> 
                                                            <input type="text" name="display_name" class="form-control"  />
                                                        </div> 
                                                    </div>

                                                    <div class="row align-items-center mb-1">
                                                        <div class="col-md-3">
                                                            <label class="form-label">Category Mapping</label>
                                                        </div>
                                                        <div class="col-md-3 pe-sm-0 mb-1 mb-sm-0">
                                                            <input type="text" name="category_name" class="form-control category-autocomplete" placeholder="Type to search category">
                                                            <input type="hidden" name="category_id" class="category-id">
                                                            <input type="hidden" name="category_type" class="category-type" value="Customer">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="text" name="subcategory_name" class="form-control subcategory-autocomplete" placeholder="Type to search sub-category">
                                                            <input type="hidden" name="subcategory_id" class="subcategory-id">
                                                            <input type="hidden" name="category_type" class="category-type" value="Customer">
                                                        </div>
                                                    </div>


                                                    <div class="row align-items-center mb-1">
                                                        <div class="col-md-3">
                                                            <label class="form-label">Sales Person</label>
                                                        </div>
                                                        <div class="col-md-3 pe-sm-0 mb-1 mb-sm-0">
                                                            <input type="text" class="form-control sales-person-autocomplete" placeholder="Type to search sales-person">
                                                            <input type="hidden" name="sales_person_id" class="sales-person-id">
                                                        </div>
                                                    </div>


                                                    <p class="mb-0" style="color: red;"><b>Note*:</b> File must be 2MB max | Formats: pdf, jpg, jpeg, png</p>
                                                </div>

                                                <div class="col-md-3 border-start">
                                                    <div class="row align-items-center mb-2">
                                                        <div class="col-md-12"> 
                                                            <label class="form-label text-primary"><strong>Status</strong></label>   
                                                            <div class="demo-inline-spacing">
                                                                @foreach ($status as $option)
                                                                    <div class="form-check form-check-primary mt-25">
                                                                        <input
                                                                            type="radio"
                                                                            id="status_{{ strtolower($option) }}"
                                                                            name="status"
                                                                            value="{{ $option }}"
                                                                            class="form-check-input"
                                                                            {{ $option == 'active' ? 'checked' : '' }} >
                                                                            <label class="form-check-label fw-bolder" for="status_{{ strtolower($option) }}">
                                                                                {{ ucfirst($option) }}
                                                                            </label>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                            @error('status')
                                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                            @enderror
                                                        </div> 
                                                    </div> 

                                                    <div class="row align-items-center mb-2">
                                                        <div class="col-md-12"> 
                                                            <label class="form-label text-primary"><strong>Stop Billing</strong></label>   
                                                            <div class="demo-inline-spacing">
                                                                @foreach ($options as $option)
                                                                    <div class="form-check form-check-primary mt-25">
                                                                        <input
                                                                            type="radio"
                                                                            id="stop_billing_{{ strtolower($option) }}"
                                                                            name="stop_billing"
                                                                            value="{{ $option }}"
                                                                            class="form-check-input"
                                                                            {{ $option == 'No' ? 'checked' : '' }} >
                                                                            <label class="form-check-label fw-bolder" for="stop_billing_{{ strtolower($option) }}">
                                                                                {{ $option }}
                                                                            </label>
                                                                    </div>
                                                                @endforeach
                                                            </div> 
                                                            @error('stop_billing')
                                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                            @enderror
                                                        </div> 
                                                    </div> 
                                                </div>

                                                
                                          </div>

                                            <!--End customer -->
											<div class="mt-1">
												<ul class="nav nav-tabs border-bottom mt-25" role="tablist">
													<li class="nav-item">
														<a class="nav-link active" data-bs-toggle="tab" href="#payment">General Details</a>
													</li>
                                                    <li class="nav-item">
														<a class="nav-link" data-bs-toggle="tab" href="#Shipping">Addresses</a>
													</li>
                                                    <li class="nav-item">
														<a class="nav-link" data-bs-toggle="tab" href="#Financial">Financial</a>
													</li>
													<li class="nav-item">
														<a class="nav-link" data-bs-toggle="tab" href="#amend">Contact Persons</a>
													</li>
													<li class="nav-item">
														<a class="nav-link" data-bs-toggle="tab" href="#schedule">Compliances</a>
													</li>
													<li class="nav-item">
														<a class="nav-link" data-bs-toggle="tab" href="#send">Bank Info</a>
													</li>
													<li class="nav-item">
														<a class="nav-link" data-bs-toggle="tab" href="#latestrates">Notes</a>
													</li>
                                                    <li class="nav-item">
														<a class="nav-link" data-bs-toggle="tab" href="#Items">Items</a>
													</li>

												</ul>

												 <div class="tab-content pb-1 px-1">
                                                     <!--Start customer Details -->
                                                     <div class="tab-pane active" id="payment">
                                                            <!-- Related Party -->
                                                            <div class="row align-items-center mb-1">
                                                                <div class="col-md-2">
                                                                    <label class="form-label">Related Party</label>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-check form-check-primary mt-25 custom-checkbox">
                                                                        <input type="checkbox" class="form-check-input" id="Related" name="related_party">
                                                                        <label class="form-check-label" for="Related">Yes/No</label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Customer Email -->
                                                            <div class="row align-items-center mb-1">
                                                                <div class="col-md-2">
                                                                    <label class="form-label">Customer Email</label>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="input-group input-group-merge">
                                                                        <span class="input-group-text" id="basic-addon5"><i data-feather='mail'></i></span>
                                                                        <input type="email" class="form-control" name="email" placeholder="">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Customer Phone and Mobile -->
                                                            <div class="row align-items-center mb-1">
                                                                <div class="col-md-2">
                                                                    <label class="form-label">Customer Phone</label>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="input-group input-group-merge">
                                                                        <span class="input-group-text" id="basic-addon5"><i data-feather='phone'></i></span>
                                                                        <input type="text" class="form-control numberonly" name="phone" placeholder="Work Phone">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="input-group input-group-merge">
                                                                        <span class="input-group-text" id="basic-addon5"><i data-feather='smartphone'></i></span>
                                                                        <input type="text" class="form-control numberonly" id="phone_mobile" name="mobile" placeholder="Mobile">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Customer Whatsapp Number -->
                                                            <div class="row mb-1">
                                                                <div class="col-md-2">
                                                                    <label class="form-label">Customer Whatsapp Number</label>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="input-group input-group-merge">
                                                                        <span class="input-group-text" id="basic-addon5"><i data-feather='phone'></i></span>
                                                                        <input type="text" class="form-control numberonly" id="whatsapp_number" name="whatsapp_number">
                                                                    </div>
                                                                    <div class="form-check form-check-primary mt-25 custom-checkbox">
                                                                        <input type="checkbox" class="form-check-input" id="colorCheck1" name="whatsapp_same_as_mobile">
                                                                        <label class="form-check-label" for="colorCheck1">Same as Mobile No.</label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Notification -->
                                                            <div class="row align-items-center mb-1">
                                                                <div class="col-md-2">
                                                                    <label class="form-label">Notification</label>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="demo-inline-spacing">
                                                                        <div class="form-check form-check-primary mt-25 custom-checkbox">
                                                                            <input type="checkbox" class="form-check-input" id="Email" name="notification[]" value="email">
                                                                            <label class="form-check-label" for="Email">Email</label>
                                                                        </div>
                                                                        <div class="form-check form-check-primary mt-25 custom-checkbox">
                                                                            <input type="checkbox" class="form-check-input" id="SMS" name="notification[]" value="sms">
                                                                            <label class="form-check-label" for="SMS">SMS</label>
                                                                        </div>
                                                                        <div class="form-check form-check-primary mt-25 custom-checkbox">
                                                                            <input type="checkbox" class="form-check-input " id="Whatsapp" name="notification[]" value="whatsapp">
                                                                            <label class="form-check-label" for="Whatsapp">Whatsapp</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- PAN Number and Attachment -->
                                                            <div class="row align-items-center mb-1">
                                                                <div class="col-md-2">
                                                                    <label class="form-label">PAN</label>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="text" class="form-control" name="pan_number">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="file" class="form-control" name="pan_attachment">
                                                                </div>
                                                            </div>

                                                            <!-- TIN Number and Attachment -->
                                                            <div class="row align-items-center mb-1">
                                                                <div class="col-md-2">
                                                                    <label class="form-label">TIN No.</label>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="text" class="form-control" name="tin_number">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="file" class="form-control" name="tin_attachment">
                                                                </div>
                                                            </div>

                                                            <!-- Aadhar Number and Attachment -->
                                                            <div class="row align-items-center mb-1">
                                                                <div class="col-md-2">
                                                                    <label class="form-label">Aadhar No.</label>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="text" class="form-control" name="aadhar_number">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="file" class="form-control" name="aadhar_attachment">
                                                                </div>
                                                            </div>
                                                            <div class="row align-items-center mb-1" style="margin-top:24px">
                                                                <div class="col-md-2">
                                                                    <label class="form-label">Currency</label>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <select class="form-select select2" id="currencySelect" name="currency_id" style="height: 40px;">
                                                                        <option value="">Select</option>
                                                                        @foreach($currencies as $currency)
                                                                            <option value="{{ $currency->id }}" data-short-name="{{ $currency->short_name }}">{{ $currency->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <!-- Org Currency -->
                                                                <div class="col-md-2" id="orgCurrencyRow" style="display: none;position: relative;">
                                                                    <label class="form-label" style="position: absolute;top:-20px">Org Currency</label>
                                                                    <div class="input-group" style="height: 38px;">
                                                                        <span class="input-group-text bg-light" id="orgCurrencySymbol">Code</span>
                                                                        <input type="text" class="form-control" id="orgCurrency" name="org_currency" readonly>
                                                                    </div>
                                                                </div>

                                                                  <!-- Company Currency -->
                                                                  <div class="col-md-2" id="companyCurrencyRow" style="display: none;position: relative;">
                                                                    <label class="form-label" style="position: absolute;top:-20px">Company Currency</label>
                                                                    <div class="input-group" style="height: 38px;">
                                                                        <span class="input-group-text bg-light" id="companyCurrencySymbol">Code</span>
                                                                        <input type="text" class="form-control" id="companyCurrency" name="company_currency" readonly>
                                                                    </div>
                                                                </div>

                                                                <!-- Group Currency -->
                                                                <div class="col-md-2" id="groupCurrencyRow" style="display: none;position: relative;">
                                                                    <label class="form-label" style="position: absolute;top:-20px">Group Currency</label>
                                                                    <div class="input-group" style="height: 38px;">
                                                                        <span class="input-group-text bg-light" id="groupCurrencySymbol">Code</span>
                                                                        <input type="text" class="form-control" id="groupCurrency" name="group_currency" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <a href="{{ route('exchange-rates.index') }}" target="_blank" class="voucehrinvocetxt mt-0">Add Exchange Rate</a>
                                                                </div>
                                                                  <!-- Hidden Transaction Date Input -->
                                                               <input type="hidden" id="transactionDate" name="transaction_date">
                                                            </div>
                                                            <!-- Opening Balance -->
                                                            <div class="row align-items-center mb-1">
                                                                <div class="col-md-2">
                                                                    <label class="form-label">Opening Balance</label>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="input-group">
                                                                        <span class="input-group-text bg-light" id="currencySymbol">INR</span>
                                                                        <input type="text" class="form-control" name="opening_balance">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Payment Terms -->
                                                            <div class="row align-items-center mb-1">
                                                                <div class="col-md-2">
                                                                    <label class="form-label">Payment Terms</label>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <select class="form-select select2" name="payment_terms_id">
                                                                        <option value="">Select</option>
                                                                        @foreach($paymentTerms as $paymentTerm)
                                                                            <option value="{{ $paymentTerm->id }}">{{ $paymentTerm->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <!-- Upload Documents -->
                                                            <div class="row align-items-center mb-1">
                                                                <div class="col-md-2">
                                                                    <label class="form-label">Upload Documents</label>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="file" class="form-control" name="other_documents[]" multiple>
                                                                </div>
                                                            </div>
                                                     </div>

                                                        <!--End customer Details -->
                                                        <div class="tab-pane" id="Shipping">
                                                            <div class="table-responsive"> 
                                                                <table class="mt-1 table myrequesttablecbox table-striped po-order-detail custnewpo-detail border"> 
                                                                    <thead>
                                                                        <tr>
                                                                            <th>S.NO.</th>
                                                                            <th>Country/Region</th>
                                                                            <th>State</th>
                                                                            <th>City</th>
                                                                            <th>Address</th>
                                                                            <th>Pin Code</th>
                                                                            <th>Phone</th>
                                                                            <th>Fax Number</th>
                                                                            <th>Type</th>
                                                                            <th>Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="address-table-body">
                                                                        <tr class="address-row" data-index="0">
                                                                            <td class="index">1</td>
                                                                            <td>
                                                                                <select class="form-select mw-100 country-select" name="addresses[0][country_id]">
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                <select class="form-select mw-100 state-select" name="addresses[0][state_id]">
                                                                                </select>
                                                                            </td> 
                                                                            <td>
                                                                                <select class="form-select mw-100 city-select" name="addresses[0][city_id]">
                                                                                </select>
                                                                            </td> 
                                                                        
                                                                            <td><input type="text" class="form-control mw-100" name="addresses[0][address]"></td> 
                                                                            <td><input type="text" class="form-control numberonly mw-100" name="addresses[0][pincode]"></td> 
                                                                            <td><input type="text" class="form-control numberonly mw-100" name="addresses[0][phone]"></td> 
                                                                            <td><input type="text" class="form-control numberonly mw-100" name="addresses[0][fax_number]"></td> 
                                                                            <td>
                                                                                <div class="demo-inline-spacing">
                                                                                    <div class="form-check form-check-primary mt-25">
                                                                                        <input type="radio" id="isDefaultPurchase0" name="addresses[0][is_billing]" value="" class="form-check-input">
                                                                                        <label class="form-check-label fw-bolder" for="isDefaultPurchase0">Billing </label>
                                                                                    </div>
                                                                                    <div class="form-check form-check-primary mt-25">
                                                                                        <input type="radio" id="isDefaultSelling0" name="addresses[0][is_shipping]" value="" class="form-check-input">
                                                                                        <label class="form-check-label fw-bolder" for="isDefaultSelling0">Shipping</label>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <a href="#" class="text-danger delete-address"><i data-feather="trash-2" class="me-50"></i></a>
                                                                                <a href="#" class="text-primary add-address"><i data-feather="plus-square" class="me-50"></i></a>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>


                                                         <!--Start Financial -->
                                                         <div class="tab-pane" id="Financial">
                                                               <div class="row align-items-center mb-1">
                                                                    <div class="col-md-2">
                                                                        <label for="ledger_name" class="form-label">Ledger</label>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <input type="text" id="ledger_name" name="ledger_name" class="form-control ladger-autocomplete" placeholder="Type to search...">
                                                                        <input type="hidden" id="ledger_id" name="ledger_id" class="ladger-id">
                                                                    </div>
                                                                </div>
                                                                <div class="row align-items-center mb-1">
                                                                    <div class="col-md-2">
                                                                        <label for="ledger_group_name" class="form-label">Ledger Group</label>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <select id="ledger_group_name" name="ledger_group_id" class="form-control ledger-group-select">
                                                                            <option value="">Select Ledger Group</option>
                                                                        </select>
                                                                    
                                                                    </div>
                                                                </div>

                                                                <div class="row align-items-center mb-1">
                                                                    <div class="col-md-2"> 
                                                                        <label for="pricing_type" class="form-label">Pricing Type</label>  
                                                                    </div>  
                                                                    <div class="col-md-3"> 
                                                                        <select id="pricing_type" name="pricing_type" class="form-select select2">
                                                                            <option value="">Select</option>
                                                                            <option value="fixed">Fixed</option>
                                                                            <option value="variable">Variable</option>
                                                                        </select>
                                                                    </div> 
                                                                </div>

                                                                <div class="row align-items-center mb-1">
                                                                    <div class="col-md-2"> 
                                                                        <label for="credit_limit" class="form-label">Credit Limit</label>  
                                                                    </div>  
                                                                    <div class="col-md-3"> 
                                                                        <input type="number" id="credit_limit" name="credit_limit" class="form-control decimal-only" placeholder="Enter credit limit"/>
                                                                    </div> 
                                                                </div>

                                                                <div class="row align-items-center mb-1">
                                                                    <div class="col-md-2"> 
                                                                        <label for="credit_days" class="form-label">Credit Days</label>  
                                                                    </div>  
                                                                    <div class="col-md-3"> 
                                                                        <input type="number" id="credit_days" name="credit_days" class="form-control numberonly" placeholder="Enter credit days" />
                                                                    </div> 
                                                                </div>

                                                                <div class="row align-items-center mb-1">
                                                                    <div class="col-md-2"> 
                                                                        <label for="interest_percent" class="form-label">Interest %</label>  
                                                                    </div>  
                                                                    <div class="col-md-3"> 
                                                                        <input type="number" id="interest_percent" name="interest_percent" class="form-control decimal-only" placeholder="Enter interest percent" />
                                                                    </div> 
                                                                </div>
                                                         </div>
                                                        <!--End Financial -->

                                                       <!--Start Contact -->
                                                       <div class="tab-pane" id="amend">
                                                            <div class="table-responsive">
                                                                <table class="table myrequesttablecbox table-striped" id="contactsTable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>S.NO.</th>
                                                                            <th>Salutation</th>
                                                                            <th>Name</th>
                                                                            <th>Email</th>
                                                                            <th>Mobile</th>
                                                                            <th>Work Phone</th>
                                                                            <th>Primary</th>
                                                                            <th>Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr id="rowTemplate">
                                                                            <td>1</td>
                                                                            <td class="px-1">
                                                                                <select class="form-select px-1" name="contacts[0][salutation]">
                                                                                    <option value="">Select</option>
                                                                                       @foreach($titles as $title)
                                                                                            <option value="{{ $title }}">{{ $title }}</option>
                                                                                        @endforeach
                                                                                </select>
                                                                            </td>
                                                                            <td class="px-1"><input type="text" name="contacts[0][name]" class="form-control "></td>
                                                                            <td class="px-1"><input type="email" name="contacts[0][email]" class="form-control"></td>
                                                                            <td class="px-1"><input type="text" name="contacts[0][mobile]" class="form-control numberonly"></td>
                                                                            <td class="px-1"><input type="text" name="contacts[0][phone]" class="form-control  numberonly"></td>
                                                                            <td>
                                                                                <input type="radio" name="contacts[0][primary]" value="1" class="primary-radio">
                                                                            </td>
                                                                            <td>
                                                                                <a href="#" class="text-danger delete-contact-row"><i data-feather="trash-2"></i></a>
                                                                                <a href="#" class="text-primary add-contact-row"><i data-feather="plus-square" class="me-50"></i></a>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                       <!--End Contact -->

                                                       <div class="tab-pane" id="schedule">
                                                            <div class="row">
                                                                <!-- TDS Details -->
                                                                <div class="col-md-6">
                                                                    <h5 class="mt-1 mb-2 text-dark"><strong>TDS Details</strong></h5>
                                                                    
                                                                    <div class="row align-items-center mb-1">
                                                                        <div class="col-md-4">
                                                                            <label class="form-label">TDS Applicable</label>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-check form-check-primary mt-25 custom-checkbox">
                                                                                <input type="checkbox" name="compliance[tds_applicable]" id="tdsApplicableIndia" class="form-check-input" checked>
                                                                                <label class="form-check-label" for="tdsApplicableIndia">Yes/No</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="row align-items-center mb-1">
                                                                        <div class="col-md-4">
                                                                            <label class="form-label">Wef Date</label>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <input type="date" name="compliance[wef_date]" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="row align-items-center mb-1">
                                                                        <div class="col-md-4">
                                                                            <label class="form-label">TDS Certificate No.</label>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <input type="text" name="compliance[tds_certificate_no]" class="form-control numberonly">
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="row align-items-center mb-1">
                                                                        <div class="col-md-4">
                                                                            <label class="form-label">TDS Tax Percentage</label>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <input type="text" name="compliance[tds_tax_percentage]" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="row align-items-center mb-1">
                                                                        <div class="col-md-4">
                                                                            <label class="form-label">TDS Category</label>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <input type="text" name="compliance[tds_category]" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="row align-items-center mb-1">
                                                                        <div class="col-md-4">
                                                                            <label class="form-label">TDS Value Cap</label>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <input type="text" name="compliance[tds_value_cab]" class="form-control numberonly">
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="row align-items-center mb-1">
                                                                        <div class="col-md-4">
                                                                            <label class="form-label">TAN Number</label>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <input type="text" name="compliance[tan_number]" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- GST Info -->
                                                                <div class="col-md-6">
                                                                    <h5 class="mt-1 mb-2 text-dark"><strong>GST Info</strong></h5>
                                                                    
                                                                    <div class="row align-items-center mb-1">
                                                                        <div class="col-md-4">
                                                                            <label class="form-label">GST Applicable</label>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="demo-inline-spacing">
                                                                                <div class="form-check form-check-primary mt-25">
                                                                                    <input type="radio" id="gstRegisteredIndia" name="compliance[gst_applicable]" value="1" class="form-check-input" checked>
                                                                                    <label class="form-check-label fw-bolder" for="gstRegisteredIndia">Registered</label>
                                                                                </div>
                                                                                <div class="form-check form-check-primary mt-25">
                                                                                    <input type="radio" id="gstNonRegisteredIndia" name="compliance[gst_applicable]" value="0"class="form-check-input">
                                                                                    <label class="form-check-label fw-bolder" for="gstNonRegisteredIndia">Non-Registered</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="row align-items-center mb-1">
                                                                        <div class="col-md-4">
                                                                            <label class="form-label">GSTIN No.</label>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <input type="text" name="compliance[gstin_no]" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="row align-items-center mb-1">
                                                                        <div class="col-md-4">
                                                                            <label class="form-label">GST Registered Name</label>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <input type="text" name="compliance[gst_registered_name]" class="form-control numberonly">
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="row align-items-center mb-1">
                                                                        <div class="col-md-4">
                                                                            <label class="form-label">GSTIN Reg. Date</label>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <input type="date" name="compliance[gstin_registration_date]" class="form-control numberonly">
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="row align-items-center mb-1">
                                                                        <div class="col-md-4">
                                                                            <label class="form-label">Upload Certificate</label>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <input type="file" name="compliance[gst_certificate][]" multiple class="form-control">
                                                                            <div id="gstCertificateLinks"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- MSME Details -->
                                                                <div class="col-md-6">
                                                                    <h5 class="mt-1 mb-2 text-dark"><strong>MSME Details</strong></h5>
                                                                    
                                                                    <div class="row align-items-center mb-1">
                                                                        <div class="col-md-4">
                                                                            <label class="form-label">MSME Registered?</label>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-check form-check-primary mt-25 custom-checkbox">
                                                                                <input type="checkbox" class="form-check-input" name="compliance[msme_registered]" id="msmeRegisteredIndia">
                                                                                <label class="form-check-label" for="msmeRegisteredIndia">This vendor is MSME registered</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="row align-items-center mb-1">
                                                                        <div class="col-md-4">
                                                                            <label class="form-label">MSME No.</label>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <input type="text" name="compliance[msme_no]" class="form-control numberonly">
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="row align-items-center mb-1">
                                                                        <div class="col-md-4">
                                                                            <label class="form-label">MSME Type</label>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <select class="form-select" name="compliance[msme_type]">
                                                                                <option value="">Select</option>
                                                                                <option value="Micro">Micro</option>
                                                                                <option value="Small">Small</option>
                                                                                <option value="Medium">Medium</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="row align-items-center mb-1">
                                                                        <div class="col-md-4">
                                                                            <label class="form-label">Upload Certificate</label>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <input type="file" name="compliance[msme_certificate][]" multiple class="form-control">
                                                                            <div id="msmeCertificateLinks"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                       </div>
                                                           <!-- Start Bank Info -->
                                                           <div class="tab-pane" id="send">
                                                            <div class="table-responsive-md">
                                                                <table class="mt-1 table myrequesttablecbox table-striped po-order-detail custnewpo-detail border">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>S.NO.</th>
                                                                            <th>Bank Name</th>
                                                                            <th>Beneficiary Name</th>
                                                                            <th>Account Number</th>
                                                                            <th>Re-enter Account No.</th>
                                                                            <th>IFSC Code</th>
                                                                            <th>Primary</th>
                                                                            <th>Cancel Cheque</th>
                                                                            <th>Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="bank-info-container">
                                                                        <!-- Initial bank info entry -->
                                                                        <tr class="bank-info-row" data-index="0">
                                                                            <td>#</td>
                                                                            <td><input type="text" class="form-control mw-100" name="bank_info[0][bank_name]" /></td>
                                                                            <td><input type="text" class="form-control mw-100" name="bank_info[0][beneficiary_name]" /></td>
                                                                            <td><input type="text" class="form-control mw-100" name="bank_info[0][account_number]" /></td>
                                                                            <td><input type="text" class="form-control mw-100" name="bank_info[0][re_enter_account_number]" /></td>
                                                                            <td><input type="text" class="form-control mw-100" name="bank_info[0][ifsc_code]" /></td>
                                                                            <td>
                                                                             <input type="radio" name="bank_info[0][primary]" value="1" class="primary-radio">
                                                                            </td>
                                                                            <td><input type="file" class="form-control mw-100" name="bank_info[0][cancel_cheque][]" multiple /></td>
                                                                            <td>
                                                                                <a href="#" class="text-primary add-bank-row"><i data-feather="plus-square" class="me-50"></i></a>
                                                                                <a href="#" class="text-danger delete-bank-row"><i data-feather="trash-2" class="me-50"></i></a>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <!-- End Bank Info -->

                                                      <!--Start Note -->
														<div class="tab-pane" id="latestrates">
                                                            <label class="form-label">Notes (For Internal Use)</label>  
												            <textarea class="form-control" name="notes[remark]" placeholder="Enter Notes...."></textarea>
														</div> 
                                                     <!--End Note -->

                                                     <!-- Item start -->
                                                        <div class="tab-pane" id="Items">
                                                        <div class="table-responsive-md"> 
                                                            <table class="mt-1 table myrequesttablecbox table-striped po-order-detail custnewpo-detail border" id="vendorTable">
                                                                <thead>
                                                                    <tr>
                                                                        <th>S.NO.</th>
                                                                        <th width="300px">Item</th>
                                                                            <th>Customer Item Code</th>
                                                                            <th>Customer Item Name</th>
                                                                            <th>Customer Item Details</th>
                                                                            <th id="sell-price-header">Sell Price</th>
                                                                            <th>Sell Uom</th>
                                                                            <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="vendorTableBody">
                                                                <tr id="row-0">
                                                                            <td>1</td>
                                                                            <td>
                                                                                <input type="text" name="customer_item[0][item_name]" class="form-control mw-100 vendor-autocomplete" data-id="0" placeholder="Search Item">
                                                                                <input type="hidden" id="item-id_0" name="customer_item[0][item_id]" class="item-id" value="">
                                                                            </td>
                                                                            <td><input type="text" name="customer_item[0][item_code]" class="form-control mw-100"></td>
                                                                            <td><input type="text" name="customer_item[0][item_name]" class="form-control mw-100"></td>
                                                                            <td><input type="text" name="customer_item[0][item_details]" class="form-control mw-100"></td>
                                                                            <td><input type="text" name="customer_item[0][sell_price]" id="sell-price_0" class="form-control sell-price-approved-customer mw-100"></td>
                                                                            <td><select name="customer_item[0][uom_id]"  id="uom_0" class="form-select mw-100" disabled></select></td>
                                                                            <td>
                                                                                <a href="#" class="text-danger delete-item"><i data-feather="trash-2" class="me-50"></i></a>
                                                                                <a href="#" class="text-primary add-item"><i data-feather="plus-square" class="me-50"></i></a>
                                                                            </td>
                                                                        </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        </div>
                                                      <!-- Item End -->
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
 </form>
    <!-- END: Content-->
@endsection
@section('scripts')
 <!-- for item -->
 <script>
    $(document).ready(function() {
        var selectedItemIds = [];
        function fetchUOMs(itemId, rowId) {
            $.ajax({
                url: "{{ url('/vendors/get-uoms') }}", 
                method: 'POST',
                data: { item_id: itemId },
                success: function(data) {
                    var uomSelect = $('#uom_' + rowId);
                    uomSelect.empty();
                    uomSelect.append('<option value="">Select</option>');
                    data.alternate_uoms.forEach(function(uom) {
                        uomSelect.append('<option value="' + uom.id + '">' + uom.name + '</option>');
                    });

                    uomSelect.prop('disabled', true); 
                },
                error: function(xhr) {
                    console.error('Error fetching UOM data:', xhr.responseText);
                }
            });
        }
        $('#vendorTable').on('input', '.sell-price-approved-customer', function () {
            var rowId = $(this).closest('tr').attr('id').split('-')[1]; 
            var sellPrice = $('#sell-price_' + rowId).val(); 
            if (sellPrice && !isNaN(sellPrice)) {
                $('#uom_' + rowId).prop('disabled', false);
            } else {
                $('#uom_' + rowId).prop('disabled', true);
            }
        });
        function initializeVendorAutocomplete(selector) {
            $(selector).autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "{{ url('/items/search') }}",
                        method: 'GET',
                        dataType: 'json',
                        data: {
                            term: request.term 
                        },
                        success: function(data) {
                            var filteredData = data.filter(function(item) {
                                return !selectedItemIds.includes(item.id); 
                            });
                            response($.map(filteredData, function(item) {
                                return {
                                    id: item.id,
                                    label: item.label,
                                    value: item.value,
                                    code: item.code || '', 
                                    item_id: item.id
                                };
                            }));
                        },
                        error: function(xhr) {
                            console.error('Error fetching vendor data:', xhr.responseText);
                        }
                    });
                },
                minLength: 0,
                select: function(event, ui) {
                    $(this).val(ui.item.label); 
                    var rowId = $(this).data('id');
                    $('#item-id_' + rowId).val(ui.item.id);
                    $('#item-code_' + rowId).val(ui.item.code);
                    
                    if (!selectedItemIds.includes(ui.item.id)) {
                        selectedItemIds.push(ui.item.id);
                    }
                    fetchUOMs(ui.item.id, rowId);
                    return false;
                },
                change: function(event, ui) {
                    var rowId = $(this).data('id');
                    var currentItemId = $('#item-id_' + rowId).val(); 
                    if (!ui.item) {
                        $(this).val("");  
                        $('#item-id_' + rowId).val(''); 
                        if (currentItemId && selectedItemIds.includes(parseInt(currentItemId))) {
                            selectedItemIds.splice(selectedItemIds.indexOf(parseInt(currentItemId)), 1);
                        }
                    }
                }
            }).focus(function() {
                if (this.value === "") {
                    $(this).autocomplete("search", "");
                }
            });
        }
        function updateRowIndices() {
            $('#vendorTable tbody tr').each(function(index) {
                var $row = $(this);
                $row.find('td:first').text(index + 1);
                $row.find('input, select').each(function() {
                    var $this = $(this);
                    var name = $this.attr('name');
                    if (name) {
                        $this.attr('name', name.replace(/\[\d+\]/, '[' + index + ']'));
                    }
                    var id = $this.attr('id');
                    if (id) {
                        $this.attr('id', id.replace(/\d+$/, index));
                    }
                    var dataId = $this.data('id');
                    if (dataId !== undefined) {
                        $this.data('id', index);
                    }
                });
                $row.attr('id', 'row-' + index);
                $row.find('.delete-item').show();
                $row.find('.add-item').toggle(index === 0);
                var uomSelect = $row.find('select[id^="uom_"]');
                uomSelect.prop('disabled', true);
                var sellPriceInput = $row.find('input[id^="sell-price_"]');
                var sellPrice = sellPriceInput.val();
                if (sellPrice && !isNaN(sellPrice)) {
                    uomSelect.prop('disabled', false); 
                }
            });
            initializeVendorAutocomplete(".vendor-autocomplete");
        }
        $('#vendorTable').on('click', '.add-item', function(e) {
            e.preventDefault();
            
            var newRow = $('#vendorTable tbody tr:first').clone();
            var rowCount = $('#vendorTable tbody tr').length;

            newRow.find('td:first').text(rowCount + 1);
            newRow.attr('id', 'row-' + rowCount);

            newRow.find('input').each(function() {
                $(this).val('');
                var id = $(this).attr('id');
                if (id) {
                    $(this).attr('id', id.replace(/\d+$/, rowCount)); 
                }
                var dataId = $(this).data('id');
                if (dataId !== undefined) {
                    $(this).data('id', rowCount); 
                }
            });
            newRow.find('select').each(function() {
                var selectId = $(this).attr('id');
                if (selectId) {
                    $(this).attr('id', selectId.replace(/\d+$/, rowCount)); 
                }
                $(this).prop('disabled', true);
            });
            
            $('#vendorTable tbody').append(newRow);
            updateRowIndices();
            feather.replace(); 
        });

        $('#vendorTable').on('click', '.delete-item', function(e) {
            e.preventDefault();
            var rowId = $(this).closest('tr').find('input[data-id]').data('id');
            var itemIdToRemove = $('#item-id_' + rowId).val();
            if (itemIdToRemove && selectedItemIds.includes(parseInt(itemIdToRemove))) {
                selectedItemIds.splice(selectedItemIds.indexOf(parseInt(itemIdToRemove)), 1);
            }
            
            $(this).closest('tr').remove();
            updateRowIndices();
        });

        $('#addVendor').on('click', function(e) {
            e.preventDefault();
            $('#vendorTable').find('.add-item').first().trigger('click'); 
        });

        initializeVendorAutocomplete(".vendor-autocomplete");
    });
</script>

 <script>
    $(document).ready(function() {
        var titles = @json($titles);
        var $contactsTableBody = $('#contactsTable tbody');
        function updateDropdown($select) {
            var options = '<option value="">Select</option>' + titles.map(function(title) {
                return '<option>' + title + '</option>';
            }).join('');
            $select.html(options);
            console.log("Dropdown updated with titles:", titles); 
        }

        function updateIcons() {
            var rows = $contactsTableBody.find('tr');
            rows.each(function(index) {
                var $row = $(this);
                $row.find('td').eq(0).text(index + 1); 

                $row.find('input[name]').each(function() {
                    var name = $(this).attr('name');
                    $(this).attr('name', name.replace(/\[\d+\]/, '[' + index + ']')); 
                });
                $row.find('.delete-contact-row').show();
                $row.find('.add-contact-row').toggle(index === 0);
            });
        }

        function addContactRow() {
            var rowCount = $contactsTableBody.children().length;
            var $currentRow = $contactsTableBody.find('tr:last');
            var $newRow = $currentRow.clone();
            $newRow.find('[name]').each(function() {
                var name = $(this).attr('name');
                $(this).attr('name', name.replace(/\[\d+\]/, '[' + rowCount + ']')); 
                $(this).val(''); 
            });
            $newRow.find('input[type=radio]').prop('checked', false);
            updateDropdown($newRow.find('.form-select'));
            $contactsTableBody.append($newRow);
            feather.replace();
            updateIcons();
        }
        $contactsTableBody.on('click', '.delete-contact-row', function(e) {
            e.preventDefault();
            $(this).closest('tr').remove();
            $contactsTableBody.children().each(function(index) {
                $(this).find('td:first').text(index + 1); 
                $(this).find('[name]').each(function() {
                    var name = $(this).attr('name');
                    $(this).attr('name', name.replace(/\[\d+\]/, '[' + index + ']')); 
                });
            });

            updateIcons();
        });

        $contactsTableBody.on('change', 'input[type=radio]', function() {
            var $radioButtons = $contactsTableBody.find('input[type=radio]');
            $radioButtons.prop('checked', false); 
            $(this).prop('checked', true);
            $radioButtons.each(function() {
                var value = $(this).is(':checked') ? 1 : 0;
                $(this).val(value);
            });
        });
        if ($contactsTableBody.children().length === 0) {
            addContactRow(); 
        } else {
            updateIcons(); 
        }
        $(document).on('click', '.add-contact-row', function(e) {
            e.preventDefault();
            addContactRow();
        });
        updateIcons();
    });
</script>

<script>
    $(document).ready(function() {
        const countries = @json($countries); 
        const addressTypes = @json($addressTypes); 

        function populateSelect(element, data, placeholder) {
            let options = `<option value="">${placeholder}</option>`;
            data.forEach(item => {
                options += `<option value="${item.id || item}">${item.name || item}</option>`;
            });
            element.html(options);
        }

        function populateDynamicSelects() {
            const $countrySelects = $('.country-select');
            const $addressTypeSelects = $('.address-type');
            populateSelect($countrySelects, countries, 'Select Country');
            populateSelect($addressTypeSelects, addressTypes, 'Select Type');
        }
        function populateRowWithValues($row) {
            const countryId = $row.find('.country-select').val();
            const stateId = $row.find('.state-select').val();
            $row.find('.address-type').each(function() {
                const $select = $(this);
                populateSelect($select, addressTypes, 'Select Type');
            });
            $row.find('.country-select').each(function() {
                const $select = $(this);
                populateSelect($select, countries, 'Select Country');
            });
            if (countryId) {
                $.get(`{{ url('/vendors/states/') }}/${countryId}`, function(states) {
                    $row.find('.state-select').each(function() {
                        const $select = $(this);
                        populateSelect($select, states, 'Select State');
                    });
                });
            }
            if (stateId) {
                $.get(`{{ url('/vendors/cities/') }}/${stateId}`, function(cities) {
                    $row.find('.city-select').each(function() {
                        const $select = $(this);
                        populateSelect($select, cities, 'Select City');
                    });
                });
            }
        }

        populateDynamicSelects();

        $('#address-table-body').on('change', '.country-select', function() {
            const $row = $(this).closest('tr');
            const countryId = $(this).val();
            const $stateSelect = $row.find('.state-select');
            const $citySelect = $row.find('.city-select');

            $stateSelect.html('<option value="">Select State</option>');
            $citySelect.html('<option value="">Select City</option>');

            if (countryId) {
                $.get(`/vendors/states/${countryId}`, function(states) {
                    populateSelect($stateSelect, states, 'Select State');
                });
            }
        });

        $('#address-table-body').on('change', '.state-select', function() {
            const $row = $(this).closest('tr');
            const stateId = $(this).val();
            const $citySelect = $row.find('.city-select');

            $citySelect.html('<option value="">Select City</option>');

            if (stateId) {
                $.get(`/vendors/cities/${stateId}`, function(cities) {
                    populateSelect($citySelect, cities, 'Select City');
                });
            }
        });

        $(document).on('click', '.add-address', function(e) {
            e.preventDefault();
            const $lastRow = $('#address-table-body .address-row').last();
            const index = $lastRow.data('index') + 1;
            const $newRow = $lastRow.clone().attr('data-index', index);
            $newRow.find('input').val('');
            $newRow.find('select').val('');
            $newRow.find('input[type="radio"]').prop('checked', false);
            populateRowWithValues($newRow);
            $('#address-table-body').append($newRow);
            updateRowIndexes();
            handleRadioSelection();
        });

        $(document).on('click', '.delete-address', function(e) {
            e.preventDefault();
            if ($('#address-table-body .address-row').length > 1) {
                $(this).closest('.address-row').remove();
                updateRowIndexes();
            }
        });
        function updateRowIndexes() {
            $('#address-table-body .address-row').each(function(index) {
                $(this).find('.index').text(index + 1);
                $(this).find('input, select').each(function() {
                    $(this).attr('name', $(this).attr('name').replace(/\[\d+\]/, `[${index}]`));
                });
                $(this).find('.delete-address').show(); 
                $(this).find('.add-address').toggle(index === 0);
            });
        }
        function handleRadioSelection() {
            $('#address-table-body').on('change', 'input[type="radio"][name*="[is_billing]"]', function() {
                $('#address-table-body input[type="radio"][name*="[is_billing]"]').not(this).prop('checked', false);
                $(this).val('1');
            });

            $('#address-table-body').on('change', 'input[type="radio"][name*="[is_shipping]"]', function() {
                $('#address-table-body input[type="radio"][name*="[is_shipping]"]').not(this).prop('checked', false);
                $(this).val('1');
            });
        }
        updateRowIndexes();
        handleRadioSelection();
    });
</script>

<script>
$(document).ready(function() {
    var $bankTableBody = $('#bank-info-container');
    var index = $bankTableBody.children('.bank-info-row').length;

    function updateRowIndices() {
        $bankTableBody.find('.bank-info-row').each(function(i) {
            var $row = $(this);
            $row.find('td:first').text(i + 1);
            $row.find('input[name]').each(function() {
                var name = $(this).attr('name');
                $(this).attr('name', name.replace(/\[\d+\]/, '[' + i + ']'));
            });
            $row.find('.delete-bank-row').show();
            $row.find('.add-bank-row').toggle(i === 0);
        });
    }

     function addNewRow() {
        let $template = $bankTableBody.find('.bank-info-row:first').clone();
        $template.attr('data-index', index++);

        $template.find('input').each(function() {
            let name = $(this).attr('name');
            if ($(this).attr('type') !== 'file') {
                $(this).val('');
            }
            $(this).attr('name', name.replace(/\d+/, index - 1)); 
        });
        $template.find('input[type=radio]').prop('checked', false).val('0');
        $template.find('input[type=file]').val('');
        $template.find('.file-link').parent().hide();
        $bankTableBody.append($template);
        updateRowIndices();
        feather.replace();
    }
    $('#bank-info-container').on('change', 'input[type=radio]', function() {
        $('#bank-info-container input[type=radio]').each(function() {
            $(this).prop('checked', false).val('0');
        });
        $(this).prop('checked', true).val('1');
    });

    $bankTableBody.on('click', '.delete-bank-row', function(e) {
        e.preventDefault();
        $(this).closest('.bank-info-row').remove();
        updateRowIndices();
    });

    $bankTableBody.on('click', '.add-bank-row', function(e) {
        e.preventDefault();
        addNewRow();
    });
    if ($bankTableBody.children('.bank-info-row').length === 0) {
        addNewRow(); 
    }
    updateRowIndices(); 
});
</script>

<script>
$(document).ready(function() {
    var today = new Date().toISOString().split('T')[0];
    $('#transactionDate').val(today);
    function fetchExchangeRates() {
        var transactionDate = $('#transactionDate').val();
        var currencyId = $('#currencySelect').val();
        $('#orgCurrencyRow, #groupCurrencyRow, #companyCurrencyRow').hide();
        if (currencyId && transactionDate) {
            $.ajax({
                url: '/exchange-rates/get-currency-exchange-rate',
                type: 'POST',
                data: {
                    currency: currencyId,
                    date: transactionDate,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status) {
                        $('#orgCurrencyRow, #groupCurrencyRow, #companyCurrencyRow').show();
                        $('#orgCurrencySymbol').text(response.data.org_currency_code);
                        $('#groupCurrencySymbol').text(response.data.group_currency_code);
                        $('#companyCurrencySymbol').text(response.data.comp_currency_code);
                        $('#orgCurrency').val(response.data.org_currency_exg_rate);
                        $('#groupCurrency').val(response.data.group_currency_exg_rate);
                        $('#companyCurrency').val(response.data.comp_currency_exg_rate);
                        $('#submit-button').prop('disabled', false);
                        $('#save-draft-button').prop('disabled', false); 
                    } else {
                        alert(response.message);
                        $('#submit-button').prop('disabled', true);
                        $('#save-draft-button').prop('disabled', true);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error: ', error);
                    alert('An error occurred while fetching exchange rates.');
                    $('#submit-button').prop('disabled', true);
                    $('#save-draft-button').prop('disabled', true);
                }
            });
        } else {
            alert('Please select a currency and ensure the date is set.');
            $('#submit-button').prop('disabled', true);
            $('#save-draft-button').prop('disabled', true);
        }
    }
    $('#currencySelect').on('change', function() {
        fetchExchangeRates();
    });
    if ($('#currencySelect').val()) {
        fetchExchangeRates();
    }
});
</script>
@endsection
