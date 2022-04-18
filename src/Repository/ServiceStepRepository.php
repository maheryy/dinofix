<?php

namespace App\Repository;

use App\Entity\Service;
use App\Entity\ServiceStep;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ServiceStep|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServiceStep|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServiceStep[]    findAll()
 * @method ServiceStep[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceStepRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServiceStep::class);
    }

    public function findOneByStepValue($value): ?ServiceStep
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.step = :value')
            ->setParameter('value', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findStepsByService(?Service $service): array
    {
        $qb = $this->createQueryBuilder('s')
            ->andWhere('s.service = :service')
            ->andWhere('s.step > :step')
            ->setParameter('service', $service)
            ->setParameter('step', 0);

        if ($service) {
            $qb->andWhere('s.service = :service')
                ->setParameter('service', $service);
        } else {
            $qb->andWhere('s.service IS NULL');
        }

        return $qb->getQuery()->getResult();
    }

    public function countStepsByService(?Service $service): ?int
    {
        $qb = $this->createQueryBuilder('s')
            ->select('COUNT(s.id)')
            ->andWhere('s.step > :step')
            ->setParameter('step', 0);

        if ($service) {
            $qb->andWhere('s.service = :service')
                ->setParameter('service', $service);
        } else {
            $qb->andWhere('s.service IS NULL');
        }

        return $qb->getQuery()->getOneOrNullResult(AbstractQuery::HYDRATE_SINGLE_SCALAR);
    }

    public function findOneStepByService(?Service $service, int $stepValue): ?ServiceStep
    {
        $qb = $this->createQueryBuilder('s')
            ->andWhere('s.step = :step')
            ->setParameter('step', $stepValue);

        if ($service) {
            $qb->andWhere('s.service = :service')
                ->setParameter('service', $service);
        } else {
            $qb->andWhere('s.service IS NULL');
        }

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function findLastStepByService(?Service $service)
    {
        $qb = $this->createQueryBuilder('s')
            ->select('s')
            ->orderBy('s.step', 'DESC')
            ->setMaxResults(1);

        if ($service) {
            $qb->andWhere('s.service = :service')
                ->setParameter('service', $service);
        } else {
            $qb->andWhere('s.service IS NULL');
        }

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function findFirstStepByService(?Service $service)
    {
        $qb = $this->createQueryBuilder('s')
            ->select('s')
            ->where('s.step >= :step')
            ->setParameter('step', 0)
            ->orderBy('s.step', 'ASC')
            ->setMaxResults(1);

        if ($service) {
            $qb->andWhere('s.service = :service')
                ->setParameter('service', $service);
        } else {
            $qb->andWhere('s.service IS NULL');
        }

        return $qb->getQuery()->getOneOrNullResult();
    }
}
