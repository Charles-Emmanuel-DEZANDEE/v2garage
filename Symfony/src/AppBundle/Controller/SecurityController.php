<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="app_security_login")
     */
    public function loginAction(Request $request, AuthenticationUtils $authUtils)
    {
//        // test du nombre d'échec de connexion dans la session
//        if($request->getSession()->get('authentication_failure_count') >= $this->getParameter('authentication_failure_max')){
//            // suppression de la clé en session
//            $request->getSession()->remove('authentication_failure_count');
//
//            // message
//            $message = 'Vous semblez avoir oublié votre mot de passe';
//            $this->addFlash('notice', $message);
//
//            // redirection
//            return $this->redirectToRoute('app.account.password.forgotten');
//        }
//
//        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();
//
//        // last username entered by the user
//        $lastUsername = $authUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
//            'last_username' => $lastUsername,
           'error'         => $error,
        ]);
    }

    /**
     * @Route("/logout", name="app_security_logout")
     */
    public function logoutAction()
    {

    }

}


























