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
                        <li class="breadcrumb-item active" aria-current="page">Edit Cases</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Edit Cases </h4>
            </div>
        </div>

        <form id="createForm" method="POST" action="{{ route('resources.update', $resource->id) }}" enctype="multipart/form-data">
            <div class="row row-sm">
                @csrf
                @method('PUT')
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="d-block">Case Number *</label>
                        <input name="name" id="name" value="{{ $resource->name }}" required type="text" class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="d-block">Status *</label>
                        <select name="category_id" class="selectpicker mg-b-5" required data-style="btn btn-outline-light btn-md btn-block tx-left" title="Select Category" data-width="100%">
                            @foreach($categories as $category)
                                <option value="{{$category->id}}" @if($resource->category_id == $category->id) selected @endif>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="d-block">Publish Date *</label>
                        <input type="date" name="publish_date" class="form-control" value="{{ $resource->publish_date }}">
                    </div>

                    <div class="form-group" style="display: none;">
                        <label class="d-block">Sector</label>
                        <input type="text" class="form-control" data-role="tagsinput" name="sector" id="sector" value="{{ $resource->sector }}">
                    </div>

                    <div class="form-group" style="display: none;">
                        <label class="d-block">Type of Case</label>
                        <input type="text" class="form-control" data-role="tagsinput" name="case_type" id="case_type" value="{{ $resource->case_type }}">
                    </div>

                    <div class="form-group">
                        <label class="d-block">Description</label>
                        <textarea class="form-control" name="description" rows="3">{{$resource->description}}</textarea>
                    </div>
                </div>

                <div class="col-lg-12" style="display: none;">
                    <div class="form-group">
                        <label class="d-block">Contents</label>
                        <textarea name="contents" id="editor1" rows="10" cols="80">
                             {{ $resource->contents }}
                        </textarea>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="col-lg-6" style="padding: 0px">
                        <div class="form-group">
                            <label class="d-block">Upload PDF</label>
                            <input type="file" name="file" class="form-control">

                            @if(isset($resource->pdf_path))
                                @php
                                    $file = explode('/', $resource->pdf_path);
                                @endphp
                                <br>
                                <a href="{{ asset('storage/'.$resource->pdf_path) }}" class="text-primary" target="_blank">{{end($file)}}</a>
                                <br><br>
                                <button type="button" class="btn btn-sm btn-danger" onclick="remove_file('{{$resource->id}}')">Remove</button>
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="d-block">Visibility</label>
                            <div class="custom-control custom-switch @error('status') is-invalid @enderror">
                                <input type="checkbox" class="custom-control-input" name="status" id="customSwitch1" {{ (old("status") == "ON" || $resource->status == "Active" ? "checked":"") }}>
                                <label class="custom-control-label" id="label_visibility" for="customSwitch1">{{ucfirst(strtolower($resource->status))}}</label>
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

    <div class="modal effect-scale" id="prompt-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="post" action="{{route('resources.remove.file')}}">
                    @csrf
                    <div class="modal-header">
                        <input type="hidden" name="resourceId" id="resourceId">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Remove File</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>You are about to remove this file. Do you want to continue?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-danger">Yes, Delete</button>
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
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

        function remove_file(id){
            $('#prompt-delete').modal('show');     
            $('#resourceId').val(id);  
        }
    </script>
@endsection