<?php

namespace App\Notifier;
use Symfony\Component\Notifier\Message\EmailMessage;
use Symfony\Component\Notifier\Notification\EmailNotificationInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\EmailRecipientInterface;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Header\Headers;
use Symfony\Component\Mime\Message;
use Symfony\Component\Mime\Part\Multipart\AlternativePart;
use Symfony\Component\Mime\Part\TextPart;
use Symfony\Component\Mime\RawMessage;

class CustomEmailNotification extends Notification implements
EmailNotificationInterface
{
    public function __construct($transport)
    {
        $this->transport = $transport;

    }

    public function asEmailMessage(EmailRecipientInterface $recipient, string $transport = null): ?EmailMessage
    {
        
        $email = (new TemplatedEmail())
        ->subject('Thanks for signing up!')
    
        // path of the Twig template to render
        ->htmlTemplate('emails/comment_notification.html.twig')
    
        // pass variables (name => value) to the template
        ->context([
            'expiration_date' => new \DateTime('+7 days'),
            'username' => 'foo',
        ]);

        $message = new EmailMessage($email);

        return $message;
        
        }
}
