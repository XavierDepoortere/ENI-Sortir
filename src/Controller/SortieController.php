<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Ville;
use App\Entity\Sortie;
use App\Form\SortieType;
use App\Entity\Participant;
use App\Form\SortieAnnulationType;
use App\Repository\LieuRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ParticipantRepository;
use App\Repository\VilleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SortieController extends AbstractController
{
    #[Route('/sortie/create', name: 'app_sortie_create')]
    public function create(EntityManagerInterface $entityManager, Request $request, SortieRepository $sortieRepository)
    {
        $user = $this->getUser();
        $sortie = new Sortie();

        if ($user instanceof Participant) {
            $userCampus = $user->getCampus();
            $sortie->setSiteOrganisateur($userCampus);
            $sortie->setOrganisateur($user);
        }
        $form = $this->createForm(SortieType::class, $sortie, ['userCampus' => $userCampus]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('save')->isClicked()) {
                $etatCreee = $entityManager->getRepository(Etat::class)->findOneBy(['libelle' => 'Créée']);
                $sortie->setEtats($etatCreee);
            }
            elseif ($form->get('post')->isClicked()){
                $etatOuverte = $entityManager->getRepository(Etat::class)->findOneBy(['libelle' => 'Ouverte']);
                $sortie->setEtats($etatOuverte);
            }
          
            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash('success', 'Sortie ajoutée ! bien joué!!');
            return $this->redirectToRoute('app_main');
        }
        return $this->render('sortie/createSortie.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/sortie/detail/{id}', name: 'app_sortie_detail')]
    public function detailSortie(Sortie $sortie, SortieRepository $sortieRepository, ParticipantRepository $participantRepository): Response
    {
        $listeparticipant = $participantRepository->findAll();
        
        $lieu = $sortie->getLieu();
        $ville = $lieu->getVille();
        

        return $this->render('sortie/detailSortie.html.twig', [
            'sortie' => $sortie,
            'ville' => $ville,
            'listeparticipant' => $listeparticipant,

        ]);
    }

    #[Route('/sortie/modifsortie/{id}', name: 'app_sortie_modif')]
        public function editSortie(Sortie $sortie,Ville $ville, EntityManagerInterface $entityManager, Request $request)
        {
            $user = $this->getUser();
            $sortie = new Sortie();

            if ($user instanceof Participant) {
                $userCampus = $user->getCampus();
                $sortie->setSiteOrganisateur($userCampus);
                $sortie->setOrganisateur($user);
            }
            $form = $this->createForm(SortieType::class, $sortie, ['userCampus' => $userCampus]);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if ($form->get('save')->isClicked()) {
                    $etatCreee = $entityManager->getRepository(Etat::class)->findOneBy(['libelle' => 'Créée']);
                    $sortie->setEtats($etatCreee);
                }
                elseif ($form->get('post')->isClicked()){
                    $etatOuverte = $entityManager->getRepository(Etat::class)->findOneBy(['libelle' => 'Ouverte']);
                    $sortie->setEtats($etatOuverte);
                }
                $entityManager->flush();
                $this->addFlash('success', 'Sortie ajoutée ! bien joué!!');
                return $this->redirectToRoute('app_main');
            }
            return $this->render('sortie/modifSortie.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        #[Route('/sortie/annuler/{id}', name: 'app_sortie_annuler', methods: ['GET', 'POST'])]
        public function annulerSortie(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
    $sortie = $entityManager->find(Sortie::class, $id);
    if (!$sortie) {
        throw $this->createNotFoundException('Sortie non trouvée');
    }

    $form = $this->createForm(SortieAnnulationType::class, $sortie);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $etatAnnulee = $entityManager->getRepository(Etat::class)->findOneBy(['libelle' => 'Annulée']);
        $sortie->setEtats($etatAnnulee); 
        $entityManager->flush();

        $this->addFlash('success', 'Sortie annulée !');
        return $this->redirectToRoute('app_main');
    }

    return $this->render('sortie/annulerSortie.html.twig', [
        'sortie' => $sortie,
        'annulationForm' => $form->createView(),
        
    ]);
        }
        #[Route('/sortie/publier/{id}', name: 'app_sortie_publier')]
    public function publier(EntityManagerInterface $entityManager, Request $request, Sortie $sortie, SortieRepository $sortieRepository)
    {
        
        // $form = $this->createForm($sortie);
        // $form->handleRequest($request);
        // if ($form->isSubmitted() && $form->isValid()) {
            

            $etat = $entityManager->getRepository(Etat::class)->findOneBy(['libelle' => 'Ouverte']);
            $sortie->setEtats($etat);
            // $entityManager->persist($sortie);
            $entityManager->flush();
            return $this->redirectToRoute('app_main');
        }

        #[Route('/get-rue-by-lieu/{lieu}', name: 'get_rue_by_lieu', methods: ['GET'])]
        public function getRueByLieu(Lieu $lieu ): JsonResponse
        {
    $rue = $lieu->getRue();
    $longitude = $lieu->getLongitude();
    $latitude = $lieu->getLatitude();
   
    
    return new JsonResponse([
        'rue' => $rue,
        'longitude' => $longitude,
        'latitude' => $latitude,
        
    ]);
    }
        #[Route('/get-cp-by-ville/{ville}', name: 'get_cp_by_ville', methods: ['GET'])]
        public function getRueByVille(Ville $ville, LieuRepository $lieuRepository ): JsonResponse
    {
    $lieux = $lieuRepository->findBy(['ville' => $ville]);
    $nom = $ville->getNom();
    $codePostal = $ville->getCodePostal();
    $villeId = $ville->getId();
    $lieuxData = [];
    foreach ($lieux as $lieu) 
    {
        $lieuxData[] = 
        [
            'id' => $lieu->getId(),
            'nom' => $lieu->getNom(),
        ];
    }
    return new JsonResponse(
    [
        'lieu' => $lieuxData,
        'nom' => $nom,
        'codePostal' => $codePostal,
        'villeId' => $villeId,
    ]);
    }
}



        
  
