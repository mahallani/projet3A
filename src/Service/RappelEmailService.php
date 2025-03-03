<?php
namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RendezVousRepository;

class RappelEmailService
{
    private $rendezVousRepository;
    private $mailer;
    private $entityManager;

    public function __construct(RendezVousRepository $rendezVousRepository, MailerInterface $mailer, EntityManagerInterface $entityManager)
    {
        $this->rendezVousRepository = $rendezVousRepository;
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
    }

    public function envoyerRappels(): void
    {
        // Récupérer les rendez-vous de demain
        $demain = new \DateTime('+1 day');
        $rdvs = $this->rendezVousRepository->findBy(['jour' => $demain]);

        foreach ($rdvs as $rdv) 
        {
            $patient = $rdv->getPatient();
            if ($patient && $patient->getEmail()) {
                $email = (new Email())
                    ->from('chebbimaram0@gmail.com')
                    ->to($patient->getEmail())
                    ->subject('Rappel de votre rendez-vous')
                    ->text(sprintf(
                        "Bonjour %s,\n\nCeci est un rappel pour votre rendez-vous prévu le %s à %s.\n\nMerci et à bientôt !",
                        $patient->getNom(),
                        $rdv->getJour()->format('d/m/Y'),
                        $rdv->getHeureString()
                    ));

                $this->mailer->send($email);
            }
        }
    }
}
