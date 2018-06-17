<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Category;
use AppBundle\Entity\Command;
use AppBundle\Entity\CommandsServices;
use AppBundle\Entity\Customer;
use AppBundle\Entity\PaymentType;
use AppBundle\Entity\Service;
use AppBundle\Entity\Vehicule;
use AppBundle\Form\CommandSearchType;
use AppBundle\Form\CommandType;
use AppBundle\Form\NoteCommandType;
use AppBundle\Service\CommandService;
use AppBundle\Service\DuplicateAddressesService;
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
     * @Route("/command/devis/{id}", name="app_admin_command_devis")
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

        //on met à jour la date de validation du devis
        $command->setCommandeValidate(new \DateTime());

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

        //on met à jour la date de validation du devis
        $command->setDateBillAcquited(new \DateTime());


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
     * @Route("/command/facture/pdf/{id}", name="app_admin_command_facture_pdf")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Command $command
     * @return \Symfony\Component\HttpFoundation\Response
     * @author : Charles-emmanuel DEZANDEE <cdezandee@sigma.fr>
     */
    public function FacturePdfAction(Request $request, Command $command)
    {
        $doctrine = $this->getDoctrine();

        $rc = $doctrine->getRepository(Command::class);
        $result = $rc->findOneOrderByPositionMagic($command->getId());
        $html = $this->renderView('/template/facture.html.twig', [
            'command' => $result[0],

        ]);

        $filename = sprintf('Facture-%s.pdf', $command->getBillRef());

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
            ]
        );
    }

    /** vue du devis ou facture
     * @Route("/command/vue/{id}", name="app_admin_command_vue")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Command $command
     * @return \Symfony\Component\HttpFoundation\Response
     * @author : Charles-emmanuel DEZANDEE <cdezandee@sigma.fr>
     */
    public function vue(Request $request, Command $command)
    {
        $doctrine = $this->getDoctrine();

        $rc = $doctrine->getRepository(Command::class);
        $result = $rc->findOneOrderByPositionMagic($command->getId());

        return $this->render('/template/facture.html.twig', [
            'command' => $result[0],

        ]);
    }

    /** envoi par mail de la facture
     * @Route("/command/facture/mail/{id}", name="app_admin_command_facture_mail")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Command $command
     * @return \Symfony\Component\HttpFoundation\Response
     * @author : Charles-emmanuel DEZANDEE <cdezandee@sigma.fr>
     */
    public function factureMailAction(Request $request, Command $command, \Swift_Mailer $mailer, \Twig_Environment $twig)
    {

        //on genére le pdf
        //$command
        $mailFrom = $this->getParameter('mail_from');
        $subject = 'facture - ' . $command->getBillRef();
        $mailtarget = $command->getVehicule()->getCustomer()->getEmail();

        $html = $this->renderView("template/facture.html.twig", ['command' => $command]);
        $filename = 'facture-' . $command->getBillRef().'.pdf';
        $pdf = $this->get("knp_snappy.pdf")->getOutputFromHtml($html);
        $message = \Swift_Message::newInstance()
            ->setSubject($subject )
            ->setFrom($mailFrom)
            ->setTo($mailtarget)
            ->setBcc($mailFrom);// on met en copie cachée
        $body = $twig->render('mailing/send.command.pdf.html.twig', ['command' => $command]);
        $message->setBody($body, 'text/html');

//join PDF
        $attachement = \Swift_Attachment::newInstance($pdf, $filename, 'application/pdf' );
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
    public function viewCommand(Request $request,Command $command)
    {


        $doctrine = $this->getDoctrine();
        $allPaymentType = $doctrine->getRepository(PaymentType::class)->findAll();

        $form = $this->createForm(CommandType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em = $doctrine->getManager();
            $em->persist($command);

            $em->flush();
            //message flash
            $message = 'La note a été mise à jour';
            $this->addFlash('info', $message);
        }

        dump($command);


        return $this->render('admin/command/viewCommand.html.twig', [
            'result' => $command,
            'select' => $allPaymentType,
            'form' => $form->createView(),
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
                $serviceCommand->setDiscountRate($ligne['remise']);
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
