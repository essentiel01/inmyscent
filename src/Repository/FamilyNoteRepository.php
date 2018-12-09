<?php

namespace App\Repository;

use App\Entity\FamilyNote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FamilyNote|null find($id, $lockMode = null, $lockVersion = null)
 * @method FamilyNote|null findOneBy(array $criteria, array $orderBy = null)
 * @method FamilyNote[]    findAll()
 * @method FamilyNote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FamilyNoteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FamilyNote::class);
    }

    // /**
    //  * @return FamilyNote[] Returns an array of FamilyNote objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FamilyNote
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
