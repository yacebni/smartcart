<?php

namespace App\Controller;

use App\Entity\ArticlePrevu;
use App\Form\ArticlePrevuType;
use App\Entity\ListeCourse;
use App\Form\ListeCourseType;
use App\Repository\ListeCourseRepository;
use App\Repository\ArticlePrevuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/liste/course')]
class ListeCourseController extends AbstractController
{
    #[Route('/', name: 'app_liste_course_index', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager, ListeCourseRepository $listeCourseRepository): Response
    {
        $userId = $this->getUser()->getId();
        $listeCourse = new ListeCourse();
        $form = $this->createForm(ListeCourseType::class, $listeCourse);
        $form->handleRequest($request);
        $listeCourses = $listeCourseRepository->findByUserId($userId);

        $statistics = $listeCourseRepository->getStatisticsForUser($userId);

        if ($form->isSubmitted() && $form->isValid()) {
            $listeCourse->setUser($this->getUser());
            $entityManager->persist($listeCourse);
            $entityManager->flush();

            return $this->redirectToRoute('app_liste_course_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('liste_course/index.html.twig', [
            'liste_courses' => $listeCourses,
            'form' => $form,
            'statistics' => $statistics,
        ]);
    }

    #[Route('/{id}', name: 'app_liste_course_show', methods: ['GET', 'POST'])]
    public function show(ListeCourse $listeCourse, Request $request, EntityManagerInterface $entityManager): Response
    {
        $articlePrevu = new ArticlePrevu();
        $articlePrevu->setListeCourse($listeCourse);
        $form = $this->createForm(ArticlePrevuType::class, $articlePrevu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $existingArticle = $listeCourse->getArticlesPrevus()->filter(function ($article) use ($articlePrevu) {
                return $article->getArticle() === $articlePrevu->getArticle();
            })->first();

            if ($existingArticle) {
                $existingArticle->setQuantite($existingArticle->getQuantite() + $articlePrevu->getQuantite());
            } else {
                $entityManager->persist($articlePrevu);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_liste_course_show', ['id' => $listeCourse->getId()], Response::HTTP_SEE_OTHER);
        }

        $articlesPrevus = $listeCourse->getArticlesPrevus();

        $articlesInfo = [];

        $prixTotal = 0;

        foreach ($articlesPrevus as $articlePrevu) {
            $article = $articlePrevu->getArticle();
            $nomArticle = $article ? $article->getNomArticle() : "Nom d'article non trouvé";
            $prixUnitaire = $article ? $article->getPrix() : 0;
            $quantite = $articlePrevu->getQuantite();
            $prixTotal += $quantite * $prixUnitaire;
            $image = $article ? $article->getImage() : "";

            $existingArticleIndex = null;
            foreach ($articlesInfo as $index => $info) {
                if ($info['nomArticle'] === $nomArticle) {
                    $existingArticleIndex = $index;
                    break;
                }
            }

            if ($existingArticleIndex !== null) {
                $articlesInfo[$existingArticleIndex]['quantite'] += $quantite;
                $articlesInfo[$existingArticleIndex]['prixTotal'] += $prixUnitaire * $quantite;
            } else {
                $articlesInfo[] = [
                    'nomArticle' => $nomArticle,
                    'quantite' => $quantite,
                    'prixUnitaire' => $prixUnitaire,
                    'prixTotal' => $prixUnitaire * $quantite, // Calcul du prix total
                    'statutAchat' => $articlePrevu->isStatutAchat(),
                    'articleId' => $articlePrevu->getId(), // Identifiant de l'article prévu
                    'image' => $image,
                ];
            }
        }

        return $this->render('liste_course/show.html.twig', [
            'liste_course' => $listeCourse,
            'form' => $form->createView(),
            'articles_info' => $articlesInfo,
            'prix_total' => $prixTotal,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_liste_course_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ListeCourse $listeCourse, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(ListeCourseType::class, $listeCourse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_liste_course_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('liste_course/edit.html.twig', [
            'liste_course' => $listeCourse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_liste_course_delete', methods: ['POST'])]
    public function delete(Request $request, ListeCourse $listeCourse, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $listeCourse->getId(), $request->request->get('_token'))) {
            $articlePrevus = $listeCourse->getArticlesPrevus();
            foreach ($articlePrevus as $articlePrevu) {
                $entityManager->remove($articlePrevu);
            }

            $entityManager->remove($listeCourse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_liste_course_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/toggle_purchase_status/{articleId}', name: 'app_liste_course_toggle_purchase_status', methods: ['POST'])]
    public function togglePurchaseStatus(Request $request, EntityManagerInterface $entityManager, ArticlePrevuRepository $articlePrevuRepository, $articleId): Response
    {
        $articlePrevu = $articlePrevuRepository->find($articleId);

        if (!$articlePrevu) {
            throw $this->createNotFoundException('Article prévu non trouvé.');
        }

        // Basculer le statut d'achat
        $newPurchaseStatus = !$articlePrevu->isStatutAchat();
        $articlePrevu->setStatutAchat($newPurchaseStatus);
        $entityManager->flush();

        // Rediriger vers la page précédente
        return $this->redirect($request->headers->get('referer'));
    }
}
