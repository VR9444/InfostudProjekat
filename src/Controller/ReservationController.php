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
     * @Route("/reservation/date", name="app_reservation_date")
     */
    public function getReservationsByDate(EntityManagerInterface $entityManager,Request $request): Response
    {
        $date = $request->request->get("date");

        $reservations = $entityManager->getRepository(Reservations::class)->getAllReservationsByDate($date);
        $return = [];
        if(empty($reservations)){
            $return["error"]= "No Reservations for ".$date;
        }else{
            $return["error"] = "none";
            $return["data"] = $reservations;
        }
        return $this->json(json_encode($return));
    }


    /**
     * @Route("/reservation/create", name="app_reservation_create")
     */
    public function createReservation(EntityManagerInterface $entityManager,Request $request): Response
    {

        $timeFrom = $request->request->get("timeFrom");
        $timeTo = $request->request->get("timeTo");
        $date = $request->request->get("date");
        $hallId = $request->request->get("hallId");
        $users = $request->request->get("users");

        $dateTimeFrom = $date." ".$timeFrom;
        $dateTimeTo = $date." ".$timeTo;
        //
        //CHECK IF RESERVATION IS AVALIABLE BEFORE INSERTING
        //


        $notAvaliablehallsIds = array();

        $notAvaliablehalls = $entityManager->getRepository(Reservations::class)->findNotFreeHalls($dateTimeFrom,$dateTimeTo);

        foreach ($notAvaliablehalls as $i){
            $notAvaliablehallsIds[] = (int) $i["id"];
        }
        $arrayHallId = [$hallId];


        $isAvaliable = array_intersect($arrayHallId , $notAvaliablehallsIds);

        if(!empty($isAvaliable)){
            return $this->json(json_encode(array("error"=>"Hall is not avaliable!")));
        }



        //
        // IF HALL IS AVALIABLE PROCEED WITH INSERT
        //


        $hall = $entityManager->getRepository(Hall::class)->find($hallId);


        $reservation = new Reservations();
        $reservation->setCreatedBy($this->getUser());
        $reservation->setDateTimeFrom(new \DateTime($dateTimeFrom));
        $reservation->setDateTimeTo(new \DateTime($dateTimeTo));
        $reservation->setHall($hall);

        $entityManager->persist($reservation);


        foreach ($users as $i){
            $user = $entityManager->getRepository(User::class)->find($i);
            $link = new UserReservationLink();
            $link->setUser($user);
            $link->setReservations($reservation);
            $link->setState('waiting');
            $entityManager->persist($link);
        }

        $entityManager->flush();

        return $this->json(json_encode(array("error"=>"none")));
    }


    /**
     * @Route("/reservation/getJoinedByResId", name="app_joined_res_user")
     */
    public function getJoinedByResId(EntityManagerInterface $entityManager,Request $request): Response
    {
        $id = $request->request->get("id");


        $response = [];
        $data = $entityManager->getRepository(UserReservationLink::class)->getJoinedDataWithReservationId($id);
        if($data === null){
            $response = ["error" => "yes"];
        }else{
            $response["error"] = "none";
            $response["data"] = $data;
        }

        return $this->json(json_encode($response));
    }


    /**
     * @Route("/reservation/delete", name="app_reservation_delete")
     */
    public function deleteReservation(EntityManagerInterface $entityManager,Request $request): Response
    {

       $resId = $request->request->get("resId");

        $entityManager->getRepository(UserReservationLink::class)->deleteUserResLinkWithResId($resId);
        $entityManager->getRepository(Reservations::class)->deleteReservationById($resId);

        $entityManager->flush();


        return $this->json(json_encode(array("error"=>"none")));
    }

    /**
     * @Route("/reservation/delete/user", name="app_delete_user_from_link")
     */
    public function deleteUserFromReservation(EntityManagerInterface $entityManager,Request $request): Response
    {
        $reservationId = $request->request->get('reservationId');
        $userId = $request->request->get('userId');

        $response = [];

            $UserReservationLink = $entityManager->getRepository(UserReservationLink::class)->deleteUserResLinkWithResIdAndUserId($userId,$reservationId);
            $entityManager->flush();
            $response = ["error" => "none"];

        return $this->json(json_encode($response));
    }
}
