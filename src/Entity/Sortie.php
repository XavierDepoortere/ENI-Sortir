<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SortieRepository::class)]
class Sortie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateHeureDebut = null;

    #[ORM\Column]
    private ?int $duree = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateLimiteInscription = null;

    #[ORM\Column]
    private ?int $nbInscriptionsMax = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $infosSortie = null;

    #[ORM\ManyToOne(inversedBy: 'sorties')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Campus $siteOrganisateur = null;

    #[ORM\ManyToOne(inversedBy: 'sorties')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Etat $etats = null;

    #[ORM\ManyToOne(inversedBy: 'sorties')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Lieu $lieux = null;

    #[ORM\ManyToMany(targetEntity: Participant::class, inversedBy: 'sorties')]
    private Collection $estInscrit;

    #[ORM\ManyToOne(inversedBy: 'organisateurSorties')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Participant $organisateur = null;

    public function __construct()
    {
        $this->estInscrit = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDateHeureDebut(): ?\DateTimeInterface
    {
        return $this->dateHeureDebut;
    }

    public function setDateHeureDebut(\DateTimeInterface $dateHeureDebut): static
    {
        $this->dateHeureDebut = $dateHeureDebut;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDateLimiteInscription(): ?\DateTimeInterface
    {
        return $this->dateLimiteInscription;
    }

    public function setDateLimiteInscription(\DateTimeInterface $dateLimiteInscription): static
    {
        $this->dateLimiteInscription = $dateLimiteInscription;

        return $this;
    }

    public function getNbInscriptionsMax(): ?int
    {
        return $this->nbInscriptionsMax;
    }

    public function setNbInscriptionsMax(int $nbInscriptionsMax): static
    {
        $this->nbInscriptionsMax = $nbInscriptionsMax;

        return $this;
    }

    public function getInfosSortie(): ?string
    {
        return $this->infosSortie;
    }

    public function setInfosSortie(?string $infosSortie): static
    {
        $this->infosSortie = $infosSortie;

        return $this;
    }

    public function getSiteOrganisateur(): ?Campus
    {
        return $this->siteOrganisateur;
    }

    public function setSiteOrganisateur(?Campus $siteOrganisateur): static
    {
        $this->siteOrganisateur = $siteOrganisateur;

        return $this;
    }

    public function getEtats(): ?Etat
    {
        return $this->etats;
    }

    public function setEtats(?Etat $etats): static
    {
        $this->etats = $etats;

        return $this;
    }

    public function getLieux(): ?Lieu
    {
        return $this->lieux;
    }

    public function setLieux(?Lieu $lieux): static
    {
        $this->lieux = $lieux;

        return $this;
    }

    /**
     * @return Collection<int, Participant>
     */
    public function getEstInscrit(): Collection
    {
        return $this->estInscrit;
    }

    public function addEstInscrit(Participant $estInscrit): static
    {
        if (!$this->estInscrit->contains($estInscrit)) {
            $this->estInscrit->add($estInscrit);
        }

        return $this;
    }

    public function removeEstInscrit(Participant $estInscrit): static
    {
        $this->estInscrit->removeElement($estInscrit);

        return $this;
    }

    public function getOrganisateur(): ?Participant
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?Participant $organisateur): static
    {
        $this->organisateur = $organisateur;

        return $this;
    }
}
