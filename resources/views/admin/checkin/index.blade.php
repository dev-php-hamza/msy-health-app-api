@extends('admin.dashboard')

@section('content')
<!-- page content -->
<div class="right_col" role="main">
	<div class="">
		 <div class="row justify-content-end mr-0">
	        <div class="col-md-3 col-sm-5 form-group top_search">
	          {{--<div class="input-group SearchBar">
	            <input type="text" class="form-control" placeholder="Search for..." id="term">
	            <span class="input-group-btn">
	              <button class="btn btn-default searchBtn" type="button" onclick="getSearchResult(this)">Go!</button>
	            </span>
	          </div>
	        </div>--}}
	      </div>
		<div class="col-md-12 col-sm-12">
			<div class="x_panel">
				<div class="x_title d-flex justify-content-between">
					<h2>All Checkins</h2>
					{{--<a class="btn add_btn" href="{{ route('checkins.create') }}">Add</a>--}}
				</div>

				<div class="x_content">
					<div class="table-responsive">
						<table
							class="table table-striped jambo_table bulk_action">
							<thead>
								<tr class="headings">
									
									<th class="column-title">User Name</th>
									<th class="column-title">Email</th>
									<th class="column-title">Employee</th>
									<th class="column-title">Company</th>
									<th class="column-title">Department</th>
									<th class="column-title">Created At</th>
									<th class="column-title no-link last">
										<span class="nobr">Action</span>
									</th>
								</tr>
							</thead>
                            
							<tbody>
								@forelse($checkins as $checkin)
									<tr class="even pointer">
										<td>{{ $checkin->user->name }}</td>
										<td>{{ $checkin->user->email }}</td>
										<td><i class="fa fa-{{($checkin->user->is_employee)?'check':'times'}}"></i></td>
										@if($checkin->user->is_employee == 0)
										<td> </td>
										<td> </td>
										@else
	                                    <td>{{ isset($checkin->user->userInfo->company)?$checkin->user->userInfo->company->name :''}}</td>
										<td>{{ isset($checkin->user->userInfo->department)?$checkin->user->userInfo->department->name:'' }}</td>
										@endif
										<td>{{ $checkin->created_at }}</td>
										<td class="last">
											<a class="btn tableButtons view" href="{{ route('checkins.show',$checkin->id) }}"><i class="fa fa-info-circle"></i></a>
										</td>
									</tr>
								@empty
									<tr><td>No Record Found!</td></tr>
								@endforelse
							</tbody>
						</table>
						<div class="pull-right ml-3">
							{{ $checkins->appends(\Request::except('_token'))->render() }}
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
