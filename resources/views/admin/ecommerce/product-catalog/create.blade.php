@extends('admin.layouts.app')

@section('pagecss')
	<link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
	<link href="{{ asset('lib/clockpicker/bootstrap-clockpicker.min.css') }}" rel="stylesheet">
	<link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
	<style>
		.select2 {width:100% !important;}

		.select2-container--default .select2-selection--multiple .select2-selection__choice{
			position: relative;
		    margin-top: 4px;
		    margin-right: 4px;
		    padding: 3px 10px 3px 20px;
		    border-color: transparent;
		    border-radius: 1px;
		    background-color: #0168fa;
		    color: #fff;
		    font-size: 13px;
		    line-height: 1.45;
		}

		.select2-container--default .select2-selection--multiple .select2-selection__choice__remove{
			color: #fff;
		    opacity: .5;
		    font-size: 14px;
		    font-weight: 400;
		    display: inline-block;
		    position: absolute;
		    top: 4px;
		    left: 7px;
		    line-height: 1.2;
		}
	</style>
@endsection

@section('content')
<div class="container pd-x-0">
	<div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
		<div>
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb breadcrumb-style1 mg-b-10">
					<li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
					<li class="breadcrumb-item" aria-current="page"><a href="{{ route('product-catalog.index') }}">Catalog</a></li>
					<li class="breadcrumb-item active" aria-current="page">Create Catalog</li>
				</ol>
			</nav>
			<h4 class="mg-b-0 tx-spacing--1">Create Catalog</h4>
		</div>
	</div>

	<form method="post" action="{{ route('product-catalog.store') }}" id="catalogForm" autocomplete="off" enctype="multipart/form-data">
		@csrf
		<div class="row row-sm">
			<div class="col-lg-6">
				<div class="form-group">
					<label class="d-block">Name *</label>
					<input type="text" id="catalog_name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
					@error('name')
						<span class="text-danger">{{ $message }}</span>
                    @enderror
				</div>

				<div class="form-group">
					<h5>Choose Products or Category</h5>
				</div>

				<div class="form-row border rounded p-3 mb-4" id="coupon-purchase-option">

					<div class="col-12 mt-3" id="coupon-product-form">
						<small class="text-danger" style="display: none;" id="spanProductOpt"></small>
						<div class="form-group">
							<label class="d-block">Product Name</label>
							<select class="form-control select2" multiple="multiple" name="product_name[]" id="product_opt">
								<option label="Choose one"></option>
								@foreach($products as $product)
									<option @if(is_array(old('product_name')) && in_array($product->id, old('product_name'))) selected @endif value="{{$product->id}}">{{ $product->name }}</option>
								@endforeach
							</select>
						</div>

						<div class="form-group">
							<label class="d-block">Category</label>
							<select class="form-control select2" multiple="multiple" name="product_category[]" id="category_opt">
								<option label="Choose one"></option>
								@foreach($categories as $category)
									<option @if(is_array(old('product_category')) && in_array($category->id, old('product_category'))) selected @endif value="{{$category->id}}">{{ $category->name }}</option>
								@endforeach
							</select>
						</div>
					</div>

				</div>
				<hr>
			</div>
			
			<div class="col-lg-12">
				<div class="form-group">
					<label class="d-block">Status</label>
					<div class="custom-control custom-switch">
						<input type="checkbox" class="custom-control-input" id="enableSwitch1" name="status" {{ (old("status") ? "checked":"") }}>
						<label class="custom-control-label" for="enableSwitch1" id="label_status">@if(old('status')) Active @else Inactive @endif</label>
					</div>
				</div>
			</div>

			<div class="col-lg-12 mg-t-30">
				<button class="btn btn-primary btn-sm btn-uppercase" type="button" id="btnSubmit">Save</button>
				<a href="{{ route('product-catalog.index') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Cancel</a>
			</div>
		</div>
	</form>
	<!-- row -->
</div>

<div class="modal effect-scale" id="prompt-no-selected" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">{{__('common.no_selected_title')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="no_selected_title"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pagejs')
	<script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
	<script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
	<script src="{{ asset('lib/jqueryui/jquery-ui.min.js') }}"></script>
	<script src="{{ asset('lib/clockpicker/bootstrap-clockpicker.min.js') }}"></script>
	<script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
@endsection


@section('customjs')
<script>

	$('#product_opt').change(function(){
		var value = $(this).val();

		if(value != ''){
			$('#category_opt').attr("disabled", true);
			$('#brand_opt').attr("disabled", true);
		} else {
			$('#category_opt').removeAttr("disabled");
			$('#brand_opt').removeAttr("disabled");
		}
	});

	$('#category_opt').change(function(){
		var selected = '';
		$('#category_opt :selected').each(function(){
		    selected += $(this).val()+'|';
		});

		var value = $(this).val();
		if(value != ''){
			$('#brand_opt').attr("disabled", true);
			$('#product_opt').attr("disabled", true);
		} else {
			$('#brand_opt').removeAttr("disabled");
			$('#product_opt').removeAttr("disabled");
		}
	});



	$('#btnSubmit').click(function(){
		
		var catalog_name = $('#catalog_name').val();
		var product = $('#product_opt').val();
		var category = $('#category_opt').val();

		if((product.length === 0 && category.length === 0) || catalog_name.length === 0 ){
			$('#spanProductOpt').css('display','block');
			$('#spanProductOpt').html('Please select at least one(1) option.');
			swal({
				title: '',
				text: "Please complete inputs.",         
			});
			rs = false;
			return false;
		}
		else{
			rs = true;
		}

		if(rs == true){
			$('#catalogForm').submit();
		}
	});
	

	$("#enableSwitch1").change(function() {
        if(this.checked) {
            $('#label_status').html('Active');
        }
        else{
            $('#label_status').html('Inactive');
        }
    });

	$('.datetime').clockpicker();

	$('.singlecalendar').datepicker({
		dateFormat: 'yy-mm-dd'
	});

	var dateToday = new Date(); 
	$('#dateFrom').datepicker({
		dateFormat: 'yy-mm-dd',
		minDate: dateToday,
	});
	$('#dateTo').datepicker({
		dateFormat: 'yy-mm-dd',
		minDate: dateToday,
	});

	$('.select2').select2({
		placeholder: 'Choose Options'
	});


	function myFunction() {
		var couponPurchase = document.getElementById("coupon-purchase");
		var fieldCouponOption = document.getElementById("coupon-purchase-option");
		if (couponPurchase.checked == true){
			fieldCouponOption.style.display = "flex";
		} else {
			fieldCouponOption.style.display = "none";
		};
	};

	$(function() {
		$('.selectpicker').selectpicker();
	});
</script>
@endsection