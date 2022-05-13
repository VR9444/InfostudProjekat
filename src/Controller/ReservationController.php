<?php

namespace App\Controller;

use App\Entity\Hall;
use App\Entity\Reservations;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;

class ReservationController extends AbstractController
{
    /**
     * @Route("/reservation/all", name="app_reservation_all")
     */
    public function index(): Response
    {
        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
        ]);
    }

    /**
     * @Route("/reservation/create", name="app_reservation_create")
     */
    public function createReservation(EntityManagerInterface $entityManager): Response
    {
//        $from = DateTime::createFromFormat("2022-12-5 10:45:00", 'Y-m-d H:i:s');
//        $to = DateTime::createFromFormat("2022-12-5 12:45:00", 'Y-m-d H:i:s');
        $dateTimeFrom = date("2022-12-5 10:45:00");
        $dateTimeTo = date("2022-12-5 12:45:00");
        //$category->setCreatedTs(new \DateTime())
        $hall = $entityManager->getRepository(Hall::class)->find(1);


        $reservation = new Reservations();
        $reservation->setCreatedBy($this->getUser());
        $reservation->setDateTimeFrom(new \DateTime("2022-12-5 10:45:00"));
        $reservation->setDateTimeTo(new \DateTime("2022-12-5 12:45:00"));
        $reservation->setHall($hall);

        $entityManager->persist($reservation);
        $entityManager->flush($reservation);

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}
