<?php


namespace App\Helpers;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client;
use GuzzleXml\XmlMiddleware;

use App\Models\Ecommerce\SalesHeader;
use App\Helpers\Setting;

class LBCHelper
{

    public static function send_pickup_request($headerId, $commonRef, $customer)
    {
        try {

            $salesHeader = SalesHeader::find($headerId);
            
            $stack = HandlerStack::create();
            $stack->push(XmlMiddleware::xml(), 'xml');

            $client = new Client([
                'handler' => $stack,
                'headers' => [
                    "Ocp-Apim-Subscription-Key" => "de235fe4dff247569889001fe72ad468"
                ]
            ]);

            $URI = 'https://lbcapigateway.lbcapps.com/lbcpickuprequest/v1/api/DirectInjection/InsertPickupRequest';

            $response = $client->post($URI, [
                'xml' => [
                    'PickupRequestEntity' => [
                        'AppKey' => env('APP_KEY'),
                        'ShipmentMode' => 1,
                        'Origin' => $commonRef['Origin'],
                        'TrackingNo' => str_replace('-', '', $salesHeader->order_number),
                        'TransactionDate' => Setting::datetimeFormat($salesHeader->created_at), //'10/23/2020 11:28:47'
                        'ODZ' => false,
                        'ShipperAccountNo' => '2015062300003',
                        'Shipper' => 'TESTCOMPANY',
                        'ShipperStBldg' => 'TEST manufacturing industry inc bldg, warehouse department',
                        'ShipperBrgy' => 'barangay san antonio',
                        'ShipperCityMuncipality' => 'metro manila',
                        'ShipperProvince' => 'Metro Manila',
                        'ShipperContactNumber' => '0288256961',
                        'ShipperSendSMS' => 0,
                        'ShipperMobileNumber' => '3031234',
                        'ProductLine' => 2,
                        'ServiceMode' => 8,
                        'CODAmount' => 0,
                        'PreferredDate' => Setting::datetimeFormat($commonRef['PreferredDate']), //'07/26/2022 10:10:10',
                        'Consignee' => $salesHeader->customer_name,
                        'ConsigneeStBldg' => $commonRef['ConsigneeStBldg'],
                        'ConsigneeBrgy' => $commonRef['ConsigneeBrgy'],
                        'ConsigneeCityMuncipality' => $commonRef['ConsigneeCityMuncipality'],
                        'ConsigneeProvince' => $commonRef['ConsigneeProvince'],
                        'ConsigneeContactNumber' => $commonRef['ConsigneeContactNumber'],
                        'ConsigneeSendSMS' => 1,
                        'ConsigneeMobileNumber' => $commonRef['ConsigneeContactNumber'],
                        'Quantity' => 1,
                        'PKG' => 16,
                        'ACTWTkgs' => 0.613,
                        'LengthCM' => 0,
                        'WidthCM' => 0,
                        'HeightCM' => 0,
                        'VolWTcbm' => 0,
                        'CBM' => 0,
                        'ChargeableWT' => 0,
                        'DeclaredValue' => 1,
                        'Description' => 'Fashion Apparel',
                        'Commodity' => 0,
                        'ForCrating' => 0,
                        'AttachmentNameOne' => 'ORDERNO',
                        'ReferenceNoOne' => $salesHeader->order_number,
                        'AttachmentNameTwo' => 'TESTMODE',
                        'ReferenceNoTwo' => 'REGULAR',
                        'AttachmentNameThree' => 'TESTSLS',
                        'ReferenceNoThree' => '123456789',
                        'AttachmentNameFour' => 'STATUS',
                        'ReferenceNoFour' => 0,
                        'DestinationCode' => $commonRef['Origin'],
                        'Client' => 'TEST',
                    ],
                ],
            ]);

            $xml = $response->getBody();
            $xml = simplexml_load_string($xml);
            $json = json_encode($xml);
            $array = json_decode($json,TRUE);

            return $array;

        } catch (\GuzzleHttp\Exception\RequestException $ex) {
            \Log::debug('error');
            \Log::debug((string) $ex->getResponse()->getBody());
            throw $ex;
        }
    }

    public static function provinces()
    {
        try {
            $client = new Client([
                'headers' => [
                    'Cache-Control' => 'no-cache',
                    'lbcOAkey' => 'db4327b32c7c4a949a75be90dc914fd5',
                ]
            ]);
            
            $URI = 'https://lbcapigateway.lbcapps.com/lbccommonreference/v2/v2/RequestRefFirstMileProvince?token=TEJDdmlzdHJhcGhfdXNlclYxc3RSQHBoMjAxNw==';
            $URI_Response = $client->request('GET',$URI);
            
            $URI_Response = json_decode($URI_Response->getBody(), true);


            return $URI_Response['Province'];

        } catch (\GuzzleHttp\Exception\RequestException $ex) {
            \Log::debug('error');
            \Log::debug((string) $ex->getResponse()->getBody());
            throw $ex;
        }     
    }

    public static function cities($provinceId)
    {
        try {
            $client = new Client([
                'headers' => [
                    'Cache-Control' => 'no-cache',
                    'lbcOAkey' => 'db4327b32c7c4a949a75be90dc914fd5',
                ]
            ]);
            
            $URI = 'https://lbcapigateway.lbcapps.com/lbccommonreference/v2/v2/RequestRefFirstMileCity?token=TEJDdmlzdHJhcGhfdXNlclYxc3RSQHBoMjAxNw==&provinceid='.$provinceId;
            $URI_Response = $client->request('GET',$URI);
            
            $URI_Response = json_decode($URI_Response->getBody(), true);

            return $URI_Response['City'];

        } catch (\GuzzleHttp\Exception\RequestException $ex) {
            \Log::debug('error');
            \Log::debug((string) $ex->getResponse()->getBody());
            throw $ex;
        }     
    }


    public static function barangay($cityId)
    {
        try {
            $client = new Client([
                'headers' => [
                    'Cache-Control' => 'no-cache',
                    'lbcOAkey' => 'db4327b32c7c4a949a75be90dc914fd5',
                ]
            ]);
            
            $URI = 'https://lbcapigateway.lbcapps.com/lbccommonreference/v2/v2/RequestRefFirstMileBarangay?token=TEJDdmlzdHJhcGhfdXNlclYxc3RSQHBoMjAxNw==&cityid='.$cityId;
            $URI_Response = $client->request('GET',$URI);
            
            $URI_Response = json_decode($URI_Response->getBody(), true);

            return $URI_Response['Barangay'];

        } catch (\GuzzleHttp\Exception\RequestException $ex) {
            \Log::debug('error');
            \Log::debug((string) $ex->getResponse()->getBody());
            throw $ex;
        }     
    }
}
