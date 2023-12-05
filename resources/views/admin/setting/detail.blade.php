@extends('admin.dashboard')

@section('content')
<!-- page content -->
<div class="right_col" role="main">
	<div class="">
		<div class="col-md-12 col-sm-12">
			<div class="x_panel">
				<div class="x_title d-flex justify-content-between">
					<h2>Detail App Setting</h2>
				</div>
				<div class="x_content">
				<br />
					<form class="form-horizontal form-label-left">
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Language</label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<select class="form-control">
									<option>{{ ($appSetting->lang == 'en')?'English':'Spanish' }}</option>
								</select>
							</div>
					   </div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">App Intro Text</label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<textarea class="form-control" readonly>{{ $appSetting->intro_text }}</textarea>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Allowed Domains</label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<textarea class="form-control" readonly>{{ $appSetting->allowed_domains }}</textarea>
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