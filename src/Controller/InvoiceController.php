<?php

namespace App\Controller;

use App\Notifier\CustomEmailNotification;
use App\Notifier\CustomSlackNotification;
use App\Notifier\CustomSmsNotification;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Notifier\ChatterInterface;
use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;

class InvoiceController extends AbstractController
{

    #[Route('/invoice/create/urgent', name: 'urgent')]
    public function createUrgent(NotifierInterface $notifier) 
    {
        //SMS

        // Create a Notification that has to be sent
        // méthode qui envoie un sms car urgent : seul le 'new invoice' est envoyé (??)
        $notification = (new Notification('Nouveau Virement effectué sur votre compte bancaire'))
            ->content('Votre virement a bien été effectué.')
            ->importance(Notification::IMPORTANCE_URGENT);


        // The receiver of the Notification
        // Si je ne mets pas l'email "", il y a une erreur
        $recipient = new Recipient(
            "",
            "+330662158004"
        );

        // Send the notification to the recipient
        $notifier->send($notification, $recipient);



        return $this->render('notifications/index.html.twig', [
            'statut' => 'URGENT -> sms'
        ]
        );
    }

    #[Route('/invoice/create/high', name: 'high')]
    public function createHigh(NotifierInterface $notifier) 
    {
        // EMAIL

        // Create a Notification that has to be sent
        $notification = (new Notification('New Invoice'))
            ->content('You got a new invoice for 15 EUR.');

        // The receiver of the Notification
        $recipient = new Recipient(
            "noble.jka@protonmail.com"
        );

        // Send the notification to the recipient
        $notifier->send($notification, $recipient);


        return $this->render('notifications/index.html.twig', [
            'statut' => 'HIGH -> email'
        ]
        );
    }

    #[Route('/invoice/create/medium', name: 'medium')]
    public function createMedium(NotifierInterface $notifier) 
    {
        // SLACK

        // Create a Notification that has to be sent
        $notification = (new Notification('Mis à jour'))
            ->content('Vous avez reçu un virement de 100 euros');

        // Send the notification to the recipient
        $notifier->send($notification);

        return $this->render('notifications/index.html.twig', [
            'statut' => 'MEDIUM -> slack'
        ]
        );
    }


    #[Route('/invoice/create/low', name: 'low')]
    public function createLow(NotifierInterface $notifier) 
    {
        // BROWSER

        $notification = (new Notification('Can you check your submission? There are some problems with it.'))
                ->importance(Notification::IMPORTANCE_LOW);


        // Send the notification to the recipient
        $notifier->send($notification);


        return $this->render('notifications/index.html.twig', [
            'statut' => 'LOW -> browser'
        ] 
        );
    }

    #[Route('/invoice/create/email/custom', name: 'email_custom')]
    public function createEmailCustom(NotifierInterface $notifier, MailerInterface $mailer) 
    {
        // personnalisation complète en interne
        // $email = (new NotificationEmail())
        //         ->from('admin@example.com')
        //         ->to('noble.jka@protonmail.com')
        //         ->subject('My first notification email via Symfony')
        //         ->markdown(<<<EOF
        //             There is a **problem** on your website, you should investigate it
        //             right now. Or just wait, the problem might solves itself automatically,
        //             we never know
        //             EOF
        //         )
        //         ->action('More info?', 'https://example.com/')
        //         ->importance(NotificationEmail::IMPORTANCE_HIGH);
        // $mailer->send($email);


        //Personnalisation depuis le customEmailNotification
        $notification = (new CustomEmailNotification('email'))
                ->importance(Notification::IMPORTANCE_HIGH);
        
        dump($notification);

        // The receiver of the Notification
        $recipient = new Recipient(
            "noble.jka@protonmail.com"
        );

        // Send the notification to the recipient
        $notifier->send($notification, $recipient);

        

        return $this->render('notifications/index.html.twig', [
            'statut' => 'CUSTOM -> mail'
        ] 
        );
    }

    #[Route('/invoice/create/slack/custom', name: 'slack_custom')]
    public function createSlackCustom(NotifierInterface $notifier) 
    {


        $notification = (new CustomSlackNotification('slack'))
                ->importance(Notification::IMPORTANCE_MEDIUM);

        dump($notification);

        $notifier->send($notification);
        

        return $this->render('notifications/index.html.twig', [
            'statut' => 'CUSTOM -> slack'
        ] 
        );
    }

    #[Route('/invoice/create/sms/custom', name: 'sms_custom')]
    public function createSmsCustom(NotifierInterface $notifier) 
    {

        $notification = (new CustomSmsNotification('sms'))
            ->importance(Notification::IMPORTANCE_URGENT);

        // The receiver of the Notification
        $recipient = new Recipient(
            "",
            "+330662158004"
        );

        $notifier->send($notification, $recipient);

        

        return $this->render('notifications/index.html.twig', [
            'statut' => 'CUSTOM -> sms'
        ] 
        );
    }

    #[Route('/invoice/create/chatemail/custom', name: 'email_slack_custom')]
    public function createEmailSlackCustom(NotifierInterface $notifier) 
    {

        $notification = (new CustomSlackNotification('slack'))
            ->importance(Notification::IMPORTANCE_MEDIUM);

        $notifier->send($notification);

        $notification = (new CustomEmailNotification('email'))
            ->importance(Notification::IMPORTANCE_HIGH);

        // The receiver of the Notification
        $recipient = new Recipient(
            "noble.jka@protonmail.com"
        );
        
        $notifier->send($notification, $recipient);
        

        return $this->render('notifications/index.html.twig', [
            'statut' => 'CUSTOM -> email/slack'
        ] 
        );
    }

}