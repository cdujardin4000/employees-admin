<?php

// src/Service/SiteUpdateManager.php
namespace App\Service;

use App\Service\MessageGenerator;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class NewEmpService
{
    private $messageGenerator;
    private $mailer;

    public function __construct(MessageGenerator $messageGenerator, MailerInterface $mailer)
    {
        $this->messageGenerator = $messageGenerator;
        $this->mailer = $mailer;
    }

    /**
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function notifyNewArrival(): bool
    {
        $newArrivalMessage = $this->messageGenerator->getNewArrivalMessage();

        $email = (new Email())
            ->from('admin@example.com')
            ->to('manager@example.com')
            ->subject('New teammates arrival!')
            ->text('Someone just joined your department: '.$newArrivalMessage);

        $this->mailer->send($email);

        // ...

        return true;
    }
}