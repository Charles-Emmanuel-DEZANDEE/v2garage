<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Datatables\CommandDatatable;
use AppBundle\Entity\Address_intervention;
use AppBundle\Entity\Category;
use AppBundle\Entity\Command;
use AppBundle\Entity\CommandsServices;
use AppBundle\Entity\PaymentType;
use AppBundle\Entity\Service;
use AppBundle\Entity\Vehicule;
use AppBundle\Form\CommandSearchType;
use AppBundle\Form\CommandType;
use AppBundle\Service\CommandService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/admin")
 */
class CommandController extends Controller
{


    /**
     * @Route("/command/datatable", name="app_admin_command_datatable")
     * @Method({"GET"})
     * @param Request $request
     * @return Response
     * @author : Charles-emmanuel DEZANDEE <cdezandee@sigma.fr>
     * @throws \Exception
     */
    public function datatableAction(Request $request)
    {

        $isAjax = $request->isXmlHttpRequest();

        // Get your Datatable ...
        //$datatable = $this->get('app.datatable.command');
        //$datatable->buildDatatable();

        // or use the DatatableFactory

        $datatable = $this->get('sg_datatables.factory')->create(CommandDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $responseService->getDatatableQueryBuilder();

            return $responseService->getResponse();
        }

        return $this->render('admin/command/syntheseCommand.html.twig', array(
            'datatable' => $datatable,
        ));
    }


    /**
     * edition de l'intervention.
     * @param Request $request
     * @param Command $command
     *
     * @Route("/command/datatable/edit/{id}", name = "app_admin_command_edit", options = {"expose" = true})
     * @Method({"GET", "POST"})
     *
     *
     * @return Response
     */
    public function editAction(Request $request, Command $command)
    {
        $doctrine = $this->getDoctrine();
        $formNote = $this->createForm(CommandType::class, $command, ['attr' => ['idCustomer' => $command->getVehicule()->getCustomer()->getId()]]);
        $formNote->handleRequest($request);

        if ($formNote->isSubmitted() && $formNote->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($command);

            $em->flush();
            //message flash
            $message = 'L\'intervention a été mise à jour';
            $this->addFlash('info', $message);

            return $this->redirectToRoute('app_admin_command_datatable');
        }
        return $this->render('admin/command/editIntervention.html.twig', array(
            'form' => $formNote->createView(),
            'command' => $command,
        ));
    }


    /**
     * @Route("/command/devis/{id}", name="app_admin_command_devis")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Vehicule $vehicule
     * @param CommandService $commandService
     * @return \Symfony\Component\HttpFoundation\Response
     * @author : Charles-emmanuel DEZANDEE <cdezandee@sigma.fr>
     */
    public function devisAction(Request $request, Vehicule $vehicule, CommandService $commandService)
    {

        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();

        $rcCategory = $doctrine->getRepository(Category::class);
        $results = $rcCategory->findAllOrderByPositionMagic();

        $command = new Command();
        $command->setVehicule($vehicule);
        //on attribut un numero de devis
        $command->setRef($commandService->getNumeroDevis());
        $em->persist($command);
        $em->flush();


        //on regarde s'il y a plusieurs adresse d'intervention
        $listAdresseIntervention = $vehicule->getCustomer()->getAddresses();
        // si oui on envoit vers la selection de l'addresse d'intervention
        if (count($listAdresseIntervention) > 1) {
            return $this->render('admin/command/choixAdressIntervention.html.twig', [
                'listAdresse' => $listAdresseIntervention,
                'vehicule' => $vehicule,
                'commande' => $command,

            ]);
        } //si non on enregistre directement dans la commande
        else if (count($listAdresseIntervention) == 1) {
            $address = $listAdresseIntervention[0];
            $command->setAdressIntervention($address);
            $em->persist($command);
            $em->flush();
        }


        return $this->render('admin/command/devis.html.twig', [
            'results' => $results,
            'vehicule' => $vehicule,
            'commande' => $command,

        ]);
    }

