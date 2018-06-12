<?php

namespace AppBundle\Repository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    public function userNotExist($email)
    {
        $response = true;
        $results = $this->createQueryBuilder('user')
            ->where('user.email = :value')
            ->setParameter('value', $email)
            ->getQuery()
            ->getResult();
        if (!empty($results)) {
            $response = false;
        }

        return $response;
    }
}
