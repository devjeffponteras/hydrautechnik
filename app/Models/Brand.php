<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'image_url',
        'status',
        'user_id',
        'menu_order_no',
        'updated_at',
    ];

    protected $timestamp = true;

    public function product_categories()
    {
        return $this->hasMany('App\Models\BrandProductCategory');
    }

    public function child_categories()
    {
        return $this->hasMany('App\Models\BrandChildCategory');
    }
}

