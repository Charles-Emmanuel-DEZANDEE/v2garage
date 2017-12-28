<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Brand;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin")
 */
class BrandController extends Controller
{
    /**
     * @Route("/brand", name="app_admin_brand_index")
     */
    public function indexAction(Request $request)
    {
        $results = [
            ['id' => 2, 'name' => 'Peugeot'],
            ['id' => 2, 'name' => 'Citroen']
        ];
        return $this->render('admin/brand/index.html.twig', [
            'results' => $results,

        ]);
    }

    /**
     * @Route("/brand/add", name="app_admin_brand_add")
     */
    public function addBrand(Request $request)
    {
        // Création de l'entité
        $brand = new Brand();
        $brand->setName('ford');
        // On peut ne pas définir ni la date ni la publication,
        // car ces attributs sont définis automatiquement dans le constructeur

        // On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();

        // Étape 1 : On « persiste » l'entité
        $em->persist($brand);

        // Étape 2 : On « flush » tout ce qui a été persisté avant
        $em->flush();
        // message flash
        $this->addFlash('notice', 'marque bien enregistrée.');


        // Si on n'est pas en POST, alors on affiche le formulaire
        return $this->render('admin/brand/addBrand.html.twig');
    }
}
