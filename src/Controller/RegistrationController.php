<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Client;
use App\Form\ClientRegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;



class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'client_register')]
    public function register(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $client = new Client();
        $form = $this->createForm(ClientRegistrationFormType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Création de l'utilisateur lié
            $user = new User();
            $user->setEmail($form->get('email')->getData()); // champ non mappé dans le formulaire
            $user->setRoles(['ROLE_CLIENT']); // rôle client
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData() // champ non mappé
                )
            );

            // Lien User ↔ Client
            $client->setUser($user);

            // Persistance en base
            $em->persist($user);
            $em->persist($client);
            $em->flush();

            // Message flash
            $this->addFlash('success', 'Inscription réussie. Vous pouvez maintenant vous connecter.');

            // Redirection vers la page login
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
