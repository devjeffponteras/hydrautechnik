<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Ecommerce\{
    Product
};

class CustomerWishlist extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'customer_id'];

    public static function isWishlist($product_id){
        return self::where('product_id', $product_id)->where('customer_id', auth()->id())->first();
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
