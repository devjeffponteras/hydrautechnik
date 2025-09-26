<?php

namespace App\Http\Controllers\Ecommerce;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Mail\DeliveryStatusMail;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\Validator;

use App\Helpers\{ListingHelper, Setting, PaynamicsHelper, XDEHelper, LBCHelper};


use App\Models\Ecommerce\{
    DeliveryStatus, SalesPayment, SalesHeader, SalesDetail, Coupon
};

use App\Models\{
    Permission, Page
};


use Auth;

class SalesController extends Controller
{
    private $searchFields = ['order_number','response_code','updated_at1', 'updated_at2'];

    public function __construct()
    {
        Permission::module_init($this, 'sales_transaction');
    }

    public function index(Request $request)
    {
        //to check update coupon availability
        Coupon::checkCouponAvailability();

        $customConditions = [
            [
                'field' => 'status',
                'operator' => '=',
                'value' => 'active',
                'apply_to_deleted_data' => true
            ],
        ];

        

        $listing = new ListingHelper('desc',10,'order_number',$customConditions);
        //$sales = $listing->simple_search(SalesHeader::class, $this->searchFields);

        $trans = SalesHeader::where('delivery_tracking_number','!=','')->get();
        foreach($trans as $tran){
            if($tran->delivery_type == 'xde'){
                $tracking = XDEHelper::get_delivery_status($tran->delivery_tracking_number);
                
                $countdelivery = DeliveryStatus::where('order_id',$tran->id)->get()->count();
                if($countdelivery < count($tracking)){
                    
                    for ($i=0;$i<count($tracking);$i++){
                        $save = DeliveryStatus::create([
                            'order_id' => $tran->id,
                            'user_id' => $tran->user_id,
                            'status'  => $tracking[$i]['status'],
                            'remarks'  => $tracking[$i]['comment'],
                            'date'  => $tracking[$i]['created_at'],
                        ]);
                        
                        $status='';
                        if($tracking[$i]['status']=='released'){
                            $status='Released';
                            $remarks='Cargo has been released to another 3pl.';
                        }
                        else if($tracking[$i]['status']=='accepted_by_courier'){
                            $status='Accepted by XDE';
                            $remarks='Accepted by XDE’s courier for delivery or sort';
                        }
                        else if($tracking[$i]['status']=='picked'){
                            $status='Picked';
                            $remarks='Cargo has been picked up from supplier’s/shipper’s
                                warehouse and is being shipped to XDE main warehouse
                                for sorting.';
                        }
                        else if($tracking[$i]['status']=='accepted_to_warehouse'){
                            $status='Accepted to Warehouse';
                            $remarks='Cargo has arrived to an XDE Main Warehouse.';
                        }
                        else if($tracking[$i]['status']=='forwarded_to_branch'){
                            $status='Forwarded to Branch';
                            $remarks='Cargo has been transferred from Main warehouse and
                                being shipped to an XDE branch.';
                        }
                        else if($tracking[$i]['status']=='accepted_to_branch'){
                            $status='Accepted to Branch';
                            $remarks='Cargo has arrived to an XDE Branch.';
                        }
                        else if($tracking[$i]['status']=='returned_to_warehouse'){
                            $status='Returned to Warehouse';
                            $remarks='Cargo has been returned back to the warehouse after a
                                failed delivery attempt.';
                        } 
                        else if($tracking[$i]['status']=='forwarded_to_warehouse'){
                            $status='Forwarded to Warehouse';
                            $remarks='Cargo is out for transfer to another hub or warehouse
                                closer to it’s destination.';
                        } 
                        else if($tracking[$i]['status']=='first_delivery_attempt'){
                            $status='First Delivery Attempt';
                            $remarks='Cargo is being delivered to consignee as the first attempt.';
                        }  
                        else if($tracking[$i]['status']=='first_attempt_failed'){
                            $status='First Attempt Failed';
                            $remarks='Cargo was not successfully delivered on the first attempt.';
                        } 
                        else if($tracking[$i]['status']=='redelivery_attempt'){
                            $status='Redelivery Attempt';
                            $remarks='Cargo is being delivered to consignee as the second or
                                third attempt.';
                        } 
                        else if($tracking[$i]['status']=='redelivery_attempt_failed'){
                            $status='Redelivery Attempt Failed';
                            $remarks='Cargo was not successfully delivered on the second
                                attempt.';
                        } 
                        else if($tracking[$i]['status']=='delivery_successful'){
                            $status='Delivery Successful';
                            $remarks='Cargo has been successfully delivered to consignee.';
                        }
                        else if($tracking[$i]['status']=='forwarded_to_main'){
                            $status='Forwarded to Main';
                            $remarks='Cargo will be returned to shipper and is heading back from
                                an XDE Branch to the XDE Main Warehouse.';
                        } 
                        else if($tracking[$i]['status']=='failed_delivery_return'){
                            $status='Failed Delivery Return';
                            $remarks='Cargo has reached it’s 3rd delivery attempt or has been
                                cancelled by consignee. The cargo will be prepared to be
                                returned back to shipper.';
                        } 
                        else if($tracking[$i]['status']=='rejected'){
                            $status='Rejected';
                            $remarks='Cargo has been cancelled and was not handed over to
                                XDE.';
                        }  
                        else if($tracking[$i]['status']=='for_disposition'){
                            $status='For Disposition';
                            $remarks='Cargo is being evaluated for disposition';
                        }   
                        else if($tracking[$i]['status']=='claims'){
                            $status='Claims';
                            $remarks='Cargo has not been returned and has been charged for
                                claims';
                        } 
                        else if($tracking[$i]['status']=='out_for_return'){
                            $status='Out for Return';
                            $remarks='Cargo is being shipped back to the shipper/supplier from
                                the XDE Main Warehouse';
                        }  
                        else if($tracking[$i]['status']=='returned'){
                            $status='Returned';
                            $remarks='Cargo is returned to shipper/supplier. Closing status for any
                                cancelled delivery.';
                        }  
                        else if($tracking[$i]['status']=='pod_returned'){
                            $status='POD Returned';
                            $remarks='Cargo’s signed proof of delivery has been returned to XDE
                                office. Closing status for all successful delivery';
                        } 
                        else if($tracking[$i]['status']=='cancelled_order'){
                            $status='Order Cancelled';
                            $remarks='Order Cancelled';
                        }
                        
                        $update = SalesHeader::whereId((int) $tran->id)->update([
                            'delivery_status' => $status
                        ]);
                    }
                }   
            }
            
        }

        $deliveryStatus = $request->get('del_status', false);
        $startDate = $request->get('startdate', false);
        $endDate = $request->get('enddate', false);
        $customer = $request->get('customer_filter', false);

        $sales = SalesHeader::where('id','>','0');
        if(isset($_GET['startdate']) && $_GET['startdate']<>'')
            $sales = $sales->where('created_at','>=',$_GET['startdate']);
        if(isset($_GET['enddate']) && $_GET['enddate']<>'')
            $sales = $sales->where('created_at','<=',$_GET['enddate'].' 23:59:59');
        if(isset($_GET['search']) && $_GET['search']<>'')
            $sales = $sales->where('order_number','like','%'.$_GET['search'].'%');
        if(isset($_GET['customer_filter']) && $_GET['customer_filter']<>'')
            $sales = $sales->where('customer_name','like','%'.$_GET['customer_filter'].'%');
        if(isset($_GET['del_status']) && $_GET['del_status']<>'')
            $sales = $sales->where('delivery_status','like',''.$_GET['del_status'].'');
        $sales = $sales->orderBy('id','desc');
        $sales = $sales->paginate(100);

        $filter = $listing->get_filter($this->searchFields);
        $searchType = 'simple_search';

        return view('admin.ecommerce.sales.index',compact('sales','filter','searchType', 'deliveryStatus', 'startDate', 'endDate', 'customer'));

    }

