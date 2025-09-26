@extends('admin.layouts.app')

@section('pagetitle')
Manage Product Reviews
@endsection

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
                        <li class="breadcrumb-item active" aria-current="page">Product Reviews</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Manage Product Reviews</h4>
            </div>
        </div>

        <div class="row row-sm">

            <!-- Start Filters -->
            <div class="col-md-12 mg-b-10">
                <div class="filter-buttons">
                    <div class="d-md-flex bd-highlight">
                        <div class="bd-highlight mg-r-10 mg-t-10">
                            <div class="dropdown d-inline mg-r-5">
                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{__('common.filters')}}
                                </button>
                                <div class="dropdown-menu">
                                    <form id="filterForm" class="pd-20">
                                        <div class="form-group">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="orderBy1" name="orderBy" class="custom-control-input" value="updated_at" @if ($filter->orderBy == 'updated_at') checked @endif>
                                                <label class="custom-control-label" for="orderBy1">{{__('common.date_modified')}}</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="orderBy2" name="orderBy" class="custom-control-input" value="product_name" @if ($filter->orderBy == 'product_name') checked @endif>
                                                <label class="custom-control-label" for="orderBy2">{{__('common.name')}}</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="orderBy3" name="orderBy" class="custom-control-input" value="rating" @if ($filter->orderBy == 'rating') checked @endif>
                                                <label class="custom-control-label" for="orderBy3">Rating</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleDropdownFormEmail1">{{__('common.sort_order')}}</label>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="sortByAsc" name="sortBy" class="custom-control-input" value="asc" @if ($filter->sortBy == 'asc') checked @endif>
                                                <label class="custom-control-label" for="sortByAsc">{{__('common.ascending')}}</label>
                                            </div>

                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="sortByDesc" name="sortBy" class="custom-control-input" value="desc"  @if ($filter->sortBy == 'desc') checked @endif>
                                                <label class="custom-control-label" for="sortByDesc">{{__('common.descending')}}</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" id="showDeleted" name="showDeleted" class="custom-control-input" @if ($filter->showDeleted) checked @endif>
                                                <label class="custom-control-label" for="showDeleted">{{__('common.show_deleted')}}</label>
                                            </div>
                                            
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" id="showApproved" name="showApproved" class="custom-control-input" @if ($filter->showApproved) checked @endif>
                                                <label class="custom-control-label" for="showApproved">Show Approved Only</label>
                                            </div>
                                            
                                            {{-- <div class="custom-control custom-checkbox">
                                                <input type="checkbox" id="showDisapproved" name="showDisapproved" class="custom-control-input" @if ($filter->showDisapproved) checked @endif>
                                                <label class="custom-control-label" for="showDisapproved">Show Disapproved Only</label>
                                            </div> --}}
                                            
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" id="showPending" name="showPending" class="custom-control-input" @if ($filter->showPending) checked @endif>
                                                <label class="custom-control-label" for="showPending">Show Pending Only</label>
                                            </div>
                                        </div>
                                        <div class="form-group mg-b-40">
                                            <label class="d-block">{{__('common.item_displayed')}}</label>
                                            <input id="displaySize" type="text" class="js-range-slider" name="perPage" value="{{ $filter->perPage }}"/>
                                        </div>
                                        <button id="filter" type="button" class="btn btn-sm btn-primary">{{__('common.apply_filters')}}</button>
                                    </form>
                                </div>
                            </div>
                            <div class="list-search d-inline">
                                <div class="dropdown d-inline mg-r-10">
                                    <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="javascript:void(0)" onclick="approve_reviews()">Approve</a>
                                        @if(\ViewPermissions::check_permission(Auth::user()->role_id,'admin/page/delete') == 1)
                                            <a class="dropdown-item tx-danger" href="javascript:void(0)" onclick="delete_reviews()">{{__('common.delete')}}</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="ml-auto bd-highlight mg-t-10">
                            <form class="form-inline" id="searchForm">
                                <div class="search-form">
                                    <input style="width: 200px;" name="search" type="search" id="search" class="form-control"  placeholder="Search by Product Name" value="{{ $filter->search }}">
                                    <button class="btn filter" type="button" id="btnSearch"><i data-feather="search"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Filters -->


            <!-- Start Pages -->
            <div class="col-md-12">
                <div class="table-list mg-b-10">
                    <div class="table-responsive-lg">
                        <table class="table mg-b-0 table-light table-hover" style="word-break: break-all;">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="checkbox_all">
                                            <label class="custom-control-label" for="checkbox_all"></label>
                                        </div>
                                    </th>
                                    <th scope="col" width="25%">Product Name</th>
                                    <th scope="col" width="15%">Customer Name</th>
                                    <th scope="col" width="30%">Review</th>
                                    <th scope="col" width="5%">Star Rating</th>
                                    <th scope="col" width="10%">Status</th>
                                    <th scope="col" width="10%">Date Modified</th>
                                    <th scope="col" width="10%">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reviews as $review)
                                <tr id="row{{$review->id}}">
                                    <th>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input cb" id="cb{{ $review->id }}">
                                            <label class="custom-control-label" for="cb{{ $review->id }}"></label>
                                        </div>
                                    </th>
                                    <td>
                                        <strong @if($review->trashed()) style="text-decoration:line-through;" @endif> {{ $review->product_name }}</strong>
                                    </td>
                                    <td>{{ $review->name }}</td>
                                    <td>
                                        <nav class="nav table-options">
                                            <a class="nav-link" href="javascript:void(0)" onclick="edit_review('{{ $review->id }}', '{{ $review->comment }}')" title="Edit Review"><i data-feather="edit"></i></a>
                                            {{ $review->comment }}
                                        </nav>                                        
                                    </td>
                                    <td>{{ $review->rating }}</td>
                                    <td>{{ ($review->status == 1) ? 'APPROVED' : 'PENDING' }}</td>
                                    <td><span class="text-nowrap">{{ Setting::date_for_listing($review->updated_at) }}</span></td>
                                    <td>
                                        @if($review->trashed())
                                            <nav class="nav table-options">
                                                <a class="nav-link" href="{{route('product-review.restore',$review->id)}}" title="Restore this service"><i data-feather="rotate-ccw"></i></a>
                                            </nav>
                                        @else
                                            <nav class="nav table-options">
                                                @if($review->status <> 1)
                                                    @if (auth()->user()->has_access_to_route('product-review.single-delete'))
                                                        <a class="nav-link" href="javascript:void(0)" onclick="single_delete('{{$review->id}}')" title="Delete Review"><i data-feather="trash"></i></a>
                                                    @endif

                                                    @if (auth()->user()->has_access_to_route('product-review.single-approve'))
                                                        <a class="nav-link" href="javascript:void(0)" onclick="single_approve('{{$review->id}}')" title="Approve Review"><i data-feather="check"></i></a>
                                                    @endif
                                                @endif
                                            </nav>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                    <tr><th colspan="8"><center>No Product Reviews Found.</center></th></tr>
                                @endforelse


                            </tbody>
                        </table>
                    </div>
                    <!-- table-responsive -->
                </div>
            </div>
            <!-- End Pages -->
            <div class="col-md-6">
                <div class="mg-t-5">
                    @if ($reviews->firstItem() == null)
                        <p class="tx-gray-400 tx-12 d-inline">{{__('common.showing_zero_items')}}</p>
                    @else
                        <p class="tx-gray-400 tx-12 d-inline">Showing {{ $reviews->firstItem() }} to {{ $reviews->lastItem() }} of {{ $reviews->total() }} items</p>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-md-right float-md-right mg-t-5">
                    <div>
                        {{ $reviews->appends((array) $filter)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="" id="posting_form" method="post" style="display: none;">
        @csrf
        <input type="text" id="reviews" name="reviews">
        <input type="text" id="post_comment" name="comment">
    </form>

    <div class="modal effect-scale" id="prompt-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">{{__('common.delete_confirmation_title')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>{{__('common.delete_confirmation')}}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" id="btnDelete">Yes, Delete</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal effect-scale" id="prompt-approved" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">{{__('common.approve_confirmation_title')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>{{__('common.approve_confirmation')}}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-primary" id="btnApprove">Yes, Approve</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal effect-scale" id="prompt-multiple-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">{{__('common.delete_mutiple_confirmation_title')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{__('common.delete_mutiple_confirmation')}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" id="btnDeleteMultiple">Yes, Delete</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal effect-scale" id="prompt-multiple-approve" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">{{__('common.approve_mutiple_confirmation_title')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    You are about to approve the selected items. Do you want to continue?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-primary" id="btnApproveMultiple">Yes, Approve</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
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
                    <p>{{__('common.no_selected')}}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal effect-scale" id="prompt-edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Edit Review</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label>Comment</label>
                    <input name="comment" class="form-control comment-input" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-warning" id="btnEdit">Update</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>

    <script>
        let listingUrl = "{{ route('product-review.index') }}";
        let advanceListingUrl = "";
        let searchType = "{{ $searchType }}";
    </script>
    <script src="{{ asset('js/listing.js') }}"></script>
@endsection


@section('customjs')
    <script>

        $(".js-range-slider").ionRangeSlider({
            grid: true,
            from: selected,
            values: perPage
        });

        /*** Handles the Select All Checkbox ***/
        $("#checkbox_all").click(function(){
            $('.cb').not(this).prop('checked', this.checked);
        });

        function post_form(url,reviewId){
            $('#posting_form').attr('action',url);
            $('#reviews').val(reviewId);
            $('#posting_form').submit();
        }

        function single_approve(id){
            $('#prompt-approved').modal('show');
            $('#btnApprove').on('click', function() {
                post_form("{{route('product-review.single-approve')}}",id);
            });
        }

        function single_delete(id){
            $('#prompt-delete').modal('show');
            $('#btnDelete').on('click', function() {
                post_form("{{route('product-review.single-delete')}}",id);
            });
        }

        function edit_review(id, comment){
            $('#prompt-edit').modal('show');
            $('.comment-input').val(comment);
            $('#btnEdit').on('click', function() {
                $('#post_comment').val($('.comment-input').val());
                post_form("{{route('product-review.update-review')}}", id, comment);
            });
        }

        function delete_reviews(){
            var counter = 0;
            var selected_reviews = '';
            $(".cb:checked").each(function(){
                counter++;
                fid = $(this).attr('id');
                selected_reviews += fid.substring(2, fid.length)+'|';
            });

            if(parseInt(counter) < 1){
                $('#prompt-no-selected').modal('show');
                return false;
            }
            else{
                $('#prompt-multiple-delete').modal('show');
                $('#btnDeleteMultiple').on('click', function() {
                    post_form("{{route('product-review.multiple.delete')}}",selected_reviews);
                });
            }
        }

        function approve_reviews(){
            var counter = 0;
            var selected_reviews = '';
            $(".cb:checked").each(function(){
                counter++;
                fid = $(this).attr('id');
                selected_reviews += fid.substring(2, fid.length)+'|';
            });

            if(parseInt(counter) < 1){
                $('#prompt-no-selected').modal('show');
                return false;
            }
            else{
                $('#prompt-multiple-approve').modal('show');
                $('#btnApproveMultiple').on('click', function() {
                    post_form("{{route('product-review.multiple-approve')}}",selected_reviews);
                });
            }
        }

        $('.cb').change(function() {
            var id = ($(this).attr('id')).replace("cb", "");
            if(this.checked) {
                $('#row'+id).addClass("table-warning");
            }
            else{
                $('#row'+id).removeClass("table-warning");
            }
        });
    </script>

@endsection
