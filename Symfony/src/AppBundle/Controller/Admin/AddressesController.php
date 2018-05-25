<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Address_intervention;
use AppBundle\Entity\Customer;
use AppBundle\Entity\Vehicule;
use AppBundle\Form\Address_interventionType;
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
     * @Route("/addresses/update/{idCustomer}/{idAddresses}", name="app_admin_addresses_update")
     *
     */
    public function addVehiculeAction(Request $request, $idCustomer, $idAddresses= null)
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $rcAddresses = $doctrine->getRepository(Address_intervention::class);
        $typePage = 'ajout';


            $customerEntity = $doctrine->getRepository(Customer::class)->find($idCustomer);


            $addressesEntity = $idAddresses ? $rcAddresses->find($idAddresses) : new Address_intervention($customerEntity);


        $form = $this->createForm(Address_interventionType::class, $addressesEntity);
        $form->handleRequest($request);

        if($idAddresses){
            $typePage = 'modif';
        }


        if ($form->isSubmitted() && $form->isValid()) {
            $saisie = $form->getData();

            // vérification qu'il n'y a pas de doublon
            $pasDeDoublon = $rcAddresses->addressesNotExist($saisie->getName(), $idCustomer);


            // en update
            if($idAddresses){

                //pour permettre la mise à jour
                $pasDeDoublon = true;
            }
            if ($pasDeDoublon){

                //mise à jour de la date de derniére action du client
                $customerEntity->setLastActionDate(new \DateTime());
                $em->persist($customerEntity);

                //insertion de l'adresse
                $em->persist($addressesEntity);

                $em->flush();

                //message flash
                $message = $idAddresses ? 'L\'adresse a été mise à jour' : 'L\'adresse a été insérée';
                $this->addFlash('info', $message);

                // redirection vers la page du client
                return $this->redirectToRoute('app_admin_customer_view', array('id' => $idCustomer));
            }
            else{
                //message flash
                $message = 'L\'adresse existe déjà';
                $this->addFlash('warning', $message);

                // redirection vers le formulaire
                return $this->redirectToRoute('app_admin_addresses_add', array('idCustomer' => $idCustomer));
            }


        }

        return $this->render('admin/adresses/addAddresses.html.twig', [
            'form' => $form->createView(),
            'typePage' => $typePage,
            'client' => $customerEntity,
        ]);
    }


}
