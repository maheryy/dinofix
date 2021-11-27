<?php

namespace App\Repository;

use App\Entity\RequestActive;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RequestActive|null find($id, $lockMode = null, $lockVersion = null)
 * @method RequestActive|null findOneBy(array $criteria, array $orderBy = null)
 * @method RequestActive[]    findAll()
 * @method RequestActive[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequestActiveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RequestActive::class);
    }

    // /**
    //  * @return RequestActive[] Returns an array of RequestActive objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RequestActive
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
