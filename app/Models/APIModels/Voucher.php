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

class Voucher extends Model
{
  
  public function getVoucherList($data){

    $Status=$data['Status'];
    $SearchText=$data['SearchText'];
    
    $Limit=$data['Limit'];
    $PageNo=$data['PageNo'];

    $query = DB::table('coupons as coup')  
       ->selectraw("
          coup.id as coupon_ID,

          COALESCE(coup.coupon_code,'') as coupon_code,
          COALESCE(coup.name,'') as coupon_name,
          
          COALESCE(coup.customer_limit,0) as customer_limit,

          COALESCE(coup.description,'') as coupon_description,
          COALESCE(coup.terms_and_conditions,'') as terms_and_conditions,
          
          COALESCE(coup.customer_scope,'') as customer_scope,
          COALESCE(coup.scope_customer_id,'') as scope_customer_id,

          COALESCE(coup.percentage,0) as percentage,
          COALESCE(coup.purchase_amount,0) as min_purchsae_amount,
          COALESCE(coup.purchase_qty,0) as purchase_qty,
          COALESCE(coup.amount,0) as discount_amount,        
          
          DATE_FORMAT(coup.start_date,'%M %d,%Y') as start_date_format,
          DATE_FORMAT(coup.end_date,'%M %d,%Y') as end_date_format,
             
          COALESCE(coup.status,'') as status          
          
        ")    
 
      ->where("coup.status","=",'ACTIVE')
      ->where("coup.location","=",null)                     
      ->where("coup.deleted_at","=",null)
      ->where("coup.activation_type","!=",'manual')
      ->where("coup.applicable_product_type","!=",'physical');

                                 
      if($SearchText != ''){
        $arSearchText = explode(" ",$SearchText);
        if(count($arSearchText) > 0){
            for($x=0; $x< count($arSearchText); $x++) {
                $query->whereraw(
                    "CONCAT_WS(' ',                          
                        COALESCE(coup.name,''),
                        COALESCE(coup.coupon_code,''),
                        COALESCE(coup.description,'')
                    ) like '%".str_replace("'", "''", $arSearchText[$x])."%'");
             }
        }
    }

    if($Limit > 0){
      $query->limit($Limit);
      $query->offset(($PageNo-1) * $Limit);
    }

    $query->orderBy("coup.name","ASC");    
    $list = $query->get();
                             
     return $list;             
           
  }

  public function getVoucherInfoByCode($Voucher_Code){

     $query = DB::table('coupons as coup')  
       ->selectraw("
          coup.id as coupon_ID,

          COALESCE(coup.coupon_code,'') as coupon_code,
          COALESCE(coup.name,'') as coupon_name,

          COALESCE(coup.description,'') as coupon_description,
          COALESCE(coup.terms_and_conditions,'') as terms_and_conditions,
          
          COALESCE(coup.activation_type,'') as activation_type,
          COALESCE(coup.applicable_product_type,'') as applicable_product_type,

          COALESCE(coup.customer_scope,'') as customer_scope,
          COALESCE(coup.scope_customer_id,'') as scope_customer_id,
          
          COALESCE(coup.percentage,0) as percentage,
          COALESCE(coup.purchase_amount,0) as min_purchsae_amount,
          COALESCE(coup.purchase_qty,0) as purchase_qty,
          COALESCE(coup.amount,0) as discount_amount,

          COALESCE(coup.scope_customer_id,'') as scope_customer_id,

          DATE_FORMAT(coup.start_date,'%M %d,%Y') as start_date_format,
          DATE_FORMAT(coup.end_date,'%M %d,%Y') as end_date_format,
             
          COALESCE(coup.status,'') as status          
          
        ");    
      
      $query->whereRaw('coup.coupon_code =?',[$Voucher_Code]);     
      $query->where("coup.status","=",'ACTIVE'); 

      $info = $query->first();

     return $info;      

  }
  
   public function getVoucherInfoByIDwithNoUsage($VoucherID,$NoUsage){

     $query = DB::table('coupons as coup')  
       ->selectraw("
          coup.id as coupon_ID,

          COALESCE(coup.coupon_code,'') as coupon_code,
          COALESCE(coup.name,'') as coupon_name,

          COALESCE(coup.description,'') as coupon_description,
          COALESCE(coup.terms_and_conditions,'') as terms_and_conditions,
          
          COALESCE(coup.activation_type,'') as activation_type,
          COALESCE(coup.applicable_product_type,'') as applicable_product_type,

          COALESCE(coup.customer_scope,'') as customer_scope,
          COALESCE(coup.scope_customer_id,'') as scope_customer_id,
          
          COALESCE(coup.percentage,0) as percentage,
          COALESCE(coup.purchase_amount,0) as min_purchsae_amount,
          COALESCE(coup.purchase_qty,0) as purchase_qty,
          COALESCE(coup.amount,0) as discount_amount,

          COALESCE(coup.scope_customer_id,'') as scope_customer_id,

          DATE_FORMAT(coup.start_date,'%M %d,%Y') as start_date_format,
          DATE_FORMAT(coup.end_date,'%M %d,%Y') as end_date_format,
             
          COALESCE(coup.status,'') as status          
          
        ");    
      
      $query->where('coup.id','=',$VoucherID);     
      $query->where('coup.customer_limit','>',$NoUsage); 
      $query->where("coup.status","=",'ACTIVE'); 

      $info = $query->first();

     return $info;      

  }
  
}