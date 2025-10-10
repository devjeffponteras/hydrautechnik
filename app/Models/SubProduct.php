<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @deprecated Use ProductSubcategory instead
 * This model is kept for backward compatibility
 */
class SubProduct extends Model
{
    protected $table = 'product_subcategories';
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
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

    // Get all active sub-products
    public static function getAllSubProducts()
    {
        return self::with('category')->orderBy('name', 'asc');
    }
}
