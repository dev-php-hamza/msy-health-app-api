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
					<h2>Update News</h2>
				</div>
				<div class="x_content">
					<form class="form-horizontal form-label-left" method="post" action="{{ route('news.update',$news->id) }}" enctype="multipart/form-data">
						@csrf
						@method('PUT')
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3 label-align">Language<span
						class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<select class="form-control" name="lang" required>
									<option value="en" {{($news->lang == 'en')?'selected':''}}>English</option>
									<option value="es" {{($news->lang == 'es')?'selected':''}}>Spanish</option>
								</select>
							</div>
				        </div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Is For Massy Employee<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<select class="form-control" name="for_employee" required>
									<option value="no" {{ ($news->for_employee == 'no')?'selected':'' }}>No</option>
									<option value="yes" {{ ($news->for_employee == 'yes')?'selected':'' }}>Yes</option>
									<option value="both" {{ ($news->for_employee == 'both')?'selected':'' }}>Both</option>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Title<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input type="text" class="form-control @error('title') is-invalid @enderror" name="title" placeholder="Enter title" value="{{ old('title',$news->title ) }}" required>
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
								<input type="text" class="form-control @error('external_url') is-invalid @enderror" name="external_url" placeholder="Enter External URL" value="{{ old('external_url',$news->external_url ) }}">
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
								<textarea class="form-control" name="snapshot" cols="3" rows="3" placeholder="Enter snapshot text...">{{ old('snapshot', $news->snapshot) }}</textarea>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Description<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<textarea class="form-control" name="description" id="description" placeholder="description..." required>{{ old('description',$news->description)}}</textarea>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Embebed Video</label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<textarea class="form-control" name="embeded_video" placeholder="Embeded Video">{{ old('embeded_video',$news->embeded_video)}}</textarea>
							</div>
						</div>
						<div class="form-group row justify-content-center">
                          <div class="col-md-6 col-sm-6 col-xs-6">
                            @if($news->image)
								<img src="{{asset('assets/news/'.$news->image)}}" alt="newsImg" width="100" height="100" class="mb-3 mt-2">
							@else
								<img src="{{asset('assets/images/no-image.png')}}" alt="not-found" width="100" height="100" class="mb-3 mt-2">
							@endif
                            </div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Please Choose Image<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input type="file" class="form-control" name="newsFile" value="{{ old('image') }}">
							</div>
						</div>
						<div class="form-group row justify-content-center">
                          <div class="col-md-6 col-sm-6 col-xs-6">
                            @if($news->banner_image)
								<img src="{{asset('assets/news/'.$news->banner_image)}}" alt="newsImg" width="100" height="100" class="mb-3 mt-2">
							@else
								<img src="{{asset('assets/images/no-image.png')}}" alt="not-found" width="100" height="100" class="mb-3 mt-2">
							@endif
                            </div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Please Choose Banner Image<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input type="file" class="form-control" name="banner_file" value="{{ old('banner_file') }}">
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
							    <input type="checkbox" class="checkAll_inputs" {{($news->check_all == 1)? 'checked': ''}}>
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
									<input type="checkbox" name="companyIds[]" value="{{$country->id.':0'}}" class="checkAll" id="checkAll" {{ in_array($country->id,$countryNewsIds)? 'checked' : '' }}> Choose Country
									@foreach($country->companies as $company)
									<div class="checkbox card-body">
										<input type="checkbox" name="companyIds[]" value="{{$country->id.':'.$company->id}}" id="checkItem" {{ in_array($company->id, $companyNewsIds)? 'checked' : '' }}> {{$company->name}}
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
	  $('.checkAll').change(function () { 
	  $(this).siblings('.checkbox').find('input:checkbox').prop('checked', this.checked)    
});
  $('.checkAll_inputs').change(function () { 
	$(this).parent().parent().find('input:checkbox').prop('checked', this.checked)    
});
</script>
@endsection