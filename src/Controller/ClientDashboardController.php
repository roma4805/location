<?php

namespace App\Controller;

use App\Repository\ContratRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ClientDashboardController extends AbstractController
{
    #[Route('/client/dashboard', name: 'client_dashboard')]
    public function index(ContratRepository $contratRepository): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if (!$user || !$user->getClient()) {
            throw $this->createAccessDeniedException('Profil client requis.');
        }

        $client = $user->getClient();

        // Récupérer toutes les réservations du client
        $reservations = $contratRepository->findBy(
            ['client' => $client],
            ['dateDebut' => 'DESC'] // trier par date décroissante
        );

        return $this->render('client/dashboard.html.twig', [
            'client' => $client,
            'reservations' => $reservations,
        ]);
    }
}
