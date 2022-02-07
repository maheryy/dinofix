<?php

namespace App\Repository;

use App\Entity\Admin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Admin|null find($id, $lockMode = null, $lockVersion = null)
 * @method Admin|null findOneBy(array $criteria, array $orderBy = null)
 * @method Admin[]    findAll()
 * @method Admin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdminRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Admin::class);
    }

    // /**
    //  * @return Admin[] Returns an array of Admin objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */



    public function findAllCustomer()
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT c.first_name, c.last_name, c.email, c.phone
            FROM App\Entity\Customer c
            '
        );

        // returns an array of Product objects
        return $query->getResult();
    }

    public function findAllFixer()
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT f.first_name, f.last_name, f.email, f.phone
            FROM App\Entity\Fixer f
            '
        );

        // returns an array of Product objects
        return $query->getResult();
    }
    /*
    public function findOneBySomeField($value): ?Admin
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
