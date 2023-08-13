<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommandeRepository;
use App\Repository\ClientRepository;
use App\Repository\LivreurRepository;
use App\Repository\PlatRepository;
use App\Repository\RestaurantRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Commande;
use Symfony\Component\HttpFoundation\JsonResponse;

class CommandeController extends AbstractController
{
    private $entityManager;
    private $commandeRepository;
    private $clientRepository;
    private $restaurantRepository;
    private $livreurRepository;
    private $platRepository;
    public function __construct(EntityManagerInterface $entityManager, ClientRepository $clientRepository, CommandeRepository $commandeRepository, RestaurantRepository $restaurantRepository, LivreurRepository $livreurRepository, PlatRepository $platRepository)
    //public function __construct(EntityManagerInterface $entityManager, Client1Repository $clientRepository, UserPasswordEncoderInterface $passwordEncoder)
        {
            $this->entityManager = $entityManager;
            $this->commandeRepository = $commandeRepository;
            $this->clientRepository = $clientRepository;
            $this->restaurantRepository = $restaurantRepository;
            $this->livreurRepository = $livreurRepository;
            $this->platRepository = $platRepository;
        }






    #[Route('/Commandes/register', name: 'api_register_Commande')]
    public function registrer(Request $request): Response
    {
        $requestData = json_decode($request->getContent(), true);
        $clientId = $requestData['client'] ?? null;
        $client = $this->clientRepository->findOneBy(['id' => $clientId]);
        //$livreurID = $requestData['livreur'] ?? null; 
        //$livreur = $this->livreurRepository->findOneBy(['id' => $livreurID]);
        $PlatId = $requestData['platt'] ?? null;
        $plat = $this->platRepository->findOneBy(['id' => $PlatId]);
        $platprix = $plat->getPrix();
        $restaurantId = $plat->getRestaurant()->getId();
        //$prixTotal = $requestData['prixTotal'] ?? null;
        //$Etat = $requestData['etat'] ?? null;
        $Etat = 'en attente_confirme';
        $nombre = $requestData['nombre'] ?? null;
        $totalprix = $platprix*$nombre;
        //$restaurantId = $requestData['restaurantt'] ?? null;
        $restaurant = $this->restaurantRepository->findOneBy(['id' => $restaurantId]);
        //$nomRestaurant = $restaurant->getNomR();

        $commande = new Commande();
       // $commande->setNomC($NomC);
        // You may need to encrypt the password before setting it in the entity, depending on your entity configuration and password encoding logic
        $commande->setPlatt($plat);
        $commande->setPrixTotal($totalprix);
        $commande->setClient($client);
        $commande->setLivreur(null);
        $commande->setEtat($Etat);
        $commande->setNombre($nombre);
        $commande->setRestaurantt($restaurant);
        $this->entityManager->persist($commande);
        $this->entityManager->flush();



      
        $responseData = [
            'message' => 'enregistrer',
            'status' => $totalprix
            //'status' => 'felicite'
        ];
        return $this->json($responseData, Response::HTTP_BAD_REQUEST);
       //return new Response('Commande passée avec succès !');
    }









