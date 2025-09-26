<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Model;
use App\Models\Ecommerce\{
    Product, Promo
};

class PromoProducts extends Model
{
    public $table = 'promo_products';
    protected $fillable = ['promo_id', 'product_id', 'user_id'];
 	public $timestamps = true;

 	public function details()
 	{
 		return $this->belongsTo(Product::class,'product_id','id')->withTrashed();
 	}

 	public function promo_details()
 	{
 		return $this->belongsTo(Promo::class,'promo_id')->withTrashed();
 	}

 	public static function is_promo($promoid,$productid)
    {
        $count = PromoProducts::where('promo_id',$promoid)->where('product_id',$productid)->count();

        return $count;
    }
}
