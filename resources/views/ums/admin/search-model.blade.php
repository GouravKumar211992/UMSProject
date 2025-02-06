<div class="modal fade" id="search" tabindex="-1" role="dialog" aria-labelledby="loginpopupTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">

        <div class="modal-content bg-light modalPad">
            <form class="needs-validation" autocomplete="nope" novalidate>
                <div class="modal-header position-relative border-0">
                    <div class="">
                        <button type="button" class="close-btn" data-dismiss="modal" aria-label="Close">
                            <img src="/assets/admin/img/close.svg" />
                        </button>
                        <div class="">
                            <h5>{{$searchTitle}}</h5>
                            <p class="mb-0">Search</p>
                        </div>

                    </div>
                </div>
                <div class="modal-body pt-0">

                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="text" class="form-control searchInput" id="input_keyword" name="search" autocomplete="nope" placeholder="Type here..." maxlength="26">
                            </div>
                        </div>

                        <div class="col-md-12 mt-1">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>

                    </div>
                </div>

            </form>
        </div>

    </div>
</div>