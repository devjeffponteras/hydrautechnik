@extends('admin.layouts.app')

@section('pagetitle')
    Manage Permissions
@endsection

@section('pagecss')
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                        <li class="breadcrumb-item"><a href="#">Account Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Permissions</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Permission Management</h4>
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
                                                <input type="radio" id="orderBy2" name="orderBy" class="custom-control-input" value="module" @if ($filter->orderBy == 'module') checked @endif>
                                                <label class="custom-control-label" for="orderBy2">Module</label>
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
                                                <input type="checkbox" id="showInactive" name="showDeleted" class="custom-control-input" @if ($filter->showDeleted) checked @endif>
                                                <label class="custom-control-label" for="showInactive">Show Deleted Items</label>
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
                        <div class="ml-auto bd-highlight mg-t-10 mg-r-10">
                            <form class="form-inline" id="searchForm">
                                <div class="search-form mg-r-10">
                                    <input name="search" type="search" id="search" class="form-control"  placeholder="Search by Module" value="{{ $filter->search }}">
                                    <button class="btn filter" type="button" id="btnSearch"><i data-feather="search"></i></button>
                                </div>
                            </form>
                        </div>
                        <div class="mg-t-10">
                            @if(ViewPermissions::check_permission(Auth::user()->role_id,'admin/role/create') == 1)
                                <a class="btn btn-primary btn-sm mg-b-5" href="{{ route('permission.create') }}">Create Permission</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="table-list mg-b-10">
                    <div class="table-responsive-lg table-audit">
                        <table class="table mg-b-0 table-light table-hover" style="width:100%;">
                            <thead>
                            <tr>
                                <th scope="col">Permission Route</th>
                                <th scope="col">Module</th>
                                <th scope="col">Description</th>
                                <th scope="col">Last Date Modified</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($permissions as $permission)
                                <tr>
                                    <td><strong @if($permission->trashed()) style="text-decoration:line-through;" @endif> {{ $permission->name }}</strong></td>
                                    <td>{{ $permission->module }}</td>
                                    <td>{{ $permission->description }}</td>
                                    <td><span class="text-nowrap">{{ Setting::date_for_listing($permission->updated_at) }}</span></td>
                                    <td>
                                        @if($permission->trashed())
                                            <nav class="nav table-options justify-content-end">
                                                <a class="nav-link" href="{{route('permission.restore',$permission->id)}}" title="Restore this permission"><i data-feather="rotate-ccw"></i></a>
                                            </nav>
                                        @else
                                            <nav class="nav table-options justify-content-end flex-nowrap">
                                                @if(ViewPermissions::check_permission(Auth::user()->role_id,'admin/permission/edit') == 1)
                                                    <a href="{{ route('permission.edit',$permission->id) }}" class="nav-link"><i data-feather="edit"></i></a>
                                                @endif
                                                @if(ViewPermissions::check_permission(Auth::user()->role_id,'admin/permission/delete') == 1)
                                                    <a href="#modalDeletePermission" class="nav-link delete_permission" data-pid="{{ $permission->id }}" data-toggle="modal"><i data-feather="trash"></i></a>
                                                @endif
                                            </nav>
                                        @endif

                                        <nav class="nav table-options justify-content-end">

                                        </nav>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6"><center>No Permissions Found...</center></td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mg-t-5">
                    <p class="tx-gray-400 tx-12 d-inline">Showing {{$permissions->firstItem()}} to {{$permissions->lastItem()}} of {{$permissions->total()}} permissions</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-md-right float-md-right mg-t-5">
                    <div>
                        {{ $permissions->appends((array) $filter)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.settings.permission.modal')
@endsection

@section('pagejs')
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
    <script>
        let listingUrl = "{{ route('permission.index') }}";
        let advanceListingUrl = "";
        let searchType = "{{ $searchType }}";
    </script>
    <script src="{{ asset('js/listing.js') }}"></script>

    <script>
        $(document).on('click','.delete_permission', function(){
            $('#modalDeletePermission').show();

            $('#pid').val($(this).data('pid'));
        });
    </script>
@endsection
