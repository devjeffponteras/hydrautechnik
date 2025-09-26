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

class BannerAds extends Model
{
  
  public function getHomeSliderBannerList($data){

     $query = DB::table('mobile_banners as mob_ban')
     ->join('mobile_albums as  mob_alb', 'mob_alb.id', '=', 'mob_ban.album_id') 
    
       ->selectraw("
          mob_ban.id as banner_ID,
          COALESCE(mob_ban.album_id,'') as album_id,

          COALESCE(mob_ban.title,'') as title,
          COALESCE(mob_ban.description,'') as description,
          COALESCE(mob_ban.alt,'') as alt,
          COALESCE(mob_ban.image_path,'') as image_path,
          
          COALESCE(mob_ban.button_text,'') as button_text,
          COALESCE(mob_ban.url,'') as url_link,          

          COALESCE(mob_ban.order,0) as order_sequence
                        
        ");    
       
       $query->where("mob_alb.status","=",1);  
       $query->where("mob_alb.type","=",'sub_banner');    
       $query->where("mob_alb.banner_type","=",'image');   
       $query->where("mob_ban.deleted_at","=",null);  

     
    $query->orderBy("mob_ban.order","ASC");     
    $list = $query->get();
                             
     return $list;    
    
  }

  public function getPopUpBannerList($data){
   
   $query = DB::table('mobile_banners as mob_ban')
     ->join('mobile_albums as  mob_alb', 'mob_alb.id', '=', 'mob_ban.album_id') 
    
       ->selectraw("
          mob_ban.id as banner_ID,
          COALESCE(mob_ban.album_id,'') as album_id,

          COALESCE(mob_ban.title,'') as title,
          COALESCE(mob_ban.description,'') as description,
          COALESCE(mob_ban.alt,'') as alt,
          COALESCE(mob_ban.image_path,'') as image_path,
          
          COALESCE(mob_ban.button_text,'') as button_text,
          COALESCE(mob_ban.url,'') as url_link,          

          COALESCE(mob_ban.order,0) as order_sequence
                        
        ");    
              
       $query->where("mob_alb.status","=",1);         
       $query->where("mob_alb.type","=",'main_banner');    
       $query->where("mob_alb.banner_type","=",'image'); 
       $query->where("mob_ban.album_id","=",1); 
       $query->where("mob_ban.deleted_at","=",null); 
             
      $query->orderBy("mob_ban.order","ASC");          
     $list = $query->get();                           
     return $list;     
    
  }


}