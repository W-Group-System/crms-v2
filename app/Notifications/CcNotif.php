<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;

class CcNotif extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $table;
    public function __construct($table)
    {
        $this->table = $table;
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
                // ->cc('international.sales@rico.com.ph')
                // ->cc('mrdc.sales@rico.com.ph')
                ->cc('iad@wgroup.com.ph')
                ->greeting('Greetings!')
                ->subject('Status of Customer Complaint')
                ->line('We would like to follow up on the current status of the customer complaint as of '.date('F Y'))
                ->line('Please received and investigate the complaint of client/ customer.')
                // ->line('ACR Code : '.$this->observation->code)
                ->line(new HtmlString($this->table))
                ->line('Please click the button provided for faster transaction')
                ->action('Customer Complaint', url('/customer_services'))
                ->line('Thank you for using our application!');
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
