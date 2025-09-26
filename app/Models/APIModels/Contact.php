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
use App\Models\APIModels\Email;

class Contact extends Model{
  
  public function doSendInquiry($data) {

    $Misc  = New Misc();
    $TODAY = date("Y-m-d H:i:s");
    

    $UserID=$data['UserID'];
    $Subject=$data['Purpose'];
    $FullName=$data['FullName'];
    $EmailAddress=$data['EmailAddress'];
    $MobileNo=$data['MobileNo'];
    $Message=$data['Message'];

    $ImageFileName=$data['ImageFileName'];    
    $FullPathImageFileName='storage/images/'.$data['ImageFileName'];

    //================================================================
    $param["FullName"] = $FullName;
    $param["Subject"] = $Subject;
    $param["EmailAddress"] = $EmailAddress;
    $param["MobileNo"] = $MobileNo;
    $param["Message"] = $Message;
    $param["ImageFileName"] = $ImageFileName;
    $param["FullPathImageFileName"] = $FullPathImageFileName;
        
    $Email = new Email();
    $Email->SendContactUsEmail($param);

    return 'Success';

  }


}