<?php

namespace App\Models\Ecommerce;
use App\Models\Ecommerce\{
    Product
};


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductReview extends Model
{
    use HasFactory, softDeletes;

    public $table = 'product_reviews';
    protected $fillable = ['product_id', 'product_name', 'user_id', 'name', 'email', 'comment','rating', 'status'];
    
    public static function getProductName($id){
        $product_name = Product::where('id', $id)->first();
        return $product_name->name;
    }

    // public function product()
    // {
    //     return $this->belongsTo('App\EcommerceModel\Product');
    // }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public static function review_counter($productId,$rating)
    {
        $qry = 
            ProductReview::where('is_approved',1)
            ->where('product_id',$productId)
            ->where('rating',$rating)
            ->count();

        return $qry;
    }

    public static function category_rating_counter($category,$rating)
    {
        $count = 
            \App\EcommerceModel\Product::where('status','PUBLISHED')->where('category_id',$category)->whereIn('id', function($query) use ($rating){
                $query->select('product_id')->from('ecommerce_product_review')
                    ->where('is_approved',1)
                    ->where('rating',$rating);
            })->count();

        return $count;

    }

    public static function search_product_rating_counter($searchtxt,$rating)
    {
        $count = 
            \App\EcommerceModel\Product::join('product_additional_info','products.id','=','product_additional_info.product_id')->select('products.*','product_additional_info.authors')->whereStatus('PUBLISHED')->where(
                    function($query) use ($searchtxt){
                        $query->where('products.name','like','%'.$searchtxt.'%')
                        ->orWhere('products.description','like','%'.$searchtxt.'%')
                        ->orWhere('authors','like','%'.$searchtxt.'%');
                    })->whereIn('products.id', 
                        function($query) use ($rating){
                            $query->select('product_id')->from('ecommerce_product_review')
                            ->where('is_approved',1)
                            ->where('rating',$rating);
                    })->count();

        return $count;

    }

    public static function getProductRating($product_id)
    {
        $rating = ProductReview::where('product_id', $product_id)->where('status', 1)->get();

        $total_rating = 0;

        if($rating->count() > 0){
            $total_rating = $rating->sum('rating')/$rating->count();
        }

        return $total_rating;
    }

}
