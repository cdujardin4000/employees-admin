<?php

namespace App\Form;

use App\Entity\Department;
use App\Entity\Employee;
use DateTime;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $roles = [
            'ROLE_SUPER_ADMIN',
            'ROLE_ADMIN',
            'ROLE_COMPTABLE',
            'ROLE_MANAGER',
            'ROLE_USER',
        ];
        $now = new DateTime();
        $builder
            ->add('first_name')
            ->add('last_name')
            ->add('birth_date', BirthdayType::class, [
                'by_reference' => false,
                'attr' => [
                    'class' => 'select2'
                ]
            ])
            ->add('gender', GenderType::class)
            ->add('photo')
            ->add('email')
            ->add('hire_date', DateType::class, [
                'years' => [$now->format('Y')],
                'months' => [$now->format('m')],
                'days' => [$now->format('d')],
            ])
            ->add('password')
            ->add('roles', ChoiceType::class, [
                'choices' => array_combine($roles, $roles),
                'expanded' => true,
                'multiple' => true
            ])
            ->add('isVerified');

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
        ]);
    }
}
