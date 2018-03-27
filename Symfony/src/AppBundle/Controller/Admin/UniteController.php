<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Unite;
use AppBundle\Form\UniteType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/admin")
 */
class UniteController extends Controller
{
    /**
     * @Route("/unite", name="app_admin_unite_list")
     * @Method({"GET", "POST"})
     */
    public function listAction(Request $request)
    {

        $doctrine = $this->getDoctrine();
        $rc = $doctrine->getRepository(Unite::class);
        $results = $rc->findAll();


        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $results,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/unite/list.html.twig', [
            'results' => $pagination,

        ]);
    }


    /**
     * @Route("/unite/add", name="app_admin_unite_add")
     * @Route("/unite/update/{idUnite}", name="app_admin_unite_update")
     *
     */
    public function addUniteAction(Request $request, $idUnite = null)
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $rcUnite = $doctrine->getRepository(Unite::class);
        $typePage = 'ajout';


        $uniteEntity = $idUnite ? $rcUnite->find($idUnite) : new Unite();


        $form = $this->createForm(UniteType::class, $uniteEntity);
        $form->handleRequest($request);

        if ($idUnite) {
            $typePage = 'modif';
        }


        if ($form->isSubmitted() && $form->isValid()) {
            $saisie = $form->getData();


            // vérification qu'il n'y a pas de doublon
            $pasDeDoublon = $rcUnite->uniteNotExist($saisie->getCode());


            // en update
            if ($idUnite) {

                //pour permettre la mise à jour
                $pasDeDoublon = true;
            }
            if ($pasDeDoublon) {


                //insertion de la unité
                $em->persist($uniteEntity);

                $em->flush();

                //message flash
                $message = $idUnite ? 'La unité a été mis à jour' : 'La unité a été insérée';
                $this->addFlash('info', $message);

                // redirection vers la page de la catégorie
                return $this->redirectToRoute('app_admin_unite_list');
            } else {
                //message flash
                $message = 'L\'unité existe déjà';
                $this->addFlash('warning', $message);

                // redirection vers le formulaire
                return $this->redirectToRoute('app_admin_unite_add');
            }


        }

        return $this->render('admin/unite/addUnite.html.twig', [
            'form' => $form->createView(),
            'typePage' => $typePage,
            'unite' => $uniteEntity,
        ]);
    }


}
