<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
<<<<<<< HEAD
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Constraints\Date;
=======
use Symfony\Component\Validator\Constraints\Date;

>>>>>>> origin-old/main
#[Route('blog/services', name: 'blog.service.')]
class ServiceController extends AbstractController
{
    //routes pour voir les services
    #[Route('/all', name: 'default.index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
<<<<<<< HEAD
        if($this->getUser()){
            $roles = $this->getUser()->getRoles();
            if(in_array('ROLE_USER',$roles))
            {
                if(in_array('ROLE_ADMIN',$roles)) {
                    return $this->redirectToRoute('blog.service.admin.index',['username' => $this->getUser()->getUserIdentifier()]);
                }
                return $this->redirectToRoute('blog.service.user.index',['username' => $this->getUser()->getUserIdentifier()]);
            }
        }
        $services = $entityManager->getRepository(Service::class)->findAll();
=======
        $services = $entityManager->getRepository(Service::class)->findAll();

>>>>>>> origin-old/main
        return $this->render('service/index.html.twig', [
                     'services' => $services,
        ]);
    }
<<<<<<< HEAD
    #[IsGranted('ROLE_USER')]
    #[Route('/user/{username}/all', name: 'user.index',requirements: ['username' => '^[a-z0-9_-]{4,15}$' ])]
    public function userIndex(EntityManagerInterface $entityManager,$username): Response
    {
        if($this->getUser()){
            $roles = $this->getUser()->getRoles();
            if(in_array('ROLE_USER',$roles))
            {
                if(in_array('ROLE_ADMIN',$roles)) {
                    return $this->redirectToRoute('blog.service.admin.index',['username' => $this->getUser()->getUserIdentifier()]);
                }
              //  return $this->redirectToRoute('blog.service.user.index',['username' => $this->getUser()->getUserIdentifier()]);
            }
        }
=======

    #[Route('/user/{username}/all', name: 'user.index',requirements: ['username' => '^[a-z0-9_-]{4,15}$' ])]
    public function userIndex(EntityManagerInterface $entityManager,$username): Response
    {
>>>>>>> origin-old/main
        $services = $entityManager->getRepository(Service::class)->findAll();
        return $this->render('service/user/index.html.twig', [
                       'services' => $services,
                        'username' => $username
        ]);
    }
<<<<<<< HEAD
    #[IsGranted('ROLE_ADMIN')]
=======
>>>>>>> origin-old/main
    #[Route('/admin/{username}/all', name: 'admin.index',requirements: ['username' => '^[a-z0-9_-]{4,15}$' ])]
    public function adminIndex(EntityManagerInterface $entityManager,$username): Response
    {
        $services = $entityManager->getRepository(Service::class)->findAll();
        return $this->render('service/admin/index.html.twig', [
                    'services' => $services ,
                    'username' => $username

        ]);
    }



    //routes pour voir un seul service
    #[Route('/{id}/show', name: 'default.show',requirements: ['id' => Requirement::DIGITS ])]
    public function show(EntityManagerInterface $entityManager, $id): Response
    {
<<<<<<< HEAD
        if($this->getUser()){
            $roles = $this->getUser()->getRoles();
            if(in_array('ROLE_USER',$roles))
            {
                if(in_array('ROLE_ADMIN',$roles)) {
                    return $this->redirectToRoute('blog.service.admin.show',['username' => $this->getUser()->getUserIdentifier()]);
                }
                return $this->redirectToRoute('blog.service.user.show',['username' => $this->getUser()->getUserIdentifier()]);
            }
        }
=======
>>>>>>> origin-old/main
        $service = $entityManager->getRepository(Service::class)->find($id);
        if (!$service) {
            $this->addFlash('error',"Ce service n'existe pas");
            return $this->redirectToRoute('blog.service.default.index');
        }
        $service->setNumberOfRequests($service->getNumberOfRequests()+1);
        $entityManager->flush();
        return $this->render('service/show.html.twig', [
            'service' => $service ,
            'username' => 'defaultName'
        ]);
    }

<<<<<<< HEAD
    #[IsGranted('ROLE_USER')]
    #[Route('/{id}/user/{username}/show', name: 'user.show', requirements: ['username' => '^[a-z0-9_-]{4,15}$', 'id' => Requirement::DIGITS])]
    public function userShow(EntityManagerInterface $entityManager, $id, $username): Response
    {
        if($this->getUser()){
            $roles = $this->getUser()->getRoles();
            if(in_array('ROLE_USER',$roles))
            {
                if(in_array('ROLE_ADMIN',$roles)) {
                    return $this->redirectToRoute('blog.service.admin.show',['username' => $this->getUser()->getUserIdentifier()]);
                }
             //   return $this->redirectToRoute('blog.service.user.show',['username' => $this->getUser()->getUserIdentifier()]);
            }
        }
=======

    #[Route('/{id}/user/{username}/show', name: 'user.show', requirements: ['username' => '^[a-z0-9_-]{4,15}$', 'id' => Requirement::DIGITS])]
    public function userShow(EntityManagerInterface $entityManager, $id, $username): Response
    {
>>>>>>> origin-old/main
        $service = $entityManager->getRepository(Service::class)->find($id);

        if (!$service) {
            $this->addFlash('error', "Ce service n'existe pas");
            return $this->redirectToRoute('blog.service.user.index', [
                'username' => $username
            ]);
        }
        $service->setNumberOfRequests($service->getNumberOfRequests() + 1);
        $entityManager->flush();
<<<<<<< HEAD
=======

>>>>>>> origin-old/main
        return $this->render('service/user/show.html.twig', [
            'service' => $service,
            'username' => $username ,
        ]);
    }

<<<<<<< HEAD
    #[IsGranted('ROLE_ADMIN')]
=======

>>>>>>> origin-old/main
    #[Route('/{id}/show/admin/{username}', name: 'admin.show',requirements: ['username' => '^[a-z0-9_-]{4,15}$' ,'id' => Requirement::DIGITS])]
    public function adminShow(EntityManagerInterface $entityManager, $id, $username): Response
    {
        $service = $entityManager->getRepository(Service::class)->find($id);
<<<<<<< HEAD
=======

>>>>>>> origin-old/main
        if (!$service) {
            $this->addFlash('error', "Ce service n'existe pas");
            return $this->redirectToRoute('blog.service.admin.index', [
                'username' => $username
            ]);
        }
        $service->setNumberOfRequests($service->getNumberOfRequests() + 1);
        $entityManager->flush();

        return $this->render('service/admin/show.html.twig', [
            'service' => $service,
            'username' => $username
        ]);
    }



    //routes pour creer un service
<<<<<<< HEAD
    #[IsGranted('ROLE_ADMIN')]
=======
>>>>>>> origin-old/main
    #[Route('/admin/{username}/create', name: 'admin.create', requirements: ['username' => '^[a-z0-9_-]{4,15}$'])]
    public function adminCreate(Request $request, EntityManagerInterface $entityManager, $username): Response
    {
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);
<<<<<<< HEAD
=======


>>>>>>> origin-old/main
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $service->setCreatedAt(new \DateTimeImmutable());
            $service->setUpdatedAt(new \DateTimeImmutable());
            $service->setNumberOfRequests(0);
            $entityManager->persist($service);
            $entityManager->flush();
<<<<<<< HEAD
            $this->addFlash('success', 'Service créé avec succès');
            return $this->redirectToRoute('blog.service.admin.index', ['username' => $username]);
        }
=======

            $this->addFlash('success', 'Service créé avec succès');

            return $this->redirectToRoute('blog.service.admin.index', ['username' => $username]);
        }

