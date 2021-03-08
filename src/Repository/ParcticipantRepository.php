<?php

namespace App\Repository;

use App\Entity\Parcticipant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Parcticipant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Parcticipant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Parcticipant[]    findAll()
 * @method Parcticipant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParcticipantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Parcticipant::class);
    }

    // /**
    //  * @return Parcticipant[] Returns an array of Parcticipant objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Parcticipant
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

}
