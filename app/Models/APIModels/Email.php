<?php

namespace App\Models\APIModels;

use \Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

use Session;
use Hash;
use View;
use Input;
use Image;
use DB;

use App\Models\APIModels\Misc;

class Email extends Model
{

  //CUSTOMER NOTIF-----------------------------
  	public function SendCustomerRegistrationEmail($param){

	    $EmailAddress = $param['EmailAddress'];
	    if (filter_var($EmailAddress, FILTER_VALIDATE_EMAIL) && config('app.EmailDebugMode') == '0'){
	      Mail::send(
	        'api/member-registration',
	        [
              'FullName'=> $param['FullName'],
              'VerificationCode'=> $param['VerificationCode']
	        ],
	        function($message) use ($EmailAddress){
	          $message->from(config('app.CompanyNoReplyEmail'));
	          $message->to($EmailAddress);
	          $message->subject('Registration');
	        }
	      );
	    }
  	}
  	

  	 public function reSendVerificationCodeEmail($param){
  	     
	     $EmailAddress = $param['EmailAddress'];
	    if (filter_var($EmailAddress, FILTER_VALIDATE_EMAIL) && config('app.EmailDebugMode') == '0'){
	      Mail::send(
	        'api/resend-verification-code',
	        [                  
                 'VerificationCode'=> $param['VerificationCode']
	        ],
	        function($message) use ($EmailAddress){
	          $message->from(config('app.CompanyNoReplyEmail'));
	          $message->to($EmailAddress);
	          $message->subject('Resend Verification Code');
	        }
	      );
	    }
  	}


	public function SendPasswordResetEmail($param){
        
	     $EmailAddress = $param['EmailAddress'];
	    if (filter_var($EmailAddress, FILTER_VALIDATE_EMAIL) && config('app.EmailDebugMode') == '0'){
	      Mail::send(
	        'api/reset-password-email',
	        [                  
                  'Password'=> $param['Password']
	        ],
	        function($message) use ($EmailAddress){
	          $message->from(config('app.CompanyNoReplyEmail'));
	          $message->to($EmailAddress);
	          $message->subject('Password Reset');
	        }
	      );
	    }
  	}

