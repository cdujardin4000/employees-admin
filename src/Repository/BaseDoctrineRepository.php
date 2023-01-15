<?php

namespace App\Repository;

use Doctrine\Persistence\ObjectManager;
use Psr\Log\LoggerInterface;


abstract class BaseDoctrineRepository
{
    protected $objectManager;
    protected $logger;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    // ...
}