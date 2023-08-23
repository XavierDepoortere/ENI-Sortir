<?php
namespace App\Service;

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

        if($nbInscrit < $nbInscritMax && $etat == "Ouverte" && $user != $organisateur){
            if (!$sortie->getEstInscrit()->contains($user)) {
                $sortie->addEstInscrit($user);
                $this->entityManager->flush();
            }
       }
    }   
}