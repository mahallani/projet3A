<?php

namespace App\Repository;

use App\Entity\RendezVous;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RendezVous>
 */
class RendezVousRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RendezVous::class);
    }
    public function findRendezVousForTomorrow(): array
    {
        $demain = new \DateTime('+1 day');  // Calculer la date de demain
        $demainStart = $demain->setTime(0, 0, 0); // Début de la journée de demain (00:00:00)
        $demainEnd = $demain->setTime(23, 59, 59); // Fin de la journée de demain (23:59:59)

        return $this->createQueryBuilder('r')
            ->andWhere('r.jour BETWEEN :start AND :end')
            ->setParameter('start', $demainStart)
            ->setParameter('end', $demainEnd)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return RendezVous[] Returns an array of RendezVous objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?RendezVous
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
