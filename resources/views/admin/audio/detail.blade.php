@extends('layout.page-app')

@section('page_title',  __('Audio Details'))

@section('content')
  @include('layout.sidebar')
  
  <div class="right-content">
    @include('layout.header')

    <div class="body-content">
  <!-- mobile title -->
  <h1 class="page-title-sm">@yield('title')</h1>

  <div class="border-bottom row mb-3">
    <div class="col-sm-10">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('video.index') }}">{{__('label.video')}}</a></li>
        <li class="breadcrumb-item"><a href="">{{__('label.details')}}</a></li>
        

      </ol>
    </div>
    <div class="col-sm-2 d-flex align-items-center justify-content-end">
          <a href="{{ route('video.index') }}" class="btn btn-default mw-120" style="margin-top: -14px;">{{__('label.video_list')}}</a>
        </div>
  </div>
  <div class="card custom-border-card">
        <table class="table table-striped table-hover table-bordered w-75 text-center" style="margin-left:auto; margin-right:auto">
            <thead>
                <tr class="table-info">
                    <th colspan="2">{{__('label.details')}} </th>
                </tr>
            </thead>
            <tbody >
                <tr>
                    <td>Name</td>
                    <td>{{$detail->name}}</td>
                </tr>
                <tr>
                    <td>{{__('label.artist')}}</td>
                    <td>
                        {{ $artist->name}}
                    </td>
                </tr>
                <tr>
                    <td>{{__('label.user')}}</td>
                    <td>
                        @if($user !=null)
                            {{ $user->full_name}}
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>{{__('label.category')}}</td>
                    <td>
                        {{ $category->name}}
                    </td>
                </tr>
                <tr>
                    <td>{{__('label.image')}}</td>
                    <td>
                        <img src="{{ $detail->image }}" class="img-thumbnail" height="100px" width="100px">
                    </td>
                </tr>
              
                <tr>
                    <td>{{__('label.IS Paid')}}</td>
                    <td>
                        @if($detail->is_paid == 0)
                            {{__('label.free')}}
                        @else 
                            {{__('label.paid')}}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>{{__('label.description')}}</td>
                    <td>
                        @if($detail->description)
                            {{$detail->description}}                            
                        @else 
                            -
                        @endif
                    </td>
                </tr>
               
                <tr>
                    <td>{{__('label.Feature')}}</td>
                    <td>
                        @if($detail->is_feature == 0)
                            No
                        @else 
                            Yes
                        @endif
                    </td>
                </tr>

                <tr>
                    <td>Audio URL</td>
                    <td>
                        {{ $detail->audio}}                           
                    </td>
                </tr>
                <tr>
                    <td>{{__('label.view')}}</td>
                    <td>
                        {{ $detail->v_view}}
                    </td>
                </tr>
               
            </tbody>
        </table>
    </div>


</div>
</div>
@endsection

@section('pagescript')


@endsection