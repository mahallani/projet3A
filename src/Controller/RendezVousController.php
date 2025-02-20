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


  #[Route('/rendezvous/new', name: 'app_rendezvous_new')]
    public function add(Request $request, EntityManagerInterface $em)
    {
        $rendezVous = new RendezVous();
        $form = $this->createForm(RendezVousType::class, $rendezVous);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
      
            $requestData = $request->request->all();
            $medecinId = $form->get('idMedecin')->getData();
            $jour = $form->get('jour')->getData();


         
            $disponibilite = $em->getRepository(Disponibilite::class)->findOneBy([
                'idMedecin' => $medecinId,
                'jour' => $jour
            ]);
            if ($disponibilite) {
              
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
            'jour' => new \DateTime($jour) 
        ]);
    
        if (!$disponibilite) {
            return new JsonResponse([], 404);
        }
    
      
        $heuresDisponibles = $disponibilite->getHeuresDisp();
    
        if (is_string($heuresDisponibles)) {
            $heuresDisponibles = json_decode($heuresDisponibles, true);
        }
    
        if (!is_array($heuresDisponibles)) {
            return new JsonResponse(['error' => 'Format incorrect'], 500);
        }
    
        
        $rendezVous = $em->getRepository(RendezVous::class)->findBy([
            'idMedecin' => $medecinId,
            'jour' => new \DateTime($jour)
        ]);
    
        $heuresReservees = [];
        foreach ($rendezVous as $rdv) {
            $heuresReservees[] = $rdv->getHeureString(); 
        }
    
      
        $heuresRestantes = array_diff($heuresDisponibles, $heuresReservees);
    
        return new JsonResponse(array_values($heuresRestantes));
    }
    

    

#[Route('/rendezvous/view', name: 'app_rendezvous_list')]
public function listRendezVous(RendezVousRepository $RendezVousRepository): Response
{
    $rendezVousList = $RendezVousRepository->findAll();

    return $this->render('rendez_vous/afficheRendezVous.html.twig', [
        'rendezVousList' => $rendezVousList
    ]);
}
#[Route('/rendezvous/edit/{id}', name: 'app_rendezvous_edit')]
public function edit(  $id,   Request $request,  EntityManagerInterface $em,  RendezVousRepository $rendezVousRepository,   DisponibiliteRepository $disponibiliteRepository): Response {
    $rendezVous = $rendezVousRepository->find($id);

    if (!$rendezVous) {
        throw $this->createNotFoundException("Le rendez-vous avec l'ID $id n'existe pas.");
    }

    $form = $this->createForm(RendezVousType::class, $rendezVous);
    $form->handleRequest($request);

  
    $medecinId = $rendezVous->getIdMedecin();
    $jour = $rendezVous->getJour()->format('Y-m-d');


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

  
    $rendezVousExistants = $rendezVousRepository->findBy([
        'idMedecin' => $medecinId,
        'jour' => new \DateTime($jour)
    ]);

    $heuresReservees = [];
    foreach ($rendezVousExistants as $rdv) {
      
        if ($rdv->getId() !== $id) {
            $heuresReservees[] = $rdv->getHeureString();
        }
    }

   
    $heuresRestantes = array_diff($heuresDisponibles, $heuresReservees);

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
        'heuresDisponibles' => array_values($heuresRestantes) 
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
#[Route('/rendezvous/view/back', name: 'app_rendezvous_listBack')]
public function listRendezVousBack(RendezVousRepository $RendezVousRepository): Response
{
    $rendezVousList = $RendezVousRepository->findAll(); 

    return $this->render('rendez_vous/afficheRendezVousBack.html.twig', [
        'rendezVousList' => $rendezVousList
    ]);
}
#[Route('/rendezvous/editBack/{id}', name: 'app_rendezvous_editBack')]
public function editBack(  $id,   Request $request,  EntityManagerInterface $em,  RendezVousRepository $rendezVousRepository,   DisponibiliteRepository $disponibiliteRepository): Response {
    $rendezVous = $rendezVousRepository->find($id);

    if (!$rendezVous) {
        throw $this->createNotFoundException("Le rendez-vous avec l'ID $id n'existe pas.");
    }

    $form = $this->createForm(RendezVousType::class, $rendezVous);
    $form->handleRequest($request);

  
    $medecinId = $rendezVous->getIdMedecin();
    $jour = $rendezVous->getJour()->format('Y-m-d');

  
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

  
    $rendezVousExistants = $rendezVousRepository->findBy([
        'idMedecin' => $medecinId,
        'jour' => new \DateTime($jour)
    ]);

    $heuresReservees = [];
    foreach ($rendezVousExistants as $rdv) {
      
        if ($rdv->getId() !== $id) {
            $heuresReservees[] = $rdv->getHeureString();
        }
    }

   
    $heuresRestantes = array_diff($heuresDisponibles, $heuresReservees);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($rendezVous);
        $em->flush();

        $this->addFlash('success', 'Rendez-vous modifié avec succès.');
        return $this->redirectToRoute('app_rendezvous_listBack');
    }

    return $this->render('rendez_vous/editRendezVousBack.html.twig', [
        'form' => $form->createView(),
        'title' => 'Modifier le Rendez-Vous',
        'ancienneHeure' => $rendezVous->getHeureString(),
        'heuresDisponibles' => array_values($heuresRestantes)
    ]);
}

#[Route('/rendezvous/deleteBack/{id}', name: 'app_rendezvous_deleteBack')]
public function deleteRendezVousBack($id, EntityManagerInterface $em, RendezVousRepository $rendezVousRepository): Response
{
    $rendezVous = $rendezVousRepository->find($id);

    if (!$rendezVous) {
        throw $this->createNotFoundException("Le rendez-vous avec l'ID $id n'existe pas.");
    }

    $em->remove($rendezVous);
    $em->flush();

    return $this->redirectToRoute('app_rendezvous_listBack');
}

}