    #[Route('/Commandes/getCommande/livreur', name: 'api_livreur_Commande')]
    public function getToLivreur(EntityManagerInterface $entityManager): Response
    {
         // Replace 'specificColumn1', 'specificColumn2', etc. with the actual column names you want to retrieve.
    $specificColumns = ['id','Etat'];

    // Fetch all commands with the specific columns using Doctrine's QueryBuilder.
    $queryBuilder = $entityManager->createQueryBuilder();
    $queryBuilder
   /*->select('c.' . implode(', c.', $specificColumns), 'Client.Email as clientEmail', 'Client.Nom as clientNom', 'Client.Tel as clientTel', 'Client.Localisation as clientLocalisation', 'Restaurant.NomR as restaurantNomR', 'Restaurant.Adresse as restaurantAddr', 'Plat.NomP as platnom') // Use 'client.Email' as an alias for the client's email.
        ->from(Commande::class, 'c')
        ->leftJoin('c.client', 'Client', 'WITH', 'c.client = Client.id') // Join Client entity using the common field 'client' in Commande and 'id' in Client.
        ->leftJoin('c.restaurantt', 'Restaurant', 'WITH', 'c.restaurantt = Restaurant.id') // Join with the Restaurant entity using the common field 'restaurantt' in Commande and 'id' in Restaurant.
        ->leftJoin('c.platt', 'Plat', 'WITH', 'c.platt = Plat.id')
        ->andWhere('c.Etat = :et')
        ->setParameter('et', 'en cours')
        ->groupBy('Restaurant.NomR');*/


        ->select('c.' . implode(', c.', $specificColumns), 'Client.email as clientEmail', 'Client.nom as clientNom', 'Client.telephone as clientTel', 'Client.Localisation as clientLocalisation', 'Restaurant.nom as restaurantNomR', 'Restaurant.Adresse as restaurantAddr', 'Plat.nom as platnom') // Use 'client.Email' as an alias for the client's email.
        ->from(Commande::class, 'c')
        ->leftJoin('c.client', 'Client', 'WITH', 'c.client = Client.id')
        ->leftJoin('c.restaurantt', 'Restaurant', 'WITH', 'c.restaurantt = Restaurant.id')
        ->leftJoin('c.platt', 'Plat', 'WITH', 'c.platt = Plat.id')
        ->andWhere('c.Etat = :et')
        ->setParameter('et', 'en cours')
        ->groupBy('Client.id', 'Restaurant.id');
       /* $queryBuilder
   /* $queryBuilder
        ->select('c.' . implode(', c.', $specificColumns))
        ->from(Commande::class, 'c'); // Replace 'Commande' with your entity class name*/
    $commands = $queryBuilder->getQuery()->getResult();

    // You may want to convert the result into an array for better control over the response.
    $formattedCommands = [];
    foreach ($commands as $command) {
        $formattedCommands[] = [
// Access 'NomPlat' directly from the $command array.
        // Access 'clientEmail' alias used in the select statement.
            'ClientNom' => $command['clientNom'], 
            'Id' => $command['id'],
            'Etat' => $command['Etat'],
            'ClientTel' => $command['clientTel'], 
            'ClientLocalisation' => $command['clientLocalisation'], 
            'Restaurant' => $command['restaurantNomR'], 
            'RestaurantAddr' => $command['restaurantAddr'], 
        ];
    }

    // Convert the result into a JSON response and return it.
    return new JsonResponse($formattedCommands);
    
    }







