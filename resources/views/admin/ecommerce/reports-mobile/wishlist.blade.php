@extends('admin.layouts.report')

@section('pagecss')
@endsection

@section('content')


<div style="margin:0px 40px 200px 40px;font-family:Arial;">
    <br><br>
    <h4 class="mg-b-0 tx-spacing--1">Customer Wishlist Report</h4>
    


    <br><br>
    <table id="example" class="display nowrap" style="width:100%;font: normal 13px/150% Arial, sans-serif, Helvetica;">
        <thead>
            <tr>
                <td>Customer</td>
                <td>Product</td>
                <td>Added At</td>
            </tr>
        </thead>
        <tbody>
            @forelse($rs as $r)
                <tr>
       
                    <td>{{$r->customer_details->fullname}}</td>
                   
                    <td>{{$r->product_details->name}}</td>
                    <td>{{$r->created_at->format('Y-m-d H:i:s')}}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10">No record found.</td>
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



