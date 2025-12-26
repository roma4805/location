<?php
// src/Entity/Voiture.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\VoitureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Contrat;
#[ORM\Entity(repositoryClass: VoitureRepository::class)]
class Voiture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 50)]
    private string $marque;

    #[ORM\Column(type: "string", length: 50)]
    private string $modele;

    #[ORM\Column(type: "string", length: 20, unique: true)]
    private string $immatriculation;

    #[ORM\Column(type: "decimal", precision: 10, scale: 2)]
    private float $prix_journalier;

    #[ORM\Column(type: "string", length: 20)]
    private string $statut = 'Disponible'; // Disponible, Louée, Maintenance

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $image = null;
// 🔹 Nouvelle relation OneToMany vers Contrat
    #[ORM\OneToMany(mappedBy: "voiture", targetEntity: Contrat::class, cascade: ["remove"])]
    private Collection $contrats;

    public function __construct()
    {
        $this->contrats = new ArrayCollection();
    }
    // Getters & Setters

    public function getId(): ?int { return $this->id; }

    public function getMarque(): string { return $this->marque; }
    public function setMarque(string $marque): self { $this->marque = $marque; return $this; }

    public function getModele(): string { return $this->modele; }
    public function setModele(string $modele): self { $this->modele = $modele; return $this; }

    public function getImmatriculation(): string { return $this->immatriculation; }
    public function setImmatriculation(string $immatriculation): self { $this->immatriculation = $immatriculation; return $this; }

    public function getPrixJournalier(): float { return $this->prix_journalier; }
    public function setPrixJournalier(float $prix_journalier): self { $this->prix_journalier = $prix_journalier; return $this; }

    public function getStatut(): string { return $this->statut; }
    public function setStatut(string $statut): self { $this->statut = $statut; return $this; }

    public function getImage(): ?string { return $this->image; }
    public function setImage(?string $image): self { $this->image = $image; return $this; }
    public function getContrats(): Collection
    {
        return $this->contrats;
    }
}
