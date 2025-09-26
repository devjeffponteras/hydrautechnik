<?php

namespace App\Http\Controllers\Ecommerce;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Facades\App\Helpers\ListingHelper;
use Facades\App\Helpers\FileHelper;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Log;


use App\Models\Ecommerce\{
    ProductCategory, ProductPhoto, ProductTag, Product, InventoryReceiverHeader, InventoryReceiverDetail, Cart, FormAttribute, ProductAdditionalInfo, EbookCustomer
};

use App\Models\{
    Permission, Page, Brand, BrandProductCategory, User, CustomerLibrary
};

use Response;
use Storage;
use Auth;


class ProductController extends Controller
{
    private $searchFields = ['name'];
    private $advanceSearchFields = ['category_id', 'sku', 'name', 'brand_id', 'short_description', 'description', 'status', 'price1', 'price2', 'user_id', 'updated_at1', 'updated_at2'];

    public function __construct()
    {
        Permission::module_init($this, 'product');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listing = ListingHelper::required_condition('status', '!=', 'UNEDITABLE');
        $products = $listing->simple_search(Product::class, $this->searchFields);

        // Simple search init data
        $filter = ListingHelper::get_filter($this->searchFields);
        $searchType = 'simple_search';

        $advanceSearchData = $listing->get_search_data($this->advanceSearchFields);
        $uniqueProductByCategory = $listing->get_unique_item_by_column(Product::class, 'category_id');

        // $uniqueProductByBrand = $listing->get_unique_item_by_column(Product::class, 'brand_id');
        $brands = Brand::orderBy('name', 'asc')->get();

        $uniqueProductByUser = $listing->get_unique_item_by_column(Product::class, 'created_by');

        return view('admin.ecommerce.products.index',compact('products', 'filter', 'searchType','uniqueProductByCategory','brands','uniqueProductByUser','advanceSearchData'));

    }

