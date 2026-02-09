<?php

namespace App\Repository;

use App\Entity\Statistic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Statistic>
 */
class StatisticRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Statistic::class);
    }

    public function findByCriteria(array $criteria): array
    {
        $qb = $this->createQueryBuilder('s');
        foreach ($criteria as $key => $value) {
            if (!empty($value)) {
                $qb->andWhere("s.$key = :$key")->setParameter($key, $value);
            }
        }

        return $qb->getQuery()->getResult();
    }

    public function findOneByCriteria(array $criteria): ?Statistic
    {
        $qb = $this->createQueryBuilder('s');
        foreach ($criteria as $key => $value) {
            if (!empty($value)) {
                $qb->andWhere("s.$key = :$key")->setParameter($key, $value);
            }
        }

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function save(Statistic $statistic): void
    {
        $this->getEntityManager()->persist($statistic);
        $this->getEntityManager()->flush();
    }
}
