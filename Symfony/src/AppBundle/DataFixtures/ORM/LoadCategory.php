<?php
/**
 * Created by PhpStorm.
 * User: cdezandee
 * Date: 29/12/2017
 * Time: 16:35
 */
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Category;

class LoadCategory extends Fixture implements FixtureInterface
{
    // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
    public function load(ObjectManager $manager)
    {

        // Liste des noms de catégorie à ajouter
        $list = array(
           ['name'=>'Main d\'oeuvre', 'position'=> 1],
           ['name'=>'Forfait', 'position'=> 2],
           ['name'=>'Kit', 'position'=> 3],
           ['name'=>'Pièces', 'position'=> 4],
           ['name'=>'Consomable', 'position'=> 5]
        );

        foreach ($list as $cat) {
            // On crée la catégorie
            $category = new Category();
            $category->setName($cat['name']);
            $category->setPosition($cat['position']);

            // On la persiste
            $manager->persist($category);
        }


        // On déclenche l'enregistrement
        $manager->flush();
    }

}