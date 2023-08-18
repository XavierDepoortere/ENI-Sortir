<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\LieuType;
use App\Form\SortieType;
use App\Repository\SortieRepository;
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

        $sortieForm = $this->createForm(SortieType::class, $sortie);

        $sortieForm->handleRequest($request);
        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {
            $entityManager->persist($sortie);

        $entityManager->flush();
        }


        
        



        return $this->render('sortie/create.html.twig', [
            'sortieForm' => $sortieForm->createView(),
        ]);
    }

    #[Route('/lieu/create', name: 'app_lieu_create')]
    public function createlieu(EntityManagerInterface $entityManager, Request $request): Response
    {
        $lieu = new Lieu();

        $lieuForm = $this->createForm(LieuType::class, $lieu);
        $lieuForm->handleRequest($request);

        if ($lieuForm->isSubmitted() &&$lieuForm->isValid())
        {
            $entityManager->persist($lieu);
            $entityManager->flush();
        }
        return $this->render('lieu/create.html.twig', [
            'lieuForm' => $lieuForm->createView(),
        ]);
    }

    #[Route('/lieu/create', name: 'app_ville_create')]
    public function createville(EntityManagerInterface $entityManager, Request $request): Response
    {
        $ville = new Ville();

        $villeForm = $this->createForm(Ville::class, $ville);

        $villeForm->handleRequest($request);

        if ($villeForm->isSubmitted() && $villeForm->isValid())
        {
            $entityManager->persist($ville);
            $entityManager->flush();
        }



        return $this->render('ville/create.html.twig', [
            'villeForm' => 'VilleController',
        ]);
    }



}

