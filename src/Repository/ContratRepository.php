<?php
// src/Repository/ContratRepository.php
namespace App\Repository;

use App\Entity\Contrat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ContratRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contrat::class);
    }

    /**
     * Trouver les contrats d'un client
     */
    public function findByClient($client)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.client = :client')
            ->setParameter('client', $client)
            ->orderBy('c.dateDebut', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Vérifier si une voiture est disponible pour une période donnée
     */
    public function findConflictsForVoiture($voiture, \DateTimeInterface $debut, \DateTimeInterface $fin)
{
    return $this->createQueryBuilder('c')
        ->andWhere('c.voiture = :voiture')
        ->andWhere('c.statut IN (:statuts)')
        ->andWhere('(c.dateDebut <= :fin AND c.dateFin >= :debut)')
        ->setParameter('voiture', $voiture)
        ->setParameter('debut', $debut)
        ->setParameter('fin', $fin)
        ->setParameter('statuts', ['En attente', 'Confirmé'])
        ->getQuery()
        ->getResult();
}

}
