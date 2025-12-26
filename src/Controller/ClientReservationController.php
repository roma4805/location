<?php
// src/Controller/ClientReservationController.php
namespace App\Controller;

use App\Entity\Contrat;
use App\Entity\Voiture;
use App\Form\ContratType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ClientReservationController extends AbstractController
{
   
    #[Route('/reservation/{id}', name:'client_voiture_reservation')]
public function reserver(
    Voiture $voiture,
    Request $request,
    EntityManagerInterface $em
): Response {
    $contrat = new Contrat();
    $contrat->setVoiture($voiture);

    /** @var \App\Entity\User $user */
    $user = $this->getUser();
    if (!$user instanceof \App\Entity\User) {
        throw $this->createAccessDeniedException('Vous devez être connecté.');
    }

    $client = $user->getClient();
    if (!$client) {
        throw $this->createAccessDeniedException('Profil client requis.');
    }

    $contrat->setClient($client);

    $form = $this->createForm(ContratType::class, $contrat);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

        $prixTotal = $contrat->calculerPrixTotal($voiture->getPrixJournalier());
        $contrat->setPrixTotal($prixTotal);

        $voiture->setStatut('Louée');

        $em->persist($contrat);
        $em->flush();

        // 🔥 REDIRECTION vers page succès
        return $this->redirectToRoute('reservation_succes', [
            'id' => $contrat->getId()
        ]);
    }

    return $this->render('client/reservation/form.html.twig', [
        'form' => $form->createView(),
        'voiture' => $voiture
    ]);
}
#[Route('/reservation/succes/{id}', name: 'reservation_succes')]
public function succes(Contrat $contrat): Response
{
    return $this->render('client/reservation/succes.html.twig', [
        'contrat' => $contrat,
        'prixTotal' => $contrat->getPrixTotal(),
        'messageReduction' => $contrat->getReductionMessage(),
    ]);
}

}
