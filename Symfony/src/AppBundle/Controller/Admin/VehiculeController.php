<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Customer;
use AppBundle\Entity\Vehicule;
use AppBundle\Form\VehiculeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/admin")
 */
class VehiculeController extends Controller
{
    /**
     * @Route("/vehicule", name="app_admin_vehicule_list")
     * @Method({"GET", "POST"})
     */
    public function listAction(Request $request)
    {

        $doctrine = $this->getDoctrine();
        $rc = $doctrine->getRepository(Vehicule::class);
        $results = $rc->findAll();


        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $results,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/vehicule/list.html.twig', [
            'results' => $pagination,

        ]);
    }

    /**
     * @Route("/vehicule/add/{idCustomer}", name="app_admin_vehicule_add")
     * @Route("/vehicule/update/{idCustomer}/{idVehicule}", name="app_admin_vehicule_update")
     *
     */
    public function addVehiculeAction(Request $request, $idCustomer, $idVehicule= null)
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $rcVehicule = $doctrine->getRepository(Vehicule::class);
        $typePage = 'ajout';


            $CustomerEntity = $doctrine->getRepository(Customer::class)->find($idCustomer);


            $vehiculeEntity = $idVehicule ? $rcVehicule->find($idVehicule) : new Vehicule($CustomerEntity);


        $form = $this->createForm(VehiculeType::class, $vehiculeEntity);
        $form->handleRequest($request);

        if($idVehicule){
            $typePage = 'modif';
        }


        if ($form->isSubmitted() && $form->isValid()) {
            $saisie = $form->getData();

            // vérification qu'il n'y a pas de doublon
            $pasDeDoublon = $rcVehicule->vehiculeNotExist($saisie->getRegistration());


            // en update
            if($idVehicule){

                //pour permettre la mise à jour
                $pasDeDoublon = true;
            }
            if ($pasDeDoublon){

                //mise à jour de la date de derniére action du client
                $CustomerEntity->setLastActionDate(new \DateTime());
                $em->persist($CustomerEntity);

                //insertion du véhicule
                $em->persist($vehiculeEntity);

                $em->flush();

                //message flash
                $message = $idVehicule ? 'Le véhicule a été mis à jour' : 'Le véhicule a été inséré';
                $this->addFlash('info', $message);

                // redirection vers la saisie du véhicule
                return $this->redirectToRoute('app_admin_vehicule_list', array('id' => $vehiculeEntity->getId()));
            }
            else{
                //message flash
                $message = 'Le véhicule existe déjà';
                $this->addFlash('warning', $message);

                // redirection vers le formulaire
                return $this->redirectToRoute('app_admin_vehicule_add');
            }


        }

        return $this->render('admin/vehicule/addVehicule.html.twig', [
            'form' => $form->createView(),
            'typePage' => $typePage,
            'client' => $CustomerEntity,
        ]);
    }
    /**
     * @Route("/vehicule/{id}", name="app_admin_vehicule_view")
     */
    public function viewVehicule(Request $request, $id)
    {
        if ($id == null){
            $message = 'Veuillez selectionner un véhicule';
            $this->addFlash('danger', $message);
            return $this->redirectToRoute('app_admin_customer_list');
        }
        $doctrine = $this->getDoctrine();
        $rc = $doctrine->getRepository(Vehicule::class);
        $result = $rc->find($id);

        //dump($results); exit;

        return $this->render('admin/vehicule/viewVehicule.html.twig', [
            'result' => $result
        ]);
    }

}
