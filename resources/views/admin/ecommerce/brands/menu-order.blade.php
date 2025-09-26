@extends('admin.layouts.app')

@section('pagecss')
<style type="text/css">


    /**
     * Nestable
     */

    .dd { position: relative; display: block; margin: 0; padding: 0; max-width: 600px; list-style: none; font-size: 13px; line-height: 20px; }

    .dd-list { display: block; position: relative; margin: 0; padding: 0; list-style: none; }
    .dd-list .dd-list { padding-left: 30px; }
    .dd-collapsed .dd-list { display: none; }

    .dd-item,
    .dd-empty,
    .dd-placeholder { display: block; position: relative; margin: 0; padding: 0; min-height: 20px; font-size: 13px; line-height: 20px; }

    .dd-handle { display: block; height: 40px; margin: 5px 0; padding: 5px 10px; color: #333; text-decoration: none; font-weight: bold; border: 1px solid #ccc;
        background: #fafafa;
        background: -webkit-linear-gradient(top, #fafafa 0%, #eee 100%);
        background:    -moz-linear-gradient(top, #fafafa 0%, #eee 100%);
        background:         linear-gradient(top, #fafafa 0%, #eee 100%);
        -webkit-border-radius: 3px;
                border-radius: 3px;
        box-sizing: border-box; -moz-box-sizing: border-box;
    }
    .dd-handle:hover { color: #2ea8e5; background: #fff; }

    .dd-item > button { display: block; position: relative; cursor: pointer; float: left; width: 25px; height: 20px; padding: 0; text-indent: 100%; white-space: nowrap; overflow: hidden; border: 0; background: transparent; line-height: 1; text-align: center; font-weight: bold; }
    .dd-item > button:before { content: '+'; display: block; position: absolute; text-align: center; text-indent: 0; margin-top: -8px; }
    .dd-item > button[data-action="collapse"]:before { content: '-'; }

    .dd-placeholder,
    .dd-empty { margin: 5px 0; padding: 0; min-height: 30px; background: #f2fbff; border: 1px dashed #b6bcbf; box-sizing: border-box; -moz-box-sizing: border-box; }
    .dd-empty { border: 1px dashed #bbb; min-height: 100px; background-color: #e5e5e5;
        background-image: -webkit-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
                          -webkit-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
        background-image:    -moz-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
                             -moz-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
        background-image:         linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
                                  linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
        background-size: 60px 60px;
        background-position: 0 0, 30px 30px;
    }

    .dd-dragel { position: absolute; pointer-events: none; z-index: 9999; }
    .dd-dragel > .dd-item .dd-handle { margin-top: 0; }
    .dd-dragel .dd-handle {
        -webkit-box-shadow: 2px 4px 6px 0 rgba(0,0,0,.1);
                box-shadow: 2px 4px 6px 0 rgba(0,0,0,.1);
    }

    /**
     * Nestable Extras
     */

    .nestable-lists { display: block; clear: both; padding: 30px 0; width: 100%; border: 0; border-top: 2px solid #ddd; border-bottom: 2px solid #ddd; }

    #nestable-menu { padding: 0; margin: 20px 0; }

    #nestable-output { width: 100%; height: 7em; font-size: 0.75em; line-height: 1.333333em; font-family: Consolas, monospace; padding: 5px; box-sizing: border-box; -moz-box-sizing: border-box; }

    @media only screen and (min-width: 700px) {

        .dd { float: left; width: 48%; }
        .dd + .dd { margin-left: 2%; }

    }

    .dd-hover > .dd-handle { background: #2ea8e5 !important; }

    /**
     * Nestable Draggable Handles
     */

    .dd3-content { display: block; height: 30px; margin: 5px 0; padding: 5px 10px 5px 40px; color: #333; text-decoration: none; font-weight: bold; border: 1px solid #ccc;
        background: #fafafa;
        background: -webkit-linear-gradient(top, #fafafa 0%, #eee 100%);
        background:    -moz-linear-gradient(top, #fafafa 0%, #eee 100%);
        background:         linear-gradient(top, #fafafa 0%, #eee 100%);
        -webkit-border-radius: 3px;
                border-radius: 3px;
        box-sizing: border-box; -moz-box-sizing: border-box;
    }
    .dd3-content:hover { color: #2ea8e5; background: #fff; }

    .dd-dragel > .dd3-item > .dd3-content { margin: 0; }

    .dd3-item > button { margin-left: 30px; }

    .dd3-handle { position: absolute; margin: 0; left: 0; top: 0; cursor: pointer; width: 30px; text-indent: 100%; white-space: nowrap; overflow: hidden;
        border: 1px solid #aaa;
        background: #ddd;
        background: -webkit-linear-gradient(top, #ddd 0%, #bbb 100%);
        background:    -moz-linear-gradient(top, #ddd 0%, #bbb 100%);
        background:         linear-gradient(top, #ddd 0%, #bbb 100%);
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }
    .dd3-handle:before { content: '≡'; display: block; position: absolute; left: 0; top: 3px; width: 100%; text-align: center; text-indent: 0; color: #fff; font-size: 20px; font-weight: normal; }
    .dd3-handle:hover { background: #ddd; }

</style>
@endsection

@section('content')
    <div class="container pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-5">
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('brands.index')}}">Brands</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Brands Sidebar Menu Order</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Manage Brands Sidebar Menu Order</h4>
            </div>
        </div>

        <div class="row row-sm">
            <div class="col-md-12">
                <div class="row">
                    <div class="cf nestable-lists">
                        <menu id="nestable-menu">
                            <button type="button" class="btn btn-sm btn-primary" data-action="expand-all">Expand All</button>
                            <button type="button" class="btn btn-sm btn-primary" data-action="collapse-all">Collapse All</button>
                        </menu>

                            <div class="dd" id="nestable">
                                <ol class="dd-list">
                                    @foreach($brands as $brand)
                                    <li class="dd-item" data-type="brand" data-id="{{$brand->id}}">
                                        <div class="dd-handle">{{$brand->name}}</div>
                                        @if(count($brand->child_categories))

                                            <ol class="dd-list">
                                                @foreach($brand->child_categories as $category)
                                                    <li class="dd-item" data-type="category" data-id="{{$category->category_id}}">
                                                        <div class="dd-handle">{{strtoupper($category->product_category->name)}}</div>
                                                        @if(count($category->child_categories))
                                                            @include('admin.ecommerce.brands.menu-items',['subcategories' => $category->child_categories])
                                                        @endif
                                                    </li>
                                                @endforeach 
                                            </ol>

                                        @else

                                            <ol class="dd-list">
                                                @foreach($brand->product_categories as $category)
                                                    <li class="dd-item" data-type="category" data-id="{{$category->product_category_id}}">
                                                        <div class="dd-handle">{{$category->product_category->name}}</div>
                                                    </li>
                                                @endforeach 
                                            </ol>

                                        @endif
                                    </li>
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('brand.update-nestable-menu') }}">
                            @csrf
                            <input type="hidden" name="nestable_output" id="nestable-output"/>
                            <button type="submit" class="btn btn-primary btn-sm mg-t-20">Save Changes</button>
                            <a class="btn btn-outline-secondary btn-sm btn-uppercase mg-t-20" href="{{ route('brands.index') }}">Cancel</a>
                        </form>    
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagejs')
    <script src="{{ asset('js/jquery.nestable.js') }}"></script>

    <script>
        $(document).ready(function(){

            var updateOutput = function(e)
            {
                var list   = e.length ? e : $(e.target),
                    output = list.data('output');
                if (window.JSON) {
                    output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
                } else {
                    output.val('JSON browser support required for this demo.');
                }
            };

            // activate Nestable for list 1
            $('#nestable').nestable({
                group: 1
            })
            .on('change', updateOutput);


            // output initial serialised data
            updateOutput($('#nestable').data('output', $('#nestable-output')));

            $('#nestable-menu').on('click', function(e)
            {
                var target = $(e.target),
                    action = target.data('action');
                if (action === 'expand-all') {
                    $('.dd').nestable('expandAll');
                }
                if (action === 'collapse-all') {
                    $('.dd').nestable('collapseAll');
                }
            });

        });
    </script>
@endsection
