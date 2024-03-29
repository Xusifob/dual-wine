<?php

namespace AppBundle\Entity\Api;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr as Expr;
use Doctrine\ORM\AbstractQuery;

/**
 * TourRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TourRepository extends EntityRepository
{


    public function queryTour($id = null)
    {
        $qb = $this->createQueryBuilder('t');
        $qb->select('t,q1,q2,q3')
            ->leftJoin('t.question1','q1', Expr\Join::WITH)
            ->leftJoin('t.question2','q2', Expr\Join::WITH)
            ->leftJoin('t.question3','q3', Expr\Join::WITH)
            ->orderBy('t.id', 'DESC')
        ;
        if($id != null){
            $qb->where('t.id = :id')
                ->setParameters([
                    ':id' => $id,
                ])
            ;
        }
        return null === $id
            ? $qb->getQuery()->getArrayResult()
            : $qb->getQuery()->getSingleResult(AbstractQuery::HYDRATE_ARRAY);
    }




}
