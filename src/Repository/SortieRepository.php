<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Sortie;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function findSearch(SearchData $search, User $user): array
    {
        $query = $this
            ->createQueryBuilder('p')
            ->select('c', 'p')
            ->leftJoin('p.campus', 'c')
            ->leftJoin('p.participants', 'participants')
            ->leftJoin('p.etat', 'e')
            ->orderBy('e.id, p.dateHeureDebut','ASC');


        if(!empty($search->q)){
            $query = $query
                ->andWhere('p.nom LIKE :q')
                ->setParameter('q', "%{$search->q}%" );
        }

        if(!empty($search->campus)){
            $query = $query
                ->andWhere('c.id IN (:campus)')
                ->setParameter('campus', $search->campus);
        }

        if(!empty($search->min)){
            $query = $query
                ->andWhere('p.dateHeureDebut >= :min')
                ->setParameter('min', $search->min );
        }

        if(!empty($search->max)){
            $query = $query
                ->andWhere('p.dateHeureDebut <= :max')
                ->setParameter('max', $search->max );
        }

        if(!empty($search->isOrga)){
            $query = $query
                ->andWhere('p.organisateur = :user')
                ->setParameter('user', $user);
        }

        if(!empty($search->isInscrit)){
            $query = $query
                ->andWhere('participants IN (:user)')
                ->setParameter('user', $user);
        }
        if(!empty($search->isNotInscrit)){
            $query = $query
                ->andWhere('participants not in (:user) OR participants is null')
                ->setParameter('user', $user);
        }

        if(!empty($search->sortiesPassees)){
            $query = $query
                ->andWhere('p.etat = 5');
        }

        return $query->getQuery()->getResult();
    }
    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    //Jointure pour récupérer participants dans sortie_user dans la base de données
    public function findOneBySomeParticipants($id): paginator
    {
        $qb = $this->createQueryBuilder('s') //Parametre alias de sortie
            ->join('s.participants', 'p') //
            ->andWhere('s.id = :val')
            ->setParameter('val', $id);
        $rs = $qb->getQuery();
        return new paginator($rs);
    }







}


