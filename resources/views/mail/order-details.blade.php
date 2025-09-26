<!DOCTYPE html>
<html>

<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <style type="text/css">
        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
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

        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        @media screen and (max-width: 480px) {
            .mobile-hide {
                display: none !important;
            }

            .mobile-center {
                text-align: center !important;
            }
        }

        div[style*="margin: 16px 0;"] {
            margin: 0 !important;
        }
    </style>

<body style="margin: 0 !important; padding: 0 !important; background-color: #eeeeee;" bgcolor="#eeeeee">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" style="background-color: #eeeeee;" bgcolor="#eeeeee">
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:1000px;">
                    <tr>
                        <td align="center" style="padding: 35px 35px 20px 35px; background-color: #ffffff;" bgcolor="#ffffff">
                            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:1000px;">
                                <tr>
                                    <td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 25px;"> <img src="https://img.icons8.com/carbon-copy/100/000000/checked-checkbox.png" width="125" height="120" style="display: block; border: 0px;" /><br>
                                        <h2 style="font-size: 30px; font-weight: 800; line-height: 36px; color: #333333; margin: 0;"> Thank You For Your Order! </h2>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 10px;">
                                        <p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #777777;">Hi {{$h->customer_name}}!</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 10px;">
                                        <p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #777777;">Thank you for your order on <strong>{{$setting->company_name}}!</strong> You will find your order details below.</p>
                                    </td>
                                </tr>

                                <!-- ORDER DETAILS -->
                                <tr>
                                    <td align="left" style="padding-top: 20px;">
                                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td width="60%" align="left" bgcolor="#000000" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px;color:#fff;"> {{$h->order_number}} </td>
                                                <td width="40%" align="right" bgcolor="#000000" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px;color:#fff;"> {{date('F d, Y H:i A',strtotime($h->created_at))}} </td>
                                            </tr>
                                            <tr>
                                                <td width="60%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">{{$h->customer_name}} </td>
                                            </tr>
                                            <tr>
                                                <td width="60%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 50px 10px 0px 10px;"> {{$h->customer_email}} </td>
                                            </tr>
                                            <tr>
                                                <td width="60%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 5px 10px;"> {{$h->customer_contact_number}}</td>
                                            </tr>
                                            <tr>
                                                <td width="60%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 30px 10px 0px 10px;"> {{$h->customer_delivery_adress}}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>


                                <!-- ITEMS -->
                                <tr>
                                    <td align="left" style="padding-top: 20px;">
                                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td width="30%" align="left" bgcolor="#3E91F6" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px;color:#fff;"> Product </td>
                                                
                                                <td width="20%" align="center" bgcolor="#3E91F6" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px;color:#fff;"> Unit Price </td>
                                                
                                                <td width="20%" align="center" bgcolor="#3E91F6" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px;color:#fff;"> Discounted Price </td>

                                                <td width="10%" align="center" bgcolor="#3E91F6" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px;color:#fff;"> Quantity </td>
                                                
                                                <td width="20%" align="center" bgcolor="#3E91F6" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px;color:#fff;"> Total </td>
                                            </tr>
                                            @php $subtotal = 0; @endphp

                                            @foreach($h->items as $i)
                                            @php
                                                $subtotal += $i->price * $i->qty;
                                            @endphp
                                            <tr>
                                                <td width="30%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">{{$i->product_name}}</td>
                                                
                                                <td width="20%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">₱{{number_format($i->price,2)}}</td>
                                                
                                                <td width="20%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">₱{{ number_format($i->product->price-$i->product->discountedprice,2) }}</td>
                                                
                                                <td width="10%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">{{$i->qty}}</td>
                                                
                                                <td width="20%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">₱{{number_format(($i->price*$i->qty),2)}}</td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    </td>
                                </tr>

                                <!-- SUBTOTAL -->
                                <tr>
                                    <td align="left" style="padding-top: 20px;">
                                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td colspan="3" width="75%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;"> SUBTOTAL: </td>
                                                <td width="25%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;"> ₱{{number_format($subtotal,2)}} </td>
                                            </tr>

                                            @if($h->discount_amount > 0)
                                            <tr>
                                                <td colspan="3" width="75%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;"> COUPON DISCOUNT: </td>
                                                <td width="25%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;"> -₱{{number_format($h->discount_amount,2)}} </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" width="75%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;"> COUPONS: </td>
                                                <td width="25%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;">
                                                    @foreach($h->coupons as $couponSale)
                                                        {{ $couponSale->details->name }}<br>
                                                    @endforeach
                                                </td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td colspan="3" width="75%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;"> DELIVERY FEE: </td>
                                                <td width="25%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;"> ₱{{number_format($h->delivery_fee_amount,2)}} </td>
                                            </tr>

                                            @php
                                                $delivery_discount = \App\Models\Ecommerce\CouponSale::total_discount_delivery($h->id);
                                            @endphp

                                            @if($delivery_discount > 0)
                                            <tr>
                                                <td colspan="3" width="75%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;"> DELIVERY DISCOUNT: </td>
                                                <td width="25%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;"> ₱{{number_format($delivery_discount,2)}} </td>
                                            </tr>
                                            @endif

                                            <tr>
                                                <td colspan="3" width="75%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;"> GRAND TOTAL: </td>
                                                {{-- <td width="25%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;"> ₱{{number_format(($subtotal - $h->discount_amount) - $h->ecredit_amount,2)}} </td> --}}
                                                <td width="25%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;"> ₱{{number_format($subtotal - $h->discount_amount + ($h->delivery_fee_amount - $delivery_discount ),2)}} </td>
                                            </tr>
                                            
                                            @if(session('use_ecredit'))
                                            <tr>
                                                <td colspan="3" width="75%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;"> E-WALLET PAYMENT: </td>
                                                <td width="25%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;"> -₱{{number_format($h->ecredit_amount,2)}} </td>
                                            </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 10px;">
                                        <p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #777777;">You can also view your orders by logging in to your account <a href="{{ route('profile.sales') }}" target="_blank">Login</a></p>
                                    </td>
                                </tr>
                                
                            </table>
                        </td>
                    </tr>

                    
                    <tr>
                        <td align="center" style="padding: 35px; background-color: #ffffff;" bgcolor="#ffffff">
                            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:1000px;">
                                <tr>
                                    <td align="center"> <img src="{{ Setting::get_company_logo_storage_path() }}" width="37" height="37" style="display: block; border: 0px;" /> </td>
                                </tr>
                                <tr>
                                    <td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 5px;">
                                        <p style="font-size: 14px; font-weight: 800; line-height: 5px; color: #333333;"> {{ Setting::info()->company_name }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 5px;">
                                        <p style="font-size: 14px; font-weight: 400; line-height: 5px; color: #777777;"> {{ Setting::info()->company_address }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 5px;">
                                        <p style="font-size: 14px; font-weight: 400; line-height: 5px; color: #777777;"> {{ Setting::info()->tel_no }} | {{ Setting::info()->mobile_no }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 5px;">
                                        <p style="font-size: 14px; font-weight: 400; line-height: 5px; color: #777777;"> {{ url('/') }}</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>