    public function advance_index(Request $request)
    {

        $equalQueryFields = ['code', 'category_id', 'status', 'created_by'];

        $listing = ListingHelper::required_condition('status', '!=', 'UNEDITABLE');
        $products = $listing->advance_search('App\Models\Ecommerce\Product', $this->advanceSearchFields, $equalQueryFields);

        $filter = $listing->get_filter($this->searchFields);

        $advanceSearchData = $listing->get_search_data($this->advanceSearchFields);
        $uniqueProductByCategory = $listing->get_unique_item_by_column('App\Models\Ecommerce\Product', 'category_id');

        // $uniqueProductByBrand = $listing->get_unique_item_by_column('App\Models\Ecommerce\Product', 'brand_id');
        $brands = Brand::orderBy('name', 'asc')->get();

        $uniqueProductByUser = $listing->get_unique_item_by_column('App\Models\Ecommerce\Product', 'created_by');

        $searchType = 'advance_search';

        return view('admin.ecommerce.products.index',compact('products', 'filter', 'searchType','uniqueProductByCategory','brands','uniqueProductByUser','advanceSearchData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parentCategories  = ProductCategory::where('parent_id', 0)->orderBy('name','asc')->get();
        $attributes = FormAttribute::orderBy('name', 'asc')->get();
        $products = Product::all();

        return view('admin.ecommerce.products.create',compact('parentCategories', 'attributes', 'products'));
    }

    public function create_bundle()
    {
        $parentCategories  = ProductCategory::where('parent_id', 0)->orderBy('name','asc')->get();
        $attributes = FormAttribute::orderBy('name', 'asc')->get();
        $products = Product::all();
        return view('admin.ecommerce.products.create_bundle',compact('parentCategories', 'attributes', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {

        $requestData = $request->validated();
        $requestData['status'] = $request->has('status') ? 'PUBLISHED' : 'PRIVATE';
        $requestData['is_bundle'] = $request->has('is_bundle');
        $requestData['is_featured'] = $request->has('is_featured');
        $requestData['is_best_seller'] = $request->has('is_best_seller');
        $requestData['is_free'] = $request->has('is_free');
        $requestData['is_premium'] = $request->has('is_premium');
        $requestData['is_preorder'] = $request->has('is_preorder');
        $requestData['description'] = $request->long_description;
        $requestData['created_by'] = Auth::id();


        if($request->has('bundle_products')){
            $bundle_products = "";
            foreach($request->bundle_products as $bundle_product){
                $bundle_products .= $bundle_product.',';
            }
            $requestData['bundle_products'] = $bundle_products;
        }

        $product = Product::create($requestData);

        $this->tags($product->id, $request->tags);

        $newPhotos = $this->set_order(request('photos'));
        $productPhotos = $this->move_product_to_official_folder($product->id, $newPhotos);

        $this->delete_temporary_product_folder();

        foreach ($productPhotos as $key => $photo) {
            ProductPhoto::create([
                'product_id' => $product->id,
                'name' => (empty($photo['name']) ? '' : $photo['name']),
                'description' => '',
                'path' => $photo['image_path'],
                'status' => 'PUBLISHED',
                'is_primary' => ($key == $request->is_primary) ? 1 : 0,
                'created_by' => Auth::id()
            ]);
        }

        $updateData['file_url'] = $request->hasFile('file_url') ? FileHelper::move_to_product_file_folder($request->file('file_url'), 'product_files/epub/' . $product->slug)['url'] : null;

        Product::where('id', $product->id)
        ->update([
            'file_url' => $updateData['file_url']
        ]);

        // if($request->hasProductAttribute > 0){
        //     foreach($requestData['productAttribute'] as $key => $attr){
        //         if($requestData['attributeValue'][$key] != ""){
        //            ProductAdditionalInfo::create([
        //                 'product_id' => $product->id,
        //                 'attribute_name' => $attr,
        //                 'value' => $requestData['attributeValue'][$key]
        //             ]); 
        //         } 
        //     }
        // }        
        
        return redirect()->route('products.index')->with('success', __('standard.products.product.create_success'));

    }

    public function tags($id,$tags)
    {
        foreach(explode(',',$tags) as $tag)
        {
            ProductTag::create([
                'product_id' => $id,
                'tag' => $tag,
                'created_by' => Auth::id()
            ]);
        }
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
        $product = Product::findOrFail($id);
        $parentCategories  = ProductCategory::where('parent_id', 0)->orderBy('name','asc')->get();

        $attributes = FormAttribute::orderBy('name', 'asc')->get();

        return view('admin.ecommerce.products.edit',compact('product','parentCategories', 'attributes'));
    }

    public function edit_bundle($id)
    {
        $product = Product::findOrFail($id);
        $parentCategories  = ProductCategory::where('parent_id', 0)->orderBy('name','asc')->get();
        $products = Product::all();
        $attributes = FormAttribute::orderBy('name', 'asc')->get();

        return view('admin.ecommerce.products.edit_bundle',compact('product','parentCategories','products','attributes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $slug = Page::convert_to_slug($request->name);

        $requestData = $request->all();
        $requestData['slug'] = $slug;
        $requestData['description'] = $request->long_description;
        $requestData['status'] = $request->has('status') ? 'PUBLISHED' : 'PRIVATE';
        $requestData['is_featured'] = $request->has('is_featured');
        $requestData['is_best_seller'] = $request->has('is_best_seller');
        $requestData['is_free'] = $request->has('is_free');
        $requestData['is_premium'] = $request->has('is_premium');
        $requestData['is_preorder'] = $request->has('is_preorder');
        $requestData['created_by'] = Auth::id();

        if($request->has('bundle_products')){
            $bundle_products = "";
            foreach($request->bundle_products as $bundle_product){
                $bundle_products .= $bundle_product.',';
            }
            $requestData['bundle_products'] = $bundle_products;
        }

        $product->update($requestData);
        $this->update_tags($product->id,$request->tags);

        $photos = $this->set_order(request('photos'));

        $this->update_photos($this->get_product_photos($photos));

        $this->remove_photos_from_product(request('remove_photos'));

        $newPhotos = $this->get_new_photos($photos);

        $newPhotos = $this->move_product_to_official_folder($product->id, $newPhotos);

        foreach ($newPhotos as $key => $photo) {
            ProductPhoto::create([
                'product_id' => $product->id,
                'name' => (empty($photo['name']) ? '' : $photo['name']),
                'description' => '',
                'path' => $photo['image_path'],
                'status' => 'PUBLISHED',
                'is_primary' => ($key == $request->is_primary) ? 1 : 0,
                'created_by' => Auth::id()
            ]);
        }


        $current_file = explode('/', $request->current_file)[1] ?? '';
        $is_free = $request->has('is_free');

        if($request->hasFile('file_url')){
            $updateData['file_url'] = FileHelper::move_to_product_file_folder($request->file('file_url'), 'product_files/epub/' . $product->slug)['url'];
        }
        else{
            if($current_file){
                $updateData['file_url'] = $product->file_url;
            }
            else{
                $updateData['file_url'] = null;
                $is_free = 0;
            }
        }


        Product::where('id', $product->id)
        ->update([
            'file_url' => $updateData['file_url'],
            'is_free' => $is_free
        ]);

        // if($request->hasProductAttribute > 0){
        //     foreach($requestData['productAttribute'] as $key => $attr){
        //         $hasInfo = ProductAdditionalInfo::where('product_id', $product->id)->where('attribute_name', $attr);

        //         if($hasInfo->count() > 0){
        //             if($requestData['attributeValue'][$key] == ""){
        //                 $hasInfo->delete();
        //             } else {
        //                 ProductAdditionalInfo::where('product_id', $product->id)->where('attribute_name', $attr)->update([
        //                     'value' => $requestData['attributeValue'][$key]
        //                 ]);   
        //             }
        //        } else {
        //             if($requestData['attributeValue'][$key] != ""){
        //                 ProductAdditionalInfo::create([
        //                     'product_id' => $product->id,
        //                     'attribute_name' => $attr,
        //                     'value' => $requestData['attributeValue'][$key]
        //                 ]);
        //             }
        //        } 
        //     }
        // }

        return redirect()->route($request->has('is_bundle') ? 'product.edit.bundle' : 'products.edit', $product->id)->with('success', __('standard.products.product.update_success'));
    }

    public function update_photos($photos)
    {
        foreach ($photos as $photo) {
            if ($photo) {
                $photo['name'] = ($photo['name']) ? $photo['name'] : '';
                ProductPhoto::find($photo['id'])->update($photo);
            }
        }
    }

    public function update_tags($id,$tags)
    {
        $delete = ProductTag::where('product_id',$id)->forceDelete();

        foreach(explode(',',$tags) as $tag){
            ProductTag::create([
                'product_id' => $id,
                'tag' => $tag,
                'created_by' => Auth::id()
            ]);
        }

        // if($delete){
        //     foreach(explode(',',$tags) as $tag){
        //         ProductTag::create([
        //             'product_id' => $id,
        //             'tag' => $tag,
        //             'created_by' => Auth::id()
        //         ]);
        //     }
        // }

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

    public function get_slug(Request $request)
    {
        return Page::convert_to_slug($request->url, $request->parentPage);
    }

    public function restore($id)
    {
        Product::withTrashed()->find($id)->update(['created_by' => Auth::id() ]);
        Product::whereId((int) $id)->restore();

        return back()->with('success', __('standard.products.product.restore_product_success'));
    }

    public function change_status($id,$status)
    {
        Product::where('id',$id)->update([
            'status' => $status,
            'created_by' => Auth::id()
        ]);

        return back()->with('success', __('standard.products.product.update_status_success', ['STATUS' => $status]));
    }

    public function single_delete(Request $request)
    {
        $product = Product::findOrFail($request->products);
        $product->update([ 'created_by' => Auth::id() ]);
        $product->delete();

        return back()->with('success', __('standard.products.product.single_delete_success'));

    }

    public function multiple_change_status(Request $request)
    {
        $products = explode("|", $request->products);

        foreach ($products as $product) {
            if($product != ""){
                $publish = Product::where('status', '!=', $request->status)->whereId((int) $product)->update([
                    'status'  => $request->status,
                    'created_by' => Auth::id()
                ]);  
            }
            
        }

        return back()->with('success',  __('standard.products.product.change_status_success', ['STATUS' => $request->status]));
    }

    public function multiple_delete(Request $request)
    {
        $products = explode("|",$request->products);

        foreach($products as $product){
            if($product != ""){
                Product::whereId((int) $product)->update(['created_by' => Auth::id() ]);
                Product::whereId((int) $product)->delete();
            }
        }

        return back()->with('success', __('standard.products.product.multiple_delete_success'));
    }

// save files to temporary folder
    public function upload(Request $request)
    {
        if ($request->hasFile('banner')) {

            $newFile = $this->upload_file_to_temporary_storage($request->file('banner'));

            return response()->json([
                'status' => 'success',
                'image_url' => $newFile['url'],
                'image_name' => $newFile['name'],
                'image_path' => $newFile['path'],
            ]);
        }

        return response()->json([
            'status' => 'failed',
            'image_url' => '',
            'image_name' => ''
        ]);
    }

    public function get_product_photos($photos)
    {
        return array_filter($photos, function ($photo) {
            return isset($photo['id']);
        });
    }

    public function get_new_photos($photos)
    {
        return array_filter($photos, function ($photo) {
            return !isset($photo['id']);
        });
    }

    public function remove_photos_from_product($photos)
    {
        ProductPhoto::find($photos ?? [])->each(function ($photo, $key) {
            $imagePath = $this->get_banner_path_in_storage($photo->image_path);
            Storage::disk('public')->delete($imagePath);
            $photo->update(['user_id' => auth()->id()]);
            $photo->delete();

        });
    }

    public function upload_file_to_temporary_storage($file)
    {
        $temporaryFolder = 'temporary_products'.auth()->id();
        $fileName = $file->getClientOriginalName();
        if (Storage::disk('public')->exists($temporaryFolder.'/'.$fileName)) {
            $fileName = $this->make_unique_file_name($temporaryFolder, $fileName);
        }

        $path = Storage::disk('public')->putFileAs($temporaryFolder, $file, $fileName);
        $url = Storage::disk('public')->url($path);

        return [
            'path' => $temporaryFolder.'/'.$fileName,
            'name' => $fileName,
            'url' => $url
        ];
    }
//

// move uploaded product files to official product folder
    public function delete_temporary_product_folder()
    {
        $temporaryFolder = 'temporary_products'.auth()->id();
        Storage::disk('public')->deleteDirectory($temporaryFolder);
    }

    public function set_order($products = [])
    {
        $products = $products ?? [];

        $count = 1;
        foreach($products as $key => $product) {
            $products[$key]['order'] = $count;
            $count += 1;
        }

        return $products;
    }

    public function move_product_to_official_folder($productId, $products)
    {
        foreach ($products as $key => $product) {
            $temporaryPath = $this->get_banner_path_in_storage($products[$key]['image_path']);
            $fileName = $this->get_banner_file_name($products[$key]['image_path']);
            $bannerFolder = '';

            $products[$key]['image_path'] = $this->move_to_products_folder($productId, $temporaryPath, $bannerFolder.$fileName);
        }

        return $products;
    }

    public function get_banner_path_in_storage($path)
    {
        $paths = explode('storage/', $path);

        if (count($paths) == 1) {
            return '';
        }

        return explode('storage/', $path)[1];
    }

    public function get_banner_file_name($path)
    {
        $temporaryFolder = 'temporary_products'.auth()->id();
        return explode($temporaryFolder, $path)[1];
    }

    public function move_to_products_folder($id,$temporaryPath, $fileName)
    {
        $folder = 'products/'.$id;
        if (Storage::disk('public')->exists($folder.$fileName)) {
            $fileName = $this->make_unique_file_name($folder, $fileName);
        }

        $newPath = $folder.$fileName;
        Storage::disk('public')->move($temporaryPath, $newPath);
        return $id.$fileName;
    }

    public function make_unique_file_name($folder, $fileName)
    {
        $fileNames = explode(".", $fileName);
        $count = 2;
        $newFilename = $fileNames[0].' ('.$count.').'.$fileNames[1];
        while(Storage::disk('public')->exists($folder.'/'.$newFilename)) {
            $count += 1;
            $newFilename = $fileNames[0].' ('.$count.').'.$fileNames[1];
        }

        return $newFilename;
    }

    public function upload_file_to_storage($folder, $file, $key = '') {

        $fileName = $file->getClientOriginalName();
        if (Storage::disk('public')->exists($folder.'/'.$fileName)) {
            $fileNames = explode(".", $fileName);
            $count = 2;
            $newFilename = $fileNames[0].' ('.$count.').'.$fileNames[1];
            while(Storage::disk('public')->exists($folder.'/'.$newFilename)) {
                $count += 1;
                $newFilename = $fileNames[0].' ('.$count.').'.$fileNames[1];
            }

            $fileName = $newFilename;
        }

        $path = Storage::disk('public')->putFileAs($folder, $file, $fileName);
        $url = Storage::disk('public')->url($path);
        $returnArr = [
            'name' => $fileName,
            'url' => $url
        ];

        if ($key == '') {
            return $returnArr;
        } else {
            return $returnArr[$key];
        }
    }

    public function add_inventory(Request $request)
    {
        $header= InventoryReceiverHeader::create([
            'user_id' => Auth::id(),
            'posted_at' => now(),
            'posted_by' => Auth::id(),
            'status' => 'POSTED'
        ]);

        InventoryReceiverDetail::create([
            'product_id' => $request->productid,
            'inventory' => $request->qty,
            'header_id' => $header->id
        ]);

        return back()->with('success','Inventory has been added.');
    }

    public function deduct_inventory(Request $request)
    {
        $product = Product::find($request->productid);

        Cart::create([
            'product_id' => $product->id,
            'user_id' => 0,
            'qty' => $request->qty,
            'price' => $product->price
        ]);

        return back()->with('success','Product inventory has been updated.');
    }

    public function download_template()
    {
        $attributes = FormAttribute::orderBy('name', 'asc')->get();
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=product.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $products = Product::all();
        $columns = array('SKU', 'Name', 'Author', 'Book Type', 'Description', 'Price', 'Discount Price', 'Size', 'Weight', 'Texture', 'UoM', 'Reorder Point');

        foreach($attributes as $attr){
            array_push($columns, $attr->name);
        }

        $callback = function() use ($products, $columns)
        {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
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

                $excel_columns = array('SKU', 'Name', 'Author', 'Book Type', 'Description', 'Price', 'Discount Price', 'Size', 'Weight', 'Texture', 'UoM', 'Reorder Point');

                $attributes = FormAttribute::orderBy('name', 'asc')->get();
                foreach($attributes as $attr){
                    array_push($excel_columns, $attr->name);
                }

                if($row > 1){

                    $code = mb_convert_encoding($data[0], "UTF-8");
                    // $name = mb_convert_encoding($data[1], "UTF-8");
                    $slug = Page::convert_to_slug($data[1]);
                                        
                    // Format Description
                    $name = nl2br(iconv(mb_detect_encoding($data[1], mb_detect_order(), true), "UTF-8//IGNORE", $data[1]));
                    $author = nl2br(iconv(mb_detect_encoding($data[2], mb_detect_order(), true), "UTF-8//IGNORE", $data[2]));
                    $description = nl2br(iconv(mb_detect_encoding($data[4], mb_detect_order(), true), "UTF-8//IGNORE", $data[4]));


                    $product = Product::create([
                        'sku' => str_replace('?',' ',$code),
                        'name' => $name,
                        'author' => $author,
                        'book_type' => $data[3],
                        'slug' => $slug,
                        'description' => '<p>' . $description . '</p>',
                        'price' => str_replace(',','',$data[5]) < 0 ? 0.0000 : str_replace(',','',$data[5]),
                        'discount_price' => str_replace(',','',$data[6]) < 0 ? 0.0000 : str_replace(',','',$data[6]),
                        'size' => $data[7],
                        'weight' => $data[8],
                        'texture' => $data[9],
                        'uom' => $data[10],
                        'reorder_point' => $data[11] ?: 0,
                        'status' => 'PRIVATE',
                        'created_by' => Auth::id()
                    ]);


                    for ($x = 8; $x <= $col_count; $x++) {

                        if($data[$x-1] != ""){
                            ProductAdditionalInfo::create([
                                'product_id' => $product->id,
                                'attribute_name' => $excel_columns[$x-1],
                                'value' => $data[$x-1]
                            ]);
                        }
                    }
                }


            }
            fclose($handle);
        }

        return back()->with('success','Successfully saved new inventory record');
    }

    public function ebook_customer_assignment($id)
    {
        $product = Product::findOrFail($id);

        $customers = User::where('role_id', 6)->get();
        $ebook_customers = CustomerLibrary::where('product_id', $id)->where('is_admin_selected', 1)->get();

        return view('admin.ecommerce.products.ebook-customer-assignment',compact('product', 'customers', 'ebook_customers'));
    }

    public function ebook_customer_assignment_update($id, Request $request)
    {
        // Remove existing ebook customers for the product
        CustomerLibrary::where('product_id', $id)->where('is_admin_selected', 1)->delete();

        // Add new ebook customers
        foreach ($request->customers as $customerId) {
            
            $already_purchased = CustomerLibrary::where('product_id', $id)->where('user_id', $customerId)->where('is_admin_selected', 0)->exists();

            if(!$already_purchased){
                CustomerLibrary::create([
                    'product_id' => $id,
                    'user_id' => $customerId,
                    'is_admin_selected' => 1
                ]);
            }
        }

        return redirect()->back()->with('success', 'Ebook customer assignment updated successfully.');
    }

    // public function ebook_customer_assignment($id)
    // {
    //     $product = Product::findOrFail($id);

    //     $customers = User::where('role_id', 6)->get();
    //     $ebook_customers = EbookCustomer::where('product_id', $id)->get();

    //     return view('admin.ecommerce.products.ebook-customer-assignment',compact('product', 'customers', 'ebook_customers'));
    // }

    // public function ebook_customer_assignment_update($id, Request $request)
    // {
    //     // Remove existing ebook customers for the product
    //     EbookCustomer::where('product_id', $id)->delete();

    //     // Add new ebook customers
    //     foreach ($request->customers as $customerId) {
    //         EbookCustomer::create([
    //             'product_id' => $id,
    //             'user_id' => $customerId,
    //         ]);
    //     }

    //     return redirect()->back()->with('success', 'Ebook customer assignment updated successfully.');
    // }


}
