<?php
namespace Betalectic\Neptune;

use Illuminate\Support\Facades\Http;
use Log;

class Neptune
{
    public $client;
    public $envrionmentUUID;
    public $createEvent;
    public $payload;
    public $recipients;

    public function __construct($payload = [], $recipients = [])
    {
        $this->client = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.config('neptune.token')
        ]);

        $this->envrionmentUUID =  config('neptune.env');

        $this->createEvent = config('neptune.endpoint')."/api/teams/".config('neptune.team')."/events";
        $this->getAppNotificatiosUrl = config('neptune.endpoint')."/api/teams/".config('neptune.team')."/app-notifications";
        $this->readAppNotificatiosUrl = config('neptune.endpoint')."/api/teams/".config('neptune.team')."/app-notifications/read";
        

        $this->payload = $payload;
        $this->recipients = $recipients;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function fire($notificationIdentifier, $identifier = "slug")
    {
        // Log::info("Fire");

        $body = [
            'environment_uuid' => $this->envrionmentUUID,
            'payload' => $this->payload,
            'recipients' => $this->recipients,
        ];

        if($identifier == 'uuid'){
            $body['notification_uuid'] = $notificationIdentifier;
        }

        if($identifier == 'slug'){
            $body['notification_slug'] = $notificationIdentifier;
        }


        // Log::info($body);

        $response = $this->client->post($this->createEvent, $body);

        $jsonResponse = $response->json();

        // Log::info($jsonResponse);

        return $jsonResponse;
    }


    public function getAppNotifications()
    {
        $response = $this->client->get($this->getAppNotificatiosUrl, $this->payload);

        $jsonResponse = $response->json();

        return $jsonResponse;
    }

    public function readAppNotifications()
    {
        $response = $this->client->post($this->readAppNotificatiosUrl, $this->payload);

        $jsonResponse = $response->json();

        return $jsonResponse;
    }

}
