<div class="sidebar">
    <div class="side-head">
        <?php  $setting = setting(); ?>
        @if(isset($setting) && $setting['app_name'] == "")
        <a href="{{route('admin.dashboard')}}" style="color:#4e45b8;">
            <h3>{{env('APP_NAME') }}</h3>
        </a>
        @else
        <a href="{{route('admin.dashboard')}}" style="color:#4e45b8;">
            <h3>{{$setting['app_name']}}</h3>
        </a>
        @endif
        <button class="btn side-toggle">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
    <ul class="side-menu mt-4">
        <li class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard')}}">
                <img class="menu-icon" src="{{asset('/assets/imgs/dashboard.png')}}" alt="" />
                <span>{{__('label.dashboard')}}</span>
            </a>
        </li>
        <li class="{{ (request()->routeIs('user*')) ? 'active' : '' }}">
            <a href="{{ route('user.index')}}">
                <img class="menu-icon" src="{{ asset('assets/imgs/user.png') }}" alt="" />
                <span>{{__('label.user')}}</span>
            </a>
        </li>
        @if(auth()->guard('admin')->user()->permissions_role == "super_admin")
        <li class="{{ (request()->routeIs('admins*')) ? 'active' : '' }}">
            <a href="{{ route('admins.index')}}">
                <img class="menu-icon" src="{{ asset('assets/imgs/Profile.png') }}" alt="" />
                <span>Admins</span>
            </a>
        </li>
        @endif
        <li class="dropdown {{ (request()->routeIs('category*')) ? 'active' : '' }} {{ (request()->routeIs('artist*')) ? 'active' : '' }} {{ (request()->routeIs('album*')) ? 'active' : '' }} {{ (request()->routeIs('page*')) ? 'active' : '' }}">
            <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img class="menu-icon" src="{{ asset('assets/imgs/subcription.png') }}" alt="" />
                <span>Basic Settings </span>
            </a>
            <ul class="dropdown-menu side-submenu {{ (request()->routeIs('category*')) ? 'show' : '' }} {{ (request()->routeIs('artist*')) ? 'show' : '' }} {{ (request()->routeIs('album*')) ? 'show' : '' }} {{ (request()->routeIs('page*')) ? 'show' : '' }}">
                <li class="{{ (request()->routeIs('category.*')) ? 'active' : '' }}">
                    <a href="{{ route('category.index') }}" class="dropdown-item">
                        <img class="submenu-icon" src="{{ asset('assets/imgs/category.png') }}" alt="" />
                        <span>{{__('label.category')}}</span>
                    </a>
                </li>
                <li class="{{ (request()->routeIs('artist.*')) ? 'active' : '' }}">
                    <a href="{{ route('artist.index') }}" class="dropdown-item">
                        <img class="submenu-icon" src="{{ asset('assets/imgs/artist.png') }}" alt="" />
                        <span>{{__('label.artist')}}</span>
                    </a>
                </li>
                <li class="{{ (request()->routeIs('album.*')) ? 'active' : '' }}">
                    <a href="{{ route('album.index') }}" class="dropdown-item">
                        <img class="submenu-icon" src="{{ asset('assets/imgs/album.png') }}" alt="" />
                        <span>{{__('label.album')}}</span>
                    </a>
                </li>
                <!-- <li class="{{ (request()->routeIs('language*')) ? 'active' : '' }}">
                    <a href="{{ route('language.index') }}" class="dropdown-item">
                        <img class="submenu-icon" src="{{ asset('assets/imgs/languages.png') }}" alt="" />
                        <span>{{__('label.language')}}</span>
                    </a>
                </li> -->
                <li class="{{ (request()->routeIs('page*')) ? 'active' : '' }}">
                    <a href="{{ route('page.index') }}" class="dropdown-item">
                        <img class="submenu-icon" src="{{ asset('assets/imgs/pages.png') }}" alt="" />
                        <span>{{__('label.page')}}</span>
                    </a>
                </li>
            </ul>
        </li>

    
        <li class="{{ (request()->routeIs('video*')) ? 'active' : '' }}">
            <a href="{{ route('video.index') }}">
                <img class="menu-icon" src="{{ asset('assets/imgs/videos.png') }}" alt="" />
                <span>{{__('label.video')}}</span>
            </a>
        </li>

        <li class="{{ (request()->routeIs('audio*')) ? 'active' : '' }}">
            <a href="{{ route('audio.index') }}">
                <img class="menu-icon" src="{{ asset('assets/imgs/videos.png') }}" alt="" />
                <span>Audio</span>
            </a>
        </li>

        <li class="{{ (request()->routeIs('aiaudiobook*')) ? 'active' : '' }}">
            <a href="{{ route('aiaudiobook.index') }}">
                <img class="menu-icon" src="{{ asset('assets/imgs/videos.png') }}" alt="" />
                <span>AI Audio Book</span>
            </a>
        </li>

        <li class="side_line {{ (request()->routeIs('comment*')) ? 'active' : '' }}">
            <a href="{{ route('comment.index') }}">
                <img class="menu-icon" src="{{ asset('assets/imgs/video_comment.png') }}" alt="" />
                <span>{{__('label.Comment')}}</span>
            </a>
        </li>
       
        <li class="{{ (request()->routeIs('notification*')) ? 'active' : '' }}">
            <a href="{{ route('notification.index') }}">
                <img class="menu-icon" src="{{ asset('assets/imgs/Notification.png') }}" alt="" />
                <span>{{__('label.notification')}}</span>
            </a>
        </li>

        <li class="dropdown {{ (request()->routeIs('package*')) ? 'active' : '' }} {{ (request()->routeIs('transaction*')) ? 'active' : '' }} {{ (request()->routeIs('payment*')) ? 'active' : '' }}">
            <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img class="menu-icon" src="{{ asset('assets/imgs/subcription.png') }}" alt="" />
                <span> {{__('label.subscription')}} </span>
            </a>
            <ul class="dropdown-menu side-submenu {{ (request()->routeIs('package*')) ? 'show' : '' }}{{ (request()->routeIs('transaction*')) ? 'show' : '' }} {{ request()->is('admin/payment*') ? 'show' : '' }}">
                <li class="{{ (request()->routeIs('package*')) ? 'active' : '' }}">
                    <a href="{{ route('package.index') }}" class="dropdown-item">
                        <img class="submenu-icon" src="{{ asset('assets/imgs/box.png') }}" alt="" />
                        <span> {{__('label.package')}} </span>
                    </a>
                </li>
                <li class="{{ (request()->routeIs('transaction*')) ? 'active' : '' }}">
                    <a href="{{ route('transaction.index') }}" class="dropdown-item">
                        <img class="submenu-icon" src="{{ asset('assets/imgs/transaction_list.png') }}" alt="" />
                        <span> {{__('label.transactions')}} </span>
                    </a>
                </li>
                <li class="{{ (request()->routeIs('payment*')) ? 'active' : '' }}">
                    <a href="{{ route('payment.index') }}" class="dropdown-item">
                        <img class="submenu-icon" src="{{ asset('assets/imgs/finance.png') }}" alt="" />
                        <span> {{__('label.payment')}} </span>
                    </a>
                </li>
                <li class="{{ (request()->routeIs('aiaudio_transaction*')) ? 'active' : '' }}">
                    <a href="{{ route('aiaudio_transaction.index') }}" class="dropdown-item">
                        <img class="submenu-icon" src="{{ asset('assets/imgs/transaction_list.png') }}" alt="" />
                        <span> {{ __('AI Audio Book') }} </span>
                    </a>
                </li>
            </ul>
        </li>
        
        <li class="{{ (request()->routeIs('setting*')) ? 'active' : '' }}">
            <a href="{{ route('setting') }}">
                <img class="menu-icon" src="{{ asset('assets/imgs/support.png') }}" alt="" />
                <span>{{__('label.setting')}}</span>
            </a>
        </li>
       
        <li>
            <a href="{{ route('admin.logout') }}"
               onclick="event.preventDefault();
                       document.getElementById('logout-form').submit();">
                <img class="menu-icon" src="{{url('/')}}/assets/imgs/logout.png" alt="" />
                <span>{{__('label.logout')}}</span>
            </a>

            <form id="logout-form" action="{{ route('admin.logout') }}" method="GET" class="d-none">
                @csrf
            </form>
        </li>
    </ul>
</div>