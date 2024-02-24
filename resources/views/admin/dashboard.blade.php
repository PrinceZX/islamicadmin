@extends('layout.page-app')

@section('page_title',  __('label.dashboard'))

@section('content')
  
    @include('layout.sidebar')

    <div class="right-content">
        @include('layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.dashboard')}}</h1>

            <div class="row counter-row">
                <div class="col-6 col-sm-4 col-md col-lg-4 col-xl">
                    <div class="db-color-card user-card">
                        <img src="{{ asset('assets/imgs/user-brown.png') }}" alt="" class="card-icon" />
                        <div class="dropdown dropright">
                            <a href="#" class="btn head-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="{{ asset('assets/imgs/dot.png') }}" class="dot-icon" />
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="{{ route('user.index')}}" style="color: #A98471;">{{__('label.view_all')}}</a>
                            </div>
                        </div>
                        <h2 class="counter">
                            {{$UserCount ?? 0}}
                            <span>{{__('label.user')}}</span>
                        </h2>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md col-lg-4 col-xl">
                    <div class="db-color-card cate-card">
                        <img src="{{ asset('assets/imgs/categories-purple.png') }}" alt="" class="card-icon" />
                        <div class="dropdown dropright">
                            <a href="#" class="btn head-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="{{ asset('assets/imgs/dot.png') }}" class="dot-icon" />
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="{{ route('category.index')}}" style="color: #736AA6">{{__('label.view_all')}}</a>
                            </div>
                        </div>
                        <h2 class="counter">
                            {{$CategoryCount ?? 0}}
                            <span>{{__('label.category')}}</span>
                        </h2>
                    </div>
                </div>
                
                <div class="col-6 col-sm-4 col-md col-lg-4 col-xl">
                    <div class="db-color-card">
                        <img src="{{ asset('assets/imgs/video.png') }}" alt="" class="card-icon" />
                            <div class="dropdown dropright">
                                <a href="#" class="btn head-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <img src="{{ asset('assets/imgs/dot.png') }}" class="dot-icon" />
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item" href="{{ route('video.index') }}" style="color: #6cb373;">{{__('label.view_all')}}</a>
                                </div>
                            </div>
                            <h2 class="counter">
                                {{$VideoCount ?? 0}}
                                <span>{{__('label.video')}}</span>
                            </h2>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md col-lg-4 col-xl">
                    <div class="db-color-card rent_video-card">
                        <img src="{{ asset('assets/imgs/album-red.png') }}" alt="" class="card-icon" style="color:#6db3c6;" />
                        <div class="dropdown dropright">
                            <a href="#" class="btn head-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="{{ asset('assets/imgs/dot.png') }}" class="dot-icon" />
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="{{ route('album.index') }}" style="color: #692705">{{__('label.view_all')}}</a>
                            </div>
                        </div>
                        <h2 class="counter">
                            {{$AlbumCount ?? 0}}
                            <span>{{__('label.album')}}</span>
                        </h2>
                    </div>
                </div>

                <!-- <div class="col-6 col-sm-4 col-md col-lg-4 col-xl">
                    <div class="db-color-card category-card">
                        <img src="{{ asset('assets/imgs/language_color.png') }}" alt="" class="card-icon" />
                        <div class="dropdown dropright">
                            <a href="#" class="btn head-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="{{ asset('assets/imgs/dot.png') }}" class="dot-icon" />
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="{{ route('language.index')}}" style="color: #9D0BB1">{{__('label.view_all')}}</a>
                            </div>
                        </div>
                        <h2 class="counter">
                        {{$LanguageCount ?? 0}}
                        <span>{{__('label.language')}}</span>
                        </h2>
                    </div>
                </div> -->

            </div>

            <div class="row counter-row">
                <div class="col-6 col-sm-4 col-md col-lg-4 col-xl">
                    <div class="db-color-card subscribers-card">
                        <img src="{{ asset('assets/imgs/plan_earnings.png') }}" alt="" class="card-icon" />
                        <div class="dropdown dropright">
                            <a href="#" class="btn head-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="{{ asset('assets/imgs/dot.png') }}" class="dot-icon" />
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="{{ route('transaction.index')}}" style="color: #530899">{{__('label.view_all')}}</a>
                            </div>
                        </div>
                        <h2 class="counter">
                        {{currency_code()}}{{no_format($EarningsCount ?? 0)}}

                        <span>{{__('label.earnings')}}</span>
                        </h2>
                    </div>
                </div>

                <div class="col-6 col-sm-4 col-md col-lg-4 col-xl">
                    <div class="db-color-card green-card">
                        <img src="{{ asset('assets/imgs/split_transaction.png') }}" alt="" class="card-icon" />
                        <div class="dropdown dropright">
                            <a href="#" class="btn head-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="{{ asset('assets/imgs/dot.png') }}" class="dot-icon" />
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="{{ route('transaction.index')}}" style="color: #245c1c">{{__('label.view_all')}}</a>
                            </div>
                        </div>
                        <h2 class="counter">
                        {{currency_code()}}{{no_format($CurrentMounthCount ?? 0)}}

                        <span>{{__('label.this_month')}}</span>
                        </h2>
                    </div>
                </div>
              
                
                <div class="col-6 col-sm-4 col-md col-lg-4 col-xl">
                    <div class="db-color-card plan-card">
                        <img src="{{ asset('assets/imgs/plan_color.png') }}" alt="" class="card-icon" />
                        <div class="dropdown dropright">
                            <a href="#" class="btn head-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="{{ asset('assets/imgs/dot.png') }}" class="dot-icon" />
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="{{ route('package.index')}}" style="color: #201f1e">{{__('label.view_all')}}</a>
                            </div>
                        </div>
                        <h2 class="counter">
                        {{$PackageCount ?? 0}}
                        <span>{{__('label.package')}}</span>
                        </h2>
                    </div>
                </div>
                
               
            </div>

            <div class="row">
                <div class="col-12 col-xl-8">

                    <div class="box-title">
                        <h2 class="title">{{__('label.recently_join_user')}}</h2>
                        <a href="{{ route('user.index') }}" class="btn btn-link">{{__('label.view_all')}}</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{__('label.full_name')}}</th>
                                    <th>{{__('label.email')}}</th>
                                    <th>{{__('label.mobile_number')}}</th>
                                    <th>{{__('label.type')}}</th>
                                    <th>{{__('label.date')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($recent_user))
                                    @foreach ($recent_user as $value)
                                    <tr>
                                        <td>
                                            <span class="avatar-control">
                                                <img src="{{ $value['image'] }}" class="avatar-img">
                                               
                                                @if(isset($value->full_name) && $value->full_name != "")
                                                    {{$value->full_name}}
                                                @else
                                                -
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            @if($value->email)
                                                {{$value->email}}
                                            @else($value->email == 'null')
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($value->mobile_number)
                                                {{$value->country_code}}{{$value->mobile_number}}
                                            @else($value->mobile_number == 'null')
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if ($value->type == 1)
                                                {{__('label.otp')}}
                                            @elseif($value->type == 2)
                                                {{__('label.social')}}
                                            @elseif($value->type == 3)
                                                {{__('label.normal')}}
                                            @else 
                                                -
                                            @endif
                                        </td>
                                        <td>{{ date("Y-m-d", strtotime($value->created_at));}}</td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                     
                    <div class="box-title">
                        <h2 class="title">{{__('label.popular_artist')}}</h2>
                        <a href="{{ route('artist.index')}}" class="btn btn-link">{{__('label.view_all')}}</a>
                    </div>
                    <div class="row artist-row">
                        @if(isset($popular_artist) && $popular_artist != null)
                            @foreach ($popular_artist as $value)
                                <div class="col-6 col-md-3">
                                    <div class="artist-grid-card">
                                        <span class="avatar-control">
                                        <img src="{{ $value['image'] }}" class="img-thumbnail" style="height: 180px; width: 100%; border-radius: 25px">

                                        </span>
                                        <h3 class="name" style="display: inline-block; text-overflow:ellipsis; white-space:nowrap; overflow:hidden; width:100%;">{{$value->name}}</h3>
                                        <p class="details">{{$value->bio}}</p>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>


                </div>

                <div class="col-12 col-xl-4">
                    <div class="video-box">
                        <div class="box-title mt-0">
                            <h2 class="title">{{__('label.most_view_video')}}</h2>
                                <a href="{{route('video.index')}}" class="btn btn-link">{{__('label.view_all')}}</a>
                        </div>
                        @if(isset($most_view_video) && $most_view_video != null)
                        <div class="p-3 bg-white mt-4">
                            <img src="{{ $most_view_video->image }}" class="img-fluid d-block mx-auto img-thumbnail" style="height: 300px; width: 100%;"/>
                            <div class="box-title box-border-0">
                            <h5 class="f600" style="display: inline-block; text-overflow:ellipsis; white-space:nowrap; overflow:hidden; width:75%;">{{ $most_view_video['name']}}</h5>
                            <div class="d-flex justify-content-between">
                                <i data-feather="eye" style="color:#4e45b8" class="mr-3"></i> {{no_format($most_view_video['v_view'])}}
                            </div>
                            </div>
                            <div class="details">
                            <span>{{ string_cut($most_view_video['description'],110) }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                   
          
                </div>
            </div>
        </div>
    </div>
@endsection
