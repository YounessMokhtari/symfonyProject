<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\LivreurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\RegistrationController;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use App\Entity\User;

#[ORM\Entity(repositoryClass: LivreurRepository::class)]
#[ApiResource(operations: [
    new Put(),
    new Put(name: 'api_update_livreur_with_user', uriTemplate: '/api/livreur/{id}', controller: RegistrationController::class),
    new Delete(),
    new Get(),
    new GetCollection(),
    new Post(name: 'api_register_livreur_with_user', uriTemplate: '/api/livreurs/register-with-user', controller: RegistrationController::class),
    new Patch()

],normalizationContext: ['groups' => ['read']],
denormalizationContext: ['groups' => ['write']],
)]
class Livreur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['read', 'write'])]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    #[Groups(['read', 'write'])]
    private ?string $prenom = null;

    #[ORM\Column(length: 50)]
    #[Groups(['read', 'write'])]
    private ?string $email = null;

    #[ORM\Column(length: 50)]
    #[Groups(['read', 'write'])]
    private ?string $telephone = null;

    #[ORM\Column(length: 10, nullable: true)]
    #[Groups(['read', 'write'])]
    private ?string $cin = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[Groups(['read', 'write'])]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'livreur', targetEntity: Commande::class)]
    #[Groups(['read', 'write'])]
    private Collection $commandes;

    #[ORM\Column(nullable: true)]
    #[Groups(['read', 'write'])]
    private ?float $latitude = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['read', 'write'])]
    private ?float $longitude = null;

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(?string $cin): static
    {
        $this->cin = $cin;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): static
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->setLivreur($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getLivreur() === $this) {
                $commande->setLivreur(null);
            }
        }

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }
}
