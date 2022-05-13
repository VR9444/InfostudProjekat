<?php

namespace App\Controller;

use App\Entity\Hall;
use App\Entity\Reservations;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HallController extends AbstractController
{
    /**
     * @Route("/hall/find", name="app_hall")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {

        $from = new \DateTime("2022-12-5 10:46:00");
        $to =  new \DateTime("2022-12-5 18:47:00");

        $notAvaliablehallsIds = array();
        $allHallsIds = array();


        $notAvaliablehalls = $entityManager->getRepository(Reservations::class)->findNotFreeHalls($from,$to);
        $allHalls = $entityManager->getRepository(Hall::class)->getAllHallIds();

        foreach ($allHalls as $i){
            $allHallsIds[] = $i["id"];
        }
        foreach ($notAvaliablehalls as $i){
            $notAvaliablehallsIds[] = (int) $i["id"];
        }


        $avaliableHallsIds = array_diff($allHallsIds , $notAvaliablehallsIds);

        $avaliableHalls = $entityManager->getRepository(Hall::class)->findBy(array('id'=>$avaliableHallsIds));
        dd($avaliableHalls);


        return $this->render('hall/index.html.twig', [
            'avaliableHalls' => $avaliableHalls
        ]);
    }
}
