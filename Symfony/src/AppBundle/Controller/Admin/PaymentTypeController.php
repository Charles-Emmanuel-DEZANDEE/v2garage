<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\PaymentType;
use AppBundle\Form\PaymentTypeType;
use AppBundle\Service\OrderPaymentTypeService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/admin")
 */
class PaymentTypeController extends Controller
{
    /**
     * @Route("/paymentType", name="app_admin_paymentType_list")
     * @Method({"GET", "POST"})
     */
    public function listAction(Request $request)
    {

        $doctrine = $this->getDoctrine();
        $rc = $doctrine->getRepository(PaymentType::class);
        $results = $rc->findAll();


        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $results,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/paymentType/list.html.twig', [
            'results' => $pagination,

        ]);
    }

    /**
     * @Route("/paymentType/add", name="app_admin_paymentType_add")
     * @Route("/paymentType/update/{id}", name="app_admin_paymentType_update")
     *
     */
    public function addPaymentTypeAction(Request $request, $id = null)
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $rcPaymentType = $doctrine->getRepository(PaymentType::class);
        $typePage = 'ajout';


        $paymentTypeEntity = $id ? $rcPaymentType->find($id) : new PaymentType();


        $form = $this->createForm(PaymentTypeType::class, $paymentTypeEntity);
        $form->handleRequest($request);

        if ($id) {
            $typePage = 'modif';
        }


        if ($form->isSubmitted() && $form->isValid()) {
            $saisie = $form->getData();


            // vérification qu'il n'y a pas de doublon
            $pasDeDoublon = $rcPaymentType->paymentTypeNotExist($saisie->getName());


            // en update
            if ($id) {

                //pour permettre la mise à jour
                $pasDeDoublon = true;
            }
            if ($pasDeDoublon) {


                //insertion de la categorie
                $em->persist($paymentTypeEntity);

                $em->flush();

                //message flash
                $message = $id ? 'La categorie a été mis à jour' : 'La categorie a été insérée';
                $this->addFlash('info', $message);

                // redirection vers la page de la catégorie
                return $this->redirectToRoute('app_admin_paymentType_list');
/*                return $this->redirectToRoute('app_admin_service_list', array('idPaymentType' => $idPaymentType));*/
            } else {
                //message flash
                $message = 'La categorie existe déjà';
                $this->addFlash('warning', $message);

                // redirection vers le formulaire
                return $this->redirectToRoute('app_admin_paymentType_add');
            }


        }

        return $this->render('admin/paymentType/addPaymentType.html.twig', [
            'form' => $form->createView(),
            'typePage' => $typePage,
            'paymentType' => $paymentTypeEntity,
        ]);
    }

    /**
     * @Route("/paymentType/delete/{idPaymentType}", name="app_admin_paymentType_delete")
     */
    public function deleteAction(Request $request, $idPaymentType)
    {
        // sélection de l'entité à supprimer
        $result = $this->getDoctrine()->getRepository(PaymentType::class)->find($idPaymentType);

        // suppression
        $em = $this->getDoctrine()->getManager();
        $em->remove($result);
        $em->flush();

        //message flash
        $message = 'La categorie a été supprimée';
        $this->addFlash('warning', $message);

        // redirection
        return $this->redirectToRoute('app_admin_paymentType_list');
    }


}
