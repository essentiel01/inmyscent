<?php

namespace App\Repository;

use App\Entity\NotFound;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method NotFound|null find($id, $lockMode = null, $lockVersion = null)
 * @method NotFound|null findOneBy(array $criteria, array $orderBy = null)
 * @method NotFound[]    findAll()
 * @method NotFound[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotFoundRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, NotFound::class);
    }

    // /**
    //  * @return NotFound[] Returns an array of NotFound objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NotFound
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
