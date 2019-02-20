<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Product::class);
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
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

    /**
     * retourne la liste de tous les produits de marque $name
     *
     * @param String $name
     * @return void
     */
    public function findByBrand(String $name)
    {
        return $this->createQueryBuilder('p')
            ->join('p.brand', 'b')
            ->where('b.name = :name')
            ->setParameter('name', $name)
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * trouve tous les produits qui ont le nom $name et qui sont de la marque $brand
     *
     * @param String $name le nom du produit
     * @param String $brand le nom de la marque
     * @return void
     */
    public function findByNameAndBrand(String $name, String $brand)
    {
        return $this->createQueryBuilder('p')
            ->join('p.brand', 'b')
            ->where('p.name = :name')
            ->andWhere('b.name = :brand')
            ->setParameters(['name'=> $name, 'brand' => $brand])
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


    public function findByFamilyNoteAndBrand(String $familyNote, String $brand)
    {
        return $this->createQueryBuilder('p')
            ->join('p.brand', 'b')
            ->where('p.familyNotes = :familyNote')
            ->andWhere('b.name = :brand')
            ->setParameters(['familyNote'=> $familyNote, 'brand' => $brand])
            ->orderBy('p.familyNotes', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }



    /*
    public function findOneBySomeField($value): ?Product
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
