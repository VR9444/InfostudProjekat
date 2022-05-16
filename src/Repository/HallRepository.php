<?php

namespace App\Repository;

use App\Entity\Hall;
use App\Entity\Reservations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Node\Expr\Array_;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Hall|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hall|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hall[]    findAll()
 * @method Hall[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HallRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hall::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Hall $entity, bool $flush = true): void
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
    public function remove(Hall $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return Hall[]
     */
    public function getAllHallIds(): ?Array
    {
        return $this->createQueryBuilder('h')
            ->select("h.id")
            ->getQuery()
            ->getResult();
    }



    /**
     * @return Reservations[] Returns an array of Reservations objects
     */
    public function findFreeHallsOnDate(array $ids, int $numberOfSeats): array
    {
        $query = $this->createQueryBuilder('h')
            ->select("h.id","h.About","h.Name","h.numberOfSeats");
            $query->where(
                $query->expr()->andX(
                    $query->expr()->in("h.id",":ids"),
                    $query->expr()->gte("h.numberOfSeats",":numberOfSeats")
                )
            )
                ->orderBy("h.numberOfSeats","ASC")
                ->setParameter('ids', $ids)
                ->setParameter('numberOfSeats', $numberOfSeats);


        return $query->getQuery()->getResult();

    }


    /**
     * @return Reservations[] Returns an array of Reservations objects
     */
    public function findHallsBySize(int $numberOfSeats): array
    {
        $query = $this->createQueryBuilder('h')
            ->select("h.id");
        $query->where(
                $query->expr()->gte("h.numberOfSeats",":numberOfSeats")
        )
            ->orderBy("h.numberOfSeats","ASC")
            ->setParameter('numberOfSeats', $numberOfSeats);


        return $query->getQuery()->getResult();

    }

    /*
     $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p
            FROM App\Entity\Product p
            WHERE p.price > :price
            ORDER BY p.price ASC'
        )->setParameter('price', $price);

        // returns an array of Product objects
        return $query->getResult();



    $stmt= $this->connect()->prepare("
        SELECT * from (
            SELECT tables.table_id as a,tables.position_x as position_x,tables.position_y as position_y,tables.about_table as about_table,tables.numOfSeats as numOfSeats,tables.size as size,tables.rotate as rotate, count(*) as cx
            FROM tables
            left join reservation
            on reservation.table_id = tables.table_id
            WHERE not (( reservation.startTime >= ? AND ? BETWEEN reservation.startTime and reservation.endTime)
            OR ( ? BETWEEN reservation.startTime and reservation.endTime and ? >= reservation.endTime)
            OR ( ? >= reservation.startTime and ? <= reservation.endTime)
            OR ( ?<= reservation.startTime and ? >= reservation.endTime))
            OR (reservation.startTime is null AND reservation.endTime is null)
            group by tables.table_id
            ORDER by tables.table_id asc) as x
        LEFT join (SELECT tables.table_id as a, count(*) as cy
        FROM tables
        left join reservation
        on reservation.table_id = tables.table_id
        group by tables.table_id
        ORDER by tables.table_id asc) as y
        on x.a = y.a
        WHERE x.cx = y.cy
        ;");
    /*
     */



    // /**
    //  * @return Hall[] Returns an array of Hall objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Hall
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
