<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPlaced extends Notification
{
    use Queueable;

    protected $order;

    /**
     * Tạo một instance mới của notification.
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Kênh gửi thông báo.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Dữ liệu lưu vào database.
     */
    public function toDatabase($notifiable)
    {
        return [
            'title'     => 'Đơn hàng mới từ khách hàng',
            'message'   => 'Khách hàng vừa đặt đơn hàng #' . $this->order->id,
            'order_id'  => $this->order->id,
            'user_name' => $this->order->name,
            'total'     => $this->order->total,
            'created_at' => now(),
        ];
    }

    /**
     * Nội dung gửi qua email cho admin.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('🔔 Đơn hàng mới #' . $this->order->id)
            ->greeting('Xin chào Admin,')
            ->line('Bạn có đơn hàng mới từ khách hàng: ' . $this->order->name)
            ->line('Số điện thoại: ' . $this->order->phone)
            ->line('Tổng tiền: ' . number_format($this->order->total, 0, ',', '.') . ' VNĐ')
            ->action('Xem chi tiết đơn hàng', url('/admin/orders/' . $this->order->id))
            ->line('Hệ thống Fresh Breeze thân mến.');
    }
}