  	public function getAdminEmailList(){

      $query = DB::table('email_recipients as admn_emails')    
       ->selectraw("
          admn_emails.email as EmailAddress        
        ");
         
      $list = $query->get();       
      return $list;                        
  	}

   public function SendContactUsEmail($param){

	    $param["AdminEmailAddress"]=config('app.CompanyEmail');
    	$param["CompanyNoReplyEmail"]=config('app.CompanyNoReplyEmail');

    	$ImageFileName=$param['ImageFileName'];
    	$FullPathImageFileName=$param['FullPathImageFileName'];	

    	//$ccEmails = DB::table('email_recipients')->pluck('email')->toArray();
    
      $i=0;
    	$contactList= [];         
      $info_list=$this->getAdminEmailList();

      if(count($info_list)>0){
           foreach($info_list as $info){                 
           	   $contactList[$i] = $info->EmailAddress;           	    
           	   $i++;
           }
      }  
   
      // $param['AdminEmailAddress'] = ['fransadan@gmail.com', 'zira0814@gmail.com']; //fix sample emails via ->cc or ->bcc  
      $param['AdminEmailAddress'] = $contactList;    
      
	    if (filter_var($param['EmailAddress'], FILTER_VALIDATE_EMAIL) && config('app.EmailDebugMode') == '0'){

       if($ImageFileName!=''){

       	 Mail::send(
	        'api/contact-us-email',
	        [                  
             'FullName'=> $param['FullName'],
             'Subject'=> $param['Subject'],
             'EmailAddress'=> $param['EmailAddress'],
             'MobileNo'=> $param['MobileNo'],
             'Message'=> $param['Message']
	        ],
	        function($message) use ($param){

	          $message->from($param['CompanyNoReplyEmail']);
	          $message->to($param["EmailAddress"]);
	          $message->cc($param['AdminEmailAddress']);
	          $message->subject('Contact Us - Inquiry');
	          $message->attach($param['FullPathImageFileName'], [
                    'as' => basename($param['FullPathImageFileName']),
                    'mime' => mime_content_type($param['FullPathImageFileName']),
                ]);
	         }
	      );

       }else{

       	  Mail::send(
	        'api/contact-us-email',
	        [                  
             'FullName'=> $param['FullName'],
             'Subject'=> $param['Subject'],
             'EmailAddress'=> $param['EmailAddress'],
             'MobileNo'=> $param['MobileNo'],
             'Message'=> $param['Message']
	        ],
	        function($message) use ($param){
	          $message->from($param['CompanyNoReplyEmail']);
	          $message->to($param["EmailAddress"]);
	          $message->cc($param['AdminEmailAddress']);
	          $message->subject('Contact Us - Inquiry');
	        }
	      );	

       }
	  
	    }
  	}

  	public function SendSubscribedEmail($param){

	    $param["AdminEmailAddress"]=config('app.CompanyEmail');
    	$param["CompanyNoReplyEmail"]=config('app.CompanyNoReplyEmail');

	    if (filter_var($param['EmailAddress'], FILTER_VALIDATE_EMAIL) && config('app.EmailDebugMode') == '0'){
	      Mail::send(
	        'api/subscribe-email',
	        [
	            'EmailAddress'=> $param['EmailAddress']
	        ],
	        function($message) use ($param){
	          $message->from($param['CompanyNoReplyEmail']);
	          $message->to($param["EmailAddress"]);	          
	          $message->subject('Subscribe To News Letter');
	        }
	      );
	    }
  	}

  public function SendUnSubscribedEmail($param){

	    $param["AdminEmailAddress"]=config('app.CompanyEmail');
    	$param["CompanyNoReplyEmail"]=config('app.CompanyNoReplyEmail');

	    if (filter_var($param['EmailAddress'], FILTER_VALIDATE_EMAIL) && config('app.EmailDebugMode') == '0'){
	      Mail::send(
	        'api/un-subscribe-email',
	        [
	            'EmailAddress'=> $param['EmailAddress']
	        ],
	        function($message) use ($param){
	          $message->from($param['CompanyNoReplyEmail']);
	          $message->to($param["EmailAddress"]);	          
	          $message->subject('Unsubscribe To News Letter');
	        }
	      );
	    }
  	}
  	
  public function SendOrderReceivedEmail($param){
              
    	$param["CompanyNoReplyEmail"]=config('app.CompanyNoReplyEmail');

	    if (filter_var($param['EmailAddress'], FILTER_VALIDATE_EMAIL) && config('app.EmailDebugMode') == '0'){
	      Mail::send(
	         'api/send-order-receive-email',
	        [                  
                  'OrderInfo'=> $param['OrderInfo'],
	                'OrderItem'=> $param['OrderItem']	
	        ],
	        function($message) use ($param){
	          $message->from($param["CompanyNoReplyEmail"]);
	          $message->to($param['EmailAddress']);
	          $message->subject('Order #'.$param['OrderNo']." has been placed.");
	        }
	      );
	    }
  	}
  	
  	  public function SendOrderHistoryEmail($param){
              
    	$param["CompanyNoReplyEmail"]=config('app.CompanyNoReplyEmail');

	    if (filter_var($param['EmailAddress'], FILTER_VALIDATE_EMAIL) && config('app.EmailDebugMode') == '0'){
	      Mail::send(
	         'api/send-order-history-email',
	        [                  
                  'OrderItemList'=> $param['OrderItemList'],	                
                  'FullName'=> $param['FullName'],	                
	        ],
	        function($message) use ($param){
	          $message->from($param["CompanyNoReplyEmail"]);
	          $message->to($param['EmailAddress']);
	          $message->subject('Purchased order history');
	        }
	      );
	    }
  	}
  	
  	

}