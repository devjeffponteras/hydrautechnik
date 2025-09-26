@extends('admin.layouts.app')


@section('pagecss')
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
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
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">ECOMMERCE</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('product-attributes.index')}}">Attributes</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Attribute</li>
                </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1">Update Attribute</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <form action="{{ route('product-attributes.update', $formAttribute->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label class="d-block">Name *</label>
                    <input type="text" name="name" id="name" value="{{ $formAttribute->name }}" class="form-control @error('name') is-invalid @enderror" maxlength="150">
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <button class="btn btn-primary btn-sm btn-uppercase" type="submit">Save Changes</button>
                <a class="btn btn-outline-secondary btn-sm btn-uppercase" href="{{ route('product-attributes.index') }}">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
@endsection

@section('customjs')
    <script>
        $('.select2').select2({
            placeholder: 'Choose Options'
        });
    </script>
@endsection
