<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\BadWordsFilter;
use App\Service\NotificationService;
use Knp\Component\Pager\PaginatorInterface;
use App\ServicePDF\PdfService;
use Twig\Environment;

#[Route('/commentaire')]
final class CommentaireController extends AbstractController
{
    private $paginator;

    // Injecter le service PaginatorInterface
    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    #[Route(name: 'app_commentaire_index', methods: ['GET'])]
    public function index(CommentaireRepository $commentaireRepository, Request $request): Response
    {
        // Récupérer les commentaires pour la pagination
        $query = $commentaireRepository->findAll(); // Remplace par une query si nécessaire pour des critères spécifiques

        // Appliquer la pagination
        $pagination = $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // Page courante (par défaut 1)
            3 // Nombre d'éléments par page
        );

        return $this->render('commentaire/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_commentaire_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request, 
        EntityManagerInterface $entityManager, 
        BadWordsFilter $badWordsFilter,
        NotificationService $notificationService // ✅ On utilise NotificationService
    ): Response {
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // 📌 Vérification du contenu du commentaire
            if ($badWordsFilter->containsBadWord($commentaire->getCommentaire())) {
                // 📌 Envoi d'une notification à l'admin
                $notificationService->sendNotification('🚨 Un commentaire impoli a été posté !');
    
                // 📌 Ferme la page (comme tu l'as demandé)
                return $this->render('error/blocked.html.twig');
            }
    
            $entityManager->persist($commentaire);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('commentaire/new.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }
    
    #[Route('/{id<\d+>}', name: 'app_commentaire_show', methods: ['GET'])]
    public function show(Commentaire $commentaire): Response
    {
        return $this->render('commentaire/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commentaire_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request, 
        Commentaire $commentaire, 
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commentaire/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    // Correction de la méthode generatePdf
    #[Route('/{id}/pdf', name: 'app_commentaire_pdf', methods: ['GET'])]
    public function generatePdf(Commentaire $commentaire, PdfService $pdfService): Response
    {
        // Rendu du template PDF pour le commentaire
        $html = $this->renderView('commentaire/pdf.html.twig', [
            'commentaire' => $commentaire,
        ]);
    
        // Génération du PDF
        $pdfContent = $pdfService->generatePdf($html);
    
        // Retourner le PDF en réponse
        return new Response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="commentaire-' . $commentaire->getId() . '.pdf"',
        ]);
    }

    #[Route('/{id}', name: 'app_commentaire_delete', methods: ['POST'])]
    public function delete(
        Request $request, 
        Commentaire $commentaire, 
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $commentaire->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/search', name: 'app_commentaire_search', methods: ['GET'])]
    public function search(Request $request, CommentaireRepository $commentaireRepository, PaginatorInterface $paginator): Response
    {
        // Récupérer l'ID depuis la requête
        $searchId = $request->query->get('id');
        $queryBuilder = $commentaireRepository->createQueryBuilder('c'); // Utiliser le repository des commentaires

        // Si l'ID est fourni, appliquer un filtre pour rechercher par ID
        if ($searchId) {
            // Vérifier que l'ID est valide
            if (!is_numeric($searchId)) {
                throw $this->createNotFoundException('L\'ID doit être un nombre valide.');
            }
            
            $queryBuilder->andWhere('c.id = :id')
                         ->setParameter('id', (int)$searchId); // Filtrer par l'ID
        }

        // Paginer les résultats
        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(), // Utiliser la requête
            $request->query->getInt('page', 1), // Obtenir la page courante (par défaut : 1)
            5 // Nombre d'éléments par page
        );

        return $this->render('commentaire/index.html.twig', [
            'pagination' => $pagination,
            'searchId' => $searchId ?? null, // Passer l'ID de recherche au template
        ]);
    }
}
