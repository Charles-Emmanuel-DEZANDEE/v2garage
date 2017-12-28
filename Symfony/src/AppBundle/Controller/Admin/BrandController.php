<?php

namespace AppBundle\Controller\Admin;

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
    public function addBrandAction(Request $request)
    {
        $results = [
            ['id' => 2, 'name' => 'Peugeot'],
            ['id' => 2, 'name' => 'Citroen']
        ];
        return $this->render('admin/brand/index.html.twig', [
            'results' => $results,

        ]);
    }
}
