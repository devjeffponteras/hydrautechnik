<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannerAdPage extends Model
{
    use HasFactory;

    protected $fillable = ['banner_ad_id', 'page_id'];

}
