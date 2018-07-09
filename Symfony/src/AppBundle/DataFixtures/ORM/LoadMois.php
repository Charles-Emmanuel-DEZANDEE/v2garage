<?php
/**
 * Created by PhpStorm.
 * User: cdezandee
 * Date: 29/12/2017
 * Time: 16:35
 */
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Mois;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadMois extends Fixture implements FixtureInterface
{
    // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
    public function load(ObjectManager $manager)
    {


        $table = [[1, 'Janvier'],[2, 'Février'],[3, 'Mars'],[4, 'Avril'],[5, 'Mai'],[6, 'Juin'],[7, 'Juillet'],[8, 'Août'],[9, 'Septembre'],[10, 'Octobre'],[11, 'Novembre'],[12, 'Décembre']];

        foreach ($table as $ligne){

            $mois = new Mois();
            $mois->setCode($ligne[0]);
            $mois->setName($ligne[1]);


            // On la persiste
            $manager->persist($mois);
        }

        // On déclenche l'enregistrement
        $manager->flush();
    }

}