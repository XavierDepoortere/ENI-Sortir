<?php
namespace App\Services;

use App\Entity\Sortie;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InscriptionSortie extends AbstractController
{           
    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function inscription(Sortie $sortie )
     {
        $user = $this->getUser();
        $nbInscrit = count($sortie->getEstInscrit());
        $nbInscritMax = $sortie->getNbInscriptionsMax();
        $etat = $sortie->getEtats();
        $organisateur = $sortie->getOrganisateur();

        if($nbInscrit < $nbInscritMax && $etat == "Ouverte" && $user !== $organisateur){
            if (!$sortie->getEstInscrit()->contains($user)) {
                $sortie->addEstInscrit($user);
                $this->entityManager->flush();
            }
       }
    }
    public function desistement(Sortie $sortie) {
        $user = $this->getUser();
        $inscrit = $sortie->getEstInscrit();
        $etat = $sortie->getEtats();


        if($sortie->getEstInscrit()->contains($user) && $etat == "Ouverte" || $etat == "Clôturée") {
            $sortie->removeEstInscrit($user);
            $this->entityManager->flush();
        }
    }
}