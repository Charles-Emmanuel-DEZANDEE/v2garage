<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Datatables\CommandDatatable;
use AppBundle\Entity\Address_intervention;
use AppBundle\Entity\Category;
use AppBundle\Entity\Command;
use AppBundle\Entity\CommandsServices;
use AppBundle\Entity\Mois;
use AppBundle\Entity\PaymentType;
use AppBundle\Entity\Service;
use AppBundle\Entity\Vehicule;
use AppBundle\Form\CommandSearchType;
use AppBundle\Form\CommandType;
use AppBundle\Form\NouvelleInterventionType;
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
class SuiviController extends Controller
{

    /**
     * @Route("/suivi/datatable", name="app_admin_suivi_datatable")
     * @Method({"GET"})
     * @param Request $request
     * @return Response
     * @author : Charles-emmanuel DEZANDEE <cdezandee@gmail.com>
     * @throws \Exception
     */
    public function datatableAction(Request $request)
    {

        $isAjax = $request->isXmlHttpRequest();


        $datatable = $this->get('sg_datatables.factory')->create(CommandDatatable::class);
        $datatable->buildDatatable();

        if ($isAjax) {
            $responseService = $this->get('sg_datatables.response');
            $responseService->setDatatable($datatable);
            $responseService->getDatatableQueryBuilder();

            return $responseService->getResponse();
        }

        return $this->render('admin/suivi/syntheseCommand.html.twig', array(
            'datatable' => $datatable,
        ));
    }

    /**
     * @Route("/suivi/devis", name="app_admin_suivi_devis")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return Response
     * auteur : Charles-emmanuel DEZANDEE  <cdezandee@gmail.com>
     */
    public function devisAction(Request $request)
    {

        $doctrine = $this->getDoctrine();
        $rcCommand = $doctrine->getRepository(Command::class);


        // formulaire de rechercher par date
        $form = $this->createForm(CommandSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $saisie = $form->getData();
            $recherche = $saisie['date'];
            $annee = intval(date_format($recherche, "Y"));
            $mois = intval(date_format($recherche, "n"));

            $devis = $rcCommand->findDevisByYearAndMonth($annee, $mois);
        }
        else{
            // on affiche le mois et l'année en cours
            $aujourdhui = new \DateTime();
            $annee = intval(date_format($aujourdhui, "Y"));
            $mois = intval(date_format($aujourdhui, "n"));

            $devis = $rcCommand->findDevisByYearAndMonth($annee, $mois);

        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $devis,
            $request->query->getInt('page', 1),
            20
        );

        $entiteMois = $doctrine->getRepository(Mois::class)->findOneBy(['code' => $mois]);
        $moisString = $entiteMois->getName();



        return $this->render('admin/suivi/list.devis.html.twig', [
            'devis' => $pagination,
            'form' => $form->createView(),
            'mois' => $moisString,
            'annee' => $annee,

        ]);
    }

    /**
     * @Route("/suivi/factures", name="app_admin_suivi_factures")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return Response
     * auteur : Charles-emmanuel DEZANDEE  <cdezandee@gmail.com>
     */
    public function facturesAction(Request $request)
    {

        $doctrine = $this->getDoctrine();
        $rcCommand = $doctrine->getRepository(Command::class);


        // formulaire de rechercher par date
        $form = $this->createForm(CommandSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $saisie = $form->getData();
            $recherche = $saisie['date'];
            $annee = intval(date_format($recherche, "Y"));
            $mois = intval(date_format($recherche, "n"));

            $factures = $rcCommand->findFacturesByYearAndMonth($annee, $mois);
        }
        else{
            // on affiche le mois et l'année en cours
            $aujourdhui = new \DateTime();
            $annee = intval(date_format($aujourdhui, "Y"));
            $mois = intval(date_format($aujourdhui, "n"));

            $factures = $rcCommand->findFacturesByYearAndMonth($annee, $mois);

        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $factures,
            $request->query->getInt('page', 1),
            20
        );

        $entiteMois = $doctrine->getRepository(Mois::class)->findOneBy(['code' => $mois]);
        $moisString = $entiteMois->getName();



        return $this->render('admin/suivi/list.factures.html.twig', [
            'factures' => $pagination,
            'form' => $form->createView(),
            'mois' => $moisString,
            'annee' => $annee,

        ]);
    }



}
