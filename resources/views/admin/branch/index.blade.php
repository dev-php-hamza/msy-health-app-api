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
				<div class="x_title d-flex justify-content-between">
					<h2>All Branches</h2>
					<a class="btn add_btn" href="{{ route('branches.create') }}">Add</a>
				</div>

				<div class="x_content">
					<div class="table-responsive">
						 <table
							class="table table-striped jambo_table bulk_action">
							<thead>
								<tr class="headings">
									
									<th class="column-title">Name</th>
									<th class="column-title">Key Contact</th>
									<th class="column-title">Phone</th>
									<th class="column-title">Company</th>
									<th class="column-title">Address</th>
									<th class="column-title no-link last">
										<span class="nobr">Action</span>
									</th>
								</tr>
							</thead>
                            
						    <tbody>
								@forelse($branches as $branch)
									<tr class="even pointer">
										<td>{{ $branch->name }}</td>
										<td>{{ $branch->key_contact_email }}</td>
										<td>{{ $branch->phone }}</td>
										<td>{{ $branch->company->name }}</td>
										<td>{{ $branch ->address }}</td>
										<td class="last">
											
											<form action="{{ route('branches.destroy',$branch->id) }}" method="POST">
												@csrf
												@method('DELETE')
												<a class="btn tableButtons view" href="{{ route('branches.show',$branch->id) }}"><i class="fa fa-info-circle"></i></a>
	                                            <a class="btn tableButtons edit" href="{{ route('branches.edit',$branch->id) }}"><i class="fa fa-edit"></i></a>
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
							{{ $branches->appends(\Request::except('_token'))->render() }}
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
			window.location.href = base_url+'/admin/branches/search?term='+term;
		}
	}
</script>
@endsection