<?php

namespace App\Models\APIModels;

use \Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Session;
use Hash;
use View;
use Input;
use Image;
use DB;

use App\Models\APIModels\Misc;
use App\Models\APIModels\Email;
use App\Models\APIModels\Cart;
use App\Models\APIModels\Voucher;
use App\Models\APIModels\UserCustomer;

class Order extends Model
{
  
  public function getOrderList($data){
    
    $UserID=$data['UserID'];

    $Status=$data['Status'];
    $SearchText=$data['SearchText'];
    
    $Limit=$data['Limit'];
    $PageNo=$data['PageNo'];

     $query = DB::table('ecommerce_sales_headers as sales_hdr')
     ->join('ecommerce_sales_payments as sales_pay', 'sales_pay.sales_header_id', '=', 'sales_hdr.id') 
     
       ->selectraw("
          sales_hdr.id as sales_Header_ID,

          COALESCE(sales_hdr.created_at,'') as order_date,
          DATE_FORMAT(sales_hdr.created_at,'%m/%d/%Y') as order_date_format,
          
          COALESCE(sales_hdr.order_number,'') as order_number,
          COALESCE(sales_hdr.order_source,'') as order_source,
          COALESCE(sales_hdr.customer_name,'') as customer_name,
          
          COALESCE(sales_hdr.customer_email,'') as customer_email,
          COALESCE(sales_hdr.customer_contact_number,'') as customer_contact_number,

          COALESCE(sales_hdr.customer_address,'') as customer_address,   

          COALESCE(sales_hdr.customer_delivery_adress,'') as customer_delivery_adress,                
          COALESCE(sales_hdr.customer_delivery_zip,'') as customer_delivery_zip,                
          
          COALESCE(sales_hdr.delivery_type,'') as delivery_type,          
          COALESCE(sales_hdr.delivery_fee_amount,0) as delivery_fee_amount,

          COALESCE(sales_hdr.gross_amount,0) as gross_amount,  
          COALESCE(sales_hdr.tax_amount,0) as tax_amount,
          COALESCE(sales_hdr.net_amount,0) as net_amount,
          COALESCE(sales_hdr.discount_amount,0) as discount_amount,

          COALESCE(sales_hdr.other_instruction,'') as order_instruction,
          
          COALESCE(sales_hdr.payment_status,'') as payment_status,        
          COALESCE(sales_hdr.other_instruction,'') as other_instruction,  

          COALESCE(sales_pay.payment_type,'') as payment_method,        
          COALESCE(sales_pay.amount,0) as payment_amount,        
          COALESCE(sales_pay.status,'') as payment_status,        
          COALESCE(sales_pay.receipt_number,'') as receipt_number,

          COALESCE(sales_hdr.status,'') as status          
          
        ");  

       $query->whereIn("sales_hdr.order_source",['Android','iOS']);    
       $query->where("sales_hdr.user_id",'=',$UserID);    

                                      
      if($SearchText != ''){
        $arSearchText = explode(" ",$SearchText);
        if(count($arSearchText) > 0){
            for($x=0; $x< count($arSearchText); $x++) {
                $query->whereraw(
                    "CONCAT_WS(' ',
                        COALESCE(sales_hdr.order_number,''),
                        COALESCE(sales_hdr.order_source,''),                        
                        COALESCE(sales_hdr.status,'')
                    ) like '%".str_replace("'", "''", $arSearchText[$x])."%'");
             }
        }
    }

    if($Limit > 0){
      $query->limit($Limit);
      $query->offset(($PageNo-1) * $Limit);
    }

    $query->orderBy("sales_hdr.created_at","DESC");    
    $list = $query->get();
                             
     return $list;             
           
  }

  public function proceedToCheckOut($data){
    
    $Misc  = New Misc();
    $Cart = new Cart();
    $UserCustomer  = New UserCustomer();
    $Voucher  = New Voucher();

    $TODAY = date("Y-m-d H:i:s");
    $PaymentDate = date("Y-m-d");
    
    // CUSTOMER
    $CustomerName='';
    $CustomerEmailAddress='';
    $CustomerMobileNo='';

    $ZipCode='';
    $CompleteAddress='';
    $CompleteDeliveryAddress='';
    
    $GrossAmount='0';
    $TaxAmount='0';
    $NetAmount='0';    
    
    $Platform=$data['Platform'];
    $UserID=$data['UserID'];
    
    // $SubTotal=$data['SubTotal'];
    $AmountPaid=$data['AmountPaid'];
    $PaymentMethod=$data['PaymentMethod'];

    $UsedECredit=$data['ApplyECredit'];  
    $CurrentEWalletCredit=0;  

    $VoucherCode=$data['VoucherCode'];    
    $VoucherDiscountAmount=$data['VoucherDiscountAmount'];    

    if($PaymentMethod=='Debit Card/Credit Card' ||  $PaymentMethod=='EWallet'){
       $PaymentStatus='PAID';
    }else{
        $PaymentStatus='UNPAID';
    }

    if($UserID>0){

     $customer_info=$UserCustomer->getCustomerInformation($data);
       if(isset($customer_info)>0){
          $CustomerName=$customer_info->fullname;
          $CustomerEmailAddress=$customer_info->emailaddress;
          $CustomerMobileNo=$customer_info->mobile;

          $CompleteAddress=$customer_info->address_street.' ,'.$customer_info->address_city;
          $CompleteDeliveryAddress=$customer_info->address_street.' ,'.$customer_info->address_city;
          $ZipCode=$customer_info->address_zip;  
          $CurrentEWalletCredit=$customer_info->ecredits;                      
       } 

      $ProductPrice=0;
      $cart_info = $Cart->getCartInfoByUserID($UserID);
      if(count($cart_info)>0){
        foreach($cart_info as $list){
            if($list->discount_amount>0){
                $ProductPrice=$list->discount_amount;
            }else{
                $ProductPrice=$list->price;    
            }
           $GrossAmount= $GrossAmount + $ProductPrice;
        } 

        $NetAmount=$GrossAmount - $VoucherDiscountAmount;
      }
     
     //HEADER
    //$OrderNo=$Misc->GenerateRandomNo(6,'ecommerce_sales_headers','order_number'); 
     
    $OrderNo=$Misc->getNextOrderNumberFormat();      
    $SalesHeaderID = DB::table('ecommerce_sales_headers')
        ->insertGetId([                                            
          'user_id' => $UserID,              
          'order_number' => $OrderNo,                                            
          'order_source' => $Platform,                                            
          'customer_name' => $CustomerName, 
          'customer_email' => $CustomerEmailAddress, 
          'customer_contact_number' => $CustomerMobileNo, 
          'customer_address' => $CompleteAddress, 
          'customer_delivery_adress' => $CompleteDeliveryAddress, 
          'customer_delivery_zip' => $ZipCode,                           
          'gross_amount' => $GrossAmount, 
          'net_amount' => $NetAmount, 
          'discount_amount' => $VoucherDiscountAmount, 
          'gross_amount' => $GrossAmount, 
          'payment_method' => $PaymentMethod,
          'payment_status' => $PaymentStatus, 
          'ecredit_amount' => $UsedECredit, 
          'delivery_type' => 'd2d', 
          'delivery_status' => 'Delivered', 
          'delivery_fee_amount' => 0, 
          'delivery_fee_discount' => 0, 
          'status' => 'Active', 
          'created_at' => $TODAY             
        ]); 
        
   if($SalesHeaderID>0){

      //PAYMENT
      $ReceiptNo=$Misc->GenerateRandomNo(6,'ecommerce_sales_headers','order_number'); 
      $PaymentHeaderID = DB::table('ecommerce_sales_payments')
         ->insertGetId([                                            
          'sales_header_id' => $SalesHeaderID,              
          'payment_type' => $PaymentMethod,                                            
          'amount' => $NetAmount,                                            
          'status' => $PaymentStatus, 
          'payment_date' => $PaymentDate, 
          'receipt_number' => $ReceiptNo,
          'created_by' => $UserID,
          'created_at' => $TODAY             
        ]); 

      //SAVE COUPON TO COUPON SALES
       if($VoucherCode!=''){

         $CouponID=0;
         $info=$Voucher->getVoucherInfoByCode($VoucherCode);

         if(isset($info)>0){

            $CouponID=$info->coupon_ID;         
             $CopuponSalesID = DB::table('coupon_sales')
                  ->insertGetId([                                            
                  'customer_id' => $UserID,              
                  'coupon_id' => $CouponID,
                  'coupon_code' => $VoucherCode,
                  'sales_header_id' => $SalesHeaderID,
                  'order_status' => $PaymentStatus,                                                                
                  'created_at' => $TODAY             
                ]); 
         }
     }         
   

     //DETAIL PRODUCT
     $cart_info = $Cart->getCartInfoByUserID($UserID);
     if(count($cart_info)>0){
        foreach($cart_info as $item_list){
        
        // SAVE TO SALES DETAIL
        $SalesDetailID = DB::table('ecommerce_sales_details')
            ->insertGetId([                                            
              'sales_header_id' => $SalesHeaderID,              
              'product_id' => $item_list->book_ID,              
              'product_name' => $item_list->name, 
              'product_category' => $item_list->category_id,              
              'price' => $item_list->price,              
              'qty' => 0, 
              'uom' => $item_list->uom, 
              'tax_amount' =>0,              
              'promo_id' =>0,  
              'promo_description' =>'',  
              'tax_amount' =>0,  
              'discount_amount' => $item_list->discount_amount,                        
              'gross_amount' => $GrossAmount,                                                        
              'net_amount' => $NetAmount,
              'created_by' => $UserID,                        
              'created_at' => $TODAY             
          ]); 

          // SAVE TO LIBRARIES
           $SalesDetailID = DB::table('customer_libraries')
            ->insertGetId([                                            
              'user_id' => $UserID,              
              'product_id' => $item_list->book_ID,                                              
              'created_at' => $TODAY             
          ]); 
      
      }
        
      //DELET CART ITEMS
       DB::table('ecommerce_shopping_cart')
          ->where('user_id', $UserID)->delete();  

       //EWALLET PAYMENT METHOD
       if($PaymentMethod=='EWallet'){
             if($UsedECredit>0){

                //UPDATE CURRENT BALANCE EWALLET
                 $BalanceEWalletCredit=$CurrentEWalletCredit-$UsedECredit;

                   $CreditBalanceID = DB::table('ecredits')
                    ->insertGetId([                                            
                      'user_id' => $UserID,              
                      'used_credits' => $UsedECredit,                                              
                      'balance' => $BalanceEWalletCredit,  
                      'remarks' => 'Used '.$UsedECredit.' e-credit as payment for order no. '.$OrderNo,
                      'created_at' => $TODAY             
                  ]); 

                 
                 DB::table('users')
                  ->where('id',$UserID)
                  ->update([                              
                    'ecredits' => $BalanceEWalletCredit,                                                            
                    'updated_at' => $TODAY
                ]);    

             }
         }            
      } 
  }
        
   
    //SEND EMAIL NOTIF
     if($SalesHeaderID>0){

        $OrderInfo= $this->getOrderInfo($SalesHeaderID);        
          if($OrderInfo->SalesHeaderID>0){
              $param['OrderID']=$OrderInfo->SalesHeaderID;
              $param['EmailAddress']=$OrderInfo->customer_email;
              $param["MobileNo"] = $OrderInfo->customer_contact_number;
              $param['OrderNo']=$OrderInfo->order_number;        
              $param['OrderInfo']=$OrderInfo;
              $param['OrderItem']=$this->getOrderItemList($SalesHeaderID);
              
              $Email = new Email();
              $Email->SendOrderReceivedEmail($param);    
          }

       }
    
    }
    return 'Success';
  }


 function getOrderInfo($SalesHeaderID){
   
   $query = DB::table('ecommerce_sales_headers as sales_hdr')
     ->leftjoin('ecommerce_sales_payments as sales_pay', 'sales_pay.sales_header_id', '=', 'sales_hdr.id') 
       ->selectraw("
          sales_hdr.id as SalesHeaderID,
        
          COALESCE(sales_hdr.created_at,'') as order_date,
          DATE_FORMAT(sales_hdr.created_at,'%m/%d/%Y') as order_date_format,

          COALESCE(sales_hdr.order_number,'') as order_number,
          COALESCE(sales_hdr.order_source,'') as order_source,
          COALESCE(sales_hdr.customer_name,'') as customer_name,
          
          COALESCE(sales_hdr.customer_email,'') as customer_email,
          COALESCE(sales_hdr.customer_contact_number,'') as customer_contact_number,

          COALESCE(sales_hdr.customer_address,'') as customer_address,   

          COALESCE(sales_hdr.customer_delivery_adress,'') as customer_delivery_adress,                
          COALESCE(sales_hdr.customer_delivery_zip,'') as customer_delivery_zip,                
          
          COALESCE(sales_hdr.delivery_type,'') as delivery_type,          
          COALESCE(sales_hdr.delivery_fee_amount,0) as delivery_fee_amount,

          COALESCE(sales_hdr.gross_amount,0) as gross_amount,  
          COALESCE(sales_hdr.tax_amount,0) as tax_amount,
          COALESCE(sales_hdr.net_amount,0) as net_amount,
          COALESCE(sales_hdr.discount_amount,0) as discount_amount,

          COALESCE(sales_hdr.ecredit_amount,0) as ecredit_amount,
          
          COALESCE(sales_hdr.other_instruction,'') as order_instruction,
          
          COALESCE(sales_hdr.payment_status,'') as payment_status,        
          COALESCE(sales_hdr.other_instruction,'') as other_instruction,  

          COALESCE(sales_pay.payment_type,'') as payment_method,        
          COALESCE(sales_pay.amount,0) as payment_amount,        
          COALESCE(sales_pay.status,'') as payment_status,        
          COALESCE(sales_pay.receipt_number,'') as receipt_number,

          COALESCE(sales_hdr.status,'') as status          
          
        ");    

       $query->where("sales_hdr.id",'=',$SalesHeaderID);    
   
    $list = $query->first();
                             
    return $list;             
           
  }
  
   function getOrderItemList($SalesHeaderID){

    $query = DB::table('ecommerce_sales_details as sls_dtls')
           ->leftjoin('products as prod', 'prod.id', '=', 'sls_dtls.product_id') 
           ->leftjoin('subscriptions as subs', 'subs.id', '=', 'sls_dtls.subscription_plan_id') 

       ->selectraw("
          sls_dtls.id as SalesDetailID,
              
          COALESCE(sls_dtls.sales_header_id,0) as sales_header_id,          
          COALESCE(sls_dtls.product_id,0) as product_id,          
          COALESCE(sls_dtls.product_name,'') as product_name,
          
          COALESCE(sls_dtls.subscription_plan_id,0) as subscription_plan_id,
          COALESCE(subs.title,'') as plan_title,
          COALESCE(subs.short_description,'') as plan_description,
          
          COALESCE(sls_dtls.product_category,'') as product_category,
          COALESCE(sls_dtls.price,0) as price,
          COALESCE(sls_dtls.discount_amount,0) as discount_price,          

          COALESCE(prod.author,'') as author,
          COALESCE(prod.is_premium,0) as is_premium,

          COALESCE((
               SELECT 
                  prod_img.path FROM 
                      product_photos as prod_img                  
                  LEFT JOIN products as prods ON prods.id = prod_img.product_id
                      WHERE prod_img.product_id = sls_dtls.product_id
                      AND prod_img.is_primary = 1    
                  LIMIT 1                                
              )
        ,'') as image_path,

            COALESCE((
              SELECT ROUND(avg(rating))
                  FROM product_reviews as rev
                WHERE rev.product_id = sls_dtls.product_id     
                AND rev.status = 1 
             LIMIT 1                                
              )
        ,0) as rating
          
    ");    

    $query->where("sls_dtls.sales_header_id",'=',$SalesHeaderID);    
    $info = $query->get();
                             
    return $info;             
           
  }

   function getOrderHistoryItemList($UserID){

    $query = DB::table('ecommerce_sales_details as sls_dtls')           
            ->leftjoin('ecommerce_sales_headers as sales_hdr', 'sales_hdr.id', '=', 'sls_dtls.sales_header_id') 

       ->selectraw("
                              
          COALESCE(sls_dtls.product_name,'') as product_name,

          COALESCE(sls_dtls.price,0) as price,                                                        
          COALESCE(sls_dtls.discount_amount,0) as discount_price,          

          COALESCE(sls_dtls.gross_amount,0) as gross_amount,          
          COALESCE(sls_dtls.net_amount,0) as net_amount,

          COALESCE(sales_hdr.created_at,'') as order_date,
          DATE_FORMAT(sales_hdr.created_at,'%m/%d/%Y') as order_date_format,

          COALESCE(sales_hdr.order_number,'') as order_number,
          COALESCE(sales_hdr.order_source,'') as order_source,
          COALESCE(sales_hdr.customer_name,'') as customer_name,
          
          COALESCE(sales_hdr.customer_email,'') as customer_email,
          COALESCE(sales_hdr.customer_contact_number,'') as customer_contact_number,

          COALESCE(sales_hdr.customer_address,'') as customer_address   

          
    ");    

    $query->where("sales_hdr.user_id",'=',$UserID);    
    $list = $query->get();
                             
    return $list;             
           
  }

  

}
