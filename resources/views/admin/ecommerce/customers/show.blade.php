@extends('admin.layouts.app')

@section('pagetitle')
    Customer Account Settings
@endsection

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('css/dashforge.profile.css') }}">
	<link href="{{ asset('lib/clockpicker/bootstrap-clockpicker.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Customer</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Customer Account Settings</h4>
            </div>
        </div>

        <div class="alert alert-danger print-error-msg" style="display:none" role="alert">
            <ul></ul>
        </div>

        <div class="row row-sm">
            <div class="col-lg-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Profile</a>
                    </li>
                </ul>
                <div class="tab-content rounded bd bd-gray-300 bd-t-0 pd-20" id="myTabContent">
                    <form id="customerForm" action="{{ route('customer.update') }}" method="post">
                    @csrf
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="media mg-b-30 mg-t-20">
                                @if(Auth::user()->avatar == '')
                                    <img src="{{ asset('images/user.png') }}" id="userLogo" class="wd-100 rounded-circle mg-r-20" alt="">
                                @else
                                    <img src="{{ $user->avatar }}" id="userLogo" class="wd-100 rounded-circle mg-r-20" alt="">
                                @endif
                                <div class="media-body pd-t-30">
                                    <h5 class="mg-b-0 tx-inverse tx-bold">{{ $user->fullname }}</h5>
                                    <p>{{$user->email}}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="d-block">Mobile #</label>
                                <input type="text" class="form-control" readonly value="{{$user->mobile}}">
                            </div>

                            <div class="form-group">
                                <label class="d-block">Telephone #</label>
                                <input type="text" class="form-control" readonly value="{{$user->phone}}">
                            </div>

                            <div class="form-group">
                                <label class="d-block">Address</label>
                                <input type="text" class="form-control" readonly value="{{$user->address}}">
                            </div>

                            <div class="form-group">
                                <label class="d-block">Ecredits</label>
                                <input type="decimal" min="0" name="ecredits" class="form-control" value="{{$user->ecredits}}" oninput="this.value = Math.max(0, this.value);" onclick="select()">
                            </div>

                            @php 
                                $user_subs = \App\Models\UsersSubscription::getSubscriptionsList($user->id);
                                $files = explode('|',$user->business_proof);
                            @endphp

                            @if(!$user_subs->isEmpty()) 

                                <br>
                                <h5 class="mg-b-0 tx-spacing--1">Subscriptions</h5>
                                <hr>

                                <div class="col-12" id="coupon-date-time-form" style="display:block;">

                                    @foreach($user_subs as $user_sub)

                                        <h6 class="mt-1 text-success">{{ \App\Models\Subscription::getPlan($user_sub->plan_id)[0]->title }}</h6>
                                        <div class="row mt-1">
                                            <div class="col-6">
                                                <label class="d-block">Expiry Date</label>
                                                <input name="end_date[]" type="text" id="dateTo" class="form-control" placeholder="To" autocomplete="off" value="{{ \Carbon\Carbon::parse($user_sub->end_date)->format('Y-m-d') }}">
                                            </div>
                                            <div class="col-6">
                                                <label class="d-block">Time</label>
                                                <input name="end_time[]" type="time" class="form-control" autocomplete="off" value="{{ \Carbon\Carbon::parse($user_sub->end_date)->format('H:i:s') }}">
                                            </div>
                                            <input name="user_sub_id[]" value="{{ $user_sub->id }}" hidden>
                                        </div>
                                    @endforeach
                                </div>

                            @endif

                            {{-- hidden inputs --}}
                            <input name="user_id" class="form-control" value="{{$user->id}}" hidden>
                            
                            <button class="btn btn-primary btn-sm btn-uppercase" type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="pos-fixed b-10 r-10">
        <div id="toast_successs" class="toast bg-success bd-0 wd-350" data-delay="3000" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-body pd-6 tx-white">
                <button type="button" class="ml-2 mb-1 close tx-normal tx-shadow-none" data-dismiss="toast" aria-label="Close">
                    <span class="tx-white" aria-hidden="true">&times;</span>
                </button>
                <h6 class="mg-b-15 mg-t-15 tx-white"><i data-feather="alert-circle"></i> SUCCESS!</h6>
                <p id="a_msg"></p>
            </div>
        </div>
    </div>

@endsection

@section('pagejs')
    <script src="{{ asset('scripts/account/update.js') }}"></script>
	<script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
	<script src="{{ asset('lib/jqueryui/jquery-ui.min.js') }}"></script>
	<script src="{{ asset('lib/clockpicker/bootstrap-clockpicker.min.js') }}"></script>

    {{--    Image validation--}}
    <script>
        let BANNER_WIDTH = "{{ env('USER_LOGO_WIDTH') }}";
        let BANNER_HEIGHT =  "{{ env('USER_LOGO_HEIGHT') }}";
    </script>
    <script src="{{ asset('js/image-upload-validation.js') }}"></script>
    {{--    End Image validation--}}
@endsection

@section('customjs')
    <script>
        function readURL(file) {
            let reader = new FileReader();

            reader.onload = function(e) {
                $('#userLogo').attr('src', e.target.result);
                $('#img_name').html(file.name);
            }

            reader.readAsDataURL(file);
        }

        $("#user_image").change(function(evt) {
            validate_images(evt, readURL);
        });
        

        $('.datetime').clockpicker();

        $('.singlecalendar').datepicker({
            dateFormat: 'yy-mm-dd'
        });

        var dateToday = new Date(); 
        $('#dateFrom').datepicker({
            dateFormat: 'yy-mm-dd',
            minDate: dateToday,
        });
        $('#dateTo').datepicker({
            dateFormat: 'yy-mm-dd',
            minDate: dateToday,
        });
    </script>
@endsection

