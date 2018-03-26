<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Category;
use AppBundle\Entity\Service;
use AppBundle\Form\ServiceType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/admin")
 */
class ServiceController extends Controller
{
    /**
     * @Route("/service/{idCategory}", name="app_admin_service_list")
     * @Method({"GET", "POST"})
     */
    public function listAction(Request $request, $idCategory)
    {

        $doctrine = $this->getDoctrine();
        $rc = $doctrine->getRepository(Service::class);
        $results = $rc->findBy(['category' => $idCategory]);

        $rcCategory = $doctrine->getRepository(Category::class);
        $category = $rcCategory->find($idCategory);


        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $results,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/service/list.html.twig', [
            'results' => $pagination,
            'category' => $category,

        ]);
    }

    /**
     * @Route("/service/add/{idCategory}", name="app_admin_service_add")
     * @Route("/service/update/{idCategory}/{idService}", name="app_admin_service_update")
     *
     */
    public function addServiceAction(Request $request, $idCategory, $idService= null)
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $rcService = $doctrine->getRepository(Service::class);
        $typePage = 'ajout';


            $categoryEntity = $doctrine->getRepository(Category::class)->find($idCategory);


            $serviceEntity = $idService ? $rcService->find($idService) : new Service($categoryEntity);


        $form = $this->createForm(ServiceType::class, $serviceEntity);
        $form->handleRequest($request);

        if($idService){
            $typePage = 'modif';
        }


        if ($form->isSubmitted() && $form->isValid()) {
            $saisie = $form->getData();

            // vérification qu'il n'y a pas de doublon
            $pasDeDoublon = $rcService->serviceNotExist($saisie->getName());


            // en update
            if($idService){

                //pour permettre la mise à jour
                $pasDeDoublon = true;
            }
            if ($pasDeDoublon){


                //insertion du service
                $em->persist($serviceEntity);

                $em->flush();

                //message flash
                $message = $idService ? 'Le service a été mis à jour' : 'Le service a été inséré';
                $this->addFlash('info', $message);

                // redirection vers la page de la catégory
                return $this->redirectToRoute('app_admin_service_list', array('idCategory' => $idCategory));
            }
            else{
                //message flash
                $message = 'Le service existe déjà';
                $this->addFlash('warning', $message);

                // redirection vers le formulaire
                return $this->redirectToRoute('app_admin_service_add', array('idCategory' => $idCategory));
            }


        }

        return $this->render('admin/service/addService.html.twig', [
            'form' => $form->createView(),
            'typePage' => $typePage,
            'category' => $categoryEntity,
        ]);
    }



}
