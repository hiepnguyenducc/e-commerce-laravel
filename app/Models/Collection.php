<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;
    protected $table = 'collection';
    protected $fillable = [
        'meta_title',
        'meta_keyword',
        'meta_description',
        'image',
        'slug',
        'name',
        'description',
        'status',

    ];
    public function category()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }
}
