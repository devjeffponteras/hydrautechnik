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

class Book extends Model
{
  
  public function getBookList($data){

    $Status=$data['Status'];
    $SearchText=$data['SearchText'];
    
    $Limit=$data['Limit'];
    $PageNo=$data['PageNo'];

    $query = DB::table('products as prds')
    ->join('product_categories as prod_cat', 'prod_cat.id', '=', 'prds.category_id') 
    
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

          COALESCE(prds.ebook_price,0) as price,   
          COALESCE(prds.ebook_discount_price,0) as discount_price,      
          
          COALESCE(prds.reorder_point,0) as reorder_point,  

          CONCAT(COALESCE(prds.name,''),' ', COALESCE(prds.author,''),'', COALESCE(prds.book_type,'') ,'', COALESCE(prds.subtitle,'')) as search_fields,

          COALESCE((
               SELECT 
                  prod_img.path FROM 
                      product_photos as prod_img                  
                  LEFT JOIN products as prods ON prods.id = prod_img.product_id
                      WHERE prod_img.product_id = prds.id     
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
                  LEFT JOIN promo_products as promo_prods ON promo_prods.promo_id = promo.id  
                       WHERE promo_prods.product_id = prds.id  
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
                  LEFT JOIN promo_products as promo_prods ON promo_prods.promo_id = promo.id  
                       WHERE promo_prods.product_id = prds.id 
                       AND promo.applicable_product_type !='physical'                                              
                       AND promo.status = 'ACTIVE'
                       AND promo_prods.deleted_at IS NULL                     
                  LIMIT 1                                
              )
        ,0) as promo_discount_price,


          COALESCE(prds.status,'') as status          
          
        ");    

      $query->where("prds.file_url","!=",null);    
      $query->where("prds.deleted_at","=",null); 
      $query->where("prds.status","=",'PUBLISHED'); 
        
      if($Status!='' && $Status!='All'){

          if($Status=='Featured'){
            $query->where("prds.is_featured","=",1);    
          }  

          if($Status=='Premium'){
            $query->where("prds.is_premium","=",1);    
            // $query->where("prds.is_free","=",0);        
          } 

          if($Status=='Best Seller'){
             $query->where("prds.is_best_seller","=",1);
             // $query->where("prds.is_free","=",0);        
          } 

          if($Status=='Free'){
             $query->where("prds.is_free","=",1);    
          } 

          if($Status=='New Release'){
            $query->where("prds.created_at","!=",null);    
          }
      }    
                                     
      if($SearchText != ''){
        $arSearchText = explode(" ",$SearchText);
        if(count($arSearchText) > 0){
            for($x=0; $x< count($arSearchText); $x++) {
                $query->whereraw(
                    "CONCAT_WS(' ',
                        COALESCE(prds.name,''),
                        COALESCE(prds.author,''),                        
                        COALESCE(prds.subtitle,''),
                        COALESCE(prds.book_type,''),
                        COALESCE(prod_cat.name,'')
                    ) like '%".str_replace("'", "''", $arSearchText[$x])."%'");
             }
        }
    }

    if($Limit > 0){
      $query->limit($Limit);
      $query->offset(($PageNo-1) * $Limit);
    }

    $query->orderBy("prds.created_at","DESC");    
    $list = $query->get();
                             
     return $list;             
           
  }

