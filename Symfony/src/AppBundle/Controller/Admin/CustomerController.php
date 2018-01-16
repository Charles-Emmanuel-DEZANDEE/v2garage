<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Customer;
use AppBundle\Form\CustomerSearchType;
use AppBundle\Form\CustomerType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/admin")
 */
class CustomerController extends Controller
{
    /**
     * @Route("/customer", name="app_admin_customer_list")
     * @Method({"GET", "POST"})
     */
    public function listAction(Request $request)
    {

        $doctrine = $this->getDoctrine();
        $rc = $doctrine->getRepository(Customer::class);
        $results = $rc->findAllOrderByLastUpdate();

        // formulaire de rechercher par nom
        $form = $this->createForm(CustomerSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $saisie = $form->getData();
            $recherche = $saisie['name'];
            $results = $rc->findByNameOrderByLastUpdate($recherche);
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $results,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/customer/list.html.twig', [
            'results' => $pagination,
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/customer/add", name="app_admin_customer_add", defaults={"id" : null })
     * @Route("/customer/update/{id}", name="app_admin_customer_update")
     *
     */
    public function addCustomerAction(Request $request, $id = null)
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $rcCustomer = $doctrine->getRepository(Customer::class);
        $typePage = 'ajout';


        $customerEntity = $id ? $rcCustomer->find($id) : new Customer();

        $form = $this->createForm(CustomerType::class, $customerEntity);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $saisie = $form->getData();
            $pasDeDoublon = $rcCustomer->customerNotExist($saisie->getEmail(),$saisie->getLastName(),$saisie->getAddressZipcode());


            // en update
            if($id){

                //pour permettre la mise à jour
                $pasDeDoublon = true;
                //personnalisation du formulaire
                $typePage = 'modif';
            }
            if ($pasDeDoublon){


                //insertion
                $em->persist($customerEntity);

                $em->flush();

                //message flash
                $message = $id ? 'Le client a été mis à jour' : 'Le client a été inséré';
                $this->addFlash('info', $message);

                if($id) {

                    // redirection vers la page du client
                    return $this->redirectToRoute('app_admin_customer_view', array(
                            'id' => $customerEntity->getId()
                        )
                    );
                }

                // redirection vers la saisie du véhicule
                //todo modif la route
                return $this->redirectToRoute('app_admin_customer_list', array(
                    'id' => $customerEntity->getId()
                    )
                );
            }
            else{
                //message flash
                $message = 'Le client existe déjà';
                $this->addFlash('warning', $message);

                // redirection vers le formulaire
                return $this->redirectToRoute('app_admin_customer_add');
            }


        }

        return $this->render('admin/customer/addCustomer.html.twig', [
            'form' => $form->createView(),
            'typePage' => $typePage
        ]);
    }
    /**
     * @Route("/customer/{id}", name="app_admin_customer_view")
     */
    public function viewCustomer(Request $request, $id = null)
    {
        if ($id == null){
            $message = 'Veuillez selectionner un client';
            $this->addFlash('danger', $message);
            return $this->redirectToRoute('app_admin_customer_list');
        }

        $doctrine = $this->getDoctrine();
        $rc = $doctrine->getRepository(Customer::class);
        $result = $rc->find($id);

        dump($result);

        return $this->render('admin/customer/viewCustomer.html.twig', [
            'result' => $result
        ]);
    }

}