    #[Route('/Commandes/Notifications/Client', name: 'api_Client_Commande')]
    public function getToClient(EntityManagerInterface $entityManager,Request $request): Response
    {
        $requestData = json_decode($request->getContent(), true);
        // $NomC = $requestData['NomC'] ?? null;
         $clientId = $requestData['Id'] ?? null;
       // $clientId = YOUR_CLIENT_ID;
         // Replace 'specificColumn1', 'specificColumn2', etc. with the actual column names you want to retrieve.
    $specificColumns = ['id','Etat'/*,'Nombre','Prix_Total'*/];

    // Fetch all commands with the specific columns using Doctrine's QueryBuilder.
    $queryBuilder = $entityManager->createQueryBuilder();
    $queryBuilder
    ->select('c.' . implode(', c.', $specificColumns)/*, 'Client.Email as clientEmail', 'Client.id as clientId', 'Client.Nom as clientNom', 'Client.Tel as clientTel', 'Client.Localisation as clientLocalisation'*/, 'Restaurant.id as restaurantId', 'Restaurant.nom as restaurantNomR', 'Restaurant.Adresse as restaurantAddr'/*, 'Plat.NomP as platnom', 'Plat.Prix as platprix'*/ ) // Use 'client.Email' as an alias for the client's email.
    ->from(Commande::class, 'c')
    ->leftJoin('c.client', 'Client', 'WITH', 'c.client = Client.id')
    ->leftJoin('c.platt', 'Plat', 'WITH', 'c.platt = Plat.id')
    ->leftJoin('Plat.restaurant', 'Restaurant', 'WITH', 'Plat.restaurant = Restaurant.id')
    ->andWhere('Client.id = :clientId')
    ->andWhere($queryBuilder->expr()->orX(
        $queryBuilder->expr()->eq('c.Etat', ':etEnCours'),
        $queryBuilder->expr()->eq('c.Etat', ':etAccepte')
    ))
    ->setParameter('clientId', $clientId)
    ->setParameter('etEnCours', 'en cours')
    ->setParameter('etAccepte', 'accepter')
    ->groupBy('Restaurant.NomR');
   /* $queryBuilder
        ->select('c.' . implode(', c.', $specificColumns))
        ->from(Commande::class, 'c'); // Replace 'Commande' with your entity class name*/
    $commands = $queryBuilder->getQuery()->getResult();
    
    // You may want to convert the result into an array for better control over the response.
    $formattedCommands = [];
    foreach ($commands as $command) {
        //$prixtotal = $command['Nombre']*$command['platprix'];
        $formattedCommands[] = [
           // 'NomPlat' => $command['platnom'], // Access 'NomPlat' directly from the $command array.
        // Access 'clientEmail' alias used in the select statement.
            'Etat' => $command['Etat'],
            'Restaurant' => $command['restaurantNomR'], 
            'RestaurantAddr' => $command['restaurantAddr'], 
            'RestaurantId' => $command['restaurantId'], 
            //'prixtotal' => $command['Prix_Total'], 
        ];
    }

    // Convert the result into a JSON response and return it.
    return new JsonResponse($formattedCommands);
    //return new JsonResponse($commands);
    
    }

    #[Route('/Commandes/getCommande/Client/PrixTotal', name: 'api_Client_CommandePrix')]
    public function getToClientPrixTotal(EntityManagerInterface $entityManager,Request $request): Response
    {
        $requestData = json_decode($request->getContent(), true);
        // $NomC = $requestData['NomC'] ?? null;
         $clientId = $requestData['Id'] ?? null;
       // $clientId = YOUR_CLIENT_ID;
         // Replace 'specificColumn1', 'specificColumn2', etc. with the actual column names you want to retrieve.
    $specificColumns = ['id','Etat','Platt'];

    // Fetch all commands with the specific columns using Doctrine's QueryBuilder.
    $queryBuilder = $entityManager->createQueryBuilder();
    $queryBuilder
    ->select('SUM(c.Prix_Total) as totalPrice', 'Client.Email as clientEmail', 'Client.id as clientId', 'Client.Nom as clientNom', 'Client.Tel as clientTel', 'Client.Localisation as clientLocalisation', 'Restaurant.NomR as restaurantNomR', 'Restaurant.Adresse as restaurantAddr')
    ->from(Commande::class, 'c')
    ->leftJoin('c.client', 'Client', 'WITH', 'c.client = Client.id') // Join Client entity using the common field 'client' in Commande and 'id' in Client.
    ->leftJoin('c.restaurantt', 'Restaurant', 'WITH', 'c.restaurantt = Restaurant.id') // Join with the Restaurant entity using the common field 'restaurantt' in Commande and 'id' in Restaurant.
    ->andWhere('Client.id = :clientId')
    ->andWhere($queryBuilder->expr()->orX(
        $queryBuilder->expr()->eq('c.Etat', ':etEnCours'),
        $queryBuilder->expr()->eq('c.Etat', ':etAccepte')
    ))
    ->setParameter('clientId', $clientId)
    ->setParameter('etEnCours', 'en cours')
    ->setParameter('etAccepte', 'accepter');
   /* $queryBuilder
        ->select('c.' . implode(', c.', $specificColumns))
        ->from(Commande::class, 'c'); // Replace 'Commande' with your entity class name*/
    $commands = $queryBuilder->getQuery()->getResult();

    // You may want to convert the result into an array for better control over the response.
    $formattedCommands = [];
    foreach ($commands as $command) {
        $formattedCommands[] = [
            'Prix_Total' => $command['totalPrice'], 
        ];
    }

    // Convert the result into a JSON response and return it.
    return new JsonResponse($formattedCommands);
    //return new JsonResponse($commands);
    
    }



