@extends('layout.page-app')

@section('page_title',  __('label.payment'))

@section('content')

    @include('layout.sidebar')

    <div class="right-content">
        @include('layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm"> {{__('label.payment')}} </h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.payment')}}</li>
                    </ol>
                </div>
            </div>

            <div class="table-responsive table">
                <table class="table table-striped text-center table-bordered" id="datatable">
                    <thead>
                        <tr style="background: #F9FAFF;">
                            <th> {{__('label.#')}} </th>
                            <th> {{__('label.name')}} </th>
                            <th> {{__('label.status')}} </th>
                            <th> {{__('label.payment_environment')}} </th>
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
                    ajax: "{{ route('payment.store') }}",
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, visible: false },
                        {data: 'name', name:'name', orderable: false, searchable: false,
                            "render": function (data, type, full, meta) {
                            if (data) {
                            return data;
                            } else {
                            return "-";
                            }
                        }
                        },
                        {data: 'visibility', name: 'visibility', orderable: false, searchable: false,
                            "render": function(data, type, full,meta) {
                            if(data == 1) {
                            return "Active";
                            } else {
                            return "In Active";
                            } 
                        }
                        },
                        {data: 'is_live', name:'is_live', orderable: false, searchable: false,
                            "render": function (data, type, full, meta) {
                            if (data == 1) {
                            return "Live";
                            } else {
                            return "Sandbox";
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