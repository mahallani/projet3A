<?php

namespace App\Controller;

use App\Entity\SuiviGrossesse;
use App\Form\SuiviGrossesseType;
use App\Repository\SuiviGrossesseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route; 
use Symfony\Bundle\SecurityBundle\Security;


#[Route('/suivi/grossesse')]
final class SuiviGrossesseController extends AbstractController
{

#[Route('/indexfront', name: 'app_suivi_grossesse_index', methods: ['GET'])]
public function index(SuiviGrossesseRepository $suiviGrossesseRepo, Security $security): Response
{
    // Get the logged-in user
    $user = $security->getUser();

    // Ensure the user is authenticated
    if (!$user) {
        throw $this->createAccessDeniedException("You must be logged in to access this page.");
    }

    // Retrieve the user's pregnancy records (assuming 'patient' is the relation field)
    $suiviGrossesses = $suiviGrossesseRepo->findBy(['patient' => $user]);

    return $this->render('suivi_grossesse/indexfront.html.twig', [
        'suivi_grossesses' => $suiviGrossesses,
       
    ]);
}
    #[Route('/indexback', name: 'app_suivi_grossesse_indexback', methods: ['GET'])]
public function indexback(SuiviGrossesseRepository $suiviGrossesseRepository): Response
{
    return $this->render('suivi_grossesse/indexback.html.twig', [
        'suivi_grossesses' => $suiviGrossesseRepository->findAll(),
    ]);
}


#[Route('/new', name: 'app_suivi_grossesse_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $suiviGrossesse = new SuiviGrossesse();
    $form = $this->createForm(SuiviGrossesseType::class, $suiviGrossesse);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($suiviGrossesse);
        $entityManager->flush();

        return $this->redirectToRoute('app_suivi_grossesse_indexback', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('suivi_grossesse/new.html.twig', [
        'suivi_grossesse' => $suiviGrossesse,
        'form' => $form->createView(), // Utilisation de createView() pour éviter d'envoyer l'objet brut à Twig
    ]);
}

    #[Route('/{id}', name: 'app_suivi_grossesse_show', methods: ['GET'])]
    public function show(SuiviGrossesse $suiviGrossesse): Response
    {
        return $this->render('suivi_grossesse/show.html.twig', [
            'suivi_grossesse' => $suiviGrossesse,
        ]);
    }

    #[Route('suivi/grossesse/{id}/edit', name: 'app_suivi_grossesse_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SuiviGrossesse $suiviGrossesse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SuiviGrossesseType::class, $suiviGrossesse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_suivi_grossesse_indexback', ['id' => $suiviGrossesse->getId()]);

        }

        return $this->render('suivi_grossesse/edit.html.twig', [
            'suivi_grossesse' => $suiviGrossesse,
            'form' => $form,
        ]);
    }

    #[Route('/suivi/grossesse/{id}/delete', name: 'app_suivi_grossesse_delete', methods: ['GET'])]
    public function deleteConfirm(SuiviGrossesse $suiviGrossesse): Response
    {
        // Affiche la page de confirmation
        return $this->render('suivi_grossesse/_delete_form.html.twig', [
            'suivi_grossesse' => $suiviGrossesse,
        ]);
    }
    
    #[Route('/suivi/grossesse/{id}/delete', name: 'app_suivi_grossesse_delete_confirm', methods: ['POST'])]
    public function delete(SuiviGrossesse $suiviGrossesse, EntityManagerInterface $entityManager): Response
    {
        // Supprime l'entité et effectue la persistance
        $entityManager->remove($suiviGrossesse);
        $entityManager->flush();
    
        // Redirige vers la page d'index après suppression
        return $this->redirectToRoute('app_suivi_grossesse_indexback');
    }
    

}  