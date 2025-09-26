<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

use URL;
use Mail;
use Session;
use Hash;
use View;
use Image;
use DB;
use Excel;
use PDF;

use App\Models\APIModels\Misc;
use App\Models\APIModels\Book;
use App\Models\APIModels\City;
use App\Models\APIModels\Cart;
use App\Models\APIModels\Email;
use App\Models\APIModels\Order;
use App\Models\APIModels\Review;
use App\Models\APIModels\Company;
use App\Models\APIModels\EWallet;
use App\Models\APIModels\Contact;
use App\Models\APIModels\Library;
use App\Models\APIModels\Voucher;
use App\Models\APIModels\Messages;
use App\Models\APIModels\BannerAds;
use App\Models\APIModels\Favorites;
use App\Models\APIModels\Subscription;
use App\Models\APIModels\UserCustomer;

class ApiController extends Controller {

// CUSTOMER=======================================
  public function doLogout(){
     return response()->json($RetVal);
  }

// CUSTOMER LOGIN==================================
  public function doCheckLogin(Request $request){
   
    $Misc = new Misc();
    $UserCustomer = new UserCustomer();
    $Subscription = new Subscription();

    $response = "Failed";
    $responseMessage = "";
    
    $data['UserID']=0;
 
   
    $data['EmailAddress'] =  $request->post('EmailAddress');
    $data['Password'] =  $request->post('Password');

     if(empty($data['EmailAddress'])){
      $ResponseMessage = 'Email address is required.';
      return response()->json([
          'response' => 'Failed',
          'message' => $ResponseMessage,
      ]);
    }

    if(empty($data['Password'])){
      $ResponseMessage ='Password is required.';
      return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }

    if($Misc->IsValidEmail($data['EmailAddress'])==false){
      $ResponseMessage ='Enter your valid email address.';
      return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }
      
     $getUserID=0;
     $getPassword=''; 
     $chkIsActive=0;
     $info=$UserCustomer->getUserLoginPassword($data['EmailAddress']);
     
     $Subcriber_Status=0;
     if(isset($info)>0){         

         $getUserID=$info->user_ID;
         $getPassword= $info->password;
         $chkIsActive= $info->is_active;
         
         //check bycrypt
         if (Hash::check($data['Password'], $getPassword)){
          
                if($chkIsActive==1){
                  
                  $data['UserID']=$getUserID;
                  $Subscription->checkSubscriptionStatus($data);

                    return response()->json([
                      'data' => $info,                      
                      'response' => 'Success',
                      'message' => "Password is okay",
                     ]);  

                }else{

                    return response()->json([
                      'data' => null,                      
                      'response' => 'Failed',
                      'message' => "Your account is deactivated & in-active. If you wish to reactivate your account, please contact our admin and staff via email.",                      
                   ]); 
                }
              
             
         }else{

            return response()->json([
                    'data' => null,                    
                    'response' => 'Failed',
                    'message' => "Invalid login credentials.",
                 ]); 
         }
     }else{

          return response()->json([
                'data' => null,                
                'response' => 'Failed',                
                'message' => "Invalid login credentials.",
             ]); 
     }
     
  }
 
  // REGISTER NEW CUSTOMER======================================
  public function doRegisterCustomer(Request $request){

    $Misc = new Misc();
    $UserCustomer = new UserCustomer();

    $response = "Failed";
    $responseMessage = "";

    $data['UserID']=0;
    $data['FirstName'] = $request->post('FirstName');
    $data['LastName'] = $request->post('LastName');
    $data['FullName'] =  $data['FirstName'] .' '. $data['LastName']; 
    
    $data['UserName'] = '';
    $data['EmailAddress'] = $request->post('EmailAddress');
    $data['MobileNo'] = $request->post('MobileNo');

    $data['Password'] = $request->post('Password');
    $data['RepeatPassword'] = $request->post('RepeatPassword');
    
    if(empty($data['FirstName'])){
      $ResponseMessage = 'First name is required.';
       return response()->json([
           'response' => 'Failed',
           'message' => $ResponseMessage,
       ]);
    }

    if(empty($data['LastName'])){
      $ResponseMessage = 'Last name name is required.';
       return response()->json([
           'response' => 'Failed',
           'message' => $ResponseMessage,
       ]);
    }
    
    if(empty($data['EmailAddress'])){
      $ResponseMessage ='Email address is required.';    
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }

    if(empty($data['MobileNo'])){
      $ResponseMessage ='Mobile number is required.';
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }

    if(empty($data['Password'])){
      $ResponseMessage ='Password is required.';
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }

    if(empty($data['RepeatPassword'])){
      $ResponseMessage ='Confirm password is required.';
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }

    if($Misc->IsValidEmail($data['EmailAddress'])==false){
      $ResponseMessage ='Enter current & valid email address.';
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }

    if(!empty($data['Password']) &&  strlen($data['Password'])<6){
      $ResponseMessage ='Password must be atleast 6 character or more.';
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }

    if(!empty($data['RepeatPassword']) &&  strlen($data['RepeatPassword'])<6){
      $ResponseMessage ='Confirm password must be atleast 6 character or more.';
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }

    if($data['Password']!=$data['RepeatPassword']){
      $ResponseMessage ='Password & Confirm password do not match.';
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }
     

    if(!empty($data['EmailAddress']) && $Misc->isDataExist('users', 'id', $data['UserID'], "email", $data['EmailAddress'])){
        $ResponseMessage = 'Email is already registered. Login your account or use forgot password.';
         return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }

    if(!empty($data['MobileNo']) && $Misc->isDataExist('users', 'id', $data['UserID'], "mobile", $data['MobileNo'])){
        $ResponseMessage = 'Mobile number is already registered.';
         return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }          
      
    $retVal=$UserCustomer->doRegisterCustomer($data);
     return response()->json([
      'response' => 'Success',
      'message' => "You have successfully registered. Login to your account now.",
    ]);                     
           
  }

  //CUSTOMER FORGOT PASSWORD=================================  
  public function doForgotPassword(Request $request){

    $Misc = new Misc();
    $UserCustomer = new UserCustomer();

    $response = "Failed";
    $responseMessage = "";

    
    $data['EmailAddress'] = $request->post('EmailAddress');

    if(empty($data['EmailAddress'])){
      $ResponseMessage ='Email address is required.';
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }

    if($Misc->IsValidEmail($data['EmailAddress'])==false){
      $ResponseMessage ='Enter your valid email address.';
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }
        
    $CustomerID=0;
    $response=$UserCustomer->doForgotPassword($data);
    if($response=='Success'){      
        return response()->json([                  
         'response' => $response,
         'message' => "Your new & temporary password has sent to your email.",
       ]);    

    }else{
        return response()->json([
          'response' => $response,
          'message' => "Email address does not exist.",
       ]); 
    } 
  }

  //CUSTOMER CHANGE PASSWORD=================================  
  public function doChangePassword(Request $request){

    $Misc = new Misc();
    $UserCustomer = new UserCustomer();

    $response = "Failed";
    $responseMessage = "";
  
    $data['UserID'] = $request->post('UserID');
    
    $data['OldPassword'] = $request->post('OldPassword');
    $data['NewPassword'] = $request->post('NewPassword');
    $data['ConfirmNewPassword'] = $request->post('ConfirmNewPassword');

    if(empty($data['OldPassword'])){
      $ResponseMessage ='Old password is required.';
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }

    if(empty($data['NewPassword'])){
      $ResponseMessage ='New password is required.';
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }

       if(empty($data['ConfirmNewPassword'])){
      $ResponseMessage ='Confirm new password is required.';
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }

    if(!empty($data['NewPassword']) &&  strlen($data['NewPassword'])<6){
      $ResponseMessage ='New password must be atleast 6 character or more.';
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }

    if(!empty($data['ConfirmNewPassword']) &&  strlen($data['ConfirmNewPassword'])<6){
      $ResponseMessage ='Confirm new password must be atleast 6 character or more.';
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }

    if($data['NewPassword']!=$data['ConfirmNewPassword']){
      $ResponseMessage ='New password & Confirm new password do not match.';
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }
    
    //Checking of password
    $getEmailAddress=''; 
    $info=$UserCustomer->getCustomerInformation($data);
    if(isset($info)>0){

      $getEmailAddress= $info->emailaddress;     
       $getPassword='';                
       $info=$UserCustomer->getUserLoginPassword($getEmailAddress);

      if(isset($info)>0){                               
         $getPassword= $info->password;
         //check bycrypt

         if (Hash::check($data['OldPassword'], $getPassword)){

            $response=$UserCustomer->doUserChangePassword($data);            
               if($response=='Success'){      
                return response()->json([                  
                'response' => $response,
                'message' => "You have successfully changed your password.",
                 ]);    
              }                
         }else{
            return response()->json([
                    'data' => null,
                    'response' => 'Failed',
                    'message' => "Invalid old password.",
                 ]); 
         }
     }else{
          return response()->json([
                'data' => null,
                'response' => 'Failed',
                'message' => "Invalid old password.",
             ]); 
     }

   }else{
      return response()->json([
          'data' => null,
          'response' => 'Failed',
          'message' => "Invalid old password.",
      ]);    
   }
 }

