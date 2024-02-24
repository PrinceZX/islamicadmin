@extends('layout.page-app')

@section('page_title', 'Add Admin')

@section('content')

	@include('layout.sidebar')

	<div class="right-content">
		@include('layout.header')

		<div class="body-content">
			<!-- mobile title -->
			<h1 class="page-title-sm">Add Admin</h1>

			<div class="border-bottom row mb-3">
				<div class="col-sm-10">
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a>
						</li>
						<li class="breadcrumb-item">
							<a href="{{ route('admins.index') }}">Admins</a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">
							Add admin
						</li>
					</ol>
				</div>
				<div class="col-sm-2 d-flex align-items-center justify-content-end">
					<a href="{{ route('admins.index') }}" class="btn btn-default mw-120" style="margin-top:-14px">Admin List</a>
				</div>
			</div>

			<div class="card custom-border-card mt-3">
				<div class="card-body">
                    <form name="admins" id="admins" autocomplete="off" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="">
						<div class="form-row">
							<div class="col-md-6 mb-3">
								<div class="form-group">
									<label> {{__('label.user_name')}} </label>
									<input type="text" value="" name="user_name" class="form-control" placeholder="{{__('label.enter_your_name')}}">
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-6 mb-3">
								<div class="form-group">
									<label>{{__('label.email')}}</label>
									<input type="email" value="" name="email" class="form-control" placeholder="{{__('label.enter_your_email')}}">
								</div>
							</div>
						</div>
						<div class="form-row">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label> Role </label>
                                    <select class="form-control" name="permissions_role">
                                    <option value="super_admin">Super Admin</option>
                                    <option value="author" selected >Author</option>
                                    <option value="publisher">Publisher</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
							<div class="col-md-6 mb-3">
								<div class="form-group">
									<label>Enter Password</label>
									<input type="password" value="" name="password" class="form-control" placeholder="Enter your password">
								</div>
							</div>
						</div>
						<div class="border-top pt-3 text-right">
							<button type="button" class="btn btn-default mw-120" onclick="save_admin()">{{__('label.save')}}</button>
							<a href="{{route('admins.index')}}" class="btn btn-cancel mw-120 ml-2">{{__('label.cancel')}}</a>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('pagescript')
	<script>
		function save_admin(){
			$("#dvloader").show();
			var formData = new FormData($("#admins")[0]);

			$.ajax({
				type: 'POST',
				url: '{{route("admins.store")}}',
				data: formData,
				cache:false,
				contentType: false,
				processData: false,
				success:function(resp){
					$("#dvloader").hide();
					get_responce_message(resp, 'admins', '{{ route("admins.index") }}');
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					$("#dvloader").hide();
					toastr.error(errorThrown.msg,'failed');         
				}
			});
		}
	</script>
@endsection