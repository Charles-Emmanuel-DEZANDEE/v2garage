<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Category;
use AppBundle\Entity\Customer;
use AppBundle\Entity\Vehicule;
use AppBundle\Form\CustomerSearchType;
use AppBundle\Form\CustomerType;
use AppBundle\Service\DuplicateAddressesService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


/**
 * @Route("/admin")
 */
class CommandController extends Controller
{


    /**
     * @Route("/command/{id}", name="app_admin_command_list")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Vehicule $vehicule
     * @return \Symfony\Component\HttpFoundation\Response
     * @author : Charles-emmanuel DEZANDEE <cdezandee@sigma.fr>
     */
    public function listAction(Request $request, Vehicule $vehicule)
    {

        $doctrine = $this->getDoctrine();

//        $vehicule = $doctrine->getRepository(Vehicule::class)->find($idVehicule);

        $rc = $doctrine->getRepository(Category::class);
        $results = $rc->findAllOrderByPositionMagic();



        return $this->render('admin/command/list.html.twig', [
            'results' => $results,
            'vehicule' => $vehicule,

        ]);
    }

    /**
     * @Route("/customer/add", name="app_admin_customer_add", defaults={"id" : null })
     * @Route("/customer/update/{id}", name="app_admin_customer_update")
     *
     */
    public function addCustomerAction(Request $request, $id = null, DuplicateAddressesService $service)
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $rcCustomer = $doctrine->getRepository(Customer::class);
        $typePage = 'ajout';


        $customerEntity = $id ? $rcCustomer->find($id) : new Customer();

        $form = $this->createForm(CustomerType::class, $customerEntity);
        $form->handleRequest($request);
        if($id) {
            //personnalisation du formulaire
            $typePage = 'modif';
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $saisie = $form->getData();
            $pasDeDoublon = $rcCustomer->customerNotExist($saisie->getEmail(),$saisie->getLastName(),$saisie->getAddressZipcode());


            // en update
            if($id){

                //pour permettre la mise à jour
                $pasDeDoublon = true;
            }
            if ($pasDeDoublon){


                //insertion
                $em->persist($customerEntity);
                // création de l'adresse d'intervention par défaut
                $service->duplicateMainAddresse($customerEntity);

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
                return $this->redirectToRoute('app_admin_vehicule_add', array(
                    'idCustomer' => $customerEntity->getId()
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
