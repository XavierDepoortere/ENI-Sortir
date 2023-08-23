<?php

namespace App\Controller;


use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Ville;
use App\Entity\Sortie;
use App\Form\SortieType;
use App\Entity\Participant;
use App\Repository\CampusRepository;
use App\Repository\LieuRepository;
use App\Repository\SortieRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
    public function detailSortie(Sortie $sortie): Response
    {

        $lieu = $sortie->getLieu();
        $ville = $lieu->getVille();


        return $this->render('sortie/detailSortie.html.twig', [
            'sortie' => $sortie,
            'ville' => $ville,


        ]);
    }

    #[Route('/sortie/modifsortie/{id}', name: 'app_sortie_modif')]



        public
        function edit(Sortie $sortie, EntityManagerInterface $entityManager, Request $request)
        {


            $form = $this->createForm(SortieType::class, $sortie);


            $form->handleRequest($request);


            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->flush();

                $this->addFlash('success', 'Sortie mise à jour !');

                return $this->redirectToRoute('app_main');
            }

            return $this->render('sortie/modifSortie.html.twig',

                ['form' => $form->createView(),

                    'sortie' => $sortie,
                ]);

        }

        #[Route('/sortie/delete/{id}', name: 'app_sortie_delete', methods: ['GET'])]



        public
        function delete(Sortie $sortie, EntityManagerInterface $entityManager, Request $request)
        {


            

            return $this->render('sortie/deleteSortie.html.twig',

                [

                    'sortie' => $sortie,
                ]);

        }

    }
