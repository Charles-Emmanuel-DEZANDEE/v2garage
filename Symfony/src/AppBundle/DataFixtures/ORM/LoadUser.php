<?php
/**
 * Created by PhpStorm.
 * User: cdezandee
 * Date: 29/12/2017
 * Time: 16:35
 */
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\user;

class LoadUser extends Fixture implements  FixtureInterface
{
    // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
    public function load(ObjectManager $manager)
    {

        $role = new Role();
        $role->setName('ROLE_ADMIN');
        // On la persiste
        $manager->persist($role);

        // On déclenche l'enregistrement
        $manager->flush();

        // On crée un user
        $user = new User();
        $user->setEmail('contacts@mikeauto17.fr');
        $user->setUsername('mike');
        $user->setPassword('P@$$w0rd');


        $this->addReference('user', $user);

            // On la persiste
            $manager->persist($user);

        // On crée un user
        $user2 = new User();
        $user2->setEmail('cdezandee@gmail.com');
        $user2->setUsername('admin');
        $user2->setPassword('P@$$w0rd');

            // On la persiste
            $manager->persist($user2);

        // On déclenche l'enregistrement
        $manager->flush();
    }

}