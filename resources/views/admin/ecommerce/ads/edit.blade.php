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
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{route('ads.index')}}">BANNER ADS</a></li>
                        <li class="breadcrumb-item active" aria-current="page">EDIT AD</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Edit Ad </h4>
            </div>
        </div>

        <form id="createForm" method="POST" action="{{ route('ads.update',$ad->id) }}" enctype="multipart/form-data">
            <div class="row row-sm">
                @method('PUT')
                @csrf
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="d-block">Name *</label>
                        <input name="name" id="name" value="{{ $ad->name }}" required type="text" class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- DESKTOP AD --}}
                    <div class="form-group">
                        <label class="d-block">Desktop Ad Image/Video</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input @error('file_url') is-invalid @enderror" name="file_url" id="file_url" accept="image/jpeg, image/png, image/gif, video/mp4" title="{{ $ad->get_image_file_name() }}">
                            <label class="custom-file-label" for="file_url" id="ad_preview">@if (empty($ad->file_url)) Choose file @else {{$ad->get_image_file_name()}} @endif</label>
                            <input type="text" id="current_file" name="current_file" value="{{ $ad->file_url }}" hidden/>
                        </div>
                        <p class="tx-10">
                            Required image dimension: {{ env('AD_BANNER_WIDTH') }}px by {{ env('AD_BANNER_HEIGHT') }}px <br /> Maximum file size: 5MB <br /> Required file type: .jpeg .png .mp4 .gif
                        </p>
                        @error('file_url')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div id="image_div" @if(empty($ad->file_url)) style="display:none;" @endif>

                            {{-- for image --}}
                            <img src="{{ asset($ad->file_url) }}" id="img_temp" alt="" height="{{ env('AD_BANNER_HEIGHT') }}" width="{{ env('AD_BANNER_WIDTH') }}" style="display: {{ Str::contains($ad->file_url, 'mp4') ? 'none' : '' }}">  <br /><br />
                        
                            {{-- for video --}}
                            <video autoplay="" muted="" loop="" id="vid_temp" style="object-fit:none; display: {{ Str::contains($ad->file_url, 'mp4') ? '' : 'none' }}">
                                <source src="{{ asset($ad->file_url) }}" type="video/mp4">
                            </video>

                            <a href="javascript:void(0)" class="btn btn-xs btn-danger" onclick="remove_image();">Remove Image</a>
                        </div>
                    </div>
                    
                    {{-- MOBILE AD --}}
                    <div class="form-group">
                        <label class="d-block">Mobile Ad Image/Video</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input @error('mobile_file_url') is-invalid @enderror" name="mobile_file_url" id="mobile_file_url" accept="image/jpeg, image/png, image/gif, video/mp4" title="{{ $ad->get_image_file_name() }}">
                            <label class="custom-file-label" for="mobile_file_url" id="mobile_ad_preview">@if (empty($ad->mobile_file_url)) Choose file @else {{$ad->get_mobile_image_file_name()}} @endif</label>
                            <input type="text" id="current_mobile_file" name="current_mobile_file" value="{{ $ad->mobile_file_url }}" hidden/>
                        </div>
                        <p class="tx-10">
                            Required image dimension: {{ env('MOBILE_AD_BANNER_WIDTH') }}px by {{ env('MOBILE_AD_BANNER_HEIGHT') }}px <br /> Maximum file size: 5MB <br /> Required file type: .jpeg .png .mp4 .gif
                        </p>
                        @error('mobile_file_url')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div id="mobile_image_div" @if(empty($ad->mobile_file_url)) style="display:none;" @endif>
                            
                            {{-- for image --}}
                            <img src="{{ asset($ad->mobile_file_url) }}" id="mobile_img_temp" alt="" height="{{ env('MOBILE_AD_BANNER_HEIGHT') }}" width="{{ env('MOBILE_AD_BANNER_WIDTH') }}" style="display: {{ Str::contains($ad->mobile_file_url, 'mp4') ? 'none' : '' }}">  <br /><br />
                        
                            {{-- for video --}}
                            <video autoplay="" muted="" loop="" id="mobile_vid_temp" style="object-fit:none; display: {{ Str::contains($ad->mobile_file_url, 'mp4') ? '' : 'none' }}">
                                <source src="{{ asset($ad->mobile_file_url) }}" type="video/mp4">
                            </video>

                            <a href="javascript:void(0)" class="btn btn-xs btn-danger" onclick="remove_mobile_image();">Remove Image</a>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="d-block">Url</label>
                        <input name="url" id="url" value="{{ $ad->url }}" type="text" class="form-control @error('url') is-invalid @enderror">
                        @error('url')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="d-block">Pages *</label>
                        <select class="form-control select2 @error('pages') is-invalid @enderror" multiple="multiple" name="pages[]" id="pages" required>
                            <option label="Choose multiple pages"></option>
                            @foreach($pages as $page)

                                @php($is_selected = "")
                                @foreach($ad_pages as $ad_page)
                                    @if($ad_page->page_id == $page->id)
                                        @php($is_selected = "selected")
                                    @endif
                                @endforeach

                                <option value="{{ $page['id'] }}" {{ $is_selected }}> {{ $page['name'] }}</option>

                            @endforeach
                        </select>
                        @error('pages')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="d-block">Expiration Date *</label>
                        <input name="expiration_date" id="expiration_date" value="{{ $ad->expiration_date }}" required type="date" class="form-control @error('expiration_date') is-invalid @enderror">
                        @error('expiration_date')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="d-block">Status</label>
                        <div class="custom-control custom-switch @error('status') is-invalid @enderror">
                            <input type="checkbox" class="custom-control-input" name="status"{{ $ad->status ? "checked":"" }} id="customSwitch1">
                            <label class="custom-control-label" id="label_visibility" for="customSwitch1">{{ $ad->status ? "Active" : "Inactive" }}</label>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 mg-t-30">
                    <input class="btn btn-primary btn-sm btn-uppercase" type="submit" value="Save Ad Changes">
                    <a href="{{ route('ads.index') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Cancel</a>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('js/file-upload-validation.js') }}"></script>
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
    
    {{-- DESKTOP AD --}}
    <script>
        function readURL(file) {
            let reader = new FileReader();

            reader.onload = function(e) {
                $('#ad_preview').html(file.name);
                $('#file_url').attr('title', file.name);
                $('#img_temp').attr('src', e.target.result);
                $('#vid_temp').attr('src', e.target.result);
            }

            reader.readAsDataURL(file);
            $('#image_div').show();
            
            if (file.type === 'video/mp4') {
                $('#img_temp').hide();
                $('#vid_temp').show();
            }
            else{
                $('#img_temp').show();
                $('#vid_temp').hide();
            }
        }

        $("#file_url").change(function(evt) {

            $('#ad_preview').html('Choose file');
            $('#img_temp').attr('src', '');
            $('#image_div').hide();

            let files = evt.target.files;
            let maxSize = 5;
            let validateFileTypes = ["image/jpeg", "image/png", "image/gif", "video/mp4"];
            let requiredWidth = "{{ env('AD_BANNER_WIDTH') }}";
            let requiredHeight =  "{{ env('AD_BANNER_HEIGHT') }}";

            validate_files(files, readURL, maxSize, validateFileTypes, requiredWidth, requiredHeight, empty_banner_value);
        });

        function empty_banner_value()
        {
            $('#file_url').removeAttr('title');
            $('#file_url').val('');
        }

        function remove_image() {
            $('#ad_preview').html('Choose file');
            $('#file_url').removeAttr('title');
            $('#file_url').val('');
            $('#file_url').prop('required', true);
            $('#current_file').val('');
            $('#img_temp').attr('src', '');
            $('#image_div').hide();
        }
    </script>

    {{-- MOBILE AD --}}
    <script>
        function mobileReadURL(file) {
            let reader = new FileReader();

            reader.onload = function(e) {
                $('#mobile_ad_preview').html(file.name);
                $('#mobile_file_url').attr('title', file.name);
                $('#mobile_img_temp').attr('src', e.target.result);
                $('#mobile_vid_temp').attr('src', e.target.result);
            }

            reader.readAsDataURL(file);
            $('#mobile_image_div').show();

            if (file.type === 'video/mp4') {
                $('#mobile_img_temp').hide();
                $('#mobile_vid_temp').show();
            }
            else{
                $('#mobile_img_temp').show();
                $('#mobile_vid_temp').hide();
            }
        }

        $("#mobile_file_url").change(function(evt) {

            $('#mobile_ad_preview').html('Choose file');
            $('#mobile_img_temp').attr('src', '');
            $('#mobile_image_div').hide();

            let files = evt.target.files;
            let maxSize = 5;
            let validateFileTypes = ["image/jpeg", "image/png", "image/gif", "video/mp4"];
            let requiredWidth = "{{ env('MOBILE_AD_BANNER_WIDTH') }}";
            let requiredHeight =  "{{ env('MOBILE_AD_BANNER_HEIGHT') }}";

            validate_files(files, mobileReadURL, maxSize, validateFileTypes, requiredWidth, requiredHeight, empty_mobile_banner_value);
        });

        function empty_mobile_banner_value()
        {
            $('#mobile_file_url').removeAttr('title');
            $('#mobile_file_url').val('');
        }

        function remove_mobile_image() {
            $('#mobile_ad_preview').html('Choose file');
            $('#mobile_file_url').removeAttr('title');
            $('#mobile_file_url').val('');
            $('#mobile_file_url').prop('required', true);
            $('#mobile_img_temp').attr('src', '');
            $('#mobile_image_div').hide();
        }
    </script>
@endsection
