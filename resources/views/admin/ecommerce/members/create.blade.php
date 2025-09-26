@extends('admin.layouts.app')

@section('pagecss')
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/clockpicker/bootstrap-clockpicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">
    <style>
        .select2 {width:100% !important;}

        .select2-container--default .select2-selection--multiple .select2-selection__choice{
            position: relative;
            margin-top: 4px;
            margin-right: 4px;
            padding: 3px 10px 3px 20px;
            border-color: transparent;
            border-radius: 1px;
            background-color: #0168fa;
            color: #fff;
            font-size: 13px;
            line-height: 1.45;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove{
            color: #fff;
            opacity: .5;
            font-size: 14px;
            font-weight: 400;
            display: inline-block;
            position: absolute;
            top: 4px;
            left: 7px;
            line-height: 1.2;
        }
    </style>
@endsection


@section('content')
<div class="container pd-x-0">
    <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('members.index')}}">Members</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create a Member</li>
                </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1">Create a Member</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <form action="{{ route('members.store') }}" method="post">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <label class="d-block">First Name *</label>
                        <input type="text" name="fname" id="fname" value="{{ old('fname')}}" class="form-control @error('fname') is-invalid @enderror" required  maxlength="50">
                        @error('fname')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="d-block">Last Name *</label>
                        <input type="text" name="lname" id="lname" value="{{ old('lname')}}" class="form-control @error('lname') is-invalid @enderror" required maxlength="50">
                        @error('lname')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="d-block">Email *</label>
                        <input type="email" name="email" id="email" value="{{ old('email')}}" class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="d-block">Permission/Department *</label>
                        <select class="form-control select2" multiple="multiple" name="department_id[]" required>
                            <option label="Choose one"></option>
                            @foreach($departments as $dept)
                                <option value="{{$dept->id}}">{{$dept->title}}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" name="role" value="2">
                    <button class="btn btn-primary btn-sm btn-uppercase" type="submit">Create Member</button>
                    <a class="btn btn-outline-secondary btn-sm btn-uppercase" href="{{ route('members.index') }}">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/jqueryui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('lib/clockpicker/bootstrap-clockpicker.min.js') }}"></script>
    <script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
@endsection

@section('customjs')
    <script>
        $(function() {
            $('.select2').select2({
                placeholder: 'Choose Options'
            });

            $('.selectpicker').selectpicker();
        });
    </script>
@endsection
