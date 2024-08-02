<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/blog/administration', name: 'blog.administration.')]
class AdministrationController extends AbstractController
{
    #[Route('/{username}', name: 'index')]
    public function index(EntityManagerInterface $entityManager, String $username): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();
        return $this->render('administration/index.html.twig', [
                'users' => $users,
                'username' => $username ,
        ]);
    }

    #[Route('/edit-role/{id}', name: 'edit_role', methods: ['POST'])]
    public function editRole(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
        $role = $request->request->get('role');
        switch ($role) {
            case '0':
                $user->setRoles(['ROLE_USER']);
                break;
            case '1':
                $user->setRoles(['ROLE_SUPER_ADMIN']);
                break;
            case '2':
                $user->setRoles(['ROLE_ADMIN']);
                break;
            default:
                throw new \InvalidArgumentException('Invalid role');
        }
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute('blog.administration.index', ['username' => $user->getUsername()]);
    }
}


