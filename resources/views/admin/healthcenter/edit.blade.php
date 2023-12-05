@extends('admin.dashboard')

@section('content')
<!-- page content -->
<div class="right_col" role="main">
	<div class="row">
		@if(count($errors) > 0)
		  <ul>
		    @foreach($errors->all() as $error)
		      <li class="alert alert-danger">{{$error}}</li>
		    @endforeach
		  </ul>
		@endif
		<div class="col-md-12 col-sm-12">
			<div class="x_panel border-none">
				<div class="x_title d-flex justify-content-between">
					<h2>Update Health Center</h2>
				</div>
				<div class="x_content">
					<form class="form-horizontal form-label-left" method="post" action="{{ route('health-centers.update',$healthCenter->id) }}" id="healthForm" enctype="multipart/form-data">
						@csrf
						@method('PUT')
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Name<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Enter health center name" value="{{ old('name', $healthCenter->name) }}" required>
								@error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Email</label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Optional" value="{{ old('email', $healthCenter->email) }}" required>
								@error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Phone<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" placeholder="Enter phone number" value="{{ old('phone',$healthCenter->phone) }}" autocomplete="off" required>
								@error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Country<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<select class="form-control" name="country_id" required onchange="getLocations(this)">
									<option value="">Choose Country</option>
									@if(isset($countries) && !empty($countries) && $countries != '')
									@foreach($countries as $country)
										<option value="{{$country->id}}" {{($country->id === $hlthCountryId)?'selected':''}}>{{$country->name}}</option>
									@endforeach
									@endif
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Location<span class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<select class="form-control" name="location_id" id="locations" required>
									<option value="">Choose Location</option>
									@if(isset($locations) && !empty($locations) && $locations != '')
									@foreach($locations as $location)
										<option value="{{$location->id}}" {{($location->id === $healthCenter->location_id)?'selected':''}}>{{$location->name}}</option>
									@endforeach
									@endif
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Address<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input type="hidden" name="latitude" id="latitude_txt"  value="{{$healthCenter->latitude}}">
								<input type="hidden" name="longitude" id="longitude_txt" value="{{$healthCenter->longitude}}">
								<input type="text" class="form-control" name="address" id="address_txt" placeholder="Address..." value="{{ old('address', $healthCenter->address)}}" required>
								@error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
							</div>
						</div>
						<div class="form-group row justify-content-center">
							<div class="col-md-6 col-sm-6 col-xs-6">
							    <label>set location pin</label>
							    <div id="map" style="width: 100%; height: 400px;"></div>
							</div>
						</div>
						<div class="form-group row justify-content-center">
                          <div class="col-md-6 col-sm-6 col-xs-6">
                            @if($healthCenter->image)
								<img src="{{asset('assets/healthcenter/'.$healthCenter->image)}}" alt="healthCenterImg" width="100" height="100" class="mb-3 mt-2">
							@else
								<img src="{{asset('assets/images/no-image.png')}}" alt="healthCenterImg" width="100" height="100" class="mb-3 mt-2">
							@endif
                            </div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Please Choose Image<span
                          class="required">*</span></label>
                          	<div class="col-md-6 col-sm-6 col-xs-6">
								<input type="file" class="form-control height-auto" name="centerFile">
								@error('centerFile')
	                                <span class="invalid-feedback" role="alert">
	                                    <strong>{{ $message }}</strong>
	                                </span>
	                            @enderror
                           	</div>
						</div>
						<div class="ln_solid"></div>
						<div class="form-group row">
							<div class="col-md-9 offset-md-3">
								<button type="button" id="submitBtn" class="btn btn-success">submit</button>
							</div>
						</div>

					</form>
				</div>
                
            </div>

		</div>
	</div>
</div>
<!-- /page content -->


@endsection

@section('scripts')
	<script src="{{ asset('js/locations.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/editMaps.js') }}"></script>
	<script async defer src="https://maps.googleapis.com/maps/api/js?key={{config('services.google.autocomplete_client_id')}}&libraries=places&callback=initMapForCreate"></script>
@endsection