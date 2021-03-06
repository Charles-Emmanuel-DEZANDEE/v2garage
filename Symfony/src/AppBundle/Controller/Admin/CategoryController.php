<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;
use AppBundle\Service\OrderCategoryService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/admin")
 */
class CategoryController extends Controller
{
    /**
     * @Route("/category", name="app_admin_category_list")
     * @Method({"GET", "POST"})
     */
    public function listAction(Request $request)
    {

        $doctrine = $this->getDoctrine();
        $rc = $doctrine->getRepository(Category::class);
        $results = $rc->findAllOrderByPosition();


        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $results,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/category/list.html.twig', [
            'results' => $pagination,

        ]);
    }

    /**
     * @Route("/category/up/{idCategory}", name="app_admin_category_up")
     * @Method({"GET", "POST"})
     */
    public function upAction(Request $request, $idCategory, OrderCategoryService $orderCategoryService)
    {


        $doctrine = $this->getDoctrine();
        $rc = $doctrine->getRepository(Category::class);
        $categoryEntity = $rc->find($idCategory);

        // on monte d'un cran
        $orderCategoryService->upCategory($categoryEntity);

        // on affiche
        $results = $rc->findAllOrderByPosition();


        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $results,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/category/list.html.twig', [
            'results' => $pagination,

        ]);
    }

    /**
     * @Route("/category/down/{idCategory}", name="app_admin_category_down")
     * @Method({"GET", "POST"})
     */
    public function downAction(Request $request, $idCategory, OrderCategoryService $orderCategoryService)
    {


        $doctrine = $this->getDoctrine();
        $rc = $doctrine->getRepository(Category::class);
        $categoryEntity = $rc->find($idCategory);

        // on monte d'un cran
        $orderCategoryService->downCategory($categoryEntity);

        // on affiche
        $results = $rc->findAllOrderByPosition();


        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $results,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/category/list.html.twig', [
            'results' => $pagination,

        ]);
    }


    /**
     * @Route("/category/add", name="app_admin_category_add")
     * @Route("/category/update/{idCategory}", name="app_admin_category_update")
     *
     */
    public function addCategoryAction(Request $request, $idCategory = null)
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $rcCategory = $doctrine->getRepository(Category::class);
        $typePage = 'ajout';


        $categoryEntity = $idCategory ? $rcCategory->find($idCategory) : new Category();


        $form = $this->createForm(CategoryType::class, $categoryEntity);
        $form->handleRequest($request);

        if ($idCategory) {
            $typePage = 'modif';
        }


        if ($form->isSubmitted() && $form->isValid()) {
            $saisie = $form->getData();


            // vérification qu'il n'y a pas de doublon
            $pasDeDoublon = $rcCategory->categoryNotExist($saisie->getName());


            // en update
            if ($idCategory) {

                //pour permettre la mise à jour
                $pasDeDoublon = true;
            } else {
                //on set la position en création
                $categoryEntity->setPosition($rcCategory->nextPositionAvailable());

            }
            if ($pasDeDoublon) {


                //insertion de la categorie
                $em->persist($categoryEntity);

                $em->flush();

                //message flash
                $message = $idCategory ? 'La categorie a été mis à jour' : 'La categorie a été insérée';
                $this->addFlash('info', $message);

                // redirection vers la page de la catégorie
                return $this->redirectToRoute('app_admin_category_list');
/*                return $this->redirectToRoute('app_admin_service_list', array('idCategory' => $idCategory));*/
            } else {
                //message flash
                $message = 'La categorie existe déjà';
                $this->addFlash('warning', $message);

                // redirection vers le formulaire
                return $this->redirectToRoute('app_admin_category_add');
            }


        }

        return $this->render('admin/category/addCategory.html.twig', [
            'form' => $form->createView(),
            'typePage' => $typePage,
            'category' => $categoryEntity,
        ]);
    }

    /**
     * @Route("/category/delete/{idCategory}", name="app_admin_category_delete")
     */
    public function deleteAction(Request $request, $idCategory)
    {
        // sélection de l'entité à supprimer
        $result = $this->getDoctrine()->getRepository(Category::class)->find($idCategory);

        // suppression
        $em = $this->getDoctrine()->getManager();
        $em->remove($result);
        $em->flush();

        //message flash
        $message = 'La categorie a été supprimée';
        $this->addFlash('warning', $message);

        // redirection
        return $this->redirectToRoute('app_admin_category_list');
    }


}
