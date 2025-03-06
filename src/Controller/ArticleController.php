<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Commentaire;
use App\Form\ArticleType;
use App\Form\CommentaireType;
use App\Repository\ArticleRepository;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;  // Assurez-vous d'inclure la classe Twig
use App\ServicePDF\PdfService;

#[Route('/article')]
final class ArticleController extends AbstractController
{
    #[Route(name: 'app_article_index', methods: ['GET'])]
    public function index(
        ArticleRepository $articleRepository, 
        PaginatorInterface $paginator, 
        Request $request
    ): Response {
        // Récupérer la requête depuis le repository Article
        $query = $articleRepository->createQueryBuilder('a')->getQuery();

        // Appliquer la pagination
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // Page courante (par défaut 1)
            3 // Nombre d'éléments par page
        );

        return $this->render('article/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/indexfront', name: 'app_article_indexfront', methods: ['GET'])]
    public function indexfront(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/indexfront.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id<\d+>}', name: 'app_article_show', methods: ['GET'])]
    public function show(int $id, ArticleRepository $articleRepository): Response
    {
        // Vérifier si l'ID est un entier
        if (!is_numeric($id)) {
            throw $this->createNotFoundException('L\'ID doit être un nombre valide.');
        }

        // Récupérer l'article en fonction de l'ID
        $article = $articleRepository->find((int)$id);

        // Si l'article n'existe pas, générer une erreur 404
        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/{id}/pdf', name: 'app_article_pdf', methods: ['GET'])]
    public function generatePdf(Article $article, PdfService $pdfService): Response
    {
        // Rendu du template PDF pour l'article
        $html = $this->renderView('article/pdf.html.twig', [
            'article' => $article,
        ]);
    
        // Génération du PDF
        $pdfContent = $pdfService->generatePdf($html);
    
        // Retourner le PDF en réponse
        return new Response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="article-' . $article->getId() . '.pdf"',
        ]);
    }

    #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        // Vérification du token CSRF
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/comment', name: 'app_add_commentairetoarticle', methods: ['GET', 'POST'])]
    public function addComment(
        Request $request,
        Article $article,
        EntityManagerInterface $entityManager
    ): Response {
        // Créer une nouvelle entité Commentaire
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Associer l'article au commentaire et définir la date
            $commentaire->setArticle($article);
            $commentaire->setDatecommentaire(new \DateTime());

            // Persister et enregistrer l'entité commentaire
            $entityManager->persist($commentaire);
            $entityManager->flush();

            // Ajouter un message de succès
            $this->addFlash('success', 'Votre commentaire a été ajouté !');

            // Rediriger vers la page de l'article
            return $this->redirectToRoute('app_article_show', ['id' => $article->getId()]);
        }

        // Rendre la page de l'article avec le formulaire de commentaire
        return $this->render('article/showcomment.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/search', name: 'app_article_search', methods: ['GET'])]
    public function search(Request $request, ArticleRepository $articleRepository, PaginatorInterface $paginator): Response
    {
        // Récupérer l'ID depuis la requête
        $searchId = $request->query->get('id');
        $queryBuilder = $articleRepository->createQueryBuilder('a');

        // Si l'ID est fourni, appliquer un filtre pour rechercher par ID
        if ($searchId) {
            // Vérifier que l'ID est valide
            if (!is_numeric($searchId)) {
                throw $this->createNotFoundException('L\'ID doit être un nombre valide.');
            }
            
            $queryBuilder->andWhere('a.id = :id')
                         ->setParameter('id', (int)$searchId); // Filtrer par l'ID
        }

        // Paginer les résultats
        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(), // Utiliser la requête
            $request->query->getInt('page', 1), // Obtenir la page courante (par défaut : 1)
            5 // Nombre d'éléments par page
        );

        return $this->render('article/index.html.twig', [
            'pagination' => $pagination,
            'searchId' => $searchId ?? null, // Passer l'ID de recherche au template
        ]);
    }
}
