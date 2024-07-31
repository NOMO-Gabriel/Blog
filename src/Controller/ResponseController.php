<?php

namespace App\Controller;


use App\Entity\Question;
use App\Form\ResponseType;
use App\Repository\QuestionRepository;
use App\Repository\ResponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Response as Resp;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('blog/responses', name: 'blog.response.')]
class ResponseController extends AbstractController
{

    #[Route('/question/{id}', name:'question.index',requirements: ['id'=> Requirement::DIGITS])]
    public function questionResponses(int $id, ResponseRepository $responseRepository, QuestionRepository $questionRepository): Response
    {
        $question = $questionRepository->find($id);
        if (!$question) {
            $this->addFlash('Question not Found');
            return  $this->redirectToRoute('blog.questions.default.index');
        }

        $responses = $responseRepository->findBy(['question' => $question]);

        return $this->render('response/question.html.twig', [
            'question' => $question,
            'responses' => $responses,
        ]);
    }

    #[Route('/question/{$questionId}',name:'default.create')]
        public function create(Request $request, QuestionRepository $questionRepository, EntityManagerInterface $entityManager, int $questionId): Response
        {
            $question = $questionRepository->find($questionId);
            if (!$question) {
               $this->addFlash('Question not found');
            }

            $response = new Resp();
            $response->setQuestion($question);

            $form = $this->createForm(ResponseType::class, $response);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($response);
                $entityManager->flush();

                $this->addFlash('success', 'Réponse ajoutée avec succès.');

                return $this->redirectToRoute('blog.response.question.index', ['id' => $questionId]);
            }

            return $this->render('response/create.html.twig', [
                'form' => $form->createView(),
                'question' => $question,
            ]);
        }