    #[Route('/Commandes/Livreur/Accepter', name: 'api_Livreur_accepter')]
    public function Accepter(EntityManagerInterface $entityManager,Request $request): Response
    {
        $requestData = json_decode($request->getContent(), true);
        // $NomC = $requestData['NomC'] ?? null;
        // $clientId = $requestData['Id'] ?? null;
         $ComId = $requestData['Idd'] ?? null;
       // $clientId = YOUR_CLIENT_ID;
         // Replace 'specificColumn1', 'specificColumn2', etc. with the actual column names you want to retrieve.
    $specificColumns = ['id','Etat','Platt'];

    // Fetch all commands with the specific columns using Doctrine's QueryBuilder.
    $queryBuilder = $entityManager->createQueryBuilder();
    $queryBuilder
    ->update(Commande::class, 'c')
    ->set('c.Etat', ':etatValue') // Set the 'Etat' field to the parameter :etatValue.
   // ->where('c.client = :clientId') // Add a condition to filter rows for a specific client ID.
    ->where('c.id = :ComId') 
    ->setParameter('etatValue', 'accepter') // Set the value to be updated for 'Etat'.
    //->setParameter('clientId', $clientId)
    ->setParameter('ComId', $ComId);
     // Set the value for the 'clientId' parameter.

// Execute the update.
$query = $queryBuilder->getQuery();
$query->execute();
    //return new JsonResponse($commands);
    return new Response('Update avec succès !');
    
    }




    #[Route('/Commandes/Livreur/Refuser', name: 'api_Livreur_refuser')]
    public function Refuser(EntityManagerInterface $entityManager,Request $request): Response
    {
        $requestData = json_decode($request->getContent(), true);
        // $NomC = $requestData['NomC'] ?? null;
         $clientId = $requestData['Id'] ?? null;
       // $clientId = YOUR_CLIENT_ID;
         // Replace 'specificColumn1', 'specificColumn2', etc. with the actual column names you want to retrieve.
    $specificColumns = ['id','Etat','Platt'];

    // Fetch all commands with the specific columns using Doctrine's QueryBuilder.
    $queryBuilder = $entityManager->createQueryBuilder();
    $queryBuilder
    ->update(Commande::class, 'c')
    ->set('c.Etat', ':etatValue') // Set the 'Etat' field to the parameter :etatValue.
    ->where('c.client = :clientId') // Add a condition to filter rows for a specific client ID.
    ->setParameter('etatValue', 'refuser') // Set the value to be updated for 'Etat'.
    ->setParameter('clientId', $clientId); // Set the value for the 'clientId' parameter.

// Execute the update.
$query = $queryBuilder->getQuery();
$query->execute();
    //return new JsonResponse($commands);
    return new Response('Update avec succès !');
    
    }




