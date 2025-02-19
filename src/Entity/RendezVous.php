<?php

namespace App\Entity;

use App\Repository\RendezVousRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RendezVousRepository::class)]
class RendezVous
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

 

    #[ORM\Column(length: 255)]
    private ?string $motif = null;

    #[ORM\Column(length: 255)]
    private ?string $symptomes = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $traitementEnCours = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $statutRendezVous = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $creation = null;

    #[ORM\Column]
    private ?int $idMedecin = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $heureString = null;

    #[ORM\ManyToOne(inversedBy: 'rendezVous')]
    private ?Disponibilite $heureR = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $jour = null;



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


}