 //CUSTOMER DE-ACTIVATE ACCOUNT=================================  
  public function doDeactivateMyAccount(Request $request){

    $Misc = new Misc();
    $UserCustomer = new UserCustomer();

    $response = "Failed";
    $responseMessage = "";
  
    $data['UserID'] = $request->post('UserID');
    
    $data['Password'] = $request->post('Password');
    $data['ConfirmNewPassword'] = $request->post('ConfirmNewPassword');


    if(empty($data['Password'])){
      $ResponseMessage ='Password is required.';
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }

       if(empty($data['ConfirmNewPassword'])){
      $ResponseMessage ='Confirm new password is required.';
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }

    if(!empty($data['Password']) &&  strlen($data['Password'])<6){
      $ResponseMessage ='Password must be atleast 6 character or more.';
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }

    if(!empty($data['ConfirmNewPassword']) &&  strlen($data['ConfirmNewPassword'])<6){
      $ResponseMessage ='Confirm password must be atleast 6 character or more.';
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }

    if($data['Password']!=$data['ConfirmNewPassword']){
      $ResponseMessage ='Password & Confirm new password do not match.';
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }
    
    //Deactivation Process
     $getEmailAddress=''; 
    $info=$UserCustomer->getCustomerInformation($data);
    if(isset($info)>0){
        
      $getEmailAddress= $info->emailaddress;     
       $getPassword='';                
       $info=$UserCustomer->getUserLoginPassword($getEmailAddress);

      if(isset($info)>0){                               
         $getPassword= $info->password;

         //check bycrypt
         if (Hash::check($data['Password'], $getPassword)){

            $response=$UserCustomer->doDeactivateMyAccount($data);            
            if($response=='Success'){      
                return response()->json([                  
                'response' => $response,
                'message' => "You have successfully deactivated your account. If you wish to reactivate your account, please contact our admin and staff via email.",
                 ]);    
              }                
            }else{
                  return response()->json([
                    'data' => null,
                    'response' => 'Failed',
                    'message' => "Invalid password.",
                 ]); 
         }
     }else{
          return response()->json([
                'data' => null,
                'response' => 'Failed',
                'message' => "Invalid password.",
             ]); 
     } 
   
   }else{
      return response()->json([
          'data' => null,
          'response' => 'Failed',
          'message' => "Invalid old password.",
      ]);    
   }
 }

 //CUSTOMER VERIFY ACCOUNT=================================  
  public function doVerifyAccount(Request $request){

    $Misc = new Misc();
    $UserCustomer = new UserCustomer();

    $response = "Failed";
    $responseMessage = "";

    $data['UserID'] = $request->post('UserID');
    $data['VerificationCode'] = $request->post('VerificationCode');    

    if(empty($data['VerificationCode'])){
      $ResponseMessage ='Enter your 4 digit verification code.';
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }

    if(!empty($data['VerificationCode']) &&  strlen($data['VerificationCode'])<4){
      $ResponseMessage ='Enter your 4 digit verification code.';
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }
            
    $response=$UserCustomer->doVerifyAccount($data);
    if($response=='Success'){      
        return response()->json([                  
         'response' => $response,
         'message' => "You have successfully verified your account.",
       ]);    

    }else{
        return response()->json([
          'response' => $response,
          'message' => "Invalid verification code.",
       ]); 
    } 
  }

  //RESEND VERIFICATION CODE=================================  
  public function doResendVerificationCode(Request $request){

    $Misc = new Misc();
    $UserCustomer = new UserCustomer();
    
    $VerficationCode='';
    $response = "Failed";
    $responseMessage = "";

    $data['UserID'] = $request->post('UserID');
    $data['EmailAddress'] = $request->post('EmailAddress');

    $response=$UserCustomer->doResendVerificationCode($data); 
    
    if($response=='Success'){   
       
      $VerficationCode=$UserCustomer->getVerificationCodeByID($data);
        return response()->json([                  
         'response' => $response,
         'code' => $VerficationCode,
         'message' => "New verfication code will be send to your email in a while.",
       ]);    

    }else{
        return response()->json([
          'response' =>$response,
          'code' => '',
          'message' => "Something went wrong while sending new verification code.",
       ]); 
    } 
  }

 //CUSTOMER UPDATE CITY ADDRESS LOCATION=================================  
  public function updateCityAddressLocation(Request $request){

    $Misc = new Misc();
    $UserCustomer = new UserCustomer();

    $response = "Failed";
    $responseMessage = "";

    

    $data['UserID'] = $request->post('UserID');
    $data['CityName'] = $request->post('CityName');    

    if(empty($data['CityName'])){
      $ResponseMessage ='Select city from the list.';
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }
            
    $response=$UserCustomer->updateCityAddressLocation($data);
    if($response=='Success'){      
        return response()->json([                  
         'response' => 'Success',
         'message' => "You have successfully set your city location.",
       ]);    

    }else{
        return response()->json([
          'response' =>'Failed',
          'message' => "Something went wrong while setting city location.",
       ]); 
    } 
  }

 //CUSTOMER PASSWORD RESET=================================  
  public function doPasswordReset(Request $request){

    $Misc = new Misc();
    $UserCustomer = new UserCustomer();

    $response = "Failed";
    $responseMessage = "";

    

    $data['EmailAddress'] = $request->post('EmailAddress');
    $data['CurrentPassword'] = $request->post('CurrentPassword');
    $data['NewPassword'] = $request->post('NewPassword');

    if(empty($data['EmailAddress'])){
      $ResponseMessage ='Email address is required.';
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }

    if(empty($data['CurrentPassword'])){
      $ResponseMessage ='Current password is required.';    
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }

    if(empty($data['NewPassword'])){
      $ResponseMessage ='New password is required.';
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }

    if($Misc->IsValidEmail($data['EmailAddress'])==false){
      $ResponseMessage ='Enter your valid email address.';
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }
        
    $CustomerID=0;
    $Info=$UserCustomer->doPasswordReset($data);
    if($Info>0){
        return response()->json([
         'data' => $Info,
         'response' => 'Success',
         'message' => "User is successfully logged-in.",
       ]);    

    }else{
        return response()->json([
          'data' => null,
          'response' => 'Failed',
          'message' => "Invalid login credentials.",
       ]); 
    } 

  }

 // GET CUSTOMER INFORMATION========================================================================
 public function getCustomerInformation(Request $request){

    $Misc = new Misc();
    $UserCustomer = new UserCustomer();

    $response = "Failed";
    $responseMessage = "";

    
    $data['UserID'] = $request->post('UserID');
                
    $Info=$UserCustomer->getCustomerInformation($data);

    if(isset($Info)>0){      
        return response()->json([                  
         'response' => 'Success',
         'data' => $Info,
         'message' => "Customer with ID ". $data['UserID']. " has profile data.",
       ]);    

    }else{
        return response()->json([
          'response' => 'Failed',
          'data' => null,
          'message' => "Customer does not exist.",
       ]); 
    } 
  }

 // GET CUSTOMER INFORMATION========================================================================
 public function getCustomerInformationByEmail(Request $request){

    $Misc = new Misc();
    $UserCustomer = new UserCustomer();

    $response = "Failed";
    $responseMessage = "";
    
    $data['FirstName']="";
    $data['LastName']="";        

    $data['SocialMedia'] = $request->post('SocialMedia');    
    $data['FullName'] = $request->post('FullName');    
    $data['EmailAddress'] = $request->post('EmailAddress');

     $result=$this->SeparateTheNames($data['FullName']);
     if($result!=""){
        $data['FirstName']=$result['FirstName'];
        $data['LastName']=$result['LastName'];
     }       
              
    $Info=$UserCustomer->getCustomerInformationByEmail($data);
    if(isset($Info)>0){      
        return response()->json([                  
         'response' => 'Success',
         'data' => $Info,
         'message' => "Customer with ID ". $data['EmailAddress']. " has profile data.",
       ]);    

    }else{

    // SAVE NEW RECORD USING SOCIAL IF EMAIL DOES NOT EXIST     
      $retVal=$UserCustomer->doRegisterSocial($data);
      if($retVal=='Success'){
         $Info=$UserCustomer->getCustomerInformationByEmail($data);
         return response()->json([                  
           'response' => 'Success',
           'data' => $Info,
           'message' => "Customer with ID ". $data['EmailAddress']. " has profile data.",
          ]);    
      }      
    } 

  }

