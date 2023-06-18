<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResponseToParkingOccupationNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    public $status;
    public $request_id;
    public function __construct($status, $request_id)
    {
        $this->status = $status;
        $this->request_id = $request_id;
    }


    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => $this->status == -1 ? 'There Is No Parking Space Available Right Now Please Try Again Later.'
                : "Your request's status had been updated to : " . $this->status,
            'type' => "Response to Occupation Request",
            'request_id' => $this->request_id,
        ];
    }
}
