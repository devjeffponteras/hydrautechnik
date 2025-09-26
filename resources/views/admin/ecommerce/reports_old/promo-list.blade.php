@extends('admin.layouts.report')

@section('pagecss')
@endsection

@section('content')
<div style="margin: 40px 40px 200px 40px;font-family:Arial;">
    <br><br>
    <h4 class="mg-b-0 tx-spacing--1">Promo List</h4>
    <form action="" method="get">
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
                <td><a href="{{ route('report.promo-list') }}" class="btn btn-sm btn-success" style="margin:0px 0px 0px 5px;">Reset</a></td>
            </tr>
        </table>
    </form>
    <br><br>
    <table id="example" class="display nowrap" style="width:100%;font: normal 13px/150% Arial, sans-serif, Helvetica;">
        <thead>
            <tr>
                <th align="left">Name</th>
                <th align="left">Start Date</th>
                <th align="left">End Date</th>
                <th align="left">Discount (%)</th>
                <th align="left">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($promos as $promo)
            <tr>
                <td>{{$promo->name}}</td>
                <td>{{SettingHelper::datetimeFormat2($promo->promo_start)}}</td>
                <td>{{SettingHelper::datetimeFormat2($promo->promo_end)}}</td>
                <td>{{$promo->discount}}</td>                                
                <td>{{$promo->status}}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5">No promos found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@section('pagejs')
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



