@extends('layout.page-app')

@section('page_title',  __('label.edit_payment'))

@section('content')

	@include('layout.sidebar')
	
	<div class="right-content">
		@include('layout.header')

		<div class="body-content">
			<!-- mobile title -->
			<h1 class="page-title-sm">{{__('label.edit_payment')}}</h1>

			<div class="border-bottom row mb-3">
				<div class="col-sm-10">
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a>
						</li>
						<li class="breadcrumb-item">
							<a href="{{ route('payment.index') }}">{{__('label.payment')}}</a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">
							{{__('label.edit_payment')}}
						</li>
					</ol>
				</div>
				<div class="col-sm-2 d-flex align-items-center">
					<a href="{{ route('payment.index') }}" class="btn btn-default mw-120" style="margin-top:-14px">{{__('label.payment_list')}}</a>
				</div>
			</div>

			<div class="card custom-border-card mt-3">
				<div class="card-body">
					<form name="payment" id="payment_update" enctype="multipart/form-data" autocomplete="off">
						<input type="hidden" name="id" value="@if($data){{$data->id}}@endif">
						<div class="form-row">
							<div class="col-md-12 mb-3">
								<div class="form-group">
								<label>{{__('label.name')}}</label>
								<input name="name" type="text" class="form-control" readonly
									placeholder="{{__('Label.Please Enter Name')}}" value="@if($data){{$data->name}}@endif">
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-6 mb-3">
								<div class="form-group">
									<label>{{__('label.status')}}</label>
									<select class="form-control" name="visibility">
										<option value="">{{__('label.select_visibility')}}</option>
										<option value="1" {{$data->visibility == 1 ? 'selected' : ''}}>{{__('label.active')}}</option>
										<option value="0" {{$data->visibility == 0 ? 'selected' : ''}}>{{__('label.in_active')}}</option>
									</select>
								</div>
							</div>
							<div class="col-md-6 mb-3">
								<div class="form-group">
									<label>{{__('label.payment_environment')}}</label>
									<select class="form-control" name="is_live">
										<option value="">{{__('label.select_payment_environment')}}</option>
										<option value="1" {{$data->is_live == 1 ? 'selected' : ''}}>{{__('label.live')}}</option>
										<option value="0" {{$data->is_live == 0 ? 'selected' : ''}}>{{__('label.sandbox')}}</option>
									</select>
								</div>
							</div>
						</div>
						<!-- Paypal -->
						@if($data->id == 2)
							<div class="form-row">
								<div class="col-md-6 mb-3">
									<div class="form-group">
									<label>{{__('label.live_paypal_client_id')}}</label>
									<input name="live_key_1" type="text" class="form-control" placeholder="{{__('label.enter_id')}}" value="@if($data){{$data->live_key_1}}@endif">
									</div>
								</div>
								<div class="col-md-6 mb-3">
									<div class="form-group">
									<label>{{__('label.test_paypal_client_id')}}</label>
									<input name="test_key_1" type="text" class="form-control" placeholder="{{__('label.enter_id')}}" value="@if($data){{$data->test_key_1}}@endif">
									</div>
								</div>
							</div>
							<div class="form-row">
								<div class="col-md-6 mb-3">
									<div class="form-group">
									<label>Live Secret Key</label>
									<input name="live_key_2" type="text" class="form-control" placeholder="{{__('label.enter_id')}}" value="@if($data){{$data->live_key_2}}@endif">
									</div>
								</div>
								<div class="col-md-6 mb-3">
									<div class="form-group">
									<label>Test Secret Key</label>
									<input name="test_key_2" type="text" class="form-control" placeholder="{{__('label.enter_id')}}" value="@if($data){{$data->test_key_2}}@endif">
									</div>
								</div>
							</div>
						@endif
						<!-- RazorPay -->
						@if($data->id == 3)
							<div class="form-row">
								<div class="col-md-6 mb-3">
									<div class="form-group">
									<label>Live Key Id</label>
									<input name="live_key_1" type="text" class="form-control" placeholder="{{__('label.enter_id')}}" value="@if($data){{$data->live_key_1}}@endif">
									</div>
								</div>
								<div class="col-md-6 mb-3">
									<div class="form-group">
									<label>Live Key Secret Id</label>
									<input name="live_key_2" type="text" class="form-control" placeholder="{{__('label.enter_id')}}" value="@if($data){{$data->live_key_2}}@endif">
									</div>
								</div>
							</div>
							<div class="form-row">
								<div class="col-md-6 mb-3">
									<div class="form-group">
									<label>Test Key Id</label>
									<input name="test_key_1" type="text" class="form-control" placeholder="{{__('label.enter_id')}}" value="@if($data){{$data->test_key_1}}@endif">
									</div>
								</div>
								<div class="col-md-6 mb-3">
									<div class="form-group">
									<label>Test Key Secret Id</label>
									<input name="test_key_2" type="text" class="form-control" placeholder="{{__('label.enter_id')}}" value="@if($data){{$data->test_key_2}}@endif">
									</div>
								</div>
							</div>
						@endif
						<!-- FlutterWave -->
						@if($data->id == 4)
							<div class="form-row">
								<div class="col-md-6 mb-3">
									<div class="form-group">
									<label>{{__('label.live_public_id')}}</label>
									<input name="live_key_1" type="text" class="form-control" placeholder="{{__('label.enter_id')}}" value="@if($data){{$data->live_key_1}}@endif">
									</div>
								</div>
								<div class="col-md-6 mb-3">
									<div class="form-group">
									<label>{{__('label.live_encryption_key')}}</label>
									<input name="live_key_2" type="text" class="form-control" placeholder="{{__('label.enter_id')}}" value="@if($data){{$data->live_key_2}}@endif">
									</div>
								</div>
							</div>
							<div class="form-row">
								<div class="col-md-6 mb-3">
									<div class="form-group">
									<label>{{__('label.test_public_id')}}</label>
									<input name="test_key_1" type="text" class="form-control" placeholder="{{__('label.enter_id')}}" value="@if($data){{$data->test_key_1}}@endif">
									</div>
								</div>
								<div class="col-md-6 mb-3">
									<div class="form-group">
									<label>{{__('label.test_encryption_key')}}</label>
									<input name="test_key_2" type="text" class="form-control" placeholder="{{__('label.enter_key')}}" value="@if($data){{$data->test_key_2}}@endif">
									</div>
								</div>
							</div>
						@endif
						<!-- PayUMoney -->
						@if($data->id == 5)
							<div class="form-row">
								<div class="col-md-6 mb-3">
									<div class="form-group">
									<label>{{__('label.live_merchant_id')}}</label>
									<input name="live_key_1" type="text" class="form-control" placeholder="{{__('label.enter_id')}}" value="@if($data){{$data->live_key_1}}@endif">
									</div>
								</div>
								<div class="col-md-6 mb-3">
									<div class="form-group">
									<label>{{__('label.live_merchant_key')}}</label>
									<input name="live_key_2" type="text" class="form-control" placeholder="{{__('label.enter_key')}}" value="@if($data){{$data->live_key_2}}@endif">
									</div>
								</div>
							</div>
							<div class="form-row">
								<div class="col-md-6 mb-3">
									<div class="form-group">
									<label>{{__('label.live_merchant_salt_key')}}</label>
									<input name="live_key_3" type="text" class="form-control" placeholder="{{__('label.enter_key')}}" value="@if($data){{$data->live_key_3}}@endif">
									</div>
								</div>
							</div>
							<div class="form-row">
								<div class="col-md-6 mb-3">
									<div class="form-group">
									<label>{{__('label.test_marchant_id')}}</label>
									<input name="test_key_1" type="text" class="form-control" placeholder="{{__('label.enter_id')}}" value="@if($data){{$data->test_key_1}}@endif">
									</div>
								</div>
								<div class="col-md-6 mb-3">
									<div class="form-group">
									<label>{{__('label.test_marchant_key')}}</label>
									<input name="test_key_2" type="text" class="form-control" placeholder="{{__('label.enter_key')}}" value="@if($data){{$data->test_key_2}}@endif">
									</div>
								</div>
							</div>
							<div class="form-row">
								<div class="col-md-6 mb-3">
									<div class="form-group">
									<label>{{__('label.test_marchant_salt_key')}}</label>
									<input name="test_key_3" type="text" class="form-control" placeholder="{{__('label.enter_key')}}" value="@if($data){{$data->test_key_3}}@endif">
									</div>
								</div>
							</div>
						@endif
						<!-- PayTm -->
						@if($data->id == 6)
							<div class="form-row">
								<div class="col-md-6 mb-3">
									<div class="form-group">
									<label>{{__('label.live_merchant_id')}}</label>
									<input name="live_key_1" type="text" class="form-control" placeholder="{{__('label.enter_id')}}" value="{{ $data->live_key_1 }}">
									</div>
								</div>
								<div class="col-md-6 mb-3">
									<div class="form-group">
									<label>{{__('label.live_merchant_key')}}</label>
									<input name="live_key_2" type="text" class="form-control" placeholder="{{__('label.enter_key')}}" value="{{ $data->live_key_2 }}">
									</div>
								</div>
							</div>
							<div class="form-row">
								<div class="col-md-6 mb-3">
									<div class="form-group">
									<label>{{__('label.test_marchant_id')}}</label>
									<input name="test_key_1" type="text" class="form-control" placeholder="{{__('label.enter_id')}}" value="{{ $data->test_key_1 }}">
									</div>
								</div>
								<div class="col-md-6 mb-3">
									<div class="form-group">
									<label>{{__('label.test_marchant_key')}}</label>
									<input name="test_key_2" type="text" class="form-control" placeholder="{{__('label.enter_key')}}" value="{{ $data->test_key_2 }}">
									</div>
								</div>
							</div>
						@endif
						<!--stripe -->
						@if($data->id == 7)
							<div class="form-row">
								<div class="col-md-6 mb-3">
									<div class="form-group">
									<label>Live Publishable key</label>
									<input name="live_key_1" type="text" class="form-control" placeholder="{{__('label.enter_id')}}" value="{{ $data->live_key_1 }}">
									</div>
								</div>
								<div class="col-md-6 mb-3">
									<div class="form-group">
									<label>Live Secret Key</label>
									<input name="live_key_2" type="text" class="form-control" placeholder="{{__('label.enter_key')}}" value="{{ $data->live_key_2 }}">
									</div>
								</div>
							</div>
							<div class="form-row">
								<div class="col-md-6 mb-3">
									<div class="form-group">
									<label>Test Publishable key</label>
									<input name="test_key_1" type="text" class="form-control" placeholder="{{__('label.enter_id')}}" value="{{ $data->test_key_1 }}">
									</div>
								</div>
								<div class="col-md-6 mb-3">
									<div class="form-group">
									<label>Test Secret Key</label>
									<input name="test_key_2" type="text" class="form-control" placeholder="{{__('label.enter_key')}}" value="{{ $data->test_key_2 }}">
									</div>
								</div>
							</div>
						@endif
						<div class="border-top pt-3 text-right">
							<button type="button" class="btn btn-default mw-120" onclick="update_payment()">{{__('label.update')}}</button>
							<a href="{{route('payment.index')}}" class="btn btn-cancel mw-120 ml-2">{{__('label.cancel')}}</a>
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
		function update_payment(){
			$("#dvloader").show();
			var formData = new FormData($("#payment_update")[0]);

			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				enctype: 'multipart/form-data',
				type: 'POST',
				url: '{{route("payment.update", [$data->id])}}',
				data: formData,
				cache:false,
				contentType: false,
				processData: false,
				success:function(resp){
					$("#dvloader").hide();
					get_responce_message(resp, 'payment_update', '{{ route("payment.index") }}');
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					$("#dvloader").hide();
					toastr.error(errorThrown.msg,'failed');         
				}
			});
		}
	</script>
@endsection