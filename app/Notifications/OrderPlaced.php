<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPlaced extends Notification implements ShouldQueue
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
            'message' => 'Bạn đã đặt hàng thành công!',
            'order_id' => $this->order->id,
        ];
    }

    /**
     * Định dạng thông báo gửi qua email.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting('Xin chào!')
            ->line('Cảm ơn bạn đã đặt hàng. Đơn hàng của bạn đã được ghi nhận thành công.')
            ->line('Mã đơn hàng: ' . $this->order->id)
            ->action('Xem đơn hàng', url('/orders/' . $this->order->id))
            ->line('Cảm ơn bạn đã tin tưởng sử dụng dịch vụ của chúng tôi!');
    }
}
