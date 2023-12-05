@extends('admin.dashboard')

@section('content')
<!-- page content -->
<div class="right_col" role="main">
	<div class="">
		 <div class="row justify-content-end mr-0">
            <div class="col-md-3 col-sm-5 form-group top_search">
              <div class="input-group SearchBar">
                <input type="text" class="form-control" placeholder="Search for..." id="term">
                <span class="input-group-btn">
                  <button class="btn btn-default searchBtn" type="button" onclick="getSearchResult(this)">Go!</button>
                </span>
              </div>
            </div>
          </div>
		<div class="col-md-12 col-sm-12">
			<div class="x_panel">
				<!-- <div class="x_title d-flex justify-content-between">
					<h2>All Countries</h2>
					<button class="btn add_btn" disabled>Add</button>
				</div> -->

				<div class="x_content">
					<div class="table-responsive">
						<table
							class="table table-striped jambo_table bulk_action">
							<thead>
								<tr class="headings">
									
									<th class="column-title">Name</th>
									<th class="column-title">Calling Code</th>
									<th class="column-title">No.Location</th>
									<th class="column-title">Switch</th>
									<th class="column-title no-link last">
										<span class="nobr">Action</span>
									</th>
								</tr>
							</thead>
                            
							<tbody>
								@forelse($countries as $country)
									<tr class="even pointer">
										<td>{{ $country->name }}</td>
										<td>{{ $country->calling_code }}</td>
										<td>{{ 0 }}</td>
										<td>
									  		<div id="toggles">
								  				<input type="checkbox" name="{{$country->territory_code}}_switch" onchange="handleClick(this)" id="{{$country->territory_code}}_switch" class="ios-toggle" 
								  				@if(isset($country->switch))
								  					{{($country->switch)?'checked':''}}
								  				@else
								  					disabled
								  				@endif
								  				/>
								  				<label for="{{$country->territory_code}}_switch" class="checkbox-label" data-off="Off" data-on="On"></label>
									  		</div>
										</td>
										<td class="last">
											
											<form action="{{-- route('countries.destroy',$country->id) --}}" method="POST">
												@csrf
												@method('DELETE')
												<a class="btn tableButtons view" href="{{ route('countries.show',$country->id) }}"><i class="fa fa-info-circle"></i></a>
                                            	<a class="btn tableButtons edit" href="{{ route('countries.edit',$country->id) }}"><i class="fa fa-edit"></i></a>
												<!-- <button type="submit" class="btn tableButtons trash" disabled><i class="fa fa-trash"></i></button> -->
											</form>
										</td>
									</tr>
								@empty
									<tr><td>No Record Found!</td></tr>
								@endforelse
							</tbody>
						</table>
						<div class="pull-right ml-3">
							{{ $countries->appends(\Request::except('_token'))->render() }}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<!-- /page content -->


@endsection

@section('scripts')
<script type="text/javascript">
	function getSearchResult(elem) {
		let base_url = window.location.origin;
		let term = $('#term').val();
		console.log(term)
		if (typeof term != "undefined" && term != '') {
			window.location.href = base_url+'/admin/countries/search?term='+term;
		}
	}
</script>
<script type="text/javascript">
	function handleClick(elem){
		let base_url = window.location.origin;
		console.log(base_url);
		let countryCode = elem.id;
		countryCode = countryCode.split('_');
		console.log(countryCode[0]);
		// return false;
		$.ajaxSetup({
		    headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    }
		});
		$.ajax({
			type: 'POST',
			url: base_url+'/admin/countries/status',
			data:{
				'territory_code':countryCode,
				},
			beforeSend: function(){
			},
			success: function(res){
				console.log(res);
			}
		});
	}
</script>
@endsection