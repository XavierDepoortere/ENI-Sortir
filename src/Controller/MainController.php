<?php

namespace App\Controller;


use App\Entity\Campus;
use Doctrine\ORM\EntityManager;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ParticipantRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(SortieRepository $repo,EntityManagerInterface $entityManager): Response
    {
        $infoList = $entityManager->getRepository(Campus::class)->findAll();

        $listeSorties = $repo->findAll();

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController','liste_sorties' => $listeSorties,'infoList' => $infoList,
        ]);
    }
    

}
