<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $table = 'comment';
    protected $fillable = [
        'user_id',
        'post_id',
        'product_id',
        'content',
        'title',
        'image'
    ];
    protected function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }
    protected function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    protected function product(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
