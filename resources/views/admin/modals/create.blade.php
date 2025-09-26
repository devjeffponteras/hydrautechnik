@extends('admin.layouts.app')

@section('pagecss')
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/bootstrap-tagsinput/bootstrap-tagsinput.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
    <script src="{{ asset('lib/ckeditor/ckeditor.js') }}"></script>
    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">
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
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{route('page-modals.index')}}">Page Modals</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create Page Modal</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Create Page Modal </h4>
            </div>
        </div>

        <form id="createForm" method="POST" action="{{ route('page-modals.store') }}" enctype="multipart/form-data">
            <div class="row row-sm">
                @csrf
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="d-block">Name *</label>
                        <input name="name" id="name" value="{{ old('name') }}" required type="text" class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="d-block">Pages *</label>
                        <select class="form-control select2 @error('pages') is-invalid @enderror" multiple="multiple" name="pages[]" id="pages" required>
                            <option label="Choose multiple pages"></option>
                            @foreach($pages as $page)
                                <option value="{{ $page['name'] }}">{{ $page['name'] }}</option>
                            @endforeach
                        </select>
                        @error('pages')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="d-block">Contents</label>
                        <textarea name="content" id="editor1" rows="10" cols="80">{{ old('content') }}</textarea>
                        @error('content')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="d-block">Status</label>
                        <div class="custom-control custom-switch @error('status') is-invalid @enderror">
                            <input type="checkbox" class="custom-control-input" name="status" {{ (old("status") ? "checked":"") }} id="customSwitch1">
                            <label class="custom-control-label" id="label_visibility" for="customSwitch1">{{ (old("status") ? "Active" : "Inactive") }}</label>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 mg-t-30">
                    <input class="btn btn-primary btn-sm btn-uppercase" type="submit" value="Save Page Modal">
                    <a href="{{ route('page-modals.index') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Cancel</a>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
@endsection

@section('customjs')
    <script>
        var CSRFToken = $('meta[name="csrf-token"]').attr('content');
        var CKEditorOptions = {
          filebrowserImageBrowseUrl: app__url_prefix+'/laravel-filemanager?type=Images',
          filebrowserImageUploadUrl: app__url_prefix+'/laravel-filemanager/upload?type=Images&_token='+CSRFToken,
          filebrowserBrowseUrl: app__url_prefix+'/laravel-filemanager?type=Files',
          filebrowserUploadUrl: app__url_prefix+'/laravel-filemanager/upload?type=Files&_token='+CSRFToken,
          allowedContent: true,
        };
        
        let editor = CKEDITOR.replace('content', CKEditorOptions);

        $(function() {
            $('.select2').select2({
                placeholder: 'Choose Options'
            });

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
