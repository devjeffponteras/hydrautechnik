@extends('admin.layouts.app')

@section('pagecss')
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/prismjs/themes/prism-vs.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/datextime/daterangepicker.css') }}" rel="stylesheet">

    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">
    <style>
        .table td {
            padding: 0 0px;
        }
    </style>
@endsection

@section('content')
<div class="container pd-x-0">
    <div class="d-sm-flex justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">CMS</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('promos.index') }}">Promos</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit a Promo</li>
                </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1">Edit a Promo</h4>
        </div>
    </div>
    <form autocomplete="off" action="{{ route('promos.update',$promo->id) }}" method="post" id="promo_form">
        @csrf
        @method('PUT')
        <div class="row row-sm">
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="d-block">Name*</label>
                    <input required type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name',$promo->name) }}" maxlength="150">
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="d-block">Promotion Date & Time*</label>
                            <input required type="text" name="promotion_dt" class="form-control wd-100p @error('promotion_dt') is-invalid @enderror" placeholder="Choose date range" id="date1" value="{{ date('Y-m-d H:i',strtotime($promo->promo_start)) }} - {{ date('Y-m-d H:i',strtotime($promo->promo_end)) }}">
                            @error('promotion_dt')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Discount (%)*</label>
                    <input required name="discount" id="discount" value="{{ old('discount',$promo->discount) }}" type="number" class="form-control @error('discount') is-invalid @enderror" max="100" min="1">
                    @error('discount')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

				<div class="form-group">
					<label class="d-block">Applicable Product Type *</label>
					<select class="custom-select @error('applicable_product_type') is-invalid @enderror" id="applicable_product_type" name="applicable_product_type">
						<option @if(old('applicable_product_type') == 'all' || $promo->applicable_product_type == 'all') selected @endif value="all">All</option>
						<option @if(old('applicable_product_type') == 'physical' || $promo->applicable_product_type == 'physical') selected @endif value="physical">Physical Books</option>
						<option @if(old('applicable_product_type') == 'ebook' || $promo->applicable_product_type == 'ebook') selected @endif value="ebook">E-Books</option>
					</select>
					@error('applicable_product_type')
						<span class="text-danger">{{ $message }}</span>
                    @enderror
				</div>

                <div class="form-group">
                    <label class="d-block">Status</label>
                    <div class="custom-control custom-switch @error('status') is-invalid @enderror">
                        <input type="checkbox" class="custom-control-input" name="status" {{ (old("status") == "ON" || $promo->status == "ACTIVE" ? "checked":"") }} id="customSwitch1">
                        <label class="custom-control-label" id="label_visibility" for="customSwitch1">{{ucfirst(strtolower($promo->status))}}</label>
                    </div>
                    @error('status')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="access-table-head" id="div_products">

                    {{-- SEARCH BOX --}}
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Search products" aria-label="Search products" id="searchInput">
                    </div>
    
                    <div class="table-responsive-lg text-nowrap">
                        <table class="table table-borderless" style="width:100%;">
                            <thead>
                            <tr>
                                <td width="50%"><strong>Select Categories</strong></td>
                                <td class="text-right">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="checkbox_all">
                                        <label class="custom-control-label" for="checkbox_all"></label>
                                    </div>
                                </td>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                <table class="table table-hover" style="width:100%;">
                    <thead>
                        
                    </thead>
                    <tbody id="productTableBody">
                    @foreach($categories as $category)
                        @if(count($category->published_products) > 0)
                            @php
                                $cproducts = 0;
                                $products = \App\Models\Ecommerce\Product::where('status','PUBLISHED')->where('category_id',$category->id)->get();
                                foreach($products as $p){
                                    $cproducts += \App\Models\Ecommerce\PromoProducts::where('promo_id',$promo->id)->where('product_id',$p->id)->count();
                                }
                            @endphp
                            <tr>
                                <td width="50%"><p class="mg-0 pd-t-5 pd-b-5 tx-uppercase tx-semibold tx-primary">{{ $category->name }}</p></td>
                                <td class="text-right">
                                    <a href="" title="View Products" data-toggle="collapse" data-target="#product_category{{$category->id}}"><i class="fa fa-list"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="hiddenRow">
                                    <div class="accordian-body collapse collapsed_items @if($cproducts>0) show @endif div_products" id="product_category{{$category->id}}">
                                        <div>
                                            <table class="table" cellpadding="0">
                                                <thead></thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Select All</td>
                                                        <td class="text-right">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" value="{{ $category->id }}" class="custom-control-input category category_{{$category->id}}" id="cat{{$category->id}}" data-category="{{$category->id}}">
                                                                
                                                                <label class="custom-control-label" for="cat{{$category->id}}"></label>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @forelse($products as $product)
                                                        @php
                                                            $exist = \App\Models\Ecommerce\PromoProducts::where('promo_id',$promo->id)->where('product_id',$product->id)->count();
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $product->name }}</td>
                                                            <td class="text-right">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" name="productid[]" value="{{$product->id}}" class="custom-control-input cb category_{{$product->category_id}}" id="pcategory{{$product->id}}" @if($exist>0) checked @endif>
                                                                    <label class="custom-control-label" for="pcategory{{$product->id}}"></label>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        @empty
                                                        <tr><td colspan="2">No Products</td></tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="col-lg-12 mg-t-20 mg-b-30">
                <button class="btn btn-primary btn-sm btn-uppercase" type="submit">Update Promo</button>
                <a href="{{ route('promos.index') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Cancel</a>
            </div>
        </div>
    </form>
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
                <p>{{__('common.no_product_selected')}}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pagejs')
    <script src="{{ asset('lib/jqueryui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>

    <script src="{{ asset('lib/datextime/moment.min.js') }}"></script>
    <script src="{{ asset('lib/datextime/daterangepicker.js') }}"></script>
    <script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>

    <script>
        var dateToday = new Date(); 

        $(function(){
            'use strict'

            $('#date1').daterangepicker({
                autoUpdateInput: false,
                timePicker: true,
                locale: {
                    format: 'YYYY-MM-DD H:mm',
                    cancelLabel: 'Clear'
                },
                minDate: dateToday,
            });

            $('input[name="promotion_dt"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD H:mm') + ' - ' + picker.endDate.format('YYYY-MM-DD H:mm'));
            });

            $('input[name="promotion_dt"]').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
        });

        $("#customSwitch1").change(function() {
            if(this.checked) {
                $('#label_visibility').html('Active');
            }
            else{
                $('#label_visibility').html('Inactive');
            }
        });
    </script>
