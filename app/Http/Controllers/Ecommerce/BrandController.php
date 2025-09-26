<?php

namespace App\Http\Controllers\Ecommerce;

use App\Models\{Brand, BrandProductCategory, BrandChildCategory, ChildCategory};
use App\Models\Ecommerce\ProductCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Facades\App\Helpers\ListingHelper;

use Illuminate\Support\Facades\Validator;
use Storage;
use Auth;

class BrandController extends Controller
{
     private $searchFields = ['name'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listing = ListingHelper::required_condition('id', '<>', 87);
        $brands = $listing->simple_search(Brand::class, $this->searchFields);

        // Simple search init data
        $filter = ListingHelper::get_filter($this->searchFields);
        $searchType = 'simple_search';

        return view('admin.ecommerce.brands.index',compact('brands', 'filter', 'searchType'));
    }

    public function menu_order()
    {
        $brands = Brand::where('status','Active')->orderBy('menu_order_no', 'ASC')->get();

        return view('admin.ecommerce.brands.menu-order',compact('brands'));
    }

    public function update_nestable_menu(Request $request)
    {
        $arr_data = json_decode($request->nestable_output,true);
        
        Brand::whereNotNull('id')->update(['menu_order_no' => 0]);

        BrandChildCategory::whereNotNull('id')->delete();
        ChildCategory::whereNotNull('id')->delete();

        foreach($arr_data as $key => $data){
            $orderNo = $key+1;

            if($data['type'] == 'brand'){
                Brand::find($data['id'])->update(['menu_order_no' => $orderNo]);

                foreach($data['children'] as $index => $children){
                    BrandChildCategory::create([
                        'brand_id' => $data['id'],
                        'category_id' => $children['id'],
                        'order_no' => 0
                    ]);

                    $this->store_and_update_menu_links($children, $index, $children['id']);
                }
            }
        }

        return back()->with('success', 'Brand menu order has been updated.');
    }   

    

    public function store_and_update_menu_links($children, $index, $parentId)
    {
        if($parentId == $children['id']){

        } else {
            ChildCategory::create([
                'parent_category_id' => $parentId,
                'category_id' => $children['id'],
            ]); 
        }
        

        if ($this->has_sub_categories($children)) {    
        

            foreach ($children['children'] as $subIndex => $subCategory) {
                $this->store_and_update_menu_links($subCategory, $subIndex, $children['id']);
            }
        }
    }


    public function has_sub_categories($children)
    {
        return isset($children['children']);
    }

    public function reorder_brand(Request $request)
    {
        $requestData = $request->all();

        foreach($requestData['order_no'] as $key => $brandId){
            Brand::find($brandId)->update([
                'menu_order_no' => $key
            ]);
        }

        return back()->with('success', 'Brand menu sequence has been updated.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = ProductCategory::orderBy('name', 'asc')->get();

        return view('admin.ecommerce.brands.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(),[
            'name' => 'required|string|unique:brands,name',
            'description' => 'required',
            'categories' => 'required',
            'image_url' => 'required|mimes:png,jpg,jpeg|max:1000|dimensions:width=400,height=300'
        ])->validate();

        $requestData = $request->all();
        $requestData['user_id'] = Auth::id();
        $requestData['status'] = (isset($request->status) ? 'Active' : 'Inactive');

        $brand = Brand::create($requestData);
        $this->upload_logo($request, $brand->id);



        $categories = BrandChildCategory::where('brand_id', $brand->id)->pluck('category_id')->toArray();
        $childCategories = ChildCategory::whereIn('parent_category_id', $categories)->pluck('category_id')->toArray();
        BrandProductCategory::where('brand_id', $brand->id)->delete();

        $arr_selected_categories = [];
        foreach($request->categories as $cat){
            if(isset($cat)){
                array_push($arr_selected_categories, $cat);

                BrandProductCategory::create([
                    'brand_id' => $brand->id,
                    'product_category_id' => $cat
                ]);


                if(!in_array($cat, $categories) && !in_array($cat, $childCategories)){
                    BrandChildCategory::create([
                        'brand_id' => $brand->id,
                        'category_id' => $cat,
                        'order_no' => 0
                    ]);
                }
            }
        }

        return redirect(route('brands.index'))->with('success', __('standard.brands.create_success'));
    }

