@extends('admin.layouts.app')

@section('pagecss')
    <link href="{{ asset('lib/bselect/dist/css/bootstrap-select.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/bootstrap-tagsinput/bootstrap-tagsinput.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/ion-rangeslider/css/ion.rangeSlider.min.css') }}" rel="stylesheet">

    <style>
        .table td {
            padding: 10px;
            font-size: 13px;
        }
    </style>
@endsection

@section('content')

    <div class="container pd-x-0">
        <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-style1 mg-b-5" style="background-color:white;">
                        <li class="breadcrumb-item" aria-current="page"><a href="{{route('dashboard')}}">CMS</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Sales Transaction</li>
                    </ol>
                </nav>
                <h4 class="mg-b-0 tx-spacing--1">Sales Transaction Manager</h4>
            </div>
        </div>

        <div class="row row-sm">

            <!-- Start Filters -->
            <div class="col-md-12">
                <div class="filter-buttons">
                    <div class="d-md-flex bd-highlight">
                        <div class="bd-highlight mg-t-10 mg-r-5">
                            <form class="form-inline" id="searchForm" style="font-size:12px;" method="GET">
                             
                                    <div class="mg-b-10 mg-r-5">Start:
                                        <input name="startdate" type="date" id="startdate" style="font-size:12px;width: 150px;" class="form-control"
                                        value="@if($startDate){{ date('Y-m-d',strtotime($startDate)) }}@endif">
                                    </div>
                                    <div class="mg-b-10">End:
                                        <input name="enddate" type="date" id="enddate" style="font-size:12px;width: 150px;" class="form-control"
                                        value="@if($endDate){{ date('Y-m-d',strtotime($endDate)) }}@endif">
                                    </div>
                                    &nbsp;
                                    <div class="mg-b-10">
                                        <select name="del_status" id="del_status" class="form-control" style="font-size:12px;width: 150px;">
                                            <option value="">All</option>
                                            <option @if($deliveryStatus == 'Pending') selected @endif value="Pending">Pending</option>
                                            <option @if($deliveryStatus == 'Processing') selected @endif value="Processing">Processing</option>
                                            <option @if($deliveryStatus == 'In Transit') selected @endif value="In Transit">In Transit</option>
                                            <option @if($deliveryStatus == 'Delivered') selected @endif value="Delivered">Delivered</option>
                                            <option @if($deliveryStatus == 'Returned') selected @endif value="Returned">Returned</option>
                                            <option @if($deliveryStatus == 'Cancelled') selected @endif value="Cancelled">Cancelled</option>
                                        </select>
                                    </div>
                                    &nbsp;
                                    <div class="mg-b-10 mg-r-5">
                                        <select name="customer_filter" id="customer_filter" class="form-control" style="font-size:12px;width: 150px;">
                                                <option value="">Customer</option>
                                                @foreach($sales->unique('customer_name')->sortBy('customer_name') as $cname)
                                                    <option value="{{$cname->customer_name}}"
                                                    @if($customer == $cname->customer_name) selected @endif 
                                                        >{{$cname->customer_name}}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="mg-b-10 mg-r-5">
                                        <input name="search" type="search" id="search" class="form-control" style="font-size:12px;width: 150px;"  placeholder="Search Order Number" value="{{ $filter->search }}">
                                    </div>

                                    <div class="mg-b-10">
                                        <button class="btn btn-sm btn-info" type="button" id="btnfilter">Search</button>
                                        <a class="btn btn-sm btn-success" href="{{route('sales-transaction.index')}}">Reset</a>
                                    </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
            <!-- End Filters -->


            <!-- Start Pages -->
            <div class="col-md-12">
                <div class="table-list mg-b-10">
                    <table class="table mg-b-0 table-light table-hover" id="table_sales">
                        <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Order Date</th>
                            <th>Payment Date</th>
                            <th>Customer</th>
                            <th>Total Amount</th>
                            <th>Order Status</th>
                            <th>Payment Status</th> 
                            <th class="exclude_export">Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        @forelse($sales as $sale)
                            <tr class="pd-20">
                                <td><strong> {{$sale->order_number }}</strong></td>
                                <td>{{ $sale->created_at }}</td>
                                <td>
                                    @if(\App\Models\Ecommerce\SalesPayment::check_if_has_added_payments($sale->id) == 1)
                                        @php
                                            $last_paid = \App\Models\Ecommerce\SalesPayment::where('sales_header_id',$sale->id)->orderBy('payment_date','desc')->first();
                                        @endphp
                                        {{ date('Y-m-d',strtotime($last_paid->payment_date) )}}
                                    @endif
                                </td>
                                <td>{{ $sale->customer_name }}</td>
                                <td>
                                    @if(\App\Models\Ecommerce\SalesPayment::check_if_has_added_payments($sale->id) == 1)
                                        <a href="javascript:;" onclick="show_added_payments('{{$sale->id}}');">{{ number_format($sale->net_amount,2) }}</a>
                                        {{-- <a href="javascript:;" onclick="show_added_payments('{{$sale->id}}');">{{ number_format($sale->net_amount - $sale->discount_amount + $sale->ecredit_amount,2) }}</a> --}}
                                    @else
                                        {{ number_format($sale->net_amount, 2) }}
                                        {{-- {{ number_format($sale->net_amount - $sale->discount_amount + $sale->ecredit_amount,2) }} --}}
                                    @endif
                                </td>
                                {{-- <td><a href="{{route('admin.report.delivery_report',$sale->id)}}" target="_blank">{{$sale->delivery_status}}</a></td> --}}
                                <td>
                                    @if($sale->cancellation_request == 1)
                                        <a href="{{route('admin.report.delivery_report',$sale->id)}}" target="_blank">CANCELLED <span class="text-danger">| {{$sale->cancellation_reason}}</span></a>
                                    @else
                                        {{-- <a href="{{ route('admin.report.delivery_report', $sale->id) }}" target="_blank">{{ $sale->delivery_status }} | <span class="text-dark">{{ optional($sale->deliveries->last())->remarks }}</span></a> --}}
                                        <a href="{{ route('admin.report.delivery_report', $sale->id) }}" target="_blank">{{ $sale->delivery_status }} @if(optional($sale->deliveries->last())->remarks) <span class="text-dark"> |  {{ optional($sale->deliveries->last())->remarks }}</span> @endif</a>
                                        
                                    @endif
                                </td>
                                {{-- <td>{{ $sale->payment_status }}</td> --}}
                                <td>{{ $sale->Paymentstatus }}</td>
                                <td>
                                    <nav class="nav table-options">
                                        @if($sale->trashed())
                                            <nav class="nav table-options">
                                                <a class="nav-link" href="{{route('sales-transaction.restore',$sale->id)}}" title="Restore this Sales Transaction"><i data-feather="rotate-ccw"></i></a>
                                            </nav>
                                        @else

                                            <a class="nav-link" href="{{ route('sales-transaction.view',$sale->id) }}" title="View Page"><i data-feather="eye"></i></a>
                                            <a class="nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i data-feather="settings"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                @if($sale->status == 'UNPAID')
                                                    <a class="dropdown-item" data-toggle="modal" data-target="#prompt-change-status" title="Update Sales Transaction" data-id="{{$sale->id}}" data-status="PAID">Paid</a>
                                                @else
                                                @endif

                                                @if($sale->status<>'CANCELLED')
                                                    @if (auth()->user()->has_access_to_route('sales-transaction.delivery_status'))
                                                        <a class="dropdown-item" href="javascript:void(0);" onclick="$('#prompt-change-delivery-status{{ $sale->id }}').modal('show');" title="Update Delivery Status" data-id="{{$sale->id}}">Update Delivery Status</a>
                                                        {{-- <a class="dropdown-item" href="javascript:void(0);" onclick="change_delivery_status({{$sale->id}})" title="Update Delivery Status" data-id="{{$sale->id}}">Update Delivery Status</a> --}}
                                                    @endif
                                                @endif
                                                <a class="dropdown-item disallow_when_multiple_selected" href="javascript:void(0);" onclick="show_delivery_history({{$sale->id}})" title="Update Delivery Status" data-id="{{$sale->id}}">Show Delivery History</a>
                                                <a class="dropdown-item disallow_when_multiple_selected" target="_blank" href="{{ route('sales-transaction.view_payment',$sale->id) }}" title="Show payment" data-id="{{$sale->id}}">Sales Payment</a>

                                                @if($sale->status<>'CANCELLED')
                                                    @if (auth()->user()->has_access_to_route('sales-transaction.destroy'))
                                                        <a class="dropdown-item text-danger disallow_when_multiple_selected" href="javascript:void(0)" onclick="delete_sales({{$sale->id}},'{{$sale->order_number}}')" title="Cancel Transaction">Cancel</a>
                                                    @endif
                                                @endif
                                            </div>
                                        @endif
                                    </nav>

                                </td>
                            </tr>


                            {{-- Delivery Status Update --}}
                            <div class="modal effect-scale" id="prompt-change-delivery-status{{ $sale->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalCenterTitle">{{__('Delivery Status')}}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form id="dd_form" method="POST" action="{{route('sales-transaction.delivery_status')}}">
                                            @csrf
                                            @method('POST')
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="delivery_status">Status</label>
                                                    <select id="delivery_status" class="custom-select mg-b-5" name="delivery_status" data-style="btn btn-outline-light btn-md btn-block tx-left" title="- None -" data-width="100%" required="required">
                                                        <option value="Pending">Pending</option>
                                                        <option value="Processing">Processing</option>
                                                        <option value="In Transit">In Transit</option>
                                                        <option value="Delivered">Delivered</option>
                                                        <option value="Returned">Returned</option>
                                                        <option value="Cancelled">Cancelled</option>
                                                    </select>
                                                    <p class="tx-10 text-danger" id="error">
                                                        @error('delivery_status')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </p>
                                                </div>
                                                <div class="form-group">
                                                    <label for="delivery_status">Remarks</label>
                                                    <textarea name="del_remarks" class="form-control" id="del_remarks" cols="30" rows="4"></textarea>
                                                </div>
                                            </div>
                                            <input type="hidden" id="del_id" name="del_id" value="{{ $sale->id }}">
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </form>
                            
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <th colspan="17" style="text-align: center;"> <p class="text-danger">No Sales Transaction found.</p></th>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- End Pages -->
            <div class="col-md-6" style="display:block;">
                <div class="mg-t-5">
                    @if ($sales->firstItem() == null)
                        <p class="tx-gray-400 tx-12 d-inline">{{__('common.showing_zero_items')}}</p>
                    @else
                        <p class="tx-gray-400 tx-12 d-inline">Showing {{ $sales->firstItem() }} to {{ $sales->lastItem() }} of {{ $sales->total() }} items</p>
                    @endif
                </div>
            </div>
            <div class="col-md-6" style="display:block;">
                <div class="text-md-right float-md-right mg-t-5">
                    <div>
                        {{ $sales->appends((array) $filter)->links() }}
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <form action="" id="posting_form" style="display:none;" method="post">
        @csrf
        <input type="text" id="pages" name="pages">
        <input type="text" id="status" name="status">
    </form>

    @include('admin.ecommerce.sales.modals')
