<?php

namespace App\Controller;

use App\Entity\Software;
use App\Form\SoftwareType;
use App\Repository\SoftwareRepository;
use App\Service\TraceurService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/logiciels')]
class SoftwareController extends AbstractController
{
    private TraceurService $traceur;

    public function __construct(TraceurService $traceurService)
    {
        $this->traceur = $traceurService;
    }

    #[Route('', name: 'app_dashboard_software')]
    public function index(SoftwareRepository $softwareRepository): Response
    {
        return $this->render('dashboard/software/softwares.html.twig', [
            'softwares' => $softwareRepository->findAllUser($this->getUser()),
        ]);
    }

    #[Route('/nouveau', name: 'app_dashboard_software_new')]
    public function new(Request $request, SoftwareRepository $softwareRepository, EntityManagerInterface $entityManager): Response
    {
        $software = new Software();
        $form = $this->createForm(SoftwareType::class, $software, [
            'submit_label' => '<i class="plus icon"></i>Ajouter',
            'submit_class' => 'ui labeled icon green button'
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($software);
            $entityManager->flush();

            $this->traceur->trace("CREATE", "Logiciels", $software->getId());

            $this->addFlash('success', 'Logiciel ajouté avec succès');

            return $this->redirectToRoute('app_dashboard_software');
        }

        return $this->render('dashboard/software/add_software.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/modifier/{id}', name: 'app_dashboard_software_edit')]
    public function edit(int $id, Request $request, Software $software, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SoftwareType::class, $software, [
            'submit_label' => '<i class="edit icon"></i>Mettre à jour',
            'submit_class' => 'ui labeled icon orange button'
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($software);
            $entityManager->flush();

            $this->traceur->trace("UPDATE", "Logiciels", $software->getId());

            $this->addFlash('success', 'Logiciel modifié avec succès');

            return $this->redirectToRoute('app_dashboard_software');
        }

        return $this->render('dashboard/software/edit_software.html.twig', [
            'form' => $form->createView(),
            'software' => $software,
        ]);
    }

    #[Route('/supprimer/{id}', name: 'app_dashboard_software_delete')]
    public function delete(int $id, Software $software, EntityManagerInterface $entityManager): Response
    {
        $temp_id = $software->getId();

        if ($software->getId() === $id) {
            $entityManager->remove($software);
            $entityManager->flush();

            $this->traceur->trace("DELETE", "Logiciels", $temp_id);

            $this->addFlash('success', 'Logiciel client supprimé avec succès');
        }

        return $this->redirectToRoute('app_dashboard_software');
    }
}
