@section('page_title',  __('label.edit_page'))
<!DOCTYPE html>
<html lang="en">
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
        <title>General_Project</title>
        
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
                <h1 class="page-title-sm">{{__('label.edit_page')}}</h1>

                <div class="border-bottom row mb-3">
                    <div class="col-sm-10">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('page.index') }}">{{__('label.page')}}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                {{__('label.edit_page')}}
                            </li>
                        </ol>
                    </div>
                    <div class="col-sm-2 d-flex align-items-center justify-content-end">
                        <a href="{{ route('page.index') }}" class="btn btn-default mw-120" style="margin-top:-14px">{{__('label.page')}}</a>
                    </div>
                </div>

                <div class="card custom-border-card mt-3">
                    <form name="page" id="page_update" enctype="multipart/form-data" autocomplete="off">				 
                        <input type="hidden" name="id" value="@if($data){{$data->id}}@endif">
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label>{{__('label.title')}}</label>
                                    <input name="title" type="text" class="form-control" id="title"value="@if($data){{$data->title}}@endif" placeholder="{{__('label.please_enter_title')}}" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label>{{__('label.description')}}</label>
                                    <textarea class="form-control" name="description" id="summernote">@if($data){{$data->description}}@endif</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="border-top mt-2 pt-3 text-right">
                            <button type="button" class="btn btn-default mw-120" onclick="edit_page()">{{__('label.update')}}</button>
                            <a href="{{route('page.index')}}" class="btn btn-cancel mw-120 ml-2">{{__('label.cancel')}}</a>
                            <input type="hidden" name="_method" value="PATCH">
                        </div>
                    </form>
                </div>

            </div>
        </div>
        <div style="display:none" id="dvloader"><img src="{{ asset('assets/imgs/loading.gif')}}" /></div>

        <script type="text/javascript">
            function edit_page(){
                $("#dvloader").show();
                var formData = new FormData($("#page_update")[0]);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    enctype: 'multipart/form-data',
                    type: 'POST',
                    url: '{{route("page.update", [$data->id])}}',
                    data: formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success:function(resp){
                        $("#dvloader").hide();
                        get_responce_message(resp, 'page_update', '{{ route("page.index") }}');
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $("#dvloader").hide();
                        toastr.error(errorThrown.msg,'failed');         
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
            
            $(".side-toggle").click(function () {
                $(".sidebar").toggleClass("hide-sidebar");
                $(".right-content").toggleClass("right-content-0");
            });
        </script>
        <!-- Toastr -->
        <script src="{{ asset('/assets/js/toastr.min.js')}}"></script>
    </body>
</html>