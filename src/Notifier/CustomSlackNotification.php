<?php

namespace App\Notifier;

use Symfony\Component\Notifier\Bridge\Slack\Block\SlackDividerBlock;
use Symfony\Component\Notifier\Bridge\Slack\Block\SlackSectionBlock;
use Symfony\Component\Notifier\Bridge\Slack\SlackOptions;
use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Component\Notifier\Notification\ChatNotificationInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\RecipientInterface;

class CustomSlackNotification extends Notification implements
    ChatNotificationInterface
{
    public function __construct($transport)
    {
        $this->transport = $transport;

    }

    public function asChatMessage(RecipientInterface $recipient,string $transport = null): ?ChatMessage {
        
        if ($transport == 'slack') {
        // Add a custom emoji if the message is sent to Slack
        if ('slack' === $transport) {
            $chatMessage = new ChatMessage('Symfony Feature');

            $options = (new SlackOptions())
                ->block((new SlackSectionBlock())->text('My message'))
                ->block(new SlackDividerBlock())
                ->block(
                    (new SlackSectionBlock())
                        ->field('*Max Rating*')
                        ->field('5.0')
                        ->field('*Min Rating*')
                        ->field('1.0')
                );

            // Add the custom options to the chat message and send the message
            $chatMessage->options($options);

            return $chatMessage;

        }

        }

        // If you return null, the Notifier will create the ChatMessage
        // based on this notification as it would without this method.
        return null;
    }
}
