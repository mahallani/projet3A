<?php

namespace App\Controller;

use App\Entity\Traitement;
use App\Form\TraitementType;
use App\Repository\TraitementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Service\PdfService;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/traitement')]
final class TraitementController extends AbstractController
{
    #[Route('/', name: 'app_traitement_index', methods: ['GET'])]
    public function index(Request $request, TraitementRepository $traitementRepository, PaginatorInterface $paginator, TranslatorInterface $translator): Response
    {
        // Get sorting parameters from the request
        $sort = $request->query->get('sort', 'datePrescription'); // Default to sorting by datePrescription
        $direction = $request->query->get('direction', 'desc'); // Default to descending order

        // Prevent invalid sorting field
        if (!in_array($sort, ['id', 'datePrescription', 'historiqueTraitement', 'ordonnance'])) {
            $sort = 'datePrescription'; // Fallback to datePrescription if invalid
        }

        // Build the query with sorting
        $query = $traitementRepository->createQueryBuilder('t')
            ->leftJoin('t.ordonnance', 'o')
            ->addSelect('o') // Ensure ordonnance is included
            ->orderBy("t.$sort", $direction) // Apply sorting
            ->getQuery();

        // Paginate the result
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // Get the current page (default to page 1)
            5 // Number of items per page
        );

        // Get translated labels for the table headers
        $translatedLabels = (new Traitement())->getTranslatedLabels($translator);

        return $this->render('traitement/index.html.twig', [
            'pagination' => $pagination,
            'sort' => $sort,
            'direction' => $direction,
            'searchId' => null,
            'translatedLabels' => $translatedLabels, // Pass translated labels to the template
        ]);
    }

    #[Route('/search', name: 'app_traitement_search', methods: ['GET'])]
    public function search(Request $request, TraitementRepository $traitementRepository, PaginatorInterface $paginator, TranslatorInterface $translator): Response
    {
        $searchId = $request->query->get('id');
        $queryBuilder = $traitementRepository->createQueryBuilder('t')
            ->leftJoin('t.ordonnance', 'o')
            ->addSelect('o'); // Ensure ordonnance is included

        if ($searchId) {
            $queryBuilder->andWhere('t.id = :id')
                         ->setParameter('id', $searchId); // Filter by ID
        }

        // Get sorting parameters from the request
        $sort = $request->query->get('sort', 'datePrescription'); // Default to sorting by datePrescription
        $direction = $request->query->get('direction', 'desc'); // Default to descending order

        // Prevent invalid sorting field
        if (!in_array($sort, ['id', 'datePrescription', 'historiqueTraitement', 'ordonnance'])) {
            $sort = 'datePrescription'; // Fallback to datePrescription if invalid
        }

        // Apply sorting to the query
        $queryBuilder->orderBy("t.$sort", $direction);

        // Paginate the result
        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(), // Use the query
            $request->query->getInt('page', 1), // Get current page (default: 1)
            5 // Number of items per page
        );

        // Get translated labels for the table headers
        $translatedLabels = (new Traitement())->getTranslatedLabels($translator);

        return $this->render('traitement/index.html.twig', [
            'pagination' => $pagination,
            'searchId' => $searchId ?? null, // Pass the search ID to the template
            'sort' => $sort,
            'direction' => $direction,
            'translatedLabels' => $translatedLabels, // Pass translated labels to the template
        ]);
    }

    #[Route('/change-locale', name: 'change_locale', methods: ['POST'])]
    public function changeLocale(Request $request): Response
    {
        // Get the selected locale from the request
        $locale = $request->request->get('locale');

        // Validate the locale (optional)
        if (!in_array($locale, ['en', 'fr'])) {
            $locale = 'en'; // Fallback to default locale
        }

        // Set the locale in the session
        $request->getSession()->set('_locale', $locale);

        // Redirect to the previous page
        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/new', name: 'app_traitement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator): Response
    {
        $traitement = new Traitement();
        $form = $this->createForm(TraitementType::class, $traitement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($traitement);
            $entityManager->flush();

            return $this->redirectToRoute('app_traitement_index');
        }

        // Get translated labels for the form
        $translatedLabels = $traitement->getTranslatedLabels($translator);

        return $this->render('traitement/new.html.twig', [
            'traitement' => $traitement,
            'form' => $form->createView(),
            'translatedLabels' => $translatedLabels, // Pass translated labels to the template
        ]);
    }

    #[Route('/{id}', name: 'app_traitement_show', methods: ['GET'])]
    public function show(Traitement $traitement, TranslatorInterface $translator): Response
    {
        // Get translated labels for the show page
        $translatedLabels = $traitement->getTranslatedLabels($translator);

        return $this->render('traitement/show.html.twig', [
            'traitement' => $traitement,
            'translatedLabels' => $translatedLabels, // Pass translated labels to the template
        ]);
    }

    #[Route('/{id}/edit', name: 'app_traitement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Traitement $traitement, EntityManagerInterface $entityManager, TranslatorInterface $translator): Response
    {
        $form = $this->createForm(TraitementType::class, $traitement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_traitement_index');
        }

        // Get translated labels for the edit page
        $translatedLabels = $traitement->getTranslatedLabels($translator);

        return $this->render('traitement/edit.html.twig', [
            'traitement' => $traitement,
            'form' => $form->createView(),
            'translatedLabels' => $translatedLabels, // Pass translated labels to the template
        ]);
    }

    #[Route('/{id}', name: 'app_traitement_delete', methods: ['POST'])]
    public function delete(Request $request, Traitement $traitement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $traitement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($traitement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_traitement_index');
    }

    #[Route('/{id}/pdf', name: 'app_traitement_pdf', methods: ['GET'])]
public function generatePdf(Traitement $traitement, PdfService $pdfService): Response
{
    // Get the associated Ordonnance
    $ordonnance = $traitement->getOrdonnance();

    if (!$ordonnance) {
        throw $this->createNotFoundException('No associated Ordonnance found for this Traitement.');
    }

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