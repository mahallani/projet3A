<?php

namespace App\Repository;

use App\Entity\Traitement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TraitementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Traitement::class);
    }

    /**
     * Fetch traitements sorted dynamically by the given field and order
     */
    public function findAllSorted(string $sortField, string $order = 'ASC')
    {
        return $this->createQueryBuilder('t')
            ->orderBy("t.$sortField", $order)
            ->getQuery();
    }

    /**
     * Search traitements by ID
     */
    public function searchById(int $id)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}