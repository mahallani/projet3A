<?php

namespace App\Entity;

use App\Repository\TraitementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Contracts\Translation\TranslatorInterface;

#[ORM\Entity(repositoryClass: TraitementRepository::class)]
class Traitement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: "La date de prescription ne peut pas être vide")]
    #[Assert\Type("\DateTimeInterface", message: "La date doit être valide")]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datePrescription = null;

    #[Assert\NotBlank(message: "L'historique du traitement ne peut pas être vide")]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: "L'historique du traitement doit contenir au moins {{ limit }} caractères",
        maxMessage: "L'historique du traitement ne peut pas dépasser {{ limit }} caractères"
    )]
    #[ORM\Column(length: 255)]
    private ?string $historiqueTraitement = null;

    #[Assert\NotNull(message: "L'ordonnance est obligatoire")]
    #[ORM\ManyToOne(targetEntity: Ordonnance::class, inversedBy: 'traitements')]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?Ordonnance $ordonnance = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatePrescription(): ?\DateTimeInterface
    {
        return $this->datePrescription;
    }

    public function setDatePrescription(\DateTimeInterface $datePrescription): static
    {
        $this->datePrescription = $datePrescription;
        return $this;
    }

    public function getHistoriqueTraitement(): ?string
    {
        return $this->historiqueTraitement;
    }

    public function setHistoriqueTraitement(string $historiqueTraitement): static
    {
        $this->historiqueTraitement = $historiqueTraitement;
        return $this;
    }

    public function getOrdonnance(): ?Ordonnance
    {
        return $this->ordonnance;
    }

    public function setOrdonnance(?Ordonnance $ordonnance): static
    {
        $this->ordonnance = $ordonnance;
        return $this;
    }

    /**
     * Get translated labels for the entity fields.
     */
    public function getTranslatedLabels(TranslatorInterface $translator): array
    {
        return [
            'datePrescription' => $translator->trans('Prescription Date'),
            'historiqueTraitement' => $translator->trans('Treatment History'),
            'ordonnance' => $translator->trans('Prescription'),
        ];
    }
}