@extends('admin.layouts.app')

@section('pagecss')
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/bootstrap-tagsinput/bootstrap-tagsinput.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom-product.css') }}" rel="stylesheet">
    <script src="{{ asset('lib/ckeditor/ckeditor.js') }}"></script>
    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">
    <style>
        #errorMessage {
            list-style-type: none;
            padding: 0;
            margin-bottom: 0px;
        }
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
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{route('products.index')}}">Products</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Bundle</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Edit a Bundle</h4>
            </div>
        </div>
        <form id="updateForm" method="POST" action="{{ route('products.update',$product->id) }}" enctype="multipart/form-data">
            <div class="row row-sm">
                @method('PUT')
                @csrf
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="d-block">SKU *</label>
                        <input name="sku" id="sku" value="{{ old('sku', $product->sku) }}" type="text" class="form-control" maxlength="150">
                        @error('sku')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="d-block">Name *</label>
                        <input required name="name" id="name" value="{{ old('name',$product->name) }}" type="text" class="form-control @error('name') is-invalid @enderror" maxlength="150">
                        <small id="product_slug"><a target="_blank" href="{{ route('product.details', $product->slug)}}">{{ route('product.details', $product->slug)}}</a></small>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group" style="display: none;">
                        <label class="d-block">Author</label>
                        <input name="author" id="author" value="{{ old('author',$product->author) }}" type="text" class="form-control @error('author') is-invalid @enderror" maxlength="150">
                        @error('author')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group" style="display: none;">
                        <label class="d-block">Book Type</label>
                        <input name="book_type" id="book_type" value="{{ old('book_type', $product->book_type) }}" type="text" class="form-control" maxlength="150">
                        @error('book_type')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="d-block">Category *</label>
                        <select name="category_id" id="category_id" class="selectpicker mg-b-5" data-style="btn btn-outline-light btn-md btn-block tx-left" title="Select category" data-width="100%" data-live-search="true">
                            @foreach ($parentCategories as $parentCategory)
                                <option style="font-weight: bold;" value="{{ $parentCategory->id }}" @if($product->category_id == $parentCategory->id) selected @endif>{{ strtoupper($parentCategory->name) }}</option>
                                @if(count($parentCategory->child_categories))
                                    @include('admin.ecommerce.product-categories.edit-subcategories',['subcategories' => $parentCategory->child_categories])
                                @endif
                            @endforeach
                        </select>
                        @error('category_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    
                    <div class="form-group">
                        @php
                            $bundle_prods = explode(',',$product->bundle_products);
                            $arr_products = [];
                            foreach($bundle_prods as $bundle_prod){
                                if($bundle_prod != ""){
                                    array_push($arr_products, $bundle_prod);
                                }   
                            }
                        @endphp
                        
                        <label class="d-block">Select Products *</label>
                        <select class="form-control select2 @error('bundle_products') is-invalid @enderror" multiple="multiple" name="bundle_products[]" id="bundle_products" required>
                            <option label="Choose multiple products"></option>
                            @foreach($products as $product)
                                <option value="{{ $product['id'] }}" @if(in_array($product['id'], $arr_products)) selected @endif>{{ $product['name'] }}</option>
                            @endforeach
                        </select>
                        @error('bundle_products')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                        {{-- hidden input --}}
                        <input name="is_bundle" value="1" hidden/>
                    </div>

                    <div class="form-group">
                        <label class="d-block">Price (in Php) *</label>
                        <input required type="number" class="form-control @error('price') is-invalid @enderror" name="price" id="price" value="{{ old('price', number_format($product->price,2,'.','')) }}" min="0.00" step="0.01">
                        @error('price')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="d-block">Discounted Price (in Php)</label>
                        <input type="number" class="form-control @error('discount_price') is-invalid @enderror" name="discount_price" id="discount_price" value="{{ old('discount_price', number_format($product->discount_price,2,'.','')) }}" min="0.00" step="0.01">
                        @error('discount_price')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- <div class="form-group">
                        <label class="d-block">Mobile Price (in Php) *</label>
                        <input required type="number" class="form-control @error('mobile_price') is-invalid @enderror" name="mobile_price" id="mobile_price" value="{{ old('mobile_price', number_format($product->mobile_price,2,'.','')) }}" min="0.00" step="0.01">
                        @error('mobile_price')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="d-block">Mobile Discounted Price (in Php)</label>
                        <input type="number" class="form-control @error('mobile_discount_price') is-invalid @enderror" name="mobile_discount_price" id="mobile_discount_price" value="{{ old('mobile_discount_price', number_format($product->mobile_discount_price,2,'.','')) }}" min="0.00" step="0.01">
                        @error('mobile_discount_price')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div> --}}
                    
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="d-block" id="long_descriptionLabel">Description *</label>
                        <textarea name="long_description" id="editor1" rows="10" cols="80">{{ old('long_description', $product->description) }}</textarea>
                        @error('long_description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <span class="invalid-feedback" role="alert" id="long_descriptionRequired" style="display: none;">
                            <strong>The description field is required</strong>
                        </span>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="d-block">Reorder Point</label>
                        <input name="reorder_point" id="reorder_point" value="{{ old('reorder_point',$product->reorder_point) }}" type="number" min="0" class="form-control" maxlength="250">
                        @error('reorder_point')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group" style="display: none;">
                        <label class="d-block">Size</label>
                        <input type="text" class="form-control" name="size" id="size" value="{{ old('size', $product->size) }}" min="0" step="1">
                        @error('size')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group" style="display: none;">
                        <label>Weight (grams)</label>
                        <input class="form-control" type="number" name="weight" value="{{ old('wight', $product->weight) }}">
                    </div>

                    <div class="form-group" style="display: none;">
                        <label>Texture</label>
                        <input class="form-control" type="text" name="texture" value="{{ old('wight', $product->texture) }}">
                    </div>

                    <div class="form-group" style="display: none;">
                        <label class="d-block">Unit of Measurement</label>
                        <input type="text" class="form-control" name="uom" id="uom" value="{{ old('uom', $product->uom) }}">
                    </div>

                    
                    <div class="form-group">
                        <label class="d-block">Upload images</label>
                        <input type="file" id="upload_image" class="image_path" accept="image/*" multiple>
                        {{-- <button type="button" class="btn btn-secondary btn-sm upload" id="selectImages">Select images</button> --}}
                        {{-- <div class="prodimg-thumb" id="bannersDiv" @if($product->photos->count() == 0) style="display: none;" @endif> --}}
                        <div class="prodimg-thumb" id="bannersDiv">
                            <ul id="banners">
                                @foreach ($product->photos as $key => $photo)
                                    @php $count = $key + 1; @endphp
                                    <li class="productImage" id="{{ $count }}li">
                                        <div class="prodmenu-left" data-toggle="modal" data-target="#image-details" data-id="{{ $count }}"  data-name="{{ $photo->file_name() }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal">
                                                <circle cx="12" cy="12" r="1"></circle>
                                                <circle cx="19" cy="12" r="1"></circle>
                                                <circle cx="5" cy="12" r="1"></circle>
                                            </svg>
                                        </div>
                                        <div class="prodmenu-right" data-toggle="modal" data-target="#remove-image" data-id="{{ $count }}" data-image-id="{{ $photo->id }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                                <line x1="6" y1="6" x2="18" y2="18"></line>
                                            </svg>
                                        </div>
                                        <label class="form-check-label radio" for="exampleRadios{{ $count }}" data-toggle="tooltip" data-placement="bottom" title="Set as primary image">
                                            <input class="form-check-input imageRadio" type="radio" name="is_primary" id="exampleRadios{{ $count }}" value="{{ $count }}" @if ($photo->is_primary) checked @endif>
                                            <input name="photos[{{ $count }}][id]" type="hidden" value="{{ $photo->id }}" />
                                            <input name="photos[{{ $count }}][image_path]" type="hidden" value="{{ $photo->path }}">
                                            <input name="photos[{{ $count }}][name]" type="hidden" id="{{ $count }}name" value="{{$photo->name}}">
                                            <img src="{{ $photo->storage_path() }}" />
                                            <div class="radio-button"></div>
                                        </label>
                                    </li>
                                @endforeach
                                {{-- @if($product->photos->count() < 1) --}}
                                    <li id="addMoreBanner">
                                        <div class="add-more txt-center upload">
                                            <i data-feather="plus-circle"></i>
                                        </div>
                                    </li>
                                {{-- @endif --}}
                            </ul>
                        </div>
                        <p class="tx-10 mt-2">
                            Required image dimension: {{ env('PRODUCT_WIDTH') }}px by {{ env('PRODUCT_HEIGHT') }}px <br /> Maximum file size: 1MB <br /> Required file type: .jpeg .png <br /> Maximum images: 5
                        </p>
                    </div>
                    
                    {{-- <div class="form-group">
                        <label class="d-block">E-book File (.epub)</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input @error('file_url') is-invalid @enderror" name="file_url" id="file_url" accept=".epub" @if (!empty($product->file_url)) title="{{$product->getProductName()}}" @endif>
                            <label class="custom-file-label" for="file_url" id="file_name">@if (empty($product->file_url)) Choose file @else {{$product->getProductName()}} @endif</label>
                            <input type="text" id="current_file" name="current_file" value="{{ $product->file_url }}" hidden/>
                        </div>
                        <p class="tx-10">
                            Required file type: .epub
                        </p>
                        <div id="epub_div" @if(empty($product->file_url)) style="display:none;" @endif>
                            <div class="d-flex mb-3">
                                <div class="card d-inline p-3 position-relative text-center">
                                    <a href="javascript:void(0)" class="fa fa-times-circle text-secondary position-absolute" style="top: 7%; right: 2%;" onclick="removeEPUB();"></a>
                                    <a id="file_temp" href="#" target="_blank">
                                        <i class="fa fa-file fa-2x mr-2 text-danger"></i>
                                        <span id="epub_file_name" class="text-dark">{{$product->getProductName()}}</span>
                                    </a>   
                                </div>                         
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="d-block">Ebook Price (in Php)</label>
                        <input type="number" class="form-control @error('ebook_price') is-invalid @enderror" name="ebook_price" id="ebook_price" value="{{ old('ebook_price', number_format($product->ebook_price,2,'.','')) }}" min="0.00" step="0.01">
                        @error('ebook_price')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="d-block">Ebook Discounted Price (in Php)</label>
                        <input type="number" class="form-control @error('ebook_discount_price') is-invalid @enderror" name="ebook_discount_price" id="ebook_discount_price" value="{{ old('ebook_discount_price', number_format($product->ebook_discount_price,2,'.','')) }}" min="0.00" step="0.01">
                        @error('ebook_discount_price')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div> --}}

                    <div class="form-group">
                        <label class="d-block">Tags</label>
                        <input type="text" class="form-control @error('tags') is-invalid @enderror" data-role="tagsinput" name="tags" id="tags" value="{{ old('tags',\App\Models\Ecommerce\ProductTag::tags($product->id)) }}">
                        @error('tags')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                @if(count($attributes))
                <div class="col-lg-12 mg-t-30">
                    <h4 class="mg-b-0 tx-spacing--1">Additional Info</h4>
                    <hr>
                </div>

                <input type="hidden" name="hasProductAttribute" value="1">
                @else
                <input type="hidden" name="hasProductAttribute" value="0">
                @endif

                <div class="col-lg-6 mg-t-20">
                    @foreach($attributes as $attr)

                    @php
                        $productInfo = \App\Models\Ecommerce\ProductAdditionalInfo::where('product_id', $product->id)->where('attribute_name', $attr->name);
                    @endphp
                    <div class="form-group">
                        <label class="d-block">{{$attr->name}}</label>
                        <input type="hidden" name="productAttribute[]" class="form-control" value="{{$attr->name}}">
                        @if($productInfo->count() > 0)

                        @php
                            $info = $productInfo->first();
                        @endphp

                        <input type="text" name="attributeValue[]" class="form-control" value="{{$info->value}}">
                        @else
                        <input type="text" name="attributeValue[]" class="form-control">
                        @endif
                    </div>
                    @endforeach
                </div>


                <div class="col-lg-12 mg-t-20">
                    <div class="form-group">
                        <label class="d-block">Visibility</label>
                        <div class="custom-control custom-switch @error('status') is-invalid @enderror">
                            <input type="checkbox" class="custom-control-input" name="status" {{ (old("status") == "ON" || $product->status == "PUBLISHED" ? "checked":"") }} id="customSwitch1">
                            <label class="custom-control-label" id="label_visibility" for="customSwitch1">{{ucfirst(strtolower($product->status))}}</label>
                        </div>
                        @error('status')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    {{-- <div class="form-group">
                        <label class="d-block">Display (Limit: {{ \App\Models\Ecommerce\Product::get_featured_count() . '/' . env('FEATURED_PRODUCTS_LIMIT') }})</label>
                        <div class="custom-control custom-switch @error('is_featured') is-invalid @enderror">
                            <input type="checkbox" class="custom-control-input" name="is_featured" {{ (old("visibility") || $product->is_featured ? "checked":"") }} id="customSwitch2" {{ \App\Models\Ecommerce\Product::featured_limit_already() ? $product->is_featured ? '' : 'disabled' : '' }}>
                            <label class="custom-control-label" for="customSwitch2">Featured</label>
                        </div>
                        @error('is_featured')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="d-block">Best Seller (Limit: {{ \App\Models\Ecommerce\Product::get_best_seller_count() . '/' . env('BEST_SELLER_LIMIT') }})</label>
                        <div class="custom-control custom-switch @error('is_best_seller') is-invalid @enderror">
                            <input type="checkbox" class="custom-control-input" name="is_best_seller" {{ (old("is_best_seller") || $product->is_best_seller ? "checked":"") }} id="customSwitch3" {{ \App\Models\Ecommerce\Product::best_seller_limit_already() ? $product->is_best_seller ? '' : 'disabled' : '' }}>
                            <label class="custom-control-label" for="customSwitch3">Best Seller</label>
                        </div>
                        @error('is_best_seller')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group" id="free_to_read_div" @if(empty($product->file_url)) style="display:none;" @endif>
                        <label class="d-block">Free to Read</label>
                        <div class="custom-control custom-switch @error('is_free') is-invalid @enderror">
                            <input type="checkbox" class="custom-control-input" name="is_free" {{ (old("is_free") || $product->is_free ? "checked":"") }} id="customSwitch4">
                            <label class="custom-control-label" for="customSwitch4">Free to Read</label>
                        </div>
                        @error('is_free')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="d-block">Premium</label>
                        <div class="custom-control custom-switch @error('is_premium') is-invalid @enderror">
                            <input type="checkbox" class="custom-control-input" name="is_premium" {{ (old("is_premium") || $product->is_premium ? "checked":"") }} id="customSwitch5">
                            <label class="custom-control-label" for="customSwitch5">Premium</label>
                        </div>
                        @error('is_premium')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div> --}}
                </div>

                <div class="col-lg-12 mg-t-30">
                    <h4 class="mg-b-0 tx-spacing--1">Manage SEO</h4>
                    <hr>
                </div>

                <div class="col-lg-6 mg-t-30">
                    <div class="form-group">
                        <label class="d-block">Title <code>(meta title)</code></label>
                        <input type="text" class="form-control @error('meta_title') is-invalid @enderror" name="meta_title" value="{{ old('meta_title', $product->meta_title) }}">
                        @error('meta_title')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <p class="tx-11 mg-t-4">{{ __('standard.seo.title') }}</p>
                    </div>
                    <div class="form-group">
                        <label class="d-block">Description <code>(meta description)</code></label>
                        <textarea rows="3" class="form-control @error('meta_description') is-invalid @enderror" name="meta_description">{!! old('meta_description',$product->meta_description) !!}</textarea>
                        @error('meta_description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <p class="tx-11 mg-t-4">{{ __('standard.seo.description') }}</p>
                    </div>
                    <div class="form-group">
                        <label class="d-block">Keywords <code>(meta keywords)</code></label>
                        <textarea rows="3" class="form-control @error('meta_keyword') is-invalid @enderror" name="meta_keyword">{!! old('meta_keyword',$product->meta_keyword) !!}</textarea>
                        @error('meta_keyword')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <p class="tx-11 mg-t-4">{{ __('standard.seo.keywords') }}</p>
                    </div>
                </div>

                <div class="col-lg-12 mg-t-30">
                    <input class="btn btn-primary btn-sm btn-uppercase" type="submit" value="Update Product">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm btn-uppercase">Cancel</a>
                </div>

            </div>
        </form>
        <!-- row -->
    </div>

    <div class="modal fade" id="remove-image" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Remove image?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this image? You cannot undo this action.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="removeImage">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="image-details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Image details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>File Name</label>
                        <input type="text" class="form-control" value="" disabled id="fileName">
                    </div>
                    <div class="form-group mg-b-0">
                        <label>Alt</label>
                        <input type="text" class="form-control" placeholder="Input alt text" id="changeAlt">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveChangeAlt">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal effect-scale" id="prompt-remove" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Remove zoom image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to remove this image?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" id="btnRemove">Yes, remove image</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
    <script src="{{ asset('lib/bootstrap-tagsinput/bootstrap-tagsinput.min.js') }}"></script>

    <script src="{{ asset('lib/jqueryui/jquery-ui.min.js') }}"></script>

    {{--    Image validation--}}
    <script>
        let BANNER_WIDTH = "{{ env('PRODUCT_WIDTH') }}";
        let BANNER_HEIGHT =  "{{ env('PRODUCT_HEIGHT') }}";
        let MAX_IMAGE =  5;
    </script>
    <script src="{{ asset('js/image-upload-validation.js') }}"></script>
    {{--    End Image validation--}}
@endsection


@section('customjs')
	<script>
        // var options = {
        //     filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
        //     filebrowserImageUpload: '/laravel-filemanager/upload?type=Images&_token=',
        //     filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
        //     filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token',
        //     allowedContent: true,

        // };

        var CSRFToken = $('meta[name="csrf-token"]').attr('content');
        var CKEditorOptions = {
          filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
          filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token='+CSRFToken,
          filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
          filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token='+CSRFToken,
          allowedContent: true,
        };
        
        let editor = CKEDITOR.replace('long_description', CKEditorOptions);
        editor.on('required', function (evt) {
            if ($('.invalid-feedback').length == 1) {
                $('#long_descriptionRequired').show();
            }
            $('#cke_editor1').addClass('is-invalid');
            evt.cancel();
        });


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

        $(function() {
            let image_count = 1;
            let objUpload;
            let objRemove;
            $('.selectpicker').selectpicker();

            $("#customSwitch1").change(function() {
                if(this.checked) {
                    $('#label_visibility').html('Published');
                }
                else{
                    $('#label_visibility').html('Private');
                }
            });

            $("#customSwitch3").change(function() {
                if(this.checked) {
                    $('#label_visibility3').html('Yes');
                }
                else{
                    $('#label_visibility3').html('No');
                }
            });


            $("#customSwitch4").change(function() {
                if (this.checked) {
                    $('#customSwitch5').prop('checked', false);
                }
            });

            $("#customSwitch5").change(function() {
                if (this.checked) {
                    $('#customSwitch4').prop('checked', false);
                }
            });
            

            $(document).on('click', '.upload', function() {
                objUpload = $(this);
                $('#upload_image').click();
            });

            $('#upload_image').on('change', function (evt) {
                let files = evt.target.files;
                let uploadedImagesLength = $('.productImage').length;
                let totalImages = uploadedImagesLength + files.length;

                if (totalImages > 5) {
                    $('#bannerErrorMessage').html("You can upload up to 5 images only.");
                    $('#prompt-banner-error').modal('show');
                    return false;
                }

                if (totalImages == 5) {
                    $('#addMoreBanner').hide();
                }

                validate_images(evt, upload_image);
            });

            function upload_image(file)
            {
                let data = new FormData();
                data.append("_token", "{{ csrf_token() }}");
                data.append("banner", file);
                $.ajax({
                    data: data,
                    type: "POST",
                    url: "{{ route('products.upload') }}",
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(returnData) {
                        $('#bannersDiv').show();
                        $('#selectImages').hide();
                        console.log(returnData);
                        if (returnData.status == "success") {
                            while ($('input[name="photos['+image_count+'][image_path]"]').length) {
                                image_count += 1;
                            }

                            let radioCheck = (image_count == 1) ? 'checked' : '';

                            $(`<li class="productImage" id="`+image_count+`li">
                                <div class="prodmenu-left" data-toggle="modal" data-target="#image-details" data-id="`+image_count+`" data-name="`+returnData.image_name+`">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal">
                                        <circle cx="12" cy="12" r="1"></circle>
                                        <circle cx="19" cy="12" r="1"></circle>
                                        <circle cx="5" cy="12" r="1"></circle>
                                    </svg>
                                </div>
                                <div class="prodmenu-right" data-toggle="modal" data-target="#remove-image" data-id="`+image_count+`">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </div>
                                <label class="form-check-label radio" for="exampleRadios`+image_count+`" data-toggle="tooltip" data-placement="bottom" title="Set as primary image">
                                    <input class="form-check-input imageRadio" type="radio" name="is_primary" id="exampleRadios`+image_count+`" value="`+image_count+`" `+radioCheck+`>
                                    <input name="photos[`+image_count+`][image_path]" type="hidden" value="`+returnData.image_url+`">
                                    <input name="photos[`+image_count+`][name]" type="hidden" id="`+image_count+`name" value="">
                                    <img src="`+returnData.image_url+`" />
                                    <div class="radio-button"></div>
                                </label>
                            </li>`).insertBefore('#addMoreBanner');
                        }
                    },
                    failed: function() {
                        alert('FAILED NGA!');
                    }
                });
            }

            let selectedImage;
            $('#image-details').on('show.bs.modal', function(e) {
                selectedImage = e.relatedTarget;
                let imageId = $(selectedImage).data('id');
                let imageName = $(selectedImage).data('name');
                $('#fileName').val(imageName);
                $('#changeAlt').val($('#'+imageId+"name").val());
            });

            $('#saveChangeAlt').on('click', function() {
                let imageId = $(selectedImage).data('id');
                $('#'+imageId+"name").val($('#changeAlt').val());
                $('#image-details').modal('hide');
            });

            $('#image-details').on('hide.bs.modal', function() {
                $('#fileName').val('');
                $('#changeName').val('');
                selectedImage = null;
            });

            $('#remove-image').on('show.bs.modal', function(e) {
                selectedImage = e.relatedTarget;
            });

            $('#removeImage').on('click', function() {
                let imageId = $(selectedImage).data('id');
                let attrImageId = $(selectedImage).data('image-id');
                if (attrImageId) {
                    $('#updateForm').prepend('<input type="hidden" name="remove_photos[]" value="'+attrImageId+'">');
                }

                let isChecked = $('#exampleRadios'+imageId).is(':checked');
                $('#'+imageId+"li").remove();
                $('#addMoreBanner').show();
                if (isChecked) {
                    $.each($('.imageRadio'), function () {
                        $(this).prop('checked', true);
                        return false;
                    });
                }
                $('#remove-image').modal('hide');
            });

            $('#image-details').on('hide.bs.modal', function() {
                imageId = 0;
            });
        });
       
        $(document).on('click', '.remove-upload', function() {
            $('#prompt-remove').modal('show');
        });
        $('#btnRemove').on('click', function() {
            $('#updateForm').prepend('<input type="hidden" name="delete_zoom" value="1"/>');            
            $('#zoom_div').show();
            $('#prompt-remove').modal('hide');
            $('#zoomimage_div').remove();
            
        });
    </script>

    <script>
        function readEPUB(file) {
            let reader = new FileReader();

            reader.onload = function(e) {
                $('#file_name').html(file.name);
                $('#epub_file_name').html(file.name);
                $('#epub_div').show();
                $('#free_to_read_div').show();
                $('#file_temp').attr('href', e.target.result);
            }

            reader.readAsDataURL(file);
        }

        $("#file_url").change(function(evt) {
            $('#file_name').html('Choose file');
            $('#epub_file_name').html('');
            $('#epub_div').hide();
            $('#free_to_read_div').hide();
            $('#file_temp').attr('href', '');

            let files = evt.target.files;
            let maxSize = 10;
            let validateFileTypes = [".epub"];

            validateFiles(files, maxSize, readEPUB, validateFileTypes);
        });

        function removeEPUB() {
            $('#file_name').html('Choose file');
            $('#epub_file_name').html('');
            $('#epub_div').hide();
            $('#free_to_read_div').hide();
            $('#file_url').val('');
            $('#current_file').val('');
            $('#file_temp').attr('href', '');
        }

        function validateFiles(files, callback, allowedTypes) {
            // Your validation logic here, if needed.
            // Call the callback function with the validated files.
            // For example, you can check file extensions here.
            for (let i = 0; i < files.length; i++) {
                let fileExtension = files[i].name.split('.').pop().toLowerCase();
                if (allowedTypes.includes("." + fileExtension)) {
                    callback(files[i]);
                } else {
                    alert('Invalid file type. Please select a valid EPUB file.');
                    // You may want to clear the input field or take other actions.
                    $('#file_url').val('');
                }
            }
        }
    </script>
@endsection
