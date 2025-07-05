<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Order;

class ReturnRequestNotification extends Notification
{
    use Queueable;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting('📦 Yêu cầu trả hàng mới')
            ->line("Khách hàng đã yêu cầu trả đơn hàng #{$this->order->id}.")
            ->action('Xem đơn hàng', url('/admin/orders/' . $this->order->id))
            ->line('Vui lòng xử lý yêu cầu này sớm nhất có thể.');
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "Khách hàng yêu cầu trả đơn hàng #{$this->order->id}",
            'order_id' => $this->order->id,
        ];
    }
}
