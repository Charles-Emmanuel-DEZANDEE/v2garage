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
use AppBundle\Entity\TaxRate;

class LoadTaxRate extends Fixture implements FixtureInterface
{
    // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
    public function load(ObjectManager $manager)
    {


        $taxRate = new TaxRate();
        $taxRate->setValue(20);

        $this->addReference('tva', $taxRate);

        // On la persiste
        $manager->persist($taxRate);


        // On déclenche l'enregistrement
        $manager->flush();
    }

}