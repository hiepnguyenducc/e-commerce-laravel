<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coupon', function (Blueprint $table) {
            $table->id();
            $table->string('code')->comment('ma giam gia');
            $table->enum('discount_type',['percentage','fixed'])->comment('Loại giảm giá (phần trăm hoặc số tiền cố định)');
            $table->decimal('discount_value',['10','2'])->comment('gia tri giam gia');
            $table->dateTime('start_date')->comment('ngay bat dau');
            $table->dateTime('end_date')->comment('ngay ket thuc');
            $table->unsignedBigInteger('max_uses')->default(1)->comment('so lan su dung toi da');
            $table->unsignedBigInteger('uses_count')->default(0)->comment('so lan da su dung');
            $table->enum('status',['active','expired','used'])->default('active')->comment('trang thai ma giam gia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon');
    }
};
