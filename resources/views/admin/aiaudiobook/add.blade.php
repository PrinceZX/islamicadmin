@extends('layout.page-app')
    
@section('page_title',  __('Add AI Audio Book'))

@section('content')
    @include('layout.sidebar')

    <div class="right-content">
        @include('layout.header')
        <style>
            .loading-button {
                position: relative;
            }

            .spinner {
                display: none;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 20px;
                height: 20px;
                border: 3px solid rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                border-top: 3px solid #3498db;
                animation: spin 1s linear infinite;
            }

            @keyframes spin {
                0% { transform: translate(-50%, -50%) rotate(0deg); }
                100% { transform: translate(-50%, -50%) rotate(360deg); }
            }

        </style>
        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">Add Audio</h1>
            <div class="border-bottom row mb-3">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('aiaudiobook.index') }}">AI Audio Book</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Add AI Audio Book
                        </li>
                    </ol>
                </div>
                <div class="col-sm-2 d-flex align-items-center justify-content-end">
                    <a href="{{ route('aiaudiobook.index') }}" class="btn btn-default mw-120" style="margin-top:-14px">AI Audio List</a>
                </div>
            </div>
            
            <div class="card custom-border-card mt-3">
                <div class="card-body">
                    <form enctype="multipart/form-data" id="aiAudioBook" autocomplete="off">
                        @csrf
                        <input type="hidden" name="id" value="">

                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="name">{{__('label.name')}}</label>
                                    <input type="text" name="name" class="form-control" placeholder="{{__('label.enter_audio_name')}}">
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
                            <div class="col-md-3 mb-3">
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
                            <div class="col-md-3 mb-3">
                                <div class="form-group IS Paid">
                                    <label for="download">{{__('label.IS Paid')}}</label>
                                    <select class="form-control" name="is_paid">
                                    <option value="0">{{__('label.free')}}</option>
                                    <option value="1">{{__('label.paid')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="form-group Is_Download">
                                    <label for="download">{{__('label.Feature')}}</label>
                                    <select class="form-control" name="is_feature">
                                    <option value="0">{{__('label.no')}}</option>
                                    <option value="1">{{__('label.yes')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="form-group">
                                    <label for="name">{{__('label.price')}}</label>
                                    <input type="text" name="price" id="priceInput" class="form-control" placeholder="{{__('label.enter_price')}}">
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
                            <div class="col-md-12 mb-3">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="audio-tab" data-toggle="tab" href="#audio" role="tab" onclick="checkActiveTab(this)" aria-controls="audio"
                                        aria-selected="true">Upload Audio</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="aiaudio-tab" data-toggle="tab" href="#aiaudio" role="tab" onclick="checkActiveTab(this)" aria-controls="aiaudio"
                                        aria-selected="false">AI Content Audio</a>
                                    </li>
                                    <input type="hidden" name="isAudioTab" id="isAudioTab" value="0">
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="audio" role="tabpanel" aria-labelledby="audio-tab">
                                               
                                        <div class="server_video box form-group col-lg-6 videoLink" style="margin-top:44px;">
                                            <div id="serverVideo" style="display: block;">
                                                <label for="input-1">Upload Audio</label>
                                                <div id="filelist"></div>
                                                <div id="container" style="position: relative;">
                                                    <div class="form-group">
                                                        <div id="audio-tab-div">
                                                            <input type="file"name="audio" id="audio_converted_url" style="position: relative; z-index: 1;">
                                                        </div>  
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="tab-pane fade" id="aiaudio" role="tabpanel" aria-labelledby="aiaudio-tab">
                                        <div class="wrapper" style="margin-top:44px;">
                                            <div class="form-row">
                                                <div class="col-md-12 mb-3">
                                                    <div class="form-group">
                                                        <label>{{__('label.audio_content')}}</label>
                                                        <textarea name="audio_content" id="audio_content" class="form-control" rows="3" placeholder="Enter Content ..."></textarea>
                                                    
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-3 mb-3">
                                                    <div class="form-group">
                                                        <label for="category_id">{{__('label.language')}}</label>
                                                        <select class="form-control" id="voice_language" style="width:100%!important;" onchange="setTypes(this)">
                                                            <option value="english">English</option>
                                                            <option value="hindi">Hindi</option>
                                                            <option value="arabic">Arabic</option>
                                                            <option value="french">French</option>
                                                            <option value="urdu">Urdu</option>
                                                            <option value="russian">Russian</option>
                                                            <option value="malay">Malay</option>
                                                            <option value="spanish">Spanish</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <div class="form-group">
                                                        <label for="category_id">Types</label>
                                                        <select class="form-control" id="voice_name" style="width:100%!important;" >
                                                            <option value="en-US-SaraNeural">Sara --- en-US-SaraNeural</option>
                                                            <option value="en-US-SteffanNeural">Steffan --- en-US-SteffanNeural</option>
                                                            <option value="en-US-MichelleNeural">Michelle --- en-US-MichelleNeural</option>
                                                            <option value="en-US-JaneNeural">Jane --- en-US-JaneNeural</option>
                                                            <option value="en-US-JacobNeural">Jacob --- en-US-JacobNeural</option>
                                                            <option value="en-US-EricNeural">Eric --- en-US-EricNeural</option>
                                                            <option value="en-US-AshleyNeural">Ashley --- en-US-AshleyNeural</option>
                                                            <option value="en-US-GuyNeural">Guy --- en-US-GuyNeural</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 mb-3">
                                                    <div id="aiaudio-tab-div">
                                                        
                                                    </div>
                                                    
                                                    <button type="button" class="btn btn-default mw-120 loading-button" id="convert_audio_btn" style="margin-top: 30px;" onclick="convert_audio()">{{__('label.convert')}}</button>
                                                    <div class="spinner" id="spinner"></div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div id="converted_audio"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                
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
                            <button type="button" class="btn btn-default mw-120" onclick="save_audio_book()">{{__('label.save')}}</button>
                            <a href="{{route('aiaudiobook.index')}}" class="btn btn-cancel mw-120 ml-2">{{__('label.cancel')}}</a>
                        </div>
                    </form>
                </div>
            </div>
    </div>
@endsection

@section('pagescript')
    <script>
        var assetBaseUrl = "{{ asset('') }}";
    </script>
    <script>
        function checkActiveTab(event){
            if(event.id == 'aiaudio-tab'){
                var html = '<input type="hidden" name="audio"  id="audio_converted_url" >';
                $("#aiaudio-tab-div").html(html);
                $("#audio-tab-div").html('');
                $("#isAudioTab").val(1);
            }else{
                var html = '<input type="file"name="audio" id="audio_converted_url" style="position: relative; z-index: 1;">';
                $("#audio-tab-div").html(html);
                $("#aiaudio-tab-div").html('');
                $("#audio_content").val('');
                $("#isAudioTab").val(0);
            }
        }
    </script>

	<script>
        function setTypes(){
            var voice_language = $("#voice_language").val();
            var html = '';
            if(voice_language == 'english'){
                html = html+'<option value="en-US-SaraNeural">Sara --- en-US-SaraNeural</option>';
                html = html+'<option value="en-US-SteffanNeural">Steffan --- en-US-SteffanNeural</option>';
                html = html+'<option value="en-US-MichelleNeural">Michelle --- en-US-MichelleNeural</option>';
                html = html+'<option value="en-US-JaneNeural">Jane --- en-US-JaneNeural</option>';
                html = html+'<option value="en-US-JacobNeural">Jacob --- en-US-JacobNeural</option>';
                html = html+'<option value="en-US-EricNeural">Eric --- en-US-EricNeural</option>';
                html = html+'<option value="en-US-AshleyNeural">Ashley --- en-US-AshleyNeural</option>';
                html = html+'<option value="en-US-GuyNeural">Guy --- en-US-GuyNeural</option>';
            }else if(voice_language == 'hindi'){
                html = html+'<option value="hi-IN-SwaraNeural">hi-IN-SwaraNeural (Female)-- Hindi</option>';
                html = html+'<option value="hi-IN-MadhurNeural">hi-IN-MadhurNeural (Male)-- Hindi</option>';
            }else if(voice_language == 'arabic'){
                html = html+'<option value="ar-AE-FatimaNeural">ar-AE-FatimaNeural (Female)-- arabic</option>';
                html = html+'<option value="ar-AE-HamdanNeural">ar-AE-HamdanNeural (Male)-- arabic</option>';
            }else if(voice_language == 'french'){
                html = html+'<option value="fr-FR-DeniseNeural">fr-FR-DeniseNeural (Female)-- french</option>';
                html = html+'<option value="fr-FR-HenriNeural">fr-FR-HenriNeural (Male)-- french</option>';
                html = html+'<option value="fr-FR-AlainNeural">fr-FR-AlainNeural (Male)-- french</option>';
                html = html+'<option value="fr-FR-BrigitteNeural">fr-FR-BrigitteNeural (Female)-- french</option>';
            }else if(voice_language == 'urdu'){
                html = html+'<option value="ur-IN-GulNeural">ur-IN-GulNeural (Female)-- urdu</option>';
                html = html+'<option value="ur-IN-SalmanNeural">ur-IN-SalmanNeural (Male)-- urdu</option>';
            }else if(voice_language == 'russian'){
                html = html+'<option value="ru-RU-SvetlanaNeural">ru-RU-SvetlanaNeural (Female)-- russian</option>';
                html = html+'<option value="ru-RU-DmitryNeural">ru-RU-DmitryNeural (Male)-- russian</option>';
                html = html+'<option value="ru-RU-DariyaNeural">ru-RU-DariyaNeural (Female)-- russian</option>';
            }else if(voice_language == 'malay'){
                html = html+'<option value="ms-MY-YasminNeural">ms-MY-YasminNeural (Female)-- malay</option>';
                html = html+'<option value="ms-MY-OsmanNeural">ms-MY-OsmanNeural (Male)-- malay</option>';
            }else if(voice_language == 'spanish'){
                html = html+'<option value="es-ES-ElviraNeural">es-ES-ElviraNeural (Female)-- spanish</option>';
                html = html+'<option value="es-ES-AlvaroNeural">es-ES-AlvaroNeural (Male)-- spanish</option>';
                html = html+'<option value="es-ES-AbrilNeural">es-ES-AbrilNeural (Female)-- spanish</option>';
                html = html+'<option value="es-ES-ArnauNeural">es-ES-ArnauNeural (Male)-- spanish</option>';
            }
            $("#voice_name").html(html);
        }
        $(document).ready(function() {
            $('#priceInput').on('input', function() {
                var price = $(this).val();
                // Use a regular expression to allow only valid price format
                if (!/^\d+(\.\d{1,2})?$/.test(price)) {
                    // Invalid price, clear the input
                    $(this).val('');
                }
            });
        });

        function convert_audio(){
            var voice_name = $("#voice_name").val();
            var audio_content = $("#audio_content").val();
            if(audio_content != '' && voice_name != ''){
                // Define the JSON data
                var jsonData = {
                    "voice_name": voice_name,
                    "text": audio_content
                };
                $("#convert_audio_btn").prop("disabled", true);
                $("#spinner").show();
                $.ajax({
                    'async': true,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route("aiaudiobook.getconvertedaudio") }}', // Replace with your actual route URL
                    method: 'POST', // or 'GET' depending on your needs
                    dataType:'json',
                    data: {
                    "voice_name": voice_name,
                    "text": audio_content
                    },
                    success: function(response) {
                        // Handle the success response
                        if(response.status == 200){
                           var filename =  response.filename;
                           $("#audio_converted_url").val(filename);
                            var filepath = 'audio/' + filename;
                            var assetUrl = assetBaseUrl + filepath;

                            // Create a new audio element
                            var audio = $('<audio controls><source src="'+assetUrl+'" type="audio/mpeg">Your browser does not support the audio element.</audio>');

                            // Append the audio element to a div with the id "audioContainer"
                            $('#converted_audio').html(audio);

                           //$("#converted_audio").html('<video controls=""  name="media"><source src="'+assetUrl+'" type="audio/mpeg"></video>');
                        }else{
                            toastr.error('error',response.errors); 
                        }
                        $("#convert_audio_btn").prop("disabled", false);
                        $("#spinner").hide();
                    },
                    error: function(xhr, status, error) {
                        // Handle the error response
                        $("#convert_audio_btn").prop("disabled", false);
                        $("#spinner").hide();
                        toastr.error('error',error); 
                        
                    }
                });
            }else{
                $("#convert_audio_btn").prop("disabled", false);
                $("#spinner").hide();
                toastr.error('Audio content should not be blank.','failed');   
            }
        }
        
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
		function save_audio_book(){
			$("#dvloader").show();
			var formData = new FormData($("#aiAudioBook")[0]);
			$.ajax({
				type:'POST',
				url:'{{ route("aiaudiobook.store") }}',
				data:formData,
				cache:false,
				contentType: false,
				processData: false,
				success:function(resp){
					$("#dvloader").hide();
                    
					get_responce_message(resp, 'aiAudioBook', '{{ route("aiaudiobook.index") }}');
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					$("#dvloader").hide();
					toastr.error(errorThrown.msg,'failed');         
				}
			});
		}
	</script>
@endsection