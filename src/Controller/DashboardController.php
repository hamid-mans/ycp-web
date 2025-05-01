<?php

namespace App\Controller;

use App\Entity\Customers;
use App\Entity\RemoteControlTool;
use App\Entity\Server;
use App\Entity\TypeDatabase;
use App\Entity\TypeServer;
use App\Entity\TypeSoftware;
use App\Entity\User;
use App\Form\CreateCustomerType;
use App\Form\CreateServerType;
use App\Form\EditCustomerType;
use App\Form\EditServerType;
use App\Form\NotesType;
use App\Form\RegistrationFormType;
use App\Form\RemoteControlToolType;
use App\Form\TypeDatabaseType;
use App\Form\TypeServerType;
use App\Form\TypeSoftwareType;
use App\Repository\ConfigRepository;
use App\Repository\CustomersRepository;
use App\Repository\DatabaseRepository;
use App\Repository\RemoteControlToolRepository;
use App\Repository\ServerRepository;
use App\Repository\SoftwareRepository;
use App\Repository\TypeDatabaseRepository;
use App\Repository\TypeServerRepository;
use App\Repository\TypeSoftwareRepository;
use App\Repository\UserRepository;
use App\Service\QuoteService;
use App\Service\TraceurService;
use App\Service\TranslationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/')]
class DashboardController extends AbstractController
{
    private TraceurService $traceur;

    private QuoteService $quoteService;
    private TranslationService $translationService;

    public function __construct(QuoteService $quoteService, TranslationService $translationService, TraceurService $traceurService)
    {
        $this->quoteService = $quoteService;
        $this->translationService = $translationService;
        $this->traceur = $traceurService;
    }

    #[Route('/panneau', name: 'app_dashboard_home')]
    public function index(SoftwareRepository $softwareRepository, EntityManagerInterface $entityManager, Request $request, ConfigRepository $configRepository, CustomersRepository $customersRepository, ServerRepository $serverRepository, DatabaseRepository $databaseRepository): Response
    {
        $notes = $configRepository->find(1);
        $form = $this->createForm(NotesType::class, $notes);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($notes);
            $entityManager->flush();

            $this->traceur->trace("UPDATE", "Notes", 0);

            $this->addFlash('success', 'Notes modifiées avec succès');

            return $this->redirectToRoute('app_dashboard_home');
        }

        $quote = $this->quoteService->getQuote();

