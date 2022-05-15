<?php

namespace App\Controller;

use App\Entity\UserReservationLink;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserReservationLinkController extends AbstractController
{


    /**
     * @Route("/invitations/all", name="app_invitations_all")
     */
    public function getAllInvitations(EntityManagerInterface $entityManager): Response
    {

        $userId = $this->getUser()->getId();
        $date = date('Y-m-d H:i:s');
        $response = [];
            $UserReservationLink = $entityManager->getRepository(UserReservationLink::class)->getJoinedDataWithUserIdForMessages($userId,$date);

            $response["error"] = 'none';
            $response["data"] = $UserReservationLink;


        return $this->json(json_encode($response));
    }

    /**
     * @Route("/invitations/answer", name="app_invitaion_answer")
     */
    public function ivnitationAcceptDecline(EntityManagerInterface $entityManager,Request $request): Response
    {
        $reservationId = $request->request->get('reservationId');
        $userId = $this->getUser()->getId();
        $op = $request->request->get('operation');


        $response = [];
        if($op === 'declined' || $op === 'accepted'){

            $UserReservationLink = $entityManager->getRepository(UserReservationLink::class)->findOneBy(array("User"=>$userId,"Reservations"=>$reservationId));
            $UserReservationLink->setState($op);
            $entityManager->flush($UserReservationLink);
            $response = ["error" => "none"];

        }else{
            $response = ["error" => "Wrong operation!"];
        }

        return $this->json(json_encode($response));
    }



}
