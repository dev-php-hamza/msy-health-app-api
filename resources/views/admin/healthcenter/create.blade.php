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
					<h2>Create Health Center</h2>
				</div>
				<div class="x_content">
					<form class="form-horizontal form-label-left" method="post" action="{{ route('health-centers.store') }}" id="healthForm" enctype="multipart/form-data">
						@csrf
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Name<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Enter health center name" value="{{ old('name') }}" required>
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
								<input type="text" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Optional" value="{{ old('email') }}" required>
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
								<input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" placeholder="Enter phone number" autocomplete="off" value="{{ old('phone') }}" required>
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
									@foreach($countries as $country)
										<option value="{{$country->id}}">{{$country->name}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Location<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<select class="form-control" name="location_id" id="locations" required>
									<option value=''>Please Choose Location</option>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Address<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input type="hidden" name="latitude" id="latitude_txt"  value="0">
								<input type="hidden" name="longitude" id="longitude_txt" value="0">
								<input type="text" class="form-control" name="address" id="address_txt" placeholder="Address..." value="{{ old('address')}}" required>
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
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Please Choose Image<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input type="file" class="form-control" name="centerFile" required>
							</div>
							@error('centerFile')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
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
	<script type="text/javascript" src="{{ asset('js/maps.js') }}"></script>
	<script async defer src="https://maps.googleapis.com/maps/api/js?key={{config('services.google.autocomplete_client_id')}}&libraries=places&callback=initMapForCreate"></script>
@endsection