<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\{
    User,
};

use App\Models\Ecommerce\{
    SalesDetail, SalesPayment, DeliveryStatus
};

use DB;

class SalesHeader extends Model
{
    use SoftDeletes;

    protected $table = 'ecommerce_sales_headers';
    protected $fillable = ['user_id', 'order_number', 'response_code', 'order_source', 'customer_name', 'customer_email', 'customer_contact_number', 'customer_address', 'customer_delivery_zip', 'customer_delivery_adress', 'delivery_tracking_number', 'delivery_fee_amount', 'delivery_fee_discount', 'delivery_courier', 'delivery_type', 'gross_amount', 'tax_amount', 'net_amount', 'discount_amount', 'ecredit_amount', 'payment_status', 'delivery_status', 'status','other_instruction','customer_type', 'payment_method', 'cancellation_request', 'cancellation_reason', 'cancellation_remarks'];
    // protected $fillable = ['user_id', 'order_number', 'response_code', 'order_source', 'customer_name', 'customer_email', 'customer_contact_number', 'customer_address', 'customer_delivery_zip', 'customer_delivery_adress', 'delivery_tracking_number', 'delivery_fee_amount', 'delivery_fee_discount', 'delivery_courier', 'delivery_type', 'gross_amount', 'tax_amount', 'net_amount', 'discount_amount', 'ecredit_amount', 'payment_status', 'delivery_status', 'status','other_instruction','customer_type', 'customer_delivery_province', 'customer_delivery_city', 'customer_delivery_barangay', 'payment_method'];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public static function balance($id){
        $amount = SalesHeader::whereId((int) $id)->sum('net_amount');        
        $paid = (float) SalesPayment::where('sales_header_id',$id)->whereStatus('PAID')->sum('amount');
        return ($amount - $paid);
    }

    public static function paid($id){
        $paid = SalesPayment::where('sales_header_id',$id)->whereStatus('PAID')->sum('amount');
        return $paid;
    }
    public function getPaymentstatusAttribute(){
        $paid = SalesPayment::where('sales_header_id',$this->id)->whereStatus('PAID')->sum('amount');
        $is_already_paid = SalesHeader::where('id', $this->id)->value('payment_status');

  
        if($paid >= $this->net_amount || $is_already_paid == 'PAID'){
            $tag_as_paid = SalesHeader::whereId((int) $this->id)->update(['payment_status' => 'PAID']);
            if($this->delivery_status == 'Pending'){
                $update_delivery_status = SalesHeader::whereId((int) $this->id)->update(['delivery_status' => 'Processing Stock']);
            }
            return 'PAID';
        }else{
            return 'UNPAID';
        }
       
    }

    public function items(){
    	return $this->hasMany(SalesDetail::class,'sales_header_id');
    }

    public function deliveries(){
        return $this->hasMany(DeliveryStatus::class,'order_id');
    }

    public function customer_details(){
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }
    
    public function coupons()
    {
        return $this->hasMany(CouponSale::class, 'sales_header_id');
    }

    public static function payment_status($order_num){
        $data = SalesHeader::where('order_number',$order_num)->first();
        return $data->payment_status;
        
    }

    public static function status(){
        $data = SalesHeader::where('status','PAID')->first();
        if(!empty($data)){
            return $data;
        } else {
            return NULL;
        }

    }
}
