<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'created_by',
        'updated_by',
        'status'
    ];

    protected $hidden = [
        'deleted_at',
    ];

    /*public function parent()
    {
        return $this->belongsTo(ProductCategory::class);
    }
*/
    /*public function childs()
    {
        return $this->hasMany(Category::class, 'parent_id')->with('subCategoryProducts');
    }

    public function services()
    {
        return $this->hasMany(Service::class, 'category_id');
    }*/

    /*public function categoryProducts()
    {
        return $this->hasMany(CategoryProduct::class, 'category_id');
    }

    public function subCategoryProducts()
    {
        return $this->hasMany(CategoryProduct::class, 'sub_category_id');
    }

    public function productslists()
    {
        return $this->hasMany(Product::class, 'sub_category_id');
    }*/
}