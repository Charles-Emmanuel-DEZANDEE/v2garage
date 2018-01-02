<?php

namespace AppBundle\Repository;

use AppBundle\Entity\UserToken;

/**
 * UserTokenRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserTokenRepository extends \Doctrine\ORM\EntityRepository
{

    public function clearUserTokensCommand(){
        $results = $this->getEntityManager()->createQueryBuilder()
            ->delete(UserToken::class, 'usertoken')
            ->where('usertoken.dateExpiration < NOW()')
            ->getQuery()
            ->execute()
        ;

        return $results;
    }

}
