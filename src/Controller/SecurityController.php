<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/blog/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
<<<<<<< HEAD
        if($this->getUser()){
            $roles = $this->getUser()->getRoles();
            if(in_array('ROLE_USER',$roles))
            {
                if(in_array('ROLE_ADMIN',$roles)) {
                    return $this->redirectToRoute('blog.home.admin.index',['username' => $this->getUser()->getUserIdentifier()]);
                }
                return $this->redirectToRoute('blog.home.user.index',['username' => $this->getUser()->getUserIdentifier()]);
            }
        }
=======
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }
>>>>>>> origin-old/main

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
<<<<<<< HEAD
=======

>>>>>>> origin-old/main
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/blog/logout', name: 'app_logout')]
    public function logout(): Response
    {
       return $this->redirectToRoute('blog.home.default.index');
    }
}