    public function upload_logo($request, $brandId)
    {
        if($request->hasFile('image_url')){
            $file = $request->file('image_url');
            $filename = time().'_'.$file->getClientOriginalName();

            $path = Storage::disk('public')->putFileAs('brands', $file, $filename);
            $url = Storage::disk('public')->url($path);

            Brand::find($brandId)->update([
                'image_url' => $url,
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {   
        $categories = ProductCategory::orderBy('name', 'asc')->get();

        return view('admin.ecommerce.brands.edit', compact('brand', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand $brand)
    {
        Validator::make($request->all(),[
            'name' => 'required|string',
            'description' => 'required',
            'categories' => 'required',
            'image_url' => 'mimes:png,jpg,jpeg|max:1000|dimensions:width=400,height=300'
        ])->validate();

        $requestData = $request->all();
        $requestData['user_id'] = Auth::id();
        $requestData['status'] = (isset($request->status) ? 'Active' : 'Inactive');
        $requestData['updated_at'] = now();

        $brand->update($requestData);

        $this->upload_logo($request, $brand->id);


        BrandProductCategory::where('brand_id', $brand->id)->delete();
        BrandChildCategory::where('brand_id', $brand->id)->delete();


        foreach($request->categories as $cat){
            if(isset($cat)){
                BrandProductCategory::create([
                    'brand_id' => $brand->id,
                    'product_category_id' => $cat
                ]);

                BrandChildCategory::create([
                    'brand_id' => $brand->id,
                    'category_id' => $cat,
                    'order_no' => 0
                ]);
            }
        }

        $brandChildCategories = BrandChildCategory::where('brand_id', $brand->id)->pluck('category_id')->toArray();
        foreach($brandChildCategories as $brand_child_category){
            ChildCategory::whereIn('parent_category_id', $brandChildCategories)->delete();
        }

        return redirect(route('brands.index'))->with('success', __('standard.brands.update_detail_success'));
    }

    public function delete_sub_categories($children, $index, $parentId)
    {        

        $arr_categories = [];
        $cat = ChildCategory::find($children->id);
        array_push($arr_categories, $cat->id);


        if($children->subcategory()){    
        
            foreach ($children->subcategory() as $subIndex => $subCategory) {
                $this->delete_child_categories($subCategory, $subIndex, $children->category_id);
            }
        }

        ChildCategory::whereIn('id', $arr_categories)->delete();
    }


    // public function delete_sub_categories($children, $index, $parentId)
    // {
    //     $arr_categories = [];
    //     $cat = ChildCategory::find($children->id);
    //     array_push($arr_categories, $cat->id);

    //     if (count($children->subcategory())) {    

    //         foreach ($children->subcategory() as $subIndex => $subCategory) {
    //             $this->delete_sub_categories($subCategory, $subIndex, $children->category_id);
    //         }
    //     }


    //     ChildCategory::whereIn('id', $arr_categories)->delete();
    // }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        //
    }

    public function update_status($id,$status)
    {
        Brand::find($id)->update([
            'status' => $status,
            'user_id' => Auth::id()
        ]);

        return back()->with('success', __('standard.brands.update_success', ['STATUS' => $status]));
    }

    public function single_delete(Request $request)
    {
        $brand = Brand::findOrFail($request->categories);
        $brand->update([ 'user_id' => Auth::id() ]);

        foreach($brand->child_categories as $child_category){

            foreach($child_category->child_categories() as $cat){
                if(count($cat->subcategory)){
                    $cat->subcategory()->delete();
                }
                
            }

            $child_category->child_categories()->delete();
            $child_category->delete();
        }

        $brand->delete();

        return back()->with('success', __('standard.brands.single_delete_success'));

    }

    public function restore($brand){
        Brand::withTrashed()->find($brand)->update(['user_id' => Auth::id() ]);
        Brand::whereId((int) $brand)->restore();

        return back()->with('success', __('standard.brands.restore_success'));
    }

    public function multiple_change_status(Request $request)
    {
        $brands = explode("|", $request->categories);

        foreach ($brands as $brand) {
            Brand::where('status', '!=', $request->status)->whereId((int) $brand)->update([
                'status'  => $request->status,
                'user_id' => Auth::id()
            ]);
        }

        return back()->with('success',  __('standard.brands.update_success', ['STATUS' => $request->status]));
    }

    public function multiple_delete(Request $request)
    {
        $brands = explode("|",$request->categories);

        foreach($brands as $brand){
            Brand::whereId((int) $brand)->update(['user_id' => Auth::id() ]);

            foreach($brand->child_categories as $child_category){

                foreach($child_category->child_categories() as $cat){
                    if(count($cat->subcategory)){
                        $cat->subcategory()->delete();
                    }
                    
                }
                
                $child_category->child_categories()->delete();
                $child_category->delete();
            }

            Brand::whereId((int) $brand)->delete();
        }

        return back()->with('success', __('standard.brands.multiple_delete_success'));
    }
}
