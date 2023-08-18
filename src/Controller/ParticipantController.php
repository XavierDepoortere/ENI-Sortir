<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantType;
use Doctrine\ORM\EntityManagerInterface;
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
    #[Route('/profil/{id}', name: 'app_profil')]
    public function edit(?Participant $participant, Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {   //TODO TEST SI PARTICIPANT = 0 redirect to login  + add flash message ( ce participant  )
        //TODO CODE POUR FAIRE EN SORT QUE SI ON INDIQUE UN ID INCONNU DANS LA BDD ON ARRIVE SUR UNE 404
        //TODO TEST POUR NUMERIQUE OBLIGATOIRE POUR L'ID DU PARTICIPANT
        //TODO faire un deuxieme template pour affichage d'un et ou de monprofil
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
public function view(int $id,ParticipantRepository $participantRepository): Response
{   
    // Récupérer le participant depuis la base de données
    $participant = $participantRepository->find($id);
    
    // Vérifier si l'ID n'est pas numérique ou si le participant n'existe pas
    if (!is_numeric($id) || !$participant) {
        throw new NotFoundHttpException('Page not found');
    }
        
    
    return $this->render('participant/profil.html.twig', [
         'participant' => $participant,
    ]);
}
}
=======
    {   

        // Vérifier si le participant est null, c'est-à-dire que l'ID est invalide
        // if (!$participant) {
        //     return $this->redirectToRoute('app_erreur');
        // }
        
        $form = $this->createForm(ParticipantType::class, $participant);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer le champ de mot de passe depuis le formulaire
            $motPasseField = $form->get('motPasse');
            // Vérifier si le champ a été modifié et n'est pas vide
            if ($motPasseField->isSubmitted() && !$motPasseField->isEmpty() && !$motPasseField->isDisabled()) {
                $plainPassword = $motPasseField->getData();
                $hashedPassword = $passwordHasher->hashPassword($participant, $plainPassword);
                $participant->setPassword($hashedPassword);
            } 
            // Enregistrer les modifications
            $entityManager->flush();
            return $this->redirectToRoute('app_main');
        }
        return $this->render('participant/profil.html.twig', [
            'participantForm' => $form->createView(),'participant' => $participant,
        ]);
    
    }

}   
>>>>>>> aaf294da1a89e57920483c8fe8750ff15dc2b59e
