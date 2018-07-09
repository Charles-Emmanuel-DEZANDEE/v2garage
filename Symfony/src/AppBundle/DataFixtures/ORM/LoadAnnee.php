<?php
/**
 * Created by PhpStorm.
 * User: cdezandee
 * Date: 29/12/2017
 * Time: 16:35
 */
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Annee;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadAnnee extends Fixture implements FixtureInterface
{
    // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
    public function load(ObjectManager $manager)
    {
        for ($i = 2017; $i < 2060; $i++) {

            $annee = new Annee();
            $annee->setCode($i);

            // On la persiste
            $manager->persist($annee);
        }

        // On déclenche l'enregistrement
        $manager->flush();
    }

}