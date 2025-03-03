<?php

namespace App\Entity;

use App\Repository\SuiviBebeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SuiviBebeRepository::class)]
class SuiviBebe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false)] // Permet que ce champ soit optionnel
    private ?\DateTime $date_suivi = null;

    #[ORM\Column]
    private ?float $PoidsBebe = null;

    #[ORM\Column]
    private ?float $tailleBebe = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Veuillez sélectionner l'état de santé.")]
    private ?string $EtatSante = null;

   

    #[ORM\Column]
    #[Assert\NotBlank(message: "Veuillez sélectionner le battement de coeur.")]
    private ?float $BattementCoeur = null;

    #[ORM\ManyToOne(targetEntity: SuiviGrossesse::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")] // Ajout de onDelete="CASCADE"
    private ?SuiviGrossesse $suiviGrossesse = null;
    

    #[ORM\Column(length: 50, name: "appetitBebe")] // 🔹 Force le nom du champ dans la BD
    #[Assert\NotBlank(message: "Veuillez sélectionner l'appétit du bébé.")]
    private ?string $appetitBebe = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateSuivi(): ?\DateTime
    {
        return $this->date_suivi;
    }

    public function setDateSuivi(?\DateTime $date_suivi): self
    {
        $this->date_suivi = $date_suivi;
        return $this;
    }

    public function getPoidsBebe(): ?float
    {
        return $this->PoidsBebe;
    }

    public function setPoidsBebe(float $PoidsBebe): static
    {
        $this->PoidsBebe = $PoidsBebe;

        return $this;
    }

    public function getTailleBebe(): ?float
    {
        return $this->tailleBebe;
    }

    public function setTailleBebe(float $tailleBebe): static
    {
        $this->tailleBebe = $tailleBebe;

        return $this;
    }

    public function getEtatSante(): ?string
    {
        return $this->EtatSante;
    }

    public function setEtatSante(string $EtatSante): static
    {
        $this->EtatSante = $EtatSante;

        return $this;
    }

   

    public function getBattementCoeur(): ?float
    {
        return $this->BattementCoeur;
    }

    public function setBattementCoeur(float $BattementCoeur): static
    {
        $this->BattementCoeur = $BattementCoeur;

        return $this;
    }

    public function getSuiviGrossesse(): ?SuiviGrossesse
    {
        return $this->suiviGrossesse;
    }
    
    public function setSuiviGrossesse(?SuiviGrossesse $suiviGrossesse): static
    {
        $this->suiviGrossesse = $suiviGrossesse;
        return $this;
    }

    public function getAppetitBebe(): ?string
    {
        return $this->appetitBebe;
    }

    public function setAppetitBebe(string $appetitBebe): static
    {
        $this->appetitBebe = $appetitBebe;

        return $this;
    }
    
}
