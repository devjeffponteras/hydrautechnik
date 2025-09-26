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
                        <li class="breadcrumb-item" aria-current="page">Ecommerce</li>
                        <li class="breadcrumb-item active" aria-current="page">Customers</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Manage Customers</h4>
            </div>
        </div>

        <div class="row row-sm">

            <!-- Start Filters -->
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
                                                <input type="checkbox" id="showInactive" name="showDeleted" class="custom-control-input" @if ($filter->showDeleted) checked @endif>
                                                <label class="custom-control-label" for="showInactive">Show Inactive Customers</label>
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
                                    <input name="search" type="search" id="search" class="form-control"  placeholder="Search by Name" value="{{ $filter->search }}">
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
                        <table class="table mg-b-0 table-light table-hover" style="width:100%;">
                            <thead>
                                <tr>
                                    <th scope="col" width="15%">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Address</th>
                                    <th scope="col">E-Credits</th>
                                    <th scope="col">Subscription</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Last Date Modified</th>
                                    <th scope="col">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    @php 
                                        $user_sub = \App\Models\UsersSubscription::getSubscriptions($user->id);
                                        $files = explode('|',$user->business_proof);
                                    @endphp
                                    <tr>
                                        <th>
                                            <strong @if($user->is_active == 0) style="text-decoration:line-through;" @endif> {{$user->fullname}}</strong><br>{{ $user->mobile }}
                                        </th>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->address }}</td>
                                        <td>{{ $user->ecredits }}</td>
                                        <td>
                                            @if($user_sub)
                                                {{ \App\Models\Subscription::getPlan($user_sub->plan_id)[0]->title }}<br>
                                                <span class="text-secondary">expires on {{ $user_sub->end_date }}</span><br>
                                            @else
                                                {{ 'Not Subscribed' }}
                                            @endif
                                            {{-- @forelse($user_subs as $user_sub)
                                                {{ \App\Models\Subscription::getPlan($user_sub->plan_id)[0]->title }}<br>
                                                <span class="text-secondary">expires on {{ $user_sub->end_date }}</span><br>
                                            @empty
                                                {{ 'Not Subscribed' }}
                                            @endforelse --}}
                                        </td>
                                        <td>
                                            @if($user->is_active == 1)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>{{ Setting::date_for_listing($user->updated_at) }}</td>
                                        <td>
                                            <nav class="nav table-options justify-content-begin">
                                                <a class="nav-link" href="{{ route('customers.show',$user->id) }}" title="View Customer Details"><i data-feather="edit"></i></a>
                                                @if($user->is_active == 1)
                                                    @if (auth()->user()->has_access_to_route('customer.deactivate'))
                                                        <a class="nav-link deactivate_user" data-user_id="{{ $user->id }}" href="#" title="Deactivate Customer" data-toggle="modal" data-target="#modalUserDeactivate"><i data-feather="user-x"></i></a>
                                                    @endif
                                                @else
                                                    @if (auth()->user()->has_access_to_route('customer.activate'))
                                                        <a class="nav-link activate_user" data-user_id="{{ $user->id }}" href="#" title="Activate User" data-toggle="modal" data-target="#modalUserActivate"><i data-feather="user-check"></i></a>
                                                    @endif
                                                @endif
                                            </nav>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" style="text-align: center;"> <p class="text-danger">No customers found.</p></td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- End Pages -->

            <!-- Start Navigation -->
            <div class="col-md-6">
                <div class="mg-t-5">
                    @if ($users->firstItem() == null)
                        <p class="tx-gray-400 tx-12 d-inline">{{__('common.showing_zero_items')}}</p>
                    @else
                        <p class="tx-gray-400 tx-12 d-inline">Showing {{$users->firstItem()}} to {{$users->lastItem()}} of {{$users->total()}} users</p>
                    @endif

                </div>
            </div>
            <div class="col-md-6">
                <div class="text-md-right float-md-right mg-t-5">
                    <div>
                        {{ $users->appends((array) $filter)->links() }}
                    </div>
                </div>
            </div>
            <!-- End Navigation -->

        </div>
    </div>
    @include('admin.ecommerce.customers.modals')
@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
    <script src="{{ asset('scripts/user/scripts.js') }}"></script>

    <script>
        let listingUrl = "{{ route('customers.index') }}";
        let advanceListingUrl = "";
        let searchType = "{{ $searchType }}";
    </script>
    <script src="{{ asset('js/listing.js') }}"></script>
@endsection

@section('customjs')
@endsection
