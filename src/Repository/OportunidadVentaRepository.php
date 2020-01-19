<?php

namespace App\Repository;

use App\Entity\OportunidadVenta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method OportunidadVenta|null find($id, $lockMode = null, $lockVersion = null)
 * @method OportunidadVenta|null findOneBy(array $criteria, array $orderBy = null)
 * @method OportunidadVenta[]    findAll()
 * @method OportunidadVenta[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OportunidadVentaRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, OportunidadVenta::class);
    }

    public function findAll()
    {
      return $this->findBy(array(), array('fecha_creacion' => 'DESC'));
    }

    public function findByGanada()
    {
      return $this->findBy(array("ganada"=>true),array('fecha_creacion'=>'DESC'));
    }

    public function findByPerdida()
    {
      return $this->findBy(array("perdida"=>true),array('fecha_creacion'=>'DESC'));
    }

    public function findOnKanban()
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT p
            FROM App\Entity\OportunidadVenta p
            WHERE p.columna_kanban IS NOT NULL'
        );
        return $query->execute();
    }
    // /**
    //  * @return OportunidadVenta[] Returns an array of OportunidadVenta objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OportunidadVenta
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
