<?php

namespace App\Repository;

use App\Entity\Inscripcion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Inscripcion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Inscripcion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Inscripcion[]    findAll()
 * @method Inscripcion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InscripcionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inscripcion::class);
    }

    // /**
    //  * @return Inscripcion[] Returns an array of Inscripcion objects
    //  */

    public function usuarioEnTipoEvento($userId, $eventoTipoId)
    {
        return $this->createQueryBuilder('i')
            ->join('i.evento', 'e')
            ->join('i.user', 'u')
            ->join('e.eventoTipo', 'et')
            ->where('et.id = :eventoTipoId')
            ->andWhere('u.id = :user')
            ->setParameter('eventoTipoId', $eventoTipoId)
            ->setParameter('user', $userId)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Inscripcion
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
