<?php

namespace App\Form\Type;


// ...
use App\Form\EventListener\ChoiceFieldSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ChoiceFieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('price');

        $builder->addEventSubscriber(new ChoiceFieldSubscriber());
    }

    // ...
}