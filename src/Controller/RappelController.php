<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Service\RappelEmailService;

class RappelController extends AbstractController
{
    #[Route('/rappel-rdv', name: 'rappel_rdv')]
    public function rappel(RappelEmailService $rappelEmailService): Response
    {
        // Appeler la méthode pour envoyer les rappels
        $rappelEmailService->envoyerRappels();
        return new Response('Emails de rappel envoyés.');
    }
}
