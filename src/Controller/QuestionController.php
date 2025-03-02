<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Service;
use App\Entity\User;
use App\Form\QuestionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
#[Route('blog/questions', name: 'blog.question.')]
class QuestionController extends AbstractController
{
    // Routes pour voir les questions
    #[Route('/all/{filter}', name: 'default.index', requirements: ['filter' => 'all|service_[0-9]+'])]
    public function index(EntityManagerInterface $entityManager, $filter = 'all'): Response
    {
        $services = $entityManager->getRepository(Service::class)->findAll();

        if ($filter === 'all') {
            $serviceName = 'aucun service';
            $questions = $entityManager->getRepository(Question::class)->findAll();
        } else {
            $serviceId = (int) str_replace('service_', '', $filter);
            $questions = $entityManager->getRepository(Question::class)->findBy(['service' => $serviceId]);
            $serviceName = $entityManager->find(Service::class,$serviceId)->getName();
        }

        return $this->render('question/index.html.twig', [
            'questions' => $questions,
            'services' => $services,
            'activeFilter' => $filter,
            'serviceName' =>  $serviceName,
        ]);
    }
    #[IsGranted('ROLE_USER')]
    #[Route('/user/{username}/{filter}', name: 'user.index', requirements: ['username' => '^[a-z0-9_-]{4,15}$', 'filter' => 'all|service_[0-9]+'])]
    public function userIndex(EntityManagerInterface $entityManager, string $username, $filter = 'all'): Response
    {
        if($this->getUser()){
            $roles = $this->getUser()->getRoles();
            if(in_array('ROLE_USER',$roles))
            {
                if(in_array('ROLE_ADMIN',$roles)) {
                    return $this->redirectToRoute('blog.question.admin.index',['username' => $this->getUser()->getUserIdentifier(),'filter'=>$filter]);
                }
                //return $this->redirectToRoute('blog.question.user.index',['username' => $this->getUser()->getUserIdentifier(),'filter'=>$filter]);
            }
        }
        $services = $entityManager->getRepository(Service::class)->findAll();

        if ($filter === 'all') {
            $serviceName = 'aucun service';
            $questions = $entityManager->getRepository(Question::class)->findAll();
        } else {
            $serviceId = (int) str_replace('service_', '', $filter);
            $questions = $entityManager->getRepository(Question::class)->findBy(['service' => $serviceId]);
            $serviceName = $entityManager->find(Service::class, $serviceId)->getName();
        }

        return $this->render('question/user/index.html.twig', [
            'questions' => $questions,
            'services' => $services,
            'activeFilter' => $filter,
            'serviceName' => $serviceName,
            'username' => $username,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/admin/{username}/{filter}', name: 'admin.index', requirements: ['username' => '^[a-z0-9_-]{4,15}$', 'filter' => 'all|service_[0-9]+'])]
    public function adminIndex(EntityManagerInterface $entityManager, string $username, $filter = 'all'): Response
    {
        $services = $entityManager->getRepository(Service::class)->findAll();

        if ($filter === 'all') {
            $serviceName = 'aucun service';
            $questions = $entityManager->getRepository(Question::class)->findAll();
        } else {
            $serviceId = (int) str_replace('service_', '', $filter);
            $questions = $entityManager->getRepository(Question::class)->findBy(['service' => $serviceId]);
            $serviceName = $entityManager->find(Service::class, $serviceId)->getName();
        }

        return $this->render('question/admin/index.html.twig', [
            'questions' => $questions,
            'services' => $services,
            'activeFilter' => $filter,
            'serviceName' => $serviceName,
            'username' => $username,
        ]);
    }
    // Routes pour voir une seule question
    #[Route('/{id}/show', name: 'default.show', requirements: ['id' => Requirement::DIGITS])]
    public function show(EntityManagerInterface $entityManager, $id): Response
    {
        $question = $entityManager->getRepository(Question::class)->find($id);

        if (!$question) {
            $this->addFlash('error', "Cette question n'existe pas");
            return $this->redirectToRoute('blog.question.default.index');
        }

        return $this->render('question/show.html.twig', [
            'question' => $question,
        ]);
    }
    #[IsGranted('ROLE_USER')]
    #[Route('/{id}/show/user/{username}', name: 'user.show', requirements: ['username' => '^[a-z0-9_-]{4,15}$', 'id' => Requirement::DIGITS])]
    public function userShow(EntityManagerInterface $entityManager, $id, $username): Response
    {
        if($this->getUser()){
            $roles = $this->getUser()->getRoles();
            if(in_array('ROLE_USER',$roles))
            {
                if(in_array('ROLE_ADMIN',$roles)) {
                    return $this->redirectToRoute('blog.question.admin.show',['username' => $this->getUser()->getUserIdentifier(),'id'=>$id]);
                }
               // return $this->redirectToRoute('blog.question.user.show',['username' => $this->getUser()->getUserIdentifier(),'id'=>$id]);
            }
        }
        $question = $entityManager->getRepository(Question::class)->find($id);
        if (!$question) {
            $this->addFlash('error', "Cette question n'existe pas");
            return $this->redirectToRoute('blog.question.user.index', [
                'username' => $username,
            ]);
        }
        return $this->render('question/user/show.html.twig', [
            'question' => $question,
            'username' => $username,
        ]);
    }
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/show/admin/{username}', name: 'admin.show', requirements: ['username' => '^[a-z0-9_-]{4,15}$', 'id' => Requirement::DIGITS])]
    public function adminShow(EntityManagerInterface $entityManager, $id, $username): Response
    {

        $question = $entityManager->getRepository(Question::class)->find($id);
        if (!$question) {
            $this->addFlash('error', "Cette question n'existe pas");
            return $this->redirectToRoute('blog.question.admin.index', [
                'username' => $username,
            ]);
        }
        return $this->render('question/admin/show.html.twig', [
            'question' => $question,
            'username' => $username,
        ]);
    }
    // Routes pour poser une question
    #[Route('/ask', name: 'default.create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->addFlash("alert", "Connectez vous ou inscrivez vous pour creer une question");
        return $this->redirectToRoute('app_login');
    }
    #[IsGranted('ROLE_USER')]
    #[Route('/ask/user/{username}', name: 'user.create', requirements: ['username' => '^[a-z0-9_-]{4,15}$'])]
    public function userCreate(Request $request, EntityManagerInterface $entityManager, $username): Response
        {
            if($this->getUser()){
            $roles = $this->getUser()->getRoles();
            if(in_array('ROLE_USER',$roles))
            {
                if(in_array('ROLE_ADMIN',$roles)) {
                    return $this->redirectToRoute('blog.question.admin.create',['username' => $this->getUser()->getUserIdentifier()]);
                }
               // return $this->redirectToRoute('blog.question.user.create',['username' => $this->getUser()->getUserIdentifier()]);
            }
        }
        if (!$this->isGranted('ROLE_USER')) {
            $this->addFlash("alert", "Connectez-vous ou inscrivez-vous pour créer une question");
            return $this->redirectToRoute('app_login');
        }
        $creator = $entityManager->getRepository(User::class)->findOneBy(['username' => $username]);
        if (!$creator) {
            $this->addFlash("error", "Utilisateur non trouvé");
            return $this->redirectToRoute('blog.question.user.index', ['username' => $username]);
        }
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $question->setCreatedAt(new \DateTimeImmutable());
            $question->setUpdatedAt(new \DateTimeImmutable());
            $question->setCreator($creator);
            $entityManager->persist($question);
            $entityManager->flush();
            $this->addFlash('success', 'Question posée avec succès');
            return $this->redirectToRoute('blog.question.user.index', ['username' => $username]);
    }

            return $this->render('question/user/create.html.twig', [
                'form' => $form->createView(),
                'username' => $username,
            ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/ask/admin/{username}', name: 'admin.create', requirements: ['username' => '^[a-z0-9_-]{4,15}$'])]
    public function adminCreate(Request $request, EntityManagerInterface $entityManager, string $username): Response
    {

        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $creator = $entityManager->getRepository(User::class)->findOneBy(['username' => $username]);
            if (!$creator) {
                $this->addFlash('error', "L'utilisateur n'existe pas");
                return $this->redirectToRoute('blog.question.admin.index', ['username' => $username]);
            }
            $question->setCreatedAt(new \DateTimeImmutable());
            $question->setUpdatedAt(new \DateTimeImmutable());
            $question->setCreator($creator);
            $entityManager->persist($question);
            $entityManager->flush();
            $this->addFlash('success', 'Question posée avec succès');
            return $this->redirectToRoute('blog.question.admin.index', ['username' => $username]);
        }
        return $this->render('question/admin/create.html.twig', [
            'form' => $form->createView(),
            'username' => $username,
        ]);
    }
    // Routes pour modifier une question
    #[Route('/{id}/edit', name: 'default.edit', requirements: ['id' => Requirement::DIGITS])]
    public function edit(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        if($this->getUser()){
            $roles = $this->getUser()->getRoles();
            if(in_array('ROLE_USER',$roles))
            {
                if(in_array('ROLE_ADMIN',$roles)) {
                    return $this->redirectToRoute('blog.question.admin.edit',['username' => $this->getUser()->getUserIdentifier(),'id'=>$id]);
                }
                return $this->redirectToRoute('blog.question.user.edit',['username' => $this->getUser()->getUserIdentifier(),'id'=>$id]);
            }
        }
        $question = $entityManager->getRepository(Question::class)->find($id);
        if (!$question) {
            $this->addFlash('error', "Cette question n'existe pas");
            return $this->redirectToRoute('blog.question.default.index');
        }
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Question modifiée avec succès');
            return $this->redirectToRoute('blog.question.default.index');
        }
        return $this->render('question/edit.html.twig', [
            'form' => $form,
        ]);
    }
    #[IsGranted('ROLE_USER')]
    #[Route('/{id}/edit/user/{username}', name: 'user.edit', requirements: ['username' => '^[a-z0-9_-]{4,15}$', 'id' => Requirement::DIGITS])]
    public function userEdit(Request $request, EntityManagerInterface $entityManager, $id, $username): Response
    {
        $question = $entityManager->getRepository(Question::class)->find($id);
        if($this->getUser()){
            $roles = $this->getUser()->getRoles();
            if(in_array('ROLE_USER',$roles))
            {
                if(in_array('ROLE_ADMIN',$roles)) {
                    return $this->redirectToRoute('blog.question.admin.edit',['username' => $this->getUser()->getUserIdentifier(),'id'=>$id]);
                }
              //  return $this->redirectToRoute('blog.question.user.create',['username' => $this->getUser()->getUserIdentifier(),'id'=>$id]);
            }
        }
        if (!$question) {
            $this->addFlash('error', "Cette question n'existe pas");
            return $this->redirectToRoute('blog.question.user.index', ['username' => $username]);
        }

        $form = $this->createForm(QuestionType::class, $question);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Question modifiée avec succès');
            return $this->redirectToRoute('blog.question.user.index', ['username' => $username]);
        }
        return $this->render('question/user/edit.html.twig', [
            'form' => $form,
            'username' => $username,
        ]);
    }
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/edit/admin/{username}', name: 'admin.edit', requirements: ['username' => '^[a-z0-9_-]{4,15}$', 'id' => Requirement::DIGITS])]
    public function adminEdit(Request $request, EntityManagerInterface $entityManager, $id, $username): Response
    {
        $question = $entityManager->getRepository(Question::class)->find($id);
        if (!$question) {
            $this->addFlash('error', "Cette question n'existe pas");
            return $this->redirectToRoute('blog.question.admin.index', ['username' => $username]);
        }
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Question modifiée avec succès');
            return $this->redirectToRoute('blog.question.admin.index', ['username' => $username]);
        }
        return $this->render('question/admin/edit.html.twig', [
            'form' => $form,
            'username' => $username,
        ]);
    }

