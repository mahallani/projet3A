<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Commentaire;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "le titre est vide.")]
    #[Assert\Length(max: 255, maxMessage: "le titre est depasse  {{ limit }} charactérs.")]
    #[Assert\Length(min: 2, minMessage: "le titre est au minimum contient {{ limit }} characters.")]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "le contenu est vide.")]
    #[Assert\Length(min: 2, minMessage: "le contenu est au minimum contient {{ limit }} characters.")]
    #[Assert\Length(max: 255, maxMessage: "le contenu est depasse  {{ limit }} charactérs.")]
    private ?string $contenu = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datearticle = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Galerie est vide.")]
    #[Assert\Length(min: 2, minMessage: "la galerie est au minimum contient {{ limit }} characters.")]
    #[Assert\Length(max: 255, maxMessage: "la galerie est depassee  {{ limit }} charactérs.")]
    private ?string $galerie = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: " vide ! .")]
    #[Assert\GreaterThanOrEqual(0, message: "entrer un nombre positif.")]
    private ?int $nbrevue = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: " vide ! .")]
    #[Assert\GreaterThanOrEqual(0, message: "entrer un nombre positif.")]
    private ?int $nbrelike = null;

    /**
     * @var Collection<int, Commentaire>
     */
    #[ORM\OneToMany(targetEntity: Commentaire::class, mappedBy: 'article')]
    private Collection $Relation;

    public function __construct()
    {
        $this->Relation = new ArrayCollection();
        $this->commentaire = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDatearticle(): ?\DateTimeInterface
    {
        return $this->datearticle;
    }

    public function setDatearticle(\DateTimeInterface $datearticle): static
    {
        $this->datearticle = $datearticle;

        return $this;
    }

    public function getGalerie(): ?string
    {
        return $this->galerie;
    }

    public function setGalerie(string $galerie): static
    {
        $this->galerie = $galerie;

        return $this;
    }

    public function getNbrevue(): ?int
    {
        return $this->nbrevue;
    }

    public function setNbrevue(int $nbrevue): static
    {
        $this->nbrevue = $nbrevue;

        return $this;
    }

    public function getNbrelike(): ?int
    {
        return $this->nbrelike;
    }

    public function setNbrelike(int $nbrelike): static
    {
        $this->nbrelike = $nbrelike;

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getRelation(): Collection
    {
        return $this->Relation;
    }

    public function addRelation(Commentaire $relation): static
    {
        if (!$this->Relation->contains($relation)) {
            $this->Relation->add($relation);
            $relation->setArticle($this);
        }

        return $this;
    }

    public function removeRelation(Commentaire $relation): static
    {
        if ($this->Relation->removeElement($relation)) {
            // set the owning side to null (unless already changed)
            if ($relation->getArticle() === $this) {
                $relation->setArticle(null);
            }
        }

        return $this;
    }
    

    public function getCommentaire(): Collection
    {
        return $this->commentaire;
    }
}
