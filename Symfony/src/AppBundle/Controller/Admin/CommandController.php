<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Category;
use AppBundle\Entity\Command;
use AppBundle\Entity\CommandsServices;
use AppBundle\Entity\Customer;
use AppBundle\Entity\Service;
use AppBundle\Entity\Vehicule;
use AppBundle\Form\CommandSearchType;
use AppBundle\Form\CommandType;
use AppBundle\Service\DuplicateAddressesService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    public function devisAction(Request $request, Vehicule $vehicule)
    {

        $doctrine = $this->getDoctrine();

//        $vehicule = $doctrine->getRepository(Vehicule::class)->find($idVehicule);

        $rc = $doctrine->getRepository(Category::class);
        $results = $rc->findAllOrderByPositionMagic();

        return $this->render('admin/command/devis.html.twig', [
            'results' => $results,
            'vehicule' => $vehicule,

        ]);
    }

    /**
     * @Route("/command/add", name="app_admin_command_add", defaults={"id" : null })
     * @Route("/command/update/{id}", name="app_admin_command_update")
     *
     */
    public function addCommandAction(Request $request, $id = null, DuplicateAddressesService $service)
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $rcCommand = $doctrine->getRepository(Command::class);
        $typePage = 'ajout';


        $commandEntity = $id ? $rcCommand->find($id) : new Command();

        $form = $this->createForm(CommandType::class, $commandEntity);
        $form->handleRequest($request);
        if($id) {
            //personnalisation du formulaire
            $typePage = 'modif';
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $saisie = $form->getData();
            $pasDeDoublon = $rcCommand->commandNotExist($saisie->getEmail(),$saisie->getLastName(),$saisie->getAddressZipcode());


            // en update
            if($id){

                //pour permettre la mise à jour
                $pasDeDoublon = true;
            }
            if ($pasDeDoublon){


                //insertion
                $em->persist($commandEntity);
                // création de l'adresse d'intervention par défaut
                $service->duplicateMainAddresse($commandEntity);

                $em->flush();

                //message flash
                $message = $id ? 'Le commande a été mis à jour' : 'Le commande a été inséré';
                $this->addFlash('info', $message);

                if($id) {

                    // redirection vers la page du commande
                    return $this->redirectToRoute('app_admin_command_view', array(
                            'id' => $commandEntity->getId()
                        )
                    );
                }

                // redirection vers la saisie du véhicule
                return $this->redirectToRoute('app_admin_vehicule_add', array(
                    'idCommand' => $commandEntity->getId()
                    )
                );
            }
            else{
                //message flash
                $message = 'La commande existe déjà';
                $this->addFlash('warning', $message);

                // redirection vers le formulaire
                return $this->redirectToRoute('app_admin_command_add');
            }


        }

        return $this->render('admin/command/addCommand.html.twig', [
            'form' => $form->createView(),
            'typePage' => $typePage
        ]);
    }
    /**
     * @Route("/command/view/{id}", name="app_admin_command_view")
     */
    public function viewCommand(Request $request, $id = null)
    {
        if ($id == null){
            $message = 'Veuillez selectionner une commande';
            $this->addFlash('danger', $message);
            return $this->redirectToRoute('app_admin_customer_list');
        }

        $doctrine = $this->getDoctrine();
        $rc = $doctrine->getRepository(Command::class);
        $result = $rc->find($id);

//        dump($result);

        return $this->render('admin/command/viewCommand.html.twig', [
            'result' => $result
        ]);
    }

    /**
     * @Route("/commandeajax}", name="app_admin_command_ajax")
     * @Method({"POST"})
     * @return JsonResponse
     * @param Request $request
     * @author : Charles-emmanuel DEZANDEE <cdezandee@sigma.fr>
     */
    public function eltServiceAjax(Request $request)
    {
        if ($request->isXMLHttpRequest()) {
            $devis = $request->request->get('devis');
            $doctrine = $this->getDoctrine();
            $em = $doctrine->getManager();
            $vehicule = $doctrine->getRepository(Vehicule::class)->find($devis['idVehicule']);
            $command = new Command();
            $command->setVehicule($vehicule);
            //todo service generation n° de devis
            $command->setRef('D5646475');
            $em->persist($command);
            //ajout des lignes
            foreach ($devis['tabElt'] as $ligne) {
                $service = $doctrine->getRepository(Service::class)->find($ligne['idservice']);
                $serviceCommand = new CommandsServices($service,$command);
                $serviceCommand->setReference($ligne['reference']);
                $serviceCommand->setValue($ligne['valeurHT']);
                $serviceCommand->setQuantity($ligne['quantity']);
                $serviceCommand->setDiscountRate($ligne['remise']);
                $serviceCommand->setTaxRate($ligne['taxerate']);
                $em->persist($serviceCommand);
            }

            $em->flush();


            $message = 'Le devis a été enregistré';
            $this->addFlash('info', $message);

            $response = new JsonResponse(['idcommand' => $command->getId()]);
            return $response;
        }
    }


}
