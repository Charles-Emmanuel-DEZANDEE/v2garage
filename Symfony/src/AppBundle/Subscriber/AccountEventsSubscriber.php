<?php
namespace AppBundle\Subscriber;

use AppBundle\Entity\User;
use AppBundle\Entity\UserToken;
use AppBundle\Event\AccountEvents;
use AppBundle\Event\AccountPasswordChangeEvent;
use AppBundle\Event\AccountPasswordForgotEvent;
use AppBundle\Service\StringUtilsService;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Translation\TranslatorInterface;

class AccountEventsSubscriber implements EventSubscriberInterface
{

    private $doctrine;
    private $translator;
    private $stringUtils;
    private $mailer;
    private $twig;

    public function __construct(ManagerRegistry $doctrine, TranslatorInterface $translator, StringUtilsService $stringUtils, \Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->doctrine = $doctrine;
        $this->translator = $translator;
        $this->stringUtils = $stringUtils;
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public static function getSubscribedEvents()
    {
        return [
            AccountEvents::PASSWORD_FORGOT => 'passwordForgot',
            AccountEvents::PASSWORD_CHANGE => 'passwordChange',
        ];
    }

    public function passwordForgot(AccountPasswordForgotEvent $event){
        // doctrine
        $em = $this->doctrine->getManager();

        // vérification de l'existence de l'email dans la table User
        $userExists = $this->doctrine->getRepository(User::class)->findOneBy([
            'email' => $event->getEmail()
        ]) ? true : false;

        // vérification de l'existence de l'email dans la table UserToken
        $userTokenExists = $this->doctrine->getRepository(UserToken::class)->findOneBy(['email' => $event->getEmail()]);

        // date actuelle
        $date = new \DateTime();

        // utilisateur existant et token inexistant OU utilisateur existant et date d'expiration valide
        if($userExists && !$userTokenExists || $userExists && $userTokenExists->getDateExpiration() < $date){

            // création du token
            $token = $this->stringUtils->generateToken(16);

            // date d'expiration
            $date = new \DateTime('+1 day');

            // instance
            $userToken = new UserToken();
            $userToken->setToken($token);
            $userToken->setEmail($event->getEmail());
            $userToken->setDateExpiration($date);

            // BDD
            $em->persist($userToken);
            $em->flush();

            // envoi d'un mail
            $message = (new \Swift_Message())
                ->setFrom('garage@garage.fr')
                ->setTo($event->getEmail())
                ->setSubject('oubli du mot de passe')
                ->setContentType('text/html')
                ->setBody(
                    $this->twig->render('mailing/account.password.forgotten.txt.twig', [
                        'token' => $token,
                        'email' => $event->getEmail(),
                    ])
                )
            ;

            // envoi du mail
            $this->mailer->send($message);
        }

    }





    public function passwordChange(AccountPasswordChangeEvent $event){
        dump('password change event');
    }

}