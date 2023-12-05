@extends('admin.dashboard')

@section('content')
<!-- page content -->
<div class="right_col" role="main">
	<div class="row">
		<div class="col-md-12 col-sm-12">
			<div class="x_panel border-none">
				<div class="x_title d-flex justify-content-between">
					<h2>Detail Question</h2>
				</div>
				<div class="x_content">
					<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Language</label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<select class="form-control">
									<option>{{ ($question->lang == 'en')?'English':'Spanish' }}</option>
								</select>
							</div>
					   </div>
					<div class="form-group row">
						<label class="col-form-label col-md-3 col-sm-3  label-align">Title</label>
						<div class="col-md-6 col-sm-6 col-xs-6">
							<input type="text" class="form-control" value="{{ $question->title }}" readonly>
						</div>
					</div>
				</div>
                
            </div>

		</div>
	</div>
</div>
<!-- /page content -->


@endsection