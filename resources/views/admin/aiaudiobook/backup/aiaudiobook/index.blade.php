
@extends('layout.page-app')

@section('page_title',  __('AI Audio Book'))

@section('content')
  @include('layout.sidebar')
  
  <div class="right-content">
    @include('layout.header')

    <div class="body-content">
  <!-- mobile title -->
  <h1 class="page-title-sm">@yield('title')</h1>

  <div class="border-bottom row mb-3">
    <div class="col-sm-12">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
          AI Audio Book
        </li>
      </ol>
    </div>
  </div>

  <!-- <div class="border-bottom mb-3 pb-3">
    <form class="" action="{{ route('video.index')}}" method="GET">
      <div class="form-row">
        <div class="col-md-1 d-flex align-items-center">
          <label for="type">{{__('label.search')}} :</label>
        </div>
        <div class="col-md-2">
          <div class="form-group">
            <select class="form-control" id="type" name="type">
              <option value="all">{{__('Label.All')}}</option>
                             
            </select>
          </div>
        </div>
        <div class="col-sm-2 ml-4">
          <button class="btn btn-default" type="submit"> {{__('label.search')}} </button>
        </div>
      </div>
    </form>
  </div>  -->
  
 

  <div class="row">

    <div class="col-12 col-sm-6 col-md-4 col-xl-3">
      <a href="{{ route('aiaudiobook.create') }}" class="add-video-btn">
        <img src="{{ asset('assets/imgs/add.png') }}" alt="" class="icon" />
        Add New AI Audio Book
      </a>
    </div>

    @foreach ($data as $key => $value)
      <div class="col-12 col-sm-6 col-md-4 col-xl-3">
        <div class="card video-card">
          <div class="position-relative">
            <img class="card-img-top" src="{{$value->image}}" alt="">
            <audio style="width: 223px;" class="play-btn video" controls>
              <source src="{{url('audio')}}/{{$value->audio}}" type="audio/mpeg">
            </audio>
          </div>
          
          <div class="card-body">
            <h5 class="card-title mr-5">{{$value->name}}</h5>
            <div class="card-details">
              </div>
              <div class="dropdown dropright">
                  <a href="#" class="btn head-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <img src="{{ asset('assets/imgs/dot.png') }}" class="dot-icon" />
                  </a>
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                  <!-- <a class="dropdown-item" href="{{ route('videoDetail', [$value->id])}}">
                          <img src="{{ asset('assets/imgs/view.png') }}" class="dot-icon" />
                             Details
                      </a> -->
                      <a class="dropdown-item" href="{{ route('aiaudiobook.edit', [$value->id])}}">
                          <img src="{{ asset('assets/imgs/edit.png') }}" class="dot-icon" />
                             Edit
                      </a>
                      <a class="dropdown-item" href="{{ route('aiaudiobook.show', $value->id)}}" onclick="return confirm('Are You Sure Delete This AI Audio Book?')">
                          <img src="{{ asset('assets/imgs/trash.png') }}" class="dot-icon" />
                        Delete
                      </a>
                  </div>
              </div>
          </div>
        </div>
      </div>
    @endforeach

    <div class="modal fade" id="videoModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-body p-0 bg-transparent">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="text-dark">&times;</span>
            </button>
            <video controls width="800" height="500" preload='none' poster="" id="theVideo" controlsList="nodownload noplaybackrate" disablepictureinpicture>
              <source src="" type="video/mp4">
            </video>
          </div>
        </div>
      </div>
    </div>

  </div>
  <!-- Pagination -->
  <div class="d-flex justify-content-between align-items-center">
    <div> Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of total {{$data->total()}} entries </div>
    <div class="pb-5"> {{ $data->links('pagination::bootstrap-4') }} </div>
  </div>

</div>
</div>
@endsection

@section('pagescript')
<script>
  

  $(function() {
    $(".video").click(function() {
      var theModal = $(this).data("target"),
        videoSRC = $(this).attr("data-video"),
        videoPoster = $(this).attr("data-image"),
        videoSRCauto = videoSRC + "";

      $(theModal + ' source').attr('src', videoSRCauto);
      $(theModal + ' video').attr('poster', videoPoster);
      $(theModal + ' video').load();
      $(theModal + ' button.close').click(function() {
        $(theModal + ' source').attr('src', videoSRC);
      });
    });
  });

  $("#videoModal .close").click(function() {
    theVideo.pause()
  });
</script>

@endsection