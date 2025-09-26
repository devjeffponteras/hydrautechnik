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

use App\Models\APIModels\Misc;
use App\Models\APIModels\UserCustomer;

class Cart extends Model
{
  
  public function getCartList($data){
    
    $UserID=$data['UserID'];
    
    $Status=$data['Status'];
    $SearchText=$data['SearchText'];
    
    $Limit=$data['Limit'];
    $PageNo=$data['PageNo'];
    
    $query = DB::table('ecommerce_shopping_cart as cart')
      ->join('products as prds', 'prds.id', '=', 'cart.product_id') 
    
       ->selectraw("
          prds.id as book_ID,

          COALESCE(prds.name,'') as name,
          COALESCE(prds.author,'') as author,
          COALESCE(prds.subtitle,'') as subtitle,
          COALESCE(prds.description,'') as short_description,
          
          COALESCE(prds.slug,'') as slug,
          COALESCE(prds.file_url,'') as file_url,          

          COALESCE(prds.category_id,0) as category_id,
          COALESCE(prds.book_type,'') as book_type,          
      
          COALESCE(prds.sku,'') as sku,          
          COALESCE(prds.size,'') as size,
          COALESCE(prds.weight,'') as weight,
          COALESCE(prds.texture,'') as texture,
          COALESCE(prds.uom,'') as uom,

          COALESCE(prds.is_featured,0) as is_featured,
          COALESCE(prds.is_best_seller,0) as is_best_seller,
          COALESCE(prds.is_free,0) as is_free,
          COALESCE(prds.is_premium,0) as is_premium,
                            
          COALESCE(cart.qty,0) as qty,
          COALESCE(cart.price,0) as price,
          COALESCE(cart.discount_amount,0) as discount_price,    

          COALESCE((
               SELECT 
                  prod_img.path FROM 
                      product_photos as prod_img                  
                  LEFT JOIN products as prods ON prods.id = prod_img.product_id
                      WHERE prod_img.product_id = cart.product_id
                      AND prod_img.is_primary = 1    
                  LIMIT 1                                
              )
        ,'') as image_path,

        COALESCE((
             SELECT ROUND(avg(rating))
                  FROM product_reviews as rev
                WHERE rev.product_id = prds.id     
                AND rev.status = 1 
             LIMIT 1                                
              )
        ,0) as rating,

        COALESCE((
               SELECT 
                  promo.discount FROM 
                        promos as promo                  
                  INNER JOIN promo_products as promo_prods ON promo_prods.promo_id = promo.id  
                       WHERE promo_prods.product_id = cart.product_id        
                       AND promo.applicable_product_type !='physical'                
                       AND promo.status = 'ACTIVE'
                       AND promo_prods.deleted_at IS NULL                     
                  LIMIT 1                                
              )
        ,0) as promo_discount_percent,

        COALESCE((
               SELECT 
                   (prds.ebook_price - (promo.discount/100 * prds.ebook_price)) FROM 
                        promos as promo                  
                  INNER JOIN promo_products as promo_prods ON promo_prods.promo_id = promo.id  
                       WHERE promo_prods.product_id = cart.product_id 
                       AND promo.applicable_product_type !='physical'
                       AND promo.status = 'ACTIVE'                       
                       AND promo_prods.deleted_at IS NULL                     
                  LIMIT 1                                
              )
        ,0) as promo_discount_price,


          COALESCE(prds.status,'') as status          
          
        ");    
        
       $query->where('cart.qty','=',0);  
       $query->where("cart.user_id",'=',$UserID);    
           
   
      // if($Status!='' && $Status!='All'){

      //   if($Status=='Epub'){
      //     $query->where("prds.file_url","!=",null);    
      //   }

      //   if($Status=='Physical'){
      //     $query->where("prds.file_url","==",null);    
      //   }      
      // }    
                                  

      if($SearchText != ''){
        $arSearchText = explode(" ",$SearchText);
        if(count($arSearchText) > 0){
            for($x=0; $x< count($arSearchText); $x++) {
                $query->whereraw(
                    "CONCAT_WS(' ',
                        COALESCE(prds.name,''),
                        COALESCE(prds.author,''),                        
                        COALESCE(prds.subtitle,'')
                    ) like '%".str_replace("'", "''", $arSearchText[$x])."%'");
             }
        }
    }

    if($Limit > 0){
      $query->limit($Limit);
      $query->offset(($PageNo-1) * $Limit);
    }

    $query->orderBy("prds.name","ASC");    
    $list = $query->get();
                             
     return $list;             
           
  }

  public function addToCart($data){

    $Misc  = New Misc();
    $TODAY = date("Y-m-d H:i:s");
    
    $CartID=0;
    
    $UserID=$data['UserID'];
    $ProductID=$data['ProductID'];
    // $ProductQty=$data['ProductQty'];

    $ProductQty=0;    
    $ProductActualDiscountPrice=0;
    
    $ProductPrice=$data['ProductPrice'];
    $ProductDiscount=$data['ProductDiscount'];

    $PromoDiscountPercent=$data['PromoDiscountPercent'];
    $PromoDiscountPrice=$data['PromoDiscountPrice'];

    if($PromoDiscountPercent>0){        
        $ProductActualDiscountPrice=$PromoDiscountPrice;
    }else{        
        $ProductActualDiscountPrice=$ProductDiscount;
    }
    
    if($UserID> 0 && $ProductID>0){
       
      $CartID = DB::table('ecommerce_shopping_cart')
        ->insertGetId([                                            
          'user_id' => $UserID,              
          'product_id' => $ProductID,                                            
          'product_id' => $ProductID, 
          'qty' => $ProductQty,                                            
          'price' => $ProductPrice,                                            
          'discount_amount' => $ProductActualDiscountPrice,
          'created_at' => $TODAY             
        ]);          

    }else{
        return 'Failed';
    }

    return 'Success';

  }

  public function getCustomerCartItemCount($UserID){
        
      $CartItem=0;
      $CartItem = DB::table('ecommerce_shopping_cart')->where('user_id',$UserID)->count();

      return $CartItem;

  }

  public function getCartInfoByUserID($UserID){
            
      $query = DB::table('ecommerce_shopping_cart as cart')
      ->leftjoin('products as prds', 'prds.id', '=', 'cart.product_id')     
       ->selectraw("
          prds.id as book_ID,

          COALESCE(prds.name,'') as name,
          COALESCE(prds.author,'') as author,
          COALESCE(prds.subtitle,'') as subtitle,
          COALESCE(prds.description,'') as short_description,
          
          COALESCE(prds.slug,'') as slug,
          COALESCE(prds.file_url,'') as file_url,          

          COALESCE(prds.category_id,0) as category_id,
          COALESCE(prds.book_type,'') as book_type,          
      
          COALESCE(prds.sku,'') as sku,          
          COALESCE(prds.size,'') as size,
          COALESCE(prds.weight,'') as weight,
          COALESCE(prds.texture,'') as texture,
          COALESCE(prds.uom,'') as uom,

          COALESCE(prds.is_featured,0) as is_featured,
          COALESCE(prds.is_best_seller,0) as is_best_seller,
          COALESCE(prds.is_free,0) as is_free,
          COALESCE(prds.is_premium,0) as is_premium,
   
          COALESCE(prds.status,'') as status,          

          COALESCE(cart.user_id,0) as user_id,
          COALESCE(cart.product_id,0) as product_id,
          COALESCE(cart.qty,0) as qty,

          COALESCE(cart.price,0) as price,
          COALESCE(cart.discount_amount,0) as discount_amount    
          
        "); 
             
      $query->where('cart.qty','=',0); 
      $query->whereRaw('cart.user_id=?',[$UserID]);  
      

     $list = $query->get();                            
     return $list;    
  }
  
  public function removeToCart($data){

    $Misc  = New Misc();
    $TODAY = date("Y-m-d H:i:s");
    
    $CartID=0;
    
    $UserID=$data['UserID'];
    $ProductID=$data['ProductID'];
    
    if($UserID> 0 && $ProductID>0){
       
        DB::table('ecommerce_shopping_cart')
          ->where('user_id', $UserID)
          ->where('product_id', $ProductID)
          ->delete();       

    }else{
        return 'Failed';
    }

    return 'Success';

  }
  
  public function checkProductsIfExist($ProductID,$CustomerID){
      
    $IsExist = false; 
    
    $list = DB::table('ecommerce_shopping_cart')          
        ->whereRaw('user_id=?',[$CustomerID])    
        ->whereRaw('product_id=?',[$ProductID])                                    
        ->get();

    if(count($list)>0){
        $IsExist=true;
    }else{
        $IsExist=false;
    }
    
    return $IsExist;
  }

  

}