    // Routes pour supprimer une question
    #[Route('/{id}/delete', name: 'default.delete', requirements: ['id' => Requirement::DIGITS])]
    public function delete(EntityManagerInterface $entityManager, $id): Response
    {
        if($this->getUser()){
            $roles = $this->getUser()->getRoles();
            if(in_array('ROLE_USER',$roles))
            {
                if(in_array('ROLE_ADMIN',$roles)) {
                    return $this->redirectToRoute('blog.question.admin.delete',['username' => $this->getUser()->getUserIdentifier(),'id'=>$id]);
                }
                  return $this->redirectToRoute('blog.question.user.delete',['username' => $this->getUser()->getUserIdentifier(),'id'=>$id]);
            }
        }
        $question = $entityManager->getRepository(Question::class)->find($id);
        if (!$question) {
            $this->addFlash('error', "Cette question n'existe pas");
            return $this->redirectToRoute('blog.question.default.index');
        }
        $entityManager->remove($question);
        $entityManager->flush();
        $this->addFlash('success', 'Question supprimée avec succès');
        return $this->redirectToRoute('blog.question.default.index');
    }
    #[IsGranted('ROLE_USER')]
    #[Route('/{id}/delete/user/{username}', name: 'user.delete', requirements: ['username' => '^[a-z0-9_-]{4,15}$', 'id' => Requirement::DIGITS])]
    public function userDelete(EntityManagerInterface $entityManager, $id, $username): Response
    {
        if($this->getUser()){
            $roles = $this->getUser()->getRoles();
            if(in_array('ROLE_USER',$roles))
            {
                if(in_array('ROLE_ADMIN',$roles)) {
                    return $this->redirectToRoute('blog.question.admin.delete',['username' => $this->getUser()->getUserIdentifier(),'id'=>$id]);
                }
//                return $this->redirectToRoute('blog.question.user.delete',['username' => $this->getUser()->getUserIdentifier(),'id'=>$id]);
            }
        }
        $question = $entityManager->getRepository(Question::class)->find($id);
        if (!$question) {
            $this->addFlash('error', "Cette question n'existe pas");
            return $this->redirectToRoute('blog.question.user.index', ['username' => $username]);
        }
        $entityManager->remove($question);
        $entityManager->flush();
        $this->addFlash('success', 'Question supprimée avec succès');
        return $this->redirectToRoute('blog.question.user.index', ['username' => $username]);
    }
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/delete/admin/{username}', name: 'admin.delete', requirements: ['username' => '^[a-z0-9_-]{4,15}$', 'id' => Requirement::DIGITS])]
    public function adminDelete(EntityManagerInterface $entityManager, $id, $username): Response
    {
        $question = $entityManager->getRepository(Question::class)->find($id);

        if (!$question) {
            $this->addFlash('error', "Cette question n'existe pas");
            return $this->redirectToRoute('blog.question.admin.index', ['username' => $username]);
        }

        $entityManager->remove($question);
        $entityManager->flush();

        $this->addFlash('success', 'Question supprimée avec succès');

        return $this->redirectToRoute('blog.question.admin.index', ['username' => $username]);
    }
}
