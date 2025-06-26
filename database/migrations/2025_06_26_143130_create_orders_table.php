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
            $table->enum('payment_method',allowed: ['Tiền Mặt','Chuyển Khoản'])->default('Tiền Mặt');
            $table->enum(column:'payment_status',allowed: ['Đã Thanh Toán','Chưa Thanh Toán'])->default('Chưa Thanh Toán');
            $table->string(column:'name');
            $table->string('phone');
            $table->string('address');
            $table->double('order_items');
            $table->enum('status',allowed: ['Đã Duyệt','Đang Giao','Đã Giao','Đã Hủy'])->default('Đã Duyệt');       
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
