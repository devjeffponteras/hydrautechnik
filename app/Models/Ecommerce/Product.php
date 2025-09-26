<?php

namespace App\Models\Ecommerce;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\{User, Brand};

use App\Models\Ecommerce\{
    ProductTag, ProductCategory, ProductPhoto, PromoProducts, Cart, CustomerFavorite, CustomerWishlist, ProductReview
};

use Carbon\Carbon;
use DB;


class Product extends Model
{
    use SoftDeletes, HasSlug;

    public $table = 'products';
    protected $fillable = [ 'sku', 'book_type', 'category_id', 'name', 'author', 'slug', 'file_url', 'ebook_price', 'ebook_discount_price', 'short_description', 'description', 'price', 'mobile_price', 'discount_price', 'mobile_discount_price', 'size','weight', 'texture', 'status', 'bundle_products', 'is_bundle', 'is_featured', 'is_best_seller', 'is_free', 'is_premium', 'is_preorder', 'uom', 'created_by', 'meta_title', 'meta_keyword', 'meta_description','brand_id','reorder_point'];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function get_url()
    {
        return env('APP_URL')."/products/".$this->slug;
    }

    public function user()
    {
        return $this->belongsTo(User::class,'created_by');
    }

    public function getPriceWithCurrencyAttribute()
    {
    	return " ".number_format($this->price,2);
    }

   
    public function tags(){
        return $this->hasMany(ProductTag::class);
    }

    public function category(){
        return $this->belongsTo(ProductCategory::class)->withTrashed()->withDefault(['id' => '0','name' => 'Uncategorized']); 
    }

    public function brand(){
        return $this->belongsTo(Brand::class)->withTrashed()->withDefault(['id' => '0','name' => 'Uncategorized']); 
    }

    public static function colors($value){

        $colors = \DB::table('products_variations')->select('color')->distinct()->where('product_id',$value)->get();
        return $colors;

    }

    public static function sizes($value){

        $sizes = \DB::table('products_variations')->select('size')->distinct()->where('product_id',$value)->get();
        return $sizes;

    }

    public function photos()
    {
        return $this->hasMany(ProductPhoto::class);
    }

    public function getPhotoPrimaryAttribute()
    {
        $photo = $this->photos()->where('is_primary', 1)->first();
        if(!$photo){
            return '0/no_image_available.PNG';
        }
        else{
            return $photo->path;
        }
    }

    public function getInventoryAttribute()
    {
        
        $in = \DB::table('inventory_receiver_details')
                ->leftJoin('inventory_receiver_header', 'inventory_receiver_details.header_id', '=', 'inventory_receiver_header.id')
                ->where('inventory_receiver_details.product_id','=',$this->id)
                ->where('inventory_receiver_header.status','=','POSTED')
                ->sum('inventory_receiver_details.inventory');
        if(empty($in))
            $in=0;

        $cart = Cart::where('product_id',$this->id)->sum('qty');

         if(empty($cart))
            $cart=0;

        $out = \DB::table('ecommerce_sales_details')
                ->leftJoin('ecommerce_sales_headers', 'ecommerce_sales_details.sales_header_id', '=', 'ecommerce_sales_headers.id')
                ->where('ecommerce_sales_details.product_id','=',$this->id)
                ->where('ecommerce_sales_headers.payment_status','=','PAID')
                ->where('ecommerce_sales_headers.status','=','active')
                ->sum('qty');
        if(empty($out))
            $out=0;
        
        return ($in - ($out + $cart));
      
    }

    public function getInventoryActualAttribute()
    {
        $in = \DB::table('inventory_receiver_details')
            ->leftJoin('inventory_receiver_header', 'inventory_receiver_details.header_id', '=', 'inventory_receiver_header.id')
            ->where('inventory_receiver_details.product_id','=',$this->id)
            ->where('inventory_receiver_header.status','=','POSTED')
            ->sum('inventory_receiver_details.inventory');

        if(empty($in))
            $in=0;     


        $cart = Cart::where('user_id', 0)->where('product_id',$this->id)->sum('qty');
        if(empty($cart))
            $cart=0;   

        
        $out = \DB::table('ecommerce_sales_details')
            ->leftJoin('ecommerce_sales_headers', 'ecommerce_sales_details.sales_header_id', '=', 'ecommerce_sales_headers.id')
            ->where('ecommerce_sales_details.product_id','=',$this->id)
            ->where('ecommerce_sales_headers.payment_status','=','PAID')
            ->where('ecommerce_sales_headers.status','=','active')
            ->sum('qty');

        if(empty($out))
            $out=0;
        
        // return ($in - $out);
        return ($in - ($out + $cart));
      
    }

    public function getMaxpurchaseAttribute() //use for identifying the maximum qty a customer can order
    {
        

        $in = \DB::table('inventory_receiver_details')
                ->leftJoin('inventory_receiver_header', 'inventory_receiver_details.header_id', '=', 'inventory_receiver_header.id')
                ->where('inventory_receiver_details.product_id','=',$this->id)
                ->where('inventory_receiver_header.status','=','POSTED')
                ->sum('inventory_receiver_details.inventory');
        if(empty($in))
            $in=0;

        $cart = Cart::where('product_id',$this->id)->sum('qty');
         if(empty($cart))
            $cart=0;
        
        $out = \DB::table('ecommerce_sales_details')
                ->leftJoin('ecommerce_sales_headers', 'ecommerce_sales_details.sales_header_id', '=', 'ecommerce_sales_headers.id')
                ->where('ecommerce_sales_details.product_id','=',$this->id)
                ->where('ecommerce_sales_headers.payment_status','=','PAID')
                ->where('ecommerce_sales_headers.status','=','active')
                ->sum('ecommerce_sales_details.qty');
        if(empty($out))
            $out=0;
        
        $inventory = ($in + $this->reorder_point) - ($out + $cart);

        return $inventory;
      
    }

