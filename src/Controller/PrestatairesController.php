<?php

namespace App\Controller;

use App\Entity\Prestataires;
use App\Form\PrestatairesType;
use App\Repository\PrestatairesRepository;
use App\Service\TraceurService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/prestataires')]
class PrestatairesController extends AbstractController
{
    private TraceurService $traceur;

    public function __construct(TraceurService $traceurService)
    {
        $this->traceur = $traceurService;
    }

    #[Route('/', name: 'app_dashboard_prestataires')]
    public function index(PrestatairesRepository $prestatairesRepository): Response
    {
        $prestataires = $prestatairesRepository->findAll();

        return $this->render('dashboard/prestataires/prestataires.html.twig', [
            'prestataires' => $prestataires
        ]);
    }

    #[Route('/nouveau', name: 'app_dashboard_prestataire_new')]
    public function new(Request $request, PrestatairesRepository $prestatairesRepository, EntityManagerInterface $entityManager): Response
    {
        $prestataire = new Prestataires();
        $form = $this->createForm(PrestatairesType::class, $prestataire, [
            'submit_label' => "<i class='plus icon'></i>Créer un préstataire",
            'submit_class' => "ui green icon labeled button"
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($prestataire);
            $entityManager->flush();

            $this->traceur->trace("CREATE", "Prestataires", $prestataire->getId());

            return $this->redirectToRoute('app_dashboard_prestataires');
        }

        return $this->render('dashboard/prestataires/new_prestataire.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/modifier/{id}', name: 'app_dashboard_update_prestataire')]
    public function update(int $id, Request $request, PrestatairesRepository $prestatairesRepository, EntityManagerInterface $entityManager): Response
    {
        $prestataire = $prestatairesRepository->find($id);

        $form = $this->createForm(PrestatairesType::class, $prestataire, [
            'submit_label' => "<i class='edit icon'></i>Mettre à jour",
            'submit_class' => "ui orange icon labeled button"
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($prestataire);
            $entityManager->flush();

            $this->traceur->trace("UPDATE", "Prestataires", $prestataire->getId());

            return $this->redirectToRoute('app_dashboard_prestataires');
        }

        return $this->render('dashboard/prestataires/update_prestataire.html.twig', [
            'form' => $form->createView(),
            'prestataire' => $prestataire
        ]);
    }

    #[Route('/supprimer/{id}', name: 'app_dashboard_delete_prestataire')]
    public function delete(int $id, PrestatairesRepository $prestatairesRepository, EntityManagerInterface $entityManager): Response
    {
        $prestataire = $prestatairesRepository->find($id);

        if ($prestataire) {
            $temp_id = $prestataire->getId();
            $entityManager->remove($prestataire);
            $entityManager->flush();

            $this->traceur->trace("DELETE", "Prestataires", $temp_id);

            return $this->redirectToRoute('app_dashboard_prestataires');
        } else {
            return $this->redirectToRoute('app_dashboard_prestataires');
        }
    }

}
