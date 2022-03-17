<?php

namespace App\Notifier;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Notification\SmsNotificationInterface;
use Symfony\Component\Notifier\Recipient\SmsRecipientInterface;


class CustomSmsNotification extends Notification implements
SmsNotificationInterface
{
    public function __construct($transport)
    {
        $this->transport = $transport;

    }

    public function asSmsMessage(SmsRecipientInterface $recipient, string $transport = null): ?SmsMessage
    {
        if ($transport == 'ovhcloud') {
            dump($transport);

            $message = SmsMessage::fromNotification($this, $recipient, $transport);
            $message->subject('test envoi sms perso');

            return $message;

        }
        
        return null;
    }
}
