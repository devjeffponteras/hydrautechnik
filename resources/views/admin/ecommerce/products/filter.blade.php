<div class="col-md-12">
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
                                    <input type="checkbox" id="showFreeOnly" name="showFreeOnly" class="custom-control-input" @if ($filter->showFreeOnly) checked @endif>
                                    <label class="custom-control-label" for="showFreeOnly">Show Free Books only</label>
                                </div>
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
                @if (auth()->user()->has_access_to_route('product.multiple.change.status') || auth()->user()->has_access_to_route('products.multiple.delete'))
                    <div class="list-search d-inline">
                        <div class="dropdown d-inline mg-r-10">
                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Actions
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                @if (auth()->user()->has_access_to_route('product.multiple.change.status'))
                                    <a class="dropdown-item" href="javascript:void(0)" onclick="change_status('PUBLISHED')">{{__('common.publish')}}</a>
                                    <a class="dropdown-item" href="javascript:void(0)" onclick="change_status('PRIVATE')">{{__('common.private')}}</a>
                                @endif

                                @if (auth()->user()->has_access_to_route('products.multiple.delete'))
                                    <a class="dropdown-item tx-danger" href="javascript:void(0)" onclick="delete_category()">{{__('common.delete')}}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="ml-auto bd-highlight mg-t-10 mg-r-10">
                <form class="form-inline" id="searchForm">
                    <div class="search-form mg-r-10">
                        <input name="search" type="search" id="search" class="form-control"  placeholder="Search by Name" value="{{ $filter->search }}">
                        <button class="btn filter" type="button" id="btnSearch"><i data-feather="search"></i></button>
                    </div>
                    <a class="btn btn-success btn-sm mg-b-5" href="javascript:void(0)" data-toggle="modal" data-target="#advanceSearchModal">Advance Search</a>
                </form>
            </div>
            <div class="mg-t-10">
                <a class="btn btn-info btn-sm mg-b-20" href="{{ route('product.download.template') }}">Download Template</a>
                <a class="btn btn-warning btn-sm mg-b-20" href="javascript:void(0)" onclick="$('#prompt-upload').modal('show');">Upload Product</a>
                @if (auth()->user()->has_access_to_route('products.create'))
                    <a class="btn btn-primary btn-sm mg-b-20" href="{{ route('products.create') }}">{{__('standard.products.product.create')}}</a>
                @endif
            </div>
        </div>
    </div>
</div>