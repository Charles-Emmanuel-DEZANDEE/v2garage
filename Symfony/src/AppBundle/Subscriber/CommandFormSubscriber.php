<?php
namespace AppBundle\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class CommandFormSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::POST_SET_DATA => 'postSetData'
        ];
    }

    public function postSetData(FormEvent $event)
    {
        // données saisies
        $data = $event->getData();

        // builder du formulaire
        $form = $event->getForm();

        // objet relié au formulaire
        $entity = $form->getData();

        // validation du devis
        if(!$entity->getId()){
            $form
                ->remove('address')

            ;
        }

        // création de la fcature
        else {
            $form
                ->remove('username')
                
            ;
        }
        //paiement de la facture



        //dump($data, $form, $entity);
    }

}