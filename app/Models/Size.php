<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;
    protected $table = 'size';
    protected $fillable = [
        'name',
        'code',
        'quantity',
        'description',
        'length',
        'width',
        'status',
        'height',
        'weight'
    ];
    public function size(){
        return $this->hasMany(ProductSize::class,'size_id','id');
    }
}
