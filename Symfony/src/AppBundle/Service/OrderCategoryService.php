<?php

namespace AppBundle\Service;

use AppBundle\Entity\Category;
use Doctrine\ORM\EntityManager;
use  Doctrine\ORM\EntityManagerInterface;

class OrderCategoryService
{
    /**
     * @var EntityManager
     */
    protected $em;


    /**
     * CommandeService constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    public function upCategory(Category $category)
    {
        $positionInitiale = $category->getPosition();
        $positionCible = $positionInitiale - 1;

        //on récupère la categorie supérieure
        $rc = $this->em->getRepository(Category::class);
        $rcCategorySuperieur = $rc->findOneBy(['position' => $positionCible]);

        // on set les nouvelles position
        $category->setPosition($positionCible);
        $rcCategorySuperieur->setPosition($positionInitiale);


        $this->em->persist($category);
        $this->em->persist($rcCategorySuperieur);

        $this->em->flush();

    }

    public function downCategory(Category $category)
    {
        $positionInitiale = $category->getPosition();
        $positionCible = $positionInitiale + 1;

        //on récupère la categorie supérieure
        $rc = $this->em->getRepository(Category::class);
        $rcCategorySuperieur = $rc->findOneBy(['position' => $positionCible]);

        // on set les nouvelles position
        $category->setPosition($positionCible);
        $rcCategorySuperieur->setPosition($positionInitiale);


        $this->em->persist($category);
        $this->em->persist($rcCategorySuperieur);

        $this->em->flush();

    }

}