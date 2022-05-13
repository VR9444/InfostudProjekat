<?php

namespace App\Controller;

use App\Entity\Hall;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="app_admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    /**
     * @Route("/admin/hall/create", name="app_admin_hall_create")
     */
    public function createHall(EntityManagerInterface $entityManager): Response
    {
        $hall = new Hall();
        $hall->setName("Third Hall");
        $hall->setAbout("TRECA SAALA NA SAJTU");
        $hall->setNumberOfSeats(10);

        $entityManager->persist($hall);
        $entityManager->flush($hall);

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }


}
