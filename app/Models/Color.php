<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;
    protected $table = 'color';
    protected $fillable = [
        'name',
        'code',
        'hex_code',
    ];
    public function product(){
        return $this->hasMany(Product::class);
    }

}