  public function SeparateTheNames($fullName) {
    
    $fullName = trim($fullName);
    
    // Find the position of the last space
    $spacePosition = strrpos($fullName, ' ');
    
    // If no space is found, return the whole name as first name
    if ($spacePosition === false) {
        return [
            'FirstName' => $fullName,
            'LastName' => ''
        ];
    }
    
    // Extract first name and family name
    $firstName = trim(substr($fullName, 0, $spacePosition));
    $familyName = trim(substr($fullName, $spacePosition + 1));
    
    return [
        'FirstName' => $firstName,
        'LastName' => $familyName
    ];
}

 //   // GET CUSTOMER INFORMATION WITH PRIMARY ADDRESS========================================================================
 // public function getCustomerInformationWithPrimaryAddress(Request $request){

 //    $Misc = new Misc();
 //    $UserCustomer = new UserCustomer();

 //    $response = "Failed";
 //    $responseMessage = "";

 //    
 //    $data['CustomerID'] = $request->post('UserID');
                
 //    $Info=$UserCustomer->getCustomerInformation($data);

 //    if(isset($Info)>0){     

 //        $Subcriber_Status=0;
 //        $Subcriber_Status=$UserCustomer->getNewsLettrSubsciberStatus($Info->emailaddress);

 //        return response()->json([                  
 //         'response' => 'Success',
 //         'data' => $Info,
 //         'subscriber_status' => $Subcriber_Status,
 //         'message' => "Customer with ID ". $data['CustomerID']. " has profile data.",
 //       ]);    

 //    }else{
 //        return response()->json([
 //          'response' => 'Failed',
 //          'data' => null,
 //          'subscriber_status' => null,
 //          'message' => "Customer does not exist.",
 //       ]); 
 //    } 
 //  }

  // UPDATE CUSTOMER PROFILE======================================
  public function doUpdateCustomerProfile(Request $request){

    $Misc = new Misc();
    $UserCustomer = new UserCustomer();

    $response = "Failed";
    $responseMessage = "";

     
    $data['UserID']=$request->post('UserID');

    $data['FirstName'] = $request->post('FirstName'); 
    $data['LastName'] = $request->post('LastName'); 
       
    $data['MobileNo'] = $request->post('MobileNo');
    $data['EmailAddress'] = $request->post('EmailAddress');

    $data['BirthDate'] = $request->post('BirthDate');
    $data['FullName'] =  $data['FirstName'] .' '. $data['LastName'];   

    $data['StreetAddress'] = $request->post('StreetAddress'); 
    $data['CityName'] = $request->post('CityName');      
    $data['ZipCode'] = $request->post('ZipCode'); 
      
    if(empty($data['FirstName'])){
      $ResponseMessage = 'First name is required.';
       return response()->json([
           'response' => 'Failed',
           'message' => $ResponseMessage,
       ]);
    }

    if(empty($data['LastName'])){
      $ResponseMessage = 'Last name name is required.';
       return response()->json([
           'response' => 'Failed',
           'message' => $ResponseMessage,
       ]);
    }
    
    if(empty($data['EmailAddress'])){
      $ResponseMessage ='Email address is required.';    
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }

    if(empty($data['BirthDate'])){
      $ResponseMessage ='Birth date is required.';
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }

    if(empty($data['FirstName'])){
      $ResponseMessage = 'First name is required.';
       return response()->json([
           'response' => 'Failed',
           'message' => $ResponseMessage,
       ]);
    }


    if($Misc->IsValidEmail($data['EmailAddress'])==false){
      $ResponseMessage ='Enter current & valid email address.';
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }


    if(empty($data['StreetAddress'])){
      $ResponseMessage = 'Street address is required.';
       return response()->json([
           'response' => 'Failed',
           'message' => $ResponseMessage,
       ]);
    }

    if(empty($data['CityName'])){
      $ResponseMessage = 'Select city from the list';
       return response()->json([
           'response' => 'Failed',
           'message' => $ResponseMessage,
       ]);
    }
    
    if(empty($data['ZipCode'])){
      $ResponseMessage ='Zipcode is required.';    
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }

    if(!empty($data['EmailAddress']) && $Misc->isDataExist('users', 'id', $data['UserID'], "email", $data['EmailAddress'])){
        $ResponseMessage = 'Email is already used and registered by other.';
         return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }

     if(!empty($data['MobileNo']) && $Misc->isDataExist('users', 'id', $data['UserID'], "mobile", $data['MobileNo'])){
        $ResponseMessage = 'Mobile number is already registered.';
         return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    } 
    
    $retVal=$UserCustomer->doUpdateCustomerProfile($data);
     return response()->json([
      'response' => 'Success',
      'message' => "You have successfully updated your profile.",
    ]);                     
           
  }

  //SUBSCRIBED TO NEWS LETTER
  public function SubscribedToNewsLetter(Request $request){

    $Misc = new Misc();
    $UserCustomer = new UserCustomer();

    $responseMessage = "";
    $response = "Failed";  
    $data['FullName']="";

    $data['UserID']=$request->post('UserID');     
    $data['EmailAddress']=$request->post('EmailAddress');     
    $data['Is_Subscribe'] = $request->post('Is_Subscribe'); 

    $info=$UserCustomer->getCustomerInformation($data);
    if(isset($info)>0){
        $data['FullName']=$info->fullname;
    }
           
    $retVal=$UserCustomer->SubscribedToNewsLetter($data);

    if($data['Is_Subscribe']){
     return response()->json([
      'response' => 'Success',
      'message' => "You have successfully subcribed to news letter.",
       ]);                      
    }else{
      return response()->json([
      'response' => 'Success',
      'message' => "You have successfully un-subcribed to news letter.",
       ]);                                    
    }

  }

// UPDATE CUSTOMER ADDRESS======================================
  public function doUpdateCustomerAddress(Request $request){

    $Misc = new Misc();
    $UserCustomer = new UserCustomer();

    $response = "Failed";
    $responseMessage = "";

     
    
    $data['UserID']=$request->post('UserID');
    
    $data['StreetAddress'] = $request->post('StreetAddress'); 
    $data['CityName'] = $request->post('CityName');      
    $data['ZipCode'] = $request->post('ZipCode');
  
    if(empty($data['StreetAddress'])){
      $ResponseMessage = 'Street address is required.';
       return response()->json([
           'response' => 'Failed',
           'message' => $ResponseMessage,
       ]);
    }

    if(empty($data['CityName'])){
      $ResponseMessage = 'Select city from';
       return response()->json([
           'response' => 'Failed',
           'message' => $ResponseMessage,
       ]);
    }
    
    if(empty($data['ZipCode'])){
      $ResponseMessage ='Zipcode is required.';    
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }
    
    $retVal=$UserCustomer->doUpdateCustomerAddress($data);
     return response()->json([
      'response' => 'Success',
      'message' => "You have successfully updated your address.",
    ]);                     
           
  }

  function doUploadPhoto(Request $request){

     $data['image']=$request->post('image');
     $data['filename']=$request->post('filename');

      return response()->json([
      'response' => 'Success',
      'message' => $data['filename'],
    ]); 

  }

  //LIBRARY=====================================================================
public function getCustomerLibraryList(Request $request){
    
    $Misc = new Misc();
    $Library = new Library();

    $response = "Failed";
    $responseMessage = "";

    
    $data['UserID']=$request->post('UserID');

    $data["SearchText"] = '';
    $data["Status"] = '';
    $data["PageNo"] = 0;
    $data["Limit"] = 0;

    $result=$Library->getLibraryList($data);  
    return response()->json($result); 
    
}

