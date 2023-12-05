@extends('admin.dashboard')

@section('content')
<!-- page content -->
<div class="right_col" role="main">
	<div class="">
		<div class="col-md-12 col-sm-12">
			<div class="x_panel">
				<div class="x_title d-flex justify-content-between">
					<h2>Detail Department</h2>
				</div>
				<div class="x_content">
				<br />
					<form class="form-horizontal form-label-left">
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Name<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input class="form-control" value="{{ $department->name }}" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Master Email</label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input class="form-control" value="{{ $department->master_email }}" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Add multiple CC Email Addresses (Separate with comma)</label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input class="form-control" value="{{ $department->cc_email_addresses }}" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Country<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input class="form-control" value="{{ $department->company->country->name }}" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Company<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input class="form-control" value="{{ $department->company->name }}" readonly>
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