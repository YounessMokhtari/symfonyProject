<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\RegistrationController;
#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[ApiResource(operations: [
    new GetCollection(),
    new Put(name: 'api_update_Client_with_user', uriTemplate: '/api/client/{id}', controller: RegistrationController::class,
    security: "is_granted('ROLE_ADMIN') or (user == object.getUser())"
),
    new Delete(security: "is_granted('ROLE_ADMIN')"),
    new Post(name: 'api_register_client_with_user', uriTemplate: '/api/clients/register-with-user', controller: RegistrationController::class),
    new Post(security: "is_granted('ROLE_ADMIN')"),
    new Patch(),
],
normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['read', 'write'])]
    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[Groups(['read', 'write'])]
    #[ORM\Column(length: 50)]
    private ?string $prenom = null;

    #[Groups(['read', 'write'])]
    #[ORM\Column(name:"email", length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 50)]
    #[Groups(['read', 'write'])]
    private ?string $telephone = null;

    #[Groups(['read', 'write'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateNaissance = null;

    #[Groups(['read', 'write'])]
    #[ORM\Column(length: 10)]
    private ?string $cin = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    //#[Groups(["book"])]
    #[Groups(['read','write'])]
    private ?User $user = null;
    #[Groups(['read', 'write'])]
    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Commande::class)]
    private Collection $commandes;
    #[Groups(['read', 'write'])]
    #[ORM\Column(nullable: true)]
    private ?float $latitude = null;
    #[Groups(['read', 'write'])]
    #[ORM\Column(nullable: true)]
    private ?float $longitude = null;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Localisation = null;
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

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $dateNaissance): static
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(string $cin): static
    {
        $this->cin = $cin;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->Localisation;
    }

    public function setLocalisation(?string $Localisation): static
    {
        $this->Localisation = $Localisation;

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
            $commande->setClient($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getClient() === $this) {
                $commande->setClient(null);
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
