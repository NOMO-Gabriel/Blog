<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/blog/about', name: 'blog.util.')]
class UtilController extends AbstractController
{
    #[Route('/', name: 'about')]
    public function index(): Response
    {
        return $this->render('util/about.html.twig', [
            'title' => 'about',
        ]);
    }
}
