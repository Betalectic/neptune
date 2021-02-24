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
                array_push($recipients, $this->getRecipientInfo((object)$recipient));
            }
        } else {
            array_push($recipients, $this->getRecipientInfo($notifiable));            
        }


        $neptune = new Neptune($payload, $recipients);

        if(isset($notification->notificationUUID) && $notification->notificationUUID != "" && !is_null($notification->notificationUUID)){
            $neptune->fire($notification->notificationUUID, 'uuid');
        }

        
        if(isset($notification->notificationSlug) && $notification->notificationSlug != "" && !is_null($notification->notificationSlug)){
            $neptune->fire($notification->notificationSlug);
        }

    }

    public function getRecipientInfo($recipient) {
        $data = [];
        if (isset($recipient->name)){
            $data['name'] = $recipient->name;
        }

        if (isset($recipient->email)){
            $data['email'] = $recipient->email;
        }

        if (isset($recipient->mobile)){
            $data['mobile'] = $recipient->mobile;
        }

        if (isset($recipient->cc)){
            $data['cc'] = $recipient->cc;
        }

        if (isset($recipient->bcc)){
            $data['bcc'] = $recipient->bcc;
        }

        return $data;
    }
}
