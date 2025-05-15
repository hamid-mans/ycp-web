<?php

namespace App\Entity;

use App\Repository\CustomersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomersRepository::class)]
class Customers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cop = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $city = null;

    /**
     * @var Collection<int, Server>
     */
    #[ORM\OneToMany(targetEntity: Server::class, mappedBy: 'customer', cascade: ['remove'])]
    private Collection $servers;

    /**
     * @var Collection<int, Prestataires>
     */
    #[ORM\ManyToMany(targetEntity: Prestataires::class, mappedBy: 'customer')]
    private Collection $prestataires;

    /**
     * @var Collection<int, Database>
     */
    #[ORM\OneToMany(targetEntity: Database::class, mappedBy: 'customer', orphanRemoval: true)]
    private Collection $bdd;

    /**
     * @var Collection<int, Software>
     */
    #[ORM\OneToMany(targetEntity: Software::class, mappedBy: 'customer')]
    private Collection $software;

    /**
     * @var Collection<int, Webdev>
     */
    #[ORM\OneToMany(targetEntity: Webdev::class, mappedBy: 'customer')]
    private Collection $webdevs;

    public function __construct()
    {
        $this->servers = new ArrayCollection();
        $this->prestataires = new ArrayCollection();
        $this->bdd = new ArrayCollection();
        $this->software = new ArrayCollection();
        $this->webdevs = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
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


    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCop(): ?string
    {
        return $this->cop;
    }

    public function setCop(?string $cop): static
    {
        $this->cop = $cop;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection<int, Server>
     */
    public function getServers(): Collection
    {
        return $this->servers;
    }

    public function addServer(Server $server): static
    {
        if (!$this->servers->contains($server)) {
            $this->servers->add($server);
            $server->setCustomer($this);
        }

        return $this;
    }

    public function removeServer(Server $server): static
    {
        if ($this->servers->removeElement($server)) {
            // set the owning side to null (unless already changed)
            if ($server->getCustomer() === $this) {
                $server->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Prestataires>
     */
    public function getPrestataires(): Collection
    {
        return $this->prestataires;
    }

    public function addPrestataire(Prestataires $prestataire): static
    {
        if (!$this->prestataires->contains($prestataire)) {
            $this->prestataires->add($prestataire);
            $prestataire->addCustomer($this);
        }

        return $this;
    }

    public function removePrestataire(Prestataires $prestataire): static
    {
        if ($this->prestataires->removeElement($prestataire)) {
            $prestataire->removeCustomer($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Database>
     */
    public function getBdd(): Collection
    {
        return $this->bdd;
    }

    public function addBdd(Database $bdd): static
    {
        if (!$this->bdd->contains($bdd)) {
            $this->bdd->add($bdd);
            $bdd->setCustomer($this);
        }

        return $this;
    }

    public function removeBdd(Database $bdd): static
    {
        if ($this->bdd->removeElement($bdd)) {
            // set the owning side to null (unless already changed)
            if ($bdd->getCustomer() === $this) {
                $bdd->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Software>
     */
    public function getSoftware(): Collection
    {
        return $this->software;
    }

    public function addSoftware(Software $software): static
    {
        if (!$this->software->contains($software)) {
            $this->software->add($software);
            $software->setCustomer($this);
        }

        return $this;
    }

    public function removeSoftware(Software $software): static
    {
        if ($this->software->removeElement($software)) {
            // set the owning side to null (unless already changed)
            if ($software->getCustomer() === $this) {
                $software->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Webdev>
     */
    public function getWebdevs(): Collection
    {
        return $this->webdevs;
    }

    public function addWebdev(Webdev $webdev): static
    {
        if (!$this->webdevs->contains($webdev)) {
            $this->webdevs->add($webdev);
            $webdev->setCustomer($this);
        }

        return $this;
    }

    public function removeWebdev(Webdev $webdev): static
    {
        if ($this->webdevs->removeElement($webdev)) {
            // set the owning side to null (unless already changed)
            if ($webdev->getCustomer() === $this) {
                $webdev->setCustomer(null);
            }
        }

        return $this;
    }
}
