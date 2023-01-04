<?php

namespace App\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoleType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => [
                'EMPLOYEE' => 'ROLE_EMPLOYEE',
                'MANAGER' => 'ROLE_MANAGER',
                'ADMIN' => 'ROLE_ADMIN',
                'SUPER ADMIN' => 'ROLE_SUPER_ADMIN',
            ],
            'placeholder' => 'Choose an option',
            'attr' => [
                'class' => 'select2'
            ]
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}