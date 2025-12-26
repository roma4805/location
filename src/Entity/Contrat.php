<?php
// src/Entity/Contrat.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ContratRepository;

#[ORM\Entity(repositoryClass: ContratRepository::class)]
class Contrat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: "contrats")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    #[ORM\ManyToOne(targetEntity: Voiture::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Voiture $voiture = null;

    #[ORM\Column(type:"datetime")]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type:"datetime")]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column(type:"decimal", precision:10, scale:2)]
    private ?float $prixTotal = null;

    #[ORM\Column(type:"string", length:20)]
    private string $statut = 'En attente';

    // ------------------------
    // Getters / Setters
    // ------------------------

    public function getId(): ?int { return $this->id; }

    public function getClient(): ?Client { return $this->client; }
public function setClient(?Client $client): self
{
    $this->client = $client;
    return $this;
}


    public function getVoiture(): ?Voiture { return $this->voiture; }
    public function setVoiture(Voiture $voiture): self { $this->voiture = $voiture; return $this; }

    public function getDateDebut(): ?\DateTimeInterface { return $this->dateDebut; }
    public function setDateDebut(\DateTimeInterface $dateDebut): self { $this->dateDebut = $dateDebut; return $this; }

    public function getDateFin(): ?\DateTimeInterface { return $this->dateFin; }
    public function setDateFin(\DateTimeInterface $dateFin): self { $this->dateFin = $dateFin; return $this; }

    public function getPrixTotal(): ?float { return $this->prixTotal; }
    public function setPrixTotal(float $prixTotal): self { $this->prixTotal = $prixTotal; return $this; }

    public function getStatut(): string { return $this->statut; }
    public function setStatut(string $statut): self { $this->statut = $statut; return $this; }

    // ------------------------
    // Méthodes calculs
    // ------------------------

   public function getDureeEnJours(): int
{
    return $this->dateFin->diff($this->dateDebut)->days;
}

public function calculerPrixTotal(float $prixJournalier): float
{
    $jours = $this->getDureeEnJours();
    $prix = $jours * $prixJournalier;

    // 🎁 remise si plus de 30 jours
    if ($jours > 30) {
        $prix *= 0.9; // -10%
    }

    return $prix;
}

public function getReductionMessage(): ?string
{
    if ($this->getDureeEnJours() > 30) {
        return '🎉 Remise de 10% appliquée (réservation longue durée)';
    }
    return null;
}

}
