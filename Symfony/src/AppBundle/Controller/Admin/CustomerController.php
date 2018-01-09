<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Customer;
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
        $results = $rc->findByLastUpdate();

        //$saisie = $request;

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
     * @Route("/customer/add", name="app_admin_customer_add", defaults={"id" : null })
     * @Route("/customer/update/{id}", name="app_admin_customer_update")
     * @Method({"GET", "POST"})
     */
    public function addCustomerAction(Request $request, $id)
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $rcCustomer = $doctrine->getRepository(Customer::class);


        $customerEntity = $id ? $rcCustomer->find($id) : new Customer();
        $customerEntityType = CustomerType::class;

        /*                exit(dump($customerEntityType, $customerEntity));*/
        $form = $this->createForm($customerEntityType, $customerEntity);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            // en update
            if($id){
                // handle
                $saisie = $form->getData();

                //address
                //entity
                $userAddress = new UserAdress();

                //set entity

                $userAddress->setCity($form->get('address')->getData()->getCity());


                $customerEntity->addAddress($userAddress);

                $em->persist($userAddress);
                //car
                $car = new Car();
                $form->get('car')->getData()->getBrand()->getName();


                $customerEntity->addCar($car);
                $em->persist($car);
            }

            $dateNow = new \Datetime();
            // date  de création du client
            $customerEntity->setDatetimeCreateAccount($dateNow);


            //insertion
            $em->persist($customerEntity);

            $em->flush();

            //message flash
            $message = $id ? 'Le client a été mis à jour' : 'Le client a été inséré';
            $this->addFlash('notice', $message);

            // redirection
            $this->redirectToRoute('app_admin_customer_list');

        }

        return $this->render('admin/customer/addCustomer.html.twig', [
        'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/customer/{id}", name="app_admin_customer_view")
     */
    public function viewCustomer(Request $request, $id)
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
