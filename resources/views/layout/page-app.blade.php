<!DOCTYPE html>
<html lang="en" dir="{{(App::isLocale('Arebic') ? 'rtl' : 'ltr')}}">

<head>
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

    <!-- Favicon -->
    <!--<link rel="icon" type="image/png" href="images/favicon.png">-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/fontawesome.min.css">
    <link rel="stylesheet" href="{{asset('/assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('/assets/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{url('/assets/css/toastr.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/assets/css/style.css?v=version()') }}" rel="stylesheet">
    <link href="{{asset('/assets/css/custom.css?v=version()') }}" rel="stylesheet">
    <!-- Select2 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"/>
    <!-- Summer notes --> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css">
    <!-- base_url -->
    <input type="hidden" value="{{URL('')}}" id="base_url">
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
        .db-color-card.subscribers-card {
            background: #c9b7f1;
            color: #530899;
        }
        .db-color-card.rent_video-card {
            background: #dfab91;
            color: #692705;
        }
        .db-color-card.plan-card {
            background: #999898;
            color: #201f1e;
        }
        .db-color-card.category-card {
            background: #e9aaf1;
            color: #9d0bb1;    
        }
        .db-color-card.green-card {
            background: #83cf78;
            color: #245c1c;
        }
        .db-color-card.category-card {
            background: #e9aaf1;
            color: #9d0bb1;    
        }
    </style>
    <!--Custom CSS-->
    <script>
        var globalSiteUrl = '<?php echo $path = url('/'); ?>'
        var serverEnvironment = '<?php echo env('APP_ENV'); ?>'
        var currentRouteName = '<?php echo request()->route()->getName(); ?>'
    </script>
</head>

<body>

    @yield('content')
    <div style="display:none" id="dvloader"><img src="{{ asset('assets/imgs/loading.gif')}}" /></div>
    <!-- Jquery -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> 
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js') }}"></script>
    <script src="{{ asset('/assets/js/js.js')}}"></script>
    <script src="{{ asset('/assets/js/toastr.min.js')}}"></script>
    <script src="{{ asset('/assets/js/jquery.validate.min.js')}}"></script>
    <script src="{{ asset('/assets/js/additional-methods.min.js')}}"></script>
    <!-- chart -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.js"></script>
    <!-- Select2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <!-- Summer notes --> 
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

    <!-- Chunk JS -->
    <!-- 1 file -->
    <script src="{{ asset('/assets/js/plupload.full.min.js')}}"></script>
    <!-- 2 file -->
    <script src="{{ asset('/assets/js/common.js')}}"></script>
    <script>
        
        function get_responce_message(resp, form_name="", url="") {
            if (resp.status == '200') {
                toastr.success(resp.success);
                if(form_name != ""){
                    document.getElementById(form_name).reset();
                }
                if(url != ""){  
                    setTimeout(function() {
                        window.location.replace(url);
                    }, 500);
                }
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

        // Toastr MSG Show
        @if(Session::has('error'))
            toastr.error('{{ Session::get('error') }}');
        @elseif(Session::has('success'))
            toastr.success('{{ Session::get('success') }}');
        @endif

        // Image Change
        $(document).ready(function (e) {
            $('#image').change(function(){
                let reader = new FileReader();
                reader.onload = (e) => { 
                    $('#Uploaded-Image').attr('src', e.target.result); 
                }
                reader.readAsDataURL(this.files[0]); 
            });
        });
    </script>
    @yield('pagescript')
</body>

</html>