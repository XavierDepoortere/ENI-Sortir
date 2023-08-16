<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantController extends AbstractController
{
    #[Route('/profil/{id}', name: 'app_profil')]
    public function profil(  Participant $participant, Request $request, EntityManagerInterface $entityManager): Response
    {
       $form = $this->createForm(ParticipantType::class, $participant);

       $form->handleRequest($request);

      $entityManager->persist($participant);

      $entityManager->flush();


        return $this->render('participant/profil.html.twig', [
            "participantForm"=> $form->createView(),

        ]);
    }
}

