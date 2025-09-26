@extends('admin.layouts.app')

@section('pagecss')
    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-5">
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{ route('mailing-list.campaigns.index') }}">Manage Campaigns</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Manage Sent Campaigns</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Manage Sent Campaigns</h4>
            </div>
        </div>

        <div class="row row-sm">
            <div class="col-md-12">
                <div class="filter-buttons mg-b-10">
                    <div class="d-md-flex bd-highlight">
                        <div class="bd-highlight mg-r-10 mg-t-10">
                            <div class="dropdown d-inline mg-r-5">
                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{__('common.filters')}}
                                </button>
                                <div class="dropdown-menu">
                                    <form id="filterForm" class="pd-20">
                                        <div class="form-group">
                                            <label for="exampleDropdownFormEmail1">{{__('common.sort_by')}}</label>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="orderBy1" name="orderBy" class="custom-control-input" value="created_at" @if ($filter->orderBy == 'created_at') checked @endif>
                                                <label class="custom-control-label" for="orderBy1">Date Sent</label>
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
                                    <input name="search" type="search" id="search" class="form-control"  placeholder="Search by Name" value="{{ $filter->search }}">
                                    <button class="btn filter" type="button" id="btnSearch"><i data-feather="search"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="table-list mg-b-10">
                    <div class="table-responsive-lg text-nowrap">
                        <table class="table mg-b-0 table-light table-hover" style="width:100%;">
                            <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Subject</th>
                                <th scope="col">Group</th>
                                <th scope="col">Recipient</th>
                                <th scope="col">Total Recipient</th>
                                <th scope="col">Date Sent</th>
                                {{-- <th class="tx-right" scope="col">Actions</th> --}}
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($sentCampaigns as $sentCampaign)
                                <tr>
                                    <th>{{ $sentCampaign->name }}</th>
                                    <td>{{ $sentCampaign->subject }}</td>
                                    <td>{{ $sentCampaign->total_group() }}</td>
                                    <td>{{ $sentCampaign->total_subscriber() }}</td>
                                    <td>{{ $sentCampaign->all_subscribers->count() }}</td>
                                    <td>{{ Setting::date_for_listing($sentCampaign->updated_at) }}</td>
                                    {{-- <td>
                                        <nav class="nav table-options justify-content-end flex-nowrap">
                                            <a class="nav-link" target="_blank" href="{{route('mailing-list.forward-campaign',$sentCampaign->campaign_id)}}" title="Share Campaign"><i data-feather="share"></i></a>
                                            <a class="nav-link" href="javascript:void(0);" onclick="delete_one_page({{$sentCampaign->id}},'{{$sentCampaign->name}}');" title="Delete Campaign"><i data-feather="trash"></i></a>
                                        </nav>
                                    </td> --}}
                                </tr>
                            @empty
                                <tr>
                                    <th colspan="6" style="text-align: center;"> <p class="text-danger">No campaigns found.</p></th>
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
                    @if ($sentCampaigns->firstItem() == null)
                        <p class="tx-gray-400 tx-12 d-inline">{{__('common.showing_zero_items')}}</p>
                    @else
                        <p class="tx-gray-400 tx-12 d-inline">Showing {{($sentCampaigns->firstItem() ?? 0)}} to {{($sentCampaigns->lastItem() ?? 0)}} of {{$sentCampaigns->total()}} items</p>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-md-right float-md-right mg-t-5">
                    {{ $sentCampaigns->appends((array) $filter)->links() }}
                </div>
            </div>
        </div>
        <!-- row -->
    </div>
    <!-- container -->

    
    {{-- modals --}}

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

    <form action="" id="posting_form" style="display:none;" method="post">
        @csrf
        <input type="text" id="pages" name="pages">
        <input type="text" id="status" name="status">
    </form>

@endsection

@section('pagejs')
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
    <script>
        let listingUrl = "{{ route('mailing-list.campaigns.sent-campaigns') }}";
        let advanceListingUrl = "";
        let searchType = "{{ $searchType }}";
    </script>
    <script src="{{ asset('js/listing.js') }}"></script>
@endsection


@section('customjs')
    <script>
        function delete_one_page(id, page) {
            $('#prompt-delete').modal('show');
            $('#btnDelete').on('click', function() {
                var route = "{{ route('mailing-list.sent-campaigns.delete', ['id' => 'ID']) }}";
                route = route.replace('ID', id);
                post_form(route, '', id);
            });
        }

        function post_form(url,status,pages){

            $('#posting_form').attr('action',url);
            $('#pages').val(pages);
            $('#status').val(status);
            $('#posting_form').submit();

        }

    </script>
@endsection
