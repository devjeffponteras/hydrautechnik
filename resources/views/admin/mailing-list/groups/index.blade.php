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
                        <li class="breadcrumb-item" aria-current="page">Manage Groups</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Manage Groups</h4>
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
                            @if(auth()->user()->has_access_to_route('mailing-list.groups.destroy_many'))
                                <div class="list-search d-inline">
                                    <div class="dropdown d-inline mg-r-10">
                                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Actions
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <button id="deleteItems" class="dropdown-item tx-danger">Delete</button>
                                            <form id="deleteItemsForm" method="POST" class="d-none" action="{{ route('mailing-list.groups.destroy_many') }}" style="display: none;">
                                                @method('DELETE')
                                                @csrf
                                                <input name="ids" id="deleteItemIds" type="hidden">
                                            </form>
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
                                @if (auth()->user()->has_access_to_route('mailing-list.groups.edit'))
                                    <a class="btn btn-primary btn-sm mg-b-5" href="{{ route('mailing-list.groups.create') }}">Add Group</a>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="table-list mg-b-10">
                    <div class="table-responsive-lg">
                        <table class="table mg-b-0 table-light table-hover">
                            <thead>
                            <tr>
                                <th style="width: 5%">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="checkbox_all">
                                        <label class="custom-control-label" for="checkbox_all"></label>
                                    </div>
                                </th>
                                <th style="width: 55%;overflow: hidden;">Name</th>
                                <th style="width: 10%;">Total Subscriber</th>
                                <th style="width: 15%;">Date Modified</th>
                                <th style="width: 15%;">Options</th>
                            </tr>
                            </thead>
                            <tbody>
                                @forelse($groups as $group)
                                    <tr id="row{{ $group->id }}" class="row_cb">
                                        <th>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input cb" id="cb{{ $group->id }}" data-id="{{ $group->id }}">
                                                <label class="custom-control-label" for="cb{{ $group->id }}"></label>
                                            </div>
                                        </th>
                                        <td style="overflow: hidden;text-overflow: ellipsis;" >
                                            <strong @if($group->trashed()) style="text-decoration:line-through;" @endif  title="{{ $group->name }}">
                                                {{ $group->name }}
                                            </strong>
                                        </td>
                                        <td>{{ $group->subscribers->count() }}</td>
                                        <td>{{ Setting::date_for_listing($group->updated_at) }}</td>
                                        <td class="text-right">
                                            @if($group->trashed())
                                                @if(auth()->user()->has_access_to_route('mailing-list.groups.restore'))
                                                    <nav class="nav table-options justify-content-end">
                                                        {{--                                                        <button type="button" class="dropdown-item" data-target="#prompt-restore" data-toggle="modal" data-animation="effect-scale" data-id="{{ $group->id }}"><i data-feather="rotate-ccw"></i></button>--}}
                                                        <a class="nav-link" href="#" title="Restore this item" onclick="document.getElementById('restoreItemForm{{$group->id}}').submit()"><i data-feather="rotate-ccw"></i></a>
                                                        <form id="restoreItemForm{{$group->id}}" method="post" action="{{ route('mailing-list.groups.restore', $group->id) }}" class="d-none">
                                                            @csrf
                                                            @method('POST')
                                                        </form>
                                                    </nav>
                                                @endif
                                            @else
                                                @if (auth()->user()->has_access_to_route('mailing-list.groups.edit') || auth()->user()->has_access_to_route('mailing-list.groups.destroy'))
                                                    <nav class="nav table-options justify-content-end">
                                                        @if (auth()->user()->has_access_to_route('mailing-list.groups.edit'))
                                                            <a class="nav-link" href="{{ route('mailing-list.groups.edit', $group->id) }}"  title="Edit Group"><i data-feather="edit"></i></a>
                                                        @endif

                                                        @if (auth()->user()->has_access_to_route('mailing-list.groups.destroy'))
                                                            <a class="nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i data-feather="settings"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <button type="button" class="dropdown-item tx-danger" data-target="#prompt-delete" data-toggle="modal" data-animation="effect-scale" data-id="{{ $group->id }}">Delete</button>
                                                                <form id="deleteItemForm{{ $group->id }}" method="POST" action="{{ route('mailing-list.groups.destroy', $group->id) }}" class="d-none">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                </form>
                                                            </div>
                                                        @endif
                                                    </nav>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <th colspan="7" style="text-align: center;"> <p class="text-danger">No groups found.</p></th>
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
                    @if ($groups->firstItem() == null)
                        <p class="tx-gray-400 tx-12 d-inline">{{__('common.showing_zero_items')}}</p>
                    @else
                        <p class="tx-gray-400 tx-12 d-inline">Showing {{($groups->firstItem() ?? 0)}} to {{($groups->lastItem() ?? 0)}} of {{ $groups->total()}} items</p>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-md-right float-md-right mg-t-5">
                    {{ $groups->appends((array) $filter)->links() }}
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

    <div class="modal effect-scale" id="prompt-restore" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">{{__('common.restore_confirmation_title')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>{{__('common.restore_confirmation')}}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" id="btnRestore">Yes, Restore</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagejs')
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
    <script>
        let listingUrl = "{{ route('mailing-list.groups.index') }}";
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
            $('#statusMany').html((status == 1) ? 'publish' : 'private');
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
            $('#statusMany').html('publish');
            $('#updateItemStatus').val(1);
            validateCheckbox('update-status');
        });

        $('#inactiveItem').on('click', function () {
            $('#statusMany').html('private');
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
