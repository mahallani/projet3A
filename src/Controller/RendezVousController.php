<?php

namespace App\Controller;

use App\Entity\RendezVous;
use App\Form\RendezVousType;
use App\Entity\Disponibilite;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\DisponibiliteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\RendezVousRepository;

final class RendezVousController extends AbstractController
{
    #[Route('/rendezvous', name: 'app_rendezvous')]
    public function index(): Response
    {
        return $this->render('rendezvous/index.html.twig', [
            'controller_name' => 'RendezVousController',
        ]);
    }
    // #[Route('/rendezvous/new', name: 'app_rendezvous_new')]
    // public function add(Request $request, EntityManagerInterface $em)
    // {
    //     $rendezVous = new RendezVous();
    //     $form = $this->createForm(RendezVousType::class, $rendezVous);
    //     $form->handleRequest($request);
    
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         // 📌 Récupérer les valeurs du formulaire
    //         $medecinId = $form->get('idMedecin')->getData(); // Récupérer l'ID du médecin
    //         $jour = $form->get('jour')->getData(); // Récupérer le jour
    //         $hS= $form->get('heureString')->getData();
    
    //         // 📌 Vérification des données
    //         if (!$medecinId) {
    //             $this->addFlash('error', 'Veuillez sélectionner un médecin.');
    //             return $this->redirectToRoute('app_rendezvous_new');
    //         }
    //         if (!$jour) {
    //             $this->addFlash('error', 'Veuillez sélectionner un jour.');
    //             return $this->redirectToRoute('app_rendezvous_new');
    //         }
    //         if (!$heureString) {
    //             $this->addFlash('error', 'Veuillez sélectionner une heure.');
    //             return $this->redirectToRoute('app_rendezvous_new');
    //         }
    
    //         // 📌 Trouver la disponibilité
    //         $disponibilite = $em->getRepository(Disponibilite::class)->findOneBy([
    //             'idMedecin' => $medecinId,
    //             'jour' => $jour
    //         ]);
    
    //         if (!$disponibilite) {
    //             $this->addFlash('error', "Aucune disponibilité trouvée pour ce médecin et ce jour.");
    //             return $this->redirectToRoute('app_rendezvous_new');
    //         }
    
    //         // 📌 Associer les valeurs au rendez-vous
    //         $rendezVous->setIdMedecin($medecinId);  // ✅ Ajout de l'ID du médecin
    //         $rendezVous->setHeureR($disponibilite);
    //         $rendezVous->setheureString($h);
    //         $rendezVous->setCreation(new \DateTime()); // 📌 Prend la date du jour
    
    //         // 📌 Sauvegarde

    //         $em->persist($rendezVous);
    //         $em->flush();
    
    //         $this->addFlash('success', 'Rendez-vous ajouté avec succès.');
    //         return $this->redirectToRoute('app_rendezvous_list');
    //     }
    
    //     return $this->render('rendez_vous/ajoutRendezVous.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }

  #[Route('/rendezvous/new', name: 'app_rendezvous_new')]
    public function add(Request $request, EntityManagerInterface $em)
    {
        $rendezVous = new RendezVous();
        $form = $this->createForm(RendezVousType::class, $rendezVous);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            // 📌 Vérification des données reçues
            $requestData = $request->request->all();
            $medecinId = $form->get('idMedecin')->getData();
            $jour = $form->get('jour')->getData();


            // 📌 Vérifier la disponibilité du médecin
            $disponibilite = $em->getRepository(Disponibilite::class)->findOneBy([
                'idMedecin' => $medecinId,
                'jour' => $jour
            ]);
            if ($disponibilite) {
                // Associer l'ID de la disponibilité au rendez-vous
                $rendezVous->setHeureR($disponibilite);
            }
            else {
                $this->addFlash('error', "Aucune disponibilité trouvée pour ce médecin et ce jour.");
                return $this->redirectToRoute('app_rendezvous_new');
            }
            $em->persist($rendezVous);
            $em->flush();

            $this->addFlash('success', 'Rendez-vous ajouté avec succès.');
            return $this->redirectToRoute('app_rendezvous_list');
        }

