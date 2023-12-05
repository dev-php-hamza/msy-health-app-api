@extends('admin.dashboard')

@section('content')
<!-- page content -->
<div class="right_col" role="main">
	<div class="">
		<div class="col-md-12 col-sm-12">
			<div class="x_panel">
				@if(count($errors) > 0)
				  <ul>
				    @foreach($errors->all() as $error)
				      <li class="alert alert-danger">{{$error}}</li>
				    @endforeach
				  </ul>
				@endif
				<div class="x_title d-flex justify-content-between">
					<h2>Update User</h2>
				</div>
				<div class="x_content">
				<br />
					<form class="form-horizontal form-label-left" method="post" action="{{ route('users.update',$user->id) }}" enctype="multipart/form-data">
						@csrf
						@method('PUT')
						<div class="card">
							<div class="card-header">Mobile App User</div>
							<div class="card-body">
								<div class="form-group row">
									<label class="col-form-label col-md-3 col-sm-3  label-align">Name<span
		                          class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-6">
										<input class="form-control" name="name" value="{{ $user->name }}">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-md-3 col-sm-3  label-align">Email<span
		                          class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-6">
										<input class="form-control" name="email" value="{{ $user->email }}" readonly>
									</div>
								</div>
							</div>
						</div>
						<br>
						<div class="card">
							<div class="card-header">User Info</div>
							<div class="card-body">
								@php
									$user         = $user;
									$userInfo     = $user->userInfo;
								@endphp
								<div class="form-group row">
									<label class="col-form-label col-md-3 col-sm-3  label-align">Gender<span
		                          class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-6">
										<select class="form-control" name="gender" required>
											<option value="male" {{ ($userInfo->gender == 'male')?'selected':''}}>Male</option>
											<option value="female" {{ ($userInfo->gender == 'female')?'selected':''}}>Female</option>
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-md-3 col-sm-3  label-align">Date of birth<span
		                          class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-6">
										<input type="date" class="form-control" name="date_of_birth" value="{{ date('Y-m-d', strtotime($userInfo->date_of_birth)) }}">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-md-3 col-sm-3  label-align">Phone<span
		                          class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-6">
										<input class="form-control" name="phone" value="{{ $userInfo->phone }}">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-md-3 col-sm-3  label-align">Country<span
		                          class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-6">
										<select class="form-control" id="country" name="country_id" required disabled>
											<option value="">Please choose country</option>
											@foreach($countries as $key => $country)
												<option value="{{$country->id}}" {{($country->id === $user->country_id)?'selected':''}}>{{$country->name}}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-md-3 col-sm-3  label-align">Massy Employee<span
		                          class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-6">
										<select class="form-control" id="{{($user->is_employee == 0)?'n':'y'}}" name="is_employee" onchange="removeElements(this)" required>
											<option value="0" {{($user->is_employee == 0)?'selected':''}}>No</option>
											<option value="1" {{($user->is_employee == 1)?'selected':''}}>Yes</option>
										</select>
									</div>
								</div>
								<div id="dynamicContainer"></div>
								@if($user->is_employee)
								<div id="employee">
									<div class="form-group row">
										<label class="col-form-label col-md-3 col-sm-3  label-align">Company<span
			                          class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-6">
											<select class="form-control" id="companies" name="company_id" onchange="getDepartmentsByComapny(this)" required>
												<option value="">Please choose company</option>
												@foreach($companies as $key => $company)
													<option value="{{$company->id}}" {{($company->id === $userInfo->company_id)?'selected':''}}>{{$company->name}}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-form-label col-md-3 col-sm-3  label-align">Department<span
			                          class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-6">
											<select class="form-control" id="departments" name="department_id" required>
												<option value="">Please choose department</option>
												@foreach($departments as $key => $deparment)
													<option value="{{$deparment->id}}" {{($deparment->id === $userInfo->department_id)?'selected':''}}>{{$deparment->name}}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-form-label col-md-3 col-sm-3  label-align">Employee Number<span
			                          class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-6">
											<input class="form-control" id="employee_number" name="employee_number" value="{{ $userInfo->employee_number }}">
										</div>
									</div>
								</div>
								@else
								<div id="employee">
									<div class="form-group row">
										<label class="col-form-label col-md-3 col-sm-3  label-align">Location/Area<span
			                          class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-6">
											<select class="form-control" id="locations" name="city" required>
												<option value="">Please choose Location</option>
												@foreach($locations as $key => $location)
													<option value="{{$location->id}}" {{ ($location->id == $user->city)?'selected':'' }}>{{$location->name}}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-form-label col-md-3 col-sm-3  label-align">Address<span
			                          class="required">*</span></label>
										<div class="col-md-6 col-sm-6 col-xs-6">
											<input class="form-control" name="address" placeholder="Please enter address" value="{{ $user->address }}">
										</div>
									</div>
								</div>
								@endif
								{{-- <div class="form-group row">
									<label class="col-form-label col-md-3 col-sm-3  label-align">Image<span
		                          class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-6">
										@if($user->profile_image)
											<img src="{{$user->profile_image}}" alt="userImage" width="100" height="100" class="mb-3 mt-2">
										@else
											<img src="{{asset('assets/images/no-image.png')}}" alt="userImage" width="100" height="100" class="mb-3 mt-2">
										@endif
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-md-3 col-sm-3  label-align">Verification Image<span
		                          class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-6">
										@if($user->verification_id_image)
											<img src="{{$user->verification_id_image}}" alt="userImage" width="100" height="100" class="mb-3 mt-2">
										@else
											<img src="{{asset('assets/images/no-image.png')}}" alt="verifyImage" width="100" height="100" class="mb-3 mt-2">
										@endif
									</div>
								</div> --}}
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
</div>
<!-- /page content -->
@endsection

