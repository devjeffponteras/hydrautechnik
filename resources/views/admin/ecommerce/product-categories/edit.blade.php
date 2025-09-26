@extends('admin.layouts.app')

@section('pagetitle')
    User Management
@endsection

@section('pagecss')
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container pd-x-0">
    <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('product-categories.index')}}">Products</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit a Product Category</li>
                </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1">Edit a Product Category</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <form action="{{ route('product-categories.update',$category->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    {{-- <div class="form-group" hidden>
                        <label class="d-block">Upload Logo *</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input @error('image_url') is-invalid @enderror" name="image_url" id="image_url"  accept="image/*">
                            <label class="custom-file-label" for="image_url" id="img_name">Choose file</label>
                        </div>
                        <p class="tx-10">
                            Required image dimension: 400px by 300px <br /> Maximum file size: 1MB <br /> Required file type: .jpeg .png <br />
                        </p>
                        @error('image_url')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    @if($category->image_url)
                        <div>
                            <img src="{{ $category->image_url }}" height="auto" width="180" alt="Company Logo">
                        </div>
                    @endif --}}

                    <div class="form-group">
                        <label class="d-block">Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name',$category->name)}}" class="form-control @error('name') is-invalid @enderror" maxlength="150">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <small id="category_slug"><a target="_blank" href="{{ url('/').'/products-list?type=category&criteria='.$category->id }}">{{ url('/').'/?type=category&criteria='.$category->id }}</a></small>
                    </div>

                    <div class="form-group">
                        <label class="d-block">Parent Category</label>
                        <select id="parentPage" class="selectpicker mg-b-5 @error('parent_page') is-invalid @enderror" name="parent_page" data-style="btn btn-outline-light btn-md btn-block tx-left" title="- None -" data-width="100%">
                            <option value="0" selected>- None -</option>
                            @foreach ($productCategories as $productCategory)
                                <option value="{{ $productCategory->id }}" @if ($productCategory->id == $category->parent_id) selected @endif>{{ $productCategory->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="d-block">Upload Image</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input @error('mobile_file_url') is-invalid @enderror" name="mobile_file_url" id="mobile_file_url" accept="image/jpeg, image/png, image/gif, video/mp4" title="{{ $category->get_image_file_name() }}">
                            <label class="custom-file-label" for="mobile_file_url" id="mobile_ad_preview">@if (empty($category->mobile_file_url)) Choose file @else {{$category->get_mobile_image_file_name()}} @endif</label>
                            <input type="text" id="current_mobile_file" name="current_mobile_file" value="{{ $category->mobile_file_url }}" hidden/>
                        </div>
                        <p class="tx-10">
                            Required image dimension: {{ env('CATEGORY_IMAGE_WIDTH') }}px by {{ env('CATEGORY_IMAGE_HEIGHT') }}px <br /> Maximum file size: 5MB <br /> Required file type: .jpeg .png
                        </p>
                        @error('mobile_file_url')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div id="mobile_image_div" @if(empty($category->mobile_file_url)) style="display:none;" @endif>
                            
                            {{-- for image --}}
                            <img src="{{ asset($category->mobile_file_url) }}" id="mobile_img_temp" alt="" height="{{ env('CATEGORY_IMAGE_HEIGHT') }}" width="{{ env('CATEGORY_IMAGE_WIDTH') }}" style="display: {{ Str::contains($category->mobile_file_url, 'mp4') ? 'none' : '' }}">  <br /><br />
                        
                            {{-- for video --}}
                            <video autoplay="" muted="" loop="" id="mobile_vid_temp" style="object-fit:none; display: {{ Str::contains($category->mobile_file_url, 'mp4') ? '' : 'none' }}">
                                <source src="{{ asset($category->mobile_file_url) }}" type="video/mp4">
                            </video>

                            <a href="javascript:void(0)" class="btn btn-xs btn-danger" onclick="remove_mobile_image();">Remove Image</a>
                        </div>
                    </div>

                    <div class="form-group banner-image">
                        <label class="d-block">Banner Image</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input @error('banner_url') is-invalid @enderror"  id="banner_url" name="banner_url" @if (!empty($category->banner_url)) title="{{$category->get_image_file_name()}}" @endif>
                            <label class="custom-file-label" for="customFile" id="img_name">@if (empty($category->banner_url)) Choose file @else {{$category->get_image_file_name()}} @endif</label>
                        </div>
                        <p class="tx-10">
                            Required image dimension: {{ env('SUB_BANNER_WIDTH') }}px by {{ env('SUB_BANNER_HEIGHT') }}px <br /> Maximum file size: 1MB <br /> Required file type: .jpeg .png
                        </p>
                        @error('banner_url')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
    
                        <div id="image_div" @if($category->banner_url == null) style="display: none;" @endif>
                            <img src="{{ env('APP_URL') . '/' . old('banner_url', $category->banner_url) }}" height="{{ env('IMAGE_DISPLAY_HEIGHT') }}" width="{{ env('IMAGE_DISPLAY_WIDTH') }}" id="img_temp" alt="">  <br /><br />
                            <input type="text" id="current_banner_file" name="current_banner_file" value="{{ $category->banner_url }}" hidden/>
                            <a href="javascript:void(0)" class="btn btn-sm btn-danger" onclick="remove_image();">Remove Image</a>
                        </div>
                    </div>

                    {{-- <div class="form-group">
                        <label class="d-block">Description *</label>
                        <textarea rows="3" class="form-control @error('description') is-invalid @enderror" name="description">{{ old('description',$category->description) }}</textarea>
                        @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div> --}}

                    <div class="form-group">
                        <label class="d-block">Visibility</label>
                        <div class="custom-control custom-switch @error('visibility') is-invalid @enderror">
                            <input type="checkbox" class="custom-control-input" name="visibility" {{ ($category->status == 'PUBLISHED' ? "checked":"") }} id="customSwitch1">
                            <label class="custom-control-label" id="label_visibility" for="customSwitch1">{{ucwords(strtolower($category->status))}}</label>
                        </div>
                        @error('visibility')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button class="btn btn-primary btn-sm btn-uppercase" type="submit">Update Category</button>
                    <a class="btn btn-outline-secondary btn-sm btn-uppercase" href="{{ route('product-categories.index') }}">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('js/file-upload-validation.js') }}"></script>
@endsection

@section('customjs')
    <script>
        $(function() {
            $('.selectpicker').selectpicker();
        });

        $("#customSwitch1").change(function() {
            if(this.checked) {
                $('#label_visibility').html('Published');
            }
            else{
                $('#label_visibility').html('Private');
            }
        });

        /** Generation of the page slug **/
        jQuery('#name').change(function(){

            var url = $('#name').val();

            $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
                }
            })

            $.ajax({
                type: "POST",
                url: "/admin/product-category-get-slug",
                data: { url: url }
            })

            .done(function(response){
                slug_url = '{{env('APP_URL')}}/product-categories/'+response;
                $('#category_slug').html("<a target='_blank' href='"+slug_url+"'>"+slug_url+"</a>");
            });
        });
    </script>
    
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
            let requiredWidth = "{{ env('CATEGORY_IMAGE_WIDTH') }}";
            let requiredHeight =  "{{ env('CATEGORY_IMAGE_HEIGHT') }}";

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
            $('#current_mobile_file').val('');
            $('#mobile_img_temp').attr('src', '');
            $('#mobile_image_div').hide();
        }
    </script>
    <script>
        function readURL(file) {
            let reader = new FileReader();

            reader.onload = function(e) {
                $('#img_name').html(file.name);
                $('#banner_url').attr('title', file.name);
                $('#img_temp').attr('src', e.target.result);
            }

            reader.readAsDataURL(file);
            $('#image_div').show();
        }

        $("#banner_url").change(function(evt) {

            $('#img_name').html('Choose file');
            $('#img_temp').attr('src', '');
            $('#image_div').hide();

            let files = evt.target.files;
            let maxSize = 1;
            let validateFileTypes = ["image/jpeg", "image/png"];
            let requiredWidth = "{{ env('SUB_BANNER_WIDTH') }}";
            let requiredHeight =  "{{ env('SUB_BANNER_HEIGHT') }}";

            validate_files(files, readURL, maxSize, validateFileTypes, requiredWidth, requiredHeight, remove_banner_value_when_error);
        });

        function remove_banner_value_when_error()
        {
            $('#banner_url').val('');
            $('#banner_url').removeAttr('title');
        }

        function remove_image() {
            $('#img_name').html('Choose file');
            $('#banner_url').removeAttr('title');
            $('#banner_url').val('');
            $('#current_banner_file').val('');
            $('#img_temp').attr('src', '');
            $('#image_div').hide();
        }
    </script>
@endsection
