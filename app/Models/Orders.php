<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Orders extends Model
{
    use HasFactory;
    protected $table = 'oders';
    protected $fillable = [
        'user_id',
        'payment_method',
        'total_amount',
        'name',
        'email',
        'city',
        'last_name',
        'full_address',
        'telephone',
        'postal_code',
        'tracking_no',
        'status',
        'payment_id',
        'transaction_id',
      
    ];
    public function orderitems(){
        return $this->HasMany(OrderItem::class,'order_id','id');
    }
}
