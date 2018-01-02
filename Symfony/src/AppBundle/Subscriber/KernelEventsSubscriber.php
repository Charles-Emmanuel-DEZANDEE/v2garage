<?php
namespace AppBundle\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Translation\TranslatorInterface;

class KernelEventsSubscriber implements EventSubscriberInterface
{

    private $twig;
    private $maintenance;
    private $session;
    private $translator;

    public function __construct(\Twig_Environment $twig, $maintenance, SessionInterface $session, TranslatorInterface $translator)
    {
        $this->twig = $twig;
        $this->maintenance = $maintenance;
        $this->session = $session;
        $this->translator = $translator;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'maintenanceMode',
            KernelEvents::RESPONSE => 'securityHeaders',
            KernelEvents::RESPONSE => 'cookiesDisclaimer',
        ];
    }

    public function cookiesDisclaimer(FilterResponseEvent $event){
        // si l'alerte a été déjà été fermée
        if(!$this->session->has('cookie-disclaimer')){

            // récupération du contenu de la réponse
            $content = $event->getResponse()->getContent();

            // message
            $message = $this->translator->trans('headers.cookie_disclaimer');

            // modification de la réponse
            $newContent = str_replace('<body>', '<body><div class="container"><div class="row"><div class="col-sm-12"><div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close close-cookie-disclaimer" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>' . $message . ' </strong></div></div></div></div>', $content);

            // réponse
            $response = new Response($newContent);

            // retour de la réponse
            return $event->setResponse($response);
        }
    }

    public function securityHeaders(FilterResponseEvent $event){
        // récupération de la requête / réponse
        $response = $event->getResponse();
        $headers = [
            //'Content-Security-Policy' => 'default-src http://localhost:8000',
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'DENY',
            'X-XSS-Protection' => '1; mode=block',
        ];

        // modification de la réponse
        $newResponse = new Response(
            $response->getContent(),
            $response->getStatusCode(),
            $headers
        );

        // retourner une réponse : setReponse
        return $event->setResponse($newResponse);
    }

    public function maintenanceMode(GetResponseEvent $event){
        // maintenance active
        if($this->maintenance){
            // contenu de la réponse
            $contentResponse = $this->twig->render('maintenance/maintenance.html.twig');

            // réponse
            $response = new Response($contentResponse, 503);

            // retourner une réponse : setReponse
            return $event->setResponse($response);
        }
    }

}



























