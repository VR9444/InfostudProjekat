<?php

namespace App\Repository;

use App\Entity\UserReservationLink;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserReservationLink|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserReservationLink|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserReservationLink[]    findAll()
 * @method UserReservationLink[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserReservationLinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserReservationLink::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(UserReservationLink $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(UserReservationLink $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

     /**
      * @return UserReservationLink[] Returns an array of UserReservationLink objects
      */

    public function getNotFreeUsers( $DateTimeFrom, $DateTimeTo,$users)
    {
        $qr =  $this->createQueryBuilder('l')
            ->select("(u.id) as id","(u.FirstName) as FirstName", "(u.LastName) as LastName","(r.CreatedBy) as CreatedBy","(r.DateTimeFrom) as DateTimeFrom", "(r.DateTimeTo) as DateTimeTo")
            ->leftJoin("App\Entity\User","u", Join::WITH,"l.User = u.id")
            ->leftJoin("App\Entity\Reservations","r", Join::WITH,"r.id = l.Reservations");
            $qr->where(
                $qr->expr()->orX(
                    $qr->expr()->andX(
                        $qr->expr()->gte("r.DateTimeFrom",":DateTimeFrom"),
                        $qr->expr()->between(":DateTimeTo","r.DateTimeFrom", "r.DateTimeTo")
                    ),
                    $qr->expr()->andX(
                        $qr->expr()->between(":DateTimeFrom","r.DateTimeFrom", "r.DateTimeTo"),
                        $qr->expr()->gte(":DateTimeTo","r.DateTimeTo")
                    ),
                    $qr->expr()->andX(
                        $qr->expr()->gte(":DateTimeFrom","r.DateTimeFrom"),
                        $qr->expr()->lte(":DateTimeTo","r.DateTimeTo")
                    ),
                    $qr->expr()->andX(
                        $qr->expr()->lte(":DateTimeFrom","r.DateTimeFrom"),
                        $qr->expr()->gte(":DateTimeTo","r.DateTimeTo")
                    )
                )
            )
                ->andWhere(
                    $qr->expr()->in("u.id",":users")
                )
            ->setParameter("DateTimeFrom",$DateTimeFrom)
            ->setParameter("DateTimeTo",$DateTimeTo)
            ->setParameter("users",$users)
        ;
        return $qr->getQuery()->getResult();
    }


    /*
    public function findOneBySomeField($value): ?UserReservationLink
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
