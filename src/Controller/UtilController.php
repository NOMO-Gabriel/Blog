<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/blog', name: 'blog.util.')]
class UtilController extends AbstractController
{
    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('util/about.html.twig', [
            'title' => 'about',
        ]);
    }
    #[Route('/about/user/{username}', name: 'about.user',requirements: ['username'=> Requirement::ASCII_SLUG])]
    public function aboutUser($username): Response
    {
        return $this->render('util/aboutUser.html.twig', [
            'title' => 'about',
            'username' => $username
        ]);
    }
    #[Route('/about/admin/{username}', name: 'about.admin',requirements: ['username'=> Requirement::ASCII_SLUG])]
    public function aboutAdmin($username): Response
    {
        return $this->render('util/aboutUser.html.twig', [
            'title' => 'about',
            'username' => $username
        ]);
    }







    #[Route('/notify/{username}', name: 'notify',requirements: ['username' => Requirement::ASCII_SLUG])]
    public function notify($username): Response
    {
        return $this->render('util/notify.html.twig', [
            'title' => 'notify',
            'username'=> $username

        ]);
    }

}
