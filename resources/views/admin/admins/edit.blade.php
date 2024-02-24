@extends('layout.page-app')

@section('page_title',  __('label.edit_user'))

@section('content')

	@include('layout.sidebar')

	<div class="right-content">
		@include('layout.header')

		<div class="body-content">
			<!-- mobile title -->
			<h1 class="page-title-sm">Edit Admin</h1>

			<div class="border-bottom row mb-3">
				<div class="col-sm-10">
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a>
						</li>
						<li class="breadcrumb-item">
							<a href="{{ route('user.index') }}">Admins</a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">
							Edit admin
						</li>
					</ol>
				</div>
				<div class="col-sm-2 d-flex align-items-center justify-content-end">
					<a href="{{ route('admins.index') }}" class="btn btn-default mw-120" style="margin-top:-14px">Admin List</a>
				</div>
			</div>

			<div class="card custom-border-card mt-3">
				<div class="card-body">
					<form name="admins" id="admin_update" enctype="multipart/form-data" autocomplete="off">
						<input type="hidden" name="id" value="@if($data){{$data->id}}@endif">
						<div class="form-row">
							<div class="col-md-6 mb-3">
								<div class="form-group">
									<label> {{__('label.user_name')}} </label>
									<input type="text" value="@if($data){{$data->user_name}}@endif" name="user_name" readonly class="form-control" placeholder="{{__('label.enter_your_name')}}">
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-6 mb-3">
								<div class="form-group">
									<label>{{__('label.email')}}</label>
									<input type="email" value="@if($data){{$data->email}}@endif" name="email" readonly class="form-control" placeholder="{{__('label.enter_your_email')}}">
								</div>
							</div>
						</div>
						<div class="form-row">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label> Role </label>
                                    <select class="form-control" name="permissions_role">
                                    <option value="super_admin" {{$data->permissions_role == 'super_admin'  ? 'selected' : ''}}>Super Admin</option>
                                    <option value="author" {{$data->permissions_role == 'author'  ? 'selected' : ''}}>Author</option>
                                    <option value="publisher" {{$data->permissions_role == 'publisher'  ? 'selected' : ''}}>Publisher</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
							<div class="col-md-6 mb-3">
								<div class="form-group">
									<label>Change Password</label>
									<input type="password" value="" name="password" class="form-control" placeholder="Enter your new password">
								</div>
							</div>
						</div>
						<div class="border-top pt-3 text-right">
							<button type="button" class="btn btn-default mw-120" onclick="update_admin()">{{__('label.update')}}</button>
							<a href="{{route('admins.index')}}" class="btn btn-cancel mw-120 ml-2">{{__('label.cancel')}}</a>
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
		function update_admin(){
			$("#dvloader").show();
			var formData = new FormData($("#admin_update")[0]);

			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				enctype: 'multipart/form-data',
				type: 'POST',
				url: '{{route("admins.update", [$data->id])}}',
				data: formData,
				cache:false,
				contentType: false,
				processData: false,
				success:function(resp){
					$("#dvloader").hide();
					get_responce_message(resp, 'admin_update', '{{ route("admins.index") }}');
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					$("#dvloader").hide();
					toastr.error(errorThrown.msg,'failed');         
				}
			});
		}
	</script>
@endsection