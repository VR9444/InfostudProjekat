<?php

namespace App\Controller;

use App\Entity\Hall;
use App\Entity\Reservations;
use App\Entity\User;
use App\Entity\UserReservationLink;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HallController extends AbstractController
{
    /**
     * @Route("/hall/find", name="app_hall_find")
     */
    public function index(EntityManagerInterface $entityManager, \Symfony\Component\HttpFoundation\Request $request): Response
    {
        $timeFrom = $request->query->get("timeFrom");
        $timeTo = $request->query->get("timeTo");
        $date = $request->query->get("date");
        $numberOfSeats = $request->query->get("numberOfSeats");

        if($timeTo == null || $timeFrom == null || $date == null){
            $timeFrom =  date("H:i");
            $timeTo = date("H:i");
            $date = date("Y-m-d");
        }



        $dateTimeFrom = $date." ".$timeFrom;
        $dateTimeTo = $date." ".$timeTo;


        $notAvaliablehallsIds = array();
        $allHallsIds = array();



        $notAvaliablehalls = $entityManager->getRepository(Reservations::class)->findNotFreeHalls($dateTimeFrom,$dateTimeTo);
        $allHalls = $entityManager->getRepository(Hall::class)->findAll();


        foreach ($allHalls as $i){
            $i = (array) $i;
            $allHallsIds[] = ($i["\x00App\Entity\Hall\x00id"]);
        }
        foreach ($notAvaliablehalls as $i){
            $notAvaliablehallsIds[] = (int) $i["id"];
        }


        $avaliableHallsIds = array_diff($allHallsIds , $notAvaliablehallsIds);

        $avaliableHalls = $entityManager->getRepository(Hall::class)->findFreeHallsOnDate($avaliableHallsIds,$numberOfSeats);
        $allUsers = $entityManager->getRepository(User::class)->findAll();

        $dateFromating = date('Y-m-d');
        $maxDate = date('Y-m-d',strtotime($dateFromating . ' +30 day'));
        $minDate = date('Y-m-d',strtotime($dateFromating . ' -10 day'));
        $mainUserId = $this->getUser()->getId();
        return $this->render('hall/index.html.twig', [
            'avaliableHalls' => $avaliableHalls,
            'users' => $allUsers,
            "timeFrom"=> $timeFrom,
            "timeTo"=> $timeTo,
            "date"=>$date,
            "allHalls"=>$allHalls,
            "minDate"=> $minDate,
            "maxDate"=> $maxDate,
            "mainUserId"=> $mainUserId,
            "numberOfSeats"=>$numberOfSeats
        ]);
    }
}
