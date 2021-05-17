<?php

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification
{
    use Queueable;

    protected $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
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
        if ($this->user->active.'' == '1') {
            return (new MailMessage)
            ->greeting(__('notications.hello', ['username' => $this->user->name]))
            ->subject(__('notications.thanks', ['app_name' => config('app.name')]))
            ->action(__('notications.visit', ['app_name' => config('app.name')]), url(config('app.url')))
            ->line(__('notications.regdone'));
        } else {
            return (new MailMessage)
            ->greeting(__('notications.hello', ['username' => $this->user->name]))
            ->subject(__('notications.thanks', ['app_name' => config('app.name')]))
            ->action(__('notications.visit', ['app_name' => config('app.name')]), url(config('app.url')))
            ->line(__('notications.adminapprove'));
        }
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
