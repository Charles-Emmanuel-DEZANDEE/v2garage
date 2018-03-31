<?php
/**
 * Created by PhpStorm.
 * User: cdezandee
 * Date: 29/12/2017
 * Time: 16:35
 */
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Unite;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\TaxRate;

class LoadUnite extends Fixture implements FixtureInterface
{
    // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
    public function load(ObjectManager $manager)
    {


        $unite = new Unite();
        $unite->setCode('l');
        $unite->setLibelle('litre');
        $this->addReference('litre', $unite);

        // On la persiste
        $manager->persist($unite);

        $unite1 = new Unite();
        $unite1->setCode('pce');
        $unite1->setLibelle('piece');
        $this->addReference('piece', $unite1);

        // On la persiste
        $manager->persist($unite1);

        $unite2 = new Unite();
        $unite2->setCode('h');
        $unite2->setLibelle('heure');
        $this->addReference('heure', $unite2);

        // On la persiste
        $manager->persist($unite2);


        $unite3 = new Unite();
        $unite3->setCode('d/j');
        $unite3->setLibelle('1/2 journée');
        $this->addReference('d/j', $unite3);

        // On la persiste
        $manager->persist($unite3);

        $unite4 = new Unite();
        $unite4->setCode('j');
        $unite4->setLibelle('journée');
        $this->addReference('jour', $unite4);

        // On la persiste
        $manager->persist($unite4);

        $unite5 = new Unite();
        $unite5->setCode('fort');
        $unite5->setLibelle('forfait');
        $this->addReference('forfait', $unite5);

        // On la persiste
        $manager->persist($unite5);

        $unite6 = new Unite();
        $unite6->setCode('kit');
        $unite6->setLibelle('kit');
        $this->addReference('kit', $unite6);

        // On la persiste
        $manager->persist($unite6);


        // On déclenche l'enregistrement
        $manager->flush();
    }

}