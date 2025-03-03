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
use App\Form\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\SecurityBundle\Security;


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

    
    
    
    
    
    

}
