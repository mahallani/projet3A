<?php

namespace App\Entity;

use App\Repository\OrdonnanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Contracts\Translation\TranslatorInterface;

#[ORM\Entity(repositoryClass: OrdonnanceRepository::class)]
class Ordonnance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: "Le médicament est obligatoire.")]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: "Le nom du médicament doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le nom du médicament ne peut pas dépasser {{ limit }} caractères."
    )]
    #[ORM\Column(length: 255)]
    private ?string $medicament = null;

    #[Assert\NotBlank(message: "La posologie est obligatoire.")]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: "La posologie doit contenir au moins {{ limit }} caractères.",
        maxMessage: "La posologie ne peut pas dépasser {{ limit }} caractères."
    )]
    #[ORM\Column(length: 255)]
    private ?string $posologie = null;

    #[Assert\NotBlank(message: "La date de prescription est obligatoire.")]
    #[Assert\Type(type: "\DateTimeInterface", message: "La date doit être valide.")]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_prescription = null;

    /**
     * @var Collection<int, Traitement>
     */
    #[ORM\OneToMany(targetEntity: Traitement::class, mappedBy: 'ordonnance', cascade: ['persist', 'remove'])]
    private Collection $traitements;

    public function __construct()
    {
        $this->traitements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMedicament(): ?string
    {
        return $this->medicament;
    }

    public function setMedicament(string $medicament): static
    {
        $this->medicament = $medicament;
        return $this;
    }

    public function getPosologie(): ?string
    {
        return $this->posologie;
    }

    public function setPosologie(string $posologie): static
    {
        $this->posologie = $posologie;
        return $this;
    }

    public function getDatePrescription(): ?\DateTimeInterface
    {
        return $this->date_prescription;
    }

    public function setDatePrescription(\DateTimeInterface $date_prescription): static
    {
        $this->date_prescription = $date_prescription;
        return $this;
    }

    /**
     * @return Collection<int, Traitement>
     */
    public function getTraitements(): Collection
    {
        return $this->traitements;
    }

    public function addTraitement(Traitement $traitement): static
    {
        if (!$this->traitements->contains($traitement)) {
            $this->traitements->add($traitement);
            $traitement->setOrdonnance($this);
        }
        return $this;
    }

    public function removeTraitement(Traitement $traitement): static
    {
        if ($this->traitements->removeElement($traitement)) {
            if ($traitement->getOrdonnance() === $this) {
                $traitement->setOrdonnance(null);
            }
        }
        return $this;
    }

    /**
     * Get translated labels for the entity fields.
     */
    public function getTranslatedLabels(TranslatorInterface $translator): array
    {
        return [
            'medicament' => $translator->trans('Medicament'),
            'posologie' => $translator->trans('Dosage'),
            'date_prescription' => $translator->trans('Prescription Date'),
        ];
    }
}