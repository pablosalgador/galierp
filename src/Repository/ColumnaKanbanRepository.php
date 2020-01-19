<?php

namespace App\Repository;

use App\Entity\ColumnaKanban;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ColumnaKanban|null find($id, $lockMode = null, $lockVersion = null)
 * @method ColumnaKanban|null findOneBy(array $criteria, array $orderBy = null)
 * @method ColumnaKanban[]    findAll()
 * @method ColumnaKanban[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ColumnaKanbanRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ColumnaKanban::class);
    }

    public function findAll()
    {
      return $this->findBy(array(), array('posicion' => 'ASC'));
    }

    public function findLastPosicion()
    {
        $qb = $this->createQueryBuilder('c');
        $qb->select('MAX(c.posicion) as posicion');  
        return $qb->getQuery()->getSingleResult();
    }

    // /**
    //  * @return ColumnaKanban[] Returns an array of ColumnaKanban objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ColumnaKanban
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
