<?php

namespace App\Form;

use App\Entity\Demand;
use App\Entity\Department;
use App\Entity\Employee;
use App\Form\EventListener\ChoiceFieldSubscriber;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DemandType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('type', ChoiceType::class, [
                'choices' =>  [
                    'reaffectation' => 'reaffectation',
                    'augmentation' => 'augmentation',
                ]
            ])

            ->add('employee', HiddenType::class,[
                    'data'  => $_GET['id'],
           ]);
           $builder->addEventSubscriber(new ChoiceFieldSubscriber());
    }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Demand::class,
        ]);
    }
}
