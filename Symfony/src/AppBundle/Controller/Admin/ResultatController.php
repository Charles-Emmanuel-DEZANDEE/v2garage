<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Datatables\CommandDatatable;
use AppBundle\Entity\Address_intervention;
use AppBundle\Entity\Category;
use AppBundle\Entity\Command;
use AppBundle\Entity\CommandsServices;
use AppBundle\Entity\Mois;
use AppBundle\Entity\PaymentType;
use AppBundle\Entity\ResultatAnnuel;
use AppBundle\Entity\ResultatMensuel;
use AppBundle\Entity\Service;
use AppBundle\Entity\Vehicule;
use AppBundle\Form\CommandSearchType;
use AppBundle\Form\CommandType;
use AppBundle\Form\NouvelleInterventionType;
use AppBundle\Form\ResultatSearchType;
use AppBundle\Service\CommandService;
use AppBundle\Service\ResultatService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/admin")
 */
class ResultatController extends Controller
{

    /**
     * @Route("/resultat", name="app_admin_resultat_list")
     * @Method({"GET", "POST"})
     */
    public function listAction(Request $request, ResultatService $resultatService)
    {
        $doctrine = $this->getDoctrine();
        $rcResusltatMensuel = $doctrine->getRepository(ResultatMensuel::class);
        $rcResusltatAnnuel = $doctrine->getRepository(ResultatAnnuel::class);
        $rcMois = $doctrine->getRepository(Mois::class);



        // formulaire de rechercher par année
        $form = $this->createForm(ResultatSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $saisie = $form->getData();
            $recherche = $saisie['date'];
            $annee = intval(date_format($recherche, "Y"));
            // on raffraichi les résultats
            $entiteAnnee = $resultatService->anneeCheckOrCreate($annee);
            $resultatService->updateResulat($entiteAnnee);

            for ($i = 1; $i <= 12; $i++) {
                $mois = $rcMois->findOneBy(['code' => $i]);
                $resultatService->updateResulat($entiteAnnee, $mois);
            }

            $resultatsMensuel = $rcResusltatMensuel->findBy(['annee' => $entiteAnnee]);
            $resultatAnnuel = $rcResusltatAnnuel->findBy(['annee' => $entiteAnnee]);
        }
        else {
            $aujourdhui = new \DateTime();
            $anneeEnCours = intval(date_format($aujourdhui, "Y"));
            $entiteAnneEnCours = $resultatService->anneeCheckOrCreate($anneeEnCours);


            // on rafraichi les données

            $resultatService->updateResulat($entiteAnneEnCours);

            $numeroMoisEnCours = intval(date_format($aujourdhui, "n"));
            for ($i = 1; $i <= $numeroMoisEnCours; $i++) {
                $mois = $rcMois->findOneBy(['code' => $i]);
                $resultatService->updateResulat($entiteAnneEnCours, $mois);
            }

            // on affiche par defaut les resultats de l'année en cours

            $resultatsMensuel = $rcResusltatMensuel->findBy(['annee' => $entiteAnneEnCours]);
            $resultatAnnuel = $rcResusltatAnnuel->findBy(['annee' => $entiteAnneEnCours]);

        }


        return $this->render('admin/resultat/list.html.twig', [
            'resultatsMensuel' => $resultatsMensuel,
            'resultatAnnuel' => $resultatAnnuel[0],
            'form' => $form->createView(),

        ]);
    }


}
