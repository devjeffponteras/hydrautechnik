<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormAttribute extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'category_id'
    ];
}
