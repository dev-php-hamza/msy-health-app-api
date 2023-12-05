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
					<h2>Update Branch</h2>
				</div>

				<div class="x_content">
					<form class="form-horizontal form-label-left" method="post" action="{{ route('branches.update',$branch->id) }}">
						@csrf
						@method('PUT')
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Name<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $branch->name) }}" required>
								@error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Key Contact<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $branch->key_contact_email) }}" required>
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
								<input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $branch->phone) }}" required>
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
								<select class="form-control" name="country_id" onchange="getCompAndLoc(this)" required>
									<option value="">Choose Country</option>
									@foreach($countries as $country)
										<option value="{{$country->id}}" {{($country->id === $branchCountryId)?'selected':''}}>{{$country->name}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Company<span class="required">*</span></label>
							<input type="hidden" name="">
							<div class="col-md-6 col-sm-6 col-xs-6">
								<select class="form-control" name="company_id" id="companies" required>
									@foreach($companies as $company)
										<option value="{{$company->id}}" {{($company->id === $branch->company_id)?'selected':''}}>{{$company->name}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Location<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<select class="form-control" name="location_id" id="locations" required>
									@foreach($locations as $location)
										<option value="{{$location->id}}" {{($location->id === $branch->location_id)?'selected':''}}>{{$location->name}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Address<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<textarea class="form-control" name="address" rows="2" required>{{ old('address', $branch->address) }}</textarea>
							</div>
						</div>
						<div class="ln_solid"></div>
						<div class="form-group row">
							<div class="col-md-9 offset-md-3">
								
								<input type="submit" value="Update" class="btn btn-success">
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
@endsection