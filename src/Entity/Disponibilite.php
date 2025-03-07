<?php

namespace App\Entity;

use App\Repository\DisponibiliteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
<<<<<<< HEAD
use App\Entity\User; 

=======
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b


#[ORM\Entity(repositoryClass: DisponibiliteRepository::class)]
class Disponibilite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: "Le jour ne doit pas être vide.")]
    #[Assert\Type(type: "\DateTimeInterface", message: "Le format du jour est invalide.")]
    #[Assert\GreaterThan('today', message: "Le jour doit être dans le futur.")]
    private ?\DateTimeInterface $jour = null;

    #[ORM\Column (type: 'json')]
    #[Assert\NotBlank(message: "Veuillez ajouter au moins un créneau horaire.")]
    #[Assert\Count(min: 1, minMessage: "Ajoutez au moins un créneau.")]
    private array $heuresDisp = [];

   

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le statut de disponibilité est obligatoire.")]
    private ?string $statutDisp = null;

<<<<<<< HEAD
=======
    #[ORM\Column]
    #[Assert\NotNull(message: "Le médecin est requis.")]
    private ?int $idMedecin = null;
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b

    /**
     * @var Collection<int, RendezVous>
     */
    #[ORM\OneToMany(targetEntity: RendezVous::class, mappedBy: 'heureR')]
    private Collection $rendezVouses;

<<<<<<< HEAD
    #[ORM\ManyToOne(inversedBy: 'disponibilites')]
    #[Assert\NotNull(message: "Le médecin est requis.")]
    private ?User $idMedecin = null;

=======
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b
    public function __construct()
    {
        $this->rendezVouses = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
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

<<<<<<< HEAD
    // public function getHeuresDisp(): array
    // {
    //     return $this->heuresDisp;
    // }
=======
  
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b

    public function getHeuresDisp(): array
{
    return $this->heuresDisp ?? [];
}

public function setHeuresDisp(array $heuresDisp): self
{
    $this->heuresDisp = $heuresDisp;
    return $this;
}

<<<<<<< HEAD
    // public function setHeuresDisp(array $heuresDisp): static
    // {
    //     $this->heuresDisp = $heuresDisp;

    //     return $this;
    // }

=======
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b
    public function getStatutDisp(): ?string
    {
        return $this->statutDisp;
    }

    public function setStatutDisp(string $statutDisp): static
    {
        $this->statutDisp = $statutDisp;

        return $this;
    }

<<<<<<< HEAD

=======
    public function getIdMedecin(): ?int
    {
        return $this->idMedecin;
    }

    public function setIdMedecin(?int $idMedecin): static
    {
        $this->idMedecin = $idMedecin;

        return $this;
    }
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b
    public function isReserved(): bool
    {
        return in_array(strtolower($this->statutDisp), ['Disponible', 'Indisponible']);
    }

    /**
     * @return Collection<int, RendezVous>
     */
    public function getRendezVouses(): Collection
    {
        return $this->rendezVouses;
    }

    public function addRendezVouse(RendezVous $rendezVouse): static
    {
        if (!$this->rendezVouses->contains($rendezVouse)) {
            $this->rendezVouses->add($rendezVouse);
            $rendezVouse->setHeureR($this);
        }

        return $this;
    }

    public function removeRendezVouse(RendezVous $rendezVouse): static
    {
        if ($this->rendezVouses->removeElement($rendezVouse)) {
<<<<<<< HEAD
=======
         
>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b
            if ($rendezVouse->getHeureR() === $this) {
                $rendezVouse->setHeureR(null);
            }
        }

        return $this;
    }
<<<<<<< HEAD

    public function getIdMedecin(): ?User
    {
        return $this->idMedecin;
    }

    public function setIdMedecin(?User $idMedecin): self
    {
        $this->idMedecin = $idMedecin;

        return $this;
    }
=======
    

>>>>>>> dd238d0bf7bf109232031f042ecf1572bb2c662b
}
