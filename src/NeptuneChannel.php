<?php

namespace Betalectic\Neptune;

use Illuminate\Notifications\Notification;
use Log;
use Betalectic\Neptune\Neptune;

class NeptuneChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $payload = $notification->toNeptune($notifiable);
        
        $notifiableArray = (array) $notifiable;

        $recipients = [];


        if (!empty($notifiableArray[0]) && is_array($notifiableArray[0])) {
            foreach ($notifiableArray as $key => $recipient) {
                array_push($recipients, $recipient);
            }
        } else {
            $recipients = [
                [
                    'name' => $notifiable->name, 'email' => $notifiable->email
                ]
            ];
        }


        $neptune = new Neptune($payload, $recipients);

        if(isset($notification->notificationUUID) && $notification->notificationUUID != "" && !is_null($notification->notificationUUID)){
            $neptune->fire($notification->notificationUUID, 'uuid');
        }

        
        if(isset($notification->notificationSlug) && $notification->notificationSlug != "" && !is_null($notification->notificationSlug)){
            $neptune->fire($notification->notificationSlug);
        }

    }
}
