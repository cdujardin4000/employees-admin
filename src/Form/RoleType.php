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
                'USER' => 'ROLE_USER',
                'ADMIN' => 'ROLE_ADMIN',
                'MANAGER' => 'ROLE_MANAGER',
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