<?php

namespace App\Controller;

use LogicException;
use App\Entity\Participant;
use App\Form\ParticipantType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ParticipantRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class ParticipantController extends AbstractController 
{
        #[Route('/profil', name: 'app_profil')]
public function edit(UserInterface $user, Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
{
    // Vérifier si l'utilisateur implémente PasswordAuthenticatedUserInterface
    if (!$user instanceof PasswordAuthenticatedUserInterface) {
        throw new \LogicException('User must implement PasswordAuthenticatedUserInterface');
    }

    $form = $this->createForm(ParticipantType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $motPasseField = $form->get('password');

        if ($motPasseField->isSubmitted() && !$motPasseField->isEmpty() && !$motPasseField->isDisabled()) {
            $plainPassword = $motPasseField->getData();
            $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
        } 

        $entityManager->flush();

        return $this->redirectToRoute('app_main');
    }

    return $this->render('participant/monProfil.html.twig', [
        'participantForm' => $form->createView(),
    ]);
}

#[Route('/profil/{id}', name: 'app_profil_view')]

    public function view(?Participant $participant): Response

    {  
        // Vérifier si le participant est null, c'est-à-dire que l'ID est invalide
        if (!$participant) {
            return $this->redirectToRoute('app_erreur');
        }

return $this->render('participant/profil.html.twig', [
    'participant' => $participant,
]);
}
}