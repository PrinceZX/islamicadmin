@extends('layout.page-app')

@section('page_title',  __('label.pages'))

@section('content')
    @include('layout.sidebar')

    <div class="right-content">
        @include('layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm"> {{__('label.page')}} </h1>

            <div class="border-bottom row mb-3">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.pages')}}</li>
                    </ol>
                </div>
            </div>

            <div class="table-responsive table">
                <table class="table table-striped text-center table-bordered" id="datatable">
                    <thead>
                        <tr style="background: #F9FAFF;">
                            <th> {{__('label.#')}} </th>
                            <th> {{__('label.title')}} </th>
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
            ajax: "{{ route('page.store') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, visible: false},
                {data: 'title', name:'title'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            });
        });
        });
    </script>
@endsection