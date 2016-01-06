<?php

namespace Naukri\SlackApi\IncomingWebhook;

class Manager {
    public function sendMessage($username, $webhookUrl, $channel, $message, $payloadOptions = []) {
        $payload = json_encode(array_merge($payloadOptions, [
            'text' => $message,
            'channel' => $channel,
            'username' => $username,
            'mrkdwn' => true
        ]));

        $ch = curl_init();
        $curlConfig = [
            CURLOPT_URL => $webhookUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => [
                'payload' => $payload
            ]
        ];
        curl_setopt_array($ch, $curlConfig);
        if (curl_exec($ch) === false) {
            throw new \Naukri\SlackApi\Exception(curl_error($ch), curl_errno($ch));
        }
        curl_close($ch);
    }
}
