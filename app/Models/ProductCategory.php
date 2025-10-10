<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    // Relationship with ProductSubcategories
    public function subcategories()
    {
        return $this->hasMany(ProductSubcategory::class, 'category_id');
    }

    // Get products through subcategories
    public function products()
    {
        return $this->hasManyThrough(
            Product::class,
            ProductSubcategory::class,
            'category_id', // Foreign key on product_subcategories table
            'subcategory_id', // Foreign key on products table
            'id', // Local key on product_categories table
            'id' // Local key on product_subcategories table
        );
    }

    // Get all categories
    public static function getAllCategories()
    {
        return self::orderBy('name', 'asc');
    }
}
