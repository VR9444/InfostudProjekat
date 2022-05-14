<?php

namespace App\Controller;

use App\Entity\Hall;
use App\Entity\Reservations;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
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
        $timeFrom = $request->request->get("timeFrom");
        $timeTo = $request->request->get("timeTo");
        $date = $request->request->get("date");

        if($timeTo == null || $timeFrom == null || $date == null){
            $timeFrom =  date("H:i:s");
            $timeTo = date("H:i:s");
            $date = date("Y-m-d");
        }

        $numberOfSeats = $request->request->get("numberOfSeats");

        $dateTimeFrom = $date." ".$timeFrom;
        $dateTimeTo = $date." ".$timeTo;


        $notAvaliablehallsIds = array();
        $allHallsIds = array();


        $notAvaliablehalls = $entityManager->getRepository(Reservations::class)->findNotFreeHalls($dateTimeFrom,$dateTimeTo);
        $allHalls = $entityManager->getRepository(Hall::class)->getAllHallIds();

        foreach ($allHalls as $i){
            $allHallsIds[] = $i["id"];
        }
        foreach ($notAvaliablehalls as $i){
            $notAvaliablehallsIds[] = (int) $i["id"];
        }


        $avaliableHallsIds = array_diff($allHallsIds , $notAvaliablehallsIds);

        $avaliableHalls = $entityManager->getRepository(Hall::class)->findBy(array('id'=>$avaliableHallsIds));
        $allUsers = $entityManager->getRepository(User::class)->findAll();

        return $this->render('hall/index.html.twig', [
            'avaliableHalls' => $avaliableHalls,
            'users' => $allUsers,
            "timeFrom"=> $timeFrom,
            "timeTo"=> $timeTo,
            "date"=>$date
        ]);
    }
}
