<?php

// src/Command/SendReminderEmailsCommand.php
namespace App\Command;

use App\Repository\RendezVousRepository;
use App\Service\EmailService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendReminderEmailsCommand extends Command
{
    protected static $defaultName = 'app:send-reminder-emails';

    private $rendezVousRepository;
    private $emailService;

    public function __construct(RendezVousRepository $rendezVousRepository, EmailService $emailService)
    {
        $this->rendezVousRepository = $rendezVousRepository;
        $this->emailService = $emailService;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Récupérer les rendez-vous dans les 24 à 48 heures
        $now = new \DateTime();
        $start = (clone $now)->modify('+24 hours');
        $end = (clone $now)->modify('+48 hours');

        $rendezVous = $this->rendezVousRepository->findByDateRange($start, $end);

        foreach ($rendezVous as $rdv) {
            $patiente = $rdv->getPatient();
            $subject = 'Rappel de rendez-vous';
            $content = sprintf(
                'Bonjour %s, vous avez un rendez-vous le %s à %s avec le Dr %s.',
                $patiente->getPrenom(),
                $rdv->getDateHeure()->format('d/m/Y'),
                $rdv->getDateHeure()->format('H:i'),
                $rdv->getIdMedecin()->getNom()
            );

            // Envoyer l'e-mail de rappel
            $this->emailService->sendReminderEmail($patiente->getEmail(), $subject, $content);
        }

        $output->writeln('E-mails de rappel envoyés avec succès !');
        return Command::SUCCESS;
    }
}
