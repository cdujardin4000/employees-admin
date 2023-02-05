<?php

namespace App\Form;

use App\Entity\CarsEmp;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarsEmpType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cars_emp_id')
            ->add('emp_no')
            ->add('car_id')
            ->add('from_date')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CarsEmp::class,
        ]);
    }
}
