<?php

namespace App\Repository;

use App\Entity\Factura;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Factura|null find($id, $lockMode = null, $lockVersion = null)
 * @method Factura|null findOneBy(array $criteria, array $orderBy = null)
 * @method Factura[]    findAll()
 * @method Factura[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FacturaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Factura::class);
    }

    public function findLastNumero($serie)
    {
        $qb = $this->createQueryBuilder('f');
        $qb->select('f.numero_factura as numero')
        ->where('f.serie = ?1')
        ->orderBy('f.id','DESC')->setMaxResults(1)->setParameter(1,$serie);
        return $qb->getQuery()->getOneOrNullResult();
    }


    public function findLastNumeroEmpresa($serie, $empresa)
    {
      $qb = $this->createQueryBuilder('f');
      $qb->select('f.numero_factura as numero')
      ->where('f.serie = ?1 AND f.empresa = ?2')
      ->orderBy('f.id','DESC')->setMaxResults(1)->setParameter(1,$serie)->setParameter(2,$empresa);
      return $qb->getQuery()->getOneOrNullResult();
    }
    // /**
    //  * @return Factura[] Returns an array of Factura objects
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
    public function findOneBySomeField($value): ?Factura
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
