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
use AppBundle\Entity\Vehicule;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class LoadVehicule extends Fixture implements  FixtureInterface,DependentFixtureInterface
{
    // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
    public function load(ObjectManager $manager)
    {
       $dateaujourdhui= new \DateTime();
        // on récupére les clients

        $customer1 = $this->getReference('customer1');
        $customer2 = $this->getReference('customer2');

// On créé un véhicule
        $Vehicule = new Vehicule();
        $Vehicule->setBrand('Peugeot');
        $Vehicule->setModel('205');
        $Vehicule->setCirculationLaunchDate(new \DateTime('2000-10-10'));
        $Vehicule->setMileage('105000');
        $Vehicule->setRegistration('AA-789-BN');
        $Vehicule->setVin('PEUGG54765436VBXN55855');
        $Vehicule->setLastControlDate($dateaujourdhui->modify('-23 month'));
        $Vehicule->setCustomer($customer1);

        // On la persiste
        $manager->persist($Vehicule);

        // On créé deuxiéme véhicule
        $Vehicule2 = new Vehicule();
        $Vehicule2->setBrand('Citroen');
        $Vehicule2->setModel('C4');
        $Vehicule2->setCirculationLaunchDate(new \DateTime('2012-10-10'));
        $Vehicule2->setLastControlDate($dateaujourdhui->modify('-25 month'));
        $Vehicule2->setMileage('26000');
        $Vehicule2->setRegistration('AZ-789-BN');
        $Vehicule2->setVin('CITGG54765436VBXN55855');
        $Vehicule2->setCustomer($customer1);

        // On la persiste
        $manager->persist($Vehicule2);


        // On créé troisieme véhicule
        $Vehicule3 = new Vehicule();
        $Vehicule3->setBrand('ford');
        $Vehicule3->setModel('focus');
        $Vehicule3->setCirculationLaunchDate(new \DateTime('2013-10-10'));
        $Vehicule3->setLastControlDate(new \DateTime('2017-10-01'));
        $Vehicule3->setMileage('36000');
        $Vehicule3->setRegistration('AP-000-BN');
        $Vehicule3->setVin('FORDGG54765436VBXN55855');
        $Vehicule3->setCustomer($customer2);

        // On la persiste
        $manager->persist($Vehicule3);


        // On créé quatrieme véhicule
        $Vehicule4 = new Vehicule();
        $Vehicule4->setBrand('VW');
        $Vehicule4->setModel('golf');
        $Vehicule4->setCirculationLaunchDate(new \DateTime('2017-10-10'));
        $Vehicule4->setMileage('6000');
        $Vehicule4->setRegistration('AD-000-BN');
        $Vehicule4->setVin('VWWGG54765436VBXN55855');
        $Vehicule4->setCustomer($customer2);

        // On la persiste
        $manager->persist($Vehicule4);




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