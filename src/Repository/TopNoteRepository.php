<?php

namespace App\Repository;

use App\Entity\TopNote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TopNote|null find($id, $lockMode = null, $lockVersion = null)
 * @method TopNote|null findOneBy(array $criteria, array $orderBy = null)
 * @method TopNote[]    findAll()
 * @method TopNote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TopNoteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TopNote::class);
    }

    // /**
    //  * @return TopNote[] Returns an array of TopNote objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TopNote
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
