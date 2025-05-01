<?php

namespace App\Controller;

use App\Entity\Customers;
use App\Entity\User;
use App\Form\CreateCustomerType;
use App\Form\EditCustomerType;
use App\Repository\CustomersRepository;
use App\Service\TraceurService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/clients')]
class CustomersController extends AbstractController
{
    private TraceurService $traceur;

    public function __construct(TraceurService $traceurService)
    {
        $this->traceur = $traceurService;
    }

    #[Route('', name: 'app_dashboard_customers')]
    public function customers(Request $request, EntityManagerInterface $entityManager, CustomersRepository $customersRepository): Response
    {
        $customers = $customersRepository->findBy([], ['name' => 'ASC']);

        return $this->render('dashboard/customers/customers.html.twig', [
            'customers' => $customers
        ]);
    }

    #[Route('/nouveau', name: 'app_dashboard_new_customer')]
    public function newCustomer(Request $request, EntityManagerInterface $entityManager, CustomersRepository $customersRepository): Response {

        $customer = new Customers();

        $createCustomerForm = $this->createForm(CreateCustomerType::class, $customer);
        $createCustomerForm->handleRequest($request);

        if($createCustomerForm->isSubmitted() && $createCustomerForm->isValid()) {
            $customer = $createCustomerForm->getData();

            $customer->setName(strtoupper($customer->getName()));
            $customer->setCity(strtoupper($customer->getCity()));

            $entityManager->persist($customer);
            $entityManager->flush();

            $this->traceur->trace("CREATE", "Client", $customer->getId());

            return $this->redirectToRoute('app_dashboard_customers');
        }

        return $this->render('dashboard/customers/new_customer.html.twig', [
            'createCustomerForm' => $createCustomerForm->createView(),
        ]);
    }

    #[isGranted('ROLE_ADMIN')]
    #[Route('/supprimer/{id}', name: 'app_dashboard_delete_customer')]
    public function deleteCustomer(int $id, EntityManagerInterface $entityManager, CustomersRepository $customersRepository): Response
    {
        $customer = $customersRepository->find($id);

        if($customer) {
            $temp_id = $customer->getId();

            $entityManager->remove($customer);
            $entityManager->flush();

            $this->traceur->trace("DELETE", "Client", $temp_id);

            $this->addFlash('success', 'Le client a bien été supprimé');
        } else {
            return $this->redirectToRoute('app_dashboard_customers');
        }

        return $this->redirectToRoute('app_dashboard_customers');
    }

    #[Route('/modifier/{id}', name: 'app_dashboard_update_customer')]
    public function editCustomer(int $id, Request $request, EntityManagerInterface $entityManager, CustomersRepository $customersRepository): Response
    {
        $customer = $customersRepository->find($id);

        if($customer) {
            $updateCustomerForm = $this->createForm(EditCustomerType::class, $customer);
            $updateCustomerForm->handleRequest($request);

            if ($updateCustomerForm->isSubmitted() && $updateCustomerForm->isValid()) {

                $customer->setName(strtoupper($customer->getName()));
                $customer->setCity(strtoupper($customer->getCity()));

                $entityManager->persist($customer);
                $entityManager->flush();
                $this->traceur->trace("UPDATE", "Client", $customer->getId());

                $this->addFlash('success', 'Le client a bien été modifié');

                return $this->redirectToRoute('app_dashboard_customers');


            }

            return $this->render('dashboard/customers/update_customer.html.twig', [
                'customer' => $customer,
                'updateCustomerForm' => $updateCustomerForm->createView(),
            ]);
        } else {
            return $this->redirectToRoute('app_dashboard_customers');
        }

    }
}