    #[Route('/Commandes/Client/PlatRe', name: 'api_Client_Plat')]
    public function getToClientPlats(EntityManagerInterface $entityManager,Request $request): Response
    {
        $requestData = json_decode($request->getContent(), true);
        // $NomC = $requestData['NomC'] ?? null;
         $restaurantId = $requestData['Idd'] ?? null;
         $clientId = $requestData['Id'] ?? null;
       // $clientId = YOUR_CLIENT_ID;
         // Replace 'specificColumn1', 'specificColumn2', etc. with the actual column names you want to retrieve.
    $specificColumns = ['id','Etat','Nombre','Prix_Total'];

    // Fetch all commands with the specific columns using Doctrine's QueryBuilder.
    $queryBuilder = $entityManager->createQueryBuilder();
    $queryBuilder
    ->select('c.' . implode(', c.', $specificColumns)/*, 'Client.Email as clientEmail', 'Client.id as clientId', 'Client.Nom as clientNom', 'Client.Tel as clientTel', 'Client.Localisation as clientLocalisation', 'Restaurant.id as restaurantId', 'Restaurant.NomR as restaurantNomR', 'Restaurant.Adresse as restaurantAddr'*/, 'Plat.nom as platnom'/*, 'Plat.Prix as platprix'*/ ) // Use 'client.Email' as an alias for the client's email.
    ->from(Commande::class, 'c')
    ->leftJoin('c.client', 'Client', 'WITH', 'c.client = Client.id')
    ->leftJoin('c.platt', 'Plat', 'WITH', 'c.platt = Plat.id')
    ->leftJoin('Plat.restaurant', 'Restaurant', 'WITH', 'Plat.restaurant = Restaurant.id')
    ->andWhere('Client.id = :clientId')
->andWhere('Restaurant.id = :clientRes') // Adding the condition for the "res" column.
->andWhere($queryBuilder->expr()->orX(
    $queryBuilder->expr()->eq('c.Etat', ':etEnCours'),
    $queryBuilder->expr()->eq('c.Etat', ':etAccepte')
))
->setParameter('clientId', $clientId)
->setParameter('etEnCours', 'en cours')
->setParameter('etAccepte', 'accepter')
->setParameter('clientRes', $restaurantId);
   /* $queryBuilder
        ->select('c.' . implode(', c.', $specificColumns))
        ->from(Commande::class, 'c'); // Replace 'Commande' with your entity class name*/
    $commands = $queryBuilder->getQuery()->getResult();
    
    // You may want to convert the result into an array for better control over the response.
    $formattedCommands = [];
    foreach ($commands as $command) {
        //$prixtotal = $command['Nombre']*$command['platprix'];
        $formattedCommands[] = [
           // 'NomPlat' => $command['platnom'], // Access 'NomPlat' directly from the $command array.
        // Access 'clientEmail' alias used in the select statement.
            
            'Plat' => $command['platnom'], 
            'Nombre' => $command['Nombre'], 
            'PrixTotal' => $command['Prix_Total'], 
            'Etat' => $command['Etat'],
            
            //'prixtotal' => $command['Prix_Total'], 
        ];
    }

    // Convert the result into a JSON response and return it.
    return new JsonResponse($formattedCommands);
    //return new JsonResponse($commands);
    
    }

