<?php

namespace App\Service;

use Twilio\Rest\Client;

class TwilioSmsService
{
    private $twilioSid;
    private $twilioAuthToken;
    private $twilioPhoneNumber;

    public function __construct(string $twilioSid, string $twilioAuthToken, string $twilioPhoneNumber)
    {
        $this->twilioSid = $twilioSid;
        $this->twilioAuthToken = $twilioAuthToken;
        $this->twilioPhoneNumber = $twilioPhoneNumber;
    }

    public function sendSms(string $to, string $message): void
    {
        if (!str_starts_with($to, '+')) {
            $to = '+216' . ltrim($to, '0'); 
        }
        $client = new Client($this->twilioSid, $this->twilioAuthToken);

        $client->messages->create(
            $to,
            [
                'from' => $this->twilioPhoneNumber,
                'body' => $message
            ]
        );
    }
}
