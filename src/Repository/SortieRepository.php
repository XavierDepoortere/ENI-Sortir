<?php

namespace App\Repository;

use App\Entity\Sortie;
use App\Data\SearchData;
use App\Entity\Participant;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bundle\SecurityBundle\Security;


/**
 * @extends ServiceEntityRepository<Sortie>
 *
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    private $securityHelper;
    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, Sortie::class);
        
    }

//    /**
//     * @return Sortie[] Returns an array of Sortie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Sortie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    /**
     * recupere les sorties en lien avec une recherche
     * @return Sortie[]
     */
    public function findSearch(SearchData $search): array
    {

        
        
        $query = $this->createQueryBuilder('s')
        ->select('c','s')
        ->join('s.siteOrganisateur', 'c');
        

        if(!empty($search->q)) {
            $query
            ->andWhere('s.nom LIKE :q')
            ->setParameter('q', "%{$search->q}%");
        }
        if(!empty($search->siteOrganisateur)) {
            $query
            ->andWhere('s.siteOrganisateur = :siteOrganisateur')
            ->setParameter('siteOrganisateur', $search->siteOrganisateur);
        }

        if(!empty($search->dateMin)){
            $query
            ->andWhere('s.dateHeureDebut >= :dateMin')
            ->setParameter('dateMin', $search->dateMin);
        }
        if(!empty($search->dateMax)){
            $query
            ->andWhere('s.dateHeureDebut <= :dateMax')
            ->setParameter('dateMax', $search->dateMax);
        }

        if ($search->inscrit){
            $query->andWhere(':user MEMBER OF s.estInscrit ')
                ->setParameter('user', $search->user);
        }
        if ($search->nonInscrit){
            $query->andWhere(':user NOT MEMBER OF s.estInscrit ')
                ->setParameter('user', $search->user);
        }


        
        
//        if ($search->inscrit && $search->organisateur && $search->nonInscrit) {
//            $query = $query
//                ->leftJoin('s.estInscrit', 'i')
//                ->leftJoin('s.organisateur', 'o')
//                ->andWhere('i.id IN (:user) OR o.id IN (:user) OR s NOT IN (SELECT s2
//                FROM App\Entity\Sortie s2 JOIN s2.estInscrit i2 WHERE i2 = :user)')
//                ->setParameter('user', $search->user);
//
//        }elseif ($search->organisateur && $search->nonInscrit) {
//            $query = $query
//                ->leftJoin('s.estInscrit', 'i')
//                ->leftJoin('s.organisateur', 'o')
//                ->andWhere('o.id IN (:user) OR s NOT IN (SELECT s2
//                FROM App\Entity\Sortie s2 JOIN s2.estInscrit i2 WHERE i2 = :user)')
//                ->setParameter('user', $search->user);
//
//        }elseif ($search->inscrit && $search->nonInscrit) {
//            $query = $query
//                ->leftJoin('s.estInscrit', 'i')
//                ->andWhere('i.id IN (:user) OR s NOT IN (SELECT s2
//                FROM App\Entity\Sortie s2 JOIN s2.estInscrit i2 WHERE i2 = :user)')
//                ->setParameter('user', $search->user);
//
//        }elseif ($search->inscrit && $search->organisateur) {
//            $query = $query
//                ->leftJoin('s.estInscrit', 'i')
//                ->leftJoin('s.organisateur', 'o')
//                ->andWhere('i.id IN (:user) OR o.id IN (:user)')
//                ->setParameter('user', $search->user);
//
//        } elseif ($search->inscrit) {
//            $query = $query
//                ->leftJoin('s.estInscrit', 'i')
//                ->andWhere('i.id IN (:user)')
//                ->setParameter('user', $search->user);
//        } elseif ($search->organisateur) {
//            $query = $query
//                ->leftJoin('s.organisateur', 'o')
//                ->andWhere('o.id IN (:user)')
//                ->setParameter('user', $search->user);
//        }elseif ($search->nonInscrit) {
//            $query = $query
//                ->andWhere('s NOT IN (SELECT s2 FROM App\Entity\Sortie s2 JOIN s2.estInscrit i WHERE i = :user)')
//                ->setParameter('user', $search->user);
//        }
        if ($search->sortiePassee) {
            $query = $query
            ->andWhere('Date_add(s.dateHeureDebut, s.duree, \'MINUTE\') < CURRENT_TIMESTAMP()');

        }
    
        return $query->getQuery()->getResult();
    }
}
