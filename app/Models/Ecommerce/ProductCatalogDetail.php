<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCatalogDetail extends Model
{
    use HasFactory;

    public $table = 'product_catalog_details';
    protected $fillable = ['product_catalog_header_id', 'product_id', 'order'];
}
