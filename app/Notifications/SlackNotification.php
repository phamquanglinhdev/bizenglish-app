<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class SlackNotification extends Notification
{
    use Queueable;

    public $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'slack'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    public function toSlack($notifiable)
    {
//        \App\Models\Notification::create(
//            [
//                "title" => "Thông báo BizEnglish",
//                "message" => $this->message,
//
//            ]
//        );
        $url = "https://fb.me";
        return (new SlackMessage())
            ->from('All Laravel')
            ->to('#reported_class')
            ->image('https://allaravel.com/themes/allaravel/assets/img/all-laravel-logo.png')
            ->content($this->message)
            ->attachment(function ($attachment) use ($url) {
                $attachment->title('Xem chi tiết', $url);
//                    ->fields([
//                        'Title' => 'Server Expenses',
//                        'Amount' => '$1,234',
//                        'Via' => 'American Express',
//                        'Was Overdue' => ':-1:',
//                    ]);
            });
    }


}
