<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPassword extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $token;
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('รีเซ็ตรหัสผ่านของคุณ')
            ->greeting('สวัสดีค่ะ!')
            ->line('คุณได้รับอีเมลฉบับนี้เนื่องจากเราพบคำขอรีเซ็ตรหัสผ่านสำหรับบัญชีของคุณ')
            ->action('รีเซ็ตรหัสผ่าน', url(config('app.url') . route('password.reset', [
                'token' => $this->token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false)))
            ->line('ลิงก์นี้จะหมดอายุใน 60 นาที')
            ->line('หากคุณไม่ได้ร้องขอการรีเซ็ตรหัสผ่าน กรุณาไม่ต้องดำเนินการใด ๆ')
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
