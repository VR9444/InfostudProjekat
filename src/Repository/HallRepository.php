<?php

namespace App\Repository;

use App\Entity\Hall;
use App\Entity\Reservations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Node\Expr\Array_;

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
    public function findAllFreeHalls($DateTimeFrom, $DateTimeTo)
    {



        /*$entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            "
        SELECT h.*, from (
            SELECT h.id as id,h.name as name,h.about as about,h.number_of_seats as numOfSeats, count(*) as cx
            FROM App\Entity\Hall as h
            left join reservations as reservation
            on r.hall_id = h.id
            WHERE not (( reservation.date_time_from >= :DateTimeFrom AND :DateTimeTo BETWEEN reservation.date_time_to and reservation.date_time_to)
            OR ( :DateTimeFrom BETWEEN reservation.date_time_from and reservation.date_time_to and :DateTimeTo >= reservation.date_time_to)
            OR ( :DateTimeFrom >= reservation.date_time_from and :DateTimeTo <= reservation.date_time_to)
            OR ( :DateTimeFrom <= reservation.date_time_from and :DateTimeTo >= reservation.date_time_to))
            OR (reservation.date_time_from is null AND reservation.date_time_to is null)
            group by h.id
            ORDER by h.id asc) as x
        LEFT join (SELECT h.id as a, count(*) as cy
        FROM App\Entity\Hall as h
        left join reservations as reservation
        on reservation.hall_id = h.id
        group by h.id
        ORDER by h.id asc) as y
        on x.a = y.a
        WHERE x.cx = y.cy
        ;"
        )->setParameter('DateTimeFrom', $DateTimeFrom)
        ->setParameter('DateTimeTo', $DateTimeTo);

        dd($query->getResult());

        // returns an array of Product objects
        return $query->getResult();


        /*WHERE not (( reservation.startTime >= ? AND ? BETWEEN reservation.startTime and reservation.endTime)
        OR ( ? BETWEEN reservation.startTime and reservation.endTime and ? >= reservation.endTime)
        OR ( ? >= reservation.startTime and ? <= reservation.endTime)
        OR ( ?<= reservation.startTime and ? >= reservation.endTime))
        OR (reservation.startTime is null AND reservation.endTime is null)
        group by tables.table_id
        ORDER by tables.table_id asc) as x
         */


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
