<?php

namespace App\Service;

use Psr\Log\LoggerInterface;

class MessageGenerator
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function getNewArrivalMessage(): string
    {
        $this->logger->info('New recruit for your team!');
        // ...
    }
}