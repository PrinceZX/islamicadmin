@extends('layout.page-app')

@section('page_title',  __('label.notification'))

@section('content')
    @include('layout.sidebar')

    <div class="right-content">
        @include('layout.header')


        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm"> {{__('label.notification')}} </h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{__('label.notification_list')}}
                        </li>
                    </ol>
                </div>
                <div class="col-sm-12 mb-3 d-flex justify-content-between">
                    <a href="{{ route('notification.create') }}" class="btn btn-default mw-120">{{__('label.add')}}</a>
                    <a href="{{ route('notification.setting') }}" class="btn btn-default mw-120">{{__('label.notification_setting')}}</a>
                </div>
            </div>

            <div class="table-responsive table">
                <table class="table table-striped text-center table-bordered" id="datatable">
                    <thead>
                        <tr style="background: #F9FAFF;">
                            <th> {{__('label.#')}} </th>
                            <th> {{__('label.image')}} </th>
                            <th> {{__('label.title')}} </th>
                            <th> {{__('label.message')}} </th>
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
                ajax: "{{ route('notification.store') }}",
                columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'image',name: 'image', orderable: false, searchable: false,
                            "render": function (data, type, full, meta) {
                                return "<img src='"+ data + "' height=50 Width=50>";
                            },
                        },
                        {data: 'title',name: 'title', orderable: false, searchable: false},
                        {data: 'message',name: 'message', orderable: false, searchable: false},
                        {data: 'action', name: 'action', orderable: false, searchable: false},

                    ],
                });
            });
        });
    </script>
@endsection