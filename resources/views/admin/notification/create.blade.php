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
					<h2>Create Notification</h2>
				</div>
				<div class="x_content">
					<form class="form-horizontal form-label-left" method="post" action="{{ route('notifications.store') }}" enctype="multipart/form-data">
						@csrf
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Country<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<select class="form-control" name="country_id" required>
									<option value="">Choose Country</option>
									@foreach($countries as $country)
										<option value="{{$country->id}}">{{$country->name}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Title<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<input type="text" class="form-control @error('title') is-invalid @enderror" name="title" placeholder="Enter title" value="{{ old('title') }}" data-emojiable="true" required>
								@error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-md-3 col-sm-3  label-align">Description<span
                          class="required">*</span></label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<textarea class="form-control" name="description" rows="2" placeholder="description..." data-emojiable="true">{{ old('description') }}</textarea>
							</div>
						</div>
						<div class="ln_solid"></div>
						<div class="form-group row">
							<div class="col-md-9 offset-md-3">
								
								<input type="submit" value="next" class="btn btn-success">
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
    $(function() {
      // Initializes and creates emoji set from sprite sheet
      window.emojiPicker = new EmojiPicker({
        emojiable_selector: '[data-emojiable=true]',
        assetsPath: "{{ asset('assets/plugins/emoji-picker/lib/img/') }}",
        popupButtonClasses: 'fa fa-smile-o'
      });
      // Finds all elements with `emojiable_selector` and converts them to rich emoji input fields
      // You may want to delay this step if you have dynamically created input fields that appear later in the loading process
      // It can be called as many times as necessary; previously converted input fields will not be converted again
      window.emojiPicker.discover();
    });
  </script>
@endsection