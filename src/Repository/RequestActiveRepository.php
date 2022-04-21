<?php

namespace App\Repository;

use App\Entity\Customer;
use App\Entity\Fixer;
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
     * @param Customer $customer
     * @param $statuses
     * @return array
     */
    public function findUserRequestsByStatus(Customer $customer, $statuses): array
    {
        return $this->createQueryBuilder('ra')
            ->select('ra', 'r', 's', 'f')
            ->innerJoin('ra.request', 'r')
            ->innerJoin('ra.fixer', 'f')
            ->innerJoin('r.customer', 'c')
            ->innerJoin('r.service', 's')
            ->andwhere('r.customer = :customer')
            ->andWhere('r.status IN (:status)')
            ->setParameter('customer', $customer)
            ->setParameter('status', $statuses)
            ->orderBy('ra.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Fixer $fixer
     * @return array
     */
    public function findActiveRequestsByFixer(Fixer $fixer): array
    {
        return $this->createQueryBuilder('ra')
            ->select('ra')
            ->innerJoin('ra.request', 'r')
            ->innerJoin('r.customer', 'c')
            ->andWhere('ra.fixer = :fixer')
            ->andWhere('ra.status IN (:statuses)')
            ->setParameter('fixer', $fixer)
            ->setParameter('statuses', [Constant::STATUS_DEFAULT, Constant::STATUS_ACTIVE, Constant::STATUS_PAUSED])
            ->orderBy('ra.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Fixer $fixer
     * @return array
     */
    public function findFixerDoneRequests(Fixer $fixer): array
    {
        return $this->createQueryBuilder('ra')
            ->select('ra')
            ->innerJoin('ra.request', 'r')
            ->innerJoin('r.customer', 'c')
            ->andWhere('ra.fixer = :fixer')
            ->andWhere('ra.status IN (:status)')
            ->setParameter('fixer', $fixer)
            ->setParameter('status', [Constant::STATUS_DONE, Constant::STATUS_CANCELLED])
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $slug
     * @return RequestActive|null
     */
    public function findRequestBySlug($slug): ?RequestActive
    {
        return $this->createQueryBuilder('ra')
            ->select('ra', 'r', 'c')
            ->innerJoin('ra.request', 'r')
            ->innerJoin('r.customer', 'c')
            ->andWhere('r.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult();
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
