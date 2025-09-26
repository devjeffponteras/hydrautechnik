@extends('admin.layouts.report')


@section('pagecss')
@endsection

@section('content')
<div style="margin: 40px 40px 200px 40px;font-family:Arial;">
    <h4 class="mg-b-0 tx-spacing--1">Product List</h4>
    @if($rs <>'')
    <br><br>
    <table id="example" class="display nowrap" style="width:100%;font: normal 13px/150% Arial, sans-serif, Helvetica;">
        <thead>
            <tr>
                <th align="left">Code</th>
                <th align="left">Name</th>
                <th align="left">Category</th>
                <th align="left">Price</th>
                <th align="left">Size</th>
                <th align="left">Weight</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rs as $r)
            <tr>
                <td>{{$r->code}}</td>
                <td>{{$r->name}}</td>
                <td>{{$r->category->name}}</td>
                <td>{{number_format($r->price,2)}}</td>                                 
                <td>{{$r->size}}</td>
                <td>{{$r->weight}}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6">No products found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @endif
</div>


@endsection

@section('pagejs')
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



