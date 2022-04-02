<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\NonUniqueResultException;
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

    public function findAllBySearch(SearchData $filters, int $maxPageResults = 5): PaginationInterface
    {
        $location = [
            'address' => '242 Rue du Faubourg Saint-Antoine, 75012 Paris',
            'latitude' => 48.849372876032064,
            'longitude' => 2.390356710312387
        ];

        $qb = $this->createQueryBuilder('s')
            ->innerJoin('s.fixer', 'f')
            ->innerJoin('f.address', 'a')
            ->leftJoin('s.reviews', 'r')
            ->select(
                's.id, s.name, s.slug, s.description, s.rating AS service_rating, COUNT(r.id) AS count_reviews,
                f.firstname, f.lastname, f.alias, f.rating as fixer_rating,
                a.country, a.region, a.postcode, a.city, a.street, GEO_DISTANCE(a.latitude, a.longitude, :latitude, :longitude) AS distance'
            )
            ->setParameter('latitude', $location['latitude'])
            ->setParameter('longitude', $location['longitude'])
            ->groupBy('s.id', 'f.id', 'a.id');

        if ($filters->getQuery()) {
            $qb->andWhere('lower(s.name) LIKE :query')
                ->setParameter('query', '%' . strtolower($filters->getQuery()) . '%');
        }

        if ($filters->getCategory()) {
            $qb->andWhere('s.category = :category')
                ->setParameter('category', $filters->getCategory());
        }

        if ($filters->getDinos() && !$filters->getDinos()->isEmpty()) {
            $qb->andWhere('s.dino IN (:dinos)')
                ->setParameter('dinos', array_map(fn ($dino) => $dino->getId(), $filters->getDinos()->toArray()));
        }

        if (!empty($filters->getReviews())) {
            $qb->andWhere('FLOOR(s.rating) IN (:reviews)')
                ->setParameter('reviews', $filters->getReviews());
        }

        if ($filters->getDistance()) {
            $qb->andWhere('GEO_DISTANCE(a.latitude, a.longitude, :latitude, :longitude) <= :distance')
                ->setParameter('distance', $filters->getDistance());
        }

        if ($filters->getSort()) {
            switch ($filters->getSort()) {
                case SearchData::SORT_TYPE_NAME:
                    $qb->addOrderBy('s.name', 'ASC');
                    break;
                case SearchData::SORT_TYPE_REVIEW:
                    $qb->addOrderBy('s.rating', 'DESC');
                    break;
                case SearchData::SORT_TYPE_POPULAR:
                    $qb
                        ->addOrderBy('count_reviews', 'DESC')
                        ->addOrderBy('s.rating', 'DESC');
                    break;
                case SearchData::SORT_TYPE_LOCATION:
                    $qb->addOrderBy('distance', 'ASC');
                    break;
            }
        } else {
            $qb->addOrderBy('distance', 'ASC');
        }

        return $this->paginator->paginate($qb->getQuery(), $filters->getPage(), $maxPageResults);
    }


    /**
     * @param $id
     * @return Service|null
     */
    public function findServiceById($id): Service|null
    {
        $qb = $this->createQueryBuilder('s')
            ->select('s', 'f', 'a')
            ->innerJoin('s.fixer', 'f')
            ->innerJoin('f.address', 'a')
            ->andwhere('s.id = :id')
            ->setParameter('id', $id);

        try {
            $res = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            $res = null;
        }

        return $res;
    }

    /**
     * @param $slug
     * @return Service|null
     */
    public function findServiceBySlug($slug): Service|null
    {
        $qb = $this->createQueryBuilder('s')
            ->select('s', 'f', 'a')
            ->innerJoin('s.fixer', 'f')
            ->innerJoin('f.address', 'a')
            ->andwhere('s.slug = :slug')
            ->setParameter('slug', $slug);

        try {
            $res = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            $res = null;
        }

        return $res;
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
            ->select('s.id, s.name, s.slug, s.description, s.rating, f.firstname, f.lastname, f.alias, COUNT(r.id) AS reviews')
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
            ->select('s.id, s.name, s.slug, s.description, s.rating, f.firstname, f.lastname, f.alias, COUNT(r.id) AS reviews')
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

    /**
     * @param int $maxResults
     * @param string $sortBy
     * @return array
     */
    public function findRandomServices(int $maxResults = 15, string $sortBy = 'name'): array
    {
        $sortType = ['ASC', 'DESC'];
        return $this->createQueryBuilder('s')
            ->select('s.id, s.name, s.slug, s.description, s.rating, f.firstname, f.lastname, f.alias, COUNT(r.id) AS reviews')
            ->innerJoin('s.fixer', 'f')
            ->leftJoin('s.reviews', 'r')
            ->orderBy("s.{$sortBy}", $sortType[array_rand($sortType)])
            ->groupBy('s.id, f.id')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_ARRAY);
    }

    /**
     * @param int $fixerId
     * @param int $maxResults
     * @return array
     */
    public function findFixerServicesById(int $fixerId, int $maxResults = 15): array
    {
        if ($maxResults == -1) {
            return $this->createQueryBuilder('s')
                ->select('s')
                ->innerJoin('s.fixer', 'f')
                ->leftJoin('s.reviews', 'r')
                ->andWhere('f.id = :fixerId')
                ->setParameter('fixerId', $fixerId)
                ->orderBy('s.rating', 'DESC')
                ->groupBy('s.id, f.id')
                ->getQuery()
                ->getResult();
        }
        return $this->createQueryBuilder('s')
            ->select('s.id, s.name, s.slug, s.description, s.rating, f.firstname, f.lastname, f.alias, COUNT(r.id) AS reviews')
            ->innerJoin('s.fixer', 'f')
            ->leftJoin('s.reviews', 'r')
            ->andWhere('f.id = :fixerId')
            ->setParameter('fixerId', $fixerId)
            ->orderBy('s.rating', 'DESC')
            ->addOrderBy('reviews', 'DESC')
            ->groupBy('s.id, f.id')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult();
    }

    public function findServicesDashboard(){

        return $this->createQueryBuilder('s')
            ->select('s.name, s.description, s.rating, f.alias')
            ->innerJoin('s.fixer', 'f')
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_ARRAY);

    }

}
