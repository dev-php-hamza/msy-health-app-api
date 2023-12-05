@extends('admin.dashboard')

@section('content')
<!-- page content -->
<div class="right_col" role="main">
	<div class="">
		<div class="col-md-12 col-sm-12">
			<div class="x_panel">
				<div class="x_title d-flex justify-content-between">
					<h2>User Detail</h2>
				</div>
				<div class="x_content">
				<br />
					<form class="form-horizontal form-label-left">
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Name<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input class="form-control" value="{{ $user->name }}" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Email<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input class="form-control" value="{{ $user->email }}" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Gender<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input class="form-control" value="{{ $user->userInfo->gender }}" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Date of birth<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input class="form-control" value="{{ $user->userInfo->date_of_birth }}" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Phone<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input class="form-control" value="{{ $user->userInfo->phone }}" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Country<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input class="form-control" value="{{ ($user->country)?$user->country->name:'' }}" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Massy Employee<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input class="form-control" value="{{ ($user->is_employee)?'True':'False' }}" readonly>
							</div>
						</div>
						@if($user->is_employee)
							<div class="form-group row">
								<label class="col-form-label col-md-3 col-sm-3  label-align">Company<span
	                          class="required">*</span></label>
								<div class="col-md-6 col-sm-6 col-xs-6">
									<input class="form-control" value="{{ ($user->userInfo->company)?$user->userInfo->company->name:'' }}" readonly>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-form-label col-md-3 col-sm-3  label-align">Department<span
	                          class="required">*</span></label>
								<div class="col-md-6 col-sm-6 col-xs-6">
									<input class="form-control" value="{{ ($user->userInfo->department)?$user->userInfo->department->name:'' }}" readonly>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-form-label col-md-3 col-sm-3  label-align">Employee Number<span
	                          class="required">*</span></label>
								<div class="col-md-6 col-sm-6 col-xs-6">
									<input class="form-control" value="{{ $user->userInfo->employee_number }}" readonly>
								</div>
							</div>
						@else
							<div class="form-group row">
								<label class="col-form-label col-md-3 col-sm-3  label-align">City<span
	                          class="required">*</span></label>
								<div class="col-md-6 col-sm-6 col-xs-6">
									<input class="form-control" value="{{ ($location != '')? $location->name:'' }}" readonly>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-form-label col-md-3 col-sm-3  label-align">Address<span
	                          class="required">*</span></label>
								<div class="col-md-6 col-sm-6 col-xs-6">
									<input class="form-control" value="{{ $user->address }}" readonly>
								</div>
							</div>
						@endif
						<div class="form-group row">
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
						</div>
						<div class="ln_solid"></div>
					</form>
			</div>
			</div>
		</div>
	</div>
</div>
</div>
<!-- /page content -->
@endsection