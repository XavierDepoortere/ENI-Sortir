<?php

namespace App\Controller;


use App\Data\SearchData;
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
    public function index(Request $request, SortieRepository $repo,EntityManagerInterface $entityManager): Response
    {
       $data = new SearchData();
       $form = $this->createForm(SearchData::class, $data);


        //à mettre si besoin
        //$form->handleRequest($request);

        $listeSorties = $repo->findSearch();

        return $this->render('main/index.html.twig', [
            'listeSorties' => $listeSorties,
            'form' => $form->createView()
        ]);
    }
    #[Route('/erreur_404', name: 'app_erreur')]

    public function erreur()
    {
        return $this->render('security/404.html.twig');
    }

}
