<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class OrderDeliveredNotification extends Notification
{
    use Queueable;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['database']; // hoặc thêm 'mail' nếu muốn
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Đơn hàng #' . $this->order->id . ' đã giao thành công và thanh toán hoàn tất.',
            'order_id' => $this->order->id,
            'link' => route('admin.order.detail', $this->order->id),
        ];
    }
}
