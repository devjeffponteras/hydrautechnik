<?php

namespace App\Helpers;

use App\Models\Ecommerce\SalesHeader;
use App\Models\Ecommerce\SalesDetail;
use App\Models\Ecommerce\Product;

use GuzzleHttp\Client;

class XDEHelper
{
    
    public static function send_pickup_request($salesId, $customer)
    {   
        $sales = SalesHeader::find($salesId);
        $totalLength = 10;
        $totalWidth = 10;

        $totalItems = 0;

        $totalWeight = 0;
        $totalHeight = 10;

        $arr_order_items = [];
        foreach($sales->items as $key => $item){
            $totalItems++;

            $product = Product::find($item->product_id);
            $totalWeight+=$product->weight;
            $arr_order_items [] = [
                "reference" => $sales->order_number.'-'.$item->product_id,
                "description" => $item->product_name,
                "quantity" => (int) $item->qty,
                "uom" => $item->uom,
                "length" => 10,
                "width" => 10,
                "height" => 10,
                "weight" => $product->weight,
                "volume" => 10,
                "value" => $item->price,
                "type" => "Standard",
                "remarks" => "Additional field for variable instruction."

            ];
        }

        try {
            $client = new \GuzzleHttp\Client([
                'headers' => [
                    'apikey' => '76CA4F6A6EA724EA9223446A1C692C83',
                    'token' => '446C717B064C913E084A8CD962E0DAD1E24E52904CAEB1724BDF475C065611BF'
                ]
            ]);
            
            
            $URI = 'https://staging-api.xdelogistics.com/v2/pickup';
            $URI_Response = $client->request('POST',$URI,[
                'json' => [[
                        'package' => [
                            "id" => "".$salesId."",
                            "tracking_number" => str_replace('-','',$sales->order_number),
                            "order_no" => str_replace('-','',$sales->order_number),
                            "serial_number" => str_replace('-','',$sales->order_number),
                            "asset_number" => str_replace('-','',$sales->order_number),
                            "payment_type" => $sales->payment_status,
                            "total_price" => $sales->net_amount,
                            "declared_value" => $sales->net_amount,
                            "package_size" => "Bulky",
                            "total_quantity" => $totalItems,
                            "length" => $totalLength,
                            "width"=> $totalWidth,
                            "height" => $totalHeight,
                            "weight" => $totalWeight,
                            "package_type" => "Sales_order",
                            "delivery_type" => "Standard",
                            "shipping_type" => "Local",
                            "journey_type" => "Last Mile",
                            "transport_mode" => "land",
                            "port_code" => "MAIN",
                            "shipment_provider" => "Ximex Delivery Express",
                            "reference_number" => "OPTIONAL-REFERENCE",
                            "remarks" => "Additional field for variable instruction.",
                            "group_id" => str_replace('-','',$sales->order_number),
                            "pickup_date_time" => "",
                        ],
                        'consignee' => [
                            'name' => $sales->customer_name,
                            'mobile_number' => $sales->customer_contact_number,
                            'email_address' =>  $sales->customer_email,
                            'full_address' => ''.$sales->customer_delivery_adress.'',
                            'province' => $sales->customer_delivery_province,
                            'city' => $sales->customer_delivery_city,
                            'barangay' => $sales->customer_delivery_barangay,
                            'building_type' => "",
                            'coordinate' => "",
              
                        ],
                        'merchant' => [

                            'name' => "Exponent Controls and Electrical Corporation",
                            'full_address' => "181-C Sunset Drive, Brookside Hills, Brgy. San Isidro, Cainta, Rizal 1900, Philippines",
                            'mobile_number' => "+639176314525",
                            'email_address' => "ryanolasco@gmail.com",
                            'province' => "Rizal",
                            'city' => "Cainta",
                            'barangay' => "San Isidro",
                        ],
                        'items' => $arr_order_items,
                    
                    
                ]]]);
            
            $URI_Response = json_decode($URI_Response->getBody(), true);
            
            return $URI_Response;

        } catch (\GuzzleHttp\Exception\RequestException $ex) {
            \Log::debug('error');
            \Log::debug((string) $ex->getResponse()->getBody());
            throw $ex;
        }
        
    }
    
    public static function get_delivery_status($tracking_number)
    {   

        try {
            $client = new \GuzzleHttp\Client([
                'headers' => [
                    'apikey' => '76CA4F6A6EA724EA9223446A1C692C83',
                    'token' => '446C717B064C913E084A8CD962E0DAD1E24E52904CAEB1724BDF475C065611BF'
                ]
            ]);
            
            
            $URI = 'https://staging-api.xdelogistics.com/status/'.$tracking_number;
            $URI_Response = $client->request('GET',$URI);
            
            $URI_Response = json_decode($URI_Response->getBody(), true);
            return $URI_Response;

        } catch (\GuzzleHttp\Exception\RequestException $ex) {
            \Log::debug('error');
            \Log::debug((string) $ex->getResponse()->getBody());
            throw $ex;
        }
        
    }

}