  //LIBRARY=====================================================================
public function checkCustomerLibraryBookExist(Request $request){
    
    $Misc = new Misc();
    $Library = new Library();

    $checkLibraryBook=false;
    $checkLibrarySubscribeBook=false;

    $response = "Failed";
    $responseMessage = "";

    $data['UserID']=$request->post('UserID');
    $data['ProductID']=$request->post('ProductID');

   $checkLibraryBook=$Library->checkProductsIfExistInLibrary($data['ProductID'],$data['UserID']);
   $checkLibrarySubscribeBook=$Library->checkProductsIfExistInSubscribeLibrary($data['ProductID'],$data['UserID']);

   if($checkLibraryBook || $checkLibrarySubscribeBook){
       $responseMessage ='Book is already in library section';
       return response()->json([
         'response' => 'Success',         
         'is_allow_post_review' => true,         
         'message' => $responseMessage,
        ]);    

    }else{
         return response()->json([
         'response' => 'Failed',         
         'is_allow_post_review' => false,         
         'message' => "This book is not in listed in your library.",
        ]);  
    }
}

public function checkBookHasBookMark(Request $request){
    
    $Misc = new Misc();
    $Library = new Library();

    $BookMarkChapter=0;
    
    $response = "Failed";
    $responseMessage = "";

    

    $data['UserID']=$request->post('UserID');
    $data['ProductID']=$request->post('ProductID');

    $BookMarkChapter=$Library->getPageChapterBookMark($data['ProductID'],$data['UserID']);
  
   if($BookMarkChapter>=1){
       $responseMessage ='This book has chapter book mark';
       return response()->json([
         'response' => 'Success',         
         'has_book_mark' => true,         
         'message' => $responseMessage,
        ]);    

    }else{
         return response()->json([
         'response' => 'Failed',         
         'has_book_mark' => false,         
         'message' => "This book has no book mark chapter",
        ]);  
    }

}

public function checkCustomerLibraryDownloadBookExist(Request $request){
    
    $Misc = new Misc();
    $Library = new Library();

    $checkLibraryBook=false;
    $checkLibrarySubscribeDownloadBook=false;

    $response = "Failed";
    $responseMessage = "";

    
    $data['UserID']=$request->post('UserID');
    $data['ProductID']=$request->post('ProductID');

   $checkLibraryBook=$Library->checkProductsIfExistInLibrary($data['ProductID'],$data['UserID']);
   $checkLibrarySubscribeDownloadBook=$Library->checkProductsIfExistInSubscribeDownloadLibrary($data['ProductID'],$data['UserID']);

   if($checkLibraryBook || $checkLibrarySubscribeDownloadBook){
       $responseMessage ='Book is already in library section';
       return response()->json([
         'response' => 'Success',         
         'is_allow_download' => true,         
         'message' => $responseMessage,
        ]);    

    }else{
         return response()->json([
         'response' => 'Failed',         
         'is_allow_download' => false,         
         'message' => "This book is not in listed in your library.",
        ]);  
    }

}

public function getSubscribedReadBooksList(Request $request){
    
    $Misc = new Misc();
    $Library = new Library();

    $response = "Failed";
    $responseMessage = "";

    
    $data['UserID']=$request->post('UserID');    

    $data["SearchText"] = '';
    $data["Status"] = '';
    $data["PageNo"] = 0;
    $data["Limit"] = 0;

    $result=$Library->getSubscribedReadBooksList($data);  
    return response()->json($result); 
    
}

//LIBRARY SUBSCRIBED SECTION========================================
public function saveBookMarks(Request $request){
    
    $Misc = new Misc();
    $Book = new Book();
    $Library = new Library();

    $response = "Failed";
    $responseMessage = "";

    $IsFreeBook=0;

  

    $data['UserID']=$request->post('UserID');
    $data['ProductID']=$request->post('ProductID');
    $data['PageNo']=$request->post('PageNo');
          
    $retVal=$Library->saveBookMarks($data);
     return response()->json([
      'response' => $response,
      'message' => "Sucessfully save book marks.",
    ]);  
    
}

public function updateBookMarks(Request $request){

    $Misc = new Misc();
    $Book = new Book();
    $Library = new Library();
   
   $data['chapter_page_no']=0;
    $response = "Failed";
    $responseMessage = "";

    $IsFreeBook=0;

  

    $data['UserID']=$request->input('UserID');
    $data['ProductID']=$request->input('ProductID');
    $data['PageNo']=$request->input('PageNo');
          
    $retVal=$Library->updateBookMarks($data);

    return Redirect::back();
  
}

