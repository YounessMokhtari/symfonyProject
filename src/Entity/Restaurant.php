<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\RestaurantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: RestaurantRepository::class)]
#[ApiResource(operations: [
    new GetCollection(),
    new Put(security: "is_granted('ROLE_ADMIN')", securityMessage: 'seule l\' administrateur qui modifier des restaurant'),
    new Delete(security: "is_granted('ROLE_ADMIN')", securityMessage: 'seule l\' administrateur qui supprimer des restaurant'),
    new Post(security: "is_granted('ROLE_ADMIN')", securityMessage: 'seule l\' administrateur qui ajoute des restaurant'),
    new Patch(),
])]

class Restaurant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $Heure_Ouverture = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Adresse = null;


    #[ORM\OneToMany(mappedBy: 'restaurant', targetEntity: Plat::class)]
    private Collection $plats;

    #[ORM\OneToMany(mappedBy: 'restaurant', targetEntity: Commande::class)]
    private Collection $commandes;

    public function __construct()
    {
        $this->plats = new ArrayCollection();
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

    public function getHeureOuverture(): ?\DateTimeInterface
    {
        return $this->Heure_Ouverture;
    }

    public function setHeureOuverture(?\DateTimeInterface $Heure_Ouverture): static
    {
        $this->Heure_Ouverture = $Heure_Ouverture;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->Adresse;
    }

    public function setAdresse(?string $Adresse): static
    {
        $this->Adresse = $Adresse;

        return $this;
    }

    /**
     * @return Collection<int, Plat>
     */
    public function getPlats(): Collection
    {
        return $this->plats;
    }

    public function addPlat(Plat $plat): static
    {
        if (!$this->plats->contains($plat)) {
            $this->plats->add($plat);
            $plat->setRestaurant($this);
        }

        return $this;
    }

    public function removePlat(Plat $plat): static
    {
        if ($this->plats->removeElement($plat)) {
            // set the owning side to null (unless already changed)
            if ($plat->getRestaurant() === $this) {
                $plat->setRestaurant(null);
            }
        }

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
            $commande->setRestaurant($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getRestaurant() === $this) {
                $commande->setRestaurant(null);
            }
        }

        return $this;
    }
}
