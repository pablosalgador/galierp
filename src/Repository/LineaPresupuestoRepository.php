<?php

namespace App\Repository;

use App\Entity\LineaPresupuesto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LineaPresupuesto|null find($id, $lockMode = null, $lockVersion = null)
 * @method LineaPresupuesto|null findOneBy(array $criteria, array $orderBy = null)
 * @method LineaPresupuesto[]    findAll()
 * @method LineaPresupuesto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LineaPresupuestoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LineaPresupuesto::class);
    }

    // /**
    //  * @return LineaPresupuesto[] Returns an array of LineaPresupuesto objects
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
    public function findOneBySomeField($value): ?LineaPresupuesto
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