  // SET READ SUBCRIBE BOOKS PREMIUM AND FREE NOT INCLUDED====================================================
public function saveReadSubscribedBooks(Request $request){
    
    $Misc = new Misc();
    $Book = new Book();
    $Library = new Library();

    $response = "Failed";
    $responseMessage = "";

    $IsFreeBook=0;
    $IsPremium=0;

    $data['UserID']=$request->post('UserID');   
    $data['ProductID']=$request->post('ProductID'); 
    $data['IsRead']=$request->post('IsRead');

    $info=$Book->getBookInfoByID($data['ProductID']);

    if(isset($info)>0){
        $IsFreeBook=$info->is_free;
        $IsPremium=$info->is_premium;
    }

    if($IsFreeBook==1){
        $ResponseMessage ='Sorry. Cannot download free books.';
         return response()->json([
           'response' => 'Failed',         
           'message' => $ResponseMessage,
          ]);    
    }

    if($IsPremium==1){
        $ResponseMessage ='Sorry. Cannot download premium books.';
         return response()->json([
           'response' => 'Failed',         
           'message' => $ResponseMessage,
          ]);    
    }

    //SAVE ALL NON FREE AND PREMIUM BOOKS   
    $result=$Library->saveReadSubscribedBooks($data);  
    return response()->json($result); 
    
}

// DOWNLOADED BOOKS====================================================
public function getSubscribedDownloadedBooksList(Request $request){
    
    $Misc = new Misc();
    $Library = new Library();

    $response = "Failed";
    $responseMessage = "";

    
    $data['UserID']=$request->post('UserID');    
    
    $result=$Library->getSubscribedDownloadedBooksList($data);  
    return response()->json($result); 
    
}

//DOWNLOADED SECTION======================================================
public function saveDownloadedSubscribedBooks(Request $request){
    
    $Misc = new Misc();
    $Book = new Book();
    $Library = new Library();

    $response = "Failed";
    $responseMessage = "";

    $IsFreeBook=0;
    $IsPremium=0;

    $data['UserID']=$request->post('UserID');
    $data['ProductID']=$request->post('ProductID');
    $data['IsSubscribed']=$request->post('IsSubscribed');
    $data['IsDownloaded']=$request->post('IsDownloaded');

    $info=$Book->getBookInfoByID($data['ProductID']);

    if(isset($info)>0){
        $IsFreeBook=$info->is_free;
        $IsPremium=$info->is_premium;
    }

    if($IsFreeBook==1){
        $ResponseMessage ='Sorry. Cannot download free books.';
         return response()->json([
           'response' => 'Failed',         
           'message' => $ResponseMessage,
          ]);    
    }

     if($IsPremium==1){
        $ResponseMessage ='Sorry. Cannot download premium books.';
         return response()->json([
           'response' => 'Failed',         
           'message' => $ResponseMessage,
          ]);    
    }

    if($Library->checkProductsIfExistInLibrary($data['ProductID'],$data['UserID'])){
       $ResponseMessage ='Sorry. Cannot download book is already in your library section.';
       return response()->json([
         'response' => 'Failed',         
         'message' => $ResponseMessage,
        ]);    
    }

    if($Library->checkProductsIfExistInDownloadSubscribedBooks($data['ProductID'],$data['UserID'])){
       $ResponseMessage ='This book is already in your downloaded list.';
       return response()->json([
         'response' => 'Failed',         
         'message' => $ResponseMessage,
        ]);    
    }
              
    $retVal=$Library->saveDownloadSubscribedBooks($data);
     return response()->json([
      'response' => 'Success',
      'message' => "Book has sucessfully added to downloaded books.",
    ]);  
    
}

//FAVORITES=====================================================================
public function getCustomerFavoriteList(Request $request){
    
    $Misc = new Misc();
    $Favorites = new Favorites();

    $response = "Failed";
    $responseMessage = "";

        
    $data['UserID']=$request->post('UserID');

    $data["SearchText"] = '';
    $data["Status"] = '';
    $data["PageNo"] = 0;
    $data["Limit"] = 0;

    $result=$Favorites->getFavoriteList($data);  
    return response()->json($result); 
    
}

public function addToFavorites(Request $request){

    $Misc = new Misc();
    $Favorites = new Favorites();

    $response = "Failed";
    $responseMessage = "";

    

    $data['UserID'] = $request->post('UserID');
    $data['ProductID'] = $request->post('ProductID');

    if($data['ProductID']<=0){
      $ResponseMessage ='Select a book to add into your favorites.';
       return response()->json([
         'response' => 'Failed',         
         'message' => $ResponseMessage,
        ]);
    }
    
    if($Favorites->checkProductsIfExist($data['ProductID'],$data['UserID'])){
       $ResponseMessage ='Book is already in your wishlist.';
       return response()->json([
         'response' => 'Failed',         
         'message' => $ResponseMessage,
        ]); 
        
    }

    $response=$Favorites->addToFavorites($data);
    if($response=='Success'){      
        return response()->json([                  
         'response' => $response,
         'message' => "Book has successfully added to your wishlist",
       ]);    

    }else{
        return response()->json([
          'response' => $response,
          'message' => "Something wrong while adding book to your wishlist.",
       ]); 
    } 

}

//CART ITEM LIST===========================================================================
public function getCustomerCartList(Request $request){
    
    $Misc = new Misc();
    $Cart = new Cart();

    $response = "Failed";
    $responseMessage = "";

    
    
    $data['UserID']=$request->post('UserID');

    $data["SearchText"] = '';
    $data["Status"] = '';
    $data["PageNo"] = 0;
    $data["Limit"] = 0;

    $result=$Cart->getCartList($data);  
    return response()->json($result); 
    
}

// ADD TO CART===============================================================
public function addToCart(Request $request){

    $Misc = new Misc();
    $Cart = new Cart();
    $Library = new Library();

    $response = "Failed";
    $responseMessage = "";

    

    $data['UserID'] = $request->post('UserID');
    $data['ProductID'] = $request->post('ProductID');

    $data['ProductQty'] = $request->post('ProductQty');
    $data['ProductPrice'] = $request->post('ProductPrice');
    $data['ProductDiscount'] = $request->post('ProductDiscount');

    $data['PromoDiscountPercent'] = $request->post('PromoDiscountPercent');
    $data['PromoDiscountPrice'] = $request->post('PromoDiscountPrice');

    if($data['ProductID']<=0){
      $ResponseMessage ='Select a book to add into your cart.';
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }
    
    if($Cart->checkProductsIfExist($data['ProductID'],$data['UserID'])){
       $ResponseMessage ='Book is already in your cart.';
       return response()->json([
         'response' => 'Failed',         
         'message' => $ResponseMessage,
        ]); 
        
    }

    if($Library->checkProductsIfExistInLibrary($data['ProductID'],$data['UserID'])){
       $ResponseMessage ='You already have purchased this book.';
       return response()->json([
         'response' => 'Failed',         
         'message' => $ResponseMessage,
        ]); 
        
    }
     
    $total_item_cart=0;
    $response=$Cart->addToCart($data);
    
    if($response=='Success'){      
 
        $total_item_cart=$Cart->getCustomerCartItemCount($data['UserID']);
        
        return response()->json([                  
         'response' => $response,
         'total' => $total_item_cart,
         'message' => "Book has successfully added to your cart",
       ]);    

    }else{
        return response()->json([
          'response' => $response,
          'total' => 0,
          'message' => "Something wrong while adding book to your cart.",
       ]); 
    } 

}

// REMOVE TO CART==========================================================
public function removeToCart(Request $request){

    $Misc = new Misc();
    $Cart = new Cart();

    $response = "Failed";
    $responseMessage = "";

    
    $data['UserID'] = $request->post('UserID');
    $data['ProductID'] = $request->post('ProductID');
    
    if($data['ProductID']<=0){
      $ResponseMessage ='Select a book to remove from your cart.';
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }
    
    $response=$Cart->removeToCart($data);
    if($response=='Success'){      
      $total_item_cart=$Cart->getCustomerCartItemCount($data['UserID']);
        return response()->json([                  
         'response' => $response,
         'total' => $total_item_cart,
         'message' => "Book has successfully removed to your cart",
       ]);    

    }else{
        return response()->json([
          'response' => $response,
          'total' => 0,
          'message' => "Something wrong while removing book to your cart.",
       ]); 
    } 

}

// PROCEED TO CHECK OUT==========================================================
public function proceedToCheckOut(Request $request){
    
    $Cart = new Cart();
    $Order = new Order();
    $Voucher = new Voucher();

    $response = "Failed";
    $responseMessage = "";

       
    $data['Platform'] = $request->post('Platform');   

    $data['UserID'] = $request->post('UserID');    
    $data['ApplyECredit'] = $request->post('ApplyECredit');
    $data['PaymentMethod'] = $request->post('PaymentMethod');
    $data['SubTotal'] = $request->post('SubTotal');
    $data['AmountPaid'] = $request->post('AmountPaid');

    $data['VoucherCode'] = $request->post('VoucherCode');
    $data['VoucherDiscountAmount'] = $request->post('VoucherAmount');
     
    $response=$Order->proceedToCheckOut($data);
    if($response=='Success'){      
        return response()->json([                  
         'response' => $response,
         'message' => "You have successfully purchase your favorite books.",
       ]);    

    }else{
        return response()->json([
          'response' => $response,
          'message' => "Something wrong while checking out order.",
       ]); 
    } 

}

//BOOK LIST==================================================================
public function getAllBookCategoryList(Request $request){

  $Books = new Book();

  $response = "Failed";
  $responseMessage = "";
 
  $data['Status'] = 'All';
  $data['SearchText'] = '';

  $data["PageNo"] = 0;
  $data["Limit"] = 0;

  $result=$Books->getAllBookCatergoryList($data);  
  return response()->json($result); 
  
  }

 public function getAllBookList(Request $request){

  $Books = new Book();

  $response = "Failed";
  $responseMessage = "";
 
  $data['Status'] = 'All';
  $data['SearchText'] = '';

  $data["PageNo"] = 0;
  $data["Limit"] = $request->post('Limit');

  $result=$Books->getBookList($data);  

  return response()->json($result); 
  }

 public function searchBookList(Request $request){

  $Books = new Book();

  $response = "Failed";
  $responseMessage = "";
 

  $data['Status'] = "All";
  $data['SearchText'] = $request->post('SearchText');
  $data['UserID'] = $request->post('UserID');

  $data['Filter_Sort'] = $request->post('Filter_Sort');
  $data['Filter_Genre'] = $request->post('Filter_Genre');
  $data['Filter_Star'] = $request->post('Filter_Star');

  $data["PageNo"] = 0;
  $data["Limit"] = 0;

  $result=$Books->getSearchBookList($data);  

  return response()->json($result); 
  }

  public function getFeaturedBookList(Request $request){

  $Books = new Book();

  $response = "Failed";
  $responseMessage = "";
 
  $data['SearchText'] = '';
  $data['Status'] = 'Featured';
  

  $data["PageNo"] = 0;
  $data["Limit"] = 0;

  $result=$Books->getBookList($data);  

  return response()->json($result); 
  }

  public function getBestSellerBookList(Request $request){

  $Books = new Book();

  $response = "Failed";
  $responseMessage = "";
 
  $data['SearchText'] = '';
  $data['Status'] = 'Best Seller';
  

  $data["PageNo"] = 0;
  $data["Limit"] = 0;

  $result=$Books->getBookList($data);  

  return response()->json($result); 
  }

  public function getNewReleaseBookList(Request $request){

  $Books = new Book();

  $response = "Failed";
  $responseMessage = "";
 
  $data['SearchText'] = '';
  $data['Status'] = 'New Release';
  

  $data["PageNo"] = 0;
  $data["Limit"] = 0;

  $result=$Books->getBookList($data);  

  return response()->json($result); 
  }

    public function getPremiumBookList(Request $request){

  $Books = new Book();

  $response = "Failed";
  $responseMessage = "";
 
  $data['SearchText'] = '';
  $data['Status'] = 'Premium';
  

  $data["PageNo"] = 0;
  $data["Limit"] = 0;

  $result=$Books->getBookList($data);  

  return response()->json($result); 
  }

  public function getFreeBookList(Request $request){

  $Books = new Book();

  $response = "Failed";
  $responseMessage = "";
 
  $data['SearchText'] = '';
  $data['Status'] = 'Free';
  

  $data["PageNo"] = 0;
  $data["Limit"] = 0;

  $result=$Books->getBookList($data);  

  return response()->json($result); 
  }

//CATALOGUE BOOK LIST==================================================================
public function getAllBookHeaderCatalogueList(Request $request){

  $Books = new Book();

  $response = "Failed";
  $responseMessage = "";
 
  $data['Status'] = 'All';
  $data['SearchText'] = '';

  $data["PageNo"] = 0;
  $data["Limit"] = 0;

  $result=$Books->getHeaderCatalogueList();  

  return response()->json($result); 
  }

public function getAllBookDetailsCatalogueList(Request $request){

  $Books = new Book();

  $response = "Failed";
  $responseMessage = "";
   
  $data['HeaderID'] = $request->post('HeaderID');
  $data['UserID'] = $request->post('UserID');

  $data['Status'] = 'All';
  $data['SearchText'] = '';

  $data["PageNo"] = 0;
  $data["Limit"] = 0;

  $result=$Books->getDetailsCatalogueList($data);  

  return response()->json($result); 
  }

//ORDER TRANSACTION===============================================================

