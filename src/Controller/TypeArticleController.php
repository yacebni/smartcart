<?php

namespace App\Controller;

use App\Entity\TypeArticle;
use App\Form\TypeArticleType;
use App\Repository\TypeArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/type/article')]
class TypeArticleController extends AbstractController
{
    #[Route('/', name: 'app_type_article_index', methods: ['GET'])]
    public function index(TypeArticleRepository $typeArticleRepository): Response
    {
        return $this->render('type_article/index.html.twig', [
            'type_articles' => $typeArticleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_type_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $typeArticle = new TypeArticle();
        $form = $this->createForm(TypeArticleType::class, $typeArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($typeArticle);
            $entityManager->flush();

            return $this->redirectToRoute('app_type_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('type_article/new.html.twig', [
            'type_article' => $typeArticle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_article_show', methods: ['GET'])]
    public function show(TypeArticle $typeArticle): Response
    {
        return $this->render('type_article/show.html.twig', [
            'type_article' => $typeArticle,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_type_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TypeArticle $typeArticle, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TypeArticleType::class, $typeArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_type_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('type_article/edit.html.twig', [
            'type_article' => $typeArticle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_article_delete', methods: ['POST'])]
    public function delete(Request $request, TypeArticle $typeArticle, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $typeArticle->getId(), $request->request->get('_token'))) {
            $entityManager->remove($typeArticle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_type_article_index', [], Response::HTTP_SEE_OTHER);
    }
}
