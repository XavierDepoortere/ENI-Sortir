<?php

namespace App\Services;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Repository\SortieRepository;
use DateInterval;
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

        $currentDate = \DateTime::createFromFormat('d-m-Y H:i', date('d-m-Y H:i'));
            
        //TODO : passé
        $searchAllData = $sortieRepository->findAll();

        //Historise toutes les sorties de plus d'un mois
        foreach ($searchAllData as $sortie) {
            $dateDebut = $sortie->getDateHeureDebut();
            $duree = $sortie->getDuree();
            $unMois = 43200;
            $nouvelleDuree = $duree + $unMois;
            $dateFinAdd = clone $dateDebut;
            $dateFinAdd->add(new \DateInterval("PT{$nouvelleDuree}M"));
            
           if ($currentDate >= $dateFinAdd){
            
            $nouvelEtat = $entityManager->getRepository(Etat::class)->findOneBy(['libelle' => 'Historisée']);
            $sortie->setEtats($nouvelEtat);
            $entityManager->flush();
           }

        //Toutes les sorties en cours        
            if ($searchEtatOuvert != null) {
                foreach ($searchEtatOuvert as $sortie) {
                    $dateDebut3 = $sortie->getDateHeureDebut();
                    $duree = $sortie->getDuree(); 
                    $dateFinEnCours = clone $dateDebut3;
                    $dateFinEnCours->add(new \DateInterval("PT{$duree}M"));

                    if ($currentDate >= $dateDebut3 && $currentDate <= $dateFinEnCours) {
                        $nouvelEtat = $entityManager->getRepository(Etat::class)->findOneBy(['libelle' => 'Activité en cours']);
                        $sortie->setEtats($nouvelEtat);
                        $entityManager->flush();
                        }

                //Gestion des états sorties clôturées

                        $dateDebut2 = $sortie->getDateHeureDebut();
                        $duree = $sortie->getDuree(); 
                        $dateFin = clone $dateDebut2;
                        $dateFin->add(new \DateInterval("PT{$duree}M"));
                        $nbInscrit = count($sortie->getEstInscrit());
                        $nbInscritMax = $sortie->getNbInscriptionsMax();
                        $dateFin = $sortie->getDateLimiteInscription();

                    if ($nbInscrit == $nbInscritMax || $currentDate > $dateFin) {
                        $nouvelEtat = $entityManager->getRepository(Etat::class)->findOneBy(['libelle' => 'Clôturée']);
                        $sortie->setEtats($nouvelEtat);
                        $entityManager->flush();
                    }
            //Repasser les sorties en Ouverte si désistement

                    if ($searchEtatCloturee != null) {
                        foreach ($searchEtatCloturee as $sortie) {

                            $nbInscrit = count($sortie->getEstInscrit());
                            $nbInscritMax = $sortie->getNbInscriptionsMax();
                            $dateFin = $sortie->getDateLimiteInscription();
                      
                            if ($nbInscrit < $nbInscritMax && $currentDate <= $dateFin) {
                                $nouvelEtat = $entityManager->getRepository(Etat::class)->findOneBy(['libelle' => 'Ouverte']);
                                $sortie->setEtats($nouvelEtat);
                                $entityManager->flush();
                            }
                        }
                    }
                }
            }
        }
    }
}