        return $this->render('rendez_vous/ajoutRendezVous.html.twig', [
            'form' => $form->createView(),
        ]);
    }




    #[Route('/rendezvous/getAvailableTimes/{medecinId}/{jour}', name: 'get_available_times')]
    public function getAvailableTimes(int $medecinId, string $jour, EntityManagerInterface $em): JsonResponse
    {
        $disponibilite = $em->getRepository(Disponibilite::class)->findOneBy([
            'idMedecin' => $medecinId,
            'jour' => new \DateTime($jour) // 📌 Convertir la date correctement
        ]);
    
        if (!$disponibilite) {
            return new JsonResponse([], 404);
        }
    
        // 🔹 Récupérer les heures disponibles depuis la table `Disponibilite`
        $heuresDisponibles = $disponibilite->getHeuresDisp();
    
        if (is_string($heuresDisponibles)) {
            $heuresDisponibles = json_decode($heuresDisponibles, true);
        }
    
        if (!is_array($heuresDisponibles)) {
            return new JsonResponse(['error' => 'Format incorrect'], 500);
        }
    
        // 🔹 Récupérer les heures déjà réservées pour ce jour et ce médecin
        $rendezVous = $em->getRepository(RendezVous::class)->findBy([
            'idMedecin' => $medecinId,
            'jour' => new \DateTime($jour)
        ]);
    
        $heuresReservees = [];
        foreach ($rendezVous as $rdv) {
            $heuresReservees[] = $rdv->getHeureString(); // 📌 Récupère les heures déjà prises
        }
    
        // 🔹 Filtrer les heures disponibles en retirant celles déjà réservées
        $heuresRestantes = array_diff($heuresDisponibles, $heuresReservees);
    
        return new JsonResponse(array_values($heuresRestantes)); // ✅ Retourne la liste mise à jour
    }
    

    

#[Route('/rendezvous/view', name: 'app_rendezvous_list')]
public function listRendezVous(RendezVousRepository $RendezVousRepository): Response
{
    $rendezVousList = $RendezVousRepository->findAll(); // Récupère tous les rendez-vous

    return $this->render('rendez_vous/afficheRendezVous.html.twig', [
        'rendezVousList' => $rendezVousList
    ]);
}
#[Route('/rendezvous/edit/{id}', name: 'app_rendezvous_edit')]
public function edit($id, Request $request, EntityManagerInterface $em, RendezVousRepository $rendezVousRepository, DisponibiliteRepository $disponibiliteRepository): Response
{
    $rendezVous = $rendezVousRepository->find($id);

    if (!$rendezVous) {
        throw $this->createNotFoundException("Le rendez-vous avec l'ID $id n'existe pas.");
    }

    $form = $this->createForm(RendezVousType::class, $rendezVous);
    $form->handleRequest($request);

    // Récupérer les informations du médecin et du jour actuel
    $medecinId = $rendezVous->getIdMedecin();
    $jour = $rendezVous->getJour()->format('Y-m-d');

    // 📌 Récupérer les horaires disponibles pour ce médecin et ce jour
    $disponibilite = $disponibiliteRepository->findOneBy([
        'idMedecin' => $medecinId,
        'jour' => new \DateTime($jour)
    ]);

    $heuresDisponibles = [];
    if ($disponibilite) {
        $heuresDisponibles = is_string($disponibilite->getHeuresDisp())
            ? json_decode($disponibilite->getHeuresDisp(), true)
            : $disponibilite->getHeuresDisp();
    }

    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($rendezVous);
        $em->flush();

        $this->addFlash('success', 'Rendez-vous modifié avec succès.');
        return $this->redirectToRoute('app_rendezvous_list');
    }

    return $this->render('rendez_vous/editRendezVous.html.twig', [
        'form' => $form->createView(),
        'title' => 'Modifier le Rendez-Vous',
        'ancienneHeure' => $rendezVous->getHeureString(),
        'heuresDisponibles' => $heuresDisponibles
    ]);
}
#[Route('/rendezvous/delete/{id}', name: 'app_rendezvous_delete')]
public function deleteRendezVous($id, EntityManagerInterface $em, RendezVousRepository $rendezVousRepository): Response
{
    $rendezVous = $rendezVousRepository->find($id);

    if (!$rendezVous) {
        throw $this->createNotFoundException("Le rendez-vous avec l'ID $id n'existe pas.");
    }

    $em->remove($rendezVous);
    $em->flush();

    return $this->redirectToRoute('app_rendezvous_list');
}



}
