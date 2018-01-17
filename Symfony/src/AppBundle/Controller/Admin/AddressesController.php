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
class AddressesController extends Controller
{

    /**
     * @Route("/addresses/add/{idCustomer}", name="app_admin_addresses_add")
     * @Route("/addresses/update/{idCustomer}/{idVehicule}", name="app_admin_addresses_update")
     *
     */
    public function addVehiculeAction(Request $request, $idCustomer, $idVehicule= null)
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $rcAddresses = $doctrine->getRepository(Addresses::class);
        $typePage = 'ajout';


            $CustomerEntity = $doctrine->getRepository(Customer::class)->find($idCustomer);


            $addressesEntity = $idAddresses ? $rcAddresses->find($idAddresses) : new Addresses($CustomerEntity);


        $form = $this->createForm(AddressesType::class, $addressesEntity);
        $form->handleRequest($request);

        if($idAddresses){
            $typePage = 'modif';
        }


        if ($form->isSubmitted() && $form->isValid()) {
            $saisie = $form->getData();

            // vérification qu'il n'y a pas de doublon
            $pasDeDoublon = $rcAddresses->addressesNotExist($saisie->getRegistration());


            // en update
            if($idAddresses){

                //pour permettre la mise à jour
                $pasDeDoublon = true;
            }
            if ($pasDeDoublon){

                //mise à jour de la date de derniére action du client
                $CustomerEntity->setLastActionDate(new \DateTime());
                $em->persist($CustomerEntity);

                //insertion du véhicule
                $em->persist($addressesEntity);

                $em->flush();

                //message flash
                $message = $idAddresses ? 'Le véhicule a été mis à jour' : 'Le véhicule a été inséré';
                $this->addFlash('info', $message);

                // redirection vers la page du client
                return $this->redirectToRoute('app_admin_customer_view', array('id' => $idCustomer));
            }
            else{
                //message flash
                $message = 'Le véhicule existe déjà';
                $this->addFlash('warning', $message);

                // redirection vers le formulaire
                return $this->redirectToRoute('app_admin_addresses_add');
            }


        }

        return $this->render('admin/addresses/addAddresses.html.twig', [
            'form' => $form->createView(),
            'typePage' => $typePage,
            'client' => $CustomerEntity,
        ]);
    }


}
