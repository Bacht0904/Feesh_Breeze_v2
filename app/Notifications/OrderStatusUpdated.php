<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Notifications\Messages\MailMessage;

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

        if ($notifiable->role === 'user') {
            return ['database', 'mail'];
        }

        return []; // hoặc ['mail', 'database'] nếu muốn gửi email
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Đơn hàng #' . $this->order->id . ' ' . $this->getFormattedStatusAttribute($this->order->status),
            'order_id' => $this->order->id,
            'status' => $this->order->status,
        ];
    }
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Cập nhật trạng thái đơn hàng #' . $this->order->id)
            ->greeting('Xin chào!')
            ->line('Trạng thái đơn hàng #' . $this->order->id . ' đã được cập nhật:')
            ->line('👉 ' . $this->getFormattedStatusAttribute($this->order->status))
            ->action('Xem đơn hàng', url('/orders/' . $this->order->id))
            ->line('Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!');
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
