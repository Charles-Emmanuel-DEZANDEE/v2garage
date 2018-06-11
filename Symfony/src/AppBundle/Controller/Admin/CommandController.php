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
use AppBundle\Service\CommandService;
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

        $rc = $doctrine->getRepository(Category::class);
        $results = $rc->findAllOrderByPositionMagic();

        return $this->render('admin/command/devis.html.twig', [
            'results' => $results,
            'vehicule' => $vehicule,

        ]);
    }

    /**
     * @Route("/command/update/{id}", name="app_admin_command_update")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Command $command
     * @return \Symfony\Component\HttpFoundation\Response
     * @author : Charles-emmanuel DEZANDEE <cdezandee@sigma.fr>
     */
    public function devisUpdateAction(Request $request, Command $command)
    {

        $doctrine = $this->getDoctrine();

        $rc = $doctrine->getRepository(Category::class);
        $results = $rc->findAllOrderByPositionMagic();

        return $this->render('admin/command/devis.html.twig', [
            'results' => $results,
            'vehicule' => $command->getVehicule(),
            'commande' => $command,

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
    public function eltServiceAjax(Request $request, CommandService $commandService)
    {
        if ($request->isXMLHttpRequest()) {
            $devis = $request->request->get('devis');
            $doctrine = $this->getDoctrine();
            $em = $doctrine->getManager();


            $vehicule = $doctrine->getRepository(Vehicule::class)->find($devis['idVehicule']);
            $idCommand = $devis['idCommande'];
            if ($idCommand != null){
                $command= $doctrine->getRepository(Command::class)->find($idCommand);
                //vider les lignes commandServices
                $commandeServices = $doctrine->getRepository(CommandsServices::class)->findBy(['command' => $command]);
                foreach ($commandeServices as $ligne){

                $em->remove($ligne);
                }
                $em->flush();
            }
            else{
            $command = new Command();
            $command->setVehicule($vehicule);
            $command->setRef($commandService->getNumeroDevis());
            }
            $command->setTotalHt($devis['totalHT']);
            $command->setTotalDiscount($devis['totalRemise']);
            $command->setTotalTva($devis['totalTva']);
            $command->setTotalTtc($devis['totalTtc']);

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

            if ($idCommand != null){
            $message = 'Le devis a été modifié';
            }
            else {
            $message = 'Le devis a été enregistré';
            }
            $this->addFlash('info', $message);

            $response = new JsonResponse(['idcommand' => $command->getId()]);
            return $response;
        }
    }


}
