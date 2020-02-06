<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Permet de récuperer les produits plus chers qu'un certain montant
     */
    public function findAllGreaterThanPrice($price): array 
    {
        // SELECT * FROM product WHERE price > 500

        //SELECT id, name, description, slug FROM product p
        $queryBuilder = $this->createQueryBuilder('p')
            ->Where('p.price > :price')
            ->setParameter('price', $price * 10)
            ->orderBy('p.price', 'ASC')
            ->setMaxResults(4)
            ->getQuery();
        return $queryBuilder->getResult();
    }

    /**
     * Permet de récuperer le produit plus cher qu'un certain montant
     */

     public function findOneGreaterThanPrice($price): ?Product
     {
         $queryBuilder = $this->createQueryBuilder('p')
         ->Where('p.price > :price')
         ->setParameter('price', $price * 10)
         ->orderBy('p.price', 'DESC')
         ->getQuery();

         return $queryBuilder->setMaxResults(1)->getOneOrNullResult();
     }

     public function findAll()
     {
         $queryBuilder = $this->createQueryBuilder('p')
            ->innerJoin('p.user', 'u')
            ->addSelect('u')
            ->orderBy('p.id', 'DESC')
            ->getQuery();

            return $queryBuilder->getResult();
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
