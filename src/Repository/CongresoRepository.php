<?php

namespace App\Repository;

use App\Entity\Congreso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Congreso|null find($id, $lockMode = null, $lockVersion = null)
 * @method Congreso|null findOneBy(array $criteria, array $orderBy = null)
 * @method Congreso[]    findAll()
 * @method Congreso[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CongresoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Congreso::class);
    }

    // /**
    //  * @return Congreso[] Returns an array of Congreso objects
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
    public function findOneBySomeField($value): ?Congreso
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