    #[Route('/Commandes/Client/Panier', name: 'api_Client_Plat2')]
    public function getToClientPanier(EntityManagerInterface $entityManager,Request $request): Response
    {
        $requestData = json_decode($request->getContent(), true);
        // $NomC = $requestData['NomC'] ?? null;
         $clientId = $requestData['Id'] ?? null;
       // $clientId = YOUR_CLIENT_ID;
         // Replace 'specificColumn1', 'specificColumn2', etc. with the actual column names you want to retrieve.
    $specificColumns = ['id','Etat','Nombre','Prix_Total'];

    // Fetch all commands with the specific columns using Doctrine's QueryBuilder.
    $queryBuilder = $entityManager->createQueryBuilder();
    $queryBuilder
    ->select('c.' . implode(', c.', $specificColumns)/*, 'Client.Email as clientEmail', 'Client.id as clientId', 'Client.Nom as clientNom', 'Client.Tel as clientTel', 'Client.Localisation as clientLocalisation', 'Restaurant.id as restaurantId'*/, 'Restaurant.nom as restaurantNomR'/*, 'Restaurant.Adresse as restaurantAddr'*/, 'Plat.nom as platnom'/*, 'Plat.Prix as platprix'*/ ) // Use 'client.Email' as an alias for the client's email.
    ->from(Commande::class, 'c')
    ->leftJoin('c.client', 'Client', 'WITH', 'c.client = Client.id')
    ->leftJoin('c.platt', 'Plat', 'WITH', 'c.platt = Plat.id')
    ->leftJoin('Plat.restaurant', 'Restaurant', 'WITH', 'Plat.restaurant = Restaurant.id')
    ->andWhere('Client.id = :clientId')
    ->andWhere('C.Etat = :ett')
/*->andWhere($queryBuilder->expr()->orX(
    $queryBuilder->expr()->eq('c.Etat', ':etEnCours'),
    $queryBuilder->expr()->eq('c.Etat', ':etAccepte')
))*/
->setParameter('clientId', $clientId)
->setParameter('ett', 'en attente_confirme');
//->setParameter('etAccepte', 'accepter');
   /* $queryBuilder
        ->select('c.' . implode(', c.', $specificColumns))
        ->from(Commande::class, 'c'); // Replace 'Commande' with your entity class name*/
    $commands = $queryBuilder->getQuery()->getResult();
    
    // You may want to convert the result into an array for better control over the response.
    $formattedCommands = [];
    foreach ($commands as $command) {
        //$prixtotal = $command['Nombre']*$command['platprix'];
        $formattedCommands[] = [
           // 'NomPlat' => $command['platnom'], // Access 'NomPlat' directly from the $command array.
        // Access 'clientEmail' alias used in the select statement.
        'Id' => $command['id'],
            'Plat' => $command['platnom'], 
            'Nombre' => $command['Nombre'], 
           // 'PrixTotal' => $command['Prix_Total'], 
            'Etat' => $command['Etat'],
            'Restaurant' => $command['restaurantNomR'],
            
            //'prixtotal' => $command['Prix_Total'], 
        ];
    }

    // Convert the result into a JSON response and return it.
    return new JsonResponse($formattedCommands);
    //return new JsonResponse($commands);
    
    }

    #[Route('/Commandes/Client/Modifier_Plat', name: 'api_Client_Plat3')]
    public function getToClientModif(EntityManagerInterface $entityManager,Request $request): Response
    {
        $requestData = json_decode($request->getContent(), true);
        // $NomC = $requestData['NomC'] ?? null;
         $CommandeId = $requestData['Id'] ?? null;
         $Nombre = $requestData['nombre'] ?? null;


         $specificColumns1= ['id','Etat','Nombre','Prix_Total'];


         $queryBuilder = $entityManager->createQueryBuilder();
         $queryBuilder
         ->select('c.' . implode(', c.', $specificColumns1), 'Plat.Prix as platPrix') // Use 'client.Email' as an alias for the client's email.
         ->from(Commande::class, 'c')
         ->leftJoin('c.client', 'Client', 'WITH', 'c.client = Client.id')
         ->leftJoin('c.restaurantt', 'Restaurant', 'WITH', 'c.restaurantt = Restaurant.id')
         ->leftJoin('c.platt', 'Plat', 'WITH', 'c.platt = Plat.id')
         ->andWhere('c.id = :et')
         ->setParameter('et', $CommandeId);


         $commands = $queryBuilder->getQuery()->getResult();
    
         // You may want to convert the result into an array for better control over the response.
         $formattedCommands = [];
         foreach ($commands as $command) {
             $formattedCommands[] = [
                 // Accédez à l'alias correct dans le tableau $command
                 'platPrix' => $command['platPrix'], 
                 $p = $command['platPrix'],
                 // ... autres clés que vous souhaitez ajouter
             ];
         }
       // $clientId = YOUR_CLIENT_ID;
         // Replace 'specificColumn1', 'specificColumn2', etc. with the actual column names you want to retrieve.
    

    // Fetch all commands with the specific columns using Doctrine's QueryBuilder.






    //return new JsonResponse($formattedCommands);








   $queryBuilder = $entityManager->createQueryBuilder();
    $queryBuilder
    ->update(Commande::class, 'c')
    ->set('c.Nombre', ':newValue') 
    ->set('c.Prix_Total', ':newValue3') 
    ->andWhere('c.id = :CommandeId')
->setParameter('CommandeId', $CommandeId)
->setParameter('newValue', $Nombre)
->setParameter('newValue3', $p*$Nombre)
        ->getQuery()
        ->execute();












    
    // You may want to convert the result into an array for better control over the response.
   

    // Convert the result into a JSON response and return it.

    //return new JsonResponse($commands);
    $responseData = [
        'message' => 'modifier',
        'status' => $p
        //'status' => 'felicite'
    ];
    return $this->json($responseData, Response::HTTP_BAD_REQUEST);
    
    }

