<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\FileDownloadCategory;

class FileDownload extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'file_download';
    protected $fillable = [
        'version_no',
        'title',
        'category_id',
        'file_url',
        'status',
        'unique_hash',
        'department_id'
    ];

    public function getCategoryAttribute()
    {
        $category = FileDownloadCategory::find($this->category_id);
        if(isset($category)){
            return $category->title;
        } else {
            return 'Uncategorized';
        }
    }

    public function department($id)
    {
        $category = FileDownloadCategory::find($id);

        return $category->title;
    }

    public function getDepartmentAttribute()
    {
        $arr_department = substr($this->department_id, 1, -1);
        $department = explode(',', $arr_department);

        $depts = "";
        foreach($department as $dept){
            $category = FileDownloadCategory::find(str_replace('"','',$dept));

            $depts .= '<small class="badge badge-secondary">'.$category->title.'</small>&nbsp;';
        }
        

        return $depts;
    }
}
