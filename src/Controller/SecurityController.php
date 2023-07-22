<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends AbstractController
{   
    #[Route(path: '/api/login', name: 'api_login',methods:['POST'])]
    public function apiLlogin(){

        $user=$this->getUser();
        return $this->json([
            'username' => $user->getUsername(),
            'roles'=>$user->getRoles()

        ]);
    }


    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils,#[CurrentUser] ?User $user,Request $request): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('target_path');
         }

        // $this->saveTargetPath($request->getSession(), 'main', $this->generateUrl('admin_index'));

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