@endsection

@section('pagejs')
    <script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
    <script src="{{ asset('lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('lib/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>

    <script>
        let listingUrl = "{{ route('sales-transaction.index') }}";
        let searchType = "{{ $searchType }}";
    </script>

    <script src="{{ asset('js/listing.js') }}"></script>
@endsection

@section('customjs')
    <script>
        $('#btnfilter').click(function(e){
            var startDate = new Date(Date.parse($("#startdate").val()));
            var endDate= new Date(Date.parse($("#enddate").val()));

            if (startDate > endDate) {
                alert('Invalid Date Range.');
                return e.preventDefault();
            }

            $('#searchForm').submit(); 

        });

        function delete_sales(x,order_number){
            $('#frm_delete').attr('action',"{{route('sales-transaction.destroy',"x")}}");
            $('#id_delete').val(x);
            $('#delete_order_div').html(order_number);
            $('#prompt-delete').modal('show');
        }

        function show_added_payments(id){
            $.ajax({
                type: "GET",
                url: "{{ route('display.added-payments') }}",
                data: { id : id },
                success: function( response ) {
                    $('#added_payments_tbl').html(response);
                    $('#prompt-show-added-payments').modal('show');
                }
            });
        }

        function show_delivery_history(id){
            $.ajax({
                type: "GET",
                url: "{{ route('display.delivery-history') }}",
                data: { id : id },
                success: function( response ) {
                    $('#delivery_history_tbl').html(response);
                    $('#prompt-show-delivery-history').modal('show');
                }
            });
        }

        function post_form(id,status,pages){

            $('#posting_form').attr('action',id);
            $('#pages').val(pages);
            $('#status').val(status);
            $('#posting_form').submit();
        }

        $(".js-range-slider").ionRangeSlider({
            grid: true,
            from: selected,
            values: perPage
        });


        $('#prompt-change-status').on('show.bs.modal', function (e) {
            //get data-id attribute of the clicked element
            let sales = e.relatedTarget;
            let salesId = $(sales).data('id');
            let salesStatus = $(sales).data('status');
            let formAction = "{{ route('sales-transaction.quick_update', 0) }}".split('/');
            formAction.pop();
            let editFormAction = formAction.join('/') + "/" + salesId;
            $('#editForm').attr('action', editFormAction);
            $('#id').val(salesId);
            $('#editStatus').val(salesStatus);

        });

        function change_delivery_status(id){
            var checked = $('.cb:checked');
            
            var count = checked.length;

            if(count == 1){
                checked.each(function () {
                    $('#del_id').val($(this).val());
                });
            }

            if(count > 1) {

                var ids = [];
                checked.each(function(){
                    ids.push(parseInt($(this).val()));
                });

                $('#del_id').val(ids.join(','));
            }
            if(count < 1){
                $('#del_id').val(id);
            }

            $('#prompt-change-delivery-status').modal('show');
        }
    </script>
@endsection
