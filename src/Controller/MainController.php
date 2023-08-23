<?php

namespace App\Controller;


use App\Data\SearchData;
use App\Form\SearchForm;
use App\Entity\Participant;
use App\Repository\SortieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main', methods: ['GET'])]
    public function index(Request $request, SortieRepository $sortieRepository): Response
    {
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
    #[Route('/erreur_404', name: 'app_erreur')]
    public function erreur()
    {
        return $this->render('security/404.html.twig');
    }

}
