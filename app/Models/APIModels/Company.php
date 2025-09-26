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

class Company extends Model
{

  public function getCompanyAboutUs(){

   $query = DB::table('pages as pg')
     
       ->selectraw("
          pg.id as Page_ID,
          COALESCE(pg.contents,'') as about_us                        
        ");

       $query->where("pg.status","=",'PUBLISHED');  
       $query->where("pg.label","=",'About Us');           
       $query->where("pg.deleted_at","=",null);    
      
    $list = $query->first();                          
   return $list;  

    
  }

  public function getCompanyFAQ($data){

     $query = DB::table('pages as pg')
         
       ->selectraw("
          pg.id as Page_ID,
          COALESCE(pg.contents,'') as faq                        
        ");    
       
       $query->where("pg.status","=",'PUBLISHED');  
       $query->where("pg.label","=",'FAQs');           
       $query->where("pg.deleted_at","=",null);  
     
    $list = $query->first();                           
     return $list;    
    
  }

  public function getCompanyPrivacyPolicy($data){

     $query = DB::table('pages as pg')
         
       ->selectraw("
          pg.id as Page_ID,
          COALESCE(pg.contents,'') as privacy_policy                        
        ");    
       
       $query->where("pg.status","=",'PUBLISHED');  
       $query->where("pg.label","=",'Privacy Policy');           
       $query->where("pg.deleted_at","=",null);  
     
    $list = $query->first();                           
     return $list;    
    
  }


  public function getCompanyTermsCondition($data){

     $query = DB::table('pages as pg')
         
       ->selectraw("
          pg.id as Page_ID,
          COALESCE(pg.contents,'') as terms_condition                        
        ");    
       
       $query->where("pg.status","=",'PUBLISHED');  
       $query->where("pg.label","=",'Terms of Use Agreement');           
       $query->where("pg.deleted_at","=",null);  
     
    $list = $query->first();                           
     return $list;    
    
  }

}