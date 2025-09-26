<?php

namespace App\Http\Controllers\Ecommerce;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Mail;
use App\Mail\ProductQuotationRequestMail;

use App\Helpers\{Setting};

use App\Models\Ecommerce\{
    ProductCategory, Product, ProductReview, CustomerFavorite, CustomerWishlist
};

use App\Models\{Page, Brand, BrandProductCategory};

use DB, Str;

class ProductFrontController extends Controller
{
    private $folder = "theme.pages.ecommerce";

    public function product_list(Request $request, $category = null)
    {
        // $page = new Page();
        $page = Page::where('slug', 'books')->where('status', 'PUBLISHED')->where('parent_page_id', 0)->first();
        $pageLimit = 12;

        $products = Product::where('status','PUBLISHED')->whereRaw('LOWER(book_type) NOT IN (?, ?)', ['ebook', 'e-book']);

        if($category){
            $productCategory  = ProductCategory::where('slug', $category)->first();
            $products->where('category_id', $productCategory->id);

            $page->name = $productCategory->name;
        } else {
            $page->name = "Books";
        }


        $maxPrice = $products->max('price');
        $minPrice = 1;

        if($request->has('search')){

            if(!empty($request->rating)){
                $rating = $request->rating;
                $products->whereIn('id',function($query) use($rating){
                    $query->select('product_id')->from('ecommerce_product_review')
                    ->where('rating',$rating)
                    ->where('is_approved',1);
                });
            }

            if(!empty($request->sort)){            
                if($request->sort == 'Price low to high'){
                    $products = $products->orderBy('price','asc');
                }
                elseif($request->sort == 'Price high to low'){
                    $products = $products->orderBy('price','desc');
                }
            }

            if(!empty($request->limit)){ 
                if($request->limit=='All')
                    $pageLimit = 100000000;      
                else
                    $pageLimit = $request->limit;
            }

            if(!empty($request->price)){
                $price = explode(';',$request->price);
                $products = $products->whereBetween('price',[$price[0],$price[1]]);

                $productMaxPrice = $maxPrice;
                $maxPrice = $price[1];
                $minPrice = $price[0];

            }

            $total_product = $products->count();
            $products = $products->orderBy('updated_at','desc')->paginate($pageLimit);
        }
        else{
            $productMaxPrice = $maxPrice;
            $minPrice = $minPrice;
            $total_product = $products->count();
            $products = $products->orderBy('name','asc')->paginate($pageLimit);
        }

        return view($this->folder.'.product-list',compact(
            'products',
            'page',
            'total_product',
            'maxPrice',
            'minPrice',
            'productMaxPrice',
        ));
    }

    
    public function search_product(Request $request)
    {
        $page = new Page();
        $page->name = 'Search Product';
        $pageLimit = 20;

        $products = Product::select('products.*')->leftJoin('product_additional_infos', 'products.id', '=', 'product_additional_infos.product_id')
        ->where('products.status', 'PUBLISHED')
        ->whereRaw('LOWER(book_type) NOT IN (?, ?)', ['ebook', 'e-book']);

        $searchtxt = $request->get('keyword', false);
        $sortBy = $request->get('sort_by', false);

        if(!empty($searchtxt)){  
            $keyword = Str::lower($request->keyword); 

            $products = $products->where(function($query) use ($keyword){
                $query->orWhereRaw('LOWER(products.name) like LOWER(?)', ["%{$keyword}%"])
                ->orWhereRaw('LOWER(products.author) like LOWER(?)', ["%{$keyword}%"])
                ->orWhereRaw('LOWER(products.description) like LOWER(?)', ["%{$keyword}%"])
                ->orWhereRaw('LOWER(product_additional_infos.value) like LOWER(?)', ["%{$keyword}%"]);
            });
        }

        if($sortBy == "name_asc"){
            $products = $products->orderBy('name','asc')->paginate($pageLimit);
        }
        elseif($sortBy == "name_desc"){
            $products = $products->orderBy('name','desc')->paginate($pageLimit);
        }
        elseif($sortBy == "price_asc"){
            $products = $products->orderBy('price','asc')->paginate($pageLimit);
        }
        elseif($sortBy == "price_desc"){
            $products = $products->orderBy('price','desc')->paginate($pageLimit);
        }
        elseif($sortBy == "date_asc"){
            $products = $products->orderBy('created_at','asc')->paginate($pageLimit);
        }
        elseif($sortBy == "date_desc"){
            $products = $products->orderBy('created_at','desc')->paginate($pageLimit);
        }
        else{
            $products = $products->orderBy('name','asc')->paginate($pageLimit);
        }


        return view($this->folder.'.product-list',compact('products','page', 'searchtxt'));
    }


