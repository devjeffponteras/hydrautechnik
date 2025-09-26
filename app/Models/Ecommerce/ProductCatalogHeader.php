<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCatalogHeader extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'product_catalog_headers';
    protected $fillable = ['name', 'is_category', 'category_id', 'status'];
}
