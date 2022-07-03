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
     * @param int|null $maxResults
     * @return Review[]
     */
    public function findServiceReviews(int $serviceId, ?int $maxResults = null): array
    {
        $qb = $this->createQueryBuilder('r')
            ->innerJoin('r.customer', 'c')
            ->select('r', 'c')
            ->where('r.service = :service')
            ->setParameter('service', $serviceId)
            ->orderBy('r.rate', 'DESC');

        if ($maxResults) {
            $qb->setMaxResults($maxResults);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $fixerId
     * @param int|null $maxResults
     * @return Review[]
     */
    public function findFixerReviews(int $fixerId, ?int $maxResults = null): array
    {
        $qb = $this->createQueryBuilder('r')
            ->innerJoin('r.customer', 'c')
            ->select('r', 'c')
            ->where('r.fixer = :fixer')
            ->setParameter('fixer', $fixerId)
            ->orderBy('r.rate', 'DESC');

        if ($maxResults) {
            $qb->setMaxResults($maxResults);
        }

        return $qb->getQuery()->getResult();
    }
}
