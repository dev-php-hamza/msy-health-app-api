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
					<h2>Create Questions For User Check Ins</h2>
				</div>

				<div class="x_content">
					<div class="dup-wrapper">
						<form class="form-horizontal form-label-left" method="post" action="{{ route('questions.store') }}">
						<div class="row-duplicate">
							@csrf
							<div class="d-inputs">
								<div class="form-group row">
									<label class="col-form-label col-md-3 col-sm-3 label-align">Language<span
								class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-6">
										<select class="form-control" name="lang" required>
											<option value="en">English</option>
											<option value="es">Spanish</option>
										</select>
									</div>
						        </div>
								<div class="form-group row">
									<label class="col-form-label col-md-3 col-sm-3 label-align">Title<span
								class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-6">
										<input type="text" class="form-control @error('name') is-invalid @enderror" name="title" placeholder="Enter question Title" required>
									</div>	
								</div>
							</div>
						</div>
						<div class="ln_solid"></div>
						<div class="form-group row">
							<div class="col-md-9 offset-md-3">
								<input type="submit" value="submit" class="btn btn-success">
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