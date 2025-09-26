<?php

namespace App\Models\Ecommerce;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\{ActivityLog, BrandProductCategory};
use App\Models\Ecommerce\Product;

class ProductCategory extends Model
{
    use SoftDeletes, HasSlug;

    protected $table = 'product_categories';
    protected $timestamp = true;

    protected $fillable = [ 'parent_id', 'name', 'slug', 'description', 'mobile_file_url', 'banner_url', 'status', 'menu_order_no', 'created_by',];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function get_url()
    {
        return env('APP_URL')."/product-categories/".$this->slug;
    }

    public function parent()
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id');
    }

    public function child_categories() {
        return  $this->hasMany(ProductCategory::class, 'parent_id')->where('status','PUBLISHED');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function published_products()
    {
        return $this->hasMany(Product::class, 'category_id')->where('status','PUBLISHED');
    }

    public function featured_products()
    {
        return $this->products()->where('is_featured', 1)->get();
    }

    public static function has_subCategory_with_products($categoryID)
    {
        $categories = ProductCategory::where('parent_id', $categoryID)->get();
        $arr_categories = [];

        foreach($categories as $category){
            array_push($arr_categories, $category->id);
        }

        $products = Product::whereIn('category_id', $arr_categories)->where('status', 'PUBLISHED')->count();

        return $products;
    }

    public function getCategoryLevelAttribute()
    {
        $parent = $this->parent;
        $counter = 0;

        while(!is_null($parent)) {
            $parent = $parent->parent;
            $counter++;
        }

        return $counter;
    }
    
    public function get_image_file_name()
    {
        $path = explode('/', $this->file_url);
        $nameIndex = count($path) - 1;
        if ($nameIndex < 0)
            return '';

        return $path[$nameIndex];
    }

    public function get_mobile_image_file_name()
    {
        $path = explode('/', $this->mobile_file_url);
        $nameIndex = count($path) - 1;
        if ($nameIndex < 0)
            return '';

        return $path[$nameIndex];
    }

    // public function sub_category($categoryParentId)
    // {
    //     $category = ProductCategory::where('parent_id')
    // }



    // ******** AUDIT LOG ******** //
    // Need to change every model
    static $oldModel;
    static $tableTitle = 'product category';
    static $name = 'name';
    static $unrelatedFields = ['id', 'slug', 'image_url', 'created_at', 'updated_at', 'deleted_at'];
    static $logName = [
        'parent_id' => 'parent id',
        'name' => 'name',
        'description' => 'description',
        'mobile_file_url' => 'mobile_file_url',
        'banner_url' => 'banner_url',
        'status' => 'status',
        'menu_order_no' => 'sequence number'
    ];
    // END Need to change every model

    public static function boot()
    {
        parent::boot();

        self::created(function($model) {
            $name = $model[self::$name];

            ActivityLog::create([
                'log_by' => auth()->id(),
                'activity_type' => 'insert',
                'dashboard_activity' => 'created a new '. self::$tableTitle,
                'activity_desc' => 'created the '. self::$tableTitle .' '. $name,
                'activity_date' => date("Y-m-d H:i:s"),
                'db_table' => $model->getTable(),
                'old_value' => '',
                'new_value' => $name,
                'reference' => $model->id
            ]);
        });

        self::updating(function($model) {
            self::$oldModel = $model->fresh();
        });

        self::updated(function($model) {
            $name = $model[self::$name];
            $oldModel = self::$oldModel->toArray();
            foreach ($oldModel as $fieldName => $value) {
                if (in_array($fieldName, self::$unrelatedFields)) {
                    continue;
                }

                $oldValue = $model[$fieldName];
                if ($oldValue != $value) {
                    ActivityLog::create([
                        'log_by' => auth()->id(),
                        'activity_type' => 'update',
                        'dashboard_activity' => 'updated the '. self::$tableTitle .' '. self::$logName[$fieldName],
                        'activity_desc' => 'updated the '. self::$tableTitle .' '. self::$logName[$fieldName] .' of '. $name .' from '. $oldValue .' to '. $value,
                        'activity_date' => date("Y-m-d H:i:s"),
                        'db_table' => $model->getTable(),
                        'old_value' => $oldValue,
                        'new_value' => $value,
                        'reference' => $model->id
                    ]);
                }
            }
        });

        self::deleted(function($model){
            $name = $model[self::$name];
            ActivityLog::create([
                'log_by' => auth()->id(),
                'activity_type' => 'delete',
                'dashboard_activity' => 'deleted a '. self::$tableTitle,
                'activity_desc' => 'deleted the '. self::$tableTitle .' '. $name,
                'activity_date' => date("Y-m-d H:i:s"),
                'db_table' => $model->getTable(),
                'old_value' => '',
                'new_value' => '',
                'reference' => $model->id
            ]);
        });
    }
}
