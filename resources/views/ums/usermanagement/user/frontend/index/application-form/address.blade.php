<div class="row mb-5">
    <div class="col-md-12">
        <h5 class="front-form-head no-bg pl-0 font-weight-bold"> Permanent Address</h5> 
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Address<span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="address" id="p_address" autocomplete="__away" />
            <div class="invalid-feedback text-danger address_application"></div>
        </div> 
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Police Station<span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="police_station" id="p_police_station" autocomplete="__away" />
            <div class="invalid-feedback text-danger police_station_application"></div>
        </div> 
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Nearest Railway Station<span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="nearest_railway_station" id="p_nearest_railway_station" autocomplete="__away" />
            <div class="invalid-feedback text-danger nearest_railway_station_application"></div>
        </div> 
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Country<span class="text-danger">*</span></label>
            <select class="form-control" id="p_country" name="country">
                <option value="">--Select Option--</option>
                @foreach($countries as $country)
                    <option value="{{$country->name}}">{{$country->name}}</option>
                @endforeach
            </select>
            <div class="invalid-feedback text-danger country_application"></div>
        </div> 
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>State/Union Territory<span class="text-danger">*</span></label>
            <select class="form-control" id="p_state_union_territory" name="state_union_territory">
                <option value="">--Select State/Union Territory--</option>
            </select>
            <div class="invalid-feedback text-danger state_union_territory_application"></div>
        </div> 
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>District<span class="text-danger">*</span></label>
            <select class="form-control" id="p_district" name="district">
                <option value="">--Select District--</option>
            </select>
            <div class="invalid-feedback text-danger district_application"></div>
        </div> 
    </div>

    <div class="col-md-4 mb-2">
        <div class="form-group">
            <label>PIN Code<span class="text-danger">*</span></label>
            <input type="text" class="form-control numbersOnly" name="pin_code" id="p_pin_code" maxlength="6" autocomplete="__away" />
            <div class="invalid-feedback text-danger pin_code_application"></div>
        </div> 
    </div>

    <div class="col-md-12">
        <p class="mb-0">
            <input type="checkbox" class="filled-in" name="is_correspondence_same" id="is_correspondence_same" value="1" autocomplete="__away">
            <label for="is_correspondence_same"  class="form-check-label">Click if Address for Correspondence is same.</label> 
            <div class="invalid-feedback text-danger is_correspondence_same_application"></div>
        </p>
    </div>

    <!-- Correspondence Address fields -->
    <div class="col-md-4">
        <div class="form-group">
            <label>Address<span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="correspondence_address" id="correspondence_address" autocomplete="__away" />
            <div class="invalid-feedback text-danger correspondence_address_application"></div>
        </div> 
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Police Station<span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="correspondence_police_station" id="correspondence_police_station" autocomplete="__away" />
            <div class="invalid-feedback text-danger correspondence_police_station_application"></div>
        </div> 
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Nearest Railway Station<span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="correspondence_nearest_railway_station" id="correspondence_nearest_railway_station" autocomplete="__away" />
            <div class="invalid-feedback text-danger correspondence_nearest_railway_station_application"></div>
        </div> 
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Country<span class="text-danger">*</span></label>
            <select class="form-control" id="correspondence_country" name="correspondence_country">
                <option value="">--Select Country--</option>
                @foreach($countries as $country)
                    <option value="{{$country->name}}">{{$country->name}}</option>
                @endforeach
            </select>
            <div class="invalid-feedback text-danger correspondence_country_application"></div>
        </div> 
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>State/Union Territory<span class="text-danger">*</span></label>
            <select class="form-control" id="correspondence_state_union_territory" name="correspondence_state_union_territory">
                <option value="">--Select State/Union Territory--</option>
            </select>
            <div class="invalid-feedback text-danger correspondence_state_union_territory_application"></div>
        </div> 
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>District<span class="text-danger">*</span></label>
            <select class="form-control" id="correspondence_district" name="correspondence_district">
                <option value="">--Select District--</option>
            </select>
            <div class="invalid-feedback text-danger correspondence_district_application"></div>
        </div> 
    </div>

    <div class="col-md-4">
        <div class="form-group mb-2">
            <label>PIN Code<span class="text-danger">*</span></label>
            <input type="text" class="form-control numbersOnly" name="correspondence_pin_code" id="correspondence_pin_code" maxlength="6" autocomplete="__away" />
            <div class="invalid-feedback text-danger correspondence_pin_code_application"></div>
        </div> 
    </div>
</div>
