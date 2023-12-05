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
				@if(count($errors) > 0)
				<ul>
					@foreach($errors->all() as $error)
					<li class="alert alert-danger">{{$error}}</li>
					@endforeach
				</ul>
				@endif
				<div class="x_title d-flex justify-content-between">
					<h2>Create Resource</h2>
				</div>
				<div class="x_content">
					<form class="form-horizontal form-label-left" method="post" action="{{ route('resources.store') }}" enctype="multipart/form-data">
						@csrf	
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
							<label class="col-form-label col-md-3 col-sm-3  label-align">Title<span
							class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input type="text" class="form-control @error('title') is-invalid @enderror" name="title" placeholder="Enter title" value="{{ old('title') }}" required>
								@error('title')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Url<span
							class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input type="url" class="form-control @error('url') is-invalid @enderror" name="url" placeholder="https://beta-massyhealth.simplyintense.com/" value="{{ old('url') }}" required>
								@error('url')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Is For Massy Employee<span
							class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<select class="form-control" name="for_employee" required>
									<option value="no">No</option>
									<option value="yes">Yes</option>
									<option value="both">Both</option>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Description<span
								class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<textarea class="form-control" id="description" name="description" rows="5" placeholder="Description should be 240 Character " onkeyup="handleReverseCount(this)" maxlength="240">{{ old('description') }}</textarea>
								<div class="d-flex justify-content-end mt-2">
									<span class="badge badge-pill badge-primary reverse-count" id="count">0/240</span>
								</div>
								<div class="warning-limit mt-1">
									<ul class="pl-0">
										<li class="alert alert-danger">Maximum Character Limit is reached, You can't enter more than 240 Characters!</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Please Choose Icon</label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input type="file" class="form-control" name="icon_file">
							</div>
						</div>		
						<!-- accordian -->
				<div class="accordion" id="newAccordian">
					<div class="card z-depth-0 bordered">
						<div class="card-header" id="headingOne">
							<h5 class="mb-0">
								<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne"
								aria-expanded="true" aria-controls="collapseOne">
								Next >
							    </button>
						    </h5>
					    </div>
						<div id="collapseOne" class="collapse" aria-labelledby="headingOne"
							data-parent="#newAccordian">
							<label class="mt-4 ml-4">
							    <input type="checkbox" class="checkAll_inputs" name="check_all" value="1">
							    Check All
					        </label>
							<div class="card-body">
								@foreach($countriesAll as $country)
								<div class="accordion" id="accordionExample">
									<div class="card">
										<div class="card-header" id="headingOne">
											<h2 class="mb-0">
												<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#{{$country->territory_code}}" aria-expanded="true" aria-controls="collapseOne">
													{{$country->name}}
												</button>
											</h2>
										</div>	
									</div>
								</div>

								<div id="{{$country->territory_code}}" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
									<input type="checkbox" name="companyIds[]" value="{{$country->id.':0'}}" class="checkAll" id="checkAll">Choose Country
									@foreach($country->companies as $company)
									<div class="checkbox card-body">
										<input type="checkbox" name="companyIds[]" value="{{$country->id.':'.$company->id}}" id="checkItem">{{$company->name}}
									</div>
									@endforeach
								</div>
								@endforeach
							</div>
						</div>
					</div>
				</div>
				<!-- end accordian -->
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
s<!-- /page content -->


@endsection

@section('scripts')
<script type="text/javascript">
	function handleReverseCount(elem) {
		let content = elem.value;
		let contentLength = content.length;
		if (contentLength <= 240) {
			$('.warning-limit').css('display','none');
			$('#count').text(contentLength+'/240');
		}

		if (contentLength == 240) {
			$('.warning-limit').css('display','flex');
		}
	}
</script>
<script type="text/javascript">
	$('.checkAll').change(function () { 
		$(this).siblings('.checkbox').find('input:checkbox').prop('checked', this.checked)    
	});
	$('.checkAll_inputs').change(function () { 
		$(this).parent().parent().find('input:checkbox').prop('checked', this.checked)    
	});
</script>

@endsection