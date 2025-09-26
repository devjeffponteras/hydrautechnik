<?php

namespace App\Http\Controllers\Ecommerce;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Facades\App\Helpers\ListingHelper;
use App\Helpers\ModelHelper;

use App\Models\Ecommerce\{InventoryReceiverHeader, InventoryReceiverDetail, Product
};

use App\Models\{
    Permission, User
};

use Response;
use Auth;

class InventoryReceiverHeaderController extends Controller
{
    private $searchFields = ['id'];

    public function __construct()
    {
        Permission::module_init($this, 'inventory');
    }

    public function index()
    {
        $lists = ListingHelper::simple_search(InventoryReceiverHeader::class, $this->searchFields);

        // Simple search init data
        $filter = ListingHelper::get_filter($this->searchFields);
        $searchType = 'simple_search';

        return view('admin.ecommerce.inventory.index',compact('lists','filter', 'searchType'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(InventoryReceiverHeader $inventoryReceiverHeader)
    {
        //
    }

    public function edit(InventoryReceiverHeader $inventoryReceiverHeader)
    {
        //
    }

    public function update(Request $request, InventoryReceiverHeader $inventoryReceiverHeader)
    {
        //
    }

    public function post($id)
    {
        $update = InventoryReceiverHeader::whereId((int) $id)->update([
            'posted_at' => date('Y-m-d H:i:s'),
            'posted_by' => Auth::id(),
            'status' => 'POSTED'
        ]);
        
        return back()->with('success','Successfully posted inventory');
    }
    public function cancel($id)
    {
        $update = InventoryReceiverHeader::whereId((int) $id)->update([
                'cancelled_at' => date('Y-m-d H:i:s'),
                'cancelled_by' => Auth::id(),
                'status' => 'CANCELLED'
            ]);
        return back()->with('success','Successfully cancelled inventory');
    }
    public function view($id)
    {
        $data = InventoryReceiverDetail::where('header_id',$id)->get();
        return view('admin.ecommerce.inventory.view',compact('data'));
    }

    public function destroy(InventoryReceiverHeader $inventoryReceiverHeader)
    {
        //
    }

    public function upload_template(Request $request)
    {

        $csv = array();

        if(($handle = fopen($request->csv, 'r')) !== FALSE) {
            // necessary if a large csv file
            set_time_limit(0);

            $row = 0;
            $header = InventoryReceiverHeader::create([
                'user_id' => Auth::id(),
                'status' => 'SAVED'
            ]);

            while(($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                $row++;
                // number of fields in the csv
                $col_count = count($data);
                if($row > 1){
                    if($data[5] <> 0){
                        $insert = InventoryReceiverDetail::create([
                            'product_id' => $data[0],
                            'inventory' => $data[5],
                            'header_id' => $header->id
                        ]);
                    }
                }


            }
            fclose($handle);
        }

        return back()->with('success','Successfully saved new inventory record');

    }

    public function download_template()
    {
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=inventory.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $products = Product::all();
        $columns = array('DB ID', 'Code', 'Name', 'Current Qty', 'Reorder Qty', 'Add Inventory', 'Price');

        $callback = function() use ($products, $columns)
        {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach($products as $p) {
                fputcsv($file, array($p->id, $p->code, $p->name, $p->inventoryActual,$p->reorder_point, '0', $p->price));
            }
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
    }
}
