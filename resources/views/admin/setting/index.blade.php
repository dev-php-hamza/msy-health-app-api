@extends('admin.dashboard')

@section('content')
<!-- page content -->
<div class="right_col" role="main">
	<div class="">
		 <div class="row justify-content-end mr-0">
	        <div class="col-md-3 col-sm-5 form-group top_search">
	         <!--  <div class="input-group SearchBar">
	            <input type="text" class="form-control" placeholder="Search for..." id="term">
	            <span class="input-group-btn">
	              <button class="btn btn-default searchBtn" type="button" onclick="getSearchResult(this)">Go!</button>
	            </span>
	          </div> -->
	        </div>
	      </div>
		<div class="col-md-12 col-sm-12">
			<div class="x_panel">
				<div class="x_title d-flex justify-content-between">
					<h2>All App Settings</h2>
					<a class="btn add_btn" href="{{ route('settings.create') }}">Add</a>
				</div>

				<div class="x_content">
					<div class="table-responsive">
						<table
							class="table table-striped jambo_table bulk_action">
							<thead>
								<tr class="headings">
									<th class="column-title">App Intro. Text</th>
									<th class="column-title">Allowed Domains</th>
									<th class="column-title">Language</th>
									<th class="column-title no-link last" style="min-width: 200px;">
										<span class="nobr">Action</span>
									</th>
								</tr>
							</thead>
                            
							<tbody>
								@forelse($settings as $setting)
									<tr class="even pointer">
										<td>{{ $setting->intro_text }}</td>
										<td>
											@forelse($setting->AllowedDomains() as $domain)
												{{ $domain }} <br>
											@empty
												No Record Found!
											@endforelse
										</td>
										<td>{{ $setting->lang }}</td>
										<td class="last">
											
											<form action="{{ route('settings.destroy',$setting->id) }}" method="POST">
												@csrf
												@method('DELETE')
												<a class="btn tableButtons view" href="{{ route('settings.show',$setting->id) }}"><i class="fa fa-info-circle"></i></a>
												<a class="btn tableButtons edit" href="{{ route('settings.edit',$setting->id) }}"><i class="fa fa-edit"></i></a>
												<button type="submit" class="btn tableButtons trash"><i class="fa fa-trash"></i></button>
											</form>
										</td>
									</tr>
								@empty
									<tr><td>No Record Found!</td></tr>
								@endforelse
							</tbody>
						</table>
						<div class="pull-right ml-3">
							{{ $settings->appends(\Request::except('_token'))->render() }}
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
			window.location.href = base_url+'/admin/resources/search?term='+term;
		}
	}
</script>
@endsection