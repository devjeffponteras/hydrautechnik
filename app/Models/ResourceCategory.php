<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResourceCategory extends Model
{
    // use HasFactory, Sluggable, SoftDeletes;
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'status',
        'user_id'
    ];

    protected $timestamp = true;

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function subcategory() {
        return  $this->hasMany(ResourceCategory::class, 'parent_id')->where('status','Active');
    }

}
