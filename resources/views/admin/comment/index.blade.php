@extends('layout.page-app')

@section('page_title',  __('label.Comment'))

@section('content')
  @include('layout.sidebar')

  <div class="right-content">
    @include('layout.header')

    <div class="body-content">
      <!-- mobile title -->
      <h1 class="page-title-sm"> {{__('label.Comment')}} </h1>

      <div class="border-bottom row mb-3">
        <div class="col-sm-12">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.Dashboard')}}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
              {{__('label.Comment')}}
            </li>
          </ol>
        </div>
      </div>

      <div class="table-responsive table">
        <table class="table table-striped text-center table-bordered" id="datatable">
          <thead>
            <tr style="background: #F9FAFF;">
              <th> {{__('label.#')}} </th>
              <th> {{__('label.user_name')}} </th>
              <th> {{__('label.video')}} </th>
              <th> {{__('label.Comment')}} </th>
              <th> {{__('label.date')}} </th>
              <th> {{__('label.action')}} </th>
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>
      </div>

    </div>
  </div>
@endsection

@section('pagescript')
  <script>
    $(document).ready(function() {
      $(function () {
        var table = $('#datatable').DataTable({
          "responsive": true,
          "autoWidth": false,
          language: {
            paginate: {
              previous: "<img src='{{asset('assets/imgs/left-arrow.png')}}' >",
              next: "<img src='{{asset('assets/imgs/left-arrow.png')}}' style='transform: rotate(180deg)'>"
            }
          },
          processing: true,
          serverSide: false,
          ajax: "{{ route('comment.index') }}",
          columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'user.full_name', name: 'full_name', orderable: false,
              "render": function (data, type, full, meta) {
                if(data) {
                  return data;
                } else {
                  return "-";
                }
              },
            },
            {data: 'video.name', name: 'title', orderable: false,
              "render": function (data, type, full, meta) {
                if(data) {
                  return data;
                } else {
                  return "-";
                }
              },
            },
            {data: 'comment', name:'comment',
              "render": function (data, type, full, meta) {
                if (data) {
                  return data;
                } else {
                  return "-";
                }
              }
            },
            {data: 'date', name: 'date'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
          ],
        });
      });
    });

    function change_status(id, status) {
      $("#dvloader").show();
      var url = "{{route('comment.show', '')}}"+"/"+id;
      $.ajax({
        type: "GET",
        url: url,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data:id,
        success:function(resp){
          $("#dvloader").hide();
          if(resp.status == 200){
            if(resp.Status_Code == 1){

              $('#'+id).text('Show');
              $('#'+id).css({"background": "#15ca20", "color": "white", "font-size": "14px", "font-weight": "bold",  "border": "none", "padding": "4px 22px", "outline": "none" });
            } else {

              $('#'+id).text('Hide');
              $('#'+id).css({"background": "#0dceec", "color": "white", "font-size": "14px", "font-weight": "bold",  "border": "none", "padding": "5px 15px", "outline": "none" });
            }
          } else {
            toastr.error(resp.errors);         
          }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          $("#dvloader").hide();
          toastr.error(errorThrown.msg,'failed');         
        }
      });
    };
  </script>
@endsection