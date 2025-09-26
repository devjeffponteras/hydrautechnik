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
use App\Models\APIModels\Book;
use App\Models\APIModels\UserCustomer;

class Messages extends Model
{
  
  public function getMessageNotificationList($data){
    
    $UserID=$data['UserID'];
    
    $Status=$data['Status'];
    $SearchText=$data['SearchText'];
    
    $Limit=$data['Limit'];
    $PageNo=$data['PageNo'];
    
    $query = DB::table('message_notification as mssg_notif')
      ->join('users as usrs', 'usrs.id', '=', 'mssg_notif.user_id')       
    
       ->selectraw("
          mssg_notif.id as message_ID,
          
          COALESCE(mssg_notif.message_notification,'') as message_notification,        
          DATE_FORMAT(mssg_notif.created_at,'%m/%d/%Y') as create_date_format,
          COALESCE(mssg_notif.is_read,0) as is_read,   
          COALESCE(mssg_notif.created_at,'') as created_at      
          
        ");    

      $query->where("mssg_notif.user_id",'=',$UserID);                                
      $query->where("mssg_notif.deleted_at",'=',null);


    if($Limit > 0){
      $query->limit($Limit);
      $query->offset(($PageNo-1) * $Limit);
    }
   
   $query->orderBy("mssg_notif.is_read","ASC");    
    $query->orderBy("mssg_notif.created_at","DESC");    
    $list = $query->get();
                             
     return $list;             
           
  }

  public function openSetReadMessageNotification($data){

    $MessageID=$data['MessageID'];

       DB::table('message_notification')
          ->whereRaw('id = ?',[$MessageID])
           ->update([
              'is_read' => 1
          ]);

        return "Success";

  }

  public function deleteReadMessageNotification($data){

    $TODAY = date("Y-m-d H:i:s");

    $MessageID=$data['MessageID'];

       DB::table('message_notification')
          ->whereRaw('id = ?',[$MessageID])
           ->update([
              'deleted_at' => $TODAY
          ]);

     return "Success";

  }

}