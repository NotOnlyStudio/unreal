<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegisterConfirmation extends Notification
{
    use Queueable;

    public $link;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($url)
    {
        $this->link = $url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->from(\Config::get("mail")['from']['address'],\Config::get("mail")['from']['name'])
            ->greeting('Confirm email!')
            ->line('To complete registration, follow the link:')
            ->action('Confirm email', url("/".$this->link))
            ->line('Thank you for registering on our service.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
