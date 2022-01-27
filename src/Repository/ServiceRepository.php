<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Service|null find($id, $lockMode = null, $lockVersion = null)
 * @method Service|null findOneBy(array $criteria, array $orderBy = null)
 * @method Service[]    findAll()
 * @method Service[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceRepository extends ServiceEntityRepository
{

    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Service::class);
        $this->paginator = $paginator;

    }

    public function findAllBySearch(SearchData $filters): PaginationInterface
    {
        $qb = $this->createQueryBuilder('s')
            ->innerJoin('s.fixer', 'f')
            ->innerJoin('f.address', 'a')
            ->select(
                's.name, s.description,
                f.first_name, f.last_name,
                a.country, a.region, a.postcode, a.city, a.street'
            );

        if ($filters->getQuery()) {
            $qb
                ->andWhere('lower(s.name) LIKE :query')
                ->setParameter('query', '%' . strtolower($filters->getQuery()) . '%');
        }

        if ($filters->getCategory()) {
            $qb
                ->andWhere('s.category = :category')
                ->setParameter('category', $filters->getCategory());
        }

        if ($filters->getSort()) {
            switch ($filters->getSort()) {
                case SearchData::SORT_TYPE_NAME:
                    $qb->orderBy('s.name', 'DESC');
                    break;
                case SearchData::SORT_TYPE_REVIEW:
                    //$qb->orderBy('reviews', 'D');
                    break;
                case SearchData::SORT_TYPE_LOCATION:
                    //$qb->orderBy('reviews', 'DESC');
                    break;

            }
        }

        return $this->paginator->paginate($qb->getQuery(), $filters->getPage(), 5);
    }

}
