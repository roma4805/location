<?php
// src/Controller/Admin/VoitureController.php
namespace App\Controller\Admin;

use App\Entity\Voiture;
use App\Form\VoitureType;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;


#[Route('/admin/voitures')]
class VoitureController extends AbstractController
{
    #[Route('/', name: 'admin_voitures')]
    public function index(VoitureRepository $repo): Response
    {
        $voitures = $repo->findAll();
        return $this->render('admin/voiture/index.html.twig', compact('voitures'));
    }

    #[Route('/ajouter', name: 'admin_voiture_ajouter')]
    #[Route('/ajouter', name: 'admin_voiture_ajouter')]
public function ajouter(
    Request $request,
    EntityManagerInterface $em,
    SluggerInterface $slugger
): Response {
    $voiture = new Voiture();
    $form = $this->createForm(VoitureType::class, $voiture);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

        /** @var UploadedFile $imageFile */
        $imageFile = $form->get('image')->getData();

        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

            $imageFile->move(
                $this->getParameter('images_directory'),
                $newFilename
            );

            $voiture->setImage($newFilename);
        }

        $em->persist($voiture);
        $em->flush();

        $this->addFlash('success', 'Voiture ajoutée avec succès !');
        return $this->redirectToRoute('admin_voitures');
    }

    return $this->render('admin/voiture/form.html.twig', [
        'form' => $form->createView(),
        'voiture' => $voiture
    ]);
}


    #[Route('/modifier/{id}', name: 'admin_voiture_modifier')]
    #[Route('/modifier/{id}', name: 'admin_voiture_modifier')]
public function modifier(Voiture $voiture, Request $request, EntityManagerInterface $em): Response
{
    $form = $this->createForm(VoitureType::class, $voiture);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Gestion du fichier image
        $imageFile = $form->get('image')->getData();
        if ($imageFile) {
            // Générer un nom unique
            $newFilename = uniqid() . '.' . $imageFile->guessExtension();
            // Déplacer le fichier dans public/uploads
            $imageFile->move(
                $this->getParameter('images_directory'),
                $newFilename
            );
            // Mettre à jour la propriété image
            $voiture->setImage($newFilename);
        }

        $em->flush();
        $this->addFlash('success', 'Voiture modifiée avec succès !');
        return $this->redirectToRoute('admin_voitures');
    }

    return $this->render('admin/voiture/form.html.twig', [
        'form' => $form->createView(),
        'voiture' => $voiture // nécessaire pour afficher l'image existante
    ]);
}


    #[Route('/supprimer/{id}', name: 'admin_voiture_supprimer')]
    public function supprimer(Voiture $voiture, EntityManagerInterface $em): Response
    {
        $em->remove($voiture);
        $em->flush();
        $this->addFlash('success', 'Voiture supprimée !');
        return $this->redirectToRoute('admin_voitures');
    }

    #[Route('/details/{id}', name: 'admin_voiture_details')]
    public function details(Voiture $voiture): Response
    {
        return $this->render('admin/voiture/details.html.twig', compact('voiture'));
    }
}
