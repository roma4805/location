<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\Contrat;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: "App\Repository\ClientRepository")]
#[ORM\Table(name: "client")]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $lastName = null;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $createdAt;

   // ✅ NOUVEAUX CHAMPS (TEMPORAIRE)
#[ORM\Column(length: 20, unique: true, nullable: true)]
private ?string $cin = null;

#[ORM\Column(length: 20)]
#[Assert\NotBlank]
#[Assert\Regex(
    pattern: "/^[0-9+\s]+$/",
    message: "Numéro de téléphone invalide"
)]
private ?string $phone = null;

    // 🔗 Relation avec User
    #[ORM\OneToOne(inversedBy: 'client', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    // 🔗 Relation avec Contrat
    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Contrat::class)]
    private Collection $contrats;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->contrats = new ArrayCollection();
    }

    // ================= GETTERS / SETTERS =================

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(string $cin): self
    {
        $this->cin = $cin;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getContrats(): Collection
    {
        return $this->contrats;
    }

    public function addContrat(Contrat $contrat): self
    {
        if (!$this->contrats->contains($contrat)) {
            $this->contrats[] = $contrat;
            $contrat->setClient($this);
        }
        return $this;
    }

    public function removeContrat(Contrat $contrat): self
    {
        if ($this->contrats->removeElement($contrat)) {
            if ($contrat->getClient() === $this) {
                $contrat->setClient(null);
            }
        }
        return $this;
    }
}