//    // Routes pour voir les réponses
//    #[Route('/all/{filter}', name: 'default.index', requirements: ['filter' => 'all|question_[0-9]+'])]
//    public function index(EntityManagerInterface $entityManager, $filter = 'all'): Response
//    {
//        $questions = $entityManager->getRepository(Question::class)->findAll();
//
//        if ($filter === 'all') {
//            $questionTitle = 'aucune question';
//            $responses = $entityManager->getRepository(Resp::class)->findAll();
//        } else {
//            $questionId = (int) str_replace('question_', '', $filter);
//            $responses = $entityManager->getRepository(Resp::class)->findBy(['question' => $questionId]);
//            $questionTitle = $entityManager->find(Question::class, $questionId)->getTitle();
//        }
//
//        return $this->render('response/index.html.twig', [
//            'responses' => $responses,
//            'questions' => $questions,
//            'activeFilter' => $filter,
//            'questionTitle' => $questionTitle,
//        ]);
//    }
//
//    #[Route('/user/{username}/all', name: 'user.index', requirements: ['username' => '^[a-z0-9_-]{4,15}$'])]
//    public function userIndex(EntityManagerInterface $entityManager, $username): Response
//    {
//        $responses = $entityManager->getRepository(Resp::class)->findAll();
//
//        return $this->render('response/user/index.html.twig', [
//            'responses' => $responses,
//            'username' => $username,
//        ]);
//    }
//
//    #[Route('/admin/{username}/all', name: 'admin.index', requirements: ['username' => '^[a-z0-9_-]{4,15}$'])]
//    public function adminIndex(EntityManagerInterface $entityManager, $username): Response
//    {
//        $responses = $entityManager->getRepository(Resp::class)->findAll();
//
//        return $this->render('response/admin/index.html.twig', [
//            'responses' => $responses,
//            'username' => $username,
//        ]);
//    }
//
//    // Routes pour voir une seule réponse
//    #[Route('/{id}/show', name: 'default.show', requirements: ['id' => Requirement::DIGITS])]
//    public function show(EntityManagerInterface $entityManager, $id): Response
//    {
//        $response = $entityManager->getRepository(Resp::class)->find($id);
//
//        if (!$response) {
//            $this->addFlash('error', "Cette réponse n'existe pas");
//            return $this->redirectToRoute('blog.response.default.index');
//        }
//
//        return $this->render('response/show.html.twig', [
//            'response' => $response,
//        ]);
//    }
//
//    #[Route('/{id}/show/user/{username}', name: 'user.show', requirements: ['username' => '^[a-z0-9_-]{4,15}$', 'id' => Requirement::DIGITS])]
//    public function userShow(EntityManagerInterface $entityManager, $id, $username): Response
//    {
//        $response = $entityManager->getRepository(Resp::class)->find($id);
//
//        if (!$response) {
//            $this->addFlash('error', "Cette réponse n'existe pas");
//            return $this->redirectToRoute('blog.response.user.index', [
//                'username' => $username,
//            ]);
//        }
//
//        return $this->render('response/user/show.html.twig', [
//            'response' => $response,
//            'username' => $username,
//        ]);
//    }
//
//    #[Route('/{id}/show/admin/{username}', name: 'admin.show', requirements: ['username' => '^[a-z0-9_-]{4,15}$', 'id' => Requirement::DIGITS])]
//    public function adminShow(EntityManagerInterface $entityManager, $id, $username): Response
//    {
//        $response = $entityManager->getRepository(Resp::class)->find($id);
//
//        if (!$response) {
//            $this->addFlash('error', "Cette réponse n'existe pas");
//            return $this->redirectToRoute('blog.response.admin.index', [
//                'username' => $username,
//            ]);
//        }
//
//        return $this->render('response/admin/show.html.twig', [
//            'response' => $response,
//            'username' => $username,
//        ]);
//    }
//
//    // Routes pour poser une réponse
//    #[Route('/ask', name: 'default.create')]
//    public function create(Request $request, EntityManagerInterface $entityManager): Response
//    {
//        $response = new Resp();
//        $form = $this->createForm(ResponseType::class, $response);
//
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//            $response->setCreatedAt(new \DateTimeImmutable());
//            $response->setUpdatedAt(new \DateTimeImmutable());
//            $entityManager->persist($response);
//            $entityManager->flush();
//
//            $this->addFlash('success', 'Réponse ajoutée avec succès');
//
//            return $this->redirectToRoute('blog.response.default.index');
//        }
//
//        return $this->render('response/create.html.twig', [
//            'form' => $form->createView(),
//        ]);
//    }
//
//    #[Route('/ask/user/{username}', name: 'user.create', requirements: ['username' => '^[a-z0-9_-]{4,15}$'])]
//    public function userCreate(Request $request, EntityManagerInterface $entityManager, $username): Response
//    {
//        $response = new Response();
//        $form = $this->createForm(ResponseType::class, $response);
//
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//            $response->setCreatedAt(new \DateTimeImmutable());
//            $response->setUpdatedAt(new \DateTimeImmutable());
//            $entityManager->persist($response);
//            $entityManager->flush();
//
//            $this->addFlash('success', 'Réponse ajoutée avec succès');
//
//            return $this->redirectToRoute('blog.response.user.index', ['username' => $username]);
//        }
//
//        return $this->render('response/user/create.html.twig', [
//            'form' => $form->createView(),
//            'username' => $username,
//        ]);
//    }
//
//    #[Route('/ask/admin/{username}', name: 'admin.create', requirements: ['username' => '^[a-z0-9_-]{4,15}$'])]
//    public function adminCreate(Request $request, EntityManagerInterface $entityManager, $username): Response
//    {
//        $response = new Response();
//        $form = $this->createForm(ResponseType::class, $response);
//
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//            $response->setCreatedAt(new \DateTimeImmutable());
//            $response->setUpdatedAt(new \DateTimeImmutable());
//            $entityManager->persist($response);
//            $entityManager->flush();
//
//            $this->addFlash('success', 'Réponse ajoutée avec succès');
//
//            return $this->redirectToRoute('blog.response.admin.index', ['username' => $username]);
//        }
//
//        return $this->render('response/admin/create.html.twig', [
//            'form' => $form->createView(),
//            'username' => $username,
//        ]);
//    }
//
//    // Routes pour modifier une réponse
//    #[Route('/{id}/edit', name: 'default.edit', requirements: ['id' => Requirement::DIGITS])]
//    public function edit(Request $request, EntityManagerInterface $entityManager, $id): Response
//    {
//        $response = $entityManager->getRepository(Response::class)->find($id);
//
//        if (!$response) {
//            $this->addFlash('error', "Cette réponse n'existe pas");
//            return $this->redirectToRoute('blog.response.default.index');
//        }
//
//        $form = $this->createForm(ResponseType::class, $response);
//
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager->flush();
//
//            $this->addFlash('success', 'Réponse modifiée avec succès');
//
//            return $this->redirectToRoute('blog.response.default.index');
//        }
//
//        return $this->render('response/edit.html.twig', [
//            'form' => $form->createView(),
//        ]);
//    }
//
//    #[Route('/{id}/edit/user/{username}', name: 'user.edit', requirements: ['username' => '^[a-z0-9_-]{4,15}$', 'id' => Requirement::DIGITS])]
//    public function userEdit(Request $request, EntityManagerInterface $entityManager, $id, $username): Response
//    {
//        $response = $entityManager->getRepository(Response::class)->find($id);
//
//        if (!$response) {
//            $this->addFlash('error', "Cette réponse n'existe pas");
//            return $this->redirectToRoute('blog.response.user.index', ['username' => $username]);
//        }
//
//        $form = $this->createForm(ResponseType::class, $response);
//
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager->flush();
//
//            $this->addFlash('success', 'Réponse modifiée avec succès');
//
//            return $this->redirectToRoute('blog.response.user.index', ['username' => $username]);
//        }
//
//        return $this->render('response/user/edit.html.twig', [
//            'form' => $form->createView(),
//            'username' => $username,
//        ]);
//    }
//
//    #[Route('/{id}/edit/admin/{username}', name: 'admin.edit', requirements: ['username' => '^[a-z0-9_-]{4,15}$', 'id' => Requirement::DIGITS])]
//    public function adminEdit(Request $request, EntityManagerInterface $entityManager, $id, $username): Response
//    {
//        $response = $entityManager->getRepository(Response::class)->find($id);
//
//        if (!$response) {
//            $this->addFlash('error', "Cette réponse n'existe pas");
//            return $this->redirectToRoute('blog.response.admin.index', ['username' => $username]);
//        }
//
//        $form = $this->createForm(ResponseType::class, $response);
//
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager->flush();
//
//            $this->addFlash('success', 'Réponse modifiée avec succès');
//
//            return $this->redirectToRoute('blog.response.admin.index', ['username' => $username]);
//        }
//
//        return $this->render('response/admin/edit.html.twig', [
//            'form' => $form->createView(),
//            'username' => $username,
//        ]);
//    }
//
//    // Routes pour supprimer une réponse
//    #[Route('/{id}/delete', name: 'default.delete', requirements: ['id' => Requirement::DIGITS])]
//    public function delete(Request $request, EntityManagerInterface $entityManager, $id): Response
//    {
//        $response = $entityManager->getRepository(Response::class)->find($id);
//
//        if (!$response) {
//            $this->addFlash('error', "Cette réponse n'existe pas");
//            return $this->redirectToRoute('blog.response.default.index');
//        }
//
//        if ($this->isCsrfTokenValid('delete'.$response->getId(), $request->request->get('_token'))) {
//            $entityManager->remove($response);
//            $entityManager->flush();
//            $this->addFlash('success', 'Réponse supprimée avec succès');
//        }
//
//        return $this->redirectToRoute('blog.response.default.index');
//    }
//
//    #[Route('/{id}/delete/user/{username}', name: 'user.delete', requirements: ['username' => '^[a-z0-9_-]{4,15}$', 'id' => Requirement::DIGITS])]
//    public function userDelete(Request $request, EntityManagerInterface $entityManager, $id, $username): Response
//    {
//        $response = $entityManager->getRepository(Response::class)->find($id);
//
//        if (!$response) {
//            $this->addFlash('error', "Cette réponse n'existe pas");
//            return $this->redirectToRoute('blog.response.user.index', ['username' => $username]);
//        }
//
//        if ($this->isCsrfTokenValid('delete'.$response->getId(), $request->request->get('_token'))) {
//            $entityManager->remove($response);
//            $entityManager->flush();
//            $this->addFlash('success', 'Réponse supprimée avec succès');
//        }
//
//        return $this->redirectToRoute('blog.response.user.index', ['username' => $username]);
//    }
//
//    #[Route('/{id}/delete/admin/{username}', name: 'admin.delete', requirements: ['username' => '^[a-z0-9_-]{4,15}$', 'id' => Requirement::DIGITS])]
//    public function adminDelete(Request $request, EntityManagerInterface $entityManager, $id, $username): Response
//    {
//        $response = $entityManager->getRepository(Response::class)->find($id);
//
//        if (!$response) {
//            $this->addFlash('error', "Cette réponse n'existe pas");
//            return $this->redirectToRoute('blog.response.admin.index', ['username' => $username]);
//        }
//
//        if ($this->isCsrfTokenValid('delete'.$response->getId(), $request->request->get('_token'))) {
//            $entityManager->remove($response);
//            $entityManager->flush();
//            $this->addFlash('success', 'Réponse supprimée avec succès');
//        }
//
//        return $this->redirectToRoute('blog.response.admin.index', ['username' => $username]);
//    }
}
