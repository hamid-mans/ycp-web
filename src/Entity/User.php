<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Traceur>
     */
    #[ORM\OneToMany(targetEntity: Traceur::class, mappedBy: 'user')]
    private Collection $traceurs;

    #[ORM\ManyToMany(targetEntity: Server::class)]
    #[ORM\JoinTable(name: 'user_servers_forbidden')]
    private Collection $serversForbidden;

    #[ORM\ManyToMany(targetEntity: Software::class)]
    #[ORM\JoinTable(name: 'user_softwares_forbidden')]
    private Collection $softwaresForbidden;

    #[ORM\ManyToMany(targetEntity: Database::class)]
    #[ORM\JoinTable(name: 'user_databases_forbidden')]
    private Collection $databasesForbidden;


    public function __construct()
    {
        $this->traceurs = new ArrayCollection();
        $this->serversForbidden = new ArrayCollection();
        $this->softwaresForbidden = new ArrayCollection();
        $this->databasesForbidden = new ArrayCollection();
    }

    public function getServersForbidden(): Collection
    {
        return $this->serversForbidden;
    }
    public function addServerForbidden(Server $server): static
    {
        if (!$this->serversForbidden->contains($server)) {
            $this->serversForbidden->add($server);
        }

        return $this;
    }
    public function removeServerForbidden(Server $server): static
    {
        $this->serversForbidden->removeElement($server);

        return $this;
    }



    public function getDatabasesForbidden(): Collection
    {
        return $this->databasesForbidden;
    }
    public function addDatabaseForbidden(Database $database): static
    {
        if (!$this->databasesForbidden->contains($database)) {
            $this->databasesForbidden->add($database);
        }

        return $this;
    }
    public function removeDatabaseForbidden(Database $database): static
    {
        $this->databasesForbidden->removeElement($database);

        return $this;
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
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
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
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Traceur>
     */
    public function getTraceurs(): Collection
    {
        return $this->traceurs;
    }

    public function addTraceur(Traceur $traceur): static
    {
        if (!$this->traceurs->contains($traceur)) {
            $this->traceurs->add($traceur);
            $traceur->setUser($this);
        }

        return $this;
    }

    public function removeTraceur(Traceur $traceur): static
    {
        if ($this->traceurs->removeElement($traceur)) {
            // set the owning side to null (unless already changed)
            if ($traceur->getUser() === $this) {
                $traceur->setUser(null);
            }
        }

        return $this;
    }

    public function getSoftwaresForbidden(): Collection
    {
        return $this->softwaresForbidden;
    }
    public function addSoftwareForbidden(Software $software): static
    {
        if (!$this->softwaresForbidden->contains($software)) {
            $this->softwaresForbidden->add($software);
        }

        return $this;
    }
    public function removeSoftwareForbidden(Software $software): static
    {
        $this->softwaresForbidden->removeElement($software);

        return $this;
    }
}
