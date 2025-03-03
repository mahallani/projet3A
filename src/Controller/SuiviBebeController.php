<?php

namespace App\Controller;
use App\Repository\SuiviGrossesseRepository;
use App\Entity\SuiviBebe;
use App\Form\SuiviBebeType;
use App\Repository\SuiviBebeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;


#[Route('/suivi/bebe')]
final class SuiviBebeController extends AbstractController
{
    #[Route('/indexfront', name: 'app_suivi_bebe_index', methods: ['GET'])]
public function index(
    SuiviBebeRepository $suiviBebeRepository,
    SuiviGrossesseRepository $suiviGrossesseRepo,
    Security $security
): Response {
    // Get the logged-in user
    $user = $security->getUser();

    // Ensure the user is authenticated
    if (!$user) {
        throw $this->createAccessDeniedException('You must be logged in.');
    }

    // Find the pregnancy (SuiviGrossesse) related to this patient
    $suiviGrossesse = $suiviGrossesseRepo->findOneBy(['patient' => $user]);

    if (!$suiviGrossesse) {
        return $this->render('suivi_bebe/indexfront.html.twig', [
            'suivi_bebes' => [],
            'suivi_grossesse' => null,
            'message' => 'Aucune grossesse trouvée pour votre profil.',
        ]);
    }

    // Fetch baby follow-ups (SuiviBebe) linked to this pregnancy
    $suiviBebes = $suiviBebeRepository->findBy(['suiviGrossesse' => $suiviGrossesse]);

    return $this->render('suivi_bebe/indexfront.html.twig', [
        'suivi_bebes' => $suiviBebes,
        'suivi_grossesse' => $suiviGrossesse,
    ]);
}

    
    #[Route('/indexback/{suiviGrossesseId}', name: 'app_suivi_bebe_indexback', methods: ['GET'])]
    public function indexback(
        SuiviBebeRepository $suiviBebeRepository, 
        SuiviGrossesseRepository $suiviGrossesseRepo, 
        int $suiviGrossesseId
    ): Response {
        // Récupérer le suivi grossesse sélectionné
        $suiviGrossesse = $suiviGrossesseRepo->find($suiviGrossesseId);
        if (!$suiviGrossesse) {
            throw $this->createNotFoundException('Suivi grossesse introuvable.');
        }
    
        // Récupérer les suivis bébés liés au suivi grossesse sélectionné
        $suiviBebes = $suiviBebeRepository->findBy(['suiviGrossesse' => $suiviGrossesse]);
    
        return $this->render('suivi_bebe/indexback.html.twig', [
            'suivi_grossesse' => $suiviGrossesse,
            'suivi_bebes' => $suiviBebes,
        ]);
    }
    
    
    


    #[Route('/suivi/bebe/new/{suiviGrossesseId}', name: 'app_suivi_bebe_new', methods: ['GET', 'POST'])]
public function new(
    Request $request, 
    EntityManagerInterface $entityManager, 
    SuiviGrossesseRepository $suiviGrossesseRepo, 
    int $suiviGrossesseId
): Response {
    // Vérifier que l'ID est bien fourni et existe en base
    $suiviGrossesse = $suiviGrossesseRepo->find($suiviGrossesseId);
    if (!$suiviGrossesse) {
        throw $this->createNotFoundException('Suivi grossesse introuvable.');
    }

    // Création d'un nouveau SuiviBebe lié au suivi grossesse
    $suiviBebe = new SuiviBebe();
    $suiviBebe->setSuiviGrossesse($suiviGrossesse);

    $form = $this->createForm(SuiviBebeType::class, $suiviBebe);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($suiviBebe);
        $entityManager->flush();

        // Message de confirmation
        $this->addFlash('success', 'Suivi Bébé ajouté avec succès.');

        return $this->redirectToRoute('app_suivi_bebe_indexback', [
            'suiviGrossesseId' => $suiviBebe->getSuiviGrossesse()->getId(),
        ]);
    }

    return $this->render('suivi_bebe/new.html.twig', [
        'form' => $form->createView(),
    ]);
}

    #[Route('/{id}', name: 'app_suivi_bebe_show', methods: ['GET'])]
    public function show(SuiviBebe $suiviBebe): Response
    {
        return $this->render('suivi_bebe/show.html.twig', [
            'suivi_bebe' => $suiviBebe,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_suivi_bebe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SuiviBebe $suiviBebe, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SuiviBebeType::class, $suiviBebe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_suivi_bebe_indexback', [
                'suiviGrossesseId' => $suiviBebe->getSuiviGrossesse()->getId()
            ], Response::HTTP_SEE_OTHER);
            
        }

        return $this->render('suivi_bebe/edit.html.twig', [
            'suivi_bebe' => $suiviBebe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_suivi_bebe_delete', methods: ['POST'])]
    public function delete(Request $request, SuiviBebe $suiviBebe, EntityManagerInterface $entityManager): Response
    {
        // Vérifier le token CSRF pour la suppression
        if ($this->isCsrfTokenValid('delete'.$suiviBebe->getId(), $request->get('_token'))) {
            // Supprimer le suivi bébé
            $entityManager->remove($suiviBebe);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('app_suivi_bebe_indexback', ['suiviGrossesseId' => $suiviBebe->getSuiviGrossesse()->getId()]);
    }
    
}
