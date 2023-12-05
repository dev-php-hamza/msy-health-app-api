@extends('admin.dashboard')

@section('content')
<!-- page content -->
<div class="right_col" role="main">
	<div class="">
		<div class="col-md-12 col-sm-12">
			<div class="x_panel">
				<div class="x_title d-flex justify-content-between">
					<h2>Detail Location</h2>
				</div>
				<div class="x_content">
					<br />
					<form class="form-horizontal form-label-left">

						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Name<span
								class="required">*</span></label>
								<div class="col-md-6 col-sm-6 col-xs-6">
									<input class="form-control" value="{{ $location->name }}" readonly>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-form-label col-md-3 col-sm-3  label-align">Country<span
									class="required">*</span></label>
									<div class="col-md-6 col-sm-6 col-xs-6">
										<input class="form-control" value="{{ $location->country->name }}" readonly>
									</div>
								</div>
								<div class="ln_solid"></div>
								<label><strong>Health Centers</strong></label>
								<div class="form-group row d-flex">
									@foreach($healthCenters as $healthCenter)
										<div class="col-md-3 col-sm-3 col-xs-3 d-flex">
											<div>
												@if($healthCenter->image)
													<img src="{{asset('assets/healthcenter/'.$healthCenter->image)}}" alt="healthCenterImg" width="35" height="35" class="mb-3 mt-1">
												@else
													<img src="{{asset('assets/images/no-image.png') }}" alt="profile image" style="height: 35;width: 35px">
												@endif
											</div>
											<div style="margin-left: 10px; margin-top: 12px">
												<a href="{{ route('health-centers.show',$healthCenter->id) }}">{{ $healthCenter->name }}</a>
											</div>
										</div>
									@endforeach
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