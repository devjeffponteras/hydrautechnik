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
        
        @php($ReferenceNo='')
        @php($ModeOfPayment='')
        @php($OrderDate='')

        @php($FullName='')
        @php($MobileNo='')
        @php($EmailAddress='')

        @php($Address='')      
     
        @php($EWalletPayment=0)  
        @php($DiscountAmount=0)

        @php($SubTotal=0)
        @php($GrandTotal=0)

        @php($OrderInstruction='')  

        @If(isset($OrderInfo) > 0)  
            @php($ReferenceNo=$OrderInfo->order_number)  
            @php($ModeOfPayment=$OrderInfo->payment_method)
            @php($OrderDate=date_format(date_create($OrderInfo->order_date),'F d, Y H:i A'))
                         
            @php($FullName=$OrderInfo->customer_name)
                    
            @php($MobileNo=$OrderInfo->customer_contact_number)       
            @php($EmailAddress=$OrderInfo->customer_email)        
            
            @php($Address=$OrderInfo->customer_delivery_adress)      
                        
            @php($DiscountAmount=$OrderInfo->discount_amount)                
            @php($EWalletPayment=$OrderInfo->ecredit_amount)
            
                
        @endif

        <tr>
            <td align="center" style="background-color: #eeeeee;" bgcolor="#eeeeee">
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:1000px;">
                    <tr>
                        <td align="center" style="padding: 35px 35px 20px 35px; background-color: #ffffff;" bgcolor="#ffffff">
                            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:1000px;">
                                <tr>
                                    <td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 25px;"> <img src="https://preciouspagesbookstore.com.ph/storage/logos/ebooklat-logo-horizontal.png" width="125" height="120" style="display: block; border: 0px;" /><br>
                                        <h2 style="font-size: 30px; font-weight: 800; line-height: 36px; color: #333333; margin: 0;"> Thank You For Your Order! </h2>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 10px;">
                                        <p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #777777;">Hi {{$FullName}}!</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 10px;">
                                        <p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #777777;">Thank you for your order on <strong>{{config('app.CompanyName')}}!</strong> You will find your order details below.</p>
                                    </td>
                                </tr>

                                <!-- ORDER DETAILS -->
                                <tr>
                                    <td align="left" style="padding-top: 20px;">
                                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td width="60%" align="left" bgcolor="#000000" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px;color:#fff;"> {{$ReferenceNo}} </td>
                                                <td width="40%" align="right" bgcolor="#000000" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px;color:#fff;"> {{$OrderDate}} </td>
                                            </tr>
                                            <tr>
                                                <td width="60%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">{{$FullName}} </td>
                                            </tr>
                                            <tr>
                                                <td width="60%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 50px 10px 0px 10px;"> {{$EmailAddress}} </td>
                                            </tr>
                                            <tr>
                                                <td width="60%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 5px 10px;"> {{$MobileNo}}</td>
                                            </tr>
                                            <tr>
                                                <td width="60%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 30px 10px 0px 10px;">  {{$Address}}</td>
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
                                                                                        
                                            @foreach($OrderItem as $order_item)

                                                @php($ProductName=$order_item->product_name)
                                                @php($Qty=1)
                                                @php($Price=$order_item->price)  
                                                @php($DiscountPrice=$order_item->discount_price) 
                                         
                                                 @if($DiscountPrice>0)                                                    
                                                    @php($chkPrice=$DiscountPrice) 
                                                    @php($ItemTotal=$DiscountPrice * $Qty)  
                                                 @else                               
                                                    @php($chkPrice=$Price)                      
                                                    @php($ItemTotal=$Price * $Qty)
                                                 @endif 
                                            

                                            <tr>
                                                <td width="30%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">{{$ProductName}}</td>
                                                
                                                <td width="20%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">₱{{number_format($Price,2)}}</td>
                                                
                                                <td width="20%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">₱{{number_format($DiscountPrice,2)}}</td>
                                                
                                                <td width="10%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">{{$Qty}}</td>
                                                
                                                <td width="20%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;">₱{{number_format($ItemTotal,2)}}</td>
                                            </tr>
                                               
                                               @php($SubTotal=$SubTotal + ($chkPrice * $Qty)) 
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
                                                <td width="25%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;"> ₱{{number_format($SubTotal,2)}} </td>
                                            </tr>

                                            @if($DiscountAmount > 0)
                                            <tr>
                                                <td colspan="3" width="75%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;"> COUPON DISCOUNT: </td>
                                                <td width="25%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;"> -₱{{number_format($DiscountAmount,2)}} </td>
                                            </tr>
                                            @endif
                                 

                                            @if($EWalletPayment > 0)
                                            <tr>
                                                <td colspan="3" width="75%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;"> E-WALLET: </td>
                                                <td width="25%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;"> -₱{{number_format($EWalletPayment,2)}} </td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td colspan="3" width="75%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;"> GRAND TOTAL: </td>
                                                <td width="25%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;"> ₱{{number_format($SubTotal - $DiscountAmount - $EWalletPayment,2)}} </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 10px;">
                                        <p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #777777;">You can also view your orders by logging in to your account <a href="https://beta.ebooklat.phr.com.ph" target="_blank">Login</a></p>
                                    </td>
                                </tr>
                                
                            </table>
                        </td>
                    </tr>

                    
                    <tr>
                        <td align="center" style="padding: 35px; background-color: #ffffff;" bgcolor="#ffffff">
                            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:1000px;">
                                <tr>
                                    <td align="center"> <img src="https://beta.ebooklat.phr.com.ph/storage/logos/ebooklat-logo.png" width="50" height="50" style="display: block; border: 0px;" /> </td>
                                </tr>
                                <tr>
                                    <td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 5px;">
                                        <p style="font-size: 14px; font-weight: 800; line-height: 5px; color: #333333;"> {{config('app.CompanyName')}}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 5px;">
                                        <p style="font-size: 14px; font-weight: 400; line-height: 5px; color: #777777;"> {{config('app.CompanyAddress')}}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 5px;">
                                        <p style="font-size: 14px; font-weight: 400; line-height: 5px; color: #777777;"> {{config('app.CompanyTelephoneNo')}} | {{config('app.CompanyMobileNo')}}</p>
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