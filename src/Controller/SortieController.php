<?php

namespace App\Controller;


use App\Entity\Etat;
use App\Entity\Sortie;
use App\Form\SortieType;
use App\Entity\Participant;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\SortieAnnulationType;

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
    public function detailSortie(Sortie $sortie, SortieRepository $sortieRepository, ): Response
    {



        $lieu = $sortie->getLieu();
        $ville = $lieu->getVille();


        return $this->render('sortie/detailSortie.html.twig', [
            'sortie' => $sortie,
            'ville' => $ville,


        ]);
    }

    #[Route('/sortie/modifsortie/{id}', name: 'app_sortie_modif')]
        public function editSortie(Sortie $sortie, EntityManagerInterface $entityManager, Request $request, SortieRepository $sortieRepository)
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


    }
