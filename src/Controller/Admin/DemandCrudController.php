<?php

namespace App\Controller\Admin;

use App\Entity\Demand;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class DemandCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Demand::class;
    }


    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->onlyOnIndex();

        yield ChoiceField::new('type')
            ->setChoices(fn () => [
                'reaffectation' => 'reaffectation',
                'augmentation' => 'augmentation',
            ]);

        yield TextField::new('about')
            ->setMaxLength(15);

        yield BooleanField::new('reviewed')
            ->hideWhenCreating()
            ->renderAsSwitch(false);

        yield BooleanField::new('status')
            ->hideWhenCreating()
            ->renderAsSwitch(false);

        yield AssociationField::new('employee')
            ->autocomplete();
            /**->setQueryBuilder(function (QueryBuilder $qb) {
                $qb->andWhere('entity.enabled = :true')
                    ->setParameter('enabled', true);
            });
            ->formatValue(static function ($value, Demand $demand): ?string {
                if (!$user = $demand->getEmployee()) {
                    return null;
                }
                return sprintf('%s&nbsp;(%s)', $user->getEmail(), $user->getDemands()->count());
            });**/
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->showEntityActionsInlined()
            ->setDefaultSort([
                'employee' => 'DESC',
                'reviewed' => 'ASC'
            ]);
    }
}
