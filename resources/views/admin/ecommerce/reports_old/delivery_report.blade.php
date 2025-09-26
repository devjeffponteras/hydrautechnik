<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ config('app.name', 'Ecommerce') }}</title>
</head>
<body>
    <table style="width:100%;font: normal 13px/150% Arial, sans-serif, Helvetica;">
        <tr>
            <td align="center"><img src="{{ asset('storage/logos/'.Setting::info()->company_logo) }}" height="100" alt=""></td>
        </tr>
        <tr>
            <td align="center" style="font-size:18px;font-weight:bold;">Delivery Receipt</td>
        </tr>
        <tr>
            <td align="center">TELEPHONE NOS: 939-1221/851-2987 to 88</td>
        </tr>
        <tr>
            <td align="center">REFERENCE #: {{$rs->order_number}}</td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td valign="top">
                <table>
                    <tr>
                        <td>Customer Name:</td>
                        <td>{{$rs->customer_name}}</td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td>{{ $rs->customer_email }}</td>
                    </tr>
                    <tr>
                        <td>Address:</td>
                        <td>{{$rs->customer_delivery_adress}}</td>
                    </tr>
                    <tr>
                        <td>Contact (Tel/Mobile):</td>
                        <td>{{$rs->customer_contact_number}}</td>
                    </tr>
                </table>
            </td>
            <td valign="top">
                <table>
                    <tr>
                        <td>Date:</td>
                        <td>{{date('F d, Y',strtotime($rs->created_at))}}</td>
                    </tr>
                    <tr>
                        <td>Time:</td>
                        <td>{{date('H:i A',strtotime($rs->update_at))}}</td>
                    </tr>
                    <tr>
                        <td>Delivery Status:</td>
                        <td>{{ $rs->delivery_status }}</td>
                    </tr>
                    <tr>
                        <td>Payment Status:</td>
                        <td>{{ $rs->payment_status }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <br><br><br>
    <table style="width:100%;font: normal 13px/150% Arial, sans-serif, Helvetica;">
        <thead>
            <tr>
                <td>Item</td>
                <td>Qty</td>
                <td><center>Price</center></td>
                <td><center>Total</center></td>

            </tr>
        </thead>
        <tbody>
            @php
                $total = 0;
            @endphp
            @forelse($rs->items as $r)
            @php
                $total += ($r->price * $r->qty);
            @endphp
            <tr>
                <td>{{$r->product_name}}</td>
                <td>{{number_format($r->qty,2)}}</td>
                <td style="text-align: right;">{{number_format($r->price,2)}}</td>
                <td style="text-align: right;">{{number_format(($r->price * $r->qty),2)}}</td>
                
            </tr>
            @empty
            @endforelse
            <tr>
                <td colspan="5"><hr></td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right;"><strong>Sub Total</strong></td>
                <td align="right"><strong>{{number_format($total,2)}}</strong></td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right;"><strong>Coupon Discount</strong></td>
                <td align="right"><strong>- {{number_format($rs->discount_amount,2)}}</strong></td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right;"><strong>Grand Total</strong></td>
                <td align="right"><strong>{{number_format($total-$rs->discount_amount,2)}}</strong></td>
            </tr>
        </tbody>
    </table>

    <br><br>
    <table width="100%">
        <tr>
            <td align="center" colspan="3">RECEIVED THE ABOVE IN GOOD ORDER AND CONDITION<br><br><br><br></td>
        </tr>
        <tr>
            <td>Print Name</td>
            <td>Signature</td>
            <td>Date Time</td>
        </tr>
    </table>
</body>
</html>




