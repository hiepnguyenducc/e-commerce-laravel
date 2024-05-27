<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Product extends Model
{
    use HasFactory;


    public mixed $id;
    protected $table = 'product';
    protected $fillable = [
        'id',
        'category_id',
        'slug',
        'name',
        'description',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'brand_id',
        'selling_price',
        'original_price',
        'quantity',
        'size_id',
        'color_id',
        'featured',
        'popular',
        'sale',
        'sale_start_date',
        'collection_id',
        'sale_end_date',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }
    public function brand()
    {
    return $this->belongsTo(Brand::class,'brand_id','id');
    }
    public function color()
    {
    return $this->belongsTo(Color::class,'color_id','id');
    }
    public function productImage()
    {
        return $this->hasMany(ProductImage::class,'product_id','id');
    }
    public function collections()
    {
        return $this->hasMany(Collection::class, 'id', 'collection_id');
    }
    public function productColor()
    {
        return $this->hasMany(ProductColor::class,'product_id','id');
    }
    public function productSize(){
        return $this->hasMany(ProductSize::class,'product_id','id');
    }

}
