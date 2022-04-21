<?php

namespace App\Repository;

use App\Entity\RequestActive;
use App\Entity\Service;
use App\Service\Constant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\NonUniqueResultException;
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
            ->select('ra', 'r', 'st', 's', 'f')
            ->innerJoin('ra.request', 'r')
            ->innerJoin('ra.fixer', 'f')
            ->innerJoin('ra.step', 'st')
            ->innerJoin('r.customer', 'c')
            ->innerJoin('r.service', 's')
            ->andwhere('c.id = :id')
            ->andWhere('r.status = :status')
            ->setParameter('id', $id)
            ->setParameter('status', $status)
            ->orderBy('ra.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $id
     * @return array
     */
    public function findUserRequestsByFixerId($id): array
    {
        return $this->createQueryBuilder('ra')
            ->select('ra')
            ->innerJoin('ra.request', 'r')
            ->innerJoin('r.customer', 'c')
            ->andWhere('ra.fixer = :id')
            ->andWhere('ra.status NOT IN(:statuses)')
            ->setParameter('id', $id)
            ->setParameter('statuses', Constant::STATUS_DONE)
            ->orderBy('ra.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $fixer
     * @return array
     */
    public function findFixerDoneRequests($fixer): array
    {
        return $this->createQueryBuilder('ra')
            ->select('ra')
            ->innerJoin('ra.request', 'r')
            ->innerJoin('r.customer', 'c')
            ->andWhere('ra.fixer = :id')
            ->andWhere('ra.status = :status')
            ->setParameter('id', $fixer)
            ->setParameter('status', Constant::STATUS_DONE)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $slug
     * @return RequestActive|null
     */
    public function findRequestBySlug($slug): ?RequestActive
    {
        $qb = $this->createQueryBuilder('ra')
            ->select('ra', 'r', 'c')
            ->innerJoin('ra.request', 'r')
            ->innerJoin('r.customer', 'c')
            ->andWhere('r.slug = :slug')
            ->setParameter('slug', $slug);

        try {
            $res = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            $res = null;
        }

        return $res;
    }

    /**
     * @param Service $service
     * @return int|null
     */
    public function countActiveRequestsByService(Service $service): ?int
    {
        return $this->createQueryBuilder('ra')
            ->select('COUNT(r.id)')
            ->innerJoin('ra.request', 'r')
            ->andWhere('r.service = :service')
            ->andWhere('ra.status NOT IN(:statuses)')
            ->setParameter('service', $service)
            ->setParameter('statuses', [Constant::STATUS_DONE, Constant::STATUS_CANCELLED, Constant::STATUS_DELETED, Constant::STATUS_INACTIVE])
            ->getQuery()
            ->getOneOrNullResult(AbstractQuery::HYDRATE_SINGLE_SCALAR);
    }


}
