<?php

namespace App\Controller;

use App\Entity\Ordonnance;
use App\Form\OrdonnanceType;
use App\Repository\OrdonnanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Service\PdfService;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/ordonnance')]
final class OrdonnanceController extends AbstractController
{
    #[Route('/', name: 'app_ordonnance_index', methods: ['GET'])]
    public function index(Request $request, OrdonnanceRepository $ordonnanceRepository, PaginatorInterface $paginator, TranslatorInterface $translator): Response
    {
        // Get sorting parameters from the request
        $sort = $request->query->get('sort', 'date_prescription'); // Default sort by date_prescription
        $direction = $request->query->get('direction', 'desc'); // Default to descending order

        // Prevent invalid sorting field
        if (!in_array($sort, ['id', 'date_prescription', 'medicament', 'posologie'])) {
            $sort = 'date_prescription'; // Fallback to date_prescription if invalid
        }

        // Build the query with sorting
        $query = $ordonnanceRepository->createQueryBuilder('o')
            ->orderBy("o.$sort", $direction) // Apply sorting
            ->getQuery();

        // Paginate the result
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // Get the current page (default to page 1)
            5 // Number of items per page
        );

        // Get translated labels for the table headers
        $translatedLabels = (new Ordonnance())->getTranslatedLabels($translator);

        return $this->render('ordonnance/index.html.twig', [
            'pagination' => $pagination,
            'sort' => $sort,
            'direction' => $direction,
            'searchId' => null,
            'translatedLabels' => $translatedLabels, // Pass translated labels to the template
        ]);
    }

    #[Route('/search', name: 'app_ordonnance_search', methods: ['GET'])]
public function search(Request $request, OrdonnanceRepository $ordonnanceRepository, PaginatorInterface $paginator, TranslatorInterface $translator): Response
{
    $searchId = $request->query->get('id');
    $queryBuilder = $ordonnanceRepository->createQueryBuilder('o');

    if ($searchId) {
        $queryBuilder->andWhere('o.id = :id')
                     ->setParameter('id', $searchId); // Filter by ID
    }

    // Get sorting parameters from the request
    $sort = $request->query->get('sort', 'date_prescription'); // Default sort by date_prescription
    $direction = $request->query->get('direction', 'desc'); // Default to descending order

    // Prevent invalid sorting field
    if (!in_array($sort, ['id', 'date_prescription', 'medicament', 'posologie'])) {
        $sort = 'date_prescription'; // Fallback to date_prescription if invalid
    }

    // Apply sorting to the query
    $queryBuilder->orderBy("o.$sort", $direction);

    // Paginate the result
    $pagination = $paginator->paginate(
        $queryBuilder->getQuery(), // Use the query
        $request->query->getInt('page', 1), // Get current page (default: 1)
        5 // Number of items per page
    );

    // Get translated labels for the table headers
    $translatedLabels = (new Ordonnance())->getTranslatedLabels($translator);

    return $this->render('ordonnance/index.html.twig', [
        'pagination' => $pagination,
        'searchId' => $searchId ?? null, // Pass the search ID to the template
        'sort' => $sort, // Pass the sort field to the template
        'direction' => $direction, // Pass the sort direction to the template
        'translatedLabels' => $translatedLabels, // Pass translated labels to the template
    ]);
}

    #[Route('/new', name: 'app_ordonnance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator): Response
    {
        $ordonnance = new Ordonnance();
        $form = $this->createForm(OrdonnanceType::class, $ordonnance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ordonnance);
            $entityManager->flush();

            return $this->redirectToRoute('app_ordonnance_index');
        }

        // Get translated labels for the form
        $translatedLabels = $ordonnance->getTranslatedLabels($translator);

        return $this->render('ordonnance/new.html.twig', [
            'form' => $form->createView(),
            'translatedLabels' => $translatedLabels, // Pass translated labels to the template
        ]);
    }

    #[Route('/{id}', name: 'app_ordonnance_show', methods: ['GET'])]
    public function show(Ordonnance $ordonnance, TranslatorInterface $translator): Response
    {
        // Get translated labels for the show page
        $translatedLabels = $ordonnance->getTranslatedLabels($translator);

        return $this->render('ordonnance/show.html.twig', [
            'ordonnance' => $ordonnance,
            'translatedLabels' => $translatedLabels, // Pass translated labels to the template
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ordonnance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ordonnance $ordonnance, EntityManagerInterface $entityManager, TranslatorInterface $translator): Response
    {
        $form = $this->createForm(OrdonnanceType::class, $ordonnance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_ordonnance_index');
        }

        // Get translated labels for the edit page
        $translatedLabels = $ordonnance->getTranslatedLabels($translator);

        return $this->render('ordonnance/edit.html.twig', [
            'form' => $form->createView(),
            'translatedLabels' => $translatedLabels, // Pass translated labels to the template
        ]);
    }

    #[Route('/{id}', name: 'app_ordonnance_delete', methods: ['POST'])]
    public function delete(Request $request, Ordonnance $ordonnance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $ordonnance->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ordonnance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ordonnance_index');
    }

    #[Route('/{id}/pdf', name: 'app_ordonnance_pdf', methods: ['GET'])]
    public function generatePdf(Ordonnance $ordonnance, PdfService $pdfService): Response
    {
        // Render the PDF template for the Ordonnance
        $html = $this->renderView('ordonnance/pdf.html.twig', [
            'ordonnance' => $ordonnance,
        ]);

        // Generate the PDF
        $pdfContent = $pdfService->generatePdf($html);

        // Return the PDF as a response
        return new Response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="ordonnance-' . $ordonnance->getId() . '.pdf"',
        ]);
    }
}