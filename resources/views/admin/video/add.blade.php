@extends('layout.page-app')
    
@section('page_title',  __('label.add_video'))

@section('content')
    @include('layout.sidebar')

    <div class="right-content">
        @include('layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.add_video')}}</h1>
            <div class="border-bottom row mb-3">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('video.index') }}">{{__('label.video')}}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{__('label.add_video')}}
                        </li>
                    </ol>
                </div>
                <div class="col-sm-2 d-flex align-items-center justify-content-end">
                    <a href="{{ route('video.index') }}" class="btn btn-default mw-120" style="margin-top:-14px">{{__('label.video_list')}}</a>
                </div>
            </div>

            <div class="card custom-border-card mt-3">
                <div class="card-body">
                    <form enctype="multipart/form-data" id="video" autocomplete="off">
                        @csrf
                        <input type="hidden" name="id" value="">

                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="name">{{__('label.name')}}</label>
                                    <input type="text" name="name" class="form-control" placeholder="{{__('label.enter_video_name')}}">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="artist_id">{{__('label.artist')}}</label>
                                    <select class="form-control" style="width:100%!important;" name="artist_id" id="artist_id">
                                        <option value="">Select Artist</option>
                                        @foreach ($artist as $key => $value)
                                        <option value="{{ $value->id}}">
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
                                        <option value="">Select User</option>
                                        @foreach ($user as $key => $value)
                                        <option value="{{ $value->id}}">
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
                                        <option value="{{ $value->id}}">
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
                                    <option value="0">{{__('label.free')}}</option>
                                    <option value="1">{{__('label.paid')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group Is_Download">
                                    <label for="download">{{__('label.Feature')}}</label>
                                    <select class="form-control" name="is_feature">
                                    <option value="0">{{__('label.no')}}</option>
                                    <option value="1">{{__('label.yes')}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row col-lg-12">
                            <div class="form-group col-lg-6">
                                <label for="type">{{__('label.video_upload_type')}}</label>
                                    <select class="form-control" name="video_type" id="type">
                                        <option value="server_video">{{__('label.server_video')}}</option>
                                        <option value="url">{{__('label.external_url')}} </option>
                                        <option value="youtube">{{__('label.youtube_video')}}</option>
                                        <option value="vimeo">{{__('label.vimeo_video')}}</option>
                                    </select>
                            </div>
        
                            <div class="youtube vimeo url box form-group col-lg-6 video_url">
                                <label for="type">{{__('label.urls')}}</label>
                                <input type="text" name="url" class="form-control">
                            </div>
        
                            <div class="server_video box form-group col-lg-6 videoLink">
                                <div id="serverVideo" style="display: block;">
                                    <label for="input-1">{{__('label.upload_video')}}</label>
                                        <div id="filelist"></div>
                                            <div id="container" style="position: relative;">
                                                <div class="form-group">
                                                    <input type="file" id="uploadFile" name="uploadFile" style="position: relative; z-index: 1;">
                                                </div>
                                                <input type="hidden" name="mp3_file_name" id="mp3_file_name" class="form-control">
        
                                                <div class="form-group">
                                                    <a id="upload" class="btn text-white" style="background-color:#4e45b8;">{{__('label.upload_files')}}</a>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label>{{__('label.description')}}</label>
                                    <textarea name="description" class="form-control" rows="3" placeholder="Address Hear ..."></textarea>
                                   
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
                                        <img  src="{{asset('assets/imgs/no_img.png')}}" height="120px" width="120px" id="Uploaded-Image">
                                    </div>
                                </div>
                            </div>
                        </div>
                
                        <div class="border-top mt-2 pt-3 text-right">
                            <button type="button" class="btn btn-default mw-120" onclick="save_video()">{{__('label.save')}}</button>
                            <a href="{{route('video.index')}}" class="btn btn-cancel mw-120 ml-2">{{__('label.cancel')}}</a>
                        </div>
                    </form>
                </div>
            </div>
    </div>
@endsection

@section('pagescript')
	<script>
        
        $(document).ready(function() {


            $('#thumbnail').change(function(){
            let reader = new FileReader();
                reader.onload = (e) => { 
                    $('#preview-image-before-upload').attr('src', e.target.result); 
                }
                reader.readAsDataURL(this.files[0]); 
            });

            $('#landscape').change(function() {
            let reader = new FileReader();
                reader.onload = (e) => {
                    $('#preview-image-before-upload1').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
        });


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
		function save_video(){
			$("#dvloader").show();
			var formData = new FormData($("#video")[0]);
			$.ajax({
				type:'POST',
				url:'{{ route("video.store") }}',
				data:formData,
				cache:false,
				contentType: false,
				processData: false,
				success:function(resp){
					$("#dvloader").hide();
					get_responce_message(resp, 'video', '{{ route("video.index") }}');
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					$("#dvloader").hide();
					toastr.error(errorThrown.msg,'failed');         
				}
			});
		}
	</script>
@endsection