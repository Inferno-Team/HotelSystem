<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\MeetingRoomOccupationRequest;
use Illuminate\Notifications\Messages\MailMessage;

class NewMeetingRoomOccupationRequest extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $oRequerst;
    public function __construct(MeetingRoomOccupationRequest $oRequerst)
    {
        $this->oRequerst = $oRequerst;
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
        $type = $this->oRequerst->type;
        return [
            'message' => "You have a new request to meeting room.",
            'type' => "New Meeting Room Occupation Request for room type : $type",
            'request_id' => $this->oRequerst->id,
        ];
    }
}
