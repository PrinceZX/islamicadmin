<header class="header">

    <div class="title-control">
        <button class="btn side-toggle">
            <span></span>
            <span></span>
            <span></span>
        </button>
        
        <?php  $setting = setting(); ?>
        @if(isset($setting) && $setting['app_name'] == "")
        <a href="{{route('admin.dashboard')}}" style="color:#4e45b8;" class="side-logo">
            <h3>{{ env('APP_NAME')}}</h3>
        </a>
        @else
        <a href="{{route('admin.dashboard')}}" style="color:#4e45b8;" class="side-logo">
            <h3>{{$setting['app_name']}}</h3>
        </a>
        @endif   
        <h1 class="page-title">@yield('page_title')</h1>

            
        
    </div>

    <div class="head-control">
        <!-- <div class="dropdown dropright">
            <a href="#" class="btn head-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img src="{{asset('/assets/imgs/languages.png')}}">
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item" href="{{route('language',1)}}">{{__('label.arabic')}}</a>
                <a class="dropdown-item" href="{{route('language',2)}}">{{__('label.english')}}</a>
                <a class="dropdown-item" href="{{route('language',3)}}">{{__('label.hindi')}}</a>

            </div>
        </div> -->
        <div class="dropdown dropright">
            <a href="#" class="btn" title="Logout" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img src="{{ asset('assets/imgs/Profile.png') }}" class="avatar-img"/>
            </a>

            <div class="dropdown-menu p-2 mt-2" aria-labelledby="dropdownMenuLink">
                <div>
                   <?php $data = adminData(); if($data){echo $data['user_name'] ?: "";} ?>
                   <br><hr class="mt-2">
                   <?php $data =adminData(); if($data){echo $data['email'] ?: "";} ?>
                </div><hr class="mt-2">
                <a class="dropdown-item" href="{{ route('admin.logout')}}" style="color:#4E45B8;"><span><img src="{{ asset('assets/imgs/Logout-sm.png') }}" class="mr-2"></span>{{__('label.logout')}}</a>
            </div>
            
        </div>
        
    </div>

</header>