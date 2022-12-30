<?php

namespace App\Form;

use App\Entity\Department;
use App\Entity\DeptEmp;
use App\Entity\Employee;
use DateTime;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeptEmpType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $today = new DateTime();
        $builder
            /**->add('emp_no', EntityType::class, [
                'class' => Employee::class,
                'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('e')
                    ->select('e.id')
                    ->orderBy('e.id', 'DESC');
                },
                'choice_label' => 'id',

            ])**/
            ->add('dept_no', EntityType::class, [
                'class' => Department::class,
                'by_reference' => false,
                'attr' => [
                    'class' => 'select2'
                ],
            ])
            ->add('from_date', DateTimeType::class, [
                'years' => [$today->format('Y')],
                'months' => [$today->format('m')],
                'days' => [$today->format('d')],
            ])
            ->add('to_date', DateTimeType::class, [
                'years' => [1999],
                'months' => [01],
                'days' => [01],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DeptEmp::class,
        ]);
    }
}
