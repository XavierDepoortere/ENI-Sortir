<?php

namespace App\Services;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GestionEtatSortie extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, Sortie $sortie, Etat $etat)
    {
        $this->entityManager = $entityManager;


    }


    public function gestionEtat(SortieRepository $sortieRepository, Sortie $sortie, EntityManagerInterface $entityManager)
    {
        $searchEtatOuvert = $sortieRepository->searchByState('Ouverte');
        $searchEtatCloturee = $sortieRepository->searchByState('Cloturée');
        if ($searchEtatOuvert != null) {
            foreach ($searchEtatOuvert as $sortie) {

                $nbInscrit = count($sortie->getEstInscrit());
                $nbInscritMax = $sortie->getNbInscriptionsMax();
                $currentDate = \DateTime::createFromFormat('d-m-Y H:i', date('d-m-Y H:i'));
                $dateFin = $sortie->getDateLimiteInscription();



                if ($nbInscrit == $nbInscritMax || $currentDate > $dateFin) {

                    $nouvelEtat = $entityManager->getRepository(Etat::class)->findOneBy(['libelle' => 'Clôturée']);
                    if ($nouvelEtat) {
                        $sortie->setEtats($nouvelEtat);
                        $entityManager->flush();

                    }
                }
            }
        }
        if($searchEtatCloturee != null){
            foreach ($searchEtatCloturee as $sortie){
                $nbInscrit = count($sortie->getEstInscrit());
                $nbInscritMax = $sortie->getNbInscriptionsMax();

                if ($nbInscrit < $nbInscritMax){
                    $nouvelEtat = $entityManager->getRepository(Etat::class)->findOneBy(['libelle' => 'Ouverte']);
                    if ($nouvelEtat) {
                        $sortie->setEtats($nouvelEtat);
                        $entityManager->flush();

                    }
                }
            }
        }
    }


}