    public static function has_bundle($id){
        
        $bundles = Product::whereStatus('PUBLISHED')->where('is_bundle', 1)->whereRaw("FIND_IN_SET(?, bundle_products)", [$id])->first();

        return $bundles ? true : false;
    }

    public static function getBundle($id){
        
        $bundles = Product::whereStatus('PUBLISHED')->where('is_bundle', 1)->whereRaw("FIND_IN_SET(?, bundle_products)", [$id])->get();

        return $bundles;
    }

    public static function has_ebook($id){
        
        $ebook = Product::where('id', $id)->whereStatus('PUBLISHED')->whereNotNull('file_url')->first();

        return $ebook !== null;
    }

    public static function related_products($id){

        $products = Product::whereStatus('PUBLISHED')->where('id','<>',$id)->take(3)->get();


        $data = '';

        foreach($products as $product){
            $data .= '
                <div class="col-md-4 col-sm-6 item">
                    <div class="product-link">
                        <div class="product-card">
                            <a href="'.route("product.front.show",$product->slug).'">
                                <div class="product-img">
                                    <img src="'.asset("storage/products/".$product->photoPrimary).'" alt="" />
                                </div>
                                <div class="gap-30"></div>
                                <p class="product-title">'.$product->name.'</p>
                            </a>
                            <div class="rating small">
                                '.$product->ratingStar.'
                            </div>
                            <h3 class="product-price">'.$product->priceWithCurrency.'</h3>
                        </div>
                    </div>
                </div>
            ';
        }

        return $data;

    }

    public static function totalProduct()
    {
        $total = Product::withTrashed()->get()->count();

        return $total;
    }

    public function is_editable()
    {
        return $this->status != 'UNEDITABLE';
    }

    public static function info($p){

        $pd = Product::where('name','=',$p)->first();

        return $pd;
    }

    public static function detail($p){

        $pd = Product::where('name',$p)->get();

        return $pd;
    }    

    public function get_image_file_name()
    {
        $path = explode('/', $this->zoom_image);
        $nameIndex = count($path) - 1;
        if ($nameIndex < 0)
            return '';

        return $path[$nameIndex];
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\Ecommerce\ProductReview');
    }

    public function getRatingAttribute()
    {
        return $this->reviews->avg('rating');
    }

    public function getRatingStarAttribute(){
        $star = 5 - (integer) $this->rating;
        $front = '';
        for($x = 1; $x<=$this->rating; $x++){
            $front.='<span class="fa fa-star checked"></span>';
        }

        for($x = 1; $x<=$star; $x++){
            $front.='<span class="fa fa-star"></span>';
        }

        return $front;
    }

    public function getDiscountedPriceAttribute()
    {
        $promoProducts = PromoProducts::where('product_id', $this->id);

        $arr_promos = [];
        if($promoProducts->count() > 0){
            $products = $promoProducts->get();

            foreach($products as $product){
                array_push($arr_promos, $product->promo_id);
            }
        }

        $promos = Promo::whereIn('id', $arr_promos)->whereNull('deleted_at')->where('status', 'ACTIVE')->where('applicable_product_type', '<>', 'ebook');

        if($promos->count() > 0){

            $discount = $promos->max('discount');

            $percentage = ($discount/100);
            $discountedAmount = ($this->price * $percentage);

            $price = ($this->price - $discountedAmount);
        } else {
            $price = $this->price;
        }

        return $price;
    }

    public static function onsale_checker($id)
    {
        $checkproduct = DB::table('promos')->join('promo_products','promos.id','=','promo_products.promo_id')->where('promos.status','ACTIVE')->where('promos.is_expire',0)->where('promo_products.product_id',$id)->count();

        return $checkproduct;
    }

    public static function featured_limit_already()
    {
        $featured_count = Product::where('is_featured', 1)->get()->count();

        return $featured_count < env('FEATURED_PRODUCTS_LIMIT') ? false : true;
    }

    public static function get_featured_count()
    {
        $featured_count = Product::where('is_featured', 1)->get()->count();

        return $featured_count;
    }

    public static function best_seller_limit_already()
    {
        $best_seller_count = Product::where('is_best_seller', 1)->get()->count();

        return $best_seller_count < env('BEST_SELLER_LIMIT') ? false : true;
    }

    public static function get_best_seller_count()
    {
        $best_seller_count = Product::where('is_best_seller', 1)->get()->count();

        return $best_seller_count;
    }

    public function on_sale()
    {
        return $this->belongsTo(PromoProducts::class,'id','product_id');
    }

    public function getPromoDiscountAttribute()
    {
        $discount = DB::table('promos')->join('promo_products','promos.id','=','promo_products.promo_id')->where('promos.status','ACTIVE')->where('promos.is_expire',0)->where('promo_products.product_id',$this->id)->max('promos.discount');

        return $discount;
    }

    public function getProductName()
    {
        $path = explode('/', $this->file_url);
        $nameIndex = count($path) - 1;
        if ($nameIndex < 0)
            return '';

        return $path[$nameIndex];
    }

    public function customerFavorites()
    {
        return $this->hasMany(CustomerFavorite::class, 'product_id');
    }

    public function customerWishlists()
    {
        return $this->hasMany(CustomerWishlist::class, 'product_id');
    }
    
}
