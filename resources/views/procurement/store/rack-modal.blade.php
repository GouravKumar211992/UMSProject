<!-- Add/Edit Rack Modal -->
<div class="modal fade" id="addaccess" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header p-0 bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-sm-4 mx-50 pb-2">
                <h1 class="text-center mb-1" id="shareProjectTitle">Add/Edit Rack</h1>
                <p class="text-center">Enter the details below.</p>

                <div class="row mt-2">
                    <div class="col-md-12 mb-1">
                        <label class="form-label w-100">Rack No
                            <a href="#" id="add-rack" class="float-end text-primary font-small-2"></a>
                        </label>
                        <div class="d-flex align-items-center">
                            <input type="text" id="rack_code" class="form-control" placeholder="Enter No." aria-label="Rack Number">
                            <button class="btn btn-outline-primary ms-2 btn-sm " id="search-rack">
                                <i class="fa fa-search"></i> Search
                            </button>
                        </div>
                    </div>
                </div>
                
                 <input type="hidden" id="deleted_racks" name="deleted_racks" value="">

                <div class="table-responsive" style="max-height: 300px">
                    <table class="table myrequesttablecbox table-striped po-order-detail custnewpo-detail border newdesignerptable">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Rack No</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="rack-list">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>