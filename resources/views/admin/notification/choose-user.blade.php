@extends('admin.dashboard')

@section('content')
<!-- page content -->
<div class="right_col" role="main">
	<div class="">
		<div class="col-md-12 col-sm-12">
			<div class="x_panel">
				@if(count($errors) > 0)
				  <ul>
				    @foreach($errors->all() as $error)
				      <li class="alert alert-danger">{{$error}}</li>
				    @endforeach
				  </ul>
				@endif
				<div class="x_title d-flex justify-content-between">
					<h2>Choose User</h2>
				</div>
				<div class="x_content">
					<br />
						<form class="form-horizontal form-label-left">
							<div class="form-group row">
								<label class="col-form-label col-md-3 col-sm-3  label-align">Country<span
	                          class="required">*</span></label>
								<div class="col-md-6 col-sm-6 col-xs-6">
									<input class="form-control" value="{{ $notification->country->name }}" readonly>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-form-label col-md-3 col-sm-3  label-align">Title<span
	                          class="required">*</span></label>
								<div class="col-md-6 col-sm-6 col-xs-6">
									<input class="form-control" value="{{ $notification->title }}" readonly>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-form-label col-md-3 col-sm-3  label-align">Description<span
	                          class="required">*</span></label>
								<div class="col-md-6 col-sm-6 col-xs-6">
									<textarea class="form-control" rows="2" readonly>{{ $notification->description }}</textarea>
								</div>
							</div>
							<div class="ln_solid"></div>
						</form>
						<div class="row">
							<div class="col-md-6">
								<label for="search_filter" class="mb-0">Please choose</label>
							</div>
							<div class="col-md-6">
								<div id="toggles" style="margin-top: 0px !important;">
									<input type="checkbox" name="filters" onchange="handleNotificationUser(this)" id="filter" class="ios-toggle" {{(isset($userData))?'unchecked':'checked'}}/>
									<label for="filter" class="checkbox-label" data-off="Custom" data-on="All Selected"></label>
								</div>
							</div>
						</div>
						<hr class="mb-4">
						<div id="filter-container" style="visibility: {{(isset($userData))?'visible':'hidden'}}">
							@if(isset($userData))
							<form class="form-horizontal" action="{{ route('notifications_users_country_term') }}" method="post">
								@csrf
								<div class="row">
									<div class="form-group">
										<div class="p-1">
											<input type="text" class="form-control" name="name" placeholder="Name Search..." value="{{ request('name')}}">
										</div>
									</div>
									<div class="form-group">
										<div class="p-1">
											<input type="text" name="email" class="form-control" placeholder="Email Search..." value="{{ request('email')}}">
										</div>
									</div>
									<div class="form-group">
										<div class="p-1">
											<input type="text" name="phone" class="form-control" placeholder="Phone Search..." value="{{ request('phone')}}">
										</div>
									</div>
									<input type="hidden" name="notification_id" id="notificationId" value="{{$notification->id}}">
									<div class="form-group">
										<div class="p-1">
											<button type="submit" class="btn btn-primary">Search</button>
										</div>
									</div>
								</div>
							</form>
							<hr class="mb-4">
							@endif
						</div>
						<form class="form-horizontal" action="{{ route('notifications.save.users')}}" method="post">
							@csrf
							<div class="form-group" id="countryUsers">
								@if(isset($userData))
									<div id="customSearchData">
										<table>
											<tr>
												<th>Name</th>
												<th>Email</th>
												<th>Phone</th>
											</tr>
											<tbody>
												@forelse($userData['users'] as $key => $user)
													<tr>
														<td> 
															<input type="checkbox" id="userIds" name="user[]" value="{{$user->id}}"> {{ ucwords($user->name) }}
														</td>
														<td>{{ $user->email }}</td>
														<td>{{ $user->userInfo->phone }}</td>
													</tr>
												@empty
													<tr><td>No Record found!</td></tr>
												@endforelse
											</tbody>
										</table>
									</div>
								@else
									<div class="col-md-12" style="justify-content: center;" id="usersCount">
										<strong> Total {{ $usersCount }} users will receive this notification</strong>
										<input type="hidden" id="userIds" name="user[]" value="all">
									</div>
								@endif
								<input type="hidden" name="notification_id" value="{{$notification->id}}">
							</div>
							<div class="form-group"> 
							  <div class="col-sm-offset-2 col-sm-10">
							    <button type="submit" class="btn btn-primary">Save</button>
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
  <script src="{{ asset('js/locations.js') }}"></script>
  <script>
  	function handleNotificationUser(elem){
  		if ($('#filter').prop('checked')) {
  			$('#customSearchData').empty();
  			$('#userIds').val(['all']);
  			$('#filter-container').empty();
  			/*Add div for usersCount into countryUsers div*/
  			let userCoutDiv = '<div class="col-md-12" style="justify-content: center;" id="usersCount"><strong> Total {{ $usersCount }} users will receive this notification</strong><input type="hidden" id="userIds" name="user[]" value="all"></div>';
  			$('#countryUsers').append(userCoutDiv);

  		}else{
  			$('#usersCount').remove();
  			$('#filter-container').css('visibility', 'visible');
  			let customSearchData = `<form class="form-horizontal" action="{{ route('notifications_users_country_term') }}" method="post">@csrf<div class="row"><div class="form-group"><div class="p-1"><input type="text" class="form-control" name="name" placeholder="Name Search..."></div></div><div class="form-group"><div class="p-1"><input type="text" name="email" class="form-control" placeholder="Email Search..."></div></div><div class="form-group"><div class="p-1"><input type="text" name="phone" class="form-control" placeholder="Phone Search..."></div></div><input type="hidden" name="notification_id" id="notificationId" value="{{$notification->id}}"><div class="form-group"> <div class="p-1"><button type="submit" class="btn btn-primary">Search</button></div></div></div></form><hr class="mb-4">`;
  			$('#filter-container').append(customSearchData);
  		}
  	}
  </script>
@endsection