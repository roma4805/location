<?php
// src/Repository/VoitureRepository.php
namespace App\Repository;

use App\Entity\Voiture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class VoitureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Voiture::class);
    }

    /**
     * Trouver toutes les voitures disponibles sur une période donnée
     */
    public function findDisponibles(\DateTimeInterface $debut, \DateTimeInterface $fin)
    {
        $qb = $this->createQueryBuilder('v')
            ->leftJoin('v.contrats', 'c')
            ->where('v.statut = :disponible')
            ->setParameter('disponible', 'Disponible')
            ->andWhere('c.date_debut IS NULL OR c.date_fin < :debut OR c.date_debut > :fin')
            ->setParameter('debut', $debut)
            ->setParameter('fin', $fin);

        return $qb->getQuery()->getResult();
    }
}
