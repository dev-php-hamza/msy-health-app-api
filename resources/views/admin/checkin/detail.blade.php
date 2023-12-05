@extends('admin.dashboard')

@section('content')
<!-- page content -->
<div class="right_col" role="main">
	<div class="">
		<div class="col-md-12 col-sm-12">
			<div class="x_panel">
				<div class="x_title d-flex justify-content-between">
					<h2>user</h2>
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
									 <label class="col-form-label col-md-3 col-sm-3  label-align">Employee<span
									    class="required">*</span></label>
									    <div class="col-md-6 col-sm-6 col-xs-6">
										<input class="form-control" value="{{($user->is_employee)?'Yes':'No'}}" readonly>
									</div>
								</div>
								<div class="form-group row">
								<label class="col-form-label col-md-3 col-sm-3  label-align">City<span
									class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-6">
										<input class="form-control" value="{{ $user->city }}" readonly>
									</div>
								</div>
								<div class="form-group row">
								<label class="col-form-label col-md-3 col-sm-3  label-align">Address<span
									class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-6">
										<input class="form-control" value="{{ $user->address }}" readonly>
									</div>
								</div>

								<div class="ln_solid"></div>
								<div class="row">
									<div class="col-md-6">
										<div class="card">
											<div class="card-body">
										<div class="x_title d-flex justify-content-between">
											<h2>user info</h2>
										</div>
									    <div class="form-group row">
											<label class="col-form-label col-md-3 col-sm-3  label-align">User name<span
											    class="required">*</span></label>
											<div class="col-md-6 col-sm-6 col-xs-6">
												<input class="form-control" value="{{ $user->name }}" readonly>
											</div>
									    </div>
									    <div class="form-group row">
											<label class="col-form-label col-md-3 col-sm-3  label-align">User Email<span
											    class="required">*</span></label>
											<div class="col-md-6 col-sm-6 col-xs-6">
												<input class="form-control" value="{{ $user->email }}" readonly>
											</div>
									    </div>
									    <div class="form-group row">
									    <label class="col-form-label col-md-3 col-sm-3  label-align">Phone<span
									    	class="required">*</span></label>
									    	<div class="col-md-6 col-sm-6 col-xs-6">
									    		<input class="form-control" value="{{ $userInfo->phone }}" readonly>
									    	</div>
									    </div>
									    <div class="form-group row">
									    <label class="col-form-label col-md-3 col-sm-3  label-align">Employee Number<span
									    	class="required">*</span></label>
									    	<div class="col-md-6 col-sm-6 col-xs-6">
									    		<input class="form-control" value="{{ $userInfo->employee_number }}" readonly>
									    	</div>
									    </div>
									    <div class="form-group row">
											<label class="col-form-label col-md-3 col-sm-3  label-align">Company<span
											    class="required">*</span></label>
											<div class="col-md-6 col-sm-6 col-xs-6">
												<input class="form-control" value="{{ isset($userInfo->company->name)?$userInfo->company->name:'' }}" readonly>
											</div>
									    </div>
									    <div class="form-group row">
											<label class="col-form-label col-md-3 col-sm-3  label-align">Department<span
											    class="required">*</span></label>
											<div class="col-md-6 col-sm-6 col-xs-6">
												<input class="form-control" value="{{ isset($userInfo->department->name )?$userInfo->department->name :''}}" readonly>
											</div>
									    </div>
									</div>
									</div>
									</div>
									<div class="col-md-6">
										<div class="card">
											<div class="card-body">
											    <div class="x_title d-flex justify-content-between">
													<h2>Checkin Detail</h2>
												</div>
										   		<label class="col-form-label col-md-3 col-sm-3  label-align"><strong>Questions</strong><span
									   		    class="required"></span></label><br><br>
									   		    <ol>
									   		    @foreach($checkinsQ as $question)
                                                	<li class="mb-3">{{$question->title}}<div><span class="{{ ($question->pivot->option == 1)? 'optionY':'' }}">Yes</span> <span class="{{ ($question->pivot->option == 0)? 'optionN':'' }}">No</span></div> </li>
									   		    @endforeach
									   		    @if(!is_null($checkin->s1_question))
									   		    <li class="mb-3">
									   		    	What can we help you with today?
									   		    	<div>
									   		    		<span class="{{($checkin->s1_question)?'optionY':'' }}">{{$checkin->s1_question }}</span>
									   		    	</div>
									   		    </li>
									   		    @endif
									   		    @if(!is_null($checkin->additional_help))
									   		    <li class="mb-3">
									   		    	Do you want someone to contact you?
									   		    	<div>
									   		    		<span class="{{ ($checkin->additional_help == 1)?'optionY':'' }}">Yes</span>
									   		    		<span class="{{ ($checkin->additional_help == 0)?'optionN':'' }}">No</span>
									   		    	</div>
									   		    </li>
									   		    @endif
									   		    </ol>
									   		    <label class="col-form-label col-md-6 col-sm-6"><strong>Additional Feedback</strong><span
									   		    class="required"></span></label><br>
									   		    <dir class="col-md-12">
									   		    	<textarea rows="5" cols="100" class="col-md-12" readonly>{{ $checkin->additional_feedback }}</textarea>
									   		    </dir>
									   		    
									       </div>
									    </div>
									</div>
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