<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $table = 'cart';
    protected $fillable = [
        'user_id',
        'product_id',
        'product_qty',
        'color_id',
        'size_id'
    ];
    protected $with = ['product', 'color', 'size'];
    public function product(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function color(){
        return $this->belongsTo(Color::class, 'color_id', 'id');
    }
    public function size(){
        return $this->belongsTo(Size::class, 'size_id', 'id');
    }
}
