<?php

namespace App\EasyAdmin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldConfiguratorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FieldDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use JetBrains\PhpStorm\Pure;
use function Symfony\Component\String\u;

class TruncateLongTextConfigurator implements FieldConfiguratorInterface
{
    private const MAX_LENGTH = 155;

    #[Pure] public function supports(FieldDto $field, EntityDto $entityDto): bool
    {
        return $field->getFieldFqcn() === TextareaField::class;
    }

    public function configure(FieldDto $field, EntityDto $entityDto, AdminContext $context): void
    {
        $crud = $context->getCrud();

        if ($crud?->getCurrentPage() === Crud::PAGE_DETAIL) {
            return;
        }

        if (strlen($field->getFormattedValue()) <= self::MAX_LENGTH) {
            return;
        }

        $truncatedValue = u($field->getFormattedValue())
            ->truncate(self::MAX_LENGTH, '...', false);

        $field->setFormattedValue($truncatedValue);
    }
}