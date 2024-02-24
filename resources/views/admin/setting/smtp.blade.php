@extends('layout.page-app')

@section('page_title',  __('label.smtp_setting'))

@section('content')
    @include('layout.sidebar')

    <div class="right-content">
        @include('layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.smtp_setting')}}</h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                {{__('label.dashboard')}}
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <a href="{{ route('setting') }}">
                                {{__('label.setting')}}
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{__('label.smtp_setting')}}
                        </li>
                    </ol>
                </div>
            </div>

            
            <div class="card custom-border-card mt-3">
                <h5 class="card-header">{{__('label.emai_settings[smtp]')}}</h5>
                <div class="card-body">
                    @if($smtp !=null)
                        <form id="smtp_setting" autocomplete="off" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="@if($smtp){{$smtp->id}}@endif">
                            @csrf
                            <div class="row col-lg-12">
                                <div class="form-group  col-lg-6">
                                    <label>{{__('label.is_smtp_active')}}</label>
                                    <select name="status" class="form-control">
                                        <option value="0" {{ $smtp->status == 0  ? 'selected' : ''}}>
                                            {{__('label.no')}}
                                        </option>
                                        <option value="1" {{ $smtp->status == 1  ? 'selected' : ''}}>
                                            {{__('label.yes')}}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>{{__('label.host')}}</label>
                                    <input type="text" name="host" class="form-control" value="@if($smtp){{$smtp->host}}@endif" placeholder="Enter Host">
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>{{__('label.port')}}</label>
                                    <input type="text" name="port" class="form-control" value="@if($smtp){{$smtp->port}}@endif" placeholder="Enter Port">
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>{{__('label.protocol')}}</label>
                                    <input type="text" name="protocol" class="form-control" placeholder="Enter Your protocol" value="@if($smtp){{$smtp->protocol}}@endif" placeholder="Enter Protocol">
                                </div>
                            </div>
                            <div class="row col-lg-12">
                                <div class="form-group col-lg-6">
                                    <label>{{__('label.user_name')}}</label>
                                    <input type="text" name="user" class="form-control" value="@if($smtp){{$smtp->user}}@endif" placeholder="Enter User Name">
                                </div>
                                <div class="form-group col-lg-6">
                                    <label for="pass">{{__('label.password')}}</label>
                                    <input type="password" name="pass" class="form-control" value="@if($smtp){{$smtp->pass}}@endif" placeholder="Enter Password">
                                </div>
                            </div>
                            <div class="row col-lg-12">
                                <div class="form-group col-lg-6">
                                    <label>{{__('label.from_name')}}</label>
                                    <input type="text" name="from_name" class="form-control" value="@if($smtp){{$smtp->from_name}}@endif" placeholder="Enter Form Name">
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>{{__('label.from_email')}}</label>
                                    <input type="text" name="from_email" class="form-control" value="@if($smtp){{$smtp->from_email}}@endif" placeholder="Enter From Email">
                                </div>
                            </div>

                            <div class="border-top pt-3 text-right">
                                <button type="button" class="btn btn-default mw-120" onclick="smtp_setting()">{{__('label.save')}}</button>
                            </div>
                        </form>
                    @else
                        <form id="smtp_setting" autocomplete="off" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="">

                            @csrf
                            <div class="row col-lg-12">
                                <div class="form-group  col-lg-6">
                                    <label>{{__('label.is_smtp_active')}}</label>
                                    <select name="status" class="form-control">
                                        <option value="0">
                                            {{__('label.no')}}
                                        </option>
                                        <option value="1">
                                            {{__('label.yes')}}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>{{__('label.host')}}</label>
                                    <input type="text" name="host" class="form-control" value="" placeholder="Enter Host">
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>{{__('label.port')}}</label>
                                    <input type="text" name="port" class="form-control" value="" placeholder="Enter Port">
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>{{__('label.protocol')}}</label>
                                    <input type="text" name="protocol" class="form-control" placeholder="Enter Your protocol" value="" placeholder="Enter Protocol">
                                </div>
                            </div>
                            <div class="row col-lg-12">
                                <div class="form-group col-lg-6">
                                    <label>{{__('label.user_name')}}</label>
                                    <input type="text" name="user" class="form-control" value="" placeholder="Enter User Name">
                                </div>
                                <div class="form-group col-lg-6">
                                    <label for="pass">{{__('label.password')}}</label>
                                    <input type="password" name="pass" class="form-control" value="" placeholder="Enter Password">
                                </div>
                            </div>
                            <div class="row col-lg-12">
                                <div class="form-group col-lg-6">
                                    <label>{{__('label.from_name')}}</label>
                                    <input type="text" name="from_name" class="form-control" value="" placeholder="Enter Form Name">
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>{{__('label.from_email')}}</label>
                                    <input type="text" name="from_email" class="form-control" value="" placeholder="Enter From Email">
                                </div>
                            </div>
                            
                            <div class="border-top pt-3 text-right">
                                <button type="button" class="btn btn-default mw-120" onclick="smtp_setting()">{{__('label.save')}}</button>
                            </div>
                        </form>
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        
        function smtp_setting() {
            var formData = new FormData($("#smtp_setting")[0]);
            $.ajax({
                type: 'POST',
                url: '{{ route("settingsmtp") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    get_responce_message(resp, 'smtp_setting', '{{ route("setting") }}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    toastr.error(errorThrown.msg, 'failed');
                }
            });
        }
    </script>
@endsection