  public function getCustomerOrderHistory(Request $request){

  $Order = new Order();

  $response = "Failed";
  $responseMessage = "";
 
  $data['UserID'] = $request->post('UserID');

  $data['Status'] = '';
  $data['SearchText'] = '';

  $data["PageNo"] = 0;
  $data["Limit"] = 20;

  $result=$Order->getOrderList($data);  

  return response()->json($result); 
  }

  public function getCustomerOrderInformation(Request $request){

  $Order = new Order();

  $response = "Failed";
  $responseMessage = "";
 
  $data['OrderID'] = $request->post('OrderID');

  $data['Status'] = '';
  $data['SearchText'] = '';

  $data["PageNo"] = 0;
  $data["Limit"] = 0;

  $sales_header=$Order->getOrderInfo($data['OrderID']);  
  if(isset($sales_header)>0){

      $response='Success';     
      if($response=='Success'){        
        return response()->json([                  
         'response' => $response,
         'data' => $sales_header,         
         'message' => "Customer has order header & detail transaction info.",
       ]);    

    }else{

        return response()->json([
          'response' => $response,
          'data' => null,         
          'message' => "Something wrong while getting customer order information",
       ]); 
    } 

  }else{
      return response()->json([
          'response' => $response,
          'data' => null,         
          'message' => "Something wrong while getting customer order information",
       ]);
    }
}

  public function sendCustomerOrderHistory(Request $request){

  $Order = new Order();
  $UserCustomer = new UserCustomer();

  $response = "Failed";
  $responseMessage = "";
 
  $data['UserID'] = $request->post('UserID');
                  
  $data['Status']='';
  $data['SearchText']='';
  
  $data['Limit']=0;
  $data['PageNo']=0;
  
  $info=$UserCustomer->getCustomerInformation($data);
    
  if(isset($info)>0){

       $data['OrderItemList']=$Order->getOrderHistoryItemList($data['UserID']);  

       $data['FullName']=$info->fullname;
       $data['EmailAddress']=$info->emailaddress;
                                    
  }

  if($data['UserID']>0 && count($data['OrderItemList'])>0){

        $Email = new Email();
        $Email->SendOrderHistoryEmail($data);

         return response()->json([
          'response' => 'Success',
          'message' => "Successfully email all transactions purchased order history.",
        ]);  

  }else{

     return response()->json([
          'response' => 'Failed',
          'message' => "Something is wrong while sending email of all transactions purchased order  history.",
        ]);  
         
    }
    
  }

 public function getCustomerOrderDetails(Request $request){

  $Order = new Order();

  $response = "Failed";
  $responseMessage = "";
 
  $data['OrderID'] = $request->post('OrderID');

  $data['Status'] = '';
  $data['SearchText'] = '';

  $data["PageNo"] = 0;
  $data["Limit"] = 0;

  $sales_details=$Order->getOrderItemList($data['OrderID']); 

  if(isset($sales_details)>0){     
    return response()->json($sales_details); 
  }
}

// CONTACT US FORM========================================================= 
 public function doSendInquiry(Request $request){

    $Misc = new Misc();
    $Contact = new Contact();

    $response = "Failed";
    $responseMessage = "";
        
    $data['Purpose'] = $request->post('Purpose');
    $data['UserID'] = $request->post('UserID');
    $data['FullName'] = $request->post('FullName');
    $data['EmailAddress'] = $request->post('EmailAddress');      
    $data['MobileNo'] = $request->post('MobileNo');
    $data['Message'] = $request->post('Message');
    $data['ImageFileName'] = $request->post('ImageFileName');  
    
    if(empty($data['Purpose'])){
      $ResponseMessage = 'Select inquiry type from the list.';
       return response()->json([
           'response' => 'Failed',
           'message' => $ResponseMessage,
       ]);
    }

    if(empty($data['FullName'])){
      $ResponseMessage = 'Full name is required.';
       return response()->json([
           'response' => 'Failed',
           'message' => $ResponseMessage,
       ]);
    }

    if(empty($data['EmailAddress'])){
      $ResponseMessage ='Email address is required.';    
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }

    if(empty($data['MobileNo'])){
      $ResponseMessage ='Mobile number is required.';
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }
    
     if(empty($data['Message'])){
      $ResponseMessage ='Message inquiry is required.';
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }


    if($Misc->IsValidEmail($data['EmailAddress'])==false){
      $ResponseMessage ='Enter current & valid email address.';
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }
    
    $retVal=$Contact->doSendInquiry($data);
     return response()->json([
      'response' => 'Success',
      'message' => "Inquiry has successfully sent. And wait for our staff to contact you.",
    ]);                     
           
  }

