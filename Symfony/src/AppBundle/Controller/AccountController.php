<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Entity\UserToken;
use AppBundle\Event\AccountEvents;
use AppBundle\Event\AccountPasswordForgotEvent;
use AppBundle\Form\PasswordForgottenType;
use AppBundle\Form\PasswordRecoveryType;
use AppBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AccountController extends Controller
{
    /**
     * @Route("/signin", name="app_account_signin")
     */
    public function signinAction(Request $request)
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();

        $entity = new User();
        $entityType = UserType::class;

        $form = $this->createForm($entityType, $entity);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // encodage et rôle déplacés dans UserListener

            // insertion en base de données
            $em->persist($entity);
            $em->flush();

            // message flash
            $message = "Vous êtes bien enregistré";
            $this->addFlash('notice', $message);

            // redirection
            return $this->redirectToRoute('app_account_signin');
        }

        return $this->render('account/signin.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/password/forgotten", name="app_account_password_forgotten")
     */
    public function passwordForgottenAction(Request $request){
        $formType = PasswordForgottenType::class;

        $form = $this->createForm($formType);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // récupération de la saisie
            $data = $form->getData();
            $email = $data['email'];

            // service de déclenchement d'événement
            $eventDispatcherService = $this->get('event_dispatcher');

            // événement
            $event = new AccountPasswordForgotEvent();
            $event->setEmail($email);

            // déclencher l'évenement
            $eventDispatcherService->dispatch(AccountEvents::PASSWORD_FORGOT, $event);

            //exit;

            // message flash
            $message = 'mail envoyé';
            $this->addFlash('notice', $message);

            // redirection
            return $this->redirectToRoute('app_security_login');
        }

        return $this->render('account/passwordForgotten.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/password/recovery/{email}/{token}", name="app_account_password_recovery")
     */
    public function passwordRecoveryAction(Request $request, $email, $token){

        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $rcUser = $doctrine->getRepository(User::class);
        $rcUserToken = $doctrine->getRepository(UserToken::class);

        // recherche concordance entre email et token
        $userToken = $rcUserToken->findOneBy([
           'email' => $email,
           'token' => $token,
        ]);

        // si pas de concordance entre email et token
        if(!$userToken){
            // message flash
            $message = 'veuillez cliquer sur le lien envoyé par mail';
            $this->addFlash('info', $message);

            // redirection
            return $this->redirectToRoute('app_security_login');
        }

        $formType = PasswordRecoveryType::class;

        $form = $this->createForm($formType);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // récupération de la saisie
            $data = $form->getData();
            $password = $data['password'];

            // récupération du user
            $user = $rcUser->findOneBy([
                'email' => $email
            ]);

            // service d'encodage
            $passwordEncoderService = $this->get('security.password_encoder');
            $passwordEncoded = $passwordEncoderService->encodePassword($user, $password);
            $user->setPassword($passwordEncoded);

            // BDD
            $em->persist($user);
            $em->remove($userToken);
            $em->flush();

            // message flash
            $message = 'Mot de passe modifié';
            $this->addFlash('info', $message);

            // redirection
            return $this->redirectToRoute('app_security_login');
        }

        return $this->render('account/passwordRecovery.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/redirect-by-role", name="app_account_redirect_by_role")
     */
    public function redirectByRoleAction()
    {
        $user = $this->getUser();
        $route = null;

        if(in_array('ROLE_ADMIN', $user->getRoles())){
            $route = 'app_admin_customer_list';
        } elseif(in_array('ROLE_USER', $user->getRoles())){
            $route = 'app_profile_homepage_index';
        }

        return $this->redirectToRoute($route);

    }

}


























