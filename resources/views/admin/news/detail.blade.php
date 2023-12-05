@extends('admin.dashboard')

@section('content')
<!-- page content -->
<div class="right_col" role="main">
	<div class="">
		<div class="col-md-12 col-sm-12">
			<div class="x_panel">
				<div class="x_title d-flex justify-content-between">
					<h2>Detail News</h2>
				</div>
				<div class="x_content">
				<br />
					<form class="form-horizontal form-label-left">
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Language</label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<select class="form-control" readonly>
									<option>{{ ($news->lang == 'en')?'English':'Spanish' }}</option>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Is For Massy Employee<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input class="form-control" value="{{ ucwords($news->for_employee) }}" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Title<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input class="form-control" value="{{ $news->title }}" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Url</label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input class="form-control" value="{{ $news->external_url }}" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Snapshot Text<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<textarea class="form-control" rows="3" cols="3" readonly>{{ $news->snapshot }}</textarea>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Description<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<textarea class="form-control" id="description" rows="10" cols="80" readonly>{{ $news->description }}</textarea>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Embebed Video<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<textarea class="form-control" rows="4" cols="80" readonly>{{ $news->embeded_video }}</textarea>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Image<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								@if($news->image)
									<img src="{{asset('assets/news/'.$news->image)}}" alt="newsImg" width="100" height="100" class="mb-3 mt-2">
								@else
									<img src="{{asset('assets/images/no-image.png') }}" alt="profile image" width="100" height="100" class="mb-3 mt-2">
								@endif
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Banner Image<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								@if($news->banner_image)
									<img src="{{asset('assets/news/'.$news->banner_image)}}" alt="newsImg" width="100" height="100" class="mb-3 mt-2">
								@else
									<img src="{{asset('assets/images/no-image.png') }}" alt="profile image" width="100" height="100" class="mb-3 mt-2">
								@endif
							</div>
						</div>
						<div class="ln_solid"></div>
						<div class="card">
							<div class="card-header">
								<h2>Associated Countries and Companies</h2>
							</div>
						     <div class="card-body">
							@foreach($data as $country)
								<h2>- {{$country['country']['name']}}</h2>

								@foreach(\App\Models\CompanyNews::where('country_news_id', $country['country']['pivot_id'])->pluck('company_id') as $compId)

									@foreach(\App\Models\Company::where('id', $compId)->get() as $company)
									  <p>- - {{$company->name}} </p>
									@endforeach

								@endforeach
						    @endforeach
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

@section('scripts')

<script>
    ClassicEditor
        .create( document.querySelector( '#description' ) )
        .catch( error => {
            console.error( error );
        } );
</script>

@endsection