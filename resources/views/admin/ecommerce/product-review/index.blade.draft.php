@extends('admin.layouts.app')

@section('pagecss')
    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
    <style>
        .row-selected {
            background-color: #92b7da !important;
        }
    </style>
@endsection

@section('content')
    <div class="container pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-5">
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{route('products.index')}}">Product Reviews</a></li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Manage Product Reviews</h4>
            </div>
        </div>

        <div class="row row-sm">

            <!-- Start Filters -->
            @include('admin.ecommerce.product-review.filter')
            <!-- End Filters -->


            <!-- Start Pages -->
            <div class="col-md-12">
                <div class="table-list mg-b-10">
                    <div class="table-responsive-lg">
                        <table class="table mg-b-0 table-light table-hover"  style="table-layout: fixed;word-wrap: break-word;">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="checkbox_all">
                                            <label class="custom-control-label" for="checkbox_all"></label>
                                        </div>
                                    </th>
                                    <th style="width: 20%;overflow: hidden;">Product Name</th>
                                    <th style="width: 20%;">Customer Name</th>
                                    <th style="width: 35%;">Review</th>
                                    <th style="width: 10%;">Star Rating</th>
                                    <th style="width: 10%;">Status</th>
                                    <th style="width: 15%;">Last Date Modified</th>
                                    <th style="width: 10%; text-align: right;">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($product_reviews as $product_review)
                                <tr id="row{{$product_review->id}}" class="row_cb">
                                    <th>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input cb" id="cb{{ $product_review->id }}">
                                            <label class="custom-control-label" for="cb{{ $product_review->id }}"></label>
                                        </div>
                                    </th>
                                    <td>
                                        <strong @if($product_review->trashed()) style="text-decoration:line-through;" @endif> {{ App\Models\Ecommerce\ProductReview::getProductName($product_review->product_id) }}</strong><br>
                                        <span class="badge badge-primary" {{ !$product_review->is_free  ? 'hidden' : '' }}>Free</span>
                                    </td>
                                    {{-- <td>{{ $product_review->category->name }}</td>
                                    <td>{{ $product_review->currency }} {{ number_format($product_review->price,2) }}</td>
                                    <td>{{ number_format($product_review->Inventory,2) }}</td>
                                    <td>{{ $product_review->status }}</td>
                                    <td>{{ Setting::date_for_listing($product_review->updated_at) }}</td>
                                    <td class="text-right">
                                        @if($product_review->trashed())
                                            @if (auth()->user()->has_access_to_route('product.restore'))
                                                <nav class="nav table-options">
                                                    <a class="nav-link" href="{{route('product.restore',$product_review->id)}}" title="Restore this product"><i data-feather="rotate-ccw"></i></a>
                                                </nav>
                                            @endif
                                        @else
                                            <nav class="nav table-options">
                                                <a class="nav-link" target="_blank" href="{{ route('product.details', $product_review->slug) }}" title="View Product Profile"><i data-feather="eye"></i></a>

                                                @if (auth()->user()->has_access_to_route('products.edit'))
                                                    <a class="nav-link" href="{{ route('products.edit',$product_review->id) }}" title="Edit Product"><i data-feather="edit"></i></a>
                                                @endif

                                                @if (auth()->user()->has_access_to_route('product.single.delete'))
                                                    <a class="nav-link" href="javascript:void(0)" onclick="delete_one_category({{$product_review->id}},'{{$product_review->name}}')" title="Delete Product"><i data-feather="trash"></i></a>
                                                @endif

                                                @if (auth()->user()->has_access_to_route('product.single-change-status'))
                                                    <a class="nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i data-feather="settings"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="#" onclick="add_inventory('{{$product_review->id}}','{{$product_review->Inventory}}')"> Add Inventory</a>

                                                        <a class="dropdown-item" href="#" onclick="deduct_inventory('{{$product_review->id}}','{{$product_review->Inventory}}')"> Deduct Inventory</a>

                                                        @if($product_review->status == 'PUBLISHED')
                                                            <a class="dropdown-item" href="{{route('product.single-change-status',[$product_review->id,'PRIVATE'])}}" > Private</a>
                                                        @else
                                                            <a class="dropdown-item" href="{{route('product.single-change-status',[$product_review->id,'PUBLISHED'])}}"> Publish</a>
                                                        @endif
                                                    </div>
                                                @endif
                                            </nav>
                                        @endif
                                    </td> --}}
                                </tr>
                                @empty
                                <tr>
                                    <th colspan="8" style="text-align: center;"> <p class="text-danger">No reviews found.</p></th>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- End Pages -->
            <div class="col-md-6">
                <div class="mg-t-5">
                    @if ($product_reviews->firstItem() == null)
                        <p class="tx-gray-400 tx-12 d-inline">{{__('common.showing_zero_items')}}</p>
                    @else
                        <p class="tx-gray-400 tx-12 d-inline">Showing {{ $product_reviews->firstItem() }} to {{ $product_reviews->lastItem() }} of {{ $product_reviews->total() }} items</p>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-md-right float-md-right mg-t-5">
                    <div>
                        {{ $product_reviews->appends((array) $filter)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="" id="posting_form" style="display:none;" method="post">
        @csrf
        <input type="text" id="products" name="products">
        <input type="text" id="status" name="status">
    </form>

    {{-- @include('admin.ecommerce.modals')
    @include('admin.ecommerce.products.modal-advance-search') --}}


    <div class="modal effect-scale" id="prompt-add-inventory" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Add Inventory</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form autocomplete="off" action="{{ route('products.add-inventory') }}" method="post">
                @csrf
                    <div class="modal-body">
                        <div class="modal-body">
                            <input type="hidden" name="productid" id="productid">
                            <h3 class="text-success">Available Stock : <span id="available_stock"></span></h3>
                            <div class="form-group">

                                <label class="d-block">Quantity *</label>
                                <input required type="number" name="qty" class="form-control" autofocus>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal effect-scale" id="prompt-deduct-inventory" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Deduct Inventory</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form autocomplete="off" action="{{ route('products.deduct-inventory') }}" method="post">
                @csrf
                    <div class="modal-body">
                        <div class="modal-body">
                            <input type="hidden" name="productid" id="productId">
                            <h3 class="text-success">Available Stock : <span id="availableStock"></span></h3>
                            <div class="form-group">

                                <label class="d-block">Quantity *</label>
                                <input required type="number" name="qty" id="qty" class="form-control" autofocus>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal effect-scale" id="prompt-upload" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <form action="{{ route('product.upload.template') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Upload Product</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Note: Make sure you've used the correct csv template</p>
                        <input type="file" name="csv" required="required">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-danger">Upload</button>
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>

    <script>
        let listingUrl = "{{ route('product_review.index') }}";
        let searchType = "{{ $searchType }}";
    </script>
    <script src="{{ asset('js/listing.js') }}"></script>
@endsection

@section('customjs')
    <script>
        function post_form(url,status,product){
            $('#posting_form').attr('action',url);
            $('#products').val(product);
            $('#status').val(status);
            $('#posting_form').submit();
        }

        /*** handles the changing of status of multiple pages ***/
        function change_status(status){
            var counter = 0;
            var selected_videos = '';
            $(".cb:checked").each(function(){
                counter++;
                fid = $(this).attr('id');
                selected_videos += fid.substring(2, fid.length)+'|';
            });
            if(parseInt(counter) < 1){
                $('#prompt-no-selected').modal('show');
                return false;
            }
            else{
                if(parseInt(counter)>1){ // ask for confirmation when multiple pages was selected
                    $('#productStatus').html(status)
                    $('#prompt-update-status').modal('show');

                    $('#btnUpdateStatus').on('click', function() {
                        post_form("{{route('product.multiple.change.status')}}",status,selected_videos);
                    });
                }
                else{
                    post_form("{{route('product.multiple.change.status')}}",status,selected_videos);
                }
            }
        }

        function delete_category(){
            var counter = 0;
            var selected_products = '';
            $(".cb:checked").each(function(){
                counter++;
                fid = $(this).attr('id');
                selected_products += fid.substring(2, fid.length)+'|';
            });

            if(parseInt(counter) < 1){
                $('#prompt-no-selected').modal('show');
                return false;
            }
            else{
                $('#prompt-multiple-delete').modal('show');
                $('#btnDeleteMultiple').on('click', function() {
                    post_form("{{route('products.multiple.delete')}}",'',selected_products);
                });
            }
        }

        function delete_one_category(id,product){
            $('#prompt-delete').modal('show');
            $('#btnDelete').on('click', function() {
                post_form("{{route('product.single.delete')}}",'',id);
            });
        }

        function add_inventory(id,inventory){
            $('#productid').val(id);
            $('#available_stock').html(inventory);
            $('#prompt-add-inventory').modal('show');
        }

        function deduct_inventory(id,inventory){
            $('#productId').val(id);
            $('#availableStock').html(inventory);
            $('#prompt-deduct-inventory').modal('show');

            $("#qty").attr({
                "max" : inventory
            });
        }
    </script>
@endsection
