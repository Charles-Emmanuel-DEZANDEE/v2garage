<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Entity\UserToken;
use AppBundle\Event\AccountEvents;
use AppBundle\Event\AccountPasswordForgotEvent;
use AppBundle\Service\StringUtilsService;
use AppBundle\Form\PasswordForgottenType;
use AppBundle\Form\PasswordRecoveryType;
use AppBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AccountController extends Controller
{
    /**
     * @Route("/admin/signin", name="app_account_signin")
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
    public function passwordForgottenAction(Request $request, StringUtilsService $stringUtils, \Swift_Mailer $mailer, \Twig_Environment $twig){
        $formType = PasswordForgottenType::class;

        $form = $this->createForm($formType);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // récupération de la saisie
            $data = $form->getData();
            $email = $data['email'];


            // doctrine
            $doctrine = $this->getDoctrine();
            $em = $doctrine->getManager();

            // vérification de l'existence de l'email dans la table User
            $userExists = $doctrine->getRepository(User::class)->findOneBy([
                'email' => $email
            ]) ? true : false;

            // vérification de l'existence de l'email dans la table UserToken
            $userTokenExists = $doctrine->getRepository(UserToken::class)->findOneBy(['email' => $email]);

            // date actuelle
            $date = new \DateTime();

            // utilisateur existant et token inexistant OU utilisateur existant et date d'expiration valide
            if(($userExists && !$userTokenExists) || ($userExists && $userTokenExists->getDateExpiration() < $date)){

                // création du token
                $token = $stringUtils->generateToken(16);

                // date d'expiration
                $date = new \DateTime('+1 day');

                // instance
                $userToken = new UserToken();
                $userToken->setToken($token);
                $userToken->setEmail($email);
                $userToken->setDateExpiration($date);

                // BDD
                $em->persist($userToken);
                $em->flush();

                // envoi d'un mail
                $message = (new \Swift_Message())
                    ->setFrom('garage@garage.fr')
                    ->setTo($email)
                    ->setSubject('oubli du mot de passe')
                    ->setContentType('text/html')
                    ->setBody(
                        $twig->render('mailing/account.password.forgotten.txt.twig', [
                            'token' => $token,
                            'email' => $email,
                        ])
                    )
                ;

                // envoi du mail
                $mailer->send($message);

                // message flash
                $message = 'mail envoyé';
                $this->addFlash('notice', $message);

            }
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

    /**
     * modification de l'email et mot de passe de l'utilisateur
     * @Route("/admin/account/add", name="app_admin_account_add")
     * @Route("/admin/account/update/{id}", name="app_admin_account_update")
     *
     */
    public function addAccountAction(Request $request, $id = null)
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $rcUser = $doctrine->getRepository(User::class);
        $typePage = 'ajout';


        $userEntity = $id ? $rcUser->find($id) : new User();


        $form = $this->createForm(UserType::class, $userEntity);
        $form->handleRequest($request);

        if ($id) {
            $typePage = 'modif';
        }


        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            // en update
            if ($id) {

                //pour permettre la mise à jour
                $pasDeDoublon = true;
            }
            else{
                // vérification qu'il n'y a pas de doublon
                $pasDeDoublon = $rcUser->userNotExist($user->getEmail());

            }
            if ($pasDeDoublon) {

                $password = $user->getPassword();

                // service d'encodage
                $passwordEncoderService = $this->get('security.password_encoder');
                $passwordEncoded = $passwordEncoderService->encodePassword($userEntity, $password);
                $user->setPassword($passwordEncoded);


                //insertion/modif du user
                $em->persist($userEntity);

                $em->flush();

                //message flash
                $message = $id ? 'Le compte a été mis à jour' : 'Le compte a été créé';
                $this->addFlash('info', $message);

                // redirection vers la page de la catégorie
                return $this->redirectToRoute('app_admin_customer_list');
            } else {
                //message flash
                $message = 'Le compte existe déjà';
                $this->addFlash('warning', $message);

                // redirection vers le formulaire
                return $this->redirectToRoute('app_admin_account_add');
            }


        }

        return $this->render('account/update.html.twig', [
            'form' => $form->createView(),
            'typePage' => $typePage,
            'unite' => $userEntity,
        ]);
    }


}


























