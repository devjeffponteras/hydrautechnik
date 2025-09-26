<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandProductCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_id',
        'product_category_id'
    ];

    public function product_category()
    {
        return $this->belongsTo('App\Models\Ecommerce\ProductCategory')->withTrashed();
    }

    public function brand()
    {
        return $this->belongsTo('App\Models\Brand', 'brand_id', 'id');
    }
}
