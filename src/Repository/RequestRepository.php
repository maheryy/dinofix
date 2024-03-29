<?php

namespace App\Repository;

use App\Entity\Customer;
use App\Entity\Request;
use App\Entity\Service;
use App\Service\Constant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Util\SecureRandom;

/**
 * @method Request|null find($id, $lockMode = null, $lockVersion = null)
 * @method Request|null findOneBy(array $criteria, array $orderBy = null)
 * @method Request[]    findAll()
 * @method Request[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequestRepository extends ServiceEntityRepository
{
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Request::class);
        $this->paginator = $paginator;
    }

    public function findFreeRequests(array $categories, array $dinos)
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.category', 'c')
            ->leftJoin('r.dino', 'd')
            ->where('r.service IS NULL')
            ->andWhere('r.status = :defaultStatus')
            ->andWhere('c.id IN (:categories) OR d.id IN (:dinos)')
            ->setParameter('defaultStatus', Constant::STATUS_DEFAULT)
            ->setParameter('categories', $categories)
            ->setParameter('dinos', $dinos)
            ->orderBy('r.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findRequestBySlug(string $slug): ?Request
    {
        return $this->createQueryBuilder('r')
            ->where('r.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findCustomerOpenRequests(Customer $customer) {
        return $this->createQueryBuilder('r')
            ->where('r.service IS NULL')
            ->andWhere('r.status = :defaultStatus')
            ->andWhere('r.customer = :customer')
            ->setParameter('defaultStatus', Constant::STATUS_DEFAULT)
            ->setParameter('customer', $customer)
            ->orderBy('r.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findPaidRequest(Customer $customer, Service $service, string $reference) {
        return $this->createQueryBuilder('r')
            ->where('r.customer = :customer')
            ->andWhere('r.service = :service')
            ->andWhere('r.payment_reference = :reference')
            ->setParameter('customer', $customer)
            ->setParameter('service', $service)
            ->setParameter('reference', $reference)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
