<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Brand;
use AppBundle\Entity\Customer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin")
 */
class CustomerController extends Controller
{
    /**
     * @Route("/customer", name="app_admin_customer_list")
     */
    public function listAction(Request $request)
    {

        $doctrine = $this->getDoctrine();
        $rc = $doctrine->getRepository(Customer::class);
        $results = $rc->findAll();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $results,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/customer/list.html.twig', [
            'results' => $pagination,

        ]);
    }

    /**
     * @Route("/customer/add", name="app_admin_customer_add")
     */
    public function addCustomerAction(Request $request)
    {
        // Création de l'entité
        $customer = new Customer();
        $customer->setFirstname('toto');
        // On peut ne pas définir ni la date ni la publication,
        // car ces attributs sont définis automatiquement dans le constructeur

        // On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();

        // Étape 1 : On « persiste » l'entité
        $em->persist($customer);

        // Étape 2 : On « flush » tout ce qui a été persisté avant
        $em->flush();
        // message flash
        $this->addFlash('notice', 'marque bien enregistrée.');


        // Si on n'est pas en POST, alors on affiche le formulaire
        return $this->render('admin/customer/addCustomer.html.twig');
    }
    /**
     * @Route("/customer/{id}", name="app_admin_customer_view")
     */
    public function viewBrand(Request $request, $id)
    {
        $doctrine = $this->getDoctrine();
        $rc = $doctrine->getRepository(Customer::class);
        $result = $rc->find($id);

        //dump($results); exit;

        return $this->render('admin/customer/viewCustomer.html.twig', [
            'result' => $result
        ]);
    }

}