    public function product_details($slug)
    {
        $product = Product::where('slug', $slug)->first();
        $categories = ProductCategory::where('parent_id', 0)->where('status', 'PUBLISHED')->orderBy('name', 'asc')->get();
        $product_reviews = ProductReview::where('product_id', $product->id)->orderByDesc('updated_at')->get();

        $page = new Page();
        $page->name = $product->name;

        $relatedProducts = Product::where('category_id',$product->category_id)->where('id','<>',$product->id)->where('status','PUBLISHED')->skip(0)->take(4)->get();

        return view($this->folder.'.product-details',compact('product','page', 'relatedProducts', 'categories', 'product_reviews'));
    }


    public function ebook_details($slug)
    {
        $product = Product::where('slug', $slug)->first();
        $categories = ProductCategory::where('parent_id', 0)->where('status', 'PUBLISHED')->orderBy('name', 'asc')->get();
        $product_reviews = ProductReview::where('product_id', $product->id)->orderByDesc('updated_at')->get();

        $page = new Page();
        $page->name = $product->name;

        $relatedProducts = Product::where('category_id',$product->category_id)->where('id','<>',$product->id)->where('status','PUBLISHED')->skip(0)->take(4)->get();

        return view($this->folder.'.ebook-details',compact('product','page', 'relatedProducts', 'categories', 'product_reviews'));
    }


    public function show_forsale(){

        $products = DB::table('products')->where('for_sale', '1')->where('status','PUBLISHED')->where('for_sale_web','1')->where('is_misc','0')->select('name')->distinct()->get();
        $miscs = DB::table('products')->where('for_sale', '1')->where('status','PUBLISHED')->where('for_sale_web','1')->where('is_misc','1')->select('name')->distinct()->get();

        $page = new Page();
        $page->name = 'Order';

        return view('theme.ecommerce.product.order',compact('products','page','miscs'));

    }
    

    public function brands(Request $request)
    {
        $page = new Page();
        $page->name = 'Brands';
        $pageLimit = 20;
        $brands = Brand::whereStatus('Active')->orderBy('menu_order_no','asc')->paginate($pageLimit);

        return view('theme.pages.ecommerce.product-brands',compact('brands','page','request'));
    }

    // public function search_product(Request $request)
    // {
    //     $page = new Page();
    //     $page->name = 'Search Product';
    //     $pageLimit = 20;

    //     $products = Product::select('products.*')->leftJoin('product_additional_infos', 'products.id', '=', 'product_additional_infos.product_id')
    //     ->where('products.status', 'PUBLISHED');

    //     $searchtxt = $request->get('keyword', false);

    //     if(!empty($searchtxt)){  
    //         $keyword = Str::lower($request->keyword); 

    //         $products = $products->where(function($query) use ($keyword){
    //             $query->orWhereRaw('LOWER(products.name) like LOWER(?)', ["%{$keyword}%"])
    //             ->orWhereRaw('LOWER(products.description) like LOWER(?)', ["%{$keyword}%"])
    //             ->orWhereRaw('LOWER(product_additional_infos.value) like LOWER(?)', ["%{$keyword}%"]);
    //         });
    //     }

    //     $products = $products->orderBy('name','asc')->paginate($pageLimit);

    //     return view('theme.pages.ecommerce.product-search',compact('products','page', 'searchtxt'));
    // }


