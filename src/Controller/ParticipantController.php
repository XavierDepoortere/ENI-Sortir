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