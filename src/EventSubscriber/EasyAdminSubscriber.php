<?php

namespace App\EventSubscriber;

use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use App\Entity\Department;
use App\Repository\DepartmentRepository;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

abstract class EasyAdminSubscriber implements EventSubscriberInterface, PasswordAuthenticatedUserInterface
{

    public function __construct(DepartmentRepository $departmentRepository, EmployeeRepository $employeeRepository, UserPasswordHasherInterface $passwordHasher)
    {
        $this->employeeRepository = $employeeRepository;
        $this->departmentRepository = $departmentRepository;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @inheritDoc
     */
    #[ArrayShape([
        BeforeEntityPersistedEvent::class => "string[]",
        AfterEntityPersistedEvent::class => "string[]",
    ])] public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => [
                ['setDeptNoService', 512],
                ['hashPasswordService', 1024]
            ],
            AfterEntityPersistedEvent::class => ['empToDeptService']
        ];
    }

    public function setDeptNoService(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Department)) {
            return;
        }

        $newDeptNo = $this->departmentRepository->getLastDepartmentRemoveDAndAddOneBeforeAddingD();
        $entity->setDeptNo($newDeptNo);
    }

    public function hashPasswordService(BeforeEntityPersistedEvent $event, UserPasswordHasherInterface $passwordHasher)
    {

        $entity = $event->getEntityInstance();

        if (!$entity instanceof Employee) {
            return;
        }

        $plaintextPassword  = $entity->getPassword();

        // hash the password (based on the security.yaml config for the $user class)
        $hashedPassword = $passwordHasher->hashPassword(
            $entity,
            $plaintextPassword
        );
        dd($hashedPassword);
        $entity->setPassword($hashedPassword);
        dd($entity);
    }

    public function empToDeptService(AfterEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Employee)) {
            return;
        }

        dd($entity);
    }
}