@section('scripts')
<!-- <script src="{{ asset('js/users.js') }}"></script> -->
<script>
	function removeElements(elem) {
	    let employee = elem.id;
	    let choosenValue = elem.value;
	    let countryId = $('#country').val();

	    if (employee == 'y') {
	        if (choosenValue == 0) {
	            $('#employee').empty();
	            //append loacation and address divs

	            let newElements = '<div class="form-group row"><label class="col-form-label col-md-3 col-sm-3  label-align">Location/Area<span class="required">*</span></label><div class="col-md-6 col-sm-6 col-xs-6"><select class="form-control" id="locations" name="city" required></select></div></div><div class="form-group row"><label class="col-form-label col-md-3 col-sm-3  label-align">Address<span class="required">*</span></label><div class="col-md-6 col-sm-6 col-xs-6"><input class="form-control" name="address" placeholder="Please enter address" required></div></div>';
	            $('#employee').append(newElements);

	            getLocations(countryId);
	        }else{
	            $('#employee').empty();
	            //append companies, departments and employee number divs

	            let newElements = '<div class="form-group row"><label class="col-form-label col-md-3 col-sm-3  label-align">Company<span class="required">*</span></label><div class="col-md-6 col-sm-6 col-xs-6"><select class="form-control" id="companies" name="company_id" onchange="getDepartmentsByComapny(this)" required><option value="">Please choose company</option></select></div></div><div class="form-group row"><label class="col-form-label col-md-3 col-sm-3  label-align">Department<span class="required">*</span></label><div class="col-md-6 col-sm-6 col-xs-6"><select class="form-control" id="departments" name="department_id" required><option value="">Please choose department</option></select></div></div><div class="form-group row"><label class="col-form-label col-md-3 col-sm-3  label-align">Employee Number<span class="required">*</span></label><div class="col-md-6 col-sm-6 col-xs-6"><input class="form-control" name="employee_number" id="employee_number" placeholder="Please enter employee number" required></div></div>';
	            $('#employee').append(newElements);

	            getCompanies(countryId);
	        }
	    }else{
	    	if (choosenValue == 0) {
	    	    $('#employee').empty();
	    	    //append loacation and address divs

	    	    let newElements = '<div class="form-group row"><label class="col-form-label col-md-3 col-sm-3  label-align">Location/Area<span class="required">*</span></label><div class="col-md-6 col-sm-6 col-xs-6"><select class="form-control" id="locations" name="city" required></select></div></div><div class="form-group row"><label class="col-form-label col-md-3 col-sm-3  label-align">Address<span class="required">*</span></label><div class="col-md-6 col-sm-6 col-xs-6"><input class="form-control" name="address" placeholder="Please enter address" required></div></div>';
	    	    $('#employee').append(newElements);

	    	    getLocations(countryId);
	    	}else{
	    	    $('#employee').empty();
	    	    //append companies, departments and employee number divs

	    	    let newElements = '<div class="form-group row"><label class="col-form-label col-md-3 col-sm-3  label-align">Company<span class="required">*</span></label><div class="col-md-6 col-sm-6 col-xs-6"><select class="form-control" id="companies" name="company_id" onchange="getDepartmentsByComapny(this)" required><option value="">Please choose company</option></select></div></div><div class="form-group row"><label class="col-form-label col-md-3 col-sm-3  label-align">Department<span class="required">*</span></label><div class="col-md-6 col-sm-6 col-xs-6"><select class="form-control" id="departments" name="department_id" required><option value="">Please choose department</option></select></div></div><div class="form-group row"><label class="col-form-label col-md-3 col-sm-3  label-align">Employee Number<span class="required">*</span></label><div class="col-md-6 col-sm-6 col-xs-6"><input class="form-control" name="employee_number" id="employee_number" placeholder="Please enter employee number" required></div></div>';
	    	    $('#employee').append(newElements);

	    	    getCompanies(countryId);
	    	}
	    }
	}

	function getLocations(countryId){
	    let base_url = window.location.origin;
	    if (countryId != '') {
	        $('#locations').empty();
	        $.ajaxSetup({
	            headers: {
	                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	            }
	        });
	        $.ajax({
	            type: 'get',
	            url: base_url+"/admin/locations/country/"+countryId,
	            dataType: "json",
	            beforeSend: function(){
	            },
	            success: function(response){
	                if(response['status']) {
	                    let newOpt = '';
	                    newOpt += "<option value=''>Please Choose Locaility</option>"
	                    $.each(response['locations'], function(index,location){
	                        console.log(index+">"+location)
	                        newOpt += "<option value='"+location['id']+"'>"+location['name']+"</option>"

	                    });

	                    $("#locations").append(newOpt);
	                }
	            }
	        });
	    }
	}

	function getCompanies(countryId){
	    let base_url = window.location.origin;

	    let userInfo     = {!! $userInfo->toJson() !!};
	    let departments  = {!! $departments->toJson() !!};

	    if (countryId != '') {
	        $('#companies').empty();
	        $('#departments').empty();
	        $.ajaxSetup({
	            headers: {
	                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	            }
	        });
	        $.ajax({
	            type: 'get',
	            url: base_url+"/admin/companies/country/"+countryId,
	            dataType: "json",
	            beforeSend: function(){
	            },
	            success: function(response){

	                if(response['status']) {
	                    let newOpt = '';
	                    newOpt += "<option value=''>Please Choose Company</option>"
	                    $.each(response['companies'], function(index,company){
	                    	if (userInfo['company_id'] == company['id']) {
	                    		newOpt += "<option value='"+company['id']+"' selected>"+company['name']+"</option>"
	                    	}else{
	                    		newOpt += "<option value='"+company['id']+"'>"+company['name']+"</option>"
	                    	}
	                    });

	                    let departOpt = '';
	                    departOpt += "<option value=''>Please Choose Company</option>"
	                    $.each(departments, function(index, department) {
	                    	if (userInfo['department_id'] == department['id']) {
	                    		departOpt += "<option value='"+department['id']+"' selected>"+department['name']+"</option>"
	                    	}else{
	                    		departOpt += "<option value='"+department['id']+"'>"+department['name']+"</option>"
	                    	}
	                    });

	                    $("#companies").append(newOpt);
	                    $("#departments").append(departOpt);

	                    $('#employee_number').val(userInfo['employee_number']);
	                }
	            }
	        });
	    }
	}

	function getDepartmentsByComapny(elem){
	    let companyId = elem.value;
	    let userInfo = {!! $userInfo->toJson() !!};
	    let base_url = window.location.origin;
	    if (companyId != '') {
	        $('#departments').empty();
	        $.ajaxSetup({
	            headers: {
	                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	            }
	        });
	        $.ajax({
	            type: 'get',
	            url: base_url+"/admin/departments/company/"+companyId,
	            dataType: "json",
	            beforeSend: function(){
	            },
	            success: function(response){
	                console.log(response);
	                console.log(response['status']);

	                if(response['status']) {
	                    let newOpt = '';
	                    newOpt += "<option value=''>Please Choose Department</option>"
	                    $.each(response['departments'], function(index,department){
	                    	if (userInfo['department_id'] == department['id']) {
	                    		newOpt += "<option value='"+department['id']+"' selected>"+department['name']+"</option>"
	                    	}else{
	                    		newOpt += "<option value='"+department['id']+"'>"+department['name']+"</option>"
	                    	}
	                    });

	                    $("#departments").append(newOpt);
	                }
	            }
	        });
	    }
	} 
</script>
@endsection