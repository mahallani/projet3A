<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RendezVousRepository;

class StatistiquesController extends AbstractController
{
    #[Route('/statistique', name: 'app_statistique')]
    public function index(RendezVousRepository $rendezVousRepository): Response
    {
        $data = $rendezVousRepository->findAverageRendezVousByMedecin();

        $labels = [];
        $averages = [];

        foreach ($data as $item) {
            $labels[] = 'Médecin ' . $item['medecinId'];
            $averages[] = $item['averageRendezVous'];
        }

        return $this->render('statistique/index.html.twig', [
            'labels' => json_encode($labels),
            'averages' => json_encode($averages),
        ]);
    }
}