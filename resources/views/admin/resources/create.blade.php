@extends('admin.layouts.app')

@section('pagecss')
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/bootstrap-tagsinput/bootstrap-tagsinput.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
    <script src="{{ asset('lib/ckeditor/ckeditor.js') }}"></script>
    <style>
        .image_path {
            opacity: 0;
            width: 0;
            display: none;
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
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{route('resources.index')}}">Cases</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create Cases</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Create Cases </h4>
            </div>
        </div>

        <form id="createForm" method="POST" action="{{ route('resources.store') }}" enctype="multipart/form-data">
            <div class="row row-sm">
                @csrf
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="d-block">Case Number *</label>
                        <input name="name" id="name" value="{{ old('name') }}" required type="text" class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="d-block">Status *</label>
                        <select name="category_id" id="category_id" class="selectpicker mg-b-5" required data-style="btn btn-outline-light btn-md btn-block tx-left" title="Select Category" data-width="100%">
                                <option value="49" selected disabled>-Select Category-</option>
                            @foreach($categories as $category)
                                <option value="{{$category->id}}" @if(old('category_id') == $category->id) selected @endif>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="d-block">Publish Date *</label>
                        <input type="date" name="publish_date" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                    
                    <div class="form-group" style="display: none;">
                        <label class="d-block">Sector</label>
                        <input type="text" class="form-control" data-role="tagsinput" name="sector" id="sector" value="HRET Cases">
                    </div>

                    <div class="form-group" style="display: none;">
                        <label class="d-block">Type of Case</label>
                        <input type="text" class="form-control" data-role="tagsinput" name="case_type" id="case_type" value="HRET">
                    </div>

                    <div class="form-group">
                        <label class="d-block">Description</label>
                        <textarea class="form-control" name="description" rows="3">{{ old("description") }}</textarea>
                    </div>
                </div>

                <div class="col-lg-12" style="display: none;">
                    <div class="form-group">
                        <label class="d-block">Contents</label>
                        <textarea name="contents" id="editor1" rows="10" cols="80">HRET Contents</textarea>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="col-lg-6" style="padding: 0px">
                        <div class="form-group">
                            <label class="d-block">Upload PDF</label>
                            <input type="file" name="file" class="form-control">
                            @error('file')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="d-block">Visibility</label>
                            <div class="custom-control custom-switch @error('status') is-invalid @enderror">
                                <input type="checkbox" class="custom-control-input" name="status" {{ (old("status") ? "checked":"") }} id="customSwitch1">
                                <label class="custom-control-label" id="label_visibility" for="customSwitch1">{{ (old("status") ? "Active" : "Inactive") }}</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 mg-t-30">
                    <input class="btn btn-primary btn-sm btn-uppercase" type="submit" value="Save Cases">
                    <a href="{{ route('resources.index') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Cancel</a>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
    <script src="{{ asset('lib/bootstrap-tagsinput/bootstrap-tagsinput.min.js') }}"></script>
@endsection

@section('customjs')
    <script>
        // var CSRFToken = $('meta[name="csrf-token"]').attr('content');
        // var CKEditorOptions = {
        //   filebrowserImageBrowseUrl: app__url_prefix+'/laravel-filemanager?type=Images',
        //   filebrowserImageUploadUrl: app__url_prefix+'/laravel-filemanager/upload?type=Images&_token='+CSRFToken,
        //   filebrowserBrowseUrl: app__url_prefix+'/laravel-filemanager?type=Files',
        //   filebrowserUploadUrl: app__url_prefix+'/laravel-filemanager/upload?type=Files&_token='+CSRFToken,
        //   allowedContent: true,
        // };
        
        // let editor = CKEDITOR.replace('contents', CKEditorOptions);

        $(function() {
            $('.selectpicker').selectpicker();

            $("#customSwitch1").change(function() {
                if(this.checked) {
                    $('#label_visibility').html('Active');
                }
                else{
                    $('#label_visibility').html('Inactive');
                }
            });
        });
    </script>
@endsection