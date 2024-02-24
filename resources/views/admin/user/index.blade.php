@extends('layout.page-app')

@section('page_title',  __('label.user'))

@section('content')
  @include('layout.sidebar')
  
  <div class="right-content">
    @include('layout.header')

    <div class="body-content">
      <!-- mobile title -->
      <h1 class="page-title-sm"> {{__('label.user')}} </h1>

      <div class="border-bottom row mb-3">
        <div class="col-sm-10">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
              {{__('label.user')}}
            </li>
          </ol>
        </div>
        <div class="col-sm-2 d-flex align-items-center justify-content-end">
          <a href="{{ route('user.create') }}" class="btn btn-default mw-120" style="margin-top: -14px;">{{__('label.add_user')}}</a>
        </div>
      </div>

      <div class="table-responsive table">
        <table class="table table-striped text-center table-bordered" id="datatable">
          <thead>
            <tr style="background: #F9FAFF;">
              <th> {{__('label.#')}} </th>
              <th> {{__('label.image')}} </th>
              <th> {{__('label.full_name')}} </th>
              <th> {{__('label.email')}} </th>
              <th> {{__('label.mobile_number')}} </th>
              <th> {{__('label.date_of_birth')}} </th>
              <th> {{__('label.gender')}} </th>
              <th> {{__('label.type')}} </th>
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
              previous: "<img src='{{url('assets/imgs/left-arrow.png')}}' >",
              next: "<img src='{{url('assets/imgs/left-arrow.png')}}' style='transform: rotate(180deg)'>"
            }
          },
          processing: true,
          serverSide: true,
          ajax: "{{ route('user.store') }}",
          columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            { data: 'image', name: 'image', orderable: false, searchable: false,
              "render": function (data, type, full, meta) {
                return "<img src='"+ data + "' height=50 Width=50 class='rounded-circle'>";
              },

            },
           
            {data: 'full_name', name:'full_name',
              "render": function (data, type, full, meta) {
                if (data) {
                  return data;
                } else {
                  return "-";
                }
              }
            },
            {data: 'email', name: 'email',
              "render": function (data, type, full, meta) {
                if (data) {
                  return data;
                } else {
                return "-";
                }
              }
            },
            {data: 'mobile_number', name: 'mobile_number',
              render: function (data, type, row, meta) {

                if (data) {
                  return row.country_code + " " + row.mobile_number;
                } else {
                  return "-";
                }
              }
            },
            {data: 'date_of_birth', name: 'date_of_birth',
              "render": function (data, type, full, meta) {
                if (data) {
                  return data;
                } else {
                return "-";
                }
              }
            },
            {data: 'gender', name: 'gender',searchable: false,
              "render": function(data, type, full,meta) {
                if(data == 1) {
                  return "Male";
                } else if (data == 2) {
                  return "Female";
                } else if (data == 3){
                  return "Other";
                } else {
                  return "-";
                }
              }
            },
            {data: 'type', name: 'type', orderable: false, searchable: false,
              "render": function(data, type, full,meta) {
                if(data == 1) {
                  return "OTP";
                } else if(data == 2) {
                  return "Social";
                } else if(data == 3) {
                  return "Normal";
                } else {
                  return "-";
                }
              }
            },
            {data: 'date', name: 'date',
              "render": function(data, type, full,meta) {
                if(data) {
                  return data;
                } else {
                  return "-";
                } 
              }
            },
            {data: 'action', name: 'action', orderable: false, searchable: false},
          ],
        });
      });
    });
  </script>
@endsection