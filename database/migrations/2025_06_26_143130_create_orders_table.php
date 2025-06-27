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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('id_user');
            $table->string('id_payment');
            $table->string('id_shipping');
            $table->timestamp('order_date');
            $table->decimal('suptotal',10,2);
            $table->enum('payment_method', ['Tiền Mặt','Chuyển Khoản'])->default('Tiền Mặt');
            $table->enum('payment_status', ['Đã Thanh Toán','Chưa Thanh Toán'])->default('Chưa Thanh Toán');
            $table->string('name');
            $table->string('phone');
            $table->string('address');
            $table->string('email')->nullable();
            $table->string('note')->nullable();
            $table->string('coupon_code')->nullable();
            $table->decimal('coupon_discount',10,2)->default(0);
            $table->decimal('shipping_fee',10,2)->default(0);
            $table->decimal('total',10,2)->default(0);
            $table->enum('status', ['Chờ Xác Nhận','Đã Xác Nhận','Chờ Lấy Hàng','Đã Lấy Hàng','Đang Giao','Đã Giao','Giao Thành Công','Xác Nhận Hủy','Đã Hủy'])->default('Chờ Xác Nhận');       
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
