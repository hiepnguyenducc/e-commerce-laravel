<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $fillable = [
        'meta_title',
        'meta_keyword',
        'meta_description',
        'slug',
        'image',
        'name',
        'description',
        'collection_id',
        'status',
    ];
    public function product()
    {
        return $this->hasMany(Product::class);
    }
    public function collections()
    {
        return $this->hasMany(Collection::class, 'id', 'collection_id');
    }
}
