<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReceiptNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $package;
    protected $billing;
    protected $invoice;

    public function __construct($user, $package, $billing, $invoice)
    {
        $this->user = $user;
        $this->package = $package;
        $this->billing = $billing;
        $this->invoice = $invoice;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('ใบเสร็จการชำระเงิน #' . $this->invoice)
            ->greeting('ขอบคุณสำหรับการชำระเงิน, ' . $this->user->name . '!')
            ->line('เราได้รับการชำระเงินของคุณเรียบร้อยแล้ว สำหรับแพ็กเกจ:')
            ->line($this->package->package_name)
            ->line('จำนวนเงิน: ฿' . number_format($this->billing->amount, 2))
            ->line('วันที่ชำระเงิน: ' . $this->billing->created_at->format('Y-m-d H:i'))
            ->line('หากคุณมีคำถามหรือข้อสงสัย กรุณาติดต่อฝ่ายบริการลูกค้าที่ support@baanlist.com')
            ->salutation('ขอบคุณค่ะ, baanlist');
    }
}
