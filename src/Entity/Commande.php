<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CommandeRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\CommandeController;
#[ORM\Entity(repositoryClass: CommandeRepository::class)]
//#[ApiResource]
#[ApiResource(operations: [
    new Post(name: 'api_register_Commande', uriTemplate: '/Commandes/register', controller: CommandeController::class),
    new Get(name: 'api_livreur_Commande', uriTemplate: '/Commandes/getCommande/livreur', controller: CommandeController::class),
    new Post(name: 'api_Client_Commande', uriTemplate: '/Commandes/Notifications/Client', controller: CommandeController::class),
    new Post(name: 'api_Client_CommandePrix', uriTemplate: '/Commandes/getCommande/Client/PrixTotal', controller: CommandeController::class),
    new Post(name: 'api_Livreur_accepter', uriTemplate: '/Commandes/Livreur/Accepter', controller: CommandeController::class),
   // new Post(name: 'api_Livreur_refuser', uriTemplate: '/Commandes/Livreur/Refuser', controller: CommandeController::class),
    new Post(name: 'api_Client_Plat', uriTemplate: '/Commandes/Client/PlatRe', controller: CommandeController::class),
    new Post(name: 'api_Client_Plat2', uriTemplate: '/Commandes/Client/Panier', controller: CommandeController::class),
    new Post(name: 'api_Client_Plat3', uriTemplate: '/Commandes/Client/Modifier_Plat', controller: CommandeController::class),
    new Post(name: 'api_Client_Plat4', uriTemplate: '/Commandes/Client/Suppimer_Plat', controller: CommandeController::class),
    new Post(name: 'api_Client_C', uriTemplate: '/Commandes/Client/Confirmer', controller: CommandeController::class),
],
)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Etat = null;

   #[ORM\Column(nullable: true)]
    private ?int $Prix_Total = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?Plat $platt = null;

    #[ORM\Column(nullable: true)]
    private ?int $Nombre = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?Client $client = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?Livreur $livreur = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?Restaurant $restaurant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCommande(): ?string
    {
        return $this->nomCommande;
    }

    public function setNomCommande(string $nomCommande): static
    {
        $this->nomCommande = $nomCommande;

        return $this;
    }

   public function getNombre(): ?int
    {
        return $this->Nombre;
    }

    public function setNombre(?int $Nombre): static
    {
        $this->Nombre = $Nombre;

        return $this;
    }

    public function getPrixTotal(): ?int
    {
        return $this->Prix_Total;
    }

    public function setPrixTotal(?int $Prix_Total): static
    {
        $this->Prix_Total = $Prix_Total;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->Etat;
    }

    public function setEtat(?string $Etat): static
    {
        $this->Etat = $Etat;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getLivreur(): ?Livreur
    {
        return $this->livreur;
    }

    public function setLivreur(?Livreur $livreur): static
    {
        $this->livreur = $livreur;

        return $this;
    }

    public function getRestaurant(): ?Restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(?Restaurant $restaurant): static
    {
        $this->restaurant = $restaurant;

        return $this;
    }
}
