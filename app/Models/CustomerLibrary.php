<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerLibrary extends Model
{
    use HasFactory;
    
    protected $table = "customer_libraries";
    protected $fillable = ['product_id', 'user_id', 'is_admin_selected'];
}
