<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    DB::statement("ALTER TABLE orders MODIFY status
        ENUM(
            'Chờ Xác Nhận','Đã Xác Nhận','Chờ Lấy Hàng',
            'Đang Giao','Đã Giao','Xác Nhận Hủy',
            'Đã Hủy', 'Yêu Cầu Trả Hàng','Xác Nhận Trả Hàng'
        ) DEFAULT 'Chờ Xác Nhận'");
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
