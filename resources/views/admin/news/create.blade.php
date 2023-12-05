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
					<h2>Create News</h2>
				</div>
				<div class="x_content">
					<form class="form-horizontal form-label-left" method="post" action="{{ route('news.store') }}" enctype="multipart/form-data">
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
							<label class="col-form-label col-md-3 col-sm-3  label-align">Url</label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input type="url" class="form-control @error('external_url') is-invalid @enderror" name="external_url" placeholder="Enter External URL" value="{{ old('external_url') }}">
								@error('external_url')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Snapshot Text<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<textarea class="form-control" name="snapshot" cols="3" rows="3" placeholder="Enter snapshot text...">{{ old('snapshot') }}</textarea>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Description<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<textarea class="form-control" id="description" name="description" cols="80" rows="10" placeholder="Description...">{{ old('description') }}</textarea>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Embeded Video</label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<textarea class="form-control" name="embeded_video" cols="80" rows="4" placeholder="Embeded Video">{{ old('embeded_video') }}</textarea>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Please Choose Image<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input type="file" class="form-control" name="newsFile" required>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Please Choose Banner Image<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input type="file" class="form-control" name="banner_file" required>
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
								@foreach($countries as $country)
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
									<input type="checkbox" name="companyIds[]" value="{{$country->id.':0'}}" class="checkAll" id="checkAll"> Check All
									@foreach($country->companies as $company)
									<div class="checkbox card-body">
										<input type="checkbox" name="companyIds[]" value="{{$country->id.':'.$company->id}}" id="checkItem"> {{$company->name}}
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
<!-- /page content -->


@endsection


@section('scripts')

<script>
    ClassicEditor
        .create( document.querySelector( '#description' ), {

	        // Configure the endpoint. See the "Configuration" section above.
	        cloudServices: {
	            tokenUrl: 'https://78978.cke-cs.com/token/dev/e040f193e6e81c79d054af4ca2d0dca5183516a6dd92e4ede7d6b81e5f3f',
	            uploadUrl: 'https://78978.cke-cs.com/easyimage/upload/'
	        }
	    } )
	    .catch( error => {
	    	console.error( error );
	    } );
</script>
<script type="text/javascript">
	$('.demo').dropdown({
		multipleMode: 'label'
	});
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