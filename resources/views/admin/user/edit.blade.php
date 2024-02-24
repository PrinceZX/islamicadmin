@extends('layout.page-app')

@section('page_title',  __('label.edit_user'))

@section('content')

	@include('layout.sidebar')

	<div class="right-content">
		@include('layout.header')

		<div class="body-content">
			<!-- mobile title -->
			<h1 class="page-title-sm">{{__('label.edit_user')}}</h1>

			<div class="border-bottom row mb-3">
				<div class="col-sm-10">
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a>
						</li>
						<li class="breadcrumb-item">
							<a href="{{ route('user.index') }}">{{__('label.user')}}</a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">
							{{__('label.edit_user')}}
						</li>
					</ol>
				</div>
				<div class="col-sm-2 d-flex align-items-center justify-content-end">
					<a href="{{ route('user.index') }}" class="btn btn-default mw-120" style="margin-top:-14px">{{__('label.user_list')}}</a>
				</div>
			</div>

			<div class="card custom-border-card mt-3">
				<div class="card-body">
					<form name="user" id="user_update" enctype="multipart/form-data" autocomplete="off">
						<input type="hidden" name="id" value="@if($data){{$data->id}}@endif">
						<div class="form-row">
							<div class="col-md-6 mb-3">
								<div class="form-group">
									<label> {{__('label.full_name')}} </label>
									<input type="text" value="@if($data){{$data->full_name}}@endif" name="full_name" class="form-control" placeholder="{{__('label.enter_your_name')}}">
								</div>
							</div>
							<div class="col-md-6 mb-3">
								<div class="form-group">
									<label> {{__('label.mobile_number')}} </label>
									<input type="number" value="@if($data){{$data->mobile_number}}@endif" name="mobile_number" class="form-control" placeholder="{{__('label.enter_mobile_number')}}">
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-6 mb-3">
								<div class="form-group">
									<label>{{__('label.email')}}</label>
									<input type="email" value="@if($data){{$data->email}}@endif" name="email" class="form-control" placeholder="{{__('label.enter_your_email')}}">
								</div>
							</div>
							
						</div>
						<div class="form-row">
							<div class="col-md-6 mb-3">
								<div class="form-group">
									<label>{{__('label.date_of_birth')}}</label>
									<input type="date" value="@if($data){{$data->date_of_birth}}@endif" name="date_of_birth" class="form-control">
								</div>
							</div>
							<div class="col-md-6 mb-3">
								<div class="form-group">
									<label> {{__('label.gender')}} </label>
									<select class="form-control" name="gender">
									<option value="1" {{$data->gender == '1'  ? 'selected' : ''}}>{{__('label.male')}}</option>
									<option value="2" {{$data->gender == '2'  ? 'selected' : ''}}>{{__('label.female')}}</option>
									<option value="3" {{$data->gender == '3'  ? 'selected' : ''}}>{{__('label.other')}}</option>
									</select>
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-12 mb-3">
								<div class="form-group">
									<label for="bio">{{__('label.bio')}}</label>
									<textarea name="bio" class="form-control" rows="3" placeholder="{{__('label.describe_your')}}">@if($data){{$data->bio}}@endif</textarea>
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-6">
								<div class="form-group"> 
									<label>{{__('label.image')}}</label> 
									<input type="file" class="form-control" name="image" value="" id="image"> 
									<label class="mt-1 text-gray">{{__('label.note')}}</label>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<div class="custom-file ml-5">
										<img  src="{{$data['image']}}" height="120px" width="120px" id="Uploaded-Image">
										<input type="hidden" name="old_image" value="@if($data){{$data['image']}}@endif">
									</div>
								</div>
							</div>
						</div>
						<div class="border-top pt-3 text-right">
							<button type="button" class="btn btn-default mw-120" onclick="update_user()">{{__('label.update')}}</button>
							<a href="{{route('user.index')}}" class="btn btn-cancel mw-120 ml-2">{{__('label.cancel')}}</a>
							<input type="hidden" name="_method" value="PATCH">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('pagescript')
	<script>
		function update_user(){
			$("#dvloader").show();
			var formData = new FormData($("#user_update")[0]);

			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				enctype: 'multipart/form-data',
				type: 'POST',
				url: '{{route("user.update", [$data->id])}}',
				data: formData,
				cache:false,
				contentType: false,
				processData: false,
				success:function(resp){
					$("#dvloader").hide();
					get_responce_message(resp, 'user_update', '{{ route("user.index") }}');
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					$("#dvloader").hide();
					toastr.error(errorThrown.msg,'failed');         
				}
			});
		}
	</script>
@endsection