    public function search_content(Request $request)
    {
        $page = new Page();
        $page->name = 'Search Content';

        $pages     = Page::select('id','name','slug','contents as description')->where('status', 'PUBLISHED');
        $products  = Product::select('id','name','slug','description')->where('status', 'PUBLISHED');

        if(!empty($request->keyword)){  
            $keyword = Str::lower($request->keyword); 

            $pages = $pages->where(function($query) use ($keyword){
                $query->orWhereRaw('LOWER(name) like LOWER(?)', ["%{$keyword}%"])
                ->orWhereRaw('LOWER(contents) like LOWER(?)', ["%{$keyword}%"]);
            });

            $products = $products->where(function($query) use ($keyword){
                $query->orWhereRaw('LOWER(name) like LOWER(?)', ["%{$keyword}%"])
                ->orWhereRaw('LOWER(description) like LOWER(?)', ["%{$keyword}%"]);
            });
        }

        $pages   = $pages->get();
        $product = $products->get();
        

        $data = $pages->merge($product)->paginate(10);

        return view('theme.pages.search-content',compact('data','page'));
    }

    public function brand_product_categories($id)
    {
        $brand = Brand::find($id);
        $pageLimit = 20;

        $page = new Page();
        $page->name = $brand->name;
        $pageLimit = 20;

        $productCategories = $brand->product_categories;

        $arr_product_categories = [];
        foreach($productCategories as $cat){
            array_push($arr_product_categories, $cat->product_category_id);
        }

        $products = Product::where('status', 'PUBLISHED')->whereIn('category_id', $arr_product_categories)->paginate($pageLimit);


        return view('theme.pages.ecommerce.product-brand-categories',compact('products', 'page', 'brand'));
    }

    public function product_sub_categories($id)
    {
        $category = ProductCategory::withTrashed()->find($id);
        $pageLimit = 20;

        $page = new Page();
        $page->name = $category->name;
        $pageLimit = 20;

        $products = Product::where('category_id', $id)->where('status','PUBLISHED')->paginate($pageLimit);

        return view('theme.pages.ecommerce.product-sub-categories',compact('products','page', 'category'));
    }


    public function brand_products($id)
    {
        $brand = Brand::find($id);

        $page = new Page();
        $page->name = $brand->name;
        $pageLimit = 20;

        $products = Product::where('brand_id', $id)->where('status','PUBLISHED')->paginate($pageLimit);

        return view('theme.pages.ecommerce.brand-product-list',compact('products','page'));
    }

    public function category_products($slug)
    {
        $cat = ProductCategory::where('slug', $slug)->first();
        $page = new Page();
        $page->name = $cat->name;

        $categories = ProductCategory::where('parent_id', $cat->id)->get();
        $arr_categories = [];
        foreach($categories as $category){
            array_push($arr_categories, $category->id);
        }

        $products = Product::whereIn('category_id', $arr_categories)->where('status', 'PUBLISHED')->paginate(10);

        return view('theme.pages.ecommerce.category-product-list',compact('products','page'));
    }

    

    
  
    public function get_sub_categories_ids($ids, $categories)
    {
        $categoryIds = $categories->pluck('id');
        $ids = array_merge($ids, $categoryIds->toArray());
        foreach ($categoryIds as $id) {
            $subCategory = ProductCategory::find($id);
            $subSubCategories = $subCategory->child_categories;
            if ($subSubCategories && $subSubCategories->count()) {
                $ids = $this->get_sub_categories_ids($ids, $subSubCategories);
            }
        }

        return $ids;
    }

    public function categories($conditions=null){

        if($conditions){

        }
        else{
            $categories = DB::select('SELECT ifnull(c.name, "Uncategorized") as cat, ifnull(c.id,0) as cid,count(ifnull(c.id,0)) as total_products FROM `products` a left join product_categories c on c.id=a.category_id where a.deleted_at is null and a.status="PUBLISHED" GROUP BY c.name,c.id ORDER BY c.name');


            $data = '<ul class="listing-category">';
            foreach($categories as $category) {
                $ul2 = '';
                if ($category->child_categories) {
                    $ul2 = '<ul>';
                    $ul3 = '';
                }
                $data .= '<li><a href="#" onclick="filter_category('.$category->id.')">'.$category->name.'</a><li>';
            }
            $data .= '</ul>';
        }

        return $data;
    }
}
