<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSubcategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'description',
        'image',
    ];

    // Relationship with ProductCategory
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    // Relationship with Products
    public function products()
    {
        return $this->hasMany(Product::class, 'subcategory_id');
    }

    // Get all active subcategories
    public static function getAllSubcategories()
    {
        return self::with('category')->orderBy('name', 'asc');
    }
}
