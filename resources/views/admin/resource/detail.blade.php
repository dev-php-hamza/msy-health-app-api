@extends('admin.dashboard')

@section('content')
<!-- page content -->
<div class="right_col" role="main">
	<div class="">
		<div class="col-md-12 col-sm-12">
			<div class="x_panel">
				<div class="x_title d-flex justify-content-between">
					<h2>Detail Resource</h2>
				</div>
				<div class="x_content">
				<br />
					<form class="form-horizontal form-label-left">
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Language</label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<select class="form-control">
									<option>{{ ($resource->lang == 'en')?'English':'Spanish' }}</option>
								</select>
							</div>
					   </div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Title<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input class="form-control" value="{{ $resource->title }}" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Url<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input type="url" class="form-control @error('url') is-invalid @enderror" name="url" placeholder="Enter url" value="{{ $resource->url }}" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Is For Massy Employee<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input class="form-control" value="{{ ucfirst($resource->for_employee) }}" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Description<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<textarea class="form-control" rows="5" readonly>{{ $resource->description }}</textarea>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Image<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								@if($resource->icon)
									<img src="{{asset('assets/resources/'.$resource->icon)}}" alt="resourceImg" width="100" height="100" class="mb-3 mt-2">
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

								@foreach(\App\Models\CompanyResource::where('country_resource_id', $country['country']['pivot_id'])->pluck('company_id') as $compId)

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