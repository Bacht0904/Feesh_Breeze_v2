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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            // Các cột liên quan đến order phải đứng ngay đây, không dùng ->after()
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('order_detail_id');

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('product_detail_id')->nullable();

            $table->unsignedTinyInteger('rating');
            $table->text('comment');

            // Trạng thái
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();

            // Foreign keys
            $table->foreign('order_id')
                ->references('id')->on('orders')
                ->onDelete('cascade');

            $table->foreign('order_detail_id')
                ->references('id')->on('order_details')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('product_id')
                ->references('id')->on('products')
                ->onDelete('cascade');

            $table->foreign('product_detail_id')
                ->references('id')->on('product_details')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
