<?php

namespace AppBundle\Repository;

/**
 * CustomerRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CustomerRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllOrderByLastUpdate(){
        $results = $this->createQueryBuilder('customer')
            ->orderBy('customer.lastActionDate')
            ->getQuery()
            ->getResult()
        ;

        return $results;
    }

    public function customerNotExist($email, $lastName, $zipcode){
        $response = true;
        $results = $this->createQueryBuilder('customer')
            ->where('customer.email = :email')
            ->setParameter('email' , $email)
            ->andWhere('customer.lastName = :lastName')
            ->setParameter('lastName' , $lastName)
            ->andWhere('customer.addressZipcode = :zipcode')
            ->setParameter('zipcode' , $zipcode)
            ->getQuery()
            ->getResult()
        ;
        $a= $results;
        if (!empty($results)){
            $response = false;
        }

        return $response;
    }
}
