<?php
namespace AppBundle\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class UserFormSubscriber implements EventSubscriberInterface
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

        // nouvel utilisateur
        if(!$entity->getId()){
            $form
                ->remove('address')
                ->remove('phone')
                ->remove('zipcode')
                ->remove('city')
                ->remove('country')
            ;
        }

        // mise à jour du profil
        else {
            $form
                ->remove('username')
                ->remove('password')
                ->remove('email')
            ;
        }



        //dump($data, $form, $entity);
    }

}