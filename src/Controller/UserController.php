<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/users/all", name="app_user")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
       $users = $entityManager->getRepository(User::class)->findAll();

        foreach ($users as $user){

            $output[]=array($user->getId(),$user->getFirstName(),$user->getLastName());
        }


        return $this->json(json_encode($output));
    }
}
