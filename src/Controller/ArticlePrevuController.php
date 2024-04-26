<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ArticlePrevu;
use App\Form\ArticlePrevuType;
use App\Repository\ArticlePrevuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/article/prevu')]
class ArticlePrevuController extends AbstractController
{
    /*#[Route('/', name: 'app_article_prevu_index', methods: ['GET'])]
    public function index(ArticlePrevuRepository $articlePrevuRepository): Response
    {
        return $this->render('article_prevu/index.html.twig', [
            'article_prevus' => $articlePrevuRepository->findAll(),
        ]);
    }*/

    /*#[Route('/new', name: 'app_article_prevu_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $articlePrevu = new ArticlePrevu();
        $form = $this->createForm(ArticlePrevuType::class, $articlePrevu);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($articlePrevu);
            $entityManager->flush();

            return $this->redirectToRoute('app_article_prevu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article_prevu/new.html.twig', [
            'article_prevu' => $articlePrevu,
            'form' => $form,
        ]);


    }

    #[Route('/{id}', name: 'app_article_prevu_show', methods: ['GET'])]
    public function show(ArticlePrevu $articlePrevu, EntityManagerInterface $entityManager): Response
    {
        $articleId = $articlePrevu->getArticle()->getId();

        $articleRepository = $entityManager->getRepository(Article::class);

        $query = $articleRepository->createQueryBuilder('a')
            ->where('a.id = :articleId')
            ->setParameter('articleId', $articleId)
            ->getQuery();

        $article = $query->getOneOrNullResult();

        return $this->render('article_prevu/show.html.twig', [
            'article_prevu' => $articlePrevu,
            'nom_article_prevu' => $article ? $article->getNom() : null,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_article_prevu_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ArticlePrevu $articlePrevu, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArticlePrevuType::class, $articlePrevu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_article_prevu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article_prevu/edit.html.twig', [
            'article_prevu' => $articlePrevu,
            'form' => $form,
        ]);
    }*/

    #[Route('/{id}/delete', name: 'app_article_prevu_delete', methods: ['POST'])]
    public function delete(Request $request, ArticlePrevu $articlePrevu, EntityManagerInterface $entityManager): Response
    {
        $listecourse = $articlePrevu->getListeCourse();
        $id = $listecourse->getId();

        if ($this->isCsrfTokenValid('delete' . $articlePrevu->getId(), $request->request->get('_token'))) {
            $entityManager->remove($articlePrevu);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_liste_course_show', ["id" => $id], Response::HTTP_SEE_OTHER);
    }
}
