<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Validation\Rules\Enum;

class OrderStatusUpdated extends Notification
{
    use Queueable;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {

        if ($notifiable->hasRole('admin')) {
            return []; // không gửi thông báo cho admin
        }

        return ['database']; // hoặc ['mail', 'database'] nếu muốn gửi email
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Đơn hàng #' . $this->order->id . ' ' . $this->getFormattedStatusAttribute($this->order->status),
            'order_id' => $this->order->id,
            'status' => $this->order->status,
        ];
    }

    //  $table->enum('status', ['Chờ Xác Nhận','Đã Xác Nhận','Chờ Lấy Hàng','Đã Lấy Hàng','Đang Giao','Đã Giao','Giao Thành Công','Xác Nhận Hủy','Đã Hủy'])->default('Chờ Xác Nhận');
    public function getFormattedStatusAttribute($status)
    {
        return match ($status) {
            'Chờ Xác Nhận' => '⏳ Chờ xác nhận',
            'Đã Xác Nhận' => 'đã được người bán xác nhận',
            'Chờ Lấy Hàng' => 'Chờ shipper đến lấy hàng',
            'Đã Lấy Hàng' => 'đã lấy hàng thành công',
            'Đang Giao' => 'đang trên đường giao đến bạn',
            'Đã Giao' => 'đã được giao đến bạn',
            'Giao Thành Công' => 'đã nhận đuợc hàng',
            'Xác Nhận Hủy' => 'đang chờ người bán xác nhận hủy',
            'Đã Hủy' => 'đã được người bán xác nhận hủy!',
            default => $status,
        };
    }
}
