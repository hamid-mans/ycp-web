<?php

namespace App\Controller;

use App\Form\TraceurSearchType;
use App\Repository\TraceurRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TraceurController extends AbstractController
{
    #[isGranted('ROLE_ADMIN')]
    #[Route('/traceur', name: 'app_dashboard_traceur')]
    public function index(PaginatorInterface $paginator, Request $request, TraceurRepository $traceurRepository): Response
    {
        $traceur = $traceurRepository->createQueryBuilder('q')->orderBy('q.datetime', 'DESC');

        $form = $this->createForm(TraceurSearchType::class, null, [
            'method' => 'GET',
            'submit_label' => '<i class="search icon"></i>Rechercher',
            'submit_class' => 'ui icon labeled olive button'
        ]);
        $form->handleRequest($request);

        $pagination = $paginator->paginate(
            $traceur,
            $request->query->getInt('page', 1),
            3
        );

        if($form->isSubmitted() && $form->isValid()) {
            $traceur = $traceurRepository->findByFilters($form->getData());

            $pagination = $paginator->paginate(
                $traceur,
                $request->query->getInt('page', 1),
                3
            );
        }

        return $this->render('dashboard/traceur/traceur.html.twig', [
            'traceur' => $traceur,
            'form' => $form->createView(),
            'pagination' => $pagination,
        ]);
    }
}
