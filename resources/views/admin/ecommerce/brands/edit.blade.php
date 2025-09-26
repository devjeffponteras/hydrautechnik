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
                    <li class="breadcrumb-item" aria-current="page"><a href="{{route('product-categories.index')}}">Products</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Brand</li>
                </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1">Edit Brand</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <form action="{{ route('brands.update', $brand->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="form-group">
                    <label class="d-block">Name *</label>
                    <input type="text" name="name" id="name" value="{{ old('name',$brand->name)}}" class="form-control @error('name') is-invalid @enderror" maxlength="150">
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="d-block">Description *</label>
                    <textarea rows="3" class="form-control @error('description') is-invalid @enderror" name="description">{{ old('description',$brand->description) }}</textarea>
                    @error('description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                @php
                    $arr_categories = [];
                    foreach($brand->product_categories as $cat){
                        array_push($arr_categories,$cat->product_category_id);
                    }
                @endphp

                <div class="form-group">
                    <label class="d-block">Product Categories *</label>
                    <select class="form-control select2" multiple="multiple" name="categories[]" id="categories" required>
                        <option label="Choose one"></option>
                        @foreach($categories as $category)
                            <option @if(in_array($category->id,$arr_categories)) selected @endif value="{{$category->id}}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <span class="text-danger">
                        Important : Adding or removing a category will change the selected brand sidebar menu into default order.
                    </span>
                    @error('categories')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="d-block">Upload Logo *</label>
                    <input type="file" name="image_url" class="form-control">
                    <p class="tx-10">
                        Required image dimension: 400px by 300px <br /> Maximum file size: 1MB <br /> Required file type: .jpeg .png <br />
                    </p>
                    @error('image_url')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <img src="{{ $brand->image_url }}" height="auto" width="180" alt="Company Logo">
                </div>

                <div class="form-group">
                    <label class="d-block">Status</label>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" name="status" {{ ($brand->status == 'Active' ? "checked":"") }} id="customSwitch1">
                        <label class="custom-control-label" id="label_visibility" for="customSwitch1">{{ucwords(strtolower($brand->status))}}</label>
                    </div>
                </div>

                <button class="btn btn-primary btn-sm btn-uppercase" type="submit">Update Brand</button>
                <a class="btn btn-outline-secondary btn-sm btn-uppercase" href="{{ route('brands.index') }}">Cancel</a>
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

        $("#customSwitch1").change(function() {
            if(this.checked) {
                $('#label_visibility').html('Active');
            }
            else{
                $('#label_visibility').html('Inactive');
            }
        });
    </script>
@endsection
