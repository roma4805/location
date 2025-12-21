<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: "App\Repository\ClientRepository")]
#[ORM\Table(name: "client")]
class Client implements UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private ?int $id = null;

    #[ORM\Column(type:"string", length:180, unique:true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column(type:"json")]
    private array $roles = [];

    #[ORM\Column(type:"string")]
    #[Assert\NotBlank]
    private ?string $password = null;

    #[ORM\Column(type:"string", length:255)]
    #[Assert\NotBlank]
    private ?string $firstName = null;

    #[ORM\Column(type:"string", length:255)]
    #[Assert\NotBlank]
    private ?string $lastName = null;

    #[ORM\Column(type:"datetime")]
    private \DateTimeInterface $createdAt;
#[ORM\OneToOne(inversedBy: 'client', cascade: ['persist', 'remove'])]
#[ORM\JoinColumn(nullable: false)]
private ?User $user = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->roles = ['ROLE_CLIENT'];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_CLIENT';
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
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

    public function eraseCredentials(): void
{
    // Si tu stockes des données sensibles temporaires, les supprimer ici
}


    public function getUserIdentifier(): string
    {
        return $this->email;
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
}