        return $this->render('dashboard/dashboard.html.twig', [
            'form' => $form->createView(),
            'customersCount' => $customersRepository->count(),
            'serversCount' => $serverRepository->count(),
            'databasesCount' => $databaseRepository->count(),
            'softwaresCount' => $softwareRepository->count(),
            'quote' => [$this->translationService->translateToFrench($quote["quote"]), $quote["author"]]
        ]);
    }

    // TODO : PARAMETRES

    #[isGranted('ROLE_ADMIN')]
    #[Route('/parametres', name: 'app_dashboard_settings')]
    public function settings(TypeSoftwareRepository $typeSoftwareRepository, UserRepository $userRepository,TypeDatabaseRepository $typeDatabaseRepository, TypeServerRepository $typeServerRepository, RemoteControlToolRepository $controlToolRepository): Response
    {
        return $this->render('dashboard/settings/settings.html.twig', [
            'users' => $userRepository->findAll(),
            'typesServeurs' => $typeServerRepository->findBy([], ['name' => 'ASC']),
            'remoteControlTool' => $controlToolRepository->findBy([], ['name' => 'ASC']),
            'typesDatabase' => $typeDatabaseRepository->findBy([], ['name' => 'ASC']),
            'typesSoftware' => $typeSoftwareRepository->findBy([], ['name' => 'ASC'])
        ]);
    }

    // TODO : UTILISATEURS

    #[isGranted('ROLE_ADMIN')]
    #[Route('/parametres/utilisateurs/nouveau', name: 'app_dashboard_settings_new_user')]
    public function newUser(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $error = '';

        $form = $this->createForm(RegistrationFormType::class, $user, [
            'submit_label' => '<i class="plus icon"></i>Créer un utilisateur',
            'submit_class' => 'ui icon labeled green button'
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('password')->getData();
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $userExist = $userRepository->findOneBy(['email' => $user->getEmail()]);

            if ($userExist) $error = "Cette adresse email est déjà utilisée.";
            if (strlen($plainPassword) < 6) $error = "Veuillez saisir un mot de passe de plus de 6 caractères.";
            if (strlen($plainPassword) < 1) $error = "Veuillez saisir un mot de passe.";

            if (!empty($error)) {
                $this->addFlash('error', $error);

                return $this->redirectToRoute('app_dashboard_settings_new_user');
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $this->traceur->trace("CREATE", "Utilisateur", $user->getId());

            return $this->redirectToRoute('app_dashboard_settings');
        }


        return $this->render('dashboard/settings/new_user.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[isGranted('ROLE_ADMIN')]
    #[Route('/parametres/utilisateurs/supprimer/{id}', name: 'app_dashboard_settings_delete_user')]
    public function deleteUser(int $id, Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->find($id);

        if ($user) {
            $temp_id = $user->getId();
            $entityManager->remove($user);
            $entityManager->flush();

            $this->traceur->trace("DELETE", "Utilisateur", $temp_id);
        } else {
            $this->addFlash('error', "Cet utilisateur n'existe pas.");

            return $this->redirectToRoute('app_dashboard_settings');
        }

        return $this->redirectToRoute('app_dashboard_settings');
    }

    #[isGranted('ROLE_ADMIN')]
    #[Route('/parametres/utilisateurs/modifier/{id}', name: 'app_dashboard_settings_edit_user')]
    public function editUser(int $id, Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->find($id);

        if ($user) {
            $form = $this->createForm(RegistrationFormType::class, $user, [
                'submit_label' => '<i class="edit icon"></i>Mettre à jour',
                'submit_class' => 'ui icon labeled orange button'
            ]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($user);
                $entityManager->flush();

                $this->traceur->trace("UPDATE", "Utilisateur", $user->getId());

                $this->addFlash('success', 'L\'utilisateur a bien été modifié');
                return $this->redirectToRoute('app_dashboard_settings');
            }
        }

        return $this->render('dashboard/settings/edit_user.html.twig', [
            'user' => $user,
            'registrationForm' => $form->createView(),
        ]);
    }


    // TODO : TYPES SERVEURS

    #[isGranted('ROLE_ADMIN')]
    #[Route('/parametres/types-serveur/modifier/{id}', name: 'app_dashboard_settings_edit_type_server')]
    public function editTypeServer(int $id, Request $request, TypeServerRepository $typeServerRepository, EntityManagerInterface $entityManager): Response
    {
        $typeServer = $typeServerRepository->find($id);

        if ($typeServer) {
            $form = $this->createForm(TypeServerType::class, $typeServer, [
                'submit_label' => '<i class="plus icon"></i>Mettre à jour',
                'submit_class' => 'ui icon labeled orange button'
            ]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($typeServer);
                $entityManager->flush();

                $this->traceur->trace("UPDATE", "Type Serveur", $typeServer->getId());

                return $this->redirectToRoute('app_dashboard_settings');
            }
        }

        return $this->render('dashboard/settings/edit_type_server.html.twig', [
            'type' => $typeServer,
            'form' => $form->createView()
        ]);
    }

    #[isGranted('ROLE_ADMIN')]
    #[Route('/parametres/types-serveur/nouveau', name: 'app_dashboard_settings_add_type_server')]
    public function addTypeServer(Request $request, EntityManagerInterface $entityManager): Response
    {
        $type = new TypeServer();

        $form = $this->createForm(TypeServerType::class, $type, [
            'submit_label' => '<i class="plus icon"></i>Ajouter',
            'submit_class' => 'ui icon labeled green button'
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($type);
            $entityManager->flush();

            $this->traceur->trace("CREATE", "Type Serveur", $type->getId());

            return $this->redirectToRoute('app_dashboard_settings');
        }

        return $this->render('dashboard/settings/add_type_server.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[isGranted('ROLE_ADMIN')]
    #[Route('/parametres/types-serveur/supprimer/{id}', name: 'app_dashboard_settings_delete_type_server')]
    public function deleteTypeServer(int $id, Request $request, TypeServerRepository $typeServerRepository, EntityManagerInterface $entityManager): Response
    {
        $typeServer = $typeServerRepository->find($id);

        if ($typeServer) {
            $temp_id = $typeServer->getId();
            $entityManager->remove($typeServer);
            $entityManager->flush();

            $this->traceur->trace("DELETE", "Type Serveur", $temp_id);

            return $this->redirectToRoute('app_dashboard_settings');
        }

        return $this->redirectToRoute('app_dashboard_settings');
    }

    // TODO : Outils de prise en main

    #[isGranted('ROLE_ADMIN')]
    #[Route('/parametres/outil-pdm/nouveau', name: 'app_dashboard_settings_add_remote_tool')]
    public function newRemoteTool(Request $request, EntityManagerInterface $entityManager): Response
    {
        $remoteControlTool = new RemoteControlTool();

        $form = $this->createForm(RemoteControlToolType::class, $remoteControlTool, [
            'submit_label' => '<i class="plus icon"></i>Ajouter',
            'submit_class' => 'ui icon labeled green button'
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($remoteControlTool);
            $entityManager->flush();

            $this->traceur->trace("CREATE", "Outil prise en main", $remoteControlTool->getId());

            $this->addFlash('success', 'Outil de prise en main ajouté avec success');

            return $this->redirectToRoute('app_dashboard_settings');
        }

        return $this->render('dashboard/settings/add_remote_tool.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[isGranted('ROLE_ADMIN')]
    #[Route('/parametres/outil-pdm/supprimer/{id}', name: 'app_dashboard_settings_delete_remote_tool')]
    public function removeRemoteControlTool(int $id, Request $request, RemoteControlToolRepository $remoteControlToolRepository, EntityManagerInterface $entityManager): Response
    {
        $remoteControlTool = $remoteControlToolRepository->find($id);

        if ($remoteControlTool) {
            $temp_id = $remoteControlTool->getId();

            $entityManager->remove($remoteControlTool);
            $entityManager->flush();

            $this->traceur->trace("DELETE", "Outil prise en main", $temp_id);

            $this->addFlash('success', 'Outil de prise en main supprimé avec succès');

            return $this->redirectToRoute('app_dashboard_settings');
        }

        return $this->redirectToRoute('app_dashboard_settings');
    }

    #[isGranted('ROLE_ADMIN')]
    #[Route('/parametres/outil-pdm/modifier/{id}', name: 'app_dashboard_settings_edit_remote_tool')]
    public function editRemoteControlTool(int $id, Request $request, RemoteControlToolRepository $remoteControlToolRepository, EntityManagerInterface $entityManager): Response
    {
        $remoteControlTool = $remoteControlToolRepository->find($id);

        if ($remoteControlTool) {
            $form = $this->createForm(RemoteControlToolType::class, $remoteControlTool, [
                'submit_label' => '<i class="plus icon"></i>Mettre à jour',
                'submit_class' => 'ui icon labeled orange button'
            ]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($remoteControlTool);
                $entityManager->flush();

                $this->traceur->trace("UPDATE", "Outil prise en main", $remoteControlTool->getId());

                $this->addFlash('success', 'Outil de prise en main modifié avec succès');

                return $this->redirectToRoute('app_dashboard_settings');
            }
        }

        return $this->render('dashboard/settings/edit_remote_tool.html.twig', [
            'form' => $form->createView(),
            'tool' => $remoteControlTool
        ]);
    }

    // TODO : Types de bases de données

    #[isGranted('ROLE_ADMIN')]
    #[Route('/parametres/types-bdd/nouveau', name: 'app_dashboard_settings_add_type_database')]
    public function newTypeDatabase(Request $request, EntityManagerInterface $entityManager): Response
    {
        $typeDatabase = new TypeDatabase();

        $form = $this->createForm(TypeDatabaseType::class, $typeDatabase, [
            'submit_label' => '<i class="plus icon"></i>Ajouter',
            'submit_class' => 'ui icon labeled green button'
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($typeDatabase);
            $entityManager->flush();

            $this->traceur->trace("CREATE", "Type base de données", $typeDatabase->getId());

            $this->addFlash('success', 'Type de base de donnée ajouté avec succès');

            return $this->redirectToRoute('app_dashboard_settings');
        }

        return $this->render('dashboard/settings/add_type_database.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[isGranted('ROLE_ADMIN')]
    #[Route('/parametres/types-bdd/supprimer/{id}', name: 'app_dashboard_settings_remove_type_database')]
    public function deleteTypeDatabase(int $id, Request $request, TypeDatabaseRepository $typeDatabaseRepository, EntityManagerInterface $entityManager): Response
    {
        $typeDatabase = $typeDatabaseRepository->find($id);

        if ($typeDatabase) {
            $temp_id = $typeDatabase->getId();
            $entityManager->remove($typeDatabase);
            $entityManager->flush();

            $this->traceur->trace("DELETE", "Type base de données", $temp_id);

            $this->addFlash('success', 'Type de base de données supprimé avec succès');
        }

        return $this->redirectToRoute('app_dashboard_settings');
    }

    #[isGranted('ROLE_ADMIN')]
    #[Route('/parametres/types-bdd/modifier/{id}', name: 'app_dashboard_settings_edit_type_database')]
    public function editTypeDatabase(int $id, Request $request, TypeDatabaseRepository $typeDatabaseRepository, EntityManagerInterface $entityManager): Response
    {
        $typeDatabase = $typeDatabaseRepository->find($id);

        if ($typeDatabase) {
            $form = $this->createForm(TypeDatabaseType::class, $typeDatabase, [
                'submit_label' => '<i class="plus icon"></i>Mettre à jour',
                'submit_class' => 'ui icon labeled orange button'
            ]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($typeDatabase);
                $entityManager->flush();

                $this->traceur->trace("UPDATE", "Type de base de données", $typeDatabase->getId());

                $this->addFlash('success', 'Type de base de données modifié avec succès');

                return $this->redirectToRoute('app_dashboard_settings');
            }

            return $this->render('dashboard/settings/edit_type_database.html.twig', [
                'database' => $typeDatabase,
                'form' => $form->createView()
            ]);
        }

        return $this->redirectToRoute('app_dashboard_settings');
    }


    // TODO : Types de logiciels (noms)

    #[isGranted('ROLE_ADMIN')]
    #[Route('/parametres/types-logiciel/nouveau', name: 'app_dashboard_settings_add_type_software')]
    public function newTypeLogiciel(Request $request, EntityManagerInterface $entityManager): Response
    {
        $typeSoftware = new TypeSoftware();
        $form = $this->createForm(TypeSoftwareType::class, $typeSoftware, [
            'submit_label' => '<i class="plus icon"></i>Ajouter',
            'submit_class' => 'ui icon labeled green button'
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($typeSoftware);
            $entityManager->flush();

            $this->traceur->trace("CREATE", "Type de logiciel", $typeSoftware->getId());

            $this->addFlash('success', 'Nouveau logiciel créé avec sucucès');

            return $this->redirectToRoute('app_dashboard_settings');
        }

        return $this->render('dashboard/settings/add_type_software.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[isGranted('ROLE_ADMIN')]
    #[Route('/parametres/types-logiciel/modifier/{id}', name: 'app_dashboard_settings_edit_type_software')]
    public function editTypeSoftware(int $id, Request $request, TypeSoftwareRepository $typeSoftwareRepository, EntityManagerInterface $entityManager): Response
    {
        $typeSoftware = $typeSoftwareRepository->find($id);

        if ($typeSoftware) {
            $form = $this->createForm(TypeSoftwareType::class, $typeSoftware, [
                'submit_label' => '<i class="edit icon"></i>Mettre à jour',
                'submit_class' => 'ui icon labeled orange button'
            ]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($typeSoftware);
                $entityManager->flush();

                $this->traceur->trace("UPDATE", "Type de logiciel", $typeSoftware->getId());

                $this->addFlash('success', 'Nom du logiciel modifié avec succès');

                return $this->redirectToRoute('app_dashboard_settings');
            }
        }

        return $this->render('dashboard/settings/edit_type_software.html.twig', [
            'software' => $typeSoftware,
            'form' => $form->createView()
        ]);
    }


    #[isGranted('ROLE_ADMIN')]
    #[Route('/parametres/types-logiciel/supprimer/{id}', name: 'app_dashboard_settings_delete_type_software')]
    public function deleteTypeSoftware(int $id, TypeSoftwareRepository $typeSoftwareRepository, EntityManagerInterface $entityManager): Response
    {
        $typeSoftware = $typeSoftwareRepository->find($id);

        if ($typeSoftware) {
            $temp_id = $typeSoftware->getId();
            $entityManager->remove($typeSoftware);
            $entityManager->flush();

            $this->traceur->trace("DELETE", "Type de logiciel", $temp_id);

            $this->addFlash('success', 'Type de logiciel supprimé avec succès');
        }

        return $this->redirectToRoute('app_dashboard_settings');
    }
}
