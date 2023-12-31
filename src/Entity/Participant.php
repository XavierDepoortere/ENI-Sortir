<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ParticipantRepository::class)]
class Participant implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    //Email non vide / avec format type nom d'utilisateur + @ + nom de domaine + extension / Unique + longueur 180 (lié à l'unicité)
    #[Assert\NotBlank(message : "L'adresse Email ne peut pas être vide")]
    #[Assert\Email(mode: 'strict', message: "L'adresse Email n'est pas valide (ex : exemple@domaine.com)")]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    private ?array $roles =[];

    /**
     * @var string The hashed password
     */

    // #[Assert\PasswordStrength(minStrength: 'medium', message: "Le mot de passe doit être plus complexe.")] ==> pas intégré pour ne pas avoir de soucis avec le mot de passe actuel
    #[Assert\NotBlank(message : "Le Password ne peut pas être vide")]
    #[Assert\Length(min: 5, max: 255)]
    #[ORM\Column(length: 255)]
    private ?string $motPasse = null;

    #[Assert\NotBlank(message : "Le Nom ne peut pas être vide")]
    #[Assert\Length(min: 3, max: 50)]
    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[Assert\NotBlank(message : "Le Prénom ne peut pas être vide")]
    #[Assert\Length(min:3, max: 50)]
    #[ORM\Column(length: 50)]
    private ?string $prenom = null;

    #[Assert\NotBlank(message : "Le Pseudo ne peut pas être vide")]
    #[Assert\Length(min:3, max: 50)]
    #[ORM\Column(length: 50, unique: true)]
    private ?string $pseudo = null;

    #[Assert\Length(min: 10, max: 14)]
    #[ORM\Column(length: 14, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column]
    private ?bool $administrateur = null;

    #[ORM\Column]
    private ?bool $actif = null;

    #[ORM\ManyToOne(inversedBy: 'participants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Campus $campus = null;

    #[ORM\ManyToMany(targetEntity: Sortie::class, mappedBy: 'estInscrit')]
    private Collection $sorties;

    #[ORM\OneToMany(mappedBy: 'organisateur', targetEntity: Sortie::class)]
    private Collection $organisateurSorties;


    public function __construct()
    {
        $this->sorties = new ArrayCollection();
        $this->organisateurSorties = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        if($this->administrateur){
            $roles[] = 'ROLE_ADMIN';
        }

        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->motPasse;
    }

    public function setMotPasse(?string $motPasse): static
    {
        $this->motPasse = $motPasse;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function isAdministrateur(): ?bool
    {
        return $this->administrateur;
    }

    public function setAdministrateur(bool $administrateur): static
    {
        $this->administrateur = $administrateur;

        return $this;
    }

    public function isActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): static
    {
        $this->actif = $actif;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): static
    {
        $this->campus = $campus;

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
            $sorty->addEstInscrit($this);
        }

        return $this;
    }

    public function removeSorty(Sortie $sorty): static
    {
        if ($this->sorties->removeElement($sorty)) {
            $sorty->removeEstInscrit($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getOrganisateurSorties(): Collection
    {
        return $this->organisateurSorties;
    }

    public function addOrganisateurSorty(Sortie $organisateurSorty): static
    {
        if (!$this->organisateurSorties->contains($organisateurSorty)) {
            $this->organisateurSorties->add($organisateurSorty);
            $organisateurSorty->setOrganisateur($this);
        }

        return $this;
    }
    

    public function removeOrganisateurSorty(Sortie $organisateurSorty): static
    {
        if ($this->organisateurSorties->removeElement($organisateurSorty)) {
            // set the owning side to null (unless already changed)
            if ($organisateurSorty->getOrganisateur() === $this) {
                $organisateurSorty->setOrganisateur(null);
            }
        }

        return $this;
    }

  
}