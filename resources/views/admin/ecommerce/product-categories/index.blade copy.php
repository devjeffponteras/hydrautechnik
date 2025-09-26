@extends('admin.layouts.app')

@section('pagetitle')
Manage Customer
@endsection

@section('pagecss')
<link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">
<style>
    .row-selected {
        background-color: #92b7da !important;
    }
</style>
@endsection

@section('content')
    <div class="container pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-5">
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Product Categories</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Manage Product Categories</h4>
            </div>
        </div>

        <div class="row row-sm">

            <!-- Start Filters -->
            @include('admin.ecommerce.product-categories.filter')
            <!-- End Filters -->

            <!-- Start Pages -->
            <div class="col-md-12">
                <div class="table-list mg-b-10">
                    <div class="table-responsive-lg">
                        <table class="table mg-b-0 table-light table-hover" style="table-layout: fixed;word-wrap: break-word;">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="checkbox_all">
                                            <label class="custom-control-label" for="checkbox_all"></label>
                                        </div>
                                    </th>
                                    <th style="width: 15%;overflow: hidden;">Name</th>
                                    <th style="width: 30%;">Parent Category</th>
                                    <th style="width: 10%;">Status</th>
                                    <th style="width: 15%;">Last Date Modified</th>
                                    <th style="width: 10%;">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($categories as $category)
                                <tr id="row{{$category->id}}">
                                    <th>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input cb" id="cb{{ $category->id }}">
                                            <label class="custom-control-label" for="cb{{ $category->id }}"></label>
                                        </div>
                                    </th>
                                    <td>
                                        <strong @if($category->trashed()) style="text-decoration:line-through;" @endif> {{ $category->name }}</strong>
                                    </td>
                                    <td>{{ $category->description }}</td>
                                    <td>{{ $category->status }}</td>
                                    <td>{{ Setting::date_for_listing($category->updated_at) }}</td>
                                    <td>
                                        @if($category->trashed())
                                            @if (auth()->user()->has_access_to_route('product.category.restore'))
                                                <nav class="nav table-options">
                                                    <a class="nav-link" href="{{route('product.category.restore', $category->id)}}" title="Restore this category"><i data-feather="rotate-ccw"></i></a>
                                                </nav>
                                            @endif
                                        @else
                                            <nav class="nav table-options">

                                                <a class="nav-link" target="_blank" href="{{route('product.index.advance-search')}}?name=&category_id={{$category->id}}&brand=&user_id=&short_description=&description=&status=&price1=&price2=&updated_at1=&updated_at2=" title="View Products"><i data-feather="eye"></i></a>

                                                @if (auth()->user()->has_access_to_route('product-categories.edit'))
                                                    <a class="nav-link" href="{{ route('product-categories.edit',$category->id) }}" title="Edit Category"><i data-feather="edit"></i></a>
                                                @endif

                                                @if (auth()->user()->has_access_to_route('product.category.single.delete'))
                                                    <a class="nav-link" href="javascript:void(0)" onclick="delete_one_category({{$category->id}},'{{$category->name}}')" title="Delete Category"><i data-feather="trash"></i></a>
                                                @endif

                                                @if (auth()->user()->has_access_to_route('product.category.change-status'))
                                                    <a class="nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i data-feather="settings"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        @if($category->status == 'PUBLISHED')
                                                            <a class="dropdown-item" href="{{route('product.category.change-status',[$category->id,'PRIVATE'])}}" > Private</a>
                                                        @else
                                                            <a class="dropdown-item" href="{{route('product.category.change-status',[$category->id,'PUBLISHED'])}}"> Publish</a>
                                                        @endif
                                                    </div>
                                                @endif
                                            </nav>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <th colspan="7" style="text-align: center;"> <p class="text-danger">No Categories found.</p></th>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- End Pages -->

            <div class="col-md-6">
                <div class="mg-t-5">
                    @if ($categories->firstItem() == null)
                        <p class="tx-gray-400 tx-12 d-inline">{{__('common.showing_zero_items')}}</p>
                    @else
                        <p class="tx-gray-400 tx-12 d-inline">Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }} of {{ $categories->total() }} items</p>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-md-right float-md-right mg-t-5">
                    <div>
                        {{ $categories->appends((array) $filter)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.ecommerce.product-categories.modals')
@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>

    <script>
        let listingUrl = "{{ route('product-categories.index') }}";
        let searchType = "{{ $searchType }}";
    </script>
    <script src="{{ asset('js/listing.js') }}"></script>
@endsection

@section('customjs')
    <script>

        /*** handles the changing of status of multiple pages ***/
        function change_status(status){
            var counter = 0;
            var selected_videos = '';
            $(".cb:checked").each(function(){
                counter++;
                fid = $(this).attr('id');
                selected_videos += fid.substring(2, fid.length)+'|';
            });
            if(parseInt(counter) < 1){
                $('#prompt-no-selected').modal('show');
                return false;
            }
            else{
                if(parseInt(counter)>1){ // ask for confirmation when multiple pages was selected
                    $('#categoryStatus').html(status)
                    $('#prompt-update-status').modal('show');

                    $('#btnUpdateStatus').on('click', function() {
                        post_form("{{route('product.category.multiple.change.status')}}",status,selected_videos);
                    });
                }
                else{
                    post_form("{{route('product.category.multiple.change.status')}}",status,selected_videos);
                }
            }
        }

        function post_form(url,status,category){
            $('#posting_form').attr('action',url);
            $('#categories').val(category);
            $('#status').val(status);
            $('#posting_form').submit();
        }

        function delete_category(){
            var counter = 0;
            var selected_videos = '';
            $(".cb:checked").each(function(){
                counter++;
                fid = $(this).attr('id');
                selected_videos += fid.substring(2, fid.length)+'|';
            });

            if(parseInt(counter) < 1){
                $('#prompt-no-selected').modal('show');
                return false;
            }
            else{
                $('#prompt-multiple-delete').modal('show');
                $('#btnDeleteMultiple').on('click', function() {
                    post_form("{{route('product.category.multiple.delete')}}",'',selected_videos);
                });
            }
        }

        function delete_one_category(id,page){
            $('#prompt-delete').modal('show');
            $('#btnDelete').on('click', function() {
                post_form("{{route('product.category.single.delete')}}",'',id);
            });
        }
    </script>
@endsection
