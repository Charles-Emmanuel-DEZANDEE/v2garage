<?php

namespace AppBundle\Service;


use AppBundle\Entity\Command;
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


    public function getNumeroDevis(){
        $ref = '';
        $rcCommande = $this->em->getRepository(Command::class);

        do{
            $ref = "D" . date('Y'). mt_rand(10000,99999);
        } while(!$rcCommande->referenceNotExist($ref));


        return $ref;

    }

}