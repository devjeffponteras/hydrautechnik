<?php

namespace App\Http\Controllers\Ecommerce;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use Facades\App\Helpers\ListingHelper;
use Facades\App\Helpers\FileHelper;

use App\Models\Ecommerce\ProductCategory;
use App\Models\{
    Permission, Page
};

use \Carbon\Carbon;
use Storage;
use Auth;

class ProductCategoryController extends Controller
{
    private $searchFields = ['name'];
    private $folder = 'admin.ecommerce.product-categories';

    public function __construct()
    {
        Permission::module_init($this, 'product_category');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listing = ListingHelper::required_condition('id', '>', 0);
        $categories = $listing->simple_search(ProductCategory::class, $this->searchFields);

        // Simple search init data
        $filter = ListingHelper::get_filter($this->searchFields);
        $searchType = 'simple_search';
        
        $parentCategories = ProductCategory::where('parent_id', 0)->orderBy('menu_order_no', 'asc')->get();

        return view($this->folder.'.index',compact('categories', 'filter', 'searchType', 'parentCategories'));

    }

    public function reorder_category(Request $request)
    {
        $requestData = $request->all();

        foreach($requestData['order_no'] as $key => $catId){
            ProductCategory::find($catId)->update([
                'menu_order_no' => $key
            ]);
        }

        return back()->with('success', 'Product Category menu sequence has been updated.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parentCategories = ProductCategory::where('parent_id', 0)->get();

        return view($this->folder.'.create', compact('parentCategories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'category_id' => 'nullable',
            'mobile_file_url' => 'nullable',
            'banner_url' => 'nullable',
            'name' => 'required',
        ])->validate();

        $requestData = $request->all();
        $requestData['mobile_file_url'] = $request->hasFile('mobile_file_url') ? FileHelper::move_to_product_file_folder($request->file('mobile_file_url'), 'storage/product_category')['url'] : null;
        $requestData['banner_url'] = $request->hasFile('banner_url') ? FileHelper::move_to_product_file_folder($request->file('banner_url'), 'storage/product_category/banners')['url'] : null;
        $requestData['status'] = isset($request->visibility) ? 'PUBLISHED' : 'PRIVATE';
        $requestData['created_by'] = Auth::id();

        $category = ProductCategory::create($requestData);

        return redirect()->route('product-categories.index')->with('success', __('standard.products.category.create_category_success'));
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
        $category = ProductCategory::findOrFail($id);

        $productCategories = ProductCategory::where('id','<>', $category->id)->orderBy('name','asc')->get();

        return view('admin.ecommerce.product-categories.edit',compact('category', 'productCategories'));
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
        Validator::make($request->all(), [
            'category_id' => 'nullable',
            'mobile_file_url' => 'nullable',
            'banner_url' => 'nullable',
            'name' => 'required',
        ])->validate();

        $productCategory = ProductCategory::findOrFail($id);
        // $this->upload_logo($request, $id);

        if($productCategory->name == $request->name){
            $slug = $productCategory->slug;
        }
        else{
            $slug = Page::convert_to_slug($request->name);
        }
        
        
        //FOR MOBILE FILE UPDATE VALUE
        $updateData['mobile_file_url'] = null;

        $current_mobile_file = explode('/', $request->current_mobile_file)[1] ?? '';
        if($request->hasFile('mobile_file_url')){
            $updateData['mobile_file_url'] = FileHelper::move_to_product_file_folder($request->file('mobile_file_url'), 'storage/product_category')['url'];
        }
        else{
            if($current_mobile_file){
                $updateData['mobile_file_url'] = $productCategory->mobile_file_url;
            }
            else{
                $updateData['mobile_file_url'] = null;
            }
        }
        
        //FOR BANNER FILE UPDATE VALUE
        $updateData['banner_url'] = null;

        $current_banner_file = explode('/', $request->current_banner_file)[1] ?? '';
        if($request->hasFile('banner_url')){
            $updateData['banner_url'] = FileHelper::move_to_product_file_folder($request->file('banner_url'), 'storage/product_category')['url'];
        }
        else{
            if($current_banner_file){
                $updateData['banner_url'] = $productCategory->banner_url;
            }
            else{
                $updateData['banner_url'] = null;
            }
        }

        $productCategory->update([
            'name' => $request->name,
            'parent_id' => $request->parent_page,
            'slug' => $slug,
            'status' => (isset($request->visibility) ? 'PUBLISHED' : 'PRIVATE'),
            'mobile_file_url' => $updateData['mobile_file_url'],
            'banner_url' => $updateData['banner_url'],
            'description' => $request->description,
            'created_by' => auth()->user()->id
        ]);

        return redirect()->back()->with('success', __('standard.products.category.update_success'));
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

    public function restore($category){
        ProductCategory::withTrashed()->find($category)->update(['created_by' => Auth::id() ]);
        ProductCategory::whereId((int) $category)->restore();

        return back()->with('success', __('standard.products.category.restore_category_success'));
    }

    public function get_slug(Request $request)
    {
        return Page::convert_to_slug($request->url, $request->parentPage);
    }

    public function single_delete(Request $request)
    {
        $category = ProductCategory::findOrFail($request->categories);
        $category->update([ 'created_by' => Auth::id() ]);
        $category->delete();

        return back()->with('success', __('standard.products.category.single_delete_success'));

    }

    public function multiple_delete(Request $request)
    {
        $categories = explode("|",$request->categories);

        foreach($categories as $category){
            ProductCategory::whereId((int) $category)->update(['created_by' => Auth::id() ]);
            ProductCategory::whereId((int) $category)->delete();
        }

        return back()->with('success', __('standard.products.category.multiple_delete_success'));
    }

    public function update_status($id,$status)
    {
        ProductCategory::where('id',$id)->update([
            'status' => $status,
            'created_by' => Auth::id()
        ]);

        return back()->with('success', __('standard.products.category.category_update_success', ['STATUS' => $status]));
    }

    public function multiple_change_status(Request $request)
    {
        $categories = explode("|", $request->categories);

        foreach ($categories as $category) {
            $publish = ProductCategory::where('status', '!=', $request->status)->whereId((int) $category)->update([
                'status'  => $request->status,
                'created_by' => Auth::id()
            ]);
        }

        return back()->with('success',  __('standard.products.category.category_update_success', ['STATUS' => $request->status]));
    }
}