@endsection

@section('customjs')
    <script>

        /*** Handles the Select All Checkbox ***/
        $("#checkbox_all").click(function(){
            $('.cb').not(this).prop('checked', this.checked);
            $('.category').not(this).prop('checked', this.checked);

            if($('#checkbox_all').is(':checked')){
                $('.div_products').addClass('show');
            } else {
                $('.div_products').removeClass('show'); 
            }
        });

        /*** Handles the Select All Checkbox ***/
        $("#brand_checkbox_all").click(function(){
            $('.cbbrand').not(this).prop('checked', this.checked);
            $('.cb_brand').not(this).prop('checked', this.checked);

            if($('#brand_checkbox_all').is(':checked')){
                $('.div_brand').addClass('show');
            } else {
                $('.div_brand').removeClass('show'); 
            }
            
        });

        $('.cb_brand').on('click', function() {
            let brand = $(this).data('brand');
            let checked = $(this).is(':checked');
            let objectName = '.brand_'+brand;
            $(objectName).each(function() {
                this.checked = checked;
            });
        });
        

        $('.category').on('click', function() {
            let category = $(this).data('category');
            let checked = $(this).is(':checked');
            let objectName = '.category_'+category;
            $(objectName).each(function() {
                this.checked = checked;
            });
        });

        function promo_type(){
            var val = $('#type').val();

            if(val == 'brand'){
                $('#tbl_brand').css('display','block');
                $('#tbl_product').css('display','none');
            }

            if(val == 'category'){
                $('#tbl_brand').css('display','none');
                $('#tbl_product').css('display','block');
            }
        }

        $('#promo_form').submit(function(){
            if(!$("input[name='productid[]']:checked").val()) {        
                $('#prompt-no-selected').modal('show');
                return false;
            } else {
                return true;
            }
        });

        /** form validations **/
        $(document).ready(function () {
            //called when key is pressed in textbox
            $("#discount").keypress(function (e) {
                //if the letter is not digit then display error and don't type anything
                var charCode = (e.which) ? e.which : event.keyCode
                if (charCode != 43 && charCode > 31 && (charCode < 48 || charCode > 57))
                    return false;
                return true;

            });
        });

        document.getElementById('searchInput').addEventListener('input', function() {
            let searchText = this.value.toLowerCase();
            let rows = document.querySelectorAll('#productTableBody tr');

            rows.forEach(function(row) {
                let productName = row.querySelector('td:first-child').textContent.toLowerCase();
                if (productName.includes(searchText)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            // Collapse all categories if the search box is empty
            let collapsedItems = document.querySelectorAll('.collapsed_items');
            if (searchText === '') {
                collapsedItems.forEach(function(item) {
                    item.classList.add('collapse');
                });
            } else {
                collapsedItems.forEach(function(item) {
                    item.classList.remove('collapse');
                });
            }

        });

    </script>
@endsection