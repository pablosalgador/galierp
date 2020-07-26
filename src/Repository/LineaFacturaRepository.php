<?php

namespace App\Repository;

use App\Entity\LineaFactura;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LineaFactura|null find($id, $lockMode = null, $lockVersion = null)
 * @method LineaFactura|null findOneBy(array $criteria, array $orderBy = null)
 * @method LineaFactura[]    findAll()
 * @method LineaFactura[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LineaFacturaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LineaFactura::class);
    }

    // /**
    //  * @return LineaFactura[] Returns an array of LineaFactura objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LineaFactura
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
