<?php

namespace App\Repository;

use App\Entity\RequestActive;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method RequestActive|null find($id, $lockMode = null, $lockVersion = null)
 * @method RequestActive|null findOneBy(array $criteria, array $orderBy = null)
 * @method RequestActive[]    findAll()
 * @method RequestActive[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequestActiveRepository extends ServiceEntityRepository
{
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, RequestActive::class);
        $this->paginator = $paginator;
    }

    /**
     * @param $id
     * @param $status
     * @return array
     */
    public function findUserRequestsByStatus($id, $status): array
    {
        return $this->createQueryBuilder('ra')
            ->select('ra')
            ->innerJoin('ra.request', 'r')
            ->innerJoin('r.customer', 'c')
            ->andwhere('c.id = :id')
            ->andWhere('r.status = :status')
            ->setParameter('id', $id)
            ->setParameter('status', $status)
            ->orderBy('ra.created_at', 'DESC')
            ->getQuery()
            ->getResult();
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
