<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ParticipantRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ParticipantController extends AbstractController
{
    #[Route('/profil/{id}', name: 'app_profil')]
    public function edit(Participant $participant, Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        //TODO CODE POUR FAIRE EN SORT QUE SI ON INDIQUE UN ID INCONNU DANS LA BDD ON ARRIVE SUR UNE 404
        $form = $this->createForm(ParticipantType::class, $participant);
        $originalPassword = $participant->getPassword();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer le champ de mot de passe depuis le formulaire
            $motPasseField = $form->get('motPasse');
            // Vérifier si le champ a été modifié et n'est pas vide
            if ($motPasseField->isSubmitted() && !$motPasseField->isEmpty() && !$motPasseField->isDisabled()) {
                $plainPassword = $motPasseField->getData();
                $hashedPassword = $passwordHasher->hashPassword($participant, $plainPassword);
                $participant->setPassword($hashedPassword);
            } else {
                // Si le champ n'a pas été modifié, restaurer le mot de passe original
                $participant->setPassword($originalPassword);
            }
            // Enregistrer les modifications
            $entityManager->persist($participant);
            $entityManager->flush();
            return $this->redirectToRoute('app_main');
        }
        return $this->render('participant/profil.html.twig', [
            'participantForm' => $form->createView(),'participant' => $participant,
        ]);
    }
}