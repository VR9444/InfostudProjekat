<?php

namespace App\Controller;


use App\Entity\Ad;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdController extends AbstractController
{
    /**
     * @Route("/ads/create", name="ad_create")
     */
    public function create(EntityManagerInterface $entityManager): Response
    {
        //$this->denyAccessUnlessGranted('ROLE_USER');
        $ad = new Ad();
        $ad->setTitle('Some title');
        $ad->setDescription('Ovo jhe prvi oglas');
        $ad->setImage('https://api.lorem.space/image/pizza?w=250&h=250');
        $ad->setPrice(random_int(10000,90000));
        $user = $this->getUser();
        $ad->setUser($user);

        $entityManager->persist($ad);
        $entityManager->flush($ad);

        return $this->render('ad/index.html.twig', [
            'ad' => $ad,
        ]);
    }

    /**
     * @Route("/ads/all", name="ad_showAll")
     */
    public function showAll(EntityManagerInterface $entityManager): Response
    {
        $ad = $entityManager->getRepository(Ad::class)->findAll();


        return $this->render('ad/showAll.html.twig', [
            'ad' => $ad,
        ]);
    }


    /**
     * @Route("/ads/delete/{id}", name="ad_delete")
     */
    public function delete(EntityManagerInterface $entityManager,int $id): Response
    {
        $ad = $entityManager->getRepository(Ad::class)->find($id);
        $entityManager->remove($ad);

        $entityManager->flush();

        return $this->redirectToRoute('ad_showAll');
    }
    /**
     * @Route("/ads/change/{id}", name="ad_change")
     */
    public function change(EntityManagerInterface $entityManager,int $id): Response
    {
        $ad = $entityManager->getRepository(Ad::class)->find($id);
        $ad->setPrice(0);

        $entityManager->flush();

        dd($ad);

        return $this->render('ad/show.html.twig', [
            'ad' => $ad,
        ]);
    }

    /**
     * @Route("/ads/{id}", name="ad_show")
     */
    public function show(EntityManagerInterface $entityManager,int $id): Response
    {
        $ad = $entityManager->getRepository(Ad::class)->find($id);


        return $this->render('ad/show.html.twig', [
            'ad' => $ad,
        ]);
    }



}
