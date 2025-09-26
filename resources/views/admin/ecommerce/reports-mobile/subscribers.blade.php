@extends('admin.layouts.report')

@section('pagecss')
@endsection

@section('content')
<div style="margin: 40px 40px 200px 40px;font-family:Arial;">
    <h4 class="mg-b-0 tx-spacing--1">Subscribers List</h4>
    @if($rs <>'')
    <br><br>
    <table id="subscribers" class="display nowrap" style="width:100%;font: normal 13px/150% Arial, sans-serif, Helvetica;">
        <thead>
            <tr>
                <th align="left">Name</th>
                <th align="left">Email</th>
                <th align="left">Contact #</th>
                <th align="left">Subscription</th>
                <th align="left">Status</th>
                <th align="left">Subscription Date</th>
                <th align="left">Remarks</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rs as $r)
                @php 
                    $user_subs = \App\Models\UsersSubscription::getSubscriptions($r->user_id);
                    $user = \App\Models\User::getUser($r->user_id); 
                @endphp
                
                @if($user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->mobile }}</td>
                        <td>
                            {{ \App\Models\Subscription::getPlan($r->plan_id)[0]->title }}
                        </td>
                        <td>
                            @if($r->is_expired == 1)
                                Expired
                            @else
                                @if(($r->is_subscribe == 1 || $r->is_extended == 1) && \Carbon\Carbon::parse($r->end_date) > \Carbon\Carbon::now())
                                    Active
                                @elseif($r->is_cancelled == 1)
                                    Cancelled
                                @else
                                    Expired
                                @endif
                            @endif
                        </td>
                        <td>{{$r->start_date}} - {{$r->end_date}}</td>
                        <td>{{$r->remarks}}</td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="6">No subscribers found.</td>
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
        $('#subscribers').DataTable( {
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



