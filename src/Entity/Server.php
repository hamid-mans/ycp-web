<?php

namespace App\Entity;

use App\Repository\ServerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServerRepository::class)]
class Server
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'server')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customers $customer = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $localIp = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $publicIp = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $login = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $password = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

    #[ORM\ManyToOne(inversedBy: 'servers')]
    private ?TypeServer $type = null;

    #[ORM\ManyToOne(inversedBy: 'servers')]
    private ?RemoteControlTool $remoteControl = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?customers
    {
        return $this->customer;
    }

    public function setCustomer(?customers $customer): static
    {
        $this->customer = $customer;

        return $this;
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

    public function getLocalIp(): ?string
    {
        return $this->localIp;
    }

    public function setLocalIp(?string $localIp): static
    {
        $this->localIp = $localIp;

        return $this;
    }

    public function getPublicIp(): ?string
    {
        return $this->publicIp;
    }

    public function setPublicIp(?string $publicIp): static
    {
        $this->publicIp = $publicIp;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(?string $login): static
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getType(): ?TypeServer
    {
        return $this->type;
    }

    public function setType(?TypeServer $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getRemoteControl(): ?RemoteControlTool
    {
        return $this->remoteControl;
    }

    public function setRemoteControl(?RemoteControlTool $remoteControl): static
    {
        $this->remoteControl = $remoteControl;

        return $this;
    }
}
