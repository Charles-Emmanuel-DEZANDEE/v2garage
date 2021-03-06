<?php

namespace AppBundle\Repository;

/**
 * uniteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UniteRepository extends \Doctrine\ORM\EntityRepository
{
    public function uniteNotExist($code)
    {
        $response = true;
        $results = $this->createQueryBuilder('unite')
            ->where('unite.code = :vname')
            ->setParameter('vname', $code)
            ->getQuery()
            ->getResult();
        if (!empty($results)) {
            $response = false;
        }

        return $response;
    }

}
