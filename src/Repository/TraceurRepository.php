<?php

namespace App\Repository;

use App\Entity\Traceur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Traceur>
 */
class TraceurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Traceur::class);
    }

    public function findByFilters(array $filters): array
    {
        $qb = $this->createQueryBuilder('traceur');

        if(!empty($filters['type'])){
            $qb->andWhere('traceur.type LIKE :type')->setParameter('type', '%'.$filters['type'].'%')
                ->orderBy('traceur.datetime', 'DESC');
        }

        if(!empty($filters['data'])){
            $qb->andWhere('traceur.data LIKE :data')->setParameter('data', '%'.$filters['data'].'%')
                ->orderBy('traceur.datetime', 'DESC');
        }

        if(!empty($filters['dataId'])){
            $qb->andWhere('traceur.dataId LIKE :dataId')->setParameter('dataId', '%'.$filters['dataId'].'%')
                ->orderBy('traceur.datetime', 'DESC');
        }

        if(!empty($filters['username'])){
            $qb->andWhere('traceur.username LIKE :username')->setParameter('username', '%'.$filters['username'].'%')
                ->orderBy('traceur.datetime', 'DESC');
        }

        return $qb->getQuery()->getResult();
    }

    //    /**
    //     * @return Traceur[] Returns an array of Traceur objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Traceur
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
