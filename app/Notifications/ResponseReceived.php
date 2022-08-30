<?php

namespace App\Notifications;

use App\Models\Invite;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Notifications\Notification;

class ResponseReceived extends Notification
{
    use Queueable;

    /**
     * @var Invite
     */
    protected $invite;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        $via = ['database'];
        if (!empty($notifiable->phone)) $via[] = 'vonage';
//        if (!empty($notifiable->email)) $via[] = 'email';
        return $via;
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
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
    * Get the Vonage / SMS representation of the notification.
    *
    * @param  mixed  $notifiable
    * @return \Illuminate\Notifications\Messages\VonageMessage
    */
    public function toVonage($notifiable)
    {
        //TODO: change messages depending on invite content
        //TODO: add link to update their response
        return (new VonageMessage)
                    ->content("You are my rock, ". $notifiable->first_name .". Your response has been saved and you are signed up for notifications. \n". $notifiable->show->name ." \n ". $notifiable->show->date->format("D F j, Y @ g:ia"));
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
