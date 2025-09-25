<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;

class CustomVerifyEmail extends BaseVerifyEmail
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('ยืนยันอีเมลของคุณ')
            ->greeting('สวัสดีค่ะ!')
            ->line('กรุณาคลิกปุ่มด้านล่างเพื่อยืนยันที่อยู่อีเมลของคุณ')
            ->action('ยืนยันอีเมล', $verificationUrl)
            ->line('หากคุณไม่ได้สร้างบัญชีนี้ ไม่ต้องดำเนินการใด ๆ')
            ->salutation('ขอบคุณค่ะ, baanlist');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
