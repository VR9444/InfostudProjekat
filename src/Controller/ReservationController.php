<?php

namespace App\Controller;

use App\Entity\Hall;
use App\Entity\Reservations;
use App\Entity\User;
use App\Entity\UserReservationLink;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;

class ReservationController extends AbstractController
{
    /**
     * @Route("/reservation/check", name="app_reservation_check")
     */
    public function checkIfUsersAreAvaliable(EntityManagerInterface $entityManager,Request $request): Response
    {
        $timeFrom = $request->request->get("timeFrom");
        $timeTo = $request->request->get("timeTo");
        $date = $request->request->get("date");
        $users = $request->request->get("users");

        $dateTimeFrom = $date." ".$timeFrom;
        $dateTimeTo = $date." ".$timeTo;

        $response = [];
        $notFreeUsers = $entityManager->getRepository(UserReservationLink::class)->getNotFreeUsers($dateTimeFrom,$dateTimeTo,$users);
        if($notFreeUsers == null){
            $response = ["error" => "none"];
        }else{
            $response["error"] = "yes";
            $response["data"] = $notFreeUsers;
        }

        return $this->json(json_encode($response));
    }


    /**
     * @Route("/reservation/create", name="app_reservation_create")
     */
    public function createReservation(EntityManagerInterface $entityManager,Request $request): Response
    {
//        $timeFrom = $request->request->get("timeFrom");
//        $timeTo = $request->request->get("timeTo");
//        $date = $request->request->get("date");
//        $hallId = $request->request->get("hallId");
//        $users = $request->request->get("users");

//            dd($timeFrom,$timeTo,$date,$users,$hallId);
//
        $hall = $entityManager->getRepository(Hall::class)->find(1);
        $array = array(1,2);


        $reservation = new Reservations();
        $reservation->setCreatedBy($this->getUser());
        $reservation->setDateTimeFrom(new \DateTime("2022-05-12 17:45:00"));
        $reservation->setDateTimeTo(new \DateTime("2022-05-12 19:45:00"));
        $reservation->setHall($hall);

        $entityManager->persist($reservation);

        foreach ($array as $i){
            $user = $entityManager->getRepository(User::class)->find($i);
            $link = new UserReservationLink();
            $link->setUser($user);
            $link->setReservations($reservation);
            $entityManager->persist($link);
        }

        $entityManager->flush();

        return $this->render("home/home.html.twig");
    }
}
