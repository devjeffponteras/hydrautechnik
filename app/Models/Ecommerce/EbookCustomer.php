<?php

namespace App\Models\Ecommerce;

use Illuminate\Database\Eloquent\Model;

class EbookCustomer extends Model
{
    protected $table = "ebook_customers";
    protected $fillable = ['product_id', 'user_id'];
}
