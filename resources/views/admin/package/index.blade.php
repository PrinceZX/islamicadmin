@extends('layout.page-app')

@section('page_title',  __('label.package'))

@section('content')
    @include('layout.sidebar')

    <div class="right-content">
        @include('layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm"> {{__('label.package')}} </h1>

            <div class="border-bottom row mb-3">
            <div class="col-sm-10">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    {{__('label.package')}}
                </li>
                </ol>
            </div>
            <div class="col-sm-2 d-flex align-items-center justify-content-end" style="margin-top:-14px">
                <a href="{{ route('package.create') }}" class="btn btn-default mw-120">{{__('label.add_package')}}</a>
            </div>
            </div>

            <div class="table-responsive table">
            <table class="table table-striped text-center table-bordered" id="datatable">
                <thead>
                    <tr style="background: #F9FAFF;">
                        <th> {{__('label.#')}} </th>
                        <th> {{__('label.image')}} </th>
                        <th> {{__('label.name')}} </th>
                        <th> {{__('label.price')}} </th>
                        <th> {{__('label.time')}} </th>
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
                serverSide: true,
                ajax: "{{ route('package.store') }}",
                columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'image', name: 'image', orderable: false, searchable: false,
                    "render": function (data, type, full, meta) {
                        return "<img src='"+ data + "' height=50 Width=50>";
                    },
                },
                {data: 'name', name:'name'},
                {data: 'price', name: 'price',
                    render: function (data, type, row, meta) {
                    return row.currency_type + " " + row.price;
                    }
                },
                {data: 'time', name: 'time',
                    render: function (data, type, row, meta) {
                    return row.time + " " + row.type;
                    }
                },
                {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
            });
        });
    });
  </script>
@endsection