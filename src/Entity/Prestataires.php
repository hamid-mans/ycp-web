<?php

namespace App\Entity;

use App\Repository\PrestatairesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrestatairesRepository::class)]
class Prestataires
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cop = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contact_name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contact_phone = null;

    /**
     * @var Collection<int, Customers>
     */
    #[ORM\ManyToMany(targetEntity: Customers::class, inversedBy: 'prestataires')]
    private Collection $customer;

    public function __construct()
    {
        $this->customer = new ArrayCollection();
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

    public function getCop(): ?string
    {
        return $this->cop;
    }

    public function setCop(string $cop): static
    {
        $this->cop = $cop;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getContactName(): ?string
    {
        return $this->contact_name;
    }

    public function setContactName(?string $contact_name): static
    {
        $this->contact_name = $contact_name;

        return $this;
    }

    public function getContactPhone(): ?string
    {
        return $this->contact_phone;
    }

    public function setContactPhone(?string $contact_phone): static
    {
        $this->contact_phone = $contact_phone;

        return $this;
    }

    /**
     * @return Collection<int, Customers>
     */
    public function getCustomer(): Collection
    {
        return $this->customer;
    }

    public function addCustomer(Customers $customer): static
    {
        if (!$this->customer->contains($customer)) {
            $this->customer->add($customer);
        }

        return $this;
    }

    public function removeCustomer(Customers $customer): static
    {
        $this->customer->removeElement($customer);

        return $this;
    }
}
