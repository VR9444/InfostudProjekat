<?php

namespace App\Repository;

use App\Entity\Hall;
use App\Entity\Reservations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Node\Expr\Array_;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Reservations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservations[]    findAll()
 * @method Reservations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservations::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Reservations $entity, bool $flush = true): void
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
    public function remove(Reservations $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }



    public function findNotFreeHalls(\DateTime $DateTimeFrom,\DateTime $DateTimeTo): ?Array
    {
        $qr = $this->createQueryBuilder('r');
        $qr->Select("(h.id) as id");
        $qr->leftJoin("App\Entity\Hall","h", Join::WITH,"h.id = r.Hall")
            ->where(
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
            ->setParameter("DateTimeFrom",$DateTimeFrom)
            ->setParameter("DateTimeTo",$DateTimeTo);


        return $qr->getQuery()->getResult();

    }

}
