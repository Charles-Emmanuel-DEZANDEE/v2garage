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

        // on récupére les clients

        $category1 = $this->getReference('Main d\'oeuvre');
        $category2 = $this->getReference('Forfait');
        $category3 = $this->getReference('Kit');
        $category4 = $this->getReference('Pièces');
        $category5 = $this->getReference('Consommable');
        $taxRate = $this->getReference('tva');

// On créé des services pour main d'oeuvre
        $service1 = new Service();
        $service1->setName('1 heure');
        $service1->setValue(50);
        $service1->setUnite('h');
        $service1->setTaxRate($taxRate);
        $service1->setCategory($category1);

        // On la persiste
        $manager->persist($service1);

        $service12 = new Service();
        $service12->setName('1 demi journée');
        $service12->setValue(180);
        $service12->setUnite('d/j');
        $service12->setTaxRate($taxRate);
        $service12->setCategory($category1);

        // On la persiste
        $manager->persist($service12);


        $service13 = new Service();
        $service13->setName('1 journée');
        $service13->setValue(350);
        $service13->setUnite('j');
        $service13->setTaxRate($taxRate);
        $service13->setCategory($category1);

        // On la persiste
        $manager->persist($service13);

// On créé un service forfait
        $service2 = new Service();
        $service2->setName('révision');
        $service2->setValue(90);
        $service2->setUnite('forf');
        $service2->setTaxRate($taxRate);
        $service2->setCategory($category2);

        // On la persiste
        $manager->persist($service2);

// On créé un service kit
        $service3 = new Service();
        $service3->setName('phare avant');
        $service3->setValue(15.60);
        $service3->setUnite('kit');
        $service3->setTaxRate($taxRate);
        $service3->setCategory($category3);

        // On la persiste
        $manager->persist($service3);


// On créé un service piece
        $service4 = new Service();
        $service4->setName('carrosserie');
        $service4->setValue(15.60);
        $service4->setUnite('p');
        $service4->setTaxRate($taxRate);
        $service4->setCategory($category4);

        // On la persiste
        $manager->persist($service4);


        $service42 = new Service();
        $service42->setName('courroie distribution');
        $service42->setValue(26.54);
        $service42->setUnite('p');
        $service42->setTaxRate($taxRate);
        $service42->setCategory($category4);

        // On la persiste
        $manager->persist($service42);


        $service42 = new Service();
        $service42->setName('bougie');
        $service42->setValue(4.54);
        $service42->setUnite('p');
        $service42->setTaxRate($taxRate);
        $service42->setCategory($category4);

        // On la persiste
        $manager->persist($service42);

// On créé un service pour consommable
        $service5 = new Service();
        $service5->setName('huile');
        $service5->setValue(7.30);
        $service5->setUnite('l');
        $service5->setTaxRate($taxRate);
        $service5->setCategory($category5);

        // On la persiste
        $manager->persist($service5);





        // On déclenche l'enregistrement
        $manager->flush();
    }
    // permet de faire executer la fixture des clients avant
    public function getDependencies()
    {
        return array(
            \AppBundle\DataFixtures\ORM\LoadTaxRate::class,
            \AppBundle\DataFixtures\ORM\LoadCategory::class,
        );
    }

}