>>>>>>> origin-old/main
        return $this->render('service/admin/create.html.twig', [
            'form' => $form->createView(),
            'username' => $username,
        ]);
    }

<<<<<<< HEAD
    //routes pour modifier une service
    #[IsGranted('ROLE_ADMIN')]
=======

    //routes pour modifier une service

>>>>>>> origin-old/main
    #[Route('/{id}/admin/{username}/edit', name: 'admin.edit', requirements: ['username' => '^[a-z0-9_-]{4,15}$', 'id' => Requirement::DIGITS])]
    public function adminEdit(Request $request, EntityManagerInterface $entityManager, $id, $username): Response
    {
        $service = $entityManager->getRepository(Service::class)->find($id);
        if (!$service) {
            $this->addFlash('error', "Ce service n'existe pas");
            return $this->redirectToRoute('blog.service.admin.index', ['username' => $username]);
        }
<<<<<<< HEAD
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Service modifié avec succès');
            return $this->redirectToRoute('blog.service.admin.index', ['username' => $username]);
        }
=======

        $form = $this->createForm(ServiceType::class, $service);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Service modifié avec succès');

            return $this->redirectToRoute('blog.service.admin.index', ['username' => $username]);
        }

>>>>>>> origin-old/main
        return $this->render('service/admin/edit.html.twig', [
            'form' => $form->createView(),
            'username' => $username,
        ]);
    }

    //routes pour supprimer une service
<<<<<<< HEAD
    #[IsGranted('ROLE_ADMIN')]
=======
>>>>>>> origin-old/main
    #[Route('/{id}/admin/{username}/delete', name: 'admin.delete', requirements: ['username' => '^[a-z0-9_-]{4,15}$', 'id' => Requirement::DIGITS])]
    public function adminDelete(Request $request, EntityManagerInterface $entityManager, $id, $username): Response
    {
        $service = $entityManager->getRepository(Service::class)->find($id);
        if (!$service) {
            $this->addFlash('error', "Ce service n'existe pas");
            return $this->redirectToRoute('blog.service.admin.index', ['username' => $username]);
        }
<<<<<<< HEAD
        if ($request->isMethod('POST')) {
            $entityManager->remove($service);
            $entityManager->flush();
            $this->addFlash('success', 'Service supprimé avec succès');
            return $this->redirectToRoute('blog.service.admin.index', ['username' => $username]);
        }
=======

        if ($request->isMethod('POST')) {
            $entityManager->remove($service);
            $entityManager->flush();

            $this->addFlash('success', 'Service supprimé avec succès');

            return $this->redirectToRoute('blog.service.admin.index', ['username' => $username]);
        }

>>>>>>> origin-old/main
        return $this->render('service/admin/delete.html.twig', [
            'service' => $service,
            'username' => $username,
        ]);
    }
}
