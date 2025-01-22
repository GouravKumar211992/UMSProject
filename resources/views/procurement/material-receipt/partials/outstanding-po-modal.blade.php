<div class="modal fade text-start" id="poModal" tabindex="-1" aria-labelledby="prModal" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 1500px">
		<div class="modal-content">
			<div class="modal-header">
				<div>
					<h4 class="modal-title fw-bolder text-dark namefont-sizenewmodal" id="poModal">
						Select Purchase Order
					</h4>
					<p class="mb-0">
						Select from the below list
					</p>
				</div>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col">
						<div class="mb-1">
							<label class="form-label">Series</label>
							<input type="text" id="book_code_input_qt" placeholder="Select" class="form-control mw-100 ledgerselecct ui-autocomplete-input" autocomplete="off" value="">
							<input type = "hidden" id = "book_id_qt_val"></input>
						</div>
					</div>
					<div class="col">
						<div class="mb-1">
							<label class="form-label">Doc No.</label>
							<input type="text" id="document_no_input_qt" placeholder="Select" class="form-control mw-100 ledgerselecct ui-autocomplete-input" autocomplete="off" value="">
							<input type = "hidden" id = "document_id_qt_val"></input>
						</div>
					</div>
					<div class="col">
						<div class="mb-1">
							<label class="form-label">Item</label>
							<input type="text" id="item_name_input_qt" placeholder="Select" class="form-control mw-100 ledgerselecct ui-autocomplete-input" autocomplete="off" value="">
							<input type = "hidden" id = "item_id_qt_val"></input>
						</div>
					</div>
					<div class="col">
						<div class="mb-1">
							<label class="form-label">Vendor</label>
							<input type="text" id="vendor_code_input_qt" placeholder="Select" class="form-control mw-100 ledgerselecct ui-autocomplete-input" autocomplete="off" value="">
							<input type = "hidden" id = "vendor_id_qt_val"></input>
						</div>
					</div>
					<div class="col mb-1">
						<label class="form-label">&nbsp;</label><br/>
						<button type = "button" class="btn btn-warning btn-sm searchPoBtn">
							<i data-feather="search"></i> Search
						</button>
					</div>
					<div class="col-md-12">
						<div class="table-responsive" style="overflow-y: auto;max-height: 700px;">
							<table class="mt-1 table myrequesttablecbox table-striped po-order-detail"> 
								<thead>
									<tr>
										<th>
											<div class="form-check form-check-inline me-0">
												<input class="form-check-input" type="checkbox" name="podetail" id="inlineCheckbox1">
											</div> 
										</th>
										<th>SUPPLIER NAME</th>
                                        <th>PO SERIES</th>
                                        <th>PO NO</th>
                                        <th>PO DATE</th>
                                        <th>INV SERIES</th>
                                        <th>INV NO</th>
                                        <th>INV DATE</th>
                                        <th>ITEM NAME[ITEM CODE]</th>
                                        <th>ATTRIBUTES</th>
                                        <th>PO QTY</th>
                                        <th>INV QTY</th>
										<th>GRN QTY</th>
                                        <th>BALANCE QTY</th>
                                        <th>RATE</th>
                                        <th>VALUE</th>
									</tr>
								</thead>
								<tbody id="poDataTable">
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer text-end">
				<button type = "button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
					<i data-feather="x-circle"></i> Cancel
				</button>
				<button type = "button" class="btn btn-primary btn-sm poProcess">
					<i data-feather="check-circle"></i> Process
				</button>
			</div>
		</div>
	</div>
</div>