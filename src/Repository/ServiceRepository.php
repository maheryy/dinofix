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

    public function findAllBySearch(SearchData $filters) : PaginationInterface
    {
        $qb = $this->createQueryBuilder('s');

        if (!empty($filters->query)) {
            $qb
                ->andWhere('s.name LIKE :query')
                ->setParameter('query', "%{$filters->query}%");
        }

        if (!empty($filters->category)) {
            $qb
                ->andWhere('s.category = :category')
                ->setParameter('category', $filters->category);
        }

        if (!empty($filters->sort)) {
            switch ($filters->sort) {
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

        return $this->paginator->paginate($qb->getQuery(), $filters->page, 5);
    }


    // /**
    //  * @return Service[] Returns an array of Service objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Service
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
