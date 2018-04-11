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
use AppBundle\Entity\Service;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class LoadService extends Fixture implements  FixtureInterface,DependentFixtureInterface
{
    // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
    public function load(ObjectManager $manager)
    {

        // on récupére les categories

        $category1 = $this->getReference('Main d\'oeuvre');
        $category2 = $this->getReference('Forfait');
        $category3 = $this->getReference('Kit');
        $category4 = $this->getReference('Pièces');
        $category5 = $this->getReference('Consommable');

        //tva
        $taxRate = $this->getReference('tva');

        //unite
        $unite = $this->getReference('litre');
        $unite1 = $this->getReference('forfait');
        $unite2 = $this->getReference('piece');
        $unite3 = $this->getReference('heure');
        $unite4 = $this->getReference('d/j');
        $unite5 = $this->getReference('jour');
        $unite6 = $this->getReference('kit');

// On créé des services pour main d'oeuvre
        $service1 = new Service($category1);
        $service1->setName('1 heure');
        $service1->setValue(50);
        $service1->setUnite($unite3);
        $service1->setTaxRate($taxRate);

        // On la persiste
        $manager->persist($service1);

        $service12 = new Service($category1);
        $service12->setName('1 demi journée');
        $service12->setValue(180);
        $service12->setUnite($unite4);
        $service12->setTaxRate($taxRate);

        // On la persiste
        $manager->persist($service12);


        $service13 = new Service($category1);
        $service13->setName('1 journée');
        $service13->setValue(350);
        $service13->setUnite($unite5);
        $service13->setTaxRate($taxRate);

        // On la persiste
        $manager->persist($service13);

// On créé un service forfait
        $service2 = new Service($category2);
        $service2->setName('révision');
        $service2->setValue(90);
        $service2->setUnite($unite1);
        $service2->setTaxRate($taxRate);
        $service2->setCategory();

        // On la persiste
        $manager->persist($service2);

// On créé un service kit
        $service3 = new Service($category3);
        $service3->setName('phare avant');
        $service3->setValue(15.60);
        $service3->setUnite($unite6);
        $service3->setTaxRate($taxRate);

        // On la persiste
        $manager->persist($service3);


// On créé un service piece
        $service4 = new Service($category4);
        $service4->setName('carrosserie');
        $service4->setValue(15.60);
        $service4->setUnite($unite2);
        $service4->setTaxRate($taxRate);

        // On la persiste
        $manager->persist($service4);


        $service42 = new Service($category4);
        $service42->setName('courroie distribution');
        $service42->setValue(26.54);
        $service42->setUnite($unite2);
        $service42->setTaxRate($taxRate);

        // On la persiste
        $manager->persist($service42);


        $service42 = new Service($category4);
        $service42->setName('bougie');
        $service42->setValue(4.54);
        $service42->setUnite($unite2);
        $service42->setTaxRate($taxRate);

        // On la persiste
        $manager->persist($service42);

// On créé un service pour consommable
        $service5 = new Service($category5);
        $service5->setName('huile');
        $service5->setValue(7.30);
        $service5->setUnite($unite);
        $service5->setTaxRate($taxRate);

        // On la persiste
        $manager->persist($service5);

        // On déclenche l'enregistrement
        $manager->flush();
    }
    // permet de faire executer les fixtures suivantes avant
    public function getDependencies()
    {
        return array(
            \AppBundle\DataFixtures\ORM\LoadTaxRate::class,
            \AppBundle\DataFixtures\ORM\LoadCategory::class,
            \AppBundle\DataFixtures\ORM\LoadUnite::class,
        );
    }

}