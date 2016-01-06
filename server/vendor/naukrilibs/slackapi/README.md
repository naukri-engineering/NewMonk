Library for sending notifications to a slack channel. Currently, only the "Incoming Webhooks" Slack API is supported.

 - Usage:
---------

    use Naukri\SlackApi\IncomingWebhook\Factory as SlackApiIncomingWebhookFactory;
    $notifier = SlackApiIncomingWebhookFactory::getInstance()->getManager();
    $notifier->sendMessage(
        'NewMonk',                          // username
        $contact['slack']['webhookUrl'],    // incoming webhook url of the channel
        $contact['slack']['channel'],       // name of the channel
        $message,                           // message to be sent (supports markdown)
        [                                   // any extra options of the 'payload'
            'icon_emoji' => ':chart_with_upwards_trend:'
        ]
    );


 - More Details:
https://api.slack.com
