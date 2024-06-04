<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    protected $table = 'coupon';
    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'start_date',
        'end_date',
        'max_uses',
        'uses_count',
        'status',
    ];
    public function isActive(): bool
    {
        // Logic để kiểm tra xem mã giảm giá có đang hoạt động hay không
        // Ví dụ: kiểm tra ngày hết hạn, trạng thái kích hoạt, v.v.
        return $this->status === 'active' && now()->lt($this->end_date);
    }
    public function hasExpirationDate(): bool
    {
        // Logic để kiểm tra xem mã giảm giá có ngày hết hạn hay không
        // Ví dụ: kiểm tra null của end_date
        return !is_null($this->end_date);
    }
    public function hasUsageLimit(): bool
    {
        // Logic để kiểm tra xem mã giảm giá có giới hạn sử dụng hay không
        // Ví dụ: kiểm tra xem max_uses thuộc tính có lớn hơn 0 không
        return $this->max_uses > 0;
    }
}
