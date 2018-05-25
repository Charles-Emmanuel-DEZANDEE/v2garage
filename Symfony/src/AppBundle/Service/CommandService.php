<?php

namespace AppBundle\Service;

use AppBundle\Entity\Address_intervention;
use AppBundle\Entity\Customer;
use Doctrine\ORM\EntityManager;
use  Doctrine\ORM\EntityManagerInterface;

class CommandService
{
    /**
     * @var EntityManager
     */
    protected $em;


    /**
     * CommandeService constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    public function duplicateMainAddresse(Customer $Customer){
        $addresses = $Customer->getAddresses();
        $addressMainFree = true;
        foreach ($addresses as $address){
            if ($address->getName() === 'Principale') $addressMainFree = false;
        }
        if ($addressMainFree) {
            $addressesEntity = new Address_intervention($Customer);
//peuplement
            $addressesEntity->setName('Principale');
            $addressesEntity->setNumber($Customer->getAddressNumber());
            $addressesEntity->setRoad1($Customer->getAddressRoad1());
            $addressesEntity->setRoad2($Customer->getAddressRoad2());
            $addressesEntity->setZipcode($Customer->getAddressZipcode());
            $addressesEntity->setCity($Customer->getAddressCity());
            $addressesEntity->setRegion($Customer->getAddressRegion());
            $addressesEntity->setCountry($Customer->getAddressCountry());

            $this->em->persist($addressesEntity);

            //$this->em->flush();
        }

    }

}