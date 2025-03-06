<?php

namespace App\Repository;

use App\Entity\Ordonnance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class OrdonnanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ordonnance::class);
    }

    /**
     * Fetch prescriptions sorted dynamically by the given field and order
     */
    public function findAllSorted(string $sortField, string $order = 'ASC')
    {
        return $this->createQueryBuilder('o')
            ->orderBy('o.' . $sortField, $order)
            ->getQuery();
    }

    /**
     * Search prescriptions by ID
     */
    public function searchById(int $id)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}