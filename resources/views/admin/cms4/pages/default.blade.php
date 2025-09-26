@extends('admin.layouts.app')

@section('pagetitle')
    Edit Page
@endsection

@section('pagecss')
    <link href="{{ asset('css/font-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('css/et-line.css') }}" rel="stylesheet">
    <link href="{{ asset('css/medical-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('css/realestate-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('lib/custom-grapesjs/grapesjs/dist/css/grapes.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('lib/custom-grapesjs/assets/css/custom-grapesjs.css') }}" />
    <link rel="stylesheet" href="{{ asset('lib/custom-grapesjs/linearicon/css/linearicons.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('lib/grapesjs/tooltip.css') }}" />
    <link rel="stylesheet" href="{{ asset('lib/grapesjs/grapesjs-plugin-filestack.css') }}" />
    <link rel="stylesheet" href="{{ asset('lib/grapesjs/tui-color-picker.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('lib/grapesjs/tui-image-editor.min.css') }}" />
@endsection

@section('content')

    <div class="container pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('pages.index')}}">Pages</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit a Page</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Edit a Page</h4>
            </div>
            <div>
                <a class="btn btn-outline-primary btn-sm" href="{{$page->get_url()}}" target="_blank">Preview Page</a>
            </div>
        </div>
        <form id="editForm" action="{{ route('pages.update-default', $page->id) }}" method="post" enctype="multipart/form-data">
            <div class="row row-sm">
                <div class="col-lg-6">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label class="d-block">Page Title *</label>
                        <label class="d-block">{{ $page->name }}</label>
                        <label>
                            <small id="page_slug">
                                <a target="_blank" href="{{ $page->get_url() }}">{{ $page->get_url() }}</a>
                            </small>
                        </label>
                    </div>
                    <div class="form-group">
                        <label class="d-block">Page Label *</label>
                        <input type="text" class="form-control @error('label') is-invalid @enderror" name="label" id="label" value="{{ old('label', $page->label) }}" required>
                        @error('label')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="d-block">Content *</label>
                        @if ($page->is_home_page())
                            <p>
                                <small>
                                    To display selected items from your News List, you need to add the following keywords:
                                    <ul>
                                        <li>{Featured Articles} = Add all the news articles marked as Featured</li>
                                    </ul>
                                </small>
                            </p>
                        @endif
          

                    <div class="grid h-100 overflow-hidden" id="editor-area">
                        <div class="grid-item grid-item--behavior-fixed" style="flex-basis: 275px;margin-left:-275px" id="layers">
                            <div class="app-content--sidebar h-100" id="sidebar-inner-1">
                                <div class="app-content--sidebar__content scrollbar-container">
                                    <div class="nav-header">
                                        <i class="lnr lnr-layers font-20px mr-3"></i>
                                        <span>Layers</span>
                                    </div>

                                    <div class="layer-view overflow-auto">
                                        <div class="layers-container"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="grid-item position-relative overflow-hidden" id="grapesjs-editor">
                            <div class="app-header px-0">
                                <div class="position-relative d-flex justify-content-start">
                                    <button class="gjs-panel-vw" data-toggle="tooltip" data-placement="right" title="Show Layers" id="layers-view-btn" type="button">
                                        <i class="lnr lnr-chevron-right font-16px"></i>
                                        <i class="lnr lnr-chevron-left font-16px"></i>
                                    </button>

                                    <button class="gjs-panel-add" data-toggle="tooltip" data-placement="bottom" title="Blocks" id="add-blocks-btn" type="button">
                                        <i class="fa fa-plus font-16px"></i>
                                    </button>

                                    <div class="gjs-panel-res gjs-pn-buttons">
                                        <button type="button" class="btn btn-link btn-hsm device-type mr-1 bg-neutral-first px-0" id="desktop-view" data-toggle="tooltip" data-placement="bottom" title="Desktop" type="button">
                                            <span class="btn-wrapper--icon d-flex align-items-center">
                                                <i class="lnr lnr-screen font-16px"></i>
                                            </span>
                                        </button>

                                        <button type="button" class="btn btn-hsm btn-link device-type mr-1 px-0" id="tablet-view" data-toggle="tooltip" data-placement="bottom" title="Tablet" type="button">
                                            <span class="btn-wrapper--icon d-flex align-items-center">
                                                <i class="lnr lnr-tablet font-16px"></i>
                                            </span>
                                        </button>

                                        <button type="button" class="btn btn-hsm btn-link device-type px-0" id="mobile-view" data-toggle="tooltip" data-placement="bottom" title="Mobile" type="button">
                                            <span class="btn-wrapper--icon d-flex align-items-center">
                                                <i class="lnr lnr-phone font-16px"></i>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                                <div class="position-relative d-flex justify-content-start">
                                    <div class="gjs-panel-tool gjs-pn-buttons">
                                        <button type="button" class="btn btn-link btn-hsm device-type mr-1 swv" id="sw-visibility" data-toggle="tooltip" data-placement="bottom" title="Show Borders" type="button">
                                            <span class="btn-wrapper--icon d-flex align-items-center">
                                                <i class="lnr lnr-border-style font-16px"></i>
                                            </span>
                                        </button>

                                        <button type="button" class="btn btn-hsm btn-link device-type mr-1" id="editor-fullscreen" data-toggle="tooltip" data-placement="bottom" title="Fullscreen" type="button">
                                            <span class="btn-wrapper--icon d-flex align-items-center">
                                                <i class="lnr lnr-expand font-16px"></i>
                                            </span>
                                        </button>

                                        <button type="button" class="btn btn-hsm btn-link device-type" data-toggle="tooltip" data-placement="bottom" title="Export" type="button">
                                            <span class="btn-wrapper--icon d-flex align-items-center" data-toggle="modal" id="export" data-target="#editor-export">
                                                <i class="lnr lnr-code font-16px"></i>
                                            </span>
                                        </button>

                                        <button type="button" class="btn btn-hsm btn-link device-type" data-toggle="tooltip" data-placement="bottom" title="Import" type="button">
                                            <span class="btn-wrapper--icon d-flex align-items-center" data-toggle="modal" id="export" data-target="#editor-import">
                                                <i class="lnr lnr-enter-down font-16px"></i>
                                            </span>
                                        </button>
                                        
                                        <button type="button" class="btn btn-hsm btn-link device-type" id="edit-code" data-toggle="tooltip" data-placement="bottom" title="Edit Code" type="button">
                                            <span class="btn-wrapper--icon d-flex align-items-center">
                                                <i class="lnr lnr-pencil4 font-16px"></i>
                                            </span>
                                        </button>

                                        <button type="button" class="btn btn-hsm btn-link device-type" id="editor-undo" data-toggle="tooltip" data-placement="bottom" title="Undo" type="button">
                                            <span class="btn-wrapper--icon d-flex align-items-center">
                                                <i class="lnr lnr-undo2 font-16px"></i>
                                            </span>
                                        </button>

                                        <button type="button" class="btn btn-hsm btn-link device-type" id="editor-redo" data-toggle="tooltip" data-placement="bottom" title="Redo" type="button">
                                            <span class="btn-wrapper--icon d-flex align-items-center">
                                                <i class="lnr lnr-redo2 font-16px"></i>
                                            </span>
                                        </button>

                                        <button type="button" class="btn btn-hsm btn-link device-type" data-toggle="tooltip" data-placement="bottom" title="Clear Canvas" id="canvas-clear" type="button">
                                            <span class="btn-wrapper--icon d-flex align-items-center">
                                                <i class="lnr lnr-trash2 font-16px"></i>
                                            </span>
                                        </button>
                                    </div>
                                    <button class="gjs-panel-vw" data-toggle="tooltip" data-placement="left" title="Show Styles & Properties" id="styles-view-btn" type="button">
                                        <i class="lnr lnr-chevron-left font-16px"></i>
                                        <i class="lnr lnr-chevron-right font-16px"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="gjs">
                                
                            </div>

                            <!-- Export-modal -->
                            <div class="modal fade" id="editor-export" tabindex="-1" role="dialog" aria-labelledby="modal-b4" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h6 class="modal-title" id="modal-title-default">
                                                <i class="lnr lnr-exit-right"></i>
                                                Export
                                            </h6>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body row">
                                            <div class="col-lg-12">
                                                <ul class="nav nav-line" id="myTab3" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="html-export-tab" data-toggle="tab" href="#html-export" role="tab" aria-controls="home" aria-selected="true">
                                                            HTML
                                                            <div class="divider"></div>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="css-export-tab" data-toggle="tab" href="#css-export" role="tab" aria-controls="profile" aria-selected="false">
                                                            CSS
                                                            <div class="divider"></div>
                                                        </a>
                                                    </li>
                                                </ul>

                                                <div class="tab-content p-2 pb-0">
                                                    <div class="tab-pane fade" id="html-export" role="tabpanel" aria-labelledby="html-export-tab">

                                                    </div>
                                                    <div class="tab-pane fade" id="css-export" role="tabpanel" aria-labelledby="css-export-tab">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary ml-auto" id='gjs-export-zip'>
                                                <i class="lnr lnr-file-zip"></i>
                                                Export to ZIP
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- import modal -->
                            <div class="modal fade" id="editor-import" tabindex="-1" role="dialog" aria-labelledby="modal-b4" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h6 class="modal-title" id="modal-title-default">
                                                <i class="lnr lnr-enter-right"></i>
                                                Import
                                            </h6>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body row">
                                            <div class="col-lg-12">

                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary ml-auto" id='import-component'>
                                                <i class="lnr lnr-check"></i>
                                                Import
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="grid-item grid-item--behavior-fixed h-100" style="flex-basis: 280px;margin-right:-280px" id="styles-or-traits-mgr">
                            <div class="nav-header">
                                <i class="lnr lnr-palette font-20px mr-3"></i>
                                <span>Styles & Properties</span>
                            </div>
                            <div class="style-view position-relative overflow-auto">
                                <div id="selector-mgr">

                                </div>
                                <div id="traits-mgr">

                                </div>
                                <div id="styles-mgr">

                                </div>
                            </div>
                        </div>

                        <!-- block panel -->
                        <div class="panel-blocks">
                            <div id="gjsSearch" class="app-content--sidebar__header py-3 panel-blocks-header">
                                <div class="grid grid--align-center">
                                    <div class="grid-item">
                                        <div class="input-group-container">
                                            <div id="searchDiv" class="position-relative">
                                                <input id="searchInputBlk" class="input-group__input--select input-box" type="text" placeholder="Search block" />
                                            </div>
                                            <div id="blocksDiv" class="position-relative">
                                                <select id="block-select" class="input-group__input--select input-box">
                                                    <option value="1" selected>Basic Blocks</option>
                                                    <option value="2">Built-in Blocks</option>
                                                </select>
                                                <i class="select-group__icon is-abs--r is-no-pointer icon fa fa-null"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="grid-item grid-item--behavior-fixed ml-2">
                                        <button type="button" class="btn btn-block btn-hinfo btn-sm px-2" id="searchBtn">
                                            <span class="btn-wrapper--icon">
                                                <i class="lnr lnr-magnifier"></i>
                                                <i class="lnr lnr-cross2"></i>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="blocks-mgr">

                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="json" id="json" value="{{ old('json', $page->json) }}">
                    <input type="hidden" name="contents" id="contents" value="{{ old('contents', $page->contents) }}">
                    <input type="hidden" name="styles" id="styles" value="{{ str_replace(array("'", "&#039;"), "", old('styles', $page->styles) ) }}">


                    @error('contents')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <span class="invalid-feedback" role="alert" id="contentsRequired" style="display: none;">
                        <strong>The content field is required</strong>
                    </span>
                </div>

                <div class="col-lg-12 mg-t-30">
                    <h4 class="mg-b-0 tx-spacing--1">Manage SEO</h4>
                    <hr>
                </div>

                <div class="col-lg-6 mg-t-30">
                    <div class="form-group">
                        <label class="d-block">Title <code>(meta title)</code></label>
                        <input type="text" class="form-control @error('meta_title') is-invalid @enderror" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}">
                        @error('meta_title')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <p class="tx-11 mg-t-4">{{ __('standard.seo.title') }}</p>
                    </div>
                    <div class="form-group">
                        <label class="d-block">Description <code>(meta description)</code></label>
                        <textarea rows="3" class="form-control @error('meta_description') is-invalid @enderror" name="meta_description">{!! old('meta_description', $page->meta_description) !!}</textarea>
                        @error('meta_description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <p class="tx-11 mg-t-4">{{ __('standard.seo.description') }}</p>
                    </div>
                    <div class="form-group">
                        <label class="d-block">Keywords <code>(meta keywords)</code></label>
                        <textarea rows="3" class="form-control @error('meta_keyword') is-invalid @enderror" name="meta_keyword">{!! old('meta_keyword', $page->meta_keyword) !!}</textarea>
                        @error('meta_keyword')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <p class="tx-11 mg-t-4">{{ __('standard.seo.keywords') }}</p>
                    </div>
                </div>

                <div class="col-lg-12 mg-t-30">
                    <input class="btn btn-primary btn-sm btn-uppercase" type="submit" value="Update Page">
                    <a href="{{route('pages.index')}}" class="btn btn-outline-secondary btn-sm btn-uppercase" type="cancel">Cancel</a>
                </div>
            </div>
        </form>
    </div>

@endsection

@section('pagejs')
    <script>
        // jQuery Typing
        (function(f){function l(g,h){function d(a){if(!e){e=true;c.start&&c.start(a,b)}}function i(a,j){if(e){clearTimeout(k);k=setTimeout(function(){e=false;c.stop&&c.stop(a,b)},j>=0?j:c.delay)}}var c=f.extend({start:null,stop:null,delay:400},h),b=f(g),e=false,k;b.keypress(d);b.keydown(function(a){if(a.keyCode===8||a.keyCode===46)d(a)});b.keyup(i);b.blur(function(a){i(a,0)})}f.fn.typing=function(g){return this.each(function(h,d){l(d,g)})}})(jQuery);

        $(document).ready( function($){

            $('#icons-filter').typing({
                stop: function (event, $elem) {
                    var filterValue = $elem.val(),
                        count = 0;

                    if( $elem.val() ) {

                        $(".icons-list li").each(function(){
                            if ($(this).text().search(new RegExp(filterValue, "i")) < 0) {
                                $(this).fadeOut();
                            } else {
                                $(this).show();
                                count++
                            }
                        });
                    } else {
                        $(".icons-list li").show();
                    }

                    count = 0;
                },
                delay: 500
            });

        });
    </script>
    <script>
        @php
            $jsPage = json_encode(old('json', $page->json));
            echo "var jsPage = $jsPage;\n";
        @endphp
        @if(!old('json', $page->json) || old('json', $page->json) == "null")
            @php
                $jsHtml = old('contents', $page->contents);
                echo "var jsHtml = `$jsHtml`;\n";
                $jsStyle = str_replace(array("'", "&#039;"), "", old('styles', $page->styles) );
                echo "var jsStyle = `$jsStyle`;";
            @endphp
        @endif
    </script>
    <script src="{{ asset('lib/custom-grapesjs/assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/owl.carousel/owl.carousel.js') }}"></script>
    <script src="{{ asset('js/file-upload-validation.js') }}"></script>
    <script src="{{ asset('vendor/laravel-filemanager/js/stand-alone-button-2.js') }}"></script>

    <script src="{{ asset('lib/custom-grapesjs/grapesjs/dist/grapes.min.js') }}"></script>
    <script src="{{ asset('lib/custom-grapesjs/grapesjs-plugins/grapesjs-blocks-basic.min.js') }}"></script>
    <script src="{{ asset('lib/custom-grapesjs/grapesjs-plugins/grapesjs-pkurg-bootstrap4-plugin.js') }}"></script>
    <script src="{{ asset('lib/custom-grapesjs/grapesjs-plugins/grapesjs-lory-slider.min.js') }}"></script>
    <script src="{{ asset('lib/custom-grapesjs/grapesjs-plugins/grapesjs-touch.min.js') }}"></script>
    <script src="{{ asset('lib/custom-grapesjs/grapesjs-plugins/grapesjs-parser-postcss.min.js') }}"></script>
    <script src="{{ asset('lib/custom-grapesjs/grapesjs-plugins/grapesjs-tooltip.min.js') }}"></script>
    <script src="{{ asset('lib/custom-grapesjs/grapesjs-plugins/grapesjs-tui-image-editor.min.js') }}"></script>
    <script src="{{ asset('lib/custom-grapesjs/grapesjs-plugins/grapesjs-typed.min.js') }}"></script>
    <script src="{{ asset('lib/custom-grapesjs/grapesjs-plugins/grapesjs-style-bg.min.js') }}"></script>
    <script src="{{ asset('lib/custom-grapesjs/grapesjs-plugins/tui-code-snippet.min.js') }}"></script>
    <script src="{{ asset('lib/custom-grapesjs/grapesjs-plugins/tui-color-picker.min.js') }}"></script>
    <script src="{{ asset('lib/custom-grapesjs/grapesjs-plugins/grapesjs-plugin-ckeditor.min.js') }}"></script>
    <script src="{{ asset('lib/custom-grapesjs/grapesjs-plugins/grapesjs-plugin-export.min.js') }}"></script>
    <script src="{{ asset('lib/custom-grapesjs/grapesjs-plugins/grapesjs-blocks-bootstrap4.min.js') }}"></script>
    <script src="{{ asset('lib/custom-grapesjs/grapesjs-plugins/b4bulder-custom-blocks.js') }}"></script>
    <script src="{{ asset('lib/custom-grapesjs/grapesjs-plugins/grapesjs-preset-webpage.min.js') }}"></script>
    <script src="{{ asset('lib/custom-grapesjs/grapesjs-plugins/grapesjs-plugin-animation.js') }}"></script>
    <script src="{{ asset('lib/custom-grapesjs/grapesjs-plugins/grapesjs-swiper-slider.min.js') }}"></script>
    <script src="{{ asset('lib/custom-grapesjs/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('lib/custom-grapesjs/assets/js/custom-grapesjs.js') }}"></script>
    <script src="{{ asset('lib/custom-grapesjs/assets/js/bamburgh.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/typed.js/2.0.0/typed.min.js"></script> 
@endsection


@section('customjs')
@endsection
