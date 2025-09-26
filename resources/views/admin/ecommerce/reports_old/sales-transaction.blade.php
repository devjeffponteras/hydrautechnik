@extends('admin.layouts.report')

@section('pagecss')
@endsection

@section('content')


<div style="margin:0px 40px 200px 40px;font-family:Arial;">
    <br><br>
    <h4 class="mg-b-0 tx-spacing--1">Sales Transaction Report</h4>
    <form action="{{route('report.sales-transaction')}}" method="get">
        <input type="hidden" name="act" value="go">
        @csrf
        <table style="font-size:12px;">
            <tr>
                <td>Start Date</td>
                <td>End Date</td>
                <td>Client/Customer Name</td>
                <td>Item/Purchase Description</td>
                <td>Status</td>
            </tr>
            <tr>
                <td><input style="font-size:12px;width: 140px;" type="date" class="form-control input-sm" name="start" autocomplete="off"
                    value="{{$startDate}}">
                </td>
                <td><input style="font-size:12px;width: 140px;" type="date" class="form-control input-sm" name="end" autocomplete="off"
                    value="{{$endDate}}">
                </td>
                <td>
                    <select style="font-size:12px;width: 140px;" name="customer" id="customer" class="form-control input-sm">
                        <option value="">Select</option>
                        @php
                        $customers = \App\Models\User::where('role_id','6')->orderBy('name')->get();
                        @endphp
                        @forelse($customers as $cu)
                        <option value="{{$cu->fullname}}"
                            @if(isset($customer) and $customer == $cu->fullname) selected="selected" @endif 
                            >
                            {{$cu->name}}
                        </option>
                        @empty
                        @endforelse
                    </select>
                </td>
                <td>
                    <select style="font-size:12px;width: 140px;" name="product" id="product" class="form-control input-sm">
                        <option value="">Select</option>
                        @php
                        $products = \App\Models\Ecommerce\Product::orderBy('name')->get();
                        @endphp
                        @forelse($products as $p)
                        <option value="{{$p->name}}"
                            @if(isset($product) and $product == $p->name) selected="selected" @endif 
                            >
                            {{$p->name}}
                        </option>
                        @empty
                        @endforelse
                    </select>
                </td>
                <td>
                    <select style="font-size:12px;width: 140px;" name="del_status" id="del_status" class="form-control input-sm">
                        <option value="">Select</option>
                        <option @if(isset($status) && $status == 'Pending') selected="selected" @endif value="Pending">Pending</option>
                        <option @if(isset($status) && $status == 'Pending') selected="selected" @endif value="Pending">Pending</option>
                        <option @if(isset($status) && $status == 'Processing') selected="selected" @endif value="Processing">Processing</option>
                        <option @if(isset($status) && $status == 'Ready For delivery') selected="selected" @endif value="Ready For delivery">Ready For delivery</option>
                        <option @if(isset($status) && $status == 'In Transit') selected="selected" @endif value="In Transit">In Transit</option>
                        <option @if(isset($status) && $status == 'Delivered') selected="selected" @endif value="Delivered">Delivered</option>
                        <option @if(isset($status) && $status == 'Returned') selected="selected" @endif value="Returned">Returned</option>
                        <option @if(isset($status) && $status == 'Cancelled') selected="selected" @endif value="Cancelled">Cancelled</option>                                     
                    </select>
                </td>
                <td><button type="submit" class="btn btn-sm btn-primary" style="margin:0px 0px 0px 10px;">Generate</button></td>
                <td><a href="{{ route('report.sales-transaction') }}" class="btn btn-sm btn-success" style="margin:0px 0px 0px 5px;">Reset</a></td>
            </tr>
        </table>
    </form>


    <br><br>
    <table id="example" class="display nowrap" style="width:100%;font: normal 13px/150% Arial, sans-serif, Helvetica;">
        <thead>
            <tr>
                <td>Date</td>
                <td>Transaction Ref#</td>
                <td>Customer No.</td>
                <td>Client/Customer Name</td>
                <td>Delivery Address</td>
                <td>Item/Purchase Description</td>
                <td>Quantity</td>
                <td>Unit Price</td>
                <td>Total</td>
                <td>Payment Method</td>
                <td>Status</td>
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $sale)
                <tr>
                    <td>{{SettingHelper::datetimeFormat2($sale->header->created_at)}}</td>
                    <td>{{$sale->order_number}}</td>
                    <td>{{str_pad(($sale->header->user->id), 8, '0', STR_PAD_LEFT)}}</td>
                    <td>{{$sale->header->user->fullname}}</td>
                    <td>{{$sale->header->customer_delivery_adress}}</td>
                    <td>{{$sale->product->name}}</td>
                    <td class="text-right">{{$sale->qty}}</td>
                    <td class="text-right">₱{{number_format($sale->price,2)}}</td>
                    <td class="text-right">₱{{number_format($sale->price*$sale->qty,2)}}</td>
                    <td>{{$sale->payment_method}}</td>
                    <td>{{$sale->header->delivery_status}}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10">No sales transaction found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@section('pagejs')
<script src="{{ asset('lib/bselect/dist/js/bootstrap-select.js') }}"></script>
<script src="{{ asset('lib/bselect/dist/js/i18n/defaults-en_US.js') }}"></script>
<script src="{{ asset('lib/prismjs/prism.js') }}"></script>
<script src="{{ asset('lib/jqueryui/jquery-ui.min.js') }}"></script>

@endsection

@section('customjs')
<script src="{{ asset('js/datatables/Buttons-1.6.1/js/buttons.colVis.min.js') }}"></script>
<script>


    $(document).ready(function() {
        $('#example').DataTable( {
            dom: 'Bfrtip',
            pageLength: 20,
            order: [[0,'desc']],
            buttons: [
            {
                extend: 'print',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'copy',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'csv',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'excel',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {   
                extend: 'pdfHtml5',
                text: 'PDF',
                exportOptions: {
                    modifier: {
                        page: 'current'
                    }
                },
                orientation : 'landscape',
                pageSize : 'LEGAL'
            },
            'colvis'
            ],
            columnDefs: [ {

                visible: false
            } ]
        } );
    } );
</script>
@endsection