    public function store(Request $request)
    {
        //
    }

    public function destroy(Request $request)
    {
        $sale = SalesHeader::findOrFail($request->id_delete);
        $sale->update(['status' => 'CANCELLED', 'delivery_status' => 'CANCELLED']);

        return back()->with('success','Successfully deleted transaction');
    }

    public function update(Request $request)
    {

        $save = SalesPayment::create([
            'sales_header_id' => $request->id,
            'payment_type' => $request->payment_type,
            'amount' => $request->amount,
            'status'  => (isset($request->status) ? 'PAID' : 'UNPAID'),
            'payment_date'  => $request->payment_date,
            'receipt_number'  => $request->receipt_number,
            'created_by' => Auth::id()
        ]);

        $sales = SalesHeader::where('id',$request->id)->first();
        $totalPayment = SalesPayment::where('sales_header_id',$request->id)->sum('amount');
        $total = $totalPayment + $request->amount;
        if($total >= $sales->net_amount)
            $status = 'PAID';
        else $status = 'UNPAID';

        $save = SalesHeader::findOrFail($request->id)->update([
            'payment_status' => $status
        ]);

        return back()->with('success','Successfully updated payment!');
        //return $status;
    }

    public function show($id)
    {
        $sales = SalesHeader::where('id',$id)->first();
        $salesPayments = SalesPayment::where('sales_header_id',$id)->get();
        $salesDetails = SalesDetail::where('sales_header_id',$id)->get();
        $totalPayment = SalesPayment::where('sales_header_id',$id)->sum('amount');
        $totalNet = SalesHeader::where('id',$id)->sum('net_amount');
        if($totalNet <= $totalPayment)
        $status = 'PAID';
        else $status = 'UNPAID';

        return view('admin.ecommerce.sales.view',compact('sales','salesPayments','salesDetails','status'));
    }

