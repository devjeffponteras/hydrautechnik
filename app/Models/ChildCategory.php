<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildCategory extends Model
{
    use HasFactory;

    protected $table = 'child_categories';

    protected $fillable = [
        'parent_category_id',
        'category_id',
    ];


    public function subcategory()
    {
        return $this->hasMany(ChildCategory::class, 'parent_category_id', 'category_id');
    }


    public function product_category()
    {
        return $this->belongsTo('App\Models\Ecommerce\ProductCategory', 'category_id', 'id');
    }
}
