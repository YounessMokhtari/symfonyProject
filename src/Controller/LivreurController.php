<?php 

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Livreur;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LivreurRepository;

class LivreurController extends AbstractController
{
    private $entityManager;
    private $livreurRepository;
    
    public function __construct(EntityManagerInterface $entityManager, LivreurRepository $livreurRepository)
    {
        $this->entityManager = $entityManager;
        $this->livreurRepository = $livreurRepository;
    }
    
    #[Route('/livreur/location/update', name: 'livreur_location_update', methods: ['POST'])]
    public function updateLocation(Request $request): JsonResponse
    {
        $latitude = $request->request->get('latitude');
        $longitude = $request->request->get('longitude');
        
        // Mettez à jour les coordonnées du livreur dans la base de données ou effectuez toute autre opération nécessaire
        
        // Récupérez l'ID du livreur depuis la requête ou toute autre source
        $livreurId = 1;
        
        // Récupérez le livreur à partir de l'ID
        $livreur = $this->livreurRepository->find($livreurId);
        
        // Vérifiez si le livreur existe
        if (!$livreur) {
            throw $this->createNotFoundException('Le livreur avec l\'ID '.$livreurId.' n\'existe pas.');
        }
        
        // Mettez à jour les coordonnées du livreur
        $livreur->setLatitude($latitude);
        $livreur->setLongitude($longitude);
        
        // Persistez les changements dans la base de données
        $this->entityManager->flush();
        
        return new JsonResponse(['message' => 'Position du livreur mise à jour avec succès']);
    }
}

