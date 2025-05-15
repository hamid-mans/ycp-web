<?php

namespace App\Entity;

use App\Repository\WebdevRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WebdevRepository::class)]
class Webdev
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'webdevs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customers $customer = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $serialNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $activationKey = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $echeanceDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?Customers
    {
        return $this->customer;
    }

    public function setCustomer(?Customers $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function getSerialNumber(): ?string
    {
        return $this->serialNumber;
    }

    public function setSerialNumber(?string $serialNumber): static
    {
        $this->serialNumber = $serialNumber;

        return $this;
    }

    public function getActivationKey(): ?string
    {
        return $this->activationKey;
    }

    public function setActivationKey(?string $activationKey): static
    {
        $this->activationKey = $activationKey;

        return $this;
    }

    public function getEcheanceDate(): ?\DateTimeInterface
    {
        return $this->echeanceDate;
    }

    public function setEcheanceDate(?\DateTimeInterface $echeanceDate): static
    {
        $this->echeanceDate = $echeanceDate;

        return $this;
    }
}
