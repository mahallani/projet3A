<?php

namespace App\Entity;

use App\Repository\SuiviGrossesseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SuiviGrossesseRepository::class)]
class SuiviGrossesse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateSuivi = null;

    #[Assert\NotBlank(message: "Veuillez saisir le poids.")]
    #[Assert\Range(
        min: 30,
        max: 200,
        notInRangeMessage: "Le poids doit être compris entre {{ min }} et {{ max }} kg."
    )]
    #[ORM\Column(nullable: false)] // Le champ est maintenant obligatoire en base de données
    private ?float $poids = null;

    #[Assert\NotBlank(message: "Veuillez saisir la tension.")]
    #[Assert\Range(
        min: 6,
        max: 20,
        notInRangeMessage: "La tension doit être comprise entre {{ min }} et {{ max }}."
    )]
    #[ORM\Column(nullable: false)]
    private ?float $tension = null;

    #[Assert\NotBlank(message: "Le champ symptômes ne peut pas être vide.")]
    #[ORM\Column(length: 255, nullable: false)]
    private ?string $symptomes = null;

    #[Assert\NotBlank(message: "Veuillez sélectionner un état de grossesse.")]
    #[ORM\Column(length: 50, nullable: false)]
    private ?string $etatGrossesse = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateSuivi(): ?\DateTimeInterface
    {
        return $this->dateSuivi;
    }

    public function setDateSuivi(?\DateTimeInterface $dateSuivi): static
    {
        $this->dateSuivi = $dateSuivi;
        return $this;
    }

    public function getPoids(): ?float
    {
        return $this->poids;
    }

    public function setPoids(?float $poids): static
    {
        $this->poids = $poids;
        return $this;
    }

    public function getTension(): ?float
    {
        return $this->tension;
    }

    public function setTension(?float $tension): static
    {
        $this->tension = $tension;
        return $this;
    }

    public function getSymptomes(): ?string
    {
        return $this->symptomes;
    }

    public function setSymptomes(?string $symptomes): static
    {
        $this->symptomes = $symptomes;
        return $this;
    }

    public function getEtatGrossesse(): ?string
    {
        return $this->etatGrossesse;
    }

    public function setEtatGrossesse(?string $etatGrossesse): static
    {
        $this->etatGrossesse = $etatGrossesse;
        return $this;
    }
}
