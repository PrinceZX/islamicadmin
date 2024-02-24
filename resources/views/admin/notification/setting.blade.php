@extends('layout.page-app')

@section('page_title',  __('label.notification_setting'))

@section('content')

    @include('layout.sidebar')

    <div class="right-content">
        @include('layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.notification_setting')}}</h1>

            <div class="border-bottom row">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{__('label.notification_setting')}}
                        </li>
                    </ol>
                </div>
            </div>

            <div class="card custom-border-card mt-3">
                <form name="notification-setting" id="notification-setting" autocomplete="off" method="post" enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label>{{__('label.one_signal_app_id')}}</label>
                                <input name="onesignal_apid" type="text" class="form-control" value="@if($result){{$result['onesignal_apid']}}@endif" placeholder="{{__('label.enter_one_signal_app_id')}}">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label>{{__('label.one_signal_reset_key')}}</label>
                                <input name="onesignal_rest_key" type="text" class="form-control" value="@if($result){{$result['onesignal_rest_key']}}@endif"  placeholder="{{__('label.enter_one_signal_reset_key')}}">
                            </div>
                        </div>
                    </div>
                    <div class="border-top pt-3 text-right">
                        <button type="button" class="btn btn-default mw-120" onclick="notification_setting()">{{__('label.save')}}</button>
                        <a href="{{route('notification.index')}}" class="btn btn-cancel mw-120 ml-2">{{__('label.cancel')}}</a>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
	<script>
		function notification_setting(){
			$("#dvloader").show();
			var formData = new FormData($("#notification-setting")[0]);
			$.ajax({
				type:'POST',
				url:'{{ route("notification.settingsave") }}',
				data:formData,
				cache:false,
				contentType: false,
				processData: false,
				success:function(resp){
					$("#dvloader").hide();
					get_responce_message(resp, 'notification-setting', '{{ route("notification.index") }}');
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					$("#dvloader").hide();
					toastr.error(errorThrown.msg,'failed');         
				}
			});
		}
	</script>
@endsection