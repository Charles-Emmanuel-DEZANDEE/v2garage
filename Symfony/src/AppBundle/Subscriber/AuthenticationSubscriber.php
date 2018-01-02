<?php

namespace AppBundle\Subscriber;

use AppBundle\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;

class AuthenticationSubscriber implements EventSubscriberInterface
{

    private $mailer;
    private $twig;
    private $request;
    private $session;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig, RequestStack $request, SessionInterface $session)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->request = $request->getMasterRequest();
        $this->session = $session;
    }

    /*
     * retourne un tableau d'événement relié à un gestionnaire d'événement
     * */
    public static function getSubscribedEvents()
    {
        return [
            AuthenticationEvents::AUTHENTICATION_SUCCESS => 'success',
            AuthenticationEvents::AUTHENTICATION_FAILURE => 'failure'
        ];
    }

    public function failure(AuthenticationFailureEvent $event){
        // tester l'existence de la clé
        if(!$this->session->has('authentication_failure_count')){
            // création de la clé
            $this->session->set('authentication_failure_count', 1);
        }
        // si la clé existe
        else {
            $count = $this->session->get('authentication_failure_count');
            $count++;
            $this->session->set('authentication_failure_count', $count);
        }
    }

    public function success(AuthenticationEvent $event){
        /*
         * type User : l'utilisateur est connecté
         * type AuthenticationEvent : l'utilisateur n'est pas connecté
         * */
        if($event->getAuthenticationToken()->getUser() instanceof User){
            // utilisteur connecté
            $user = $event->getAuthenticationToken()->getUser();

            /*
             * création d'un message
             *   - setFrom (expéditeur) est obligatoire
             *   - setTo (destinataire); fonctionnel uniquement en mode prod
             */
            $message = (new \Swift_Message())
                ->setFrom('garage@garage.fr')
                ->setTo($user->getEmail())
                ->setSubject('connexion')
                ->setBody(
                    $this->twig->render('mailing/authentication.success.txt.twig', [
                        'username' => $user->getUsername(),
                        'ip' => $this->request->getClientIp()
                    ])
                )
                ->addPart(
                    $this->twig->render('mailing/authentication.success.html.twig', [
                        'username' => $user->getUsername(),
                        'ip' => $this->request->getClientIp()
                    ])
                , 'text/html')
            ;

            // envoi du mail
            $this->mailer->send($message);
        }
    }

}
















