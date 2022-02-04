<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Tools\Pagination\Paginator;
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
                's.id, s.name, s.description,
                f.firstname, f.lastname,
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


    /**
     * @param $id
     * @return Service|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findServiceById($id): Service|null
    {
        return $this->createQueryBuilder('s')
            ->select('s', 'f', 'a')
            ->innerJoin('s.fixer', 'f')
            ->innerJoin('f.address', 'a')
            ->andwhere('s.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param int $fixerId
     * @param int $serviceId
     * @param int $max
     * @return array
     */
    public function findFixerServices(int $fixerId, int $serviceId, int $max): array
    {
        return $this->createQueryBuilder('s')
            ->select('s.id, s.name, s.description, s.rating, f.firstname, f.lastname, f.alias, COUNT(r.id) AS reviews')
            ->innerJoin('s.fixer', 'f')
            ->leftJoin('s.reviews', 'r')
            ->andWhere('s.id <> :serviceId')
            ->andWhere('f.id = :fixerId')
            ->setParameter('serviceId', $serviceId)
            ->setParameter('fixerId', $fixerId)
            ->orderBy('s.rating', 'DESC')
            ->addOrderBy('reviews', 'DESC')
            ->groupBy('s.id, f.id')
            ->setMaxResults($max)
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_ARRAY);
    }

    /**
     * @param int $maxResults
     * @param float $minRating
     * @return array
     */
    public function findPopularServices(int $maxResults = 15, float $minRating = 3.0): array
    {
        return $this->createQueryBuilder('s')
            ->select('s.id, s.name, s.description, s.rating, f.firstname, f.lastname, f.alias, COUNT(r.id) AS reviews')
            ->innerJoin('s.fixer', 'f')
            ->leftJoin('s.reviews', 'r')
            ->where('s.rating > :minRating')
            ->setParameter('minRating', $minRating)
            ->orderBy('s.rating', 'DESC')
            ->addOrderBy('reviews', 'DESC')
            ->groupBy('s.id, f.id')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_ARRAY);
    }


}
