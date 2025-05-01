<?php

namespace App\Controller;

use App\Entity\Database;
use App\Form\DatabaseType;
use App\Repository\DatabaseRepository;
use App\Service\TraceurService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/bases-de-donnees')]
class DatabaseController extends AbstractController
{
    private TraceurService $traceur;

    public function __construct(TraceurService $traceurService)
    {
        $this->traceur = $traceurService;
    }

    #[Route('/', name: 'app_dashboard_database')]
    public function index(DatabaseRepository $databaseRepository): Response
    {

        return $this->render('dashboard/database/databases.html.twig', [
            'databases' => $databaseRepository->findAllUser($this->getUser()),
        ]);
    }

    #[Route('/nouveau', name: 'app_dashboard_database_new')]
    public function new(Request $request, DatabaseRepository $databaseRepository, EntityManagerInterface $entityManager): Response
    {
        $database = new Database();
        $form = $this->createForm(DatabaseType::class, $database, [
            'database_label' => "<i class='plus icon'></i>Ajouter",
            'database_class' => "ui green icon labeled button"
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($database);
            $entityManager->flush();

            $this->traceur->trace("CREATE", "Base de donnée", $database->getId());

            $this->addFlash('success', 'Base de donnée ajoutée avec succès');

            return $this->redirectToRoute('app_dashboard_database');
        }

        return $this->render('dashboard/database/new_database.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/modifier/{id}', name: 'app_dashboard_database_edit')]
    public function edit(Request $request, DatabaseRepository $databaseRepository, EntityManagerInterface $entityManager, int $id): Response
    {
        $database = $databaseRepository->find($id);

        $form = $this->createForm(DatabaseType::class, $database, [
            'database_label' => "<i class='edit icon'></i>Mettre à jour",
            'database_class' => "ui orange icon labeled button"
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($database);
            $entityManager->flush();

            $this->traceur->trace("UPDATE", "Base de donnée", $database->getId());

            $this->addFlash('success', 'Base de donnée modifiée avec succès');

            return $this->redirectToRoute('app_dashboard_database');
        }

        return $this->render('dashboard/database/edit_database.html.twig', [
            'form' => $form->createView(),
            'database' => $database,
        ]);
    }

    #[Route('/supprimer/{id}', name: 'app_dashboard_database_delete')]
    public function delete(Request $request, DatabaseRepository $databaseRepository, EntityManagerInterface $entityManager, int $id): Response
    {
        $database = $databaseRepository->find($id);

        if($database) {
            $temp_id = $database->getId();
            $entityManager->remove($database);
            $entityManager->flush();

            $this->traceur->trace("DELETE", "Base de donnée", $temp_id);

            $this->addFlash('success', 'Base de donnée supprimée avec succès');
        }

        return $this->redirectToRoute('app_dashboard_database');
    }
}