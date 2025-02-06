<?php

namespace App\Repository;

use App\Entity\Record;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Record>
 */
class RecordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Record::class);
    }

    public function getRecords($userId)
    {
        
        $qb = $this->createQueryBuilder('r')
            ->join('r.user', 'u')
            ->where("u.id = :userId")
            ->setParameter('userId', $userId)
            ->andWhere("r.date IS NOT NULL")
            ->orderBy('r.date', 'asc')
            //->setMaxResults($max)
            ;

        $query = $qb->getQuery();

        return $query->getResult();
    }

    //    /**
    //     * @return Record[] Returns an array of Record objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Record
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
