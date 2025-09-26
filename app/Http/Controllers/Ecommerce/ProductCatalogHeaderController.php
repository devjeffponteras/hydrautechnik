<?php

namespace App\Http\Controllers\Ecommerce;
use App\Http\Controllers\Controller;
use App\Helpers\ListingHelper;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Ecommerce\{
    ProductCatalogHeader, ProductCatalogDetail, Coupon, Product, ProductCategory, Deliverablecities
};
use App\Models\{User, Brand};

class ProductCatalogHeaderController extends Controller
{
    private $searchFields = ['name'];

    public function index()
    {
        $listing = new ListingHelper('desc', 10, 'updated_at');

        $product_catalog_headers = $listing->simple_search(ProductCatalogHeader::class, $this->searchFields);

        // Simple search init data
        $filter = $listing->get_filter($this->searchFields);
        $searchType = 'simple_search';

        return view('admin.ecommerce.product-catalog.index',compact('product_catalog_headers', 'filter', 'searchType'));
    }

    
    public function create()
    {
        $products = Product::where('status','PUBLISHED')->get();
        $categories =  ProductCategory::has('published_products')->where('status','PUBLISHED')->get();

        return view('admin.ecommerce.product-catalog.create',compact('products','categories'));
    }

    public function store(Request $request){

        Validator::make($request->all(), [
            'name' => 'required|max:150',
            'product_name' => 'nullable',
            'product_category' => 'nullable'
        ])->validate();


        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;
        
        if($request->product_name){

            $catalog = ProductCatalogHeader::create($data);

            foreach ($request->product_name as $product_id) {
                ProductCatalogDetail::create([
                    'product_catalog_header_id' => $catalog->id,
                    'product_id' => $product_id
                ]);
            }
        }
        else{

            $data['category_id'] = '';
            $count = count($request->product_category);
            $current = 0;

            foreach ($request->product_category as $category_id) {
                $data['category_id'] .= $category_id;
                if (++$current < $count) {
                    $data['category_id'] .= ',';
                }
            }

            $data['is_category'] = 1;

            ProductCatalogHeader::create($data);
        }

        return redirect(route('product-catalog.index'))->with('success','Catalog has been added.');
    }
    
    public function edit($id)
{
        $catalog = ProductCatalogHeader::findOrFail($id);
        $catalog_details = ProductCatalogDetail::where('product_catalog_header_id', $catalog->id)->get();

        $products = Product::where('status','PUBLISHED')->get();
        $categories =  ProductCategory::has('published_products')->where('status','PUBLISHED')->get();

        return view('admin.ecommerce.product-catalog.edit',compact('products','categories','catalog', 'catalog_details'));
    }

    public function update_status($id,$status)
    {
        ProductCatalogHeader::find($id)->update([
            'status' => $status
        ]);

        return back()->with('success', 'Catalog status has been changed');
    }

    public function update(Request $request, $id){

        Validator::make($request->all(), [
            'name' => 'required|max:150',
            'product_name' => 'nullable',
            'product_category' => 'nullable'
        ])->validate();


        $data = $request->all();
        $data['status'] = $request->has('status') ? 1 : 0;
        
        //DELETE TO REFRESH
        ProductCatalogDetail::where('product_catalog_header_id', $id)->delete();
        
        if($request->product_name){

            ProductCatalogHeader::where('id', $id)
            ->update([
                'name' => $request->name,
                'is_category' => 0,
                'category_id' => null,
                'status' => $request->has('status') ? 1 : 0
            ]);

            ProductCatalogDetail::where('product_catalog_header_id', $id)->delete();

            foreach ($request->product_name as $product_id) {
                ProductCatalogDetail::create([
                    'product_catalog_header_id' => $id,
                    'product_id' => $product_id
                ]);
            }
        }
        else{
            
            $cat_id = '';
            $count = count($request->product_category);
            $current = 0;

            foreach ($request->product_category as $category_id) {
                $cat_id .= $category_id;
                if (++$current < $count) {
                    $cat_id .= ',';
                }
            }

            ProductCatalogHeader::where('id', $id)
            ->update([
                'name' => $request->name,
                'is_category' => 1,
                'category_id' => $cat_id,
                'status' => $request->has('status') ? 1 : 0
            ]);
        }

        return redirect()->back()->with('success','Catalog has been updated.');
    }

    public function single_delete(Request $request)
    {
        $catalog_header = ProductCatalogHeader::findOrFail($request->coupons);
        $catalog_header->update([ 'status' => 0 ]);
        $catalog_header->delete();

        return back()->with('success', 'Catalog has been deleted');
    }

    public function restore($catalog_header){
        ProductCatalogHeader::withTrashed()->find($catalog_header)->update(['status' => 0]);
        ProductCatalogHeader::whereId((int) $catalog_header)->restore();

        return back()->with('success',  'Catalog has been restored');
    }

    public function multiple_change_status(Request $request)
    {
        $coupons = explode("|", $request->coupons);

        foreach ($coupons as $coupon) {
            $publish = Coupon::where('status', '!=', $request->status)->whereId((int) $coupon)->update([
                'status'  => $request->status,
                'user_id' => Auth::id()
            ]);
        }

        return back()->with('success',  __('standard.coupons.multiple_status_update_success', ['STATUS' => $request->status]));
    }

    public function multiple_delete(Request $request)
    {
        $coupons = explode("|",$request->coupons);

        foreach($coupons as $coupon){
            Coupon::whereId((int) $coupon)->update(['user_id' => Auth::id() ]);
            Coupon::whereId((int) $coupon)->delete();
        }

        return back()->with('success', __('standard.coupons.multiple_delete_success'));
    }
}
