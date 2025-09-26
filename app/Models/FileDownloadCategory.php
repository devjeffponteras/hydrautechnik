<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class FileDownloadCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'file_download_category';
    protected $fillable = [
        'parent_category',
        'title',
        'slug',
        'type',
        'status',
        'user_id'
    ];
}
