<?php

namespace App\Entity;

use App\Repository\TypeDatabaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeDatabaseRepository::class)]
class TypeDatabase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Database>
     */
    #[ORM\OneToMany(targetEntity: Database::class, mappedBy: 'type')]
    private Collection $bdd;

    public function __construct()
    {
        $this->bdd = new ArrayCollection();
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
            $bdd->setType($this);
        }

        return $this;
    }

    public function removeBdd(Database $bdd): static
    {
        if ($this->bdd->removeElement($bdd)) {
            // set the owning side to null (unless already changed)
            if ($bdd->getType() === $this) {
                $bdd->setType(null);
            }
        }

        return $this;
    }
}
