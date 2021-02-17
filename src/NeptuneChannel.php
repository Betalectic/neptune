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

        $recipient = [];

        if(isset($notifiable->name)){
            $recipient['name'] = $notifiable->name;
        }

        if(isset($notifiable->email)){
            $recipient['email'] = $notifiable->email;
        }

        if(isset($notifiable->mobile)){
            $recipient['mobile'] = $notifiable->mobile;
        }

        $recipients = [
            $recipient
        ];

        // $payload['name'] = $notifiable->name;

        $neptune = new Neptune($payload, $recipients);

        if(isset($notification->notificationUUID) && $notification->notificationUUID != "" && !is_null($notification->notificationUUID)){
            $neptune->fire($notification->notificationUUID, 'uuid');
        }

        
        if(isset($notification->notificationSlug) && $notification->notificationSlug != "" && !is_null($notification->notificationSlug)){
            $neptune->fire($notification->notificationSlug);
        }

    }
}
