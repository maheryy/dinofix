<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Review|null find($id, $lockMode = null, $lockVersion = null)
 * @method Review|null findOneBy(array $criteria, array $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    /**
     * @param int $serviceId
     * @return Review[]
     */
    public function findServiceReviews(int $serviceId): array
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.customer', 'c')
            ->select('r', 'c')
            ->where('r.service = :service')
            ->setParameter('service', $serviceId)
            ->orderBy('r.rate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $fixerId
     * @return Review[]
     */
    public function findFixerReviews(int $fixerId): array
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.customer', 'c')
            ->select('r', 'c')
            ->where('r.fixer = :fixer')
            ->setParameter('fixer', $fixerId)
            ->orderBy('r.rate', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
