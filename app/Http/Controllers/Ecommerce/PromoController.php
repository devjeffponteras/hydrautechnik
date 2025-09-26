<?php

namespace App\Http\Controllers\Ecommerce;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use Facades\App\Helpers\ListingHelper;

use App\Models\Ecommerce\{
    ProductCategory, Product, PromoProducts, Promo 
};

use Auth;
use DB;

class PromoController extends Controller
{
    private $searchFields = ['name'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $listing = ListingHelper::required_condition('is_expire', '=', 0);
        $promos  = $listing->simple_search(Promo::class, $this->searchFields);

        // Simple search init data
        $filter = ListingHelper::get_filter($this->searchFields);
        $searchType = 'simple_search';

        return view('admin.ecommerce.promos.index',compact('promos', 'filter', 'searchType'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = ProductCategory::where('status','PUBLISHED')->orderBy('name','asc')->get();

        return view('admin.ecommerce.promos.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(
            $request,[
                'name' => 'required|max:150|unique:promos,name',
                'promotion_dt' => 'required',
                'applicable_product_type' => 'required',
                'discount' => 'nullable'
            ],
            [
                'name.unique' => 'This promo is already in the list.',
            ]  
        );

        $data = $request->all();
        $date = explode(' - ',$request->promotion_dt);
        $prodId = $data['productid'];

        $promo = Promo::create([
            'name' => $request->name,
            'promo_start' => $date[0].':00.000',
            'promo_end' => $date[1].':00.000',
            'discount' => $request->discount,
            'applicable_product_type' => $request->applicable_product_type,
            'status' => ($request->has('status') ? 'ACTIVE' : 'INACTIVE'),
            'is_expire' => 0,
            'type' => $request->type,
            'user_id' => Auth::id()
        ]);

        if($promo){
            foreach($prodId as $key => $id){
                $product = Product::find($id);
                PromoProducts::create([
                    'promo_id' => $promo->id,
                    'product_id' => $id,
                    'user_id' => Auth::id()
                ]); 
            } 
        }
        
        return redirect()->route('promos.index')->with('success', __('standard.promos.create_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = ProductCategory::where('status','PUBLISHED')->orderBy('name','asc')->get();
        $promo = Promo::find($id);

        return view('admin.ecommerce.promos.edit',compact('categories','promo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();

        $date = explode(' - ',$request->promotion_dt);

        $promo = Promo::find($id)->update([
            'name' => $request->name,
            'promo_start' => $date[0].':00.000',
            'promo_end' => $date[1].':00.000',
            'discount' => $request->discount,
            'applicable_product_type' => $request->applicable_product_type,
            'status' => ($request->has('status') ? 'ACTIVE' : 'INACTIVE'),
            'type' => $request->type,
            'user_id' => Auth::id()
        ]);
        
        if($promo){

            $arr_promoproducts = [];
            $promo_products = PromoProducts::where('promo_id',$id)->get();

            foreach($promo_products as $p){
                array_push($arr_promoproducts,$p->product_id);
            }

            $selected_products = $data['productid'];
            // save new promotional products
            foreach($selected_products as $key => $prod){
                if(!in_array($prod,$arr_promoproducts)){
                    $product = Product::find($prod);
                    PromoProducts::create([
                        'promo_id' => $id,
                        'product_id' => $prod,
                        'cost' => $product->price,
                        'user_id' => Auth::id()
                    ]);
                }
            }

            // delete existing promotional product that is not selected
            $arr_selectedproducts = [];
            foreach($selected_products as $key => $sprod){
                array_push($arr_selectedproducts,$sprod);
            }

            foreach($promo_products as $product){
                if(!in_array($product->product_id,$arr_selectedproducts)){
                    PromoProducts::where('promo_id',$id)->where('product_id',$product->product_id)->delete();
                }
            }
        }

        return back()->with('success', __('standard.promos.promo_update_details_success'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function update_status($id,$status)
    {
        Promo::find($id)->update([
            'status' => $status,
            'user_id' => Auth::id()
        ]);

        return back()->with('success', __('standard.promos.promo_update_success', ['STATUS' => $status]));
    }

    public function single_delete(Request $request)
    {
        $promo = Promo::findOrFail($request->promos);
        $promo->update([
            'status' => 'INACTIVE',
            'user' => Auth::id()
        ]);
        $promo->delete();

        return back()->with('success', __('standard.promos.single_delete_success'));

    }

    public function multiple_change_status(Request $request)
    {
        $promos = explode("|", $request->promos);

        foreach ($promos as $promo) {
            $publish = Promo::where('status', '!=', $request->status)->whereId((int) $promo)->update([
                'status'  => $request->status,
                'user_id' => Auth::id()
            ]);
        }

        return back()->with('success',  __('standard.promos.promo_update_success', ['STATUS' => $request->status]));
    }

    public function multiple_delete(Request $request)
    {
        $promos = explode("|",$request->promos);

        foreach($promos as $promo){
            Promo::whereId((int) $promo)->update([
                'status' => 'INACTIVE',
                'user_id' => Auth::id()
            ]);
            Promo::whereId((int) $promo)->delete();
        }

        return back()->with('success', __('standard.promos.multiple_delete_success'));
    }

    public function restore($promo){
        Promo::withTrashed()->find($promo)->update(['status' => 'INACTIVE','user_id' => Auth::id() ]);
        Promo::whereId((int) $promo)->restore();

        return back()->with('success', __('standard.promos.restore_promo_success'));
    }
}
