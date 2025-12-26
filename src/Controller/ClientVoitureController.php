<?php
// src/Controller/ClientVoitureController.php
namespace App\Controller;

use App\Repository\VoitureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ClientVoitureController extends AbstractController
{
    #[Route('/voitures', name: 'client_voitures')]
    public function index(VoitureRepository $voitureRepository): Response
    {
        // Récupérer toutes les voitures disponibles
        $voitures = $voitureRepository->findBy(['statut' => 'Disponible']);

        return $this->render('client/voitures/index.html.twig', [
            'voitures' => $voitures,
        ]);
    }

    #[Route('/voitures/reserver/{id}', name: 'client_voiture_reserver')]
    public function reserver(int $id): Response
    {
        // Ici, tu peux rediriger vers un formulaire de réservation
        // Par exemple: /reservation/ajouter/{voitureId}

        $this->addFlash('success', 'Réservation de la voiture #'.$id.' réussie (à implémenter)');

        return $this->redirectToRoute('client_voitures');
    }
}
