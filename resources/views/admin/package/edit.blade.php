@extends('layout.page-app')

@section('page_title',  __('label.edit_package'))

@section('content')
	@include('layout.sidebar')

	<div class="right-content">
		@include('layout.header')

		<div class="body-content">
			<!-- mobile title -->
			<h1 class="page-title-sm">{{__('label.edit_package')}}</h1>

			<div class="border-bottom row mb-3">
				<div class="col-sm-10">
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a>
						</li>
						<li class="breadcrumb-item">
							<a href="{{ route('package.index') }}">{{__('label.package')}}</a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">
							{{__('label.edit_package')}}
						</li>
					</ol>
				</div>
				<div class="col-sm-2 d-flex align-items-center justify-content-end">
					<a href="{{ route('package.index') }}" class="btn btn-default mw-120" style="margin-top:-14px">{{__('label.package_list')}}</a>
				</div>
			</div>

			<div class="card custom-border-card mt-3">
				<div class="card-body">
					<form name="package" id="package_update" enctype="multipart/form-data" autocomplete="off">
						<input type="hidden" name="id" value="@if($data){{$data->id}}@endif">        
						<div class="form-row">
							<div class="col-md-6 mb-3">
								<div class="form-group">
									<label>{{__('label.name')}}</label>
									<input type="text" name="name" value="@if($data){{$data->name}}@endif" class="form-control" placeholder="{{__('label.please_enter_name')}}">
								</div>
							</div>
							<div class="col-md-6 mb-3">
								<div class="form-group">
									<label>{{__('label.price')}}</label>
									<input type="number" name="price" value="@if($data){{$data->price}}@endif" class="form-control" placeholder="{{__('label.please_enter_price')}}">
								</div>
							</div>
							
						</div>
						
						<div class="form-row">
            				<div class="col-md-6 mb-6 mt-3">
              					<div class="form-group">
                					<label for="type">{{__('label.package_time')}}</label>
                                        <select class="form-control" id="validity_type" name="type">
                                          <option value="">{{__('label.select_type')}}</option>
                                          <option value="Day" {{$data->type == 'Day' ? 'selected' : ''}}>{{__('label.day')}}</option>
                                          <option value="Week" {{$data->type == 'Week' ? 'selected' : ''}}>{{__('label.week')}}</option>
                                          <option value="Month" {{$data->type == 'Month' ? 'selected' : ''}}>{{__('label.month')}}</option>
                                          <option value="Year" {{$data->type == 'Year' ? 'selected' : ''}}>{{__('label.year')}}</option>
                                        </select>
              					</div>
            				</div>
            				<div class="col-md-6 mb-6 mt-5">
              					<div class="form-group">
                					<select class="form-control time" id="time" name="time">
                  						<option value="">{{__('label.select_number')}}</option>
                  							@for($i=1; $i<=31; $i++)
                  								<option value="{{$i}}" {{$data->time == $i ? 'selected' : ''}}>{{$i}}</option>
                  							@endfor
                					</select>
              					</div>
            				</div>
							
    					</div>

						<div class="form-row">
                            <div class="col-md-6 mb-3">
                              <div class="form-group">
                                <label for="name">{{__('label.android_package')}}</label>
                                <input name="android_product_package" type="text" class="form-control" placeholder="{{__('label.enter_android_package')}}" value="{{$data->android_product_package}}">
                              </div>
                            </div>
                            <div class="col-md-6 mb-3">
                              <div class="form-group">
                                <label for="price">{{__('label.iso_package')}}</label>
                                <input name="ios_product_package" type="text" class="form-control" placeholder="{{__('label.enter_ios_package')}}" value="{{$data->ios_product_package}}">
                              </div>
                            </div>
          				</div>
						
						<div class="form-row">
							<div class="col-md-6"> 
								<div class="form-group"> 
									<label>{{__('label.image')}}</label> 
									<input type="file" value="" class="form-control" id="image" name="image"> 
									<label class="mt-1 text-gray">{{__('label.note')}}</label>
								</div>
							</div>
							<div class="col-md-6"> 
								<div class="form-group">
									<div class="custom-file ml-5"> 
										
										<img  src="{{$data['image']}}" height="120px" width="120px" id="Uploaded-Image">
										<input type="hidden" name="old_image" value="@if($data){{$data->image}}@endif">
									</div>
								</div>
							</div>
						</div>

						<div class="border-top pt-3 text-right">
							<button type="button" class="btn btn-default mw-120" onclick="update_package()">{{__('label.update')}}</button>
							<a href="{{route('package.index')}}" class="btn btn-cancel mw-120 ml-2">{{__('label.cancel')}}</a>
							<input type="hidden" name="_method" value="PATCH">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('pagescript')
	<script>
		function update_package(){
			$("#dvloader").show();
			var formData = new FormData($("#package_update")[0]);

			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				enctype: 'multipart/form-data',
				type: 'POST',
				url: '{{route("package.update", [$data->id])}}',
				data: formData,
				cache:false,
				contentType: false,
				processData: false,
				success:function(resp){
					$("#dvloader").hide();
					get_responce_message(resp, 'package_update', '{{ route("package.index") }}');
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					$("#dvloader").hide();
					toastr.error(errorThrown.msg,'failed');         
				}
			});
		}
		$(document).ready(function() {
      		var validity_type = "<?php echo $data->type; ?>";
            if (validity_type == "Day") {
              for (let i = 8; i <= 31; i++) {
                            $(".time option[value="+i+"]").hide();
                        }
            } else if (validity_type == "Week") {
              for (let i = 5; i <= 31; i++) {
                            $(".time option[value="+i+"]").hide();
                        }
            }  else if (validity_type == "Month") {
              for (let i = 13; i <= 31; i++) {
                            $(".time option[value="+i+"]").hide();
                        }
            } else if (validity_type == "Year") {
              for (let i = 2; i <= 31; i++) {
                            $(".time option[value="+i+"]").hide();
                        }
            } else {
              $('.time').hide();
            }
    	});

    	$('#validity_type').on('click', function() {
      		$('.time').show();
				var type = $("#validity_type").val()

                for (let i = 1; i <= 31; i++) {
                  $(".time option[value="+i+"]").show();
                  $(".time option[value="+i+"]").attr("selected", false);
                }
          
	          		if (type == "Day") {
                  for (let i = 8; i <= 31; i++) {
                    $(".time option[value="+i+"]").hide();
                  }
                } else if (type == "Week") {
                  for (let i = 5; i <= 31; i++) {
                    $(".time option[value="+i+"]").hide();
                  }
                } else if (type == "Month") {
                  for (let i = 13; i <= 31; i++) {
                    $(".time option[value="+i+"]").hide();
                  }
                } else if (type == "Year") {
                  for (let i = 2; i <= 31; i++) {
                    $(".time option[value="+i+"]").hide();
                  }
                } else {
                  $('.time').hide();
                }
		})
	</script>
@endsection