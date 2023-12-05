@extends('admin.dashboard')

@section('content')
<!-- page content -->
<div class="right_col" role="main">
	<div class="">
		<div class="col-md-12 col-sm-12">
			<div class="x_panel">
				<div class="x_title d-flex justify-content-between">
					<h2>Detail Notification</h2>
				</div>
				<div class="x_content">
				<br />
					<form class="form-horizontal form-label-left">
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Title<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input class="form-control" value="{{ $notification->title }}" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Completed<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input class="form-control" value="{{ $notification->is_completed }}" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Country<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input class="form-control" value="{{ $country }}" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Description<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<textarea class="form-control" rows="2">{{ $notification->description }}</textarea>
							</div>
						</div>
						<div class="ln_solid"></div>
						<label><strong>Attached Users</strong></label>
						<div class="form-group row d-flex">
						  	@if($users)
						  		<div class="col-md-12" style="justify-content: center;" id="usersCount">
						  			<strong>Notification created for Total {{ $users }} users</strong>
						  		</div>
						  	@endif
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