<?php

namespace App\Repository;

use App\Entity\Asociacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Asociacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Asociacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Asociacion[]    findAll()
 * @method Asociacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AsociacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Asociacion::class);
    }

    // /**
    //  * @return Asociacion[] Returns an array of Asociacion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Asociacion
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