    /** accés à la modification du devis
     * @Route("/command/update/{id}", name="app_admin_command_update")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Command $command
     * @return \Symfony\Component\HttpFoundation\Response
     * @author : Charles-emmanuel DEZANDEE <cdezandee@sigma.fr>
     */
    public function devisUpdateAction(Request $request, Command $command)
    {
        //control de la modification du devis: ok si pas validé
        if ($command->getCommandeValidate()) {
            $message = 'Le devis a été validé par le client et n\'est plus modifiable';

            $this->addFlash('danger', $message);

            return $this->redirectToRoute('app_admin_command_view', ['id' => $command->getId()]);
        }

        $doctrine = $this->getDoctrine();

        $rc = $doctrine->getRepository(Category::class);
        $results = $rc->findAllOrderByPositionMagic();

        return $this->render('admin/command/devis.html.twig', [
            'results' => $results,
            'vehicule' => $command->getVehicule(),
            'commande' => $command,

        ]);
    }

    /** selection de l'adresse d'intervention
     * @Route("/command/selectaddress/{id}", name="app_admin_command_selectAddress")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Command $command
     * @return \Symfony\Component\HttpFoundation\Response
     * @author : Charles-emmanuel DEZANDEE <cdezandee@sigma.fr>
     */
    public function selectAdressAction(Request $request, Command $command)
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();

        $idAddress = $request->get('address');
        $Address = $doctrine->getRepository(Address_intervention::class)->find($idAddress);
        $command->setAdressIntervention($Address);
        $em->persist($command);
        $em->flush();

