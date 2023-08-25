<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Data\SearchData;
use App\Form\SearchForm;
use App\Entity\Participant;
use App\Services\GestionEtatSortie;
use App\Services\InscriptionSortie;
use Doctrine\ORM\EntityManager;

use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main', methods: ['GET'])]
    public function index(Request $request, SortieRepository $sortieRepository, Sortie $sortie, EntityManagerInterface $entityManager): Response
    {

        $this->gestionEtatService->gestionEtat($sortieRepository, $sortie, $entityManager);
        $searchData = new SearchData();
        $user = $this->getUser();
            $searchData->setUser($user);
        $form = $this->createForm(SearchForm::class, $searchData);
        $form->handleRequest($request);
       // if($form->isSubmitted() && $form->isValid()) {
       // }
    $listeSorties = $sortieRepository->findSearch($searchData);
      //dd($searchData);
        return $this->render('main/index.html.twig', [
            'listeSorties' => $listeSorties,
            'form' => $form->createView()
        ]);
    }
   
    // Appel du servie InscriptionSortie
    private InscriptionSortie $inscriptionService;
    private GestionEtatSortie $gestionEtatService;
    public function __construct(InscriptionSortie $inscriptionService, GestionEtatSortie $gestionEtatService)
    {
        $this->inscriptionService = $inscriptionService;
        $this->gestionEtatService = $gestionEtatService;
    }
    #[Route("/inscrire-sortie/{id}", name: 'inscrire_sortie')]
    public function inscrireSortie(Sortie $sortie) :  Response
    {
        $this->inscriptionService->inscription($sortie);
        return $this->redirectToRoute("app_main");

        
    }

    #[Route("/desistement-sortie/{id}", name: 'desistement_sortie')]
    public function desistementSortie(Sortie $sortie) : Response
    {
        $this->inscriptionService->desistement($sortie);
        return $this->redirectToRoute("app_main");


    }





}
