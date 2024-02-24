@extends('layout.page-app')

@section('page_title',  __('label.video'))

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
          {{__('label.video')}}
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
 

  <div class="row">

    <div class="col-12 col-sm-6 col-md-4 col-xl-3">
      <a href="{{ route('video.create') }}" class="add-video-btn">
        <img src="{{ asset('assets/imgs/add.png') }}" alt="" class="icon" />
        {{__('label.add_new_video')}}
      </a>
    </div>

    @foreach ($data as $key => $value)
      <div class="col-12 col-sm-6 col-md-4 col-xl-3">
        <div class="card video-card">
          <div class="position-relative">
          <img class="card-img-top" src="{{$value->image}}" alt="">
              @if($value->video_type == "server_video")
                <button class="btn play-btn video" data-toggle="modal" data-target="#videoModal" data-video="{{$value->url}}" data-image="{{$value->thumbnail}}">
                  <img src="{{ asset('assets/imgs/play.png') }}" alt="" />
                </button>
              @endif
          </div>
          
          <div class="card-body">
            <h5 class="card-title mr-5">{{ucfirst($value->name)}}</h5>
            @if($value->is_created_by_admin == 0)
            <h5 class="card-title mr-5">User: {{ucfirst($value->username)}}</h5>
              @if($value->is_approved == 0)
                <a href="javascript:void(0)" data-id="{{$value->id}}" data-type="1" id="approve_btn" onclick="approvevideo(this)" class="btn btn-default mw-120 card-title mr-5 loading-button" >Approve</a>
                <div class="spinner" id="spinner"></div>
                <a href="javascript:void(0)" data-id="{{$value->id}}" data-type="2" id="reject_btn" onclick="rejectvideo(this)" class="btn btn-default mw-120 card-title mr-5 loading-button" >Reject</a>
                <div class="spinner" id="spinner1"></div>
              @else
                @if($value->is_approved == 1)
                  <span class="badge badge-primary">Approved</span>
                @else
                <span class="badge badge-danger">Rejected</span>
                @endif
              @endif
            @endif
            <div class="card-details">
              </div>
              <div class="dropdown dropright">
                  <a href="#" class="btn head-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <img src="{{ asset('assets/imgs/dot.png') }}" class="dot-icon" />
                  </a>
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                  <a class="dropdown-item" href="{{ route('videoDetail', [$value->id])}}">
                          <img src="{{ asset('assets/imgs/view.png') }}" class="dot-icon" />
                             Details
                      </a>
                      <a class="dropdown-item" href="{{ route('video.edit', [$value->id])}}">
                          <img src="{{ asset('assets/imgs/edit.png') }}" class="dot-icon" />
                             Edit
                      </a>
                      <a class="dropdown-item" href="{{ route('video.show', $value->id)}}" onclick="return confirm('Are You Sure Delete This Category?')">
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
  function rejectvideo(event){
    var videoid = $(event).attr('data-id');
    var type = $(event).attr('data-type');
    if(videoid != ''){
        // Define the JSON data
      if (window.confirm("Do you want to reject?")) {
        $("#reject_btn").prop("disabled", true);
        $("#spinner1").show();
        $.ajax({
            'async': true,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '{{ route("video.approvevideo") }}', // Replace with your actual route URL
            method: 'POST', // or 'GET' depending on your needs
            dataType:'json',
            data: {
            "videoid": videoid,"type":type
            },
            success: function(response) {
                // Handle the success response
                if(response.status == 200){
                    location.reload();
                }else{
                    toastr.error('error',response.errors); 
                }
                $("#reject_btn").prop("disabled", false);
                $("#spinner1").hide();
            },
            error: function(xhr, status, error) {
                // Handle the error response
                $("#reject_btn").prop("disabled", false);
                $("#spinner1").hide();
                toastr.error('error',error); 
                
            }
        });
      }
    }else{
        $("#approve_btn").prop("disabled", false);
        $("#spinner").hide();
        toastr.error('Something went wrong.','failed');   
    }
  }
  function approvevideo(event){
    var videoid = $(event).attr('data-id');
    var type = $(event).attr('data-type');
    if(videoid != ''){
        // Define the JSON data
      if (window.confirm("Do you want to proceed?")) {
        $("#approve_btn").prop("disabled", true);
        $("#spinner").show();
        $.ajax({
            'async': true,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '{{ route("video.approvevideo") }}', // Replace with your actual route URL
            method: 'POST', // or 'GET' depending on your needs
            dataType:'json',
            data: {
            "videoid": videoid,"type":type
            },
            success: function(response) {
                // Handle the success response
                if(response.status == 200){
                    location.reload();
                }else{
                    toastr.error('error',response.errors); 
                }
                $("#approve_btn").prop("disabled", false);
                $("#spinner").hide();
            },
            error: function(xhr, status, error) {
                // Handle the error response
                $("#approve_btn").prop("disabled", false);
                $("#spinner").hide();
                toastr.error('error',error); 
                
            }
        });
      }
    }else{
        $("#approve_btn").prop("disabled", false);
        $("#spinner").hide();
        toastr.error('Something went wrong.','failed');   
    }
  } 
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