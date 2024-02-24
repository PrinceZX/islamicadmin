@extends('layout.page-app')
    
    @section('page_title',  __('Edit Audio'))
    
    @section('content')
        @include('layout.sidebar')
    
        <div class="right-content">
            @include('layout.header')
    
            <div class="body-content">
                <!-- mobile title -->
                <h1 class="page-title-sm">{{__('Edit Aduio')}}</h1>
                <div class="border-bottom row mb-3">
                    <div class="col-sm-10">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('audio.index') }}">{{__('Audio')}}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                {{__('Edit Audio')}}
                            </li>
                        </ol>
                    </div>
                    <div class="col-sm-2 d-flex align-items-center justify-content-end">
                        <a href="{{ route('audio.index') }}" class="btn btn-default mw-120" style="margin-top:-14px">{{__('Audio List')}}</a>
                    </div>
                </div>
                <div class="card custom-border-card mt-3">
                    <div class="card-body">
                        <form enctype="multipart/form-data" id="video" autocomplete="off">
                            @csrf
                            <input type="hidden" name="id" value="@if($data){{$data->id}}@endif"> 
                            <input type="hidden" name="old_video_url" value="@if($data){{$data->url}}@endif">

                            <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="name">{{__('label.name')}}</label>
                                    <input type="text" name="name" value="@if($data){{$data->name}}@endif" class="form-control" placeholder="{{__('label.enter_video_name')}}">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="artist_id">{{__('label.artist')}}</label>
                                    <select class="form-control" style="width:100%!important;" name="artist_id" id="artist_id">
                                        <option value="">Select Artist</option>
                                        @foreach ($artist as $key => $value)
                                        <option value="{{ $value->id}}" {{$value->id == $data->artist_id ? 'selected' : ''}}>
                                            {{ $value->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="user_id">{{__('label.user')}}</label>
                                    <select class="form-control" style="width:100%!important;" name="user_id" id="user_id">
                                        <option value="">Select Users</option>
                                        @foreach ($user as $key => $value)
                                        <option value="{{ $value->id}}" {{$value->id == $data->user_id ? 'selected' : ''}}>
                                            {{ $value->full_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                        </div>
               
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="category_id">{{__('label.category')}}</label>
                                    <select class="form-control" style="width:100%!important;" name="category_id" id="category_id">
                                    <option value="">Select Category</option>
                                        @foreach ($category as $key => $value)
                                        <option value="{{ $value->id}}" {{$value->id == $data->category_id ? 'selected' : ''}}>
                                            {{ $value->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group IS Paid">
                                    <label for="download">{{__('label.IS Paid')}}</label>
                                    <select class="form-control" name="is_paid">
                                    <option value="0" {{ $data->is_paid == 0  ? 'selected' : ''}}>{{__('label.free')}}</option>
                                    <option value="1" {{ $data->is_paid == 1  ? 'selected' : ''}}>{{__('label.paid')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group Is_Download">
                                    <label for="download">{{__('label.Feature')}}</label>
                                    <select class="form-control" name="is_feature">
                                    <option value="0" {{ $data->is_feature == 0  ? 'selected' : ''}}>{{__('label.no')}}</option>
                                    <option value="1" {{ $data->is_feature == 1  ? 'selected' : ''}}>{{__('label.yes')}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row col-lg-12">
        
                            <div class="server_video box form-group col-lg-6 videoLink">
                                <div id="serverVideo" style="display: block;">
                                    <label for="input-1">Upload Audio</label>
                                        <div id="filelist"></div>
                                            <div id="container" style="position: relative;">
                                                <div class="form-group">
                                                    <input type="file" name="audio" style="position: relative; z-index: 1;">
                                                </div>
                                        </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3" style="margin-top:44px;">
                                @if($data->isAudioTab == 0)
                                <audio style="width: 223px;" controls>
                                    <source src="{{url('audio')}}/{{$data->audio}}" type="audio/mpeg">
                                </audio>
                                @endif
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label>{{__('label.description')}}</label>
                                    <textarea name="description" class="form-control" rows="3" placeholder="Address Hear ...">{{$data->description}}</textarea>
                                   
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
                                        <img  src="{{$data->image}}" height="120px" width="120px" id="preview-image-before-upload">
                                        <input type="hidden" name="old_thumbnail" value="@if($data){{$data->image}}@endif"> 
                                    </div>
                                </div>
                            </div>
                        </div>


                            <div class="border-top mt-2 pt-3 text-right">
                                <button type="button" class="btn btn-default mw-120" onclick="edit_video()">{{__('label.update')}}</button>
                                <a href="{{route('audio.index')}}" class="btn btn-cancel mw-120 ml-2">{{__('label.cancel')}}</a>
                                <input type="hidden" name="_method" value="PATCH">
                            </div>
                        </form>
                    </div>
                </div>
    
           
    @endsection
    
    @section('pagescript')
    <script>
        
        $(document).ready(function() {


           

          


        $("#type").change(function() {
            $(this).find("option:selected").each(function() {
                var optionValue = $(this).attr("value");
                if (optionValue) {
                    $(".box").not("." + optionValue).hide();
                    $("." + optionValue).show();
                } else {
                    $(".box").hide();
                }
            });
        }).change();
        });
		
        function edit_video(){
                $("#dvloader").show();
                var formData = new FormData($("#video")[0]);
                $.ajax({
                    type:'POST',
                    url:'{{ route("audio.update" ,[$data->id]) }}',
                    data:formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success:function(resp){
                        $("#dvloader").hide();
                        get_responce_message(resp, 'video', '{{ route("audio.index") }}');
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $("#dvloader").hide();
                        toastr.error(errorThrown.msg,'failed');         
                    }
                });
            }
	</script>
        
    @endsection</h1>