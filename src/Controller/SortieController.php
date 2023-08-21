<?php

namespace App\Controller;


use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\SortieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    #[Route('/sortie/create', name: 'app_sortie_create')]
    public function create(EntityManagerInterface $entityManager, Request $request)
    {

        $sortie = new Sortie();

        $form = $this->createForm(SortieType::class, $sortie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($sortie);

            $entityManager->flush();


            $this->addFlash('success', 'Sortie ajoutée ! bien joué!!');

            return $this->redirectToRoute('app_main');


        }

        return $this->render('sortie/createSortie.html.twig', [
            'form' => $form->createView(),

        ]);
    }
}