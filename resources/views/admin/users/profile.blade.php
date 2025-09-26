@extends('admin.layouts.app')

@section('pagetitle')
    User Profile
@endsection

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('css/dashforge.profile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modals.css') }}">
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container pd-x-0 tx-13">
    <div class="media d-block d-lg-flex">
        <div class="profile-sidebar profile-sidebar-two pd-lg-r-15">
            <div class="row">
                <div class="col-sm-3 col-md-2 col-lg">
                    @if($user->is_active == 1)
                        <div class="avatar avatar-xxl avatar-online"><img src="{{ asset('images/user.png')}}" class="rounded-circle" alt=""></div>
                    @else
                        <div class="avatar avatar-xxl avatar-offline"><img src="{{ asset('images/user.png')}}" class="rounded-circle" alt=""></div>
                    @endif
                </div>
                <div class="col-sm-8 col-md-7 col-lg mg-t-20 mg-sm-t-0 mg-lg-t-25">
                    <input type="hidden" id="user_id" value="{{ $user->id }}">
                    <h5 class="mg-b-2 tx-spacing--1">{{ $user->fullname }}</h5>
                    <p class="tx-color-03 mg-b-25">{{ User::userRole($user->role_id) }}</p>
                </div>
            </div>
        </div>
        
        <div class="row row-sm">
            <div class="col-md-12">
                <div class="filter-buttons mg-b-10">
                    <div class="d-md-flex bd-highlight">
                        <div class="bd-highlight mg-t-10">
                            <div class="dropdown d-inline">
                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Filters
                                </button>
                                <div class="dropdown-menu">
                                    <form id="filterForm" class="pd-20">
                                        <div class="form-group">
                                            <label for="exampleDropdownFormEmail1">{{__('common.sort_by')}}</label>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="orderBy1" name="orderBy" class="custom-control-input" value="updated_at" @if ($filter->orderBy == 'updated_at') checked @endif>
                                                <label class="custom-control-label" for="orderBy1">Activity Date</label>
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
                        <div class="ml-auto bd-highlight">
                                <a class="btn btn-primary btn-sm mg-b-5" href="{{route('dashboard')}}"><i class="fa fa-arrow-left"></i> Go back to Dashboard</a>
                            </form>
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
                                <th scope="col">My Recent Activities</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($logs as $log)
                                <tr>
                                    <td>{{ ActivityLog::getLogs($log->activity_type,$log->id) }} {{ Setting::date_for_listing($log->activity_date) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mg-t-5">
                    <p class="tx-gray-400 tx-12 d-inline">Showing {{$logs->firstItem()}} to {{$logs->lastItem()}} of {{$logs->total()}} activities</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-md-right float-md-right mg-t-5">
                    <div>
                        {{ $logs->appends((array) $filter)->links() }}
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@include('admin.users.modals')
@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('scripts/user/scripts.js') }}"></script>
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>

    <script>
        let listingUrl = "{{ route('users.show',$user->id) }}";
        let advanceListingUrl = "";
        let searchType = "{{ $searchType }}";
    </script>
    <script src="{{ asset('js/listing.js') }}"></script>
@endsection

@section('customjs')
    <script>
        $(".js-range-slider").ionRangeSlider({
            min: 10,
            max: 100
		});

  //       /*** handles the click function on filter classes, redirects it to function filter ***/
  //       $('.filter').click(function(){
  //           filter();
		// });
		
		// /*** generate the parameters for filtering of pages ***/
  //       function filter(){

		// 	var url = '';
  //           var uid = $('#user_id').val();

		// 	if($('#sortDate').is(':checked'))
		// 		url += '&sort=activity_date';
		// 	if($('#orderAsc').is(':checked'))
		// 		url += '&order=asc';
		// 	if($('#orderDesc').is(':checked'))
		// 		url += '&order=desc';
		// 	if($('#pageLimit').val())
		// 		url += '&id='+uid+'&pageLimit='+$('#pageLimit').val();
		// 	url = url.substring(1, url.length);

		// 	window.location.href = "{{ route('user.activity.search') }}?"+url;

		// }
    </script>
@endsection