  // UPLOAD IMAGE FOR CONTACT US FORM ===================================================
  public function uploadPaymentImage(Request $request){

  $Misc = new Misc();
  $Messages = new Messages();
  
  $response = "Failed";
  $responseMessage = "";

  $ImageName='image-'.date("Y-m-d").'-'.date("H:i:s").'.jpg';

  $chk_extensions = ["jpg", "jpeg","png"];
    
   if ($request->hasFile('image_file')) {

        $file = $request->file('image_file'); 
        $isImage = $file->getClientOriginalExtension(); 


        $path = $file->storeAs('images',$ImageName,'public');    
        return response()->json(['path' => $path], 200); 

        // if (in_array($isImage,$chk_extensions){

        //   $path = $file->storeAs('images',$ImageName,'public');    
        //   return response()->json(['path' => $path], 200);

        //   return response()->json([                  
        //        'response' => "Success",
        //        'message' => "Image is successfully uploaded.",
        //     ],200); 

        // }else{     

        //     return response()->json([                  
        //        'response' => "Failed",
        //        'message' => "Sorry! Image file type is required.",
        //     ],500);                              

        // }               
    }

  }

//COMMENTS & REVIEW=====================================================================
public function getBookReview(Request $request){
    
    $Misc = new Misc();
    $Review = new Review();

    $response = "Failed";
    $responseMessage = "";

    
    $data['ProductID']=$request->post('ProductID');

    $data["SearchText"] = '';
    $data["Status"] = '';
    $data["PageNo"] = 0;
    $data["Limit"] = 0;

    $result=$Review->getReviewList($data);  
    return response()->json($result); 
    
}

public function doPostCommentReview(Request $request){

    $Misc = new Misc();
    $Review = new Review();

    $response = "Failed";
    $responseMessage = "";
             
    $data['UserID'] = $request->post('UserID');
    $data['ProductID'] = $request->post('ProductID');

    $data['Comment'] = $request->post('Comment');
    $data['Rating'] = $request->post('Rating');

    if(empty($data['Comment'])){
      $ResponseMessage = 'Post comment is required.';
       return response()->json([
           'response' => 'Failed',
           'message' => $ResponseMessage,
       ]);
    }
    
    $retVal=$Review->doPostComment($data);
     return response()->json([
      'response' => 'Success',
      'message' => "Your comment has successfully send & subject for Admins approval.",
    ]);                     
           
  }

//BANNER ADS=====================================================================
public function getHomeSliderBanner(Request $request){
        
    $BannerAds = new BannerAds();

    $response = "Failed";
    $responseMessage = "";

    $data['Type']=$request->post('Type');

    $data["SearchText"] = '';
    $data["Status"] = '';
    $data["PageNo"] = 0;
    $data["Limit"] = 0;

    $result=$BannerAds->getHomeSliderBannerList($data);  
    return response()->json($result); 
    
}

public function getPopUpBanner(Request $request){
        
    $BannerAds = new BannerAds();

    $response = "Failed";
    $responseMessage = "";

    $data['Type']=$request->post('Type');

    $data["SearchText"] = '';
    $data["Status"] = '';
    $data["PageNo"] = 0;
    $data["Limit"] = 0;

    $result=$BannerAds->getPopUpBannerList($data);  
    return response()->json($result); 
    
}

// PROCEED TO SUBSCRIBED==========================================================
public function proceedToSubscribe(Request $request){
    
    $Cart = new Cart();
    $Subscription = new Subscription();
    

    $response = "Failed";
    $responseMessage = "";

    $data['Platform'] = $request->post('Platform'); 

    $data['UserID'] = $request->post('UserID');    
    $data['SubscriptionPlanID'] = $request->post('PlanID');
    $data['ApplyECredit'] = $request->post('ApplyECredit');
    $data['PaymentMethod'] = $request->post('PaymentMethod');
    $data['SubTotal'] = $request->post('SubTotal');
     
    $response=$Subscription->proceedToSubscribe($data);
    if($response=='Success'){      
        return response()->json([                  
         'response' => $response,
         'message' => "You have successfully subscribe to plan.",
       ]);    

    }else{
        return response()->json([
          'response' => $response,
          'message' => "Something wrong while subscribing to plan.",
       ]); 
    } 

}

//SUBSCRIPTION PLAN=====================================================================
public function getSubscriptionPlanList(Request $request){
    
    $Misc = new Misc();
    $Subscription = new Subscription();

    $response = "Failed";
    $responseMessage = "";

    
    
    $data["SearchText"] = '';
    $data["Status"] = '';
    $data["PageNo"] = 0;
    $data["Limit"] = 0;

    $result=$Subscription->getSubscriptionPlanList($data);  
    return response()->json($result); 
    
}

// CANCEL SUBSCRIPTION STATUS==========================================================
public function cancelSubscriptionPlan(Request $request){
    
    $Misc = new Misc();
    $Subscription = new Subscription();

    $response = "Failed";
    $responseMessage = "";

             
    $data['UserID'] = $request->post('UserID');
    $data['FullName'] = $request->post('FullName');
    $data['EmailAddress'] = $request->post('EmailAddress');      
    $data['MobileNo'] = $request->post('MobileNo');
    $data['Reason'] = $request->post('Reason'); 
    
    if(empty($data['FullName'])){
      $ResponseMessage = 'Full name is required.';
       return response()->json([
           'response' => 'Failed',
           'message' => $ResponseMessage,
       ]);
    }

    if(empty($data['EmailAddress'])){
      $ResponseMessage ='Email address is required.';    
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }

    if(empty($data['MobileNo'])){
      $ResponseMessage ='Mobile number is required.';
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }
    
     if(empty($data['Reason'])){
      $ResponseMessage ='Reason for cancellation is required.';
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }

    if($Misc->IsValidEmail($data['EmailAddress'])==false){
      $ResponseMessage ='Enter current & valid email address.';
       return response()->json([
         'response' => 'Failed',
         'message' => $ResponseMessage,
        ]);
    }
    
    $retVal=$Subscription->cancelSubscriptionPlan($data); 
     return response()->json([
      'response' => 'Success',
      'message' => "You have successfully cancelled your subscription plan & will end today. ",
    ]);   
    
}

public function extendSubscriptionPlan(Request $request){
    
    $Misc = new Misc();
    $Subscription = new Subscription();

    $response = "Failed";
    $responseMessage = "";

    
    $data['UserID'] = $request->post('UserID'); 

    $data["SearchText"] = '';
    $data["Status"] = '';
    $data["PageNo"] = 0;
    $data["Limit"] = 0;

    $result=$Subscription->getSubscriptionPlanList($data);  
    return response()->json($result); 
    
}

// CHECK SUBSCRIPTION STATUS==========================================================
public function checkSubscriptionStatus(Request $request){
    
    $Cart = new Cart();
    $UserCustomer = new UserCustomer();
    $Subscription = new Subscription();
    
    $response = "Failed";
    $responseMessage = "";

    $has_subscription=false;
    $SubscriptionPlanID=0;

    
    $data['UserID'] = $request->post('UserID'); 

    $SubscriptionPlanID=$Subscription->checkCustomerSubscriptionIfExist($data['UserID']);  

    if($SubscriptionPlanID>0){
      $has_subscription=true;  
      $Subscription->checkSubscriptionStatus($data);//check plan subscription
      $info=$UserCustomer->getCustomerCurrentSubscriptionInfo($data['UserID']);

       return response()->json([                  
           'response' => 'Success',
           'data' => $info,                     
           'message' => "You have successfully check customer subscription plan.",
         ]);   

    }else{

      $has_subscription=false;
      $info=null;

       return response()->json([
          'response' => 'Failed',
          'data' => null,          
          'message' => "User has no subscription plan.",
       ]);
    }  
         
}


// CHECK SUBSCRIBER STATUS==========================================================
public function checkSubscriberStatus(Request $request){
    
    $Cart = new Cart();
    $UserCustomer = new UserCustomer();
    $Subscription = new Subscription();
    
    $response = "Success";
    $responseMessage = "";

    $getEmailAddress="";    
    $data['UserID'] = $request->post('UserID');  

    $info=$UserCustomer->getCustomerInformation($data);
    if(isset($info)>0){
       $getEmailAddress= $info->emailaddress;     
      }   
     
     $info=$UserCustomer->getCustomerNewsLetterSubscriberInfo($getEmailAddress);

     if(isset($info)>0){          
          return response()->json([                  
           'response' => 'Success',
           'data' => $info,                   
           'message' => "You have successfully check customer subscriber plan."
         ]);   
     }else{
       return response()->json([
          'response' => 'Failed',
          'data' => $info,                    
          'message' => "Something wrong while checking subscriber.",
       ]); 
     }

}

//COUPON CODE=====================================================================
public function getAvailableCouponList(Request $request){

   
    
    $Misc = new Misc();
    $Voucher = new Voucher();

    $response = "Failed";
    $responseMessage = "";

    
    
    $data["SearchText"] = '';
    $data['UserID']=$request->post('UserID');

    $data["Status"] = '';
    $data["PageNo"] = 0;
    $data["Limit"] = 0;

    $chkListCoupon=$Voucher->getVoucherList($data);
      
    $arr_all_coupons = [];    

    $VoucherID=0;
    $VoucherCode='';
    $VoucherScope='';

     foreach($chkListCoupon as $list){
         
          $VoucherID=$list->coupon_ID;
          $VoucherCode=$list->coupon_code;
          $VoucherScope=$list->customer_scope;

          if($VoucherScope=='all'){

              $no_use=DB::table('coupon_sales')
                ->where('order_status','=','PAID')
                ->where('coupon_id','=',$VoucherID)
                ->count();
                      
               $info=$Voucher->getVoucherInfoByIDwithNoUsage($VoucherID,$no_use);
           
               if(isset($info)>0){
                    array_push($arr_all_coupons, $info);     
               }

          }else{

             $list = DB::table('coupons')
                 ->where('coupon_code','=',$VoucherCode)
                 ->whereraw ("CONCAT('|',scope_customer_id,'|') LIKE CONCAT('%|',". $data['UserID'].",'|%')")
                 ->get();

                if(count($list)>0){

                     $info=$Voucher->getVoucherInfoByIDwithNoUsage($VoucherID,$no_use);
           
                     if(isset($info)>0){
                          array_push($arr_all_coupons, $info);     
                     }
                }
          }
                                       
     }
    
         
      // return response()->json([
      //       'coupons' => $arr_all_coupons, 
      //   ]);

    return response()->json($arr_all_coupons); 
    
}

//VALIDATE COUPON CODE=====================================================================
public function validateCouponCode(Request $request){
    
    $Voucher = new Voucher();

    $response = "Failed";
    $responseMessage = "";

    
    $data['VoucherCode']=$request->post('VoucherCode');
    $data['UserID']=$request->post('UserID');

    if($data['VoucherCode']!=''){       
      
      $getVoucherID=0;
      $getVoucherNoUsage=0;

      $getVoucherPercentDiscount=0;
      $getVoucherAmountDiscount=0;

      $getRequiredQty=0;
      $getMinPurchase=0;

      $getActivationType='';  
      $getApplicableType='';

      $getScopeCustomerScope='';
      $getScopeCustomerID=0;

      $IsCustomerAllowToUse=false;

      $voucher_info=$Voucher->getVoucherInfoByCode($data['VoucherCode']);

      if(isset($voucher_info)>0){

          $getVoucherID =$voucher_info->coupon_ID;
          $getVoucherPercentDiscount=$voucher_info->percentage;
          $getVoucherAmountDiscount=$voucher_info->discount_amount;
          $getRequiredQty=$voucher_info->purchase_qty;
          $getMinPurchase=$voucher_info->min_purchsae_amount;

          $getActivationType=$voucher_info->activation_type;  
          $getApplicableType=$voucher_info->applicable_product_type;  
          
          $getScopeCustomerScope=$voucher_info->customer_scope;  
          $getScopeCustomerID=$voucher_info->scope_customer_id;  

          if($getVoucherID>0){

             $getVoucherNoUsage=DB::table('coupon_sales')
                          ->where('order_status','=','PAID')
                          ->where('coupon_id','=',$getVoucherID)
                          ->count();

             $info=$Voucher->getVoucherInfoByIDwithNoUsage($getVoucherID,$getVoucherNoUsage);  
             
               if($info==null || !isset($info)){
                 $ResponseMessage ='Sorry coupon code is already in maximum use limit.';
                   return response()->json([
                    'response' => 'Failed',     
                    'percent_discount' => null,      
                    'amount_discount' => null,  
                    'required_qty' => null,  
                    'min_purchase' => null, 
                    'message' => $ResponseMessage,
                    ]);
               }           
            } 


          if($getApplicableType!='physical'){  

                if($getScopeCustomerScope!='' && $getScopeCustomerScope=='specific'){
                        
                        $list = DB::table('coupons')
                               ->where('coupon_code','=',$data['VoucherCode'])
                               ->whereraw ("CONCAT('|',scope_customer_id,'|') LIKE CONCAT('%|',". $data['UserID'].",'|%')")
                               ->get();
                               
                               if(count($list)>0){
                                   $IsCustomerAllowToUse=true;
                               }else{
                                   $IsCustomerAllowToUse=false;
                               }
                                     
                                     
                                if($IsCustomerAllowToUse){
                                      return response()->json([
                                         'response' => 'Success',         
                                         'percent_discount' => $getVoucherPercentDiscount,         
                                         'amount_discount' => $getVoucherAmountDiscount,   
                                         'required_qty' => $getRequiredQty, 
                                         'min_purchase' => $getMinPurchase,
                                         'message' => 'Voucher Discount is '.$getVoucherPercentDiscount. '%',
                                        ]); 
                                }else{
                                      $ResponseMessage ='Sorry you are not qualify to use this coupon code.';
                                         return response()->json([
                                          'response' => 'Failed',     
                                          'percent_discount' => null,      
                                          'amount_discount' => null,  
                                          'required_qty' => null,  
                                          'min_purchase' => null, 
                                          'message' => $ResponseMessage,
                                          ]); 
                                }
                                
                      }else{            
                           return response()->json([
                           'response' => 'Success',         
                           'percent_discount' => $getVoucherPercentDiscount,         
                           'amount_discount' => $getVoucherAmountDiscount,   
                           'required_qty' => $getRequiredQty, 
                           'min_purchase' => $getMinPurchase, 
                           'message' => 'Voucher Discount is '.$getVoucherPercentDiscount. '%',
                           
                          ]); 
             
                      }
                                                        
           }else{

                $ResponseMessage ='Coupon is invalid. Please try other coupon.';
                 return response()->json([
                   'response' => 'Failed',     
                   'percent_discount' => null,      
                   'amount_discount' => null,  
                   'required_qty' => null,  
                   'min_purchase' => null, 
                   'message' => $ResponseMessage,
                  ]); 

            }  


      }else{

            $ResponseMessage ='Coupon is invalid. Please try other coupon.';
               return response()->json([
                 'response' => 'Failed',     
                 'percent_discount' => null,      
                 'amount_discount' => null,  
                 'required_qty' => null,  
                 'min_purchase' => null, 
                 'message' => $ResponseMessage,
                ]); 

       }                      
     }

       return response()->json($result); 

   }
  
