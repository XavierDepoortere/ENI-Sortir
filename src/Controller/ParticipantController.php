<?php

namespace App\Controller;

use LogicException;
use App\Entity\Participant;
use App\Form\ParticipantType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class ParticipantController extends AbstractController
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher=$passwordHasher;
    }

        #[Route('/profil', name: 'app_profil')]

    public function edit(Request $request, EntityManagerInterface $entityManagerInterface) : Response

    {
        $participant = $this->getUser(); // Récupère l'utilisateur actuellement connecté

        $form = $this->createForm(ParticipantType::class, $participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                
            $participant = $form->getData();

            // Si le mot de passe est modifié
            if ($form->get('motPasse')->getData()) {
                $participant->setMotPasse($this->passwordHasher->hashPassword(
                    $participant,
                    $form->get('motPasse')->getData()
                ));
            }
            $entityManagerInterface->persist($participant);
            $entityManagerInterface->flush();  
            
        return $this->redirectToRoute('app_main');
        }else{
            $entityManagerInterface->refresh($participant);
        }
    return $this->render('participant/monProfil.html.twig', [
        'participantForm' => $form->createView(),
        'participant'=>$participant,
    ]);
}

#[Route('/profil/{id}', name: 'app_profil_view')]

    public function view(?Participant $participant): Response

    {  
        if (!$participant) {
            throw new NotFoundHttpException('Ressource non trouvée');
        }
return $this->render('participant/profil.html.twig', [
    'participant' => $participant,
]);
}
}

// public function edit(ParticipantRepository $user, Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
// {
    
    // // Vérifier si l'utilisateur implémente PasswordAuthenticatedUserInterface
    // if (!$user instanceof PasswordAuthenticatedUserInterface) {
    //     throw new \LogicException('User must implement PasswordAuthenticatedUserInterface');
    // }

    // $form = $this->createForm(ParticipantType::class, $user);
    // $form->handleRequest($request);

    // if ($form->isSubmitted() && $form->isValid()) {
    //     $motPasseField = $form->get('password');

    //     if ($motPasseField->isSubmitted() && !$motPasseField->isEmpty() && !$motPasseField->isDisabled()) {
    //         $plainPassword = $motPasseField->getData();
    //         $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
    //         $user->setMotPasse($hashedPassword);
    //     } 
