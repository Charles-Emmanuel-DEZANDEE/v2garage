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
use AppBundle\Entity\Customer;

class LoadCustomer extends Fixture implements FixtureInterface
{
    // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
    public function load(ObjectManager $manager)
    {

        // On crée un client
        $customer = new Customer();
        $customer->setEmail('toto@google.fr');
        $customer->setFirstname('toto');
        $customer->setLastname('lulu');
        $customer->setAddressNumber('5');
        $customer->setAddressRoad1('rue de la joie');
        $customer->setAddressZipcode('15251');
        $customer->setAddressCity('toronto');
        $customer->setAddressCountry('canada');
        $customer->setTelephonePrimary('0665656565');
        $customer->setLastActionDate(new \DateTime());

        $this->addReference('customer1', $customer);

        // On la persiste
        $manager->persist($customer);

        // On crée un client2
        $customer2 = new Customer();
        $customer2->setEmail('titi@google.fr');
        $customer2->setFirstname('titi');
        $customer2->setLastname('lolo');
        $customer2->setAddressNumber('8');
        $customer2->setAddressRoad1('rue de la vie');
        $customer2->setAddressZipcode('34251');
        $customer2->setAddressCity('lille');
        $customer2->setAddressCountry('france');
        $customer2->setTelephonePrimary('06656656565');
        $customer2->setLastActionDate(new \DateTime());

        $this->addReference('customer2', $customer2);

        // On la persiste
        $manager->persist($customer2);


        // On déclenche l'enregistrement
        $manager->flush();
    }

}