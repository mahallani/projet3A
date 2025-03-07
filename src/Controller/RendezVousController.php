<?php

namespace App\Controller;

<<<<<<< HEAD
use App\Service\TwilioSmsService;
use App\Entity\RendezVous;
use App\Form\RendezVousType;
use App\Entity\Disponibilite;
use App\Entity\User;
=======
use App\Entity\RendezVous;
use App\Form\RendezVousType;
use App\Entity\Disponibilite;
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\DisponibiliteRepository;
<<<<<<< HEAD
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\RendezVousRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;


=======
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\RendezVousRepository;
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b

final class RendezVousController extends AbstractController
{
    #[Route('/rendezvous', name: 'app_rendezvous')]
    public function index(): Response
    {
        return $this->render('rendezvous/index.html.twig', [
            'controller_name' => 'RendezVousController',
        ]);
    }


<<<<<<< HEAD
    #[Route('/rendezvous/new', name: 'app_rendezvous_new')]
    public function add(Request $request, EntityManagerInterface $em): Response
=======
  #[Route('/rendezvous/new', name: 'app_rendezvous_new')]
    public function add(Request $request, EntityManagerInterface $em)
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b
    {
        $rendezVous = new RendezVous();
        $form = $this->createForm(RendezVousType::class, $rendezVous);
        $form->handleRequest($request);
<<<<<<< HEAD
    
        if ($form->isSubmitted() && $form->isValid()) 
        {
         
            $user = $this->getUser();
            if (!$user) {
                $this->addFlash('error', "Vous devez être connecté pour prendre un rendez-vous.");
                return $this->redirectToRoute('app_login');
            }
    
          
            $rendezVous->setPatient($user);
    
           
            $medecinId = $form->get('idMedecin')->getData();
            $jour = $form->get('jour')->getData();
    
           
=======

        if ($form->isSubmitted() && $form->isValid()) 
        {
      
            $requestData = $request->request->all();
            $medecinId = $form->get('idMedecin')->getData();
            $jour = $form->get('jour')->getData();


         
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b
            $disponibilite = $em->getRepository(Disponibilite::class)->findOneBy([
                'idMedecin' => $medecinId,
                'jour' => $jour
            ]);
<<<<<<< HEAD
    
            if ($disponibilite) {
                $rendezVous->setHeureR($disponibilite);
            } else {
                $this->addFlash('error', "Aucune disponibilité trouvée pour ce médecin et ce jour.");
                return $this->redirectToRoute('app_rendezvous_new');
            }
    
            $em->persist($rendezVous);
            $em->flush();
    
            $this->addFlash('success', 'Rendez-vous ajouté avec succès.');
            return $this->redirectToRoute('app_rendezvous_list');
        }
    
=======
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

>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b
        return $this->render('rendez_vous/ajoutRendezVous.html.twig', [
            'form' => $form->createView(),
        ]);
    }
<<<<<<< HEAD
    
     
=======

>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b



    #[Route('/rendezvous/getAvailableTimes/{medecinId}/{jour}', name: 'get_available_times')]
    public function getAvailableTimes(int $medecinId, string $jour, EntityManagerInterface $em): JsonResponse
<<<<<<< HEAD
    {   

        $disponibilite = $em->getRepository(Disponibilite::class)->findOneBy([
            'idMedecin' => $medecinId, 
            'jour' => new \DateTime($jour)
=======
    {
        $disponibilite = $em->getRepository(Disponibilite::class)->findOneBy([
            'idMedecin' => $medecinId,
            'jour' => new \DateTime($jour) 
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b
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
<<<<<<< HEAD
            $heuresReservees[] = $rdv->getHeureString();
        }
    
        
        $heuresRestantes = array_diff($heuresDisponibles, $heuresReservees);
    
        return new JsonResponse(array_values($heuresRestantes)); 
=======
            $heuresReservees[] = $rdv->getHeureString(); 
        }
    
      
        $heuresRestantes = array_diff($heuresDisponibles, $heuresReservees);
    
        return new JsonResponse(array_values($heuresRestantes));
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b
    }
    

    

#[Route('/rendezvous/view', name: 'app_rendezvous_list')]
<<<<<<< HEAD
public function listRendezVous(RendezVousRepository $RendezVousRepository, Security $security): Response
{
    $patient = $security->getUser();

    if (!$patient) {
        throw $this->createAccessDeniedException("Vous devez être connecté.");
    }

   
    $rendezVousList = $RendezVousRepository->findBy(['patient' => $patient]);
=======
public function listRendezVous(RendezVousRepository $RendezVousRepository): Response
{
    $rendezVousList = $RendezVousRepository->findAll();
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b

    return $this->render('rendez_vous/afficheRendezVous.html.twig', [
        'rendezVousList' => $rendezVousList
    ]);
}
#[Route('/rendezvous/edit/{id}', name: 'app_rendezvous_edit')]
<<<<<<< HEAD
public function edit(  $id,   Request $request,  EntityManagerInterface $em,  RendezVousRepository $rendezVousRepository,   DisponibiliteRepository $disponibiliteRepository,TwilioSmsService $twilioSmsService,UserRepository $userRepository): Response 
{
=======
public function edit(  $id,   Request $request,  EntityManagerInterface $em,  RendezVousRepository $rendezVousRepository,   DisponibiliteRepository $disponibiliteRepository): Response {
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b
    $rendezVous = $rendezVousRepository->find($id);

    if (!$rendezVous) {
        throw $this->createNotFoundException("Le rendez-vous avec l'ID $id n'existe pas.");
    }
<<<<<<< HEAD
    $ancienneDate = $rendezVous->getJour()->format('Y-m-d');
    $ancienneHeure = $rendezVous->getHeureString();
=======
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b

    $form = $this->createForm(RendezVousType::class, $rendezVous);
    $form->handleRequest($request);

<<<<<<< HEAD
   
    $medecinId = $rendezVous->getIdMedecin();
    $medecin = $userRepository->find($medecinId);
    $numeroMedecin = $medecin ? $medecin->getNumtel() : null;
    $jour = $rendezVous->getJour()->format('Y-m-d');

   
=======
  
    $medecinId = $rendezVous->getIdMedecin();
    $jour = $rendezVous->getJour()->format('Y-m-d');


>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b
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

<<<<<<< HEAD
    
=======
  
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b
    $rendezVousExistants = $rendezVousRepository->findBy([
        'idMedecin' => $medecinId,
        'jour' => new \DateTime($jour)
    ]);

    $heuresReservees = [];
    foreach ($rendezVousExistants as $rdv) {
<<<<<<< HEAD
=======
      
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b
        if ($rdv->getId() !== $id) {
            $heuresReservees[] = $rdv->getHeureString();
        }
    }

<<<<<<< HEAD
    
    $heuresRestantes = array_diff($heuresDisponibles, $heuresReservees);

    if ($form->isSubmitted() && $form->isValid()) {
        $nouvelleDate = $rendezVous->getJour()->format('Y-m-d');
        $nouvelleHeure = $rendezVous->getHeureString();

        if (($nouvelleDate !== $ancienneDate || $nouvelleHeure !== $ancienneHeure) && $numeroMedecin) {
            // 🔥 Envoyer un SMS via TwilioSmsService
            $message = "⚠️ Notification : Le rendez-vous du $ancienneDate à $ancienneHeure a été modifié. 
            🗓️ Nouvelle date : $nouvelleDate 
            ⏰ Nouvelle heure : $nouvelleHeure.";

            $twilioSmsService->sendSms($numeroMedecin, $message);
        }
=======
   
    $heuresRestantes = array_diff($heuresDisponibles, $heuresReservees);

    if ($form->isSubmitted() && $form->isValid()) {
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b
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
<<<<<<< HEAD
public function listRendezVousBack(RendezVousRepository $rendezVousRepository, Security $security): Response
{
  
    $medecin = $security->getUser();

    if (!$medecin) {
        throw $this->createAccessDeniedException("Vous devez être connecté.");
    }

    $rendezVousList = $rendezVousRepository->findBy(['idMedecin' => $medecin]);
=======
public function listRendezVousBack(RendezVousRepository $RendezVousRepository): Response
{
    $rendezVousList = $RendezVousRepository->findAll(); 
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b

    return $this->render('rendez_vous/afficheRendezVousBack.html.twig', [
        'rendezVousList' => $rendezVousList
    ]);
}
<<<<<<< HEAD

=======
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b
#[Route('/rendezvous/editBack/{id}', name: 'app_rendezvous_editBack')]
public function editBack(  $id,   Request $request,  EntityManagerInterface $em,  RendezVousRepository $rendezVousRepository,   DisponibiliteRepository $disponibiliteRepository): Response {
    $rendezVous = $rendezVousRepository->find($id);

    if (!$rendezVous) {
        throw $this->createNotFoundException("Le rendez-vous avec l'ID $id n'existe pas.");
    }

    $form = $this->createForm(RendezVousType::class, $rendezVous);
    $form->handleRequest($request);

<<<<<<< HEAD
   
    $medecinId = $rendezVous->getIdMedecin();
    $jour = $rendezVous->getJour()->format('Y-m-d');

   
=======
  
    $medecinId = $rendezVous->getIdMedecin();
    $jour = $rendezVous->getJour()->format('Y-m-d');

  
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b
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
<<<<<<< HEAD

=======
      
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b
        if ($rdv->getId() !== $id) {
            $heuresReservees[] = $rdv->getHeureString();
        }
    }

<<<<<<< HEAD
  
=======
   
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b
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
<<<<<<< HEAD
        'heuresDisponibles' => array_values($heuresRestantes) 
=======
        'heuresDisponibles' => array_values($heuresRestantes)
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b
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
<<<<<<< HEAD
#[Route('/medecin/calendar', name: 'medecin_calendar')]
#[IsGranted('ROLE_MEDECIN')] // S'assurer que seul un médecin peut accéder
public function calendar(): Response
{
    return $this->render('rendez_vous/calendar.html.twig');
}


#[Route('/api/medecin/events', name: 'api_medecin_events', methods: ['GET'])]
public function getMedecinEvents(
    RendezVousRepository $rendezVousRepository,
    Security $security,
    UrlGeneratorInterface $urlGenerator // 👈 Ajout ici
): JsonResponse {
    $medecin = $security->getUser();

    if (!$medecin) {
        return new JsonResponse(['error' => 'Utilisateur non connecté'], Response::HTTP_FORBIDDEN);
    }

    $rendezVous = $rendezVousRepository->findBy(['idMedecin' => $medecin]);

    $events = [];

    foreach ($rendezVous as $rdv) {
        $patient = $rdv->getPatient();
        $patientName = $patient ? $patient->getNom() : 'Patient inconnu';
        $heures = explode('-', $rdv->getHeureString());

        if (count($heures) !== 2) {
            continue;
        }

        $startTime = new \DateTime($rdv->getJour()->format('Y-m-d') . ' ' . trim($heures[0]));
        $endTime = new \DateTime($rdv->getJour()->format('Y-m-d') . ' ' . trim($heures[1]));

        $events[] = [
            'id' => $rdv->getId(),
            'title' => 'Rdv avec ' . $patientName,
            'start' => $startTime->format('Y-m-d\TH:i:s'),
            'end' => $endTime->format('Y-m-d\TH:i:s'),
            'url' => $urlGenerator->generate('rendez_vous_show', ['id' => $rdv->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
            'backgroundColor' => '#007bff',
            'borderColor' => '#007bff',
        ];
    }

    return new JsonResponse($events);
}
#[Route('/medecin/calendrier', name: 'medecin_calendrier')]
public function calendrier(): Response
{
    return $this->render('rendez_vous/calendar.html.twig');
}
#[Route('/rendez-vous/{id}', name: 'rendez_vous_show')]
public function show(RendezVous $rendezVous): Response
{
    return $this->render('rendez_vous/show.html.twig', [
        'rendezVous' => $rendezVous,
    ]);
}

=======
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b

}
