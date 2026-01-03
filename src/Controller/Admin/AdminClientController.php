<?php

namespace App\Controller\Admin;

use App\Entity\Client;
use App\Form\ClientRegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/admin/clients')]
class AdminClientController extends AbstractController
{
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher)
    {
        $this->em = $em;
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/', name: 'admin_clients_index')]
    public function index(): Response
    {
        $clients = $this->em->getRepository(Client::class)->findAll();
        return $this->render('admin/clients/index.html.twig', [
            'clients' => $clients,
        ]);
    }

    #[Route('/new', name: 'admin_clients_new')]
    public function new(Request $request): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientRegistrationFormType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $client->getUser() ?? new \App\Entity\User();
            $user->setEmail($form->get('email')->getData());
            $user->setRoles(['ROLE_CLIENT']);
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, $form->get('password')->getData())
            );

            $client->setUser($user);

            $this->em->persist($user);
            $this->em->persist($client);
            $this->em->flush();

            $this->addFlash('success', 'Client créé avec succès.');
            return $this->redirectToRoute('admin_clients_index');
        }

        return $this->render('admin/clients/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_clients_edit')]
    public function edit(Client $client, Request $request): Response
    {
        $form = $this->createForm(ClientRegistrationFormType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $client->getUser();
            $user->setEmail($form->get('email')->getData());

            if ($form->get('password')->getData()) {
                $user->setPassword(
                    $this->passwordHasher->hashPassword($user, $form->get('password')->getData())
                );
            }

            $this->em->flush();
            $this->addFlash('success', 'Client mis à jour avec succès.');
            return $this->redirectToRoute('admin_clients_index');
        }

        return $this->render('admin/clients/edit.html.twig', [
            'form' => $form->createView(),
            'client' => $client,
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_clients_delete', methods: ['POST'])]
public function delete(Client $client, EntityManagerInterface $em, Request $request): Response
{
    if (count($client->getContrats()) > 0) {
        $this->addFlash('danger', 'Impossible de supprimer ce client : des contrats existent.');
        return $this->redirectToRoute('admin_clients_index');
    }

    $em->remove($client);
    $em->flush();

    $this->addFlash('success', 'Client supprimé avec succès.');
    return $this->redirectToRoute('admin_clients_index');
}


#[Route('/reservations', name: 'admin_clients_reservations')]
public function clientsAvecReservations(): Response
{
    // Récupérer les clients qui ont au moins un contrat
    $clients = $this->em->getRepository(Client::class)
        ->createQueryBuilder('c')
        ->innerJoin('c.contrats', 'co')  // inner join = au moins un contrat
        ->addSelect('co')
        ->getQuery()
        ->getResult();

    return $this->render('admin/clients/reservations.html.twig', [
        'clients' => $clients,
    ]);
}

}
