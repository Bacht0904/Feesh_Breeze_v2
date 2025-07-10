<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('content');
            $table->tinyInteger('rating')->nullable(); // nếu có chấm sao
            $table->boolean('is_approved')->default(false); // duyệt trước khi hiển thị
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }

};
