<?php

namespace App\Controller\Admin;

use App\Entity\Car;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CarCrudController extends AbstractCrudController
{
    protected $container;


    public static function getEntityFqcn(): string
    {
        return Car::class;
    }

    public function createEntity(string $entityFqcn): Car
    {
        return new Car();
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->showEntityActionsInlined()
            ->setDefaultSort([
                'car_id' => 'ASC',
            ]);
    }
}
