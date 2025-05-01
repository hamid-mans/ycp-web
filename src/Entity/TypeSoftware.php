<?php

namespace App\Entity;

use App\Repository\TypeSoftwareRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeSoftwareRepository::class)]
class TypeSoftware
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Software>
     */
    #[ORM\OneToMany(targetEntity: Software::class, mappedBy: 'type')]
    private Collection $software;

    public function __construct()
    {
        $this->software = new ArrayCollection();
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
            $software->setType($this);
        }

        return $this;
    }

    public function removeSoftware(Software $software): static
    {
        if ($this->software->removeElement($software)) {
            // set the owning side to null (unless already changed)
            if ($software->getType() === $this) {
                $software->setType(null);
            }
        }

        return $this;
    }
}
