<?php

namespace App\Repository;

use App\Entity\Software;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Software>
 */
class SoftwareRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Software::class);
    }

    public function findAllUser(User $user): array
    {
        $qb = $this->createQueryBuilder('s');

        $forbiddenSoftwares = $user->getSoftwaresForbidden();

        if (count($forbiddenSoftwares) > 0) {
            $qb->where($qb->expr()->notIn('s.id', ':forbiddenIds'))
                ->setParameter('forbiddenIds', $forbiddenSoftwares->map(fn($s) => $s->getId())->toArray());
        }

        return $qb->getQuery()->getResult();
    }

    //    /**
    //     * @return Software[] Returns an array of Software objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Software
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
