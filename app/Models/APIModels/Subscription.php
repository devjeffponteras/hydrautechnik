<?php

namespace App\Models\APIModels;

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

use App\Models\APIModels\Order;
use App\Models\APIModels\Misc;
use App\Models\APIModels\UserCustomer;

class Subscription extends Model
{
  
  public function getSubscriptionPlanList($data){

     $query = DB::table('subscriptions as subs')     
    
       ->selectraw("
          subs.id as Users_Subscriptions_ID,          

          COALESCE(subs.title,'') as title,

          COALESCE(subs.no_days,0) as no_days,
          COALESCE(subs.price,0) as price,

          COALESCE(subs.short_description,'') as short_description,
          COALESCE(subs.long_description,'') as long_description,

          COALESCE(subs.status,'') as status,
                    
          COALESCE(subs.created_at,0) as created_at
                        
        ");    
       
       $query->where("subs.status","=",'Active');  
   

     
    $query->orderBy("subs.id","ASC");     
    $list = $query->get();
                             
     return $list;    
    
  }

   public function getSubscriptionPlanInfo($PlanID){

     $query = DB::table('subscriptions as subs')  
       ->selectraw("
          subs.id as Users_Subscriptions_ID,          

          COALESCE(subs.title,'') as title,

          COALESCE(subs.no_days,0) as no_days,
          COALESCE(subs.price,0) as price,

          COALESCE(subs.short_description,'') as short_description,
          COALESCE(subs.long_description,'') as long_description,

          COALESCE(subs.status,'') as status,
                    
          COALESCE(subs.created_at,0) as created_at
                             
          
        ");    
      
      $query->whereRaw('subs.id =?',[$PlanID]);     
      
      $info = $query->first();

     return $info;      

  }

  function proceedToSubscribe($data){
   
    $Misc  = New Misc();
    $UserCustomer  = New UserCustomer();

    $TODAY = date("Y-m-d H:i:s");
    $PaymentDate = date("Y-m-d");
    
    $StartDate = date("Y-m-d H:i:s");
    $EndDate = date("Y-m-d H:i:s");

    $EndDateFormatted="";

    $Platform=$data['Platform'];
    
    $UserID=$data['UserID'];    
    $SubscriptionPlanID=$data['SubscriptionPlanID'];

    $UsedECredit=$data['ApplyECredit'];  
    $CurrentEWalletCredit=0; 

    $PaymentMethod=$data['PaymentMethod'];
    $SubTotal=$data['SubTotal'];

    $GrossAmount=$SubTotal;
    $NetAmount=$SubTotal;

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

     $TitlePlan="";
     $PlanNoDays=0;

     $plan_info=$this->getSubscriptionPlanInfo($SubscriptionPlanID);

       if(isset($plan_info)>0){

          $TitlePlan=$plan_info->title;                   
          $PlanNoDays=$plan_info->no_days;  
          $EndDate = date('Y-m-d H:i:s', strtotime("+".$PlanNoDays." day"));          
          $EndDateFormatted=date_format(date_create($EndDate),'M. j, Y ');
       } 
     

      //Check if existing from Subscription
      $checkCustomerSubscriptionPlanIDExist=0;
      $checkCustomerSubscriptionPlanIDExist=$this->checkCustomerSubscriptionIfExist($UserID);

      if($checkCustomerSubscriptionPlanIDExist<=0){
          // SAVE NEW SUBSCRIPTION
         $User_Subscription_ID = DB::table('users_subscriptions')
            ->insertGetId([                                            
              'user_id' => $UserID,                                                                          
              'plan_id' => $SubscriptionPlanID, 
              'no_days' => $PlanNoDays, 
              'mode_payment' => $PaymentMethod, 
              'amount_paid' => $SubTotal,           
              'start_date' => $StartDate, 
              'end_date' => $EndDate, 
              'is_subscribe' => 1, 
              'remarks' => 'Set a '.$PlanNoDays.' days subscription plan with plan ID:'.$SubscriptionPlanID, 
              'created_at' => $TODAY             
            ]); 

          //Send Notification Message
           $MessageNotificationID = DB::table('message_notification')
                ->insertGetId([                                            
                  'user_id' => $UserID,                                                         
                  'message_notification' => 'You have successfully subscribe to a '.$TitlePlan. " plan & it will expire on ".$EndDateFormatted. " .",
                  'created_at' => $TODAY             
              ]);  

      }else{
           
           // UPDATE AND EXTEND OLD SUBSCRIPTION

           $User_Subscription_ID=$checkCustomerSubscriptionPlanIDExist;

           $OldExpirationDate='';
           $NewExpirationDate='';

           $OldNoOfDays=0;
           $NewNoOfDays=0;

           $info=$UserCustomer->getCustomerCurrentSubscriptionInfo($UserID);
           if(isset($info)>0){
                $StartDate=$info->start_date;
                $EndDate=$info->end_date;
                
                $OldNoOfDays=$info->no_days;
                $NewNoOfDays=$PlanNoDays+$OldNoOfDays;

                $DatePlanNoDaysExtended=$PlanNoDays." days";

                $new_date_extended=date_create($EndDate);
                date_add($new_date_extended,date_interval_create_from_date_string($DatePlanNoDaysExtended));            
                $NewExpiryEndDate=date("Y-m-d H:i:s", strtotime(date_format($new_date_extended,"Y-m-d H:i:s")));

                $NewExpiryEndDateFormatted=date_format(date_create($NewExpiryEndDate),'M. j, Y ');
                          
           }

             DB::table('users_subscriptions')
                  ->where('id',$User_Subscription_ID)
                  ->update([                              
                    'user_id' => $UserID,                                                                          
                    'plan_id' => $SubscriptionPlanID, 
                    'no_days' => $NewNoOfDays, 
                    'mode_payment' => $PaymentMethod, 
                    'amount_paid' => $SubTotal,           
                    'start_date' => $StartDate, 
                    'end_date' => $NewExpiryEndDate, 
                    'is_subscribe' => 1, 
                    'is_extended' => 1, 
                    'is_expired' => 0, 
                    'is_cancelled' => 0, 
                    'cancel_reason' => null, 
                       'remarks' => 'Extended a '.$PlanNoDays.' days subscription plan with plan ID:'.$SubscriptionPlanID, 
                    'created_at' => $TODAY  
                ]); 


                //SEND EXTENDED NOTIF
                $MessageNotificationID = DB::table('message_notification')
                    ->insertGetId([                                            
                      'user_id' => $UserID,                                                         
                      'message_notification' => 'You have successfully extended your current plan to a '.$PlanNoDays.' days subscription & will expired on '.$NewExpiryEndDateFormatted, 
                      'created_at' => $TODAY             
                  ]);  

      }
     
      //save to sales header
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
            'discount_amount' => 0, 
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

        
        if($SalesHeaderID>0 && $User_Subscription_ID>0){

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


              // SAVE TO SALES DETAIL
              $SalesDetailID = DB::table('ecommerce_sales_details')
                  ->insertGetId([                                            
                    'sales_header_id' => $SalesHeaderID,              
                    'product_id' => 0,        
                    'subscription_plan_id' => $SubscriptionPlanID,              
                    'product_name' => $TitlePlan, 
                    'product_category' =>0,              
                    'price' => $SubTotal,              
                    'qty' => 1, 
                    'uom' => '', 
                    'tax_amount' =>0,              
                    'promo_id' =>0,  
                    'promo_description' =>'',  
                    'tax_amount' =>0,  
                    'discount_amount' => '0',                        
                    'gross_amount' => $GrossAmount,                                                        
                    'net_amount' => $NetAmount,
                    'created_by' => $UserID,                        
                    'created_at' => $TODAY             
                ]); 

             //EWALLET PAYMENT METHOD
             if($PaymentMethod=='EWallet'){
                   if($UsedECredit>0){

                      //Save to EWallet Credit History
                       $BalanceEWalletCredit=$CurrentEWalletCredit-$UsedECredit;
                         $CreditBalanceID = DB::table('ecredits')
                          ->insertGetId([                                            
                            'user_id' => $UserID,              
                            'used_credits' => $UsedECredit,                                              
                            'balance' => $BalanceEWalletCredit,  
                            'remarks' => 'Used '.$UsedECredit.' e-credit as payment for subscription with order no. '.$OrderNo,
                            'created_at' => $TODAY             
                        ]); 
                  
                     // Update Customer EWallet     
                       DB::table('users')
                        ->where('id',$UserID)
                        ->update([                              
                          'ecredits' => $BalanceEWalletCredit,                                                            
                          'updated_at' => $TODAY
                      ]);  
                               
                   }
               }  
          }

         //SEND EMAIL NOTIF===============================================================================================
         if($SalesHeaderID>0){
            $Order= new Order();
            $OrderInfo= $Order->getOrderInfo($SalesHeaderID);        
              if($OrderInfo->SalesHeaderID>0){
                  $param['OrderID']=$OrderInfo->SalesHeaderID;
                  $param['EmailAddress']=$OrderInfo->customer_email;
                  $param["MobileNo"] = $OrderInfo->customer_contact_number;
                  $param['OrderNo']=$OrderInfo->order_number;        
                  $param['OrderInfo']=$OrderInfo;
                  $param['OrderItem']=$Order->getOrderItemList($SalesHeaderID);
                  
                  $Email = new Email();
                  $Email->SendOrderReceivedEmail($param);    
              }

            //RESUME & RETUEN SUBSBRIBED BOOKS===
            DB::table('subscribed_books')
              ->where('user_id',$UserID)              
              ->update([                                  
                'deleted_at' => null
            ]);

           }

   }
       
    return 'Success';
  }

  function checkSubscriptionStatus($data){
 
    $UserCustomer= new UserCustomer();
    $TODAY = date("Y-m-d H:i:s");

    $CurrentDay = date("Y-m-d H:i:s"); 

    $CurrentDayFormatted=date_format(date_create($CurrentDay),'M. j, Y ');

    $IsSubcscribe=0;
    $EndDate="";
    $ExpiryDateOneDayBefore="";

     $TitlePlan="";
     $PlanNoDays=0;

    $UserID=$data['UserID'];
    
     if($UserID>0){

       $customer_info=$UserCustomer->getCustomerCurrentSubscriptionInfo($UserID);

       if(isset($customer_info)>0){  
        
          $IsSubcscribe=$customer_info->is_subscribe; 
          $TitlePlan=$customer_info->title_plan;                   
          $PlanNoDays=$customer_info->no_days;                       
          $EndDate=$customer_info->end_date; 
          $EndDateFormatted=date_format(date_create($EndDate),'M. j, Y ');

          $ExpiryDateOneDayBefore=date($EndDate, strtotime('+1 day')); 
          $ExpiryDateTowDaysBefore=date($EndDate, strtotime('-2 day')); 
          $ExpiryDateThreeDaysBefore=date($EndDate, strtotime('-3 day')); 
          
         if($IsSubcscribe=1){ 
                         
            //get the successeding days of 3,2,1 days before the actual expiration day. then send message notification             
            $date1=date_create($EndDate);
            date_sub($date1,date_interval_create_from_date_string("1 days"));            
            $ExpiryDateOneDayBefore=date("Y-m-d", strtotime(date_format($date1,"Y-m-d")));
            
            $date2=date_create($EndDate);
            date_sub($date2,date_interval_create_from_date_string("2 days"));            
            $ExpiryDateTowDaysBefore=date("Y-m-d", strtotime(date_format($date2,"Y-m-d")));
            
            $date3=date_create($EndDate);
            date_sub($date3,date_interval_create_from_date_string("3 days"));            
            $ExpiryDateThreeDaysBefore=date("Y-m-d", strtotime(date_format($date3,"Y-m-d")));
                                            
            // send Message base from days                                
            //check current date if same as 3 days before the expiration date
            if($ExpiryDateThreeDaysBefore==$CurrentDay){                
                $MessageNotificationID = DB::table('message_notification')
                    ->insertGetId([                                            
                      'user_id' => $UserID,                                                         
                      'message_notification' => 'Your subscription of '.$TitlePlan. " plan will end 3 days from now & will expired on ".$EndDateFormatted. " .",
                      'created_at' => $TODAY             
                  ]);   
            }            

            //check current date if same as 3 days before the expiration date
            if($ExpiryDateTowDaysBefore==$CurrentDay){               
              //send message expiration date 1 day before the expiration date  
                $MessageNotificationID = DB::table('message_notification')
                    ->insertGetId([                                            
                      'user_id' => $UserID,                                                         
                      'message_notification' => 'Your subscription of '.$TitlePlan. " plan will end 2 days from now & will expired on ".$EndDateFormatted. " .",
                      'created_at' => $TODAY             
                  ]); 
            }            


            //check current date if same as 3 days before the expiration date
            if($ExpiryDateOneDayBefore==$CurrentDay){                              
              //send message expiration date 1 day before the expiration date  
                $MessageNotificationID = DB::table('message_notification')
                    ->insertGetId([                                            
                      'user_id' => $UserID,                                                         
                      'message_notification' => 'Your subscription of '.$TitlePlan. " plan will end 1 day from now & will expired on ".$EndDateFormatted. " .",
                      'created_at' => $TODAY             
                  ]); 
            }            

            //check current date hits the actual expiration date.
            if($EndDate<=$CurrentDay){
                
                DB::table('users_subscriptions')
                  ->where('user_id',$UserID)
                  ->update([            
                    'is_subscribe' => 0,                                                 
                    'is_expired' => 1,                                                          
                    'updated_at' => $TODAY
                ]);

                //Dalete All Subscribed Read Books after Expired Subscription
                  DB::table('subscribed_books')
                    ->where('user_id',$UserID)                    
                    ->update([                                  
                      'deleted_at' => $TODAY
                  ]);  

                 $MessageNotificationID = DB::table('message_notification')
                    ->insertGetId([                                            
                      'user_id' => $UserID,                                                         
                      'message_notification' => 'Your '.$TitlePlan. " has expired & ended today ".$CurrentDayFormatted. ".",
                      'created_at' => $TODAY             
                  ]);  
                
            }
         }
       } 
    }

    return 'Success';
  }

  public function checkCustomerSubscriptionIfExist($CustomerID){
      
    $IsExist = false; 
    $CustomerSubscriptionPlanID=0;
    
    $info = DB::table('users_subscriptions')          
        ->whereRaw('user_id=?',[$CustomerID])    
        ->where('is_subscribe',"=",1)                                   
        ->first();

    if(isset($info)>0){
        $IsExist=true;
        $CustomerSubscriptionPlanID=$info->id;
    }else{
        $IsExist=false;
        $CustomerSubscriptionPlanID=0;
    }
    
    return $CustomerSubscriptionPlanID;
  }

  public function cancelSubscriptionPlan($data){

    $UserCustomer= new UserCustomer();

    $TODAY = date("Y-m-d H:i:s");
    $CurrentDay = date("Y-m-d"); 
     
     $TitlePlan="";
    $PlanNoDays=0;
    $SubscriptionPlanID=0;
    $CurrentDayFormatted=date_format(date_create($CurrentDay),'M. j, Y ');

    $UserID=$data['UserID'];
    $Reason=$data['Reason'];

    if($UserID>0){

       $plan_info=$UserCustomer->getCustomerCurrentSubscriptionInfo($UserID);

         if(isset($plan_info)>0){   

            $IsSubcscribe=$plan_info->is_subscribe; 
            $SubscriptionPlanID=$plan_info->plan_id;

            $TitlePlan=$plan_info->title_plan;                   
            $PlanNoDays=$plan_info->no_days;                       

            $EndDate=$plan_info->end_date; 
            $EndDateFormatted=date_format(date_create($EndDate),'M. j, Y ');
          }

            DB::table('users_subscriptions')
              ->where('user_id',$UserID)
              ->update([            
                'is_subscribe' => 0,                                                 
                'is_expired' => 1,                                                          
                'is_cancelled' => 1,                                                          
                'cancel_reason' => $Reason, 
                'remarks' => 'Cancel a '.$PlanNoDays.' days subscription plan with plan ID:'.$SubscriptionPlanID, 
                'updated_at' => $TODAY
            ]); 
            
            //Dalete All Subscribed Read Books after Cancelled Subscription
            DB::table('subscribed_books')
              ->where('user_id',$UserID)              
              ->update([                                  
                'deleted_at' => $TODAY
            ]);  

            //send cancel notif 
           $MessageNotificationID = DB::table('message_notification')
              ->insertGetId([                                            
                'user_id' => $UserID,                                                         
                'message_notification' => "Your current and active subscription plan has successfully cancelled & will end today ".$CurrentDayFormatted. ".",                
                'created_at' => $TODAY             
            ]);  
                
      }

      return 'Success';      

  }

  public function extendSubscriptionPlan($data){
    
  }
  
}