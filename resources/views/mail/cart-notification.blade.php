<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <title>Document</title>
    <style type="text/css">
        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        table {
            border-collapse: collapse !important;
        }

        body {
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }
    </style>
</head>
<body>
    <p>Dear {{$firstname}},</p>
    <br>
    <p>We noticed that you have left the following item/s in your cart:</p>

    @php
        $items = \App\Models\Ecommerce\Cart::where('user_id', $customerId)->get();
    @endphp

    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:1000px;">
        <thead bgcolor="#3E91F6">
            <th style="padding: 10px; border:1px solid black;" align="left">Item</th>
            <th style="padding: 10px; border:1px solid black;" align="center">Unit Price</th>
            <th style="padding: 10px; border:1px solid black;" align="center">Discounted Price</th>
            <th style="padding: 10px; border:1px solid black;" align="center">Quantity</th>
            <th style="padding: 10px; border:1px solid black;" align="left">Total</th>
        </thead>
        <tbody>
            @foreach($items as $item)
                @php
                    $pasthour = abs(strtotime(today().' 23:59:59.999') - strtotime($item->created_at))/(60*60);
                @endphp

                @if($pasthour >= 24)
                <tr style="border:1px solid black;">
                    <td style="padding:8px; border:1px solid black;" align="left" >{{$item->product->name}}</td>
                    <td style="padding:8px; border:1px solid black;" align="right">₱{{ number_format($item->price,2) }}</td>
                    <td style="padding:8px; border:1px solid black;" align="right">₱{{ number_format($item->product->price-$item->product->discountedprice,2) }}</td>
                    <td style="padding:8px; border:1px solid black;" align="center">{{$item->qty}}</td>
                    <td style="padding:8px; border:1px solid black;" align="right">₱{{ number_format($item->price*$item->qty,2) }}</td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>
    <br>
    <p>You can still check out your items within the next 24 hours using the following link.</p>
    <p><a class="btn btn-primary" href="{{ route('cart.front.show') }}" target="_blank">View Shopping Cart</a></p>
    <br>
    <p>All the items that have not been checked out will eventually be removed from the cart. Please complete your transactions within 24 hours to ensure the availability of your purchased items.</p>
    <br>
    <p>Kind Regards,</p>
    <p>{{$companyName}}</p>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>
</html>

