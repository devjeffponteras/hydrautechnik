@extends('admin.layouts.report')

@section('pagecss')
@endsection

@section('content')
<div style="margin: 40px 40px 200px 40px;font-family:Arial;">
    <h4 class="mg-b-0 tx-spacing--1">Top Buyers</h4>
    
    <form action="{{route('report.top-buyers')}}" method="get">
        <input type="hidden" name="act" value="go">
        @csrf
        <table style="font-size:12px;">
            <tr>
                <td>Start Date</td>
                <td>End Date</td>
            </tr>
            <tr>
                <td><input style="font-size:12px;width: 140px;" type="date" class="form-control input-sm" name="start" autocomplete="off"
                    value="{{$startDate}}">
                </td>
                <td><input style="font-size:12px;width: 140px;" type="date" class="form-control input-sm" name="end" autocomplete="off"
                    value="{{$endDate}}">
                </td>
                <td><button type="submit" class="btn btn-sm btn-primary" style="margin:0px 0px 0px 10px;">Generate</button></td>
                <td><a href="{{ route('report.top-buyers') }}" class="btn btn-sm btn-success" style="margin:0px 0px 0px 5px;">Reset</a></td>
            </tr>
        </table>
    </form>

    @if($rs <>'')
    <br><br>
    <table id="example" class="display nowrap" style="width:100%;font: normal 13px/150% Arial, sans-serif, Helvetica;">
        <thead>
            <tr>
                <th align="left">Customer</th>
                <th align="left">No. of Orders</th>
                <th align="left">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rs as $r)
            <tr>
                <td>{{ optional($r->user)->name }}</td>
                <td>{{$r->order_count}}</td>
                <td>{{number_format($r->total_net_amount,2)}}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center">No item.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @endif
    
    <div class="row row-sm">

        <div class="col-md-6">
            <div class="mg-t-5">
                @if ($rs->firstItem() == null)
                    <p class="tx-gray-400 tx-12 d-inline">{{__('common.showing_zero_items')}}</p>
                @else
                    <p class="tx-gray-400 tx-12 d-inline">Showing {{ $rs->firstItem() }} to {{ $rs->lastItem() }} of {{ $rs->total() }} items</p>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="text-md-right float-md-right mg-t-5">
                <div>
                    {{ $rs->links() }}
                </div>
            </div>
        </div>

    </div>
</div>


@endsection

@section('pagejs')
@endsection

@section('customjs')
{{-- <script src="{{ asset('js/datatables/Buttons-1.6.1/js/buttons.colVis.min.js') }}"></script>
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
</script> --}}
@endsection



