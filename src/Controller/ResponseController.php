<?php
namespace App\Controller;
use App\Entity\Question;
use App\Entity\User;
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
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('blog/responses/question', name: 'blog.response.')]
class ResponseController extends AbstractController
{
    #[Route('/{id}/show/{username}', name: 'question.index', requirements: ['id' => Requirement::DIGITS])]
    public function questionResponses(int $id, ResponseRepository $responseRepository, QuestionRepository $questionRepository, string $username): Response
    {
        $question = $questionRepository->find($id);
        if (!$question) {
            $this->addFlash('error','Question not Found');
            return $this->redirectToRoute('blog.question.default.index');
        }
        $responses = $responseRepository->findBy(['question' => $question]);
        return $this->render('response/question.html.twig', [
            'question' => $question,
            'responses' => $responses,
            'username' => $username,
        ]);
    }
    #[Route('/{id}/default/answer', name: 'create.default')]
    public function createIndex(EntityManagerInterface $entityManager, int $id)
    {
        if($this->getUser()){
            $roles = $this->getUser()->getRoles();
            if(in_array('ROLE_USER',$roles))
            {
                if(in_array('ROLE_ADMIN',$roles)) {
                    return $this->redirectToRoute('blog.response.create.admin',['username' => $this->getUser()->getUserIdentifier(),'id'=>$id]);
                }
                return $this->redirectToRoute('blog.response.create.user',['username' => $this->getUser()->getUserIdentifier(),'id'=>$id]);
            }
        }
        $this->addFlash("error","Vous n'avez pas le droit de repondre sans vous connecter");
       return  $this->redirectToRoute('app_login');
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/{id}/user/{username}/answer', name: 'create.user')]
    public function create(Request $request, QuestionRepository $questionRepository, EntityManagerInterface $entityManager, int $id, String $username): Response
    {
        if($this->getUser()){
            $roles = $this->getUser()->getRoles();
            if(in_array('ROLE_USER',$roles))
            {
                if(in_array('ROLE_ADMIN',$roles)) {
                    return $this->redirectToRoute('blog.response.create.admin',['username' => $this->getUser()->getUserIdentifier(),'id'=>$id]);
                }
               // return $this->redirectToRoute('blog.response.create.user',['username' => $this->getUser()->getUserIdentifier(),'id'=>$id]);
            }
        }
        $question = $questionRepository->find($id);
        if (!$question) {
            $this->addFlash('error','Question not found');
            return $this->redirectToRoute('blog.response.question.index', ['id' => $id , 'username' => $username]);
        }
        $response = new Resp();
        $response->setQuestion($question);
        $form = $this->createForm(ResponseType::class, $response);
        $form->handleRequest($request);
        $creator = $entityManager->getRepository(User::class)->findBy(['username' => $username]);
        if(!$creator){
                $this->addFlash("error","Vous n'avez pas le droit de repondre sans vous connecter");
              return  $this->redirectToRoute('app_login');
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $response->setCreator($creator[0]);
            $response->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($response);
            $entityManager->flush();
            $this->addFlash('success', 'Réponse ajoutée avec succès.');
            return $this->redirectToRoute('blog.response.question.index', [ 'username' => $username,'id'=>$question->getId() ]);
        }
        return $this->render('response/create.html.twig', [
            'form' => $form,
            'question' => $question,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/admin/{username}/answer', name: 'create.admin')]
    public function createA(Request $request, QuestionRepository $questionRepository, EntityManagerInterface $entityManager, int $id, String $username): Response
    {
        $question = $questionRepository->find($id);
        if (!$question) {
            $this->addFlash('error','Question not found');
            return $this->redirectToRoute('blog.response.question.index', ['id' => $id , 'username' => $username]);
        }
        $response = new Resp();
        $response->setQuestion($question);
        $form = $this->createForm(ResponseType::class, $response);
        $form->handleRequest($request);
        $creator = $entityManager->getRepository(User::class)->findOneBy(['username'=> $username]);
        if (!$creator) {
            $this->addFlash("error", "Utilisateur non trouvé");
            return $this->redirectToRoute('app_login');
        }
        $response->setCreator($creator);

        if ($form->isSubmitted() && $form->isValid()) {
            $response->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($response);
            $entityManager->flush();
            $this->addFlash('success', 'Réponse ajoutée avec succès.');
            return $this->redirectToRoute('blog.response.question.index', [ 'username' => $username,'id'=>$question->getId() ]);
        }
        return $this->render('response/create.html.twig', [
            'form' => $form,
            'question' => $question,
        ]);
    }
}