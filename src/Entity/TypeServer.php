<?php

namespace App\Entity;

use App\Repository\TypeServerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeServerRepository::class)]
class TypeServer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Server>
     */
    #[ORM\OneToMany(targetEntity: Server::class, mappedBy: 'type')]
    private Collection $servers;

    public function __construct()
    {
        $this->servers = new ArrayCollection();
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
            $server->setType($this);
        }

        return $this;
    }

    public function removeServer(Server $server): static
    {
        if ($this->servers->removeElement($server)) {
            // set the owning side to null (unless already changed)
            if ($server->getType() === $this) {
                $server->setType(null);
            }
        }

        return $this;
    }
}