    public function print($id)
    {
        $sales = SalesHeader::where('id',$id)->first();
        $salesPayments = SalesPayment::where('sales_header_id',$id)->get();
        $salesDetails = SalesDetail::where('sales_header_id',$id)->get();
        $totalPayment = SalesPayment::where('sales_header_id',$id)->sum('amount');
        $totalNet = SalesHeader::where('id',$id)->sum('net_amount');
        if($totalNet <= $totalPayment)
        $status = 'PAID';
        else $status = 'UNPAID';

        return view('admin.ecommerce.sales.print',compact('sales','salesPayments','salesDetails','status'));
    }

    public function quick_update(Request $request)
    {
        $update = SalesHeader::findOrFail($request->pages)->update([
            'delivery_status' => $request->status
        ]);

        $order = SalesHeader::findOrFail($request->pages);

        return back()->with('success','Successfully updated delivery status!');

    }

    public function delivery_status(Request $request)
    {
        $sales = explode(",", $request->del_id);
        foreach($sales as $sale){
            logger($sale);
            $update = SalesHeader::whereId((int) $sale)->update([
                'delivery_status' => $request->delivery_status
            ]);

            $update_delivery_table = DeliveryStatus::create([
                'order_id' => $sale,
                'user_id' => Auth::id(),
                'status' => $request->delivery_status,
                'remarks' => $request->del_remarks
            ]);

            if($request->delivery_status == 'Delivered'){
                $order = SalesHeader::find($sale);
                $order->update(['payment_status' => 'PAID']);
                SalesPayment::create([
                    'sales_header_id' => $sale,
                    'payment_type' => $order->payment_method == 'ecredit' ? 'EWallet' : 'Cash',
                    'amount' => $order->gross_amount,
                    'status' => 'PAID',
                    'payment_date' => today(),
                    'receipt_number' => Str::random(10),
                    'created_by' => Auth::id()
                ]);
            }
        }

        $order = SalesHeader::findOrFail($request->del_id);
        
        Mail::to($order->customer_email)->send(new DeliveryStatusMail($order, Setting::info()));
        
        //to check update coupon availability
        Coupon::checkCouponAvailability();

        return back()->with('success','Successfully updated delivery status!');

    }

    public function view_payment($id)
    {
        $salesPayments = SalesPayment::where('sales_header_id',$id)->get();
        $totalPayment = SalesPayment::where('sales_header_id',$id)->sum('amount');
        $totalNet = SalesHeader::where('id',$id)->sum('net_amount');
        $remainingPayment = $totalNet - $totalPayment;

        return view('admin.ecommerce.sales.payment',compact('salesPayments','totalPayment','totalNet','remainingPayment'));
    }

    public function cancel_product(Request $request)
    {
        return $request;
    }

    public function display_payments(Request $request){
        $input = $request->all();

        $payments = SalesPayment::where('sales_header_id',$request->id)->get();

        return view('admin.ecommerce.sales.added-payments-result',compact('payments'));
    }

    public function display_delivery(Request $request){

        $input = $request->all();

        $delivery = DeliveryStatus::where('order_id',$request->id)->get();

        return view('admin.ecommerce.sales.delivery_history',compact('delivery'));
    }

}
