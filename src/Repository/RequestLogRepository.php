<?php

namespace App\Repository;

use App\Entity\Request;
use App\Entity\RequestLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RequestLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method RequestLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method RequestLog[]    findAll()
 * @method RequestLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequestLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RequestLog::class);
    }

    /**
     * @return RequestLog[] Returns an array of RequestLog objects
     */
    public function findAllRequestLog(Request $request, string $orderBy = 'ASC'): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.request = :request')
            ->setParameter('request', $request)
            ->orderBy('r.created_at', $orderBy)
            ->getQuery()
            ->getResult();
    }

}
