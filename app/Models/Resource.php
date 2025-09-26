<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\ResourceCategory;

class Resource extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'contents',
        'pdf_path',
        'status',
        'user_id',
        'sector',
        'case_type',
        'publish_date'
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

    public function getCategoryAttribute()
    {
        $category = ResourceCategory::find($this->category_id);
        if(isset($category)){
            return $category->name;
        } else {
            return 'Uncategorized';
        }
    }

            
}
