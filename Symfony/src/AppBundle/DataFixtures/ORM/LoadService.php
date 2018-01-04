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
       $dateaujourdhui= new \DateTime();
        // on récupére les clients

        $category1 = $this->getReference('Main d\'oeuvre');
        $category2 = $this->getReference('Forfait');
        $category3 = $this->getReference('Kit');
        $category4 = $this->getReference('Pièces');
        $category5 = $this->getReference('Consommable');
        $taxRate = $this->getReference('tva');

// On créé des services pour main d'oeuvre
        $adress1 = new Service();
        $adress1->setName('Travail');
        $adress1->setValue('2');
        $adress1->setTaxRate($taxRate);
        $adress1->setZipcode('41560');
        $adress1->setCity('toronto');
        $adress1->setCountry('canada');
        $adress1->setCustomer($category1);

        // On la persiste
        $manager->persist($adress1);

// On créé une adresse
        $adress2 = new Address_intervention();
        $adress2->setName('Travail');
        $adress2->setNumber('4');
        $adress2->setRoad1('boulevard trouvee');
        $adress2->setZipcode('65987');
        $adress2->setCity('vouille');
        $adress2->setCountry('france');
        $adress2->setCustomer($category2);

        // On la persiste
        $manager->persist($adress2);



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