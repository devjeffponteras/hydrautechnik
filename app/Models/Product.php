<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subcategory_id',
        'description',
        'image',
        'specification',
        'ixu',
        'olx',
        'fam_atex',
        'olsw',
        'category_id',
    ];

    // Relationship with ProductSubcategory
    public function subcategory()
    {
        return $this->belongsTo(ProductSubcategory::class, 'subcategory_id');
    }

    // Relationship with ProductCategory (direct)
    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    // Get category through subcategory
    public function category()
    {
        return $this->hasOneThrough(
            ProductCategory::class,
            ProductSubcategory::class,
            'id', // Foreign key on product_subcategories table
            'id', // Foreign key on product_categories table
            'subcategory_id', // Local key on products table
            'category_id' // Local key on product_subcategories table
        );
    }

    // Get all products with relationships
    public static function getAllProducts()
    {
        return self::with(['subcategory.category'])->orderBy('name', 'asc');
    }
}
