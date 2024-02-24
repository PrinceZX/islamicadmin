@extends('layout.page-app')

@section('page_title',  __('label.ai_audio_transactions'))

@section('content')

    @include('layout.sidebar')

    <div class="right-content">
        @include('layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm"> {{__('label.ai_audio_transactions')}} </h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-9">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.ai_audio_transactions')}}</li>
                    </ol>
                </div>
                <div class="col-sm-3 d-flex align-items-center justify-content-end">
                    <a href="{{ route('aiaudio_transaction.create') }}" class="btn btn-default mw-120" style="margin-top:-14px">Add Ai Audio Transaction</a>
                </div>
            </div>

            <div class="table-responsive table">
                <table class="table table-striped text-center table-bordered" id="datatable">
                    <thead>
                        <tr style="background: #F9FAFF;">
                            <th> {{__('label.#')}} </th>
                            <th> {{__('label.user_name')}} </th>
                            <th> {{__('label.email')}} </th>
                            <th> {{__('label.mobile_number')}}</th>
                            <th> {{__('label.audio_name')}}</th>
                            <th> {{__('label.payment_id')}} </th>
                            <th> {{__('label.amount')}} </th>
                            <th> {{__('label.date')}} </th> 
                            <th> Invoice </th> 
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
                   
                    ajax: "{{ route('aiaudio_transaction.index') }}",
                    columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'user.full_name', name: 'user.full_name', orderable: false, searchable: true,
                        "render": function (data, type, full, meta) {
                        if(data) {
                            return data;
                        } else {
                            return "-";
                        }
                        },
                    },
                    {data: 'user.email', name: 'user.email', orderable: true, searchable: true,
                        "render": function (data, type, full, meta) {
                        if(data) {
                            return data;
                        } else {
                            return "-";
                        }
                        },
                    },
                    {data: 'user.mobile_number', name: 'user.mobile_number', orderable: false, searchable: true,
                        "render": function (data, type, full, meta) {
                        if(data) {
                            return data;
                        } else {
                            return "-";
                        }
                        },
                    },
                    {data: 'audio.name', name: 'audio.name', orderable: false, searchable: true,
                        "render": function (data, type, full, meta) {
                        if(data) {
                            return data;
                        } else {
                            return "-";
                        }
                        },
                    },
                    {data: 'payment_id', name:'payment_id', orderable: true, searchable: true,
                        "render": function (data, type, full, meta) {
                        if(data) {
                            return data;
                        } else {
                            return "-";
                        }
                        },
                    },
                    {data: 'price', name: 'audio.price', orderable: false, searchable: true,
                        render: function (data, type, row, meta) {
                        return row.currency_code + " " + row.amount;
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
                    {data: 'invoice', name: 'invoice', orderable: false, searchable: false},
                    ],
                });
            });
        });
    </script>
@endsection