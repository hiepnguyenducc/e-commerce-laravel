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

        'featured',
        'popular',
        'sale',
        'status',
    ];
//   protected $with = ['categories'];
//   public function category(){
//       return $this->belongsTo(Category::class,'category_id','id');
//   }

    public function productImage()
    {
        return $this->hasMany(ProductImage::class,'product_id','id');
    }
}
