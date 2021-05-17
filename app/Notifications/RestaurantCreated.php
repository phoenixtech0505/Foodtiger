<?php

namespace App\Notifications;

use App\Restorant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RestaurantCreated extends Notification
{
    use Queueable;

    protected $password;
    protected $restaurant;
    protected $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($password, $restaurant, $user)
    {
        $this->password = $password;
        $this->restaurant = $restaurant;
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
        return (new MailMessage)
                    ->greeting(__('notications.hello', ['username' => $this->user->name]))
                    ->subject(__('notications.acc_create', ['app_name'=>env('APP_NAME', '')]))
                    ->line(__('notications.rest_acc_created', ['restoname'=>$this->restaurant->name]))
                    ->action(__('notications.login'), url(config('app.url').'/login'))
                    ->line(__('notications.username', ['email'=>$this->user->email]))
                    ->line(__('notications.password', ['password'=>$this->password]))
                    ->line(__('notications.reset_pass'))
                    ->line(__('notications.thanks_for_using_us'));
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
