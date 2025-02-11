@if($permanent_address)
					<div class="col-md-12 col-xs-12">
						<h5 class="front-form-head no-bg pl-0 font-weight-bold">  Permanent Address</h5> 
                    </div>

					<div class="col-md-4 col-xs-4">
						<div class="form-group">
							<label>Address*</label>
							<p>{{$permanent_address->address}}</p></div> 
					</div>
					
					<div class="col-md-4 col-xs-4">
						<div class="form-group">
							<label>Police Station*</label>
							<p>{{$permanent_address->police_station}}</p>
						</div> 
					</div>
					
					<div class="col-md-4 col-xs-4">
						<div class="form-group">
							<label>Nearest Railway Station*</label>
							<p>{{$permanent_address->nearest_railway_station}}</p>
						</div> 
					</div>
					
					<div class="col-md-4 col-xs-4">
						<div class="form-group">
							<label>Country*</label>
							<p>{{$permanent_address->country}}</p>
						</div> 
					</div>
					
					<div class="col-md-4 col-xs-4">
						<div class="form-group">
							<label>State/Union Territory*</label>
							<p>{{$permanent_address->state_union_territory}}</p>
						</div> 
					</div>
					
					<div class="col-md-4 col-xs-4">
						<div class="form-group">
							<label>District*</label>
							<p>{{$permanent_address->district}}</p>
						</div> 
					</div>
					
					<div class="col-md-4 col-xs-4">
						<div class="form-group">
							<label>PIN Code*</label>
							<p>{{$permanent_address->pin_code}}</p>
						</div> 
					</div>

					<div class="col-md-12 col-xs-12">
						<h5 class="front-form-head no-bg pl-0 font-weight-bold">  Correspondence Address</h5> 
                    </div>
					<div class="col-md-4 col-xs-4">
						<div class="form-group">
							<label>Address*</label>
							<p>{{$correspondence_address->address}}</p></div> 
					</div>
					
					<div class="col-md-4 col-xs-4">
						<div class="form-group">
							<label>Police Station*</label>
							<p>{{$correspondence_address->police_station}}</p>
						</div> 
					</div>
					
					<div class="col-md-4 col-xs-4">
						<div class="form-group">
							<label>Nearest Railway Station*</label>
							<p>{{$correspondence_address->nearest_railway_station}}</p>
						</div> 
					</div>
					
					<div class="col-md-4 col-xs-4">
						<div class="form-group">
							<label>Country*</label>
							<p>{{$correspondence_address->country}}</p>
						</div> 
					</div>
					
					<div class="col-md-4 col-xs-4">
						<div class="form-group">
							<label>State/Union Territory*</label>
							<p>{{$correspondence_address->state_union_territory}}</p>
						</div> 
					</div>
					
					<div class="col-md-4 col-xs-4">
						<div class="form-group">
							<label>District*</label>
							<p>{{$correspondence_address->district}}</p>
						</div> 
					</div>
					
					<div class="col-md-4 col-xs-4">
						<div class="form-group">
							<label>PIN Code*</label>
							<p>{{$correspondence_address->pin_code}}</p>
						</div> 
					</div>

@endif