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

class Library extends Model
{
  
  public function getLibraryList($data){
    
    $UserID=$data['UserID'];
    
    $Status=$data['Status'];
    $SearchText=$data['SearchText'];
    
    $Limit=$data['Limit'];
    $PageNo=$data['PageNo'];
    
    $query = DB::table('customer_libraries as lib')
      ->join('products as prds', 'prds.id', '=', 'lib.product_id') 
      
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
                      WHERE prod_img.product_id = lib.product_id
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
                       WHERE promo_prods.product_id = lib.product_id 
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
                       WHERE promo_prods.product_id = lib.product_id 
                       AND promo.applicable_product_type !='physical'                   
                       AND promo.status = 'ACTIVE'
                       AND promo_prods.deleted_at IS NULL                     
                  LIMIT 1                                
              )
        ,0) as promo_discount_price,

        COALESCE((
               SELECT 
                   bkmrk.chapter_page_no FROM 
                        book_marks as bkmrk                  
                    INNER JOIN products as prods ON prods.id = bkmrk.product_id
                         WHERE bkmrk.product_id = prds.id                            
                  LIMIT 1                                
              )
        ,0) as chapter_page_no,

          COALESCE(prds.status,'') as status          
          
        ");    

    $query->where("lib.user_id",'=',$UserID);    
   
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

   public function getSubscribedReadBooksList($data){
    
    $UserID=$data['UserID'];
    
    $Status=$data['Status'];
    $SearchText=$data['SearchText'];
    
    $Limit=$data['Limit'];
    $PageNo=$data['PageNo'];
    
    $query = DB::table('subscribed_books as rbooks')
      ->join('products as prds', 'prds.id', '=', 'rbooks.product_id')        
    
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
                      WHERE prod_img.product_id = rbooks.product_id
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
                       WHERE promo_prods.product_id = rbooks.product_id  
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
                       WHERE promo_prods.product_id = rbooks.product_id     
                       AND promo.applicable_product_type !='physical'                   
                       AND promo.status = 'ACTIVE'
                       AND promo_prods.deleted_at IS NULL                     
                  LIMIT 1                                
              )
        ,0) as promo_discount_price,


        COALESCE((
               SELECT 
                   bkmrk.chapter_page_no FROM 
                        book_marks as bkmrk                  
                    INNER JOIN products as prods ON prods.id = bkmrk.product_id
                         WHERE bkmrk.product_id = prds.id                            
                  LIMIT 1                                
              )
        ,0) as chapter_page_no,


          COALESCE(prds.status,'') as status          
          
        ");    

            
    $query->where("prds.is_free",'=',0);        
    $query->where("prds.is_premium",'=',0);        
    
    $query->where("rbooks.user_id",'=',$UserID);        
    $query->where("rbooks.deleted_at",'=',null);   

    $query->where("rbooks.is_read",'=',1);
  
                                  
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

 public function checkProductsIfExistInLibrary($ProductID,$CustomerID){
      
    $IsExist = false; 
    
    $list = DB::table('customer_libraries')          
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

   public function checkProductsIfExistInSubscribeLibrary($ProductID,$CustomerID){
      
    $IsExist = false; 
    
    $list = DB::table('subscribed_books')          
        ->whereRaw('user_id=?',[$CustomerID])    
        ->whereRaw('product_id=?',[$ProductID])                                    
        ->where('is_read','=',1)
        ->where('deleted_at','=',null)
        ->get();

    if(count($list)>0){
        $IsExist=true;
    }else{
        $IsExist=false;
    }
    
    return $IsExist;
  }

    public function checkProductsIfExistInSubscribeDownloadLibrary($ProductID,$CustomerID){
      
    $IsExist = false; 
    
    $list = DB::table('subscribed_books')          
        ->whereRaw('user_id=?',[$CustomerID])    
        ->whereRaw('product_id=?',[$ProductID])                                    
        ->where('is_downloaded','=',1)
        ->where('deleted_at','=',null)
        ->get();

    if(count($list)>0){
        $IsExist=true;
    }else{
        $IsExist=false;
    }
    
    return $IsExist;
  }

  public function getSubscribedDownloadedBooksList($data){
    
    $UserID=$data['UserID'];
        
    $query = DB::table('subscribed_books as rbooks')
      ->join('products as prds', 'prds.id', '=', 'rbooks.product_id') 
    
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
                      WHERE prod_img.product_id = rbooks.product_id
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
                       WHERE promo_prods.product_id = rbooks.product_id  
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
                       WHERE promo_prods.product_id = rbooks.product_id     
                       AND promo.applicable_product_type !='physical'                   
                       AND promo.status = 'ACTIVE'
                       AND promo_prods.deleted_at IS NULL                     
                  LIMIT 1                                
              )
        ,0) as promo_discount_price,

          COALESCE(prds.status,'') as status          
          
        ");    

    $query->where("rbooks.user_id",'=',$UserID);    
    $query->where("rbooks.is_downloaded",'=',1);        
    $query->where("rbooks.deleted_at",'=',null);   
   

    $list = $query->get();
                             
     return $list;             
           
  }

  public function saveReadSubscribedBooks($data){

    $TODAY = date("Y-m-d H:i:s");
    
    $UserID=$data['UserID'];    
    $ProductID=$data['ProductID'];    
    $IsRead=$data['IsRead'];    

    $info = DB::table('subscribed_books')          
        ->whereRaw('user_id=?',[$UserID])    
        ->whereRaw('product_id=?',[$ProductID])          
        ->first();

    if(isset($info)<=0){

            $ReadBookID = DB::table('subscribed_books')
            ->insertGetId([                                            
              'user_id' => $UserID,              
              'product_id' => $ProductID,                                            
              'is_read' => $IsRead,                                                      
              'is_downloaded' => 0,                                                      
              'created_at' => $TODAY             
            ]);
    } 
            
        
  }

  public function checkProductsIfExistInSubscribedBooks($ProductID,$CustomerID){
      
    $IsExist = false; 
    
    $list = DB::table('subscribed_books')          
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

  public function saveDownloadSubscribedBooks($data){

    $TODAY = date("Y-m-d H:i:s");
    $PaymentDate = date("Y-m-d");

    $UserID=$data['UserID'];    
    $ProductID=$data['ProductID'];    
    $IsSubscribe=$data['IsSubscribed'];    
    $IsDownloaded=$data['IsDownloaded'];


    DB::table('subscribed_books')
      ->where('user_id',$UserID)                  
      ->where('product_id',$ProductID)
      ->update([                                  
            'is_downloaded' => $IsDownloaded,
            'updated_at' => $TODAY,            
       ]);  

  }

  public function checkProductsIfExistInDownloadSubscribedBooks($ProductID,$CustomerID){
      
    $IsExist = false; 
    
    $list = DB::table('subscribed_books')          
        ->whereRaw('user_id=?',[$CustomerID])    
        ->whereRaw('product_id=?',[$ProductID])  
        ->where('is_downloaded',"=",1)                                      
        ->get();

    if(count($list)>0){
        $IsExist=true;
    }else{
        $IsExist=false;
    }
    
    return $IsExist;
  }

    public function saveBookMarks($data){

    $TODAY = date("Y-m-d H:i:s");
    
    
    $UserID=$data['UserID'];    
    $ProductID=$data['ProductID'];    
    $PageNo=$data['PageNo'];   

    $info = DB::table('book_marks')          
        ->whereRaw('customer_id=?',[$UserID])    
        ->whereRaw('product_id=?',[$ProductID])          
        ->first();

    if(isset($info)>0){
       
       if($PageNo!=null){

          DB::table('book_marks')
            ->where('customer_id',$UserID)
            ->where('product_id',$ProductID)
            ->update([                                                       
              'chapter_page_no' => $PageNo
           ]);   

       }
        
                    
    }else{

     $BookMarkID = DB::table('book_marks')
        ->insertGetId([                                            
          'customer_id' => $UserID,              
          'product_id' => $ProductID,                                            
          'chapter_page_no' => $PageNo,                                                                                                                  
          'created_at' => $TODAY             
        ]);

    }
    
  }

  public function updateBookMarks($data){

    $TODAY = date("Y-m-d H:i:s");    
    
    $UserID=$data['UserID'];    
    $ProductID=$data['ProductID'];    
    $PageNo=$data['PageNo'];   

    $info = DB::table('book_marks')          
        ->whereRaw('customer_id=?',[$UserID])    
        ->whereRaw('product_id=?',[$ProductID])          
        ->first();

    if(isset($info)>0){
       
       if($PageNo!=null){
       
       //remove no customer reference
        DB::table('book_marks')->where('customer_id',"=",0)->delete();  
        // delete existing customer book
        DB::table('book_marks')->where('customer_id', $UserID)->where('product_id', $ProductID)->delete();  
        // save new book
        $BookMarkID = DB::table('book_marks')
            ->insertGetId([                                            
              'customer_id' => $UserID,              
              'product_id' => $ProductID,                                            
              'chapter_page_no' => $PageNo,                                                                                                                  
              'created_at' => $TODAY             
            ]);
            
          // DB::table('book_marks')
          //   ->where('customer_id',$UserID)
          //   ->where('product_id',$ProductID)
          //   ->update([                                                       
          //     'chapter_page_no' => $PageNo
          //  ]);   
       }
        
                    
    }else{

     $BookMarkID = DB::table('book_marks')
        ->insertGetId([                                            
          'customer_id' => $UserID,              
          'product_id' => $ProductID,                                            
          'chapter_page_no' => $PageNo,                                                                                                                  
          'created_at' => $TODAY             
        ]);

    }
                   
  }

   public function getPageChapterBookMark($ProductID,$CustomerID){
      
    $ChapterPageNo = 0; 
    
    $ChapterPageNo = DB::table('book_marks')          
        ->whereRaw('customer_id=?',[$CustomerID])    
        ->whereRaw('product_id=?',[$ProductID])                                          
        ->value('chapter_page_no');

    return $ChapterPageNo;
        

    }


}