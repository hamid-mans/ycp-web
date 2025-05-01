<?php

namespace App\Repository;

use App\Entity\Database;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Database>
 */
class DatabaseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Database::class);
    }

    public function findAllUser(User $user): array
    {
        $qb = $this->createQueryBuilder('s');

        $forbiddenDatabases = $user->getDatabasesForbidden();

        if (count($forbiddenDatabases) > 0) {
            $qb->where($qb->expr()->notIn('s.id', ':forbiddenIds'))
                ->setParameter('forbiddenIds', $forbiddenDatabases->map(fn($s) => $s->getId())->toArray());
        }

        return $qb->getQuery()->getResult();
    }

    //    /**
    //     * @return Database[] Returns an array of Database objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Database
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
