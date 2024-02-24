@extends('layout.page-app')

@section('content')
  <div class="h-100">
    <div class="h-100 no-gutters row">

      <div class="d-none d-lg-block h-100 col-lg-5 col-xl-4">
        <div class="left-caption">
          <img src="{{asset('assets/imgs/login.jpg')}}" class="bg-img" />
          <div class="caption">
            <div>
              <!-- logo -->
                <?php  $setting = setting(); ?>
                @if(isset($setting) && $setting['app_name'] == "")
                  <h1>{{ env('APP_NAME') }}</h1>
                @else
                  <h1>{{$setting['app_name']}}</h1>
                @endif
             
              <p class="text">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto quaerat fuga optio voluptatibus ullam
                aliquam consectetur, quam, veritatis facilis dolor id perspiciatis distinctio ratione! Reprehenderit
                rerum
                provident vero praesentium molestiae?
              </p>
            </div>
          </div>
        </div>
      </div>

      <div class="h-100 d-flex login-bg justify-content-center align-items-lg-center col-md-12 col-lg-7 col-xl-8">
        <div class="mx-auto col-sm-12 col-md-10 col-xl-8">
          <div class="py-5 p-3">

            <div class="app-logo mb-4">
              <h3 class="primary-color mb-0 font-weight-bold">{{__('label.login')}}</h3>
            </div>

            <h4 class="mb-0 font-weight-bold">
              <span class="d-block mb-2">{{__('label.welcome_back')}}</span>
              <span>{{__('label.sign_account')}}</span>
            </h4>
            
            <form method="POST" id="login_form">
              <div class="form-row mt-4">
                <div class="col-md-6">
                  <div class="position-relative form-group">
                    <label for="exampleEmail" class="">{{__('label.email')}}</label>
                    <input name="email" id="email" placeholder="Email here..." type="email" class="form-control @error('email') is-invalid @enderror" value="" required autocomplete="email" autofocus>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="position-relative form-group">
                    <label for="examplePassword" class="">{{__('label.password')}}</label>
                    <input name="password" id="password" placeholder="Password here..." type="password" class="form-control @error('password') is-invalid @enderror" value="" required autocomplete="current-password">
                  </div>
                </div>
              </div>
              <div class="custom-control custom-checkbox mr-sm-2">
                <input type="checkbox" class="custom-control-input" id="customControlAutosizing" name="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="custom-control-label" for="customControlAutosizing">{{__('label.logged_in')}}</label>
              </div>
              <div class="form-row mt-4">
                <div class="col-sm-6 text-center text-sm-left">
                  <button class="btn btn-default my-3 mw-120" onclick="save_login()" type="button">{{__('label.login')}}</button>
                  <a class="btn btn-default my-3 mw-120" href="/admin/signup">Sign Up</a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>

    </div>
  </div>
@endsection

@section('pagescript')
<script>
    // Login Form
    function save_login() {
      $("#dvloader").show();
      var formData = new FormData($("#login_form")[0]);
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'POST',
        url: '{{ route("admin.login") }}',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success:function(resp){
          $("#dvloader").hide();
          get_responce_message(resp, 'login_form', '{{ route("admin.dashboard") }}');
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          $("#dvloader").hide();
          toastr.error(errorThrown.msg,'failed');         
        }
      });
    }
  </script>
@endsection
