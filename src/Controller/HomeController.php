<?php

namespace App\Controller;

<<<<<<< HEAD
use App\Repository\QuestionRepository;
use App\Repository\ServiceRepository;
=======
>>>>>>> origin-old/main
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
<<<<<<< HEAD
use Symfony\Component\Security\Http\Attribute\IsGranted;
=======
>>>>>>> origin-old/main

#[Route('/', name: 'blog.home.')]

class HomeController extends AbstractController
{
    #[Route('/')]
    public function home():Response
    {
        return $this->redirectToRoute('blog.home.default.index');
    }
<<<<<<< HEAD
    #[Route('/blog')]
    public function home1():Response
    {
        return $this->redirectToRoute('blog.home.default.index');
    }
    #[Route('/blog/home', name: 'default.index')]
    public function index(ServiceRepository $serviceRepository, QuestionRepository $questionRepository): Response
    {
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
        $services = $serviceRepository->findBy([], ['id' => 'DESC'], 8);
        $questions = $questionRepository->findBy([], ['id' => 'DESC'], 9);
        return $this->render('home/index.html.twig', [
            'title' => 'homeUser',
            'services' => $services,
            'questions' => $questions,
        ]);
    }
    #[IsGranted('ROLE_USER')]
    #[Route('/blog/home/user/{username}', name: 'user.index',requirements: ['username' => '^[a-z0-9_-]{4,15}$' ])]
    public function UerIndex(string $username, ServiceRepository $serviceRepository, QuestionRepository $questionRepository): Response
    {
            if($this->getUser()){
                $roles=$this->getUser()->getRoles();
                if(in_array('ROLE_ADMIN',$roles)){
                    return $this->redirectToRoute('blog.home.admin.index',['username'=> $username]);
                }
            }
        $services = $serviceRepository->findBy([], ['id' => 'DESC'], 8);
        $questions = $questionRepository->findBy([], ['id' => 'DESC'], 9);
        return $this->render('home/user/index.html.twig', [
            'title' => 'homeUser',
            'username' => $username,
            'services' => $services,
            'questions' => $questions,
        ]);

    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/blog/home/admin/{username}', name: 'admin.index',requirements: ['username' => '^[a-z0-9_-]{4,15}$' ])]
    public function AdminIndex(string $username, ServiceRepository $serviceRepository, QuestionRepository $questionRepository): Response
    {
        $services = $serviceRepository->findBy([], ['id' => 'DESC'], 8);
        $questions = $questionRepository->findBy([], ['id' => 'DESC'], 9);

        return $this->render('home/admin/index.html.twig', [
            'title' => 'homeAdmin',
            'username' => $username,
            'services' => $services,
            'questions' => $questions,
=======
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
>>>>>>> origin-old/main
        ]);
    }
}