  public function getSearchBookList($data){

    $Status=$data['Status'];
    $SearchText=$data['SearchText'];

    $UserID=$data['UserID'];

    $Filter_Sort=$data['Filter_Sort'];
    $Filter_Genre=$data['Filter_Genre'];
    $Filter_Star=$data['Filter_Star'];
    
    $Limit=$data['Limit'];
    $PageNo=$data['PageNo'];

    $query = DB::table('products as prds')
    ->leftjoin('product_categories as prod_cat', 'prod_cat.id', '=', 'prds.category_id') 
    
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

          COALESCE(prds.ebook_price,0) as price,   
          COALESCE(prds.ebook_discount_price,0) as discount_price,      
          
          COALESCE(prds.reorder_point,0) as reorder_point,  

          CONCAT(COALESCE(prds.name,''),' ', COALESCE(prds.author,''),'', COALESCE(prds.book_type,'') ,'', COALESCE(prds.subtitle,'')) as search_fields,

          COALESCE((
               SELECT 
                  prod_img.path FROM 
                      product_photos as prod_img                  
                  LEFT JOIN products as prods ON prods.id = prod_img.product_id
                      WHERE prod_img.product_id = prds.id     
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
                  LEFT JOIN promo_products as promo_prods ON promo_prods.promo_id = promo.id  
                       WHERE promo_prods.product_id = prds.id  
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
                  LEFT JOIN promo_products as promo_prods ON promo_prods.promo_id = promo.id  
                       WHERE promo_prods.product_id = prds.id  
                       AND promo.applicable_product_type !='physical'
                       AND promo.status = 'ACTIVE'                     
                       AND promo_prods.deleted_at IS NULL                     
                  LIMIT 1                                
              )
        ,0) as promo_discount_price,

         COALESCE((
               SELECT 
                  cust_lib.product_id FROM 
                customer_libraries as cust_lib                                    
                      WHERE cust_lib.product_id = prds.id 
                      AND cust_lib.user_id=".$UserID."                      
                  LIMIT 1                                
              )
        ,0) as product_library_exist,


        COALESCE(prds.status,'') as status          
          
        ");    

      $query->where("prds.file_url","!=",null);    
      $query->where("prds.deleted_at","=",null); 
        

      if($Status!='' && $Status!='All'){

          if($Status=='Featured'){
            $query->where("prds.is_featured","=",1);    
          }  

          if($Status=='Premium'){
            $query->where("prds.is_premium","=",1);    
            $query->where("prds.is_free","=",0);        
          } 

          if($Status=='Best Seller'){
            $query->where("prds.is_best_seller","=",1);
            $query->where("prds.is_free","=",0);        
          } 

          if($Status=='Free'){
            $query->where("prds.is_free","=",1);    
          } 

          if($Status=='New Release'){
            $query->where("prds.created_at","!=",null);    
          }
      }    
       
       
      //Filter By Star Rating
      if($Filter_Star!=''){
         if($Filter_Star=='5'){             
           $query->whereRaw("
              COALESCE((
               SELECT AVG(rating)
                  FROM product_reviews as rev
                    WHERE rev.product_id = prds.id     
                    AND rev.status = 1 
                  LIMIT 1                                
                )
              ,0)=5
          ");
        }

        if($Filter_Star=='4'){
                 $query->whereRaw("
              COALESCE((
               SELECT AVG(rating)
                  FROM product_reviews as rev
                    WHERE rev.product_id = prds.id     
                    AND rev.status = 1 
                  LIMIT 1                                
                )
              ,0)=4
          ");
        }

         if($Filter_Star=='3'){
                 $query->whereRaw("
              COALESCE((
               SELECT AVG(rating)
                  FROM product_reviews as rev
                    WHERE rev.product_id = prds.id     
                    AND rev.status = 1 
                  LIMIT 1                                
                )
              ,0)=3
          ");
        }

        if($Filter_Star=='2'){
                 $query->whereRaw("
              COALESCE((
               SELECT AVG(rating)
                  FROM product_reviews as rev
                    WHERE rev.product_id = prds.id     
                    AND rev.status = 1 
                  LIMIT 1                                
                )
              ,0)=2
          ");
        }

        if($Filter_Star=='1'){
                 $query->whereRaw("
              COALESCE((
               SELECT AVG(rating)
                  FROM product_reviews as rev
                    WHERE rev.product_id = prds.id     
                    AND rev.status = 1 
                  LIMIT 1                                
                )
              ,0)=1
          ");
        }
     }  

     //Filter By Genre Category
      if($Filter_Genre!=''){
         $query->where("prds.category_id","=",$Filter_Genre);   
      }
                                  
      if($SearchText != ''){
        $arSearchText = explode(" ",$SearchText);
        if(count($arSearchText) > 0){
            for($x=0; $x< count($arSearchText); $x++) {
                $query->whereraw(
                    "CONCAT_WS(' ',
                        COALESCE(prds.name,''),
                        COALESCE(prds.author,''),                        
                        COALESCE(prds.subtitle,''),
                        COALESCE(prds.book_type,''),
                        COALESCE(prod_cat.name,'')
                    ) like '%".str_replace("'", "''", $arSearchText[$x])."%'");
             }
        }
    }

    if($Limit > 0){
      $query->limit($Limit);
      $query->offset(($PageNo-1) * $Limit);
    }
   
    //Sorting Option
    if($Filter_Sort!=''){
      if($Filter_Sort=='Featured Books'){
        $query->orderBy("prds.is_featured","DESC");   
       }
       if($Filter_Sort=='Price: Low-High'){
        $query->orderBy("prds.ebook_price","ASC");   
       }
        if($Filter_Sort=='Price: High-Low'){
        $query->orderBy("prds.ebook_price","DESC");   
       }
       if($Filter_Sort=='Publication Date'){
        $query->orderBy("prds.publication_date","ASC");   
       }
        if($Filter_Sort=='A-Z Sort'){
        $query->orderBy("prds.name","ASC");   
       }
        if($Filter_Sort=='Z-A Sort'){
        $query->orderBy("prds.name","DESC");   
       }
     }
    else{
        $query->orderBy("prds.created_at","DESC");   
    }  
    


    $list = $query->get();
                             
     return $list;             
           
  }
  
  public function getBookInfoByID($BookID){


    $query = DB::table('products as prds')
    
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

          COALESCE(prds.ebook_price,0) as price,   
          COALESCE(prds.ebook_discount_price,0) as discount_price,      
          
          COALESCE(prds.reorder_point,0) as reorder_point,  

          CONCAT(COALESCE(prds.name,''),' ', COALESCE(prds.author,''),'', COALESCE(prds.book_type,'') ,'', COALESCE(prds.subtitle,'')) as search_fields,

          COALESCE((
               SELECT 
                  prod_img.path FROM 
                      product_photos as prod_img                  
                  LEFT JOIN products as prods ON prods.id = prod_img.product_id
                      WHERE prod_img.product_id = prds.id     
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
                  LEFT JOIN promo_products as promo_prods ON promo_prods.promo_id = promo.id  
                       WHERE promo_prods.product_id = prds.id  
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
                  LEFT JOIN promo_products as promo_prods ON promo_prods.promo_id = promo.id  
                       WHERE promo_prods.product_id = prds.id  
                       AND promo.applicable_product_type !='physical'
                       AND promo.status = 'ACTIVE'                    
                       AND promo_prods.deleted_at IS NULL                     
                  LIMIT 1                                
              )
        ,0) as promo_discount_price,

          COALESCE(prds.status,'') as status          
          
        ");    

      $query->where("prds.id","=",$BookID); 
      $query->where("prds.file_url","!=",null);    
      
      
    $info = $query->first();
                             
     return $info;             
           
  }
  
 // BOOK CATEGORY
  public function getAllBookCatergoryList(){

      $query = DB::table('product_categories as prod_cat')    

       ->selectraw("
          prod_cat.id as category_ID,

          COALESCE(prod_cat.name,'') as CategoryName,
          COALESCE(prod_cat.mobile_file_url,'') as CategoryIcon,
          COALESCE(prod_cat.slug,'') as slug,       

          COALESCE(prod_cat.created_at,'') as created_at,
          COALESCE(prod_cat.status,'') as status
        ");

       $query->where("prod_cat.deleted_at","=",null); 
       $query->where("prod_cat.status","=","PUBLISHED"); 
         
      $list = $query->get();
                             
     return $list;  

  }

 //HEADER CATALOGUE 
  public function getHeaderCatalogueList(){

      $query = DB::table('product_catalog_headers as prod_hdrs_cat')    

       ->selectraw("
          prod_hdrs_cat.id  as prod_hdrs_cat_ID,

          COALESCE(prod_hdrs_cat.name,'') as CatalogueName,          
          COALESCE(prod_hdrs_cat.status,'') as status
        ");

       $query->where("prod_hdrs_cat.status","=",1); 
         
      $list = $query->get();
                             
     return $list;  

  }

 // DETAILS
 public function getDetailsCatalogueList($data){

      $HeaderID=$data['HeaderID'];
      $UserID=$data['UserID'];

      $query = DB::table('product_catalog_details as prod_det_cat')  
      ->join('product_catalog_headers as prod_hdrs_cat', 'prod_hdrs_cat.id', '=', 'prod_det_cat.product_catalog_header_id')   
      ->join('products as prds', 'prds.id', '=', 'prod_det_cat.product_id')   

       ->selectraw("    
          prod_hdrs_cat.name  as header_name,

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

          COALESCE(prds.ebook_price,0) as price,   
          COALESCE(prds.ebook_discount_price,0) as discount_price,      
          
          COALESCE(prds.reorder_point,0) as reorder_point,  

          CONCAT(COALESCE(prds.name,''),' ', COALESCE(prds.author,''),'', COALESCE(prds.book_type,'') ,'', COALESCE(prds.subtitle,'')) as search_fields,

          COALESCE((
               SELECT 
                  prod_img.path FROM 
                      product_photos as prod_img                  
                  LEFT JOIN products as prods ON prods.id = prod_img.product_id
                      WHERE prod_img.product_id = prod_det_cat.product_id   
                      AND prod_img.is_primary = 1    
                  LIMIT 1                                
              )
        ,'') as image_path,

         COALESCE((
             SELECT ROUND(avg(rating))
                  FROM product_reviews as rev
                WHERE rev.product_id = prod_det_cat.product_id   
                AND rev.status = 1 
             LIMIT 1                                
              )
        ,0) as rating,

        COALESCE((
               SELECT 
                  promo.discount FROM 
                        promos as promo                  
                  LEFT JOIN promo_products as promo_prods ON promo_prods.promo_id = promo.id  
                       WHERE promo_prods.product_id = prod_det_cat.product_id  
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
                  LEFT JOIN promo_products as promo_prods ON promo_prods.promo_id = promo.id  
                       WHERE promo_prods.product_id = prod_det_cat.product_id  
                       AND promo.applicable_product_type !='physical'
                       AND promo.status = 'ACTIVE'                     
                       AND promo_prods.deleted_at IS NULL                     
                  LIMIT 1                                
              )
        ,0) as promo_discount_price,

         COALESCE((
               SELECT 
                  cust_lib.product_id FROM 
                customer_libraries as cust_lib                                    
                      WHERE cust_lib.product_id = prds.id 
                      AND cust_lib.user_id=".$UserID."                      
                  LIMIT 1                                
              )
        ,0) as product_library_exist,


          COALESCE(prds.status,'') as status        

        ");

       $query->where("prds.deleted_at","=",null);
       $query->whereRaw("prod_det_cat.product_catalog_header_id =?",[$HeaderID]); 
         
      $list = $query->get();
                             
     return $list;  

  }

}