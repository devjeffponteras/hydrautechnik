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
                        <li class="breadcrumb-item active" aria-current="page">Manage Subscribers</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Manage Subscribers</h4>
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
                                                <label class="custom-control-label" for="orderBy1">Date Subscribed</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="orderBy2" name="orderBy" class="custom-control-input" value="email" @if ($filter->orderBy == 'email') checked @endif>
                                                <label class="custom-control-label" for="orderBy2">Email</label>
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
                            @if(auth()->user()->has_access_to_route('mailing-list.subscribers.change-status'))
                                <div class="list-search d-inline">
                                    <div class="dropdown d-inline mg-r-10">
                                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Actions
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            @if (auth()->user()->has_access_to_route('mailing-list.subscribers.change-status') )
                                                <button id="activeItem" class="dropdown-item">Active</button>
                                                <button id="inactiveItem" class="dropdown-item">Deactivate</button>
                                                <form id="updateItemsForm" method="POST" class="d-none" action="{{ route('mailing-list.subscribers.change-status') }}" style="display: none;">
                                                    @csrf
                                                    <input name="ids" id="updateItemIds" type="hidden">
                                                    <input name="is_active" id="updateItemStatus" type="hidden">
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="ml-auto bd-highlight mg-t-10">
                            <form class="form-inline" id="searchForm">
                                <div class="search-form mg-r-10">
                                    <input name="search" type="search" id="search" class="form-control"  placeholder="Search by Form name" value="{{ $filter->search }}">
                                    <button class="btn filter" type="button" id="btnSearch"><i data-feather="search"></i></button>
                                </div>
                                @if(auth()->user()->has_access_to_route('mailing-list.subscribers.create'))
                                    <a class="btn btn-primary btn-sm mg-b-5" href="{{ route('mailing-list.subscribers.create') }}">Add Subscriber</a>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="table-list mg-b-10">
                    <div class="table-responsive-lg">
                        <table class="table mg-b-0 table-light table-hover" style="width:100%;">
                            <thead>
                            <tr>
                                <th style="width: 10%">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="checkbox_all">
                                        <label class="custom-control-label" for="checkbox_all"></label>
                                    </div>
                                </th>
                                <th scope="col">Email</th>
                                <th scope="col">Name</th>
                                <th scope="col">Date Subscribed</th>
                                <th scope="col">Status</th>
                                <th scope="col">Options</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($subscribers as $subscriber)
                                <tr id="row{{ $subscriber->id }}" class="row_cb">
                                    <th>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input cb" id="cb{{$subscriber->id}}" data-id="{{ $subscriber->id }}">
                                            <label class="custom-control-label" for="cb{{$subscriber->id}}"></label>
                                        </div>
                                    </th>
                                    <th style="text-transform: initial;">
                                        <strong>{{ $subscriber->email }}</strong>
                                    </th>
                                    <td style="overflow: hidden;text-overflow: ellipsis;" >{{ $subscriber->name }}</td>
                                    <td>{{ Setting::date_for_listing($subscriber->created_at) }}</td>
                                    <td>
                                        @if ($subscriber->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if (auth()->user()->has_access_to_route('mailing-list.subscribers.edit') ||auth()->user()->has_access_to_route('mailing-list.subscribers.change-status') )
                                            <nav class="nav table-options justify-content-end">
                                                @if (auth()->user()->has_access_to_route('mailing-list.subscribers.edit'))
                                                    <a class="nav-link" href="{{ route('mailing-list.subscribers.edit', $subscriber->id) }}" title="Edit Subscriber"><i data-feather="edit"></i></a>
                                                @endif
                                                @if (auth()->user()->has_access_to_route('mailing-list.subscribers.change-status'))
                                                    <a class="nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i data-feather="settings"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        @if ($subscriber->is_active)
                                                            <button type="button" class="dropdown-item update-status" data-id="{{ $subscriber->id }}" data-status="0">Deactivate</button>
                                                        @else
                                                            <button type="button" class="dropdown-item update-status" data-id="{{ $subscriber->id }}" data-status="1"> Active</button>
                                                        @endif
                                                    </div>
                                                @endif
                                            </nav>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <th colspan="7" style="text-align: center;"> <p class="text-danger">No subscribers found.</p></th>
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
                    @if ($subscribers->firstItem() == null)
                        <p class="tx-gray-400 tx-12 d-inline">{{__('common.showing_zero_items')}}</p>
                    @else
                        <p class="tx-gray-400 tx-12 d-inline">Showing {{($subscribers->firstItem() ?? 0)}} to {{($subscribers->lastItem() ?? 0)}} of {{ $subscribers->total()}} items</p>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-md-right float-md-right mg-t-5">
                    {{ $subscribers->appends((array) $filter)->links() }}
                </div>
            </div>
        </div>
        <!-- row -->
    </div>
    <!-- container -->

    <div class="modal effect-scale" id="prompt-update-status" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">{{__('common.update_confirmation_title')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    You are about to <span id="statusMany"></span> the selected item/s. Do you want to continue?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" id="btnUpdateStatusMany">Yes, Update</button>
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
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
    <script>
        let listingUrl = "{{ route('mailing-list.subscribers.index') }}";
        let advanceListingUrl = "";
        let searchType = "{{ $searchType }}";
    </script>
    <script src="{{ asset('js/listing.js') }}"></script>
@endsection


@section('customjs')
    <script>

        $('.update-status').on('click', function () {
            let status = $(this).data('status');
            $('#updateItemStatus').val($(this).data('status'));
            ids = [];
            ids.push($(this).data('id'));
            $('#statusMany').html((status == 1) ? 'active' : 'deactivate');
            $('#prompt-update-status').modal('show');
        });

        function validateCheckbox(method)
        {
            if($(".cb:checked").length <= 0){
                $('#prompt-no-selected').modal('show');
                return false;
            }
            else {
                ids = [];
                $.each($(".cb:checked"), function() {
                    ids.push($(this).data('id'));
                });

                if (method == 'delete') {
                    $('#prompt-delete-many').modal('show');
                } else if (method == 'update-status') {
                    $('#prompt-update-status').modal('show');
                }
            }
        }

        $('#activeItem').on('click', function () {
            $('#statusMany').html('active');
            $('#updateItemStatus').val(1);
            validateCheckbox('update-status');
        });

        $('#inactiveItem').on('click', function () {
            $('#statusMany').html('deactivate');
            $('#updateItemStatus').val(0);
            validateCheckbox('update-status');
        });

        $('#btnUpdateStatusMany').on('click', function () {
            $('#updateItemIds').val(ids);
            $('#updateItemsForm').submit();
        });

        $('#deleteItems').on('click', function () {
            validateCheckbox('delete');
        });

        $('#btnDeleteMany').on('click', function () {
            $('#deleteItemIds').val(ids);
            $('#deleteItemsForm').submit();
        });

        let itemId;
        $('#prompt-delete').on('show.bs.modal', function (e) {
            let itemObj = e.relatedTarget;
            itemId = $(itemObj).data('id');
        });

        $('#btnDelete').on('click', function() {
            $('#deleteItemForm'+itemId).submit();
        });

        $('#prompt-restore').on('show.bs.modal', function (e) {
            let itemObj = e.relatedTarget;
            itemId = $(itemObj).data('id');
        });

        $('#btnRestore').on('click', function() {
            $('#restoreItemForm'+itemId).submit();
        });
    </script>
@endsection
