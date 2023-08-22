<?php

namespace App\Data;


use App\Entity\Campus;
use App\Entity\Sortie;
use DateTimeInterface;
use App\Form\SortieType;
use App\Entity\Participant;
use Doctrine\ORM\Mapping\Id;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Polyfill\Intl\Icu\IntlDateFormatter;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;

class SearchData 
{

    
    /**
     * @var string
     */
    public ?string $q = '';

     /**
     * @var Campus|null
     */
    public ?Campus $siteOrganisateur = null;

    private ?Campus $campus = null;



    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }


    /**
     * @var \DateTimeInterface|null
     */
    public ?\DateTimeInterface $dateMin = null;

    /**
     * @var \DateTimeInterface|null
     */
    public ?\DateTimeInterface $dateMax = null;
    public function getDateMin(): ?DateTimeInterface
    {
        return $this->dateMin;
    }

    public function setDateMin(?\DateTimeInterface $dateMin): self
    {
        $this->dateMin = $dateMin;

        return $this;
    }

    public function getDateMax(): ?\DateTimeInterface
    {
        return $this->dateMax;
    }

    public function setDateMax(?\DateTimeInterface $dateMax): self
    {
        $this->dateMax = $dateMax;

        return $this;
    }
    /**
     * @var bool
     */
    public bool $inscrit = true;

    public function isInscrit(): bool
    {
        return $this->inscrit;
    }

    public function setInscrit(bool $inscrit): self
    {
        $this->inscrit = $inscrit;
        return $this;
    }
    /**
     * @var Participant|null
     */
    public ?Participant $user = null;

    // ...

    public function setUser(?Participant $user): self
    {
        $this->user = $user;

        return $this;
    }
/**
     * @var bool
     */
    public bool $organisateur = true;

    public function isOrganisateur(): bool
    {
        return $this->organisateur;
    }

    public function setOrganisateur(bool $organisateur): self
    {
        $this->organisateur = $organisateur;
        return $this;
    }

    public bool $nonInscrit = true;
    public ?\DateTime $currentDate = null;
    public function __construct()
    {
        $this->currentDate = new \DateTime(); // Initialisation dans le constructeur
    }
    
/*    * @var bool
    */
   public bool $sortiePassee = false;

}