 //EWALLET CREDITS HISTORY===============================================================
  public function getEWalletCreditsHistory(Request $request){

  $EWallet = new EWallet();

  $response = "Failed";
  $responseMessage = "";
 
  $data['UserID'] = $request->post('UserID');

  $data['Status'] = '';
  $data['SearchText'] = '';

  $data["PageNo"] = 0;
  $data["Limit"] = 0;

  $result=$EWallet->getEWalletHistoryList($data);  

  return response()->json($result); 

  }

//MESSAGE NOTIFICATION===============================================================
  public function getMessageNotificationList(Request $request){

  $Messages = new Messages();

  $response = "Failed";
  $responseMessage = "";
 
  $data['UserID'] = $request->post('UserID');

  $data['Status'] = '';
  $data['SearchText'] = '';

  $data["PageNo"] = 0;
  $data["Limit"] = 0;

  $result=$Messages->getMessageNotificationList($data);  

  return response()->json($result); 
  }

  //Set Read Message===================================================
  public function openSetReadMessageNotification(Request $request){

  $Messages = new Messages();

  $response = "Failed";
  $responseMessage = "";
 
  $data['MessageID'] = $request->post('MessageID');

  $data['Status'] = '';
  $data['SearchText'] = '';

  $data["PageNo"] = 0;
  $data["Limit"] = 0;

  $response=$Messages->openSetReadMessageNotification($data);  

   return response()->json([                  
     'response' => $response,
     'message' => "Message has successfully set to read.",
   ]);    

  }

  //Set Deleted Message===================================================
  public function deleteReadMessageNotification(Request $request){

  $Messages = new Messages();

  $response = "Failed";
  $responseMessage = "";
 
  $data['MessageID'] = $request->post('MessageID');

  $data['Status'] = '';
  $data['SearchText'] = '';

  $data["PageNo"] = 0;
  $data["Limit"] = 0;

  $response=$Messages->deleteReadMessageNotification($data);  

   return response()->json([                  
     'response' => $response,
     'message' => "Message has successfully deleted.",
   ]);    

  }

//COMPANY ABOUT=====================================================================
public function getCompanyAboutUs(Request $request){

    $Company = new Company();

    $response = "Failed";
    $responseMessage = "";

    $data['Type']=$request->post('Type');

    $data['Info']=$Company->getCompanyAboutUs($data);
     return View::make('api/about-us')->with($data);   

    // return response()->json([
    //   'response' => 'Success',
    //   'data' => $Info,
    //   'message' => "Successfully get pop company about us.",
    // ]); 

    
}

//FAQ COMPANY=======================================================================
public function getCompanyFAQ(Request $request){
    
    $Company = new Company();

    $response = "Failed";
    $responseMessage = "";

    $data['Type']=$request->post('Type');

    $data['Info']=$Company->getCompanyFAQ($data);
    return View::make('api/faqs')->with($data);   
    
    // return response()->json([
    //   'response' => 'Success',
    //   'data' => $Info,
    //   'message' => "Successfully get pop company faq.",
    // ]); 
    
}

//TERMS CONDITION COMPANY=======================================================================
public function getCompanyTermsCondition(Request $request){
    
    $Company = new Company();

    $response = "Failed";
    $responseMessage = "";

    $data['Type']=$request->post('Type');

    $data['Info']=$Company->getCompanyTermsCondition($data);
    return View::make('api/terms-condition')->with($data);   
    
    // return response()->json([
    //   'response' => 'Success',
    //   'data' => $Info,
    //   'message' => "Successfully get pop company faq.",
    // ]); 
    
}

//PRIVACY POLICY COMPANY=======================================================================
public function getCompanyPrivacyPolicy(Request $request){
    
    $Company = new Company();

    $response = "Failed";
    $responseMessage = "";

    $data['Type']=$request->post('Type');

    $data['Info']=$Company->getCompanyPrivacyPolicy($data);    
    return View::make('api/privacy-policy')->with($data);   
    
    // return response()->json([
    //   'response' => 'Success',
    //   'data' => $Info,
    //   'message' => "Successfully get pop company faq.",
    // ]); 
    
}

 //CITY LIST==================================================================
 public function getCityList(Request $request){

  $City = new City();

  $response = "Failed";
  $responseMessage = "";
 
  $result=$City->getNewCityList();  
  return response()->json($result); 
  }

  //EPUB VIEWER================================================================
   public function showViewerEpub(Request $request){
     
   $Book= new Book();
   $Library= new Library();
   
   $data['epub_file_exist']=false;
   $data['chapter_page_no']=0;

   $data['product_id'] =  $request->input('doc_id');
   $data['customer_id'] =  $request->input('user_id');
   
   if($data['product_id']>0){
       $Epub_file='';

       $info=$Book->getBookInfoByID($data['product_id']);
       $data['chapter_page_no']=$Library->getPageChapterBookMark($data['product_id'],$data['customer_id']);
       
       if(is_null($data['chapter_page_no'])){
           $data['chapter_page_no']=0;
       }

       if(isset($info)>0){

           $Epub_file=$info->file_url;                              
            
            $data['epub_doc']='https://www.beta.ebooklat.phr.com.ph/public/'.$Epub_file;

             if(file_exists($_SERVER['DOCUMENT_ROOT'].'/public/'.$Epub_file)){
                $data['epub_file_exist']=true;
             }else{
                $data['epub_file_exist']=false;
             }
                             
          return View::make('api/epub_viewer')->with($data);    

       }else{
           $data['epub_doc']='';
           return View::make('api/epub_viewer')->with($data);    
       }
      
   }else{

       $data['epub_doc']='';
       return View::make('api/epub_viewer')->with($data);    
    }  
 }


}