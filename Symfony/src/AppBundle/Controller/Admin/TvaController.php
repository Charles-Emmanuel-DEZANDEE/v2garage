<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\TaxRate;
use AppBundle\Form\TaxRateType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/admin")
 */
class TvaController extends Controller
{
    /**
     * @Route("/tva", name="app_admin_tva_list")
     * @Method({"GET", "POST"})
     */
    public function listAction(Request $request)
    {

        $doctrine = $this->getDoctrine();
        $rc = $doctrine->getRepository(TaxRate::class);
        $results = $rc->findAll();


        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $results,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/tva/list.html.twig', [
            'results' => $pagination,

        ]);
    }


    /**
     * @Route("/tva/add", name="app_admin_tva_add")
     * @Route("/tva/update/{idTva}", name="app_admin_tva_update")
     *
     */
    public function addUniteAction(Request $request, $idTva = null)
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $rcTva = $doctrine->getRepository(TaxRate::class);
        $typePage = 'ajout';


        $tvaEntity = $idTva ? $rcTva->find($idTva) : new TaxRate();


        $form = $this->createForm(TaxRateType::class, $tvaEntity);
        $form->handleRequest($request);

        if ($idTva) {
            $typePage = 'modif';
        }


        if ($form->isSubmitted() && $form->isValid()) {
            $saisie = $form->getData();


            // vérification qu'il n'y a pas de doublon
            $pasDeDoublon = $rcTva->tvaNotExist($saisie->getValue());


            // en update
            if ($idTva) {

                //pour permettre la mise à jour
                $pasDeDoublon = true;
            }
            if ($pasDeDoublon) {


                //insertion de la unité
                $em->persist($tvaEntity);

                $em->flush();

                //message flash
                $message = $idTva ? 'La unité a été mis à jour' : 'La unité a été insérée';
                $this->addFlash('info', $message);

                // redirection vers la page de la catégorie
                return $this->redirectToRoute('app_admin_tva_list');
            } else {
                //message flash
                $message = 'L\'unité existe déjà';
                $this->addFlash('warning', $message);

                // redirection vers le formulaire
                return $this->redirectToRoute('app_admin_tva_add');
            }


        }

        return $this->render('admin/tva/addTva.html.twig', [
            'form' => $form->createView(),
            'typePage' => $typePage,
            'unite' => $tvaEntity,
        ]);
    }


}
