<?php

namespace App\Controller;

use App\Entity\Hall;
use App\Entity\User;
use App\Entity\UserReservationLink;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/superUser", name="app_user_page")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {

        $allHalls = $entityManager->getRepository(Hall::class)->findAll();
        $date = date('Y-m-d');



        return $this->render('user/index.html.twig',[
            "allHalls"=>$allHalls,
            "date"=>$date
        ]);
    }



    /**
     * @Route("/users/all", name="app_user")
     */
    public function getAllUsers(EntityManagerInterface $entityManager): Response
    {
       $users = $entityManager->getRepository(User::class)->findAll();

        foreach ($users as $user){

            $output[]=array($user->getId(),$user->getFirstName(),$user->getLastName());
        }


        return $this->json(json_encode($output));
    }
}
