<?php

namespace App\Repository;

use App\Entity\EventoTipo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method EventoTipo|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventoTipo|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventoTipo[]    findAll()
 * @method EventoTipo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventoTipoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventoTipo::class);
    }

    public static function finByActivoTrue(ServiceEntityRepository $r)
    {
        return $r->createQueryBuilder('e')
            ->where('e.activo = true')
            ->orderBy('e.nombre', 'ASC')
        ;
    }

    // /**
    //  * @return EventoTipo[] Returns an array of EventoTipo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EventoTipo
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
