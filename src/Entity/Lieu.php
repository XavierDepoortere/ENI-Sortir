<?php

namespace App\Entity;

use App\Repository\LieuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Ville;

#[ORM\Entity(repositoryClass: LieuRepository::class)]
class Lieu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: "Le nom du lieu ne peut pas être vide")]
    #[Assert\Length(min : 4, max: 50)]
    #[ORM\Column(length: 50)]
    private ?string $nom = null;


    #[Assert\NotBlank(message: "Le nom de la rue ne peut pas être vide")]
    #[Assert\Length(min : 4, max: 100)]
    #[ORM\Column(length: 100)]
    private ?string $rue = null;

    #[Assert\Regex(pattern:"/^\d{3}\.\d{6}$/", message:"La latitude doit avoir 3 chiffres avant la virgule et 6 chiffres après")]
    #[ORM\Column (nullable:true)] 
    private ?float $latitude = null;

    #[Assert\Regex(pattern:"/^\d{3}\.\d{6}$/", message:"La longitude doit avoir 3 chiffres avant la virgule et 6 chiffres après")]
    #[ORM\Column (nullable:true)]
    private ?float $longitude = null;

    #[ORM\ManyToOne(targetEntity:Ville::class,inversedBy: 'lieux', cascade:['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ville $ville = null;

    #[ORM\OneToMany(mappedBy: 'lieux', targetEntity: Sortie::class)]
    private Collection $sorties;

    public function __construct()
    {
        $this->sorties = new ArrayCollection();
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

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(string $rue): static
    {
        $this->rue = $rue;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    public function addSorty(Sortie $sorty): static
    {
        if (!$this->sorties->contains($sorty)) {
            $this->sorties->add($sorty);
            $sorty->setLieux($this);
        }

        return $this;
    }

    public function removeSorty(Sortie $sorty): static
    {
        if ($this->sorties->removeElement($sorty)) {
            // set the owning side to null (unless already changed)
            if ($sorty->getLieux() === $this) {
                $sorty->setLieux(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nom;
    }
}
