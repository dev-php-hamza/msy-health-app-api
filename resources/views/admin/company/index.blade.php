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
					<h2>All Companies</h2>
					<a class="btn add_btn" href="{{ route('companies.create') }}">Add</a>
					<!-- Button trigger modal -->
					<!-- <button type="button" class="btn add_btn" data-toggle="modal" data-target="#importModal">
					  Import
					</button> -->
				</div>

				<!-- Modal -->
				<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				  <div class="modal-dialog" role="document">
				    <div class="modal-content">
				      <div class="modal-header">
				        <h5 class="modal-title" id="exampleModalLabel">Import Companies</h5>
				        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				          <span aria-hidden="true">&times;</span>
				        </button>
				      </div>
				      <div class="modal-body">
				        <form class="form-horizontal form-label-left" action="{{ route('companies.import.save') }}" method="post" id="importForm" enctype="multipart/form-data">
				        	@csrf
							<div class="form-group row">
								<label class="col-form-label col-md-3 col-sm-3  label-align">Name<span
	                          class="required">*</span></label>
								<div class="col-md-6 col-sm-6 col-xs-6">
									<input type="file" class="form-control" name="importFile" required>
								</div>
							</div>
				        </form>
				      </div>
				      <div class="modal-footer">
				        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				        <button type="button" class="btn btn-primary" id="btnImport">Import Companies</button>
				      </div>
				    </div>
				  </div>
				</div>
				<div class="x_content">
					<div class="table-responsive">
						<table
							class="table table-striped jambo_table bulk_action">
							<thead>
								<tr class="headings">
									
									<th class="column-title">Name</th>
									<th class="column-title">Phone</th>
									<th class="column-title">Key Contact</th>
									<th class="column-title">Address</th>
									<th class="column-title">Country</th>
									<th class="column-title no-link last">
										<span class="nobr">Action</span>
									</th>
								</tr>
							</thead>
                            
							<tbody>
								@forelse($companies as $company)
								<tr class="even pointer">
									<td>{{ $company->name }}</td>
									<td>{{ $company->phone }}</td>
									<td>{{ $company->key_contact_email }}</td>
									<td>{{ $company->address }}</td>
									<td>{{ $company->country->name }}</td>
									<td class="last">
										
										<form action="{{ route('companies.destroy',$company->id) }}" method="POST">
											@csrf
											@method('DELETE')
											<a class="btn tableButtons view" href="{{ route('companies.show',$company->id) }}"><i class="fa fa-info-circle"></i></a>
                                            <a class="btn tableButtons edit" href="{{ route('companies.edit',$company->id) }}"><i class="fa fa-edit"></i></a>
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
							{{ $companies->appends(\Request::except('_token'))->render() }}
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
		window.location.href = base_url+'/admin/companies/search?term='+term;
	}
	$(document).ready(function(){
	  $("#btnImport").click(function(){
	    $("#importForm").submit();
	  });
	});
</script>
@endsection