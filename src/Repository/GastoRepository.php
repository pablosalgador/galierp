<?php

namespace App\Repository;

use App\Entity\Gasto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Gasto|null find($id, $lockMode = null, $lockVersion = null)
 * @method Gasto|null findOneBy(array $criteria, array $orderBy = null)
 * @method Gasto[]    findAll()
 * @method Gasto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GastoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Gasto::class);
    }

    // /**
    //  * @return Gasto[] Returns an array of Gasto objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Gasto
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