        return $this->redirectToRoute('app_admin_command_update', ['id' => $command->getId()]);


    }


    /** suppression d'une intervention non accepté par le client
     * @Route("/command/remove/{id}", name="app_admin_command_remove")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Command $command
     * @return \Symfony\Component\HttpFoundation\Response
     * @author : Charles-emmanuel DEZANDEE <cdezandee@sigma.fr>
     */
    public function removeAction(Request $request, Command $command)
    {
        $idcustomer = $command->getVehicule()->getCustomer()->getId();
        if (!$command->getCommandeValidate()) {

            $doctrine = $this->getDoctrine();
            $em = $doctrine->getManager();

            // on supprime les commandservices
            foreach ($command->getCommandsServices() as $comService) {
                $em->remove($comService);
            }
            $em->remove($command);
            $em->flush();

            $message = 'Le devis a été supprimé';
        } else {
            $message = "Il n'est pas possible supprimer un devis accepté par le client !";
        }


        $this->addFlash('danger', $message);


        return $this->redirectToRoute('app_admin_customer_view', [
            'id' => $idcustomer,
        ]);
    }


    /** devis accepté par le client
     * @Route("/command/validedevis/{id}", name="app_admin_command_valide_devis")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Command $command
     * @return \Symfony\Component\HttpFoundation\Response
     * @author : Charles-emmanuel DEZANDEE <cdezandee@sigma.fr>
     */
    public function devisAccepteAction(Request $request, Command $command)
    {

        $doctrine = $this->getDoctrine();

        $commandeValidate = $request->get('commandeValidate')
            ? new \DateTime($request->get('commandeValidate')) : new \DateTime();

        //on met à jour la date de validation du devis
        $command->setCommandeValidate($commandeValidate);

        $em = $doctrine->getManager();
        $em->persist($command);
        $em->flush();


        $message = 'Le devis a été validé par le client et n\'est plus modifiable';

        $this->addFlash('info', $message);

        return $this->redirectToRoute('app_admin_command_view', ['id' => $command->getId()]);

    }

    /** travaux realisés suivant le devis accepté: generation de la facture
     * @Route("/command/travauxfait/{id}", name="app_admin_command_travaux_fait")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Command $command
     * @return \Symfony\Component\HttpFoundation\Response
     * @author : Charles-emmanuel DEZANDEE <cdezandee@sigma.fr>
     */
    public function travauxFaitsAction(Request $request, Command $command, CommandService $commandService)
    {

        $doctrine = $this->getDoctrine();

        //on met à jour la date de validation du devis
        $command->setDateBill(new \DateTime());
        //on attribut un numero de facture
        $command->setBillRef($commandService->getNumeroFacture());


        $em = $doctrine->getManager();
        $em->persist($command);
        $em->flush();


        $message = 'La facture a été créée';

        $this->addFlash('info', $message);

        return $this->redirectToRoute('app_admin_command_view', ['id' => $command->getId()]);

    }

    /** duplication de la commande en devis
     * @Route("/command/dupliquer/{id}", name="app_admin_command_dupliquer")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Command $command
     * @return \Symfony\Component\HttpFoundation\Response
     * @author : Charles-emmanuel DEZANDEE <cdezandee@sigma.fr>
     */
    public function dupliquerCommandAction(Request $request, Command $command, CommandService $commandService)
    {

        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();

        $nouvelleCommand = clone $command;
        $nouvelleCommand->setDateCreate(new \DateTime());
        $nouvelleCommand->setBillRef(null);
        $nouvelleCommand->setDateBill(null);
        $nouvelleCommand->setCommandeValidate(null);
        $nouvelleCommand->setPaymentType(null);
        $nouvelleCommand->setRef($commandService->getNumeroDevis());
        $nouvelleCommand->setNote(null);
        $em->persist($nouvelleCommand);
        $em->flush();

        $ligneCommand = $command->getCommandsServices();


        foreach ($ligneCommand as $commandService) {
            $nouvelleCommandService = clone $commandService;
            $nouvelleCommandService->nullId();
            $nouvelleCommandService->setCommand($nouvelleCommand);
            $em->persist($nouvelleCommandService);
        }

        $em->flush();

        $message = "L'intervention a été dupliquée";

        $this->addFlash('info', $message);

        return $this->redirectToRoute('app_admin_command_view', ['id' => $nouvelleCommand->getId()]);

    }

    /** la facture a été payée
     * @Route("/command/facturepayee/{id}", name="app_admin_command_facture_payee")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Command $command
     * @return \Symfony\Component\HttpFoundation\Response
     * @author : Charles-emmanuel DEZANDEE <cdezandee@sigma.fr>
     */
    public function facturePayeeAction(Request $request, Command $command)
    {

        $doctrine = $this->getDoctrine();

        $dateBillAcquited = $request->get('dateBillAcquited')
            ? new \DateTime($request->get('dateBillAcquited')) : new \DateTime();

        //on met à jour la date de validation du devis
        $command->setDateBillAcquited($dateBillAcquited);


        $idPayment = $request->get('paymentType');

        $rc = $doctrine->getRepository(PaymentType::class);
        $paymentType = $rc->find($idPayment);
        $command->setPaymentType($paymentType);


        $em = $doctrine->getManager();
        $em->persist($command);
        $em->flush();


        $message = 'La facture a été créée';

        $this->addFlash('info', $message);

        return $this->redirectToRoute('app_admin_command_view', ['id' => $command->getId()]);

    }

    /** enregistrement de la facture en pdf
     * @Route("/command/facture/pdf/{id}/{devis}", name="app_admin_command_facture_pdf")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Command $command
     * @return \Symfony\Component\HttpFoundation\Response
     * @author : Charles-emmanuel DEZANDEE <cdezandee@sigma.fr>
     */
    public function pdfAction(Request $request, Command $command, int $devis)
    {
        $doctrine = $this->getDoctrine();

        $rc = $doctrine->getRepository(Command::class);
        $result = $rc->findOneOrderByPositionMagic($command->getId());
        if ($devis) {
            $html = $this->renderView('/template/devis.html.twig', [
                'command' => $result[0],
            ]);
            $filename = sprintf('Devis-%s.pdf', $command->getRef());

        } else {

            $html = $this->renderView('/template/facture.html.twig', [
                'command' => $result[0],
            ]);
            $filename = sprintf('Facture-%s.pdf', $command->getBillRef());
        }

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
            ]
        );
    }

    /** vue du devis ou facture
     * @Route("/command/vue/{id}/{devis}", name="app_admin_command_vue")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Command $command
     * @return \Symfony\Component\HttpFoundation\Response
     * @author : Charles-emmanuel DEZANDEE <cdezandee@sigma.fr>
     */
    public function vueCommandPdf(Request $request, Command $command, int $devis)
    {
        $doctrine = $this->getDoctrine();

        $rc = $doctrine->getRepository(Command::class);
        $result = $rc->findOneOrderByPositionMagic($command->getId());
        if ($devis) {

            return $this->render('/template/devis.html.twig', [
                'command' => $result[0],
            ]);
        } else {
            return $this->render('/template/facture.html.twig', [
                'command' => $result[0],
            ]);

        }

    }

    /** envoi par mail de la facture ou devis selon parametre
     * @Route("/command/facture/mail/{id}/{devis}", name="app_admin_command_facture_mail")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Command $command
     * @return \Symfony\Component\HttpFoundation\Response
     * @author : Charles-emmanuel DEZANDEE <cdezandee@sigma.fr>
     */
    public function mailAction(Request $request, Command $command, \Swift_Mailer $mailer, \Twig_Environment $twig, int $devis)
    {

        //on genére le pdf
        //$command
        $mailFrom = $this->getParameter('mail_from');
        $mailtarget = $command->getVehicule()->getCustomer()->getEmail();

        if ($devis) {
            $subject = 'devis - ' . $command->getRef();
            $html = $this->renderView("template/devis.html.twig", ['command' => $command]);
            $filename = 'devis-' . $command->getBillRef() . '.pdf';

        } else {
            $subject = 'facture - ' . $command->getBillRef();

            $html = $this->renderView("template/facture.html.twig", ['command' => $command]);
            $filename = 'facture-' . $command->getBillRef() . '.pdf';
        }


        $pdf = $this->get("knp_snappy.pdf")->getOutputFromHtml($html);
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($mailFrom)
            ->setTo($mailtarget)
            ->setBcc($mailFrom);// on met en copie cachée
        $body = $twig->render('mailing/send.command.pdf.html.twig', ['command' => $command,
            'devis' => $devis]);
        $message->setBody($body, 'text/html');

//join PDF
        $attachement = \Swift_Attachment::newInstance($pdf, $filename, 'application/pdf');
        $message->attach($attachement);
        //envoi du mail
        $mailer->send($message);


        if ($command->getBillRef()) {

            $message = 'La facture a été envoyée par mail';
        } else {

            $message = 'Le devis a été envoyée par mail';
        }
        $this->addFlash('info', $message);

        return $this->redirectToRoute('app_admin_command_view', ['id' => $command->getId()]);

    }


    /**
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Command $command
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/command/view/{id}", name="app_admin_command_view")
     */
    public function viewCommand(Request $request, Command $command)
    {


        $doctrine = $this->getDoctrine();
        $allPaymentType = $doctrine->getRepository(PaymentType::class)->findAll();



        //dump($command);


        return $this->render('admin/command/viewCommand.html.twig', [
            'command' => $command,
            'selectTypePaiement' => $allPaymentType,

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
            if ($idCommand != null) {
                $command = $doctrine->getRepository(Command::class)->find($idCommand);
                //vider les lignes commandServices
                $commandeServices = $doctrine->getRepository(CommandsServices::class)->findBy(['command' => $command]);
                foreach ($commandeServices as $ligne) {

                    $em->remove($ligne);
                }
                $em->flush();
            } else {
                $command = new Command();
                $command->setVehicule($vehicule);
                //on attribut un numero de devis
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
                $serviceCommand = new CommandsServices($service, $command);
                $serviceCommand->setReference($ligne['reference']);
                $serviceCommand->setValue($ligne['valeurHT']);
                $serviceCommand->setQuantity($ligne['quantity']);
                $serviceCommand->setDiscountRate($ligne['remiserate']);
                $serviceCommand->setTaxRate($ligne['taxerate']);
                $em->persist($serviceCommand);
            }

            $em->flush();

            if ($idCommand != null) {
                $message = 'Le devis a été modifié';
            } else {
                $message = 'Le devis a été enregistré';
            }
            $this->addFlash('info', $message);

            $response = new JsonResponse(['idcommand' => $command->getId()]);
            return $response;
        }
    }


}
