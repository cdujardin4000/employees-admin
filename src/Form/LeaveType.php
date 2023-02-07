<?php

namespace App\Form;

use App\Entity\Leave;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LeaveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('emp_no')
            ->add('type')
            ->add('from_date')
            ->add('to_date')
            ->add('employee')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Leave::class,
        ]);
    }
}
