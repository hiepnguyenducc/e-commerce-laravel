<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorites extends Model
{
    use HasFactory;
    protected $table = 'favorites';
    protected $fillable = [
        'user_id',
        'product_id'
    ];
    protected $with = ['user','product'];
    public function product(){
        return $this->belongsTo(Product::class, 'product_id','id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id','id');
    }
}