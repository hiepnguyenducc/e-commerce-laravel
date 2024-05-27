<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    protected $table = 'brand';
    protected $fillable = [
        'meta_title',
        'meta_description',
        'meta_keyword',
        'image',
        'slug',
        'name',
        'description',
        'status'

    ];
    public function product(){
        return $this->hasMany(Product::class);
    }
}
