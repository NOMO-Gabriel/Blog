<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/blog/contact', name: 'blog.contact.')]
class ContactController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Traitement des données du formulaire
            // Exemple: envoyer un e-mail ou sauvegarder les données
            $this->addFlash('success', 'Your message has been sent successfully.');
            return $this->redirectToRoute('blog.home.default.index');
        }
        return $this->render('contact/index.html.twig', [
            'title' => 'Contact',
            'form' => $form,
        ]);
    }
    #[IsGranted('ROLE_USER')]
    #[Route('/user/{username}',name:'user',requirements: ['username'=> Requirement::ASCII_SLUG])]
    public function contactUser(Request $request,String $username)
        {
            $form = $this->createForm(ContactType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                // Traitement des données du formulaire
                // Exemple: envoyer un e-mail ou sauvegarder les données
                $this->addFlash('success', 'Your message has been sent successfully.');
                return $this->redirectToRoute('blog.home.user.index',['username'=>$username]);
            }
            return $this->render('contact/user/index.html.twig', [
                'title' => 'Contact',
                'form' => $form,
                'username'=> $username
            ]);
        }
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/{username}',name:'admin',requirements: ['username'=> Requirement::ASCII_SLUG])]
    public function contactAdmin(Request $request,String $username)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Traitement des données du formulaire
            // Exemple: envoyer un e-mail ou sauvegarder les données
            $this->addFlash('success', 'Your message has been sent successfully.');
            return $this->redirectToRoute('blog.home.admin.index',['username'=>$username]);
        }
        return $this->render('contact/admin/index.html.twig', [
            'title' => 'Contact',
            'form' => $form,
            'username'=> $username
        ]);
    }
}
