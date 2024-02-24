@section('page_title',  __('label.setting'))
<!DOCTYPE html>
<html lang="en" dir="{{(App::isLocale('Arebic') ? 'rtl' : 'ltr')}}">
    <head>

        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
        
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
        
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

        <!-- Meta Tag -->
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Title Tag  -->
        <?php  $setting = setting(); ?>
        @if(isset($setting) && $setting['app_name'] == "")
            <title>{{ env('APP_NAME') }}</title>
        @else
            <title>{{$setting['app_name']}}</title>
        @endif
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/fontawesome.min.css">
        <link rel="stylesheet" href="{{asset('/assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('/assets/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
        <link href="{{url('/assets/css/toastr.min.css')}}" rel="stylesheet" type="text/css">
        <link href="{{ asset('/assets/css/style.css?v=version()') }}" rel="stylesheet">
        <link href="{{asset('/assets/css/custom.css?v=version()') }}" rel="stylesheet">

        <!-- Custom CSS -->
        <style>
            #dvloader {
                width: 100%;
                height: 100%;
                top: 0;
                left: 0;
                position: fixed;
                display: block;
                opacity: 0.7;
                background-color: #fff;
                z-index: 9999;
                text-align: center;
            }
            #dvloader image {
                position: absolute;
                top: 100px;
                left: 240px;
                z-index: 100;
            }
            .btn-cancel {
                background: #000;
                border-radius: 50px;
                font-size: 16px;
                font-weight: 600;
                color: #fff;
                border: 1px solid transparent;
                -webkit-transition: all 0.3s;
                transition: all 0.3s;
                padding: 8px 20px;
            }
            .btn-cancel:hover {
                color: #000;
                background: transparent;
                border-color: #000;
            }
        </style>
        <!--Custom CSS-->
    </head>

    <body>
        @include('layout.sidebar')

        <div class="right-content">
            @include('layout.header')

            <div class="body-content">
                <!-- mobile title -->
                <h1 class="page-title-sm">{{__('label.setting')}}</h1>

                <div class="border-bottom row mb-3">
                    <div class="col-sm-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('setting') }}">{{__('label.setting')}}</a>
                            </li>
                            
                        </ol>
                    </div>
                    
                </div>

            <ul class="nav nav-pills custom-tabs inline-tabs" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="app-tab" data-toggle="tab" href="#app" role="tab" aria-controls="app" aria-selected="true">{{__('label.app_settings')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="change-password-tab" data-toggle="tab" href="#change-password" role="tab" aria-controls="change-password" aria-selected="true">{{__('label.change_passwords')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="admob-tab" data-toggle="tab" href="#admob" role="tab" aria-controls="admob" aria-selected="false">{{__('label.admob')}}</a>
                </li>
                
            </ul>

            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="app" role="tabpanel" aria-labelledby="app-tab">
                    <div class="app-right-btn">
                        <a href="{{ route('settingsmtpindex')}}" class="btn btn-default">{{__('label.emai_settings[smtp]')}}</a>
                    </div>
                    <div class="card custom-border-card">
                        <h5 class="card-header">{{__('label.app_configrations')}}</h5>
                        <div class="card-body">
                            <div class="input-group">
                                <div class="col-2">
                                    <label class="ml-5 pt-3" style="font-size:16px; font-weight:500; color:#1b1b1b">{{__('label.api_path')}}</label>
                                </div>
                                <input type="text" readonly value="{{url('/')}}/api" name="api_path" class="form-control" style="background-color:matte gray;" id="api_path">
                                <div class="input-group-prepend">
                                    <div class="input-group-text btn" style="background-color:matte gray;" onclick="Function_Api_path()">
                                        <img src="{{ url('/') }}/assets/imgs/copy.png" alt=""/>
                                    </div> 
                                </div>
                            </div> 
                        </div>
                    </div>
                    <div class="card custom-border-card">
                        <h5 class="card-header">{{__('label.app_setting')}}</h5>
                        <div class="card-body">
                            <form id="app_setting" enctype="multipart/form-data">
                                @csrf
                                <div class="row col-lg-12">
                                    <div class="form-group col-lg-6">
                                        <label>{{__('label.app_name')}}</label>
                                        <input type="text" name="app_name" class="form-control"
                                            placeholder="Enter App Name" value="{{$result['app_name']}}">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>{{__('label.host_email')}}</label>
                                        <input type="email" name="host_email" class="form-control"
                                            value="{{$result['host_email']}}" placeholder="Enter Host Email">
                                    </div>
                                </div>
                                <div class="row col-lg-12">
                                    <div class="form-group col-lg-6">
                                        <label>{{__('label.app_version')}}</label>
                                        <input type="text" name="app_version" class="form-control" 
                                            value="{{$result['app_version']}}" placeholder="Enter App Version">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>{{__('label.author')}}</label>
                                        <input type="text" name="Author" class="form-control" 
                                            value="{{$result['Author']}}" placeholder="Enter Author">
                                    </div>
                                </div>
                                <div class="row col-lg-12">
                                    <div class="form-group col-lg-6">
                                        <label>{{__('label.email')}} </label>
                                        <input type="email" name="email" class="form-control"
                                            value="{{$result['email']}}" placeholder="Enter Email">
                                    </div>
                                    <div class="form-group  col-lg-6">
                                        <label> {{__('label.contact')}} </label>
                                        <input type="text" name="contact" class="form-control" 
                                            value="{{$result['contact']}}" placeholder="Enter Contact">
                                    </div>
                                </div>
                                <div class="row col-lg-12">
                                    <div class="form-group col-lg-12">
                                        <label>{{__('label.app_description')}}</label>
                                        <textarea name="app_desripation" class="form-control summernote" id="summernote"
                                            placeholder="Enter App Desripation">{{$result['app_desripation']}}</textarea>
                                    </div>
                                </div>
                                <div class="row col-lg-12">
                                    <div class="form-group col-lg-12">
                                        <label>{{__('label.private_policy')}}</label>
                                        <textarea name="privacy_policy" class="form-control summernote" 
                                            placeholder="Enter Privacy Policy">{{$result['privacy_policy']}}</textarea>
                                    </div>
                                </div>
                                <div class="row col-lg-12">
                                    <div class="form-group col-lg-12">
                                        <label>{{__('label.instrucation')}}</label>
                                        <textarea name="instrucation" class="form-control summernote" 
                                            placeholder="Enter Instrucation">{{$result['instrucation']}}</textarea>
                                    </div>
                                </div>

                                <div class="row col-lg-12">
                                    <div class="form-group col-lg-6">
                                        <label for="app_logo">{{__('label.app_image')}}</label>
                                        <input type="file" name="app_logo" class="form-control" id="thumbnail" placeholder="Enter Your App Name" name="app_logo">

                                        <label class="mt-1 text-gray">{{__('label.note')}}</label>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>{{__('label.website')}}</label>
                                        <input type="text" name="website" class="form-control" value="{{$result['website']}}" placeholder="Enter Your Website">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-5">
                                    <div class="form-group">
                                        <div class="custom-file ml-5">
                                        <img src="{{$result['app_logo']}}" height="120px" width="120px" class="mb-5" id="preview-image-before-upload">
                                        <input type="hidden" name="old_app_logo" value="@if($result){{$result['app_logo']}}@endif">

                                       
                                        </div>
                                    </div>
                                </div>
                              
                               
                                <div class="border-top pt-3 text-right">
                                    <button type="button" class="btn btn-default mw-120"
                                        onclick="app_setting()">{{__('label.save')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card custom-border-card">
                        <h5 class="card-header">{{__('label.currency_setting')}}</h5>
                        <div class="card-body">
                            <form id="save_currency">
                                @csrf
                                <div class="row col-lg-12">
                                    <div class="form-group col-lg-6">
                                        <label>{{__('label.currency_name')}} </label>
                                        <input type="text" name="currency" class="form-control" value="{{$result['currency']}}" placeholder="Enter Currency Name">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label> {{__('label.currency_code')}} </label>
                                        <input type="text" name="currency_code" class="form-control"
                                            value="{{$result['currency_code']}}" placeholder="Enter Currency Code">
                                    </div>
                                </div>
                                <div class="border-top pt-3 text-right">
                                    <button type="button" class="btn btn-default mw-120"
                                        onclick="save_currency()">{{__('label.save')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="change-password" role="tabpanel" aria-labelledby="change-password-tab">
                    <div class="card custom-border-card">
                        <h5 class="card-header">{{__('label.change_password')}}</h5>
                        <div class="card-body">
                            <div class="">
                                <div class="form-group">
                                    <form id="change_password">
                                        @csrf
                                        <input type="hidden" name="admin_id" value="1">
                                        <div class="row">
                                            <div class="form-group col-lg-12">
                                                <label for="password">{{__('label.new_password')}}</label>
                                                <input type="password" name="password" class="form-control" id="password"
                                                    placeholder="{{__('label.enter_new_password')}}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-lg-12">
                                                <label for="confirm_password">{{__('label.confirm_password')}}</label>
                                                <input type="password" name="confirm_password" class="form-control"
                                                    id="confirm_password" placeholder="{{__('label.enter_config_password')}}">
                                            </div>
                                        </div>
                                        <div class="border-top pt-3 text-right">
                                            <button type="button" class="btn btn-default mw-120"
                                                onclick="change_password()">{{__('label.save')}}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="admob" role="tabpanel" aria-labelledby="admob-tab">
                    <div class="card custom-border-card mt-3">
                        <h5 class="card-header">{{__('label.android_settings')}}</h5>
                        <div class="card-body">
                            <form id="admob_android">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label for="banner_ad">{{__('label.banner_ad')}}</label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="banner_ad" name="banner_ad"
                                                        class="custom-control-input"
                                                        {{ ($result['banner_ad']=='1')? "checked" : "" }} value="1">
                                                    <label class="custom-control-label"
                                                        for="banner_ad">{{__('label.yes')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="banner_ad1" name="banner_ad"
                                                        class="custom-control-input"
                                                        {{ ($result['banner_ad']=='0')? "checked" : "" }} value="0">
                                                    <label class="custom-control-label"
                                                        for="banner_ad1">{{__('label.no')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label for="interstital_ad">{{__('label.interstital_ad')}}</label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="interstital_ad" name="interstital_ad"
                                                        class="custom-control-input"
                                                        {{ ($result['interstital_ad']=='1')? "checked" : "" }} value="1">
                                                    <label class="custom-control-label"
                                                        for="interstital_ad">{{__('label.yes')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="interstital_ad1" name="interstital_ad"
                                                        class="custom-control-input"
                                                        {{ ($result['interstital_ad']=='0')? "checked" : "" }} value="0">
                                                    <label class="custom-control-label"
                                                        for="interstital_ad1">{{__('label.no')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label for="reward_ad">{{__('label.reward_ad')}}</label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="reward_ad" name="reward_ad"
                                                        class="custom-control-input"
                                                        {{ ($result['reward_ad']=='1')? "checked" : "" }} value="1">
                                                    <label class="custom-control-label"
                                                        for="reward_ad">{{__('label.yes')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="reward_ad1" name="reward_ad"
                                                        class="custom-control-input"
                                                        {{ ($result['reward_ad']=='0')? "checked" : "" }} value="0">
                                                    <label class="custom-control-label"
                                                        for="reward_ad1">{{__('label.no')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label for="banner_adid">{{__('label.banner_ad_id')}}</label>
                                            <input type="text" name="banner_adid" class="form-control" id="banner_adid"
                                                placeholder="Enter Banner Ad ID" value="{{$result['banner_adid']}}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label for="interstital_adid">{{__('label.interstital_ad_id')}}</label>
                                            <input type="text" name="interstital_adid" class="form-control"
                                                id="interstital_adid" placeholder="Enter interstital Ad ID"
                                                value="{{$result['interstital_adid']}}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label for="reward_adid">{{__('label.reward_ad_id')}}</label>
                                            <input type="text" name="reward_adid" class="form-control" id="reward_adid"
                                                placeholder="Enter Reward Ad ID" value="{{$result['reward_adid']}}">
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label></label>
                                            &nbsp;
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label for="interstital_adclick">{{__('label.interstital_ad_click')}}</label>
                                            <input type="text" name="interstital_adclick" class="form-control"
                                                id="interstital_adclick" placeholder="Enter Interstital Ad Click"
                                                value="{{$result['interstital_adclick']}}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label for="reward_adclick">{{__('label.reward_ad_click')}}</label>
                                            <input type="text" name="reward_adclick" class="form-control"
                                                placeholder="Enter Reward Ad Click" value="{{$result['reward_adclick']}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="border-top pt-3 text-right">
                                    <button type="button" class="btn btn-default mw-120"
                                        onclick="admob_android()">{{__('label.save')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card custom-border-card mt-3">
                        <h5 class="card-header">{{__('label.iOS_settings')}}</h5>
                        <div class="card-body">
                            <form id="admob_ios">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label for="ios_banner_ad">{{__('label.banner_ad')}}</label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="ios_banner_ad" name="ios_banner_ad"
                                                        class="custom-control-input"
                                                        {{ ($result['ios_banner_ad']=='1')? "checked" : "" }} value="1">
                                                    <label class="custom-control-label"
                                                        for="ios_banner_ad">{{__('label.yes')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="ios_banner_ad1" name="ios_banner_ad"
                                                        class="custom-control-input"
                                                        {{ ($result['ios_banner_ad']=='0')? "checked" : "" }} value="0">
                                                    <label class="custom-control-label"
                                                        for="ios_banner_ad1">{{__('label.no')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label for="ios_interstital_ad">{{__('label.interstital_ad')}}</label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="ios_interstital_ad" name="ios_interstital_ad"
                                                        class="custom-control-input"
                                                        {{ ($result['ios_interstital_ad']=='1')? "checked" : "" }} value="1">
                                                    <label class="custom-control-label"
                                                        for="ios_interstital_ad">{{__('label.yes')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="ios_interstital_ad1" name="ios_interstital_ad"
                                                        class="custom-control-input"
                                                        {{ ($result['ios_interstital_ad']=='0')? "checked" : "" }} value="0">
                                                    <label class="custom-control-label"
                                                        for="ios_interstital_ad1">{{__('label.no')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label for="ios_reward_ad">{{__('label.reward_ad')}}</label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="ios_reward_ad" name="ios_reward_ad"
                                                        class="custom-control-input"
                                                        {{ ($result['ios_reward_ad']=='1')? "checked" : "" }} value="1">
                                                    <label class="custom-control-label"
                                                        for="ios_reward_ad">{{__('label.yes')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="ios_reward_ad1" name="ios_reward_ad"
                                                        class="custom-control-input"
                                                        {{ ($result['ios_reward_ad']=='0')? "checked" : "" }} value="0">
                                                    <label class="custom-control-label"
                                                        for="ios_reward_ad1">{{__('label.no')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label for="ios_banner_adid">{{__('label.banner_ad_id')}}</label>
                                            <input type="text" name="ios_banner_adid" class="form-control" id="ios_banner_adid"
                                                placeholder="Enter Banner Ad ID" value="{{$result['ios_banner_adid']}}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label for="ios_interstital_adid">{{__('label.interstital_ad_id')}}</label>
                                            <input type="text" name="ios_interstital_adid" class="form-control"
                                                id="ios_interstital_adid" placeholder="Enter interstital Ad ID"
                                                value="{{$result['ios_interstital_adid']}}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label for="ios_reward_adid">{{__('label.reward_ad_id')}}</label>
                                            <input type="text" name="ios_reward_adid" class="form-control" id="ios_reward_adid"
                                                placeholder="Enter Reward Ad ID" value="{{$result['ios_reward_adid']}}">
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label></label>
                                            &nbsp;
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label for="ios_interstital_adclick">{{__('label.interstital_ad_click')}}</label>
                                            <input type="text" name="ios_interstital_adclick" class="form-control"
                                                id="ios_interstital_adclick" placeholder="Enter Interstital Ad Click"
                                                value="{{$result['ios_interstital_adclick']}}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label for="ios_reward_adclick">{{__('label.reward_ad_click')}}</label>
                                            <input type="text" name="ios_reward_adclick" class="form-control"
                                                placeholder="Enter Reward Ad Click" value="{{$result['ios_reward_adclick']}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="border-top pt-3 text-right">
                                    <button type="button" class="btn btn-default mw-120"
                                        onclick="admob_ios()">{{__('label.save')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
            </div>

            </div>
        </div>
        <div style="display:none" id="dvloader"><img src="{{ asset('assets/imgs/loading.gif')}}" /></div>

        <script>
          
          $(document).ready(function(e) {
            $('#thumbnail').change(function(){
                let reader = new FileReader();
                    reader.onload = (e) => { 
                        $('#preview-image-before-upload').attr('src', e.target.result); 
                    }
                    reader.readAsDataURL(this.files[0]); 
                });
        });
        

        function app_setting() {
            var formData = new FormData($("#app_setting")[0]);
            $("#dvloader").show();
            $.ajax({
                type: 'POST',
                url: '{{ route("settingapp") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'app_setting','{{route("setting")}}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown.msg, 'failed');
                }
            });
        }
        $('#summernote').summernote({
                placeholder: 'Hello stand alone ui',
                tabsize: 2,
                height: 120,
                toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
                ]
        });
        
        function save_currency() {
            var formData = new FormData($("#save_currency")[0]);
            $("#dvloader").show();
            $.ajax({
                type: 'POST',
                url: '{{ route("settingcurrency") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    $("html, body").animate({ scrollTop: 0 }, "swing");
                    get_responce_message(resp);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown.msg, 'failed');
                }
            });
        }

        function change_password() {
            var formData = new FormData($("#change_password")[0]);
            $("#dvloader").show();
            $.ajax({
                type: 'POST',
                url: '{{ route("settingchangepassword") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    $("html, body").animate({ scrollTop: 0 }, "swing");
                    get_responce_message(resp, 'change_password');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown.msg, 'failed');
                }
            });
        }

        function admob_android() {
            var formData = new FormData($("#admob_android")[0]);
            $("#dvloader").show();
            $.ajax({
                type: 'POST',
                url: '{{ route("settingadmob_android") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    $("html, body").animate({ scrollTop: 0 }, "swing");
                    get_responce_message(resp);                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown.msg, 'failed');
                }
            });
        }

        function admob_ios() {
            var formData = new FormData($("#admob_ios")[0]);
            $("#dvloader").show();
            $.ajax({
                type: 'POST',
                url: '{{ route("settingadmob_ios") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    $("html, body").animate({ scrollTop: 0 }, "swing");
                    get_responce_message(resp);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown.msg, 'failed');
                }
            });
        }

        function get_responce_message(resp, form_name="", url="") {
            if (resp.status == '200') {
                toastr.success(resp.success);
                document.getElementById(form_name).reset();
                setTimeout(function() {
                    window.location.replace(url);
                }, 500);
            } else {
                var obj = resp.errors;
                if (typeof obj === 'string') {
                    toastr.error(obj);
                } else {
                    $.each(obj, function(i, e) {
                    toastr.error(e);
                    });
                }
            }
        }
        
        function Function_Api_path() {
            /* Get the text field */
            var copyText = document.getElementById("api_path");

            /* Select the text field */
            copyText.select();
            copyText.setSelectionRange(0, 99999); /* For mobile devices */

            document.execCommand('copy');
            
            /* Alert the copied text */
            alert("Copied the API Path: " + copyText.value);
        }
        </script>
        <!-- Toastr -->
        <script src="{{ asset('/assets/js/toastr.min.js')}}"></script>
    </body>
</html>