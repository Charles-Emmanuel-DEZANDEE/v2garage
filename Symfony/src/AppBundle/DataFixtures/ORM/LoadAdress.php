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
use AppBundle\Entity\Address_intervention;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class LoadAdress extends Fixture implements  FixtureInterface,DependentFixtureInterface
{
    // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
    public function load(ObjectManager $manager)
    {
       $dateaujourdhui= new \DateTime();
        // on récupére les clients

        $customer1 = $this->getReference('customer1');
        $customer2 = $this->getReference('customer2');

// On créé une adresse
        $adress1 = new Address_intervention();
        $adress1->setName('Travail');
        $adress1->setNumber('2');
        $adress1->setRoad1('impasse perdue');
        $adress1->setZipcode('41560');
        $adress1->setCity('toronto');
        $adress1->setCountry('canada');
        $adress1->setCustomer($customer1);

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
        $adress2->setCustomer($customer2);

        // On la persiste
        $manager->persist($adress2);



        // On déclenche l'enregistrement
        $manager->flush();
    }
    // permet de faire executer la fixture des clients avant
    public function getDependencies()
    {
        return array(
            \AppBundle\DataFixtures\ORM\LoadUser::class,
        );
    }

}