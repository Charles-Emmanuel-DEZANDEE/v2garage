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





}
