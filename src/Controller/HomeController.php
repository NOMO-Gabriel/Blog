<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/', name: 'blog.home.')]

class HomeController extends AbstractController
{
    #[Route('/')]
    public function home():Response
    {
        return $this->redirectToRoute('blog.home.default.index');
    }
    #[Route('/blog/home', name: 'default.index')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'title' => 'home'

        ]);
    }
    #[Route('/blog/home/user/{username}', name: 'user.index',requirements: ['username' => '^[a-z0-9_-]{4,15}$' ])]
    public function UserIndex($username): Response
    {

        return $this->render('home/user/index.html.twig', [
            'title' => 'homeUser',
            'username' => $username
        ]);
    }
    #[Route('/blog/home/admin/{username}', name: 'admin.index')]
    public function AdminIndex($username): Response
    {
        return $this->render('home/admin/index.html.twig', [
            'title' => 'homeAdmin',
            'username' => $username,
        ]);
    }
}
