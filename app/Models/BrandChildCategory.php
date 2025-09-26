<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandChildCategory extends Model
{
    use HasFactory;

    protected $table = 'brand_child_categories';

    protected $fillable = [
        'brand_id',
        'category_id',
        'order_no'
    ];

    public function product_category()
    {
        return $this->belongsTo('App\Models\Ecommerce\ProductCategory', 'category_id', 'id');
    }

    public function child_categories()
    {
        return $this->hasMany('App\Models\ChildCategory', 'parent_category_id', 'category_id');
    }

}
