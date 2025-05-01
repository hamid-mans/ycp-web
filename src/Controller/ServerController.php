<?php

namespace App\Controller;

use App\Entity\Server;
use App\Entity\User;
use App\Form\CreateServerType;
use App\Form\EditServerType;
use App\Repository\ServerRepository;
use App\Service\TraceurService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/serveurs')]
class ServerController extends AbstractController
{
    private TraceurService $traceur;

    public function __construct(TraceurService $traceurService)
    {
        $this->traceur = $traceurService;
    }

    #[Route('/', name: 'app_dashboard_servers')]
    public function servers(Request $request, ServerRepository $serverRepository): Response
    {

        /* @var User $user */
        $user = $this->getUser();

        $servers = $serverRepository->findAllUser($this->getUser());


        return $this->render('dashboard/servers/servers.html.twig', [
            'servers' => $servers
        ]);
    }

    #[Route('/nouveau', name: 'app_dashboard_new_server')]
    public function newServer(Request $request, EntityManagerInterface $entityManager, ServerRepository $serverRepository): Response
    {
        $server = new Server();

        $createServerForm = $this->createForm(CreateServerType::class, $server);
        $createServerForm->handleRequest($request);

        if($createServerForm->isSubmitted() && $createServerForm->isValid()) {

            $this->addFlash('success', 'Le serveur a bien été ajouté');

            $entityManager->persist($server);
            $entityManager->flush();

            $this->traceur->trace("CREATE", "Serveurs", $server->getId());

            return $this->redirectToRoute('app_dashboard_servers');
        }

        return $this->render('dashboard/servers/new_server.html.twig', [
            'createServerForm' => $createServerForm->createView(),
        ]);
    }

    #[Route('/modifier/{id}', name: 'app_dashboard_update_server')]
    public function editServer(int $id, Request $request, EntityManagerInterface $entityManager, ServerRepository $serverRepository): Response
    {
        $server = $serverRepository->find($id);
        $editServerForm = $this->createForm(EditServerType::class, $server);
        $editServerForm->handleRequest($request);

        if($server) {

            if($editServerForm->isSubmitted() && $editServerForm->isValid())
            {
                $entityManager->persist($server);
                $entityManager->flush();

                $this->traceur->trace("UPDATE", "Serveurs", $server->getId());

                $this->addFlash('success', 'Le serveur a bien été modifié');

                return $this->redirectToRoute('app_dashboard_servers');
            }

            return $this->render('dashboard/servers/update_server.html.twig', [
                'server' => $server,
                'editServerForm' => $editServerForm->createView()
            ]);
        } else {
            return $this->redirectToRoute('app_dashboard_servers');
        }

    }

    #[Route('/supprimer/{id}', name: 'app_dashboard_delete_server')]
    public function deleteServer(int $id, EntityManagerInterface $entityManager, ServerRepository $serverRepository): Response
    {
        $server = $serverRepository->find($id);

        if($server) {
            $temp_id = $server->getId();
            $entityManager->remove($server);
            $entityManager->flush();
            $entityManager->clear();

            $this->traceur->trace("DELETE", "Serveurs", $temp_id);

            $this->addFlash('success', 'Le serveur a bien été supprimé');

            return $this->redirectToRoute('app_dashboard_servers');
        } else {
            return $this->redirectToRoute('app_dashboard_servers');
        }
    }
}
