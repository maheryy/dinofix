<?php

namespace App\Repository;

use App\Entity\ServiceStep;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ServiceStep|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServiceStep|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServiceStep[]    findAll()
 * @method ServiceStep[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceStepRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServiceStep::class);
    }

    public function findOneByStepValue($value): ?ServiceStep
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.step = :value')
            ->setParameter('value', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }

    // /**
    //  * @return ServiceStep[] Returns an array of ServiceStep objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ServiceStep
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
