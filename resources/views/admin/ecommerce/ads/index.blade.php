@extends('admin.layouts.app')

@section('pagetitle')
    Manage Ads
@endsection

@section('pagecss')
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/owl.carousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/owl.carousel/assets/owl.theme.default.min.css') }}" rel="stylesheet">
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
                        <li class="breadcrumb-item active" aria-current="page">BANNER ADS</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Manage Ads</h4>
            </div>
        </div>

        <div class="row row-sm">
            <div class="col-md-12">
                <div class="filter-buttons mg-b-10">
                    <div class="d-md-flex bd-highlight">
                        <div class="bd-highlight mg-r-10 mg-t-10">
                            <div class="dropdown d-inline mg-r-5">
                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Filters
                                </button>
                                <div class="dropdown-menu">
                                    <form id="filterForm" class="pd-20">
                                        <div class="form-group">
                                            <label for="exampleDropdownFormEmail1">{{__('common.sort_by')}}</label>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="orderBy1" name="orderBy" class="custom-control-input" value="updated_at" @if ($filter->orderBy == 'updated_at') checked @endif>
                                                <label class="custom-control-label" for="orderBy1">{{__('common.date_modified')}}</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="orderBy2" name="orderBy" class="custom-control-input" value="name" @if ($filter->orderBy == 'name') checked @endif>
                                                <label class="custom-control-label" for="orderBy2">{{__('common.name')}}</label>
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
                                        </div>
                                        <div class="form-group mg-b-40">
                                            <label class="d-block">{{__('common.item_displayed')}}</label>
                                            <input id="displaySize" type="text" class="js-range-slider" name="perPage" value="{{ $filter->perPage }}"/>
                                        </div>
                                        <button id="filter" type="button" class="btn btn-sm btn-primary">{{__('common.apply_filters')}}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="ml-auto bd-highlight mg-t-10">
                            <form class="form-inline" id="searchForm">
                                <div class="search-form mg-r-10">
                                    <input name="search" type="search" id="search" class="form-control" placeholder="Search by Ad Title" value="{{ $filter->search }}">
                                    <button class="btn"><i data-feather="search"></i></button>
                                </div>
                                @if(auth()->user()->has_access_to_route('ads.create'))
                                    <a href="{{route('ads.create')}}" class="btn btn-primary btn-sm mg-b-5 mg-l-5 mt-lg-0 mt-md-0 mt-sm-0 mt-1">Create Ad</a>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="table-list mg-b-10">
                    <div class="table-responsive-lg">
                        <table class="table mg-b-0 table-light table-hover" style="width:100%;word-wrap: break-word;">
                            <thead>
                            <tr>
                                <th scope="col" width="35%">Ad Title</th>
                                <th scope="col" width="35%">Url</th>
                                <th scope="col">Click Counts</th>
                                <th scope="col">Expiration Date</th>
                                <th scope="col" class="text-center">Options</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($ads as $ad)
                                <tr id="row{{$ad->id}}" class="row_cb" height="50px">
                                    <td style="overflow: hidden;text-overflow: ellipsis;" title="{{$ad->name}}">
                                        <strong @if($ad->trashed()) style="text-decoration:line-through;" @endif title="{{ $ad->name }}">{{ $ad->name }}</strong>
                                        @if($ad->status && $ad->expiration_date > now()) <span class="badge badge-sm badge-success ml-2">Active</span> @endif
                                        @if($ad->expiration_date <= now()) <span class="badge badge-sm badge-danger ml-2">Expired</span> @endif
                                    </td>
                                    <td><span class="text-nowrap"><a href="{{ $ad->url }}" target="blank_">{{ $ad->url }}</a></span></td>
                                    <td><span class="text-nowrap">{{ $ad->click_counts }}</span></td>
                                    <td><span class="text-nowrap">{{ Carbon\Carbon::parse($ad->expiration_date)->format('F d, Y') }}</span></td>
                                    <td>
                                        @if($ad->trashed())
                                            @if (auth()->user()->has_access_to_route('ads.restore'))
                                                <nav class="nav table-options justify-content-center flex-nowrap">
                                                    <form id="form{{$ad->id}}" method="post" action="{{ route('ads.restore', $ad->id) }}">
                                                        @csrf
                                                        @method('POST')
                                                        <a class="nav-link" href="#" title="Restore this ad" onclick="document.getElementById('form{{$ad->id}}').submit()"><i data-feather="rotate-ccw"></i></a>
                                                    </form>
                                                </nav>
                                            @endif
                                        @else
                                            <nav class="nav table-options justify-content-center flex-nowrap">
                                                @if(auth()->user()->has_access_to_route('ads.edit'))
                                                    <a class="nav-link" title="Edit banner" href="{{ route('ads.edit', $ad->id) }}"><i data-feather="edit"></i></a>
                                                @endif
 
                                                @if (auth()->user()->has_access_to_route('ads.delete'))
                                                    <a class="nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i data-feather="settings"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        @if(auth()->user()->has_access_to_route('ads.delete'))
                                                            <button type="button" class="dropdown-item" data-target="#prompt-delete" data-toggle="modal" data-animation="effect-scale" data-id="{{ $ad->id }}" data-name="{{ $ad->name }}">Delete</button>
                                                            <form id="adForm{{ $ad->id }}" method="POST" action="{{ route('ads.delete', $ad->id) }}" class="d-none">
                                                                @csrf
                                                            </form>
                                                        @endif
                                                    </div>
                                                @endif
                                            </nav>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center;"> <p class="text-danger">No ad found.</p></td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- table-responsive -->
                </div>
            </div>
            <div class="col-md-6">
                <div class="mg-t-5">
                    @if ($ads->firstItem() == null)
                        <p class="tx-gray-400 tx-12 d-inline">{{__('common.showing_zero_items')}}</p>
                    @else
                        <p class="tx-gray-400 tx-12 d-inline">Showing {{ $ads->firstItem() }} to {{ $ads->lastItem() }} of {{ $ads->total() }} items</p>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-md-right float-md-right mg-t-5">
                    <div>
                        {{ $ads->appends((array) $filter)->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="modal effect-scale" id="prompt-delete-many" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">{{__('common.delete_mutiple_confirmation_title')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>{{__('common.delete_mutiple_confirmation')}}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" id="btnDeleteMany">Yes, Delete</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

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
@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
    <script src="{{ asset('lib/owl.carousel/owl.carousel.js') }}"></script>
    <script>
        let listingUrl = "{{ route('ads.index') }}";
        let advanceListingUrl = "";
        let searchType = "{{ $searchType }}";
    </script>
    <script src="{{ asset('js/listing.js') }}"></script>
@endsection

@section('customjs')
    @if ($errors->any())
        <script>
            $('#toastErrorMessage').toast('show');
        </script>
    @endif
    <script>
        let ids;
        $('#deleteAds').on('click', function() {
            if($(".cb:checked").length <= 0){
                $('#prompt-no-selected').modal('show');
                return false;
            }
            else {
                ids = [];
                $.each($(".cb:checked"), function() {
                    ids.push($(this).data('id'));
                });

                $('#prompt-delete-many').modal('show');
            }
        });

        $('#btnDeleteMany').on('click', function () {
            $('#adIds').val(ids);
            $('#adsForm').submit();
        });

        let adId;
        $('#prompt-delete').on('show.bs.modal', function (e) {
            //get data-id attribute of the clicked element
            let ad = e.relatedTarget;
            adId = $(ad).data('id');
            let albumName = $(ad).data('name');

            $('#albumName').html(albumName);
        });

        $('#btnDelete').on('click', function() {
            $('#adForm'+adId).submit();
        });
    </script>
@endsection
