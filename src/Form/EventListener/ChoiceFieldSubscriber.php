<?php

namespace App\Form\EventListener;

use App\Entity\Department;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


class ChoiceFieldSubscriber implements EventSubscriberInterface
{

    /**
     * @inheritDoc
     */
    #[ArrayShape([FormEvents::PRE_SET_DATA => "string"])] public static function getSubscribedEvents(): array
    {
        // Tells the dispatcher that you want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return [FormEvents::PRE_SET_DATA => 'preSetData'];
    }

    public function preSetData(FormEvent $event): void
    {
        $demandType = $event->getData();
        $form = $event->getForm();

        // checks if the Product object is "new"
        // If no data is passed to the form, the data is "null".
        // This should be considered a new "Product"
        if ($demandType  === 'affectation') {
            $form->add('about', EntityType::class, [
                'class' => Department::class
            ]);
        } else {
            $form->add('about', MoneyType::class, [
                'currency' => 'EUR',
            ]);
        }
    }
}