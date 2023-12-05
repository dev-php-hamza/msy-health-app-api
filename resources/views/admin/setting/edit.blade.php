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
					<h2>App Settings</h2>
				</div>
				<div class="x_content">
					<form class="form-horizontal form-label-left" method="post" action="{{ route('settings.update',$setting->id) }}">
						@csrf
						@method('PUT')
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3 label-align">Language<span
						class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<select class="form-control" name="lang" required>
									<option value="en" {{($setting->lang == 'en')?'selected':''}}>English</option>
									<option value="es" {{($setting->lang == 'es')?'selected':''}}>Spanish</option>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Intro Text<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<textarea class="form-control" name="intro_text" rows="2" placeholder="Please Add App Intro Text..." required>{{ isset($setting->intro_text)?old('intro_text',$setting->intro_text):''}}</textarea>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Allowed Domains<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input type="text" class="form-control @error('allowed_domains') is-invalid @enderror" name="allowed_domains" placeholder="Enter Allowed Domains" value="{{ isset($setting->allowed_domains)?old('allowed_domains',$setting->allowed_domains ):'' }}" required>
								@error('allowed_domains')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
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
