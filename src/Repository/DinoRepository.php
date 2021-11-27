<?php

namespace App\Repository;

use App\Entity\Dino;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Dino|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dino|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dino[]    findAll()
 * @method Dino[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DinoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dino::class);
    }

    // /**
    //  * @return Dino[] Returns an array of Dino objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Dino
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
