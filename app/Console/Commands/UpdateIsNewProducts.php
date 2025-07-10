<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateIsNewProducts extends Command
{
    protected $signature = 'products:update-isnew';
    protected $description = 'Cập nhật cột is_new cho sản phẩm dưới 30 ngày';

    public function handle()
    {
        DB::table('products')->update([
            'is_new' => DB::raw('CASE WHEN DATEDIFF(NOW(), created_at) <= 30 THEN 1 ELSE 0 END')
        ]);

        $this->info('Đã cập nhật is_new thành công!');
    }
}
