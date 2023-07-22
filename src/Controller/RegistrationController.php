<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\AppAuthenticorAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use ApiPlatform\Metadata\ApiResource;
use App\Entity\Client;
use App\Entity\Livreur;

#[ApiResource]
class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'fgjj')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppAuthenticorAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
    


#[Route('/api/clients/register-with-user', name: 'api_register_client_with_user', methods: ['POST'])]
public function registerClientWithUser(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    // Créer un nouvel utilisateur
    $user = new User();
    $user->setEmail($data['email']);

    // Hacher le mot de passe
    $hashedPassword = $userPasswordHasher->hashPassword($user, $data['user']['password']);
    $user->setPassword($hashedPassword);
    $user->setRoles($data['user']['roles']);

    // Créer un nouveau client
    $client = new Client();
    $client->setNom($data['nom']);
    $client->setPrenom($data['prenom']);
    $client->setEmail($data['email']);
    $client->setTelephone($data['telephone']);
    $client->setDateNaissance(new \DateTime($data['dateNaissance']));
    $client->setCin($data['cin']);
    $client->setUser($user);

    // Persistez l'utilisateur et le client
    $entityManager->persist($user);
    $entityManager->persist($client);
    $entityManager->flush();

    return new JsonResponse(['message' => 'Client registered successfully']);
}
#[Route('/api/livreurs/register-with-user', name: 'api_register_livreur_with_user', methods: ['POST'])]
public function registerLivreurWithUser(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): JsonResponse
{
    $data = json_decode($request->getContent(), true);

    // Créer un nouvel utilisateur
    $user = new User();
    $user->setEmail($data['email']);

    // Hacher le mot de passe
    $hashedPassword = $userPasswordHasher->hashPassword($user, $data['user']['password']);
    $user->setPassword($hashedPassword);
    $user->setRoles($data['user']['roles']);

    // Créer un nouveau Livreur
    $livreur = new Livreur();
$livreur->setNom($data['nom']);
$livreur->setPrenom($data['prenom']);
$livreur->setEmail($data['email']);
$livreur->setTelephone($data['telephone']);
$livreur->setCin($data['cin']);
$livreur->setUser($user);

    // Persistez l'utilisateur et le Livreur
    $entityManager->persist($user);
    $entityManager->persist($livreur);
    $entityManager->flush();

    return new JsonResponse(['message' => 'Livreur registered successfully']);
}
#[Route('/api/livreur/{id}', name: 'api_update_livreur_with_user', methods: ['PUT'])]
public function updateLivreur(Request $request,UserPasswordHasherInterface $userPasswordHasher,EntityManagerInterface $entityManager,int $id): JsonResponse{
    // Récupérer le Livreur et l'utilisateur  associé
    $livreur = $entityManager->getRepository(Livreur::class)->find($id);

    if (!$livreur) {
        throw $this->createNotFoundException('L\'utilisateur avec l\'ID ' . $id . ' n\'existe pas.');
    }
    $user=$livreur->getUser();

    // Récupérer les données du corps de la requête
    $data = json_decode($request->getContent(), true);

    
    $livreur->setNom($data['nom']);
    $livreur->setPrenom($data['prenom']);
    $livreur->setEmail($data['email']);
    $livreur->setTelephone($data['telephone']);
    $livreur->setCin($data['cin']);
    $livreur->setLatitude($data['latitude']);
    $livreur->setlongitude($data['longitude']);
    $user->setEmail($data['email']);
    // Hacher le mot de passe
    $hashedPassword = $userPasswordHasher->hashPassword($user, $data['user']['password']);
    $user->setPassword($hashedPassword);

    $user->setRoles($data['user']['roles']);
    $livreur->setUser($user);
    //$entityManager->persist($livreur);
    $entityManager->flush();

    return new JsonResponse(['message' => 'Livreur updated successfully']);

}
#[Route('/api/client/{id}', name: 'api_update_Client_with_user', methods: ['PUT'])]
public function updateClient(Request $request,UserPasswordHasherInterface $userPasswordHasher,EntityManagerInterface $entityManager,int $id): JsonResponse{
    // Récupérer le Livreur et l'utilisateur  associé
    $client = $entityManager->getRepository(Client::class)->find($id);

    if (!$client) {
        throw $this->createNotFoundException('L\'utilisateur avec l\'ID ' . $id . ' n\'existe pas.');
    }
    $user=$client->getUser();

    // Récupérer les données du corps de la requête
    $data = json_decode($request->getContent(), true);

    
    $client->setNom($data['nom']);
    $client->setPrenom($data['prenom']);
    $client->setEmail($data['email']);
    $client->setTelephone($data['telephone']);
    $client->setCin($data['cin']);
    $client->setLatitude($data['latitude']);
    $client->setlongitude($data['longitude']);
    $user->setEmail($data['email']);
    // Hacher le mot de passe
    $hashedPassword = $userPasswordHasher->hashPassword($user, $data['user']['password']);
    $user->setPassword($hashedPassword);

    $user->setRoles($data['user']['roles']);
    $client->setUser($user);
    //$entityManager->persist($livreur);
    $entityManager->flush();

    return new JsonResponse(['message' => 'Client updated successfully']);

}


}

