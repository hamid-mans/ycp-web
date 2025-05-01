<?php

namespace App\Service;

use App\Entity\Traceur;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class TraceurService
{
    private string $type;
    private string $data;
    private int $dataId;
    private string $username;
    private EntityManagerInterface $entityManager;
    private Security $security;
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->requestStack = $requestStack;
    }

    public function trace(string $type, string $data, int $dataId): void
    {
        $user = $this->security->getUser();
        $request = $this->requestStack->getCurrentRequest();

        if (!$user instanceof User) {
            return; // Si ce n'est pas un objet User
        }

        $traceur = new Traceur();
        $traceur->setType($type);
        $traceur->setData($data);
        $traceur->setDataId($dataId);
        $traceur->setUsername($user->getName() . " | " . $request->getClientIp());
        $traceur->setDatetime(new \DateTime());

        $this->entityManager->persist($traceur);
        $this->entityManager->flush();
    }
}