<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Disponibilite;
use App\Repository\DisponibiliteRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\DisponibiliteType;
<<<<<<< HEAD
use App\Form\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\SecurityBundle\Security;
=======
use Symfony\Component\HttpFoundation\JsonResponse;
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b


final class DisponibiliteController extends AbstractController
{
    #[Route('/disponibilite', name: 'app_disponibilite')]
    public function index(): Response
    {
        return $this->render('index.html.twig', [
            'controller_name' => 'DisponibiliteController',
        ]);
    }
    #[Route('/disponibilite/new', name: 'app_disponibilite_new')]
<<<<<<< HEAD
    public function newDisponibilite(Request $request, EntityManagerInterface $em, Security $security)
    {
        $disponibilite = new Disponibilite();
        
       
        $user = $security->getUser();
        
       
        $isMedecin = in_array('ROLE_MEDECIN', $user->getRoles());
    
        $form = $this->createForm(DisponibiliteType::class, $disponibilite, [
            'user' => $user, 
            'isMedecin' => $isMedecin, 
        ]);
        
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($disponibilite);
            $em->flush();
            return $this->redirectToRoute('app_disponibilite_listBack');
        }
    
        return $this->render('disponibilite/ajoutdispo.html.twig', [
            'title' => 'Add Disponibilité',
            'form' => $form->createView(),
        ]);
    }
    
=======
    public function newDisponibilite(Request $request,EntityManagerInterface $em){
        $disponibilite= new Disponibilite();
        $form= $this->createForm(disponibiliteType::class,$disponibilite);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($disponibilite);
            $em->flush();
            return $this->redirectToRoute('app_disponibilite_new');
        }
        return $this->render('disponibilite/ajoutdispo.html.twig',[
            'title' => 'Add disponibilite',
            'form'=> $form
        ]);
    }
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b
    #[Route('/disponibilites', name: 'app_disponibilite_list')]
    public function listDisponibilites(DisponibiliteRepository $DisponibiliteRepository): Response
    {
        $disponibilites = $DisponibiliteRepository->findAll(); 
    
        return $this->render('disponibilite/afficheDispo.html.twig', [
            'disponibilites' => $disponibilites 
        ]);
    }
    #[Route('/disponibilite/edit/{id}', name: 'app_disponibilite_edit')]
    public function editDisponibilite($id, Request $request, EntityManagerInterface $em, DisponibiliteRepository $disponibiliteRepository): Response
    {
        $disponibilite = $disponibiliteRepository->find($id);
    
        if (!$disponibilite) {
            throw $this->createNotFoundException("La disponibilité avec l'ID $id n'existe pas.");
        }
    
        $form = $this->createForm(DisponibiliteType::class, $disponibilite);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($disponibilite);
            $em->flush();
    
            return $this->redirectToRoute('app_disponibilite_listBack'); 
        }
    
        return $this->render('disponibilite/editDispo.html.twig', [
            'form' => $form->createView(),
            'title' => 'Modifier la disponibilité'
        ]);
    }
    #[Route('/disponibilite/delete/{id}', name: 'app_disponibilite_delete')]
    public function deleteDisponibilite($id, EntityManagerInterface $em, DisponibiliteRepository $disponibiliteRepository): Response
    {
        $disponibilite = $disponibiliteRepository->find($id);
    
        if (!$disponibilite) {
            throw $this->createNotFoundException("La disponibilité avec l'ID $id n'existe pas.");
        }
    
        $em->remove($disponibilite);
        $em->flush();
    
        return $this->redirectToRoute('app_disponibilite_list');
    }
<<<<<<< HEAD
    #[Route('/disponibilites/back', name: 'app_disponibilite_listBack')]
    public function listDisponibilitesBack(DisponibiliteRepository $DisponibiliteRepository, Security $security): Response
    {
        $medecin = $security->getUser();

        if (!$medecin) {
            throw $this->createAccessDeniedException("Vous devez être connecté.");
        }
    
        
        $disponibilites = $DisponibiliteRepository->findBy(['idMedecin' => $medecin]);
    
        return $this->render('disponibilite/afficheDispoBack.html.twig', [
            'disponibilites' => $disponibilites, 
        ]);
    }
    
    #[Route('/disponibilite/deleteBack/{id}', name: 'app_disponibilite_deleteBack')]
    public function deleteDisponibiliteBack($id, EntityManagerInterface $em, DisponibiliteRepository $disponibiliteRepository): Response
    {
        $disponibilite = $disponibiliteRepository->find($id);
    
        if (!$disponibilite) {
            throw $this->createNotFoundException("La disponibilité avec l'ID $id n'existe pas.");
        }
    
        $em->remove($disponibilite);
        $em->flush();
    
        return $this->redirectToRoute('app_disponibilite_listBack');
    }
=======
    #[Route('/disponibilite/calendar', name: 'app_disponibilite_calendar', methods: ['GET'])]
    public function getDisponibilites(DisponibiliteRepository $disponibiliteRepository): JsonResponse
    {
        $disponibilites = $disponibiliteRepository->findAll();
        $events = [];
    
        foreach ($disponibilites as $disponibilite) {
            foreach ($disponibilite->getHeuresDisp() as $heure) {
                $start = new \DateTime($disponibilite->getJour()->format('Y-m-d') . ' ' . $heure['start']);
                $end = new \DateTime($disponibilite->getJour()->format('Y-m-d') . ' ' . $heure['end']);
    
                $events[] = [
                    'title' => "Médecin ID: " . $disponibilite->getIdMedecin() . " - " . ($disponibilite->getStatutDisp() === 'réservé' ? 'Réservé' : 'Disponible'),
                    'start' => $start->format('Y-m-d\TH:i:s'),
                    'end' => $end->format('Y-m-d\TH:i:s'),
                    'color' => $disponibilite->getStatutDisp() === 'réservé' ? 'red' : 'green'
                ];
            }
        }
    
        return new JsonResponse($events);
    }
    #[Route('/disponibilites/back', name: 'app_disponibilite_listBack')]
    public function listDisponibilitesBack(DisponibiliteRepository $DisponibiliteRepository): Response
    {
        $disponibilites = $DisponibiliteRepository->findAll(); 
    
        return $this->render('disponibilite/afficheDispoBack.html.twig', [
            'disponibilites' => $disponibilites 
        ]);
    }
    
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b

    
    
    
    
    
    

}
