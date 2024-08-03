<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
    #[IsGranted('ROLE_USER')]
    #[Route('/about/user/{username}', name: 'about.user',requirements: ['username'=> Requirement::ASCII_SLUG])]
    public function aboutUser($username): Response
    {
        if($this->getUser()){
            $roles = $this->getUser()->getRoles();
            if(in_array('ROLE_USER',$roles))
            {
                if(in_array('ROLE_ADMIN',$roles)) {
                    return $this->redirectToRoute('blog.util.about.admin',['username' => $this->getUser()->getUserIdentifier()]);
                }
              //  return $this->redirectToRoute('blog.util.about.user',['username' => $this->getUser()->getUserIdentifier()]);
            }
        }

        return $this->render('util/aboutUser.html.twig', [
            'title' => 'about',
            'username' => $username
        ]);
    }
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/about/admin/{username}', name: 'about.admin',requirements: ['username'=> Requirement::ASCII_SLUG])]
    public function aboutAdmin($username): Response
    {
        return $this->render('util/aboutAdmin.html.twig', [
            'title' => 'about',
            'username' => $username
        ]);
    }
    #[IsGranted('ROLE_USER')]
    #[Route('/notify/{username}', name: 'notify',requirements: ['username' => Requirement::ASCII_SLUG])]
    public function notify($username): Response
    {
        return $this->render('util/notify.html.twig', [
            'title' => 'notify',
            'username'=> $username

        ]);
    }
}
