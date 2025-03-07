<?php

namespace App\Entity;

use App\Repository\RendezVousRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: RendezVousRepository::class)]
class RendezVous
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

 

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le motif est obligatoire.")]
    private ?string $motif = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Les symptômes sont obligatoires.")]
    private ?string $symptomes = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Les traitements sont obligatoires.")]
    private ?string $traitementEnCours = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $statutRendezVous = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $creation = null;

<<<<<<< HEAD
=======
    #[ORM\Column]
    private ?int $idMedecin = null;
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b
    
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "L'heure du rendez-vous est obligatoire.")]
    private ?string $heureString = null;

    #[ORM\ManyToOne(inversedBy: 'rendezVous')]
<<<<<<< HEAD
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
=======
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b
    private ?Disponibilite $heureR = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotNull(message: "La date de création est obligatoire.")]
    #[Assert\GreaterThan('today', message: "Le jour doit être dans le futur.")]
    private ?\DateTimeInterface $jour = null;

<<<<<<< HEAD
    #[ORM\ManyToOne(inversedBy: 'rendezvouses')]
    #[ORM\JoinColumn(name: "id_medecin_id", referencedColumnName: "id", nullable: false)]
    private ?User $idMedecin = null;

    #[ORM\ManyToOne(inversedBy: 'rendezvouses')]
    #[ORM\JoinColumn(name: "patient_id", referencedColumnName: "id", nullable: false)]
    private ?User $patient = null;
   
=======
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b


    public function getId(): ?int
    {
        return $this->id;
    }



    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): static
    {
        $this->motif = $motif;

        return $this;
    }

    public function getSymptomes(): ?string
    {
        return $this->symptomes;
    }

    public function setSymptomes(string $symptomes): static
    {
        $this->symptomes = $symptomes;

        return $this;
    }

    public function getTraitementEnCours(): ?string
    {
        return $this->traitementEnCours;
    }

    public function setTraitementEnCours(?string $traitementEnCours): static
    {
        $this->traitementEnCours = $traitementEnCours;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }

    public function getStatutRendezVous(): ?string
    {
        return $this->statutRendezVous;
    }

    public function setStatutRendezVous(string $statutRendezVous): static
    {
        $this->statutRendezVous = $statutRendezVous;

        return $this;
    }

    public function getCreation(): ?\DateTimeInterface
    {
        return $this->creation;
    }

    public function setCreation(\DateTimeInterface $creation): static
    {
        $this->creation = $creation;

        return $this;
    }

<<<<<<< HEAD
=======
    public function getIdPatient(): ?int
    {
        return $this->idPatient;
    }

    public function setIdPatient(int $idPatient): static
    {
        $this->idPatient = $idPatient;

        return $this;
    }

    public function getIdMedecin(): ?int
    {
        return $this->idMedecin;
    }

    public function setIdMedecin(int $idMedecin): static
    {
        $this->idMedecin = $idMedecin;

        return $this;
    }
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b

    public function getHeureR(): ?Disponibilite
    {
        return $this->heureR;
    }

    public function setHeureR(?Disponibilite $heureR): static
    {
        $this->heureR = $heureR;

        return $this;
    }

    public function getJour(): ?\DateTimeInterface
    {
        return $this->jour;
    }

    public function setJour(\DateTimeInterface $jour): static
    {
        $this->jour = $jour;

        return $this;
    }
    public function getHeureString(): ?string
    {
        return $this->heureString;
    }

    public function setHeureString(?string $heureString): self
    {
        $this->heureString = $heureString;
        return $this;
    }

<<<<<<< HEAD
    public function getIdMedecin(): ?User
    {
        return $this->idMedecin;
    }

    public function setIdMedecin(?User $idMedecin): static
    {
        $this->idMedecin = $idMedecin;

        return $this;
    }

    public function getPatient(): ?User
    {
        return $this->patient;
    }

    public function setPatient(?User $patient): static
    {
        $this->patient = $patient;

        return $this;
    }

=======
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b

}