    #[Route('/Commandes/Client/Supprimer_Plat', name: 'api_Client_Plat')]
    public function getToClientSupp(EntityManagerInterface $entityManager,Request $request): Response
    {
        $requestData = json_decode($request->getContent(), true);
        // $NomC = $requestData['NomC'] ?? null;
         $CommandeId = $requestData['Id'] ?? null;
        // $ClientId = $requestData['Id'] ?? null;
       // $clientId = YOUR_CLIENT_ID;
         // Replace 'specificColumn1', 'specificColumn2', etc. with the actual column names you want to retrieve.
    

    // Fetch all commands with the specific columns using Doctrine's QueryBuilder.
    $queryBuilder = $entityManager->createQueryBuilder();
    $queryBuilder
    ->delete(Commande::class, 'c')
    ->andWhere('c.id = :et') 
   // ->andWhere('c.client = :et2') 
    // Utilisez les critères appropriés pour identifier les enregistrements à supprimer
    ->setParameter('et', $CommandeId) // Assurez-vous de fournir la valeur appropriée de CommandeId
   // ->setParameter('et2', $ClientId) 
    ->getQuery()
    ->execute(); // Exécute
    // You may want to convert the result into an array for better control over the response.
    $responseData = [
        'message' => 'supprimer',
        'status' => 'bravo'
        //'status' => 'felicite'
    ];
    return $this->json($responseData, Response::HTTP_BAD_REQUEST);
    
    }


    #[Route('/Commandes/Client/Confirmer', name: 'api_Client_C')]
    public function getToClientConf(EntityManagerInterface $entityManager,Request $request): Response
    {
        $requestData = json_decode($request->getContent(), true);
        // $NomC = $requestData['NomC'] ?? null;
         $ClientId = $requestData['Id'] ?? null;
        // $ClientId = $requestData['Id'] ?? null;
       // $clientId = YOUR_CLIENT_ID;
         // Replace 'specificColumn1', 'specificColumn2', etc. with the actual column names you want to retrieve.
    

    // Fetch all commands with the specific columns using Doctrine's QueryBuilder.
    $queryBuilder = $entityManager->createQueryBuilder();
    $queryBuilder
    ->update(Commande::class, 'c')
    ->set('c.Etat', ':newValue3') 
    ->andWhere('c.client = :ClientId')
    ->andWhere('c.Etat = :et')
->setParameter('ClientId', $ClientId)
->setParameter('et', 'en attente_confirme')
->setParameter('newValue3', 'en cours')
        ->getQuery()
        ->execute(); 
    // You may want to convert the result into an array for better control over the response.
    $responseData = [
        'message' => 'confirmer',
        'status' => 'bravo'
        //'status' => 'felicite'
    ];
    return $this->json($responseData, Response::HTTP_BAD_REQUEST);
    
    }
}
