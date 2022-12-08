<?php

namespace App\Controller;

use App\Entity\Restaurant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(EntityManagerInterface $em): Response
    {

        $restaurants = $em->getRepository(Restaurant::class)->findAll();
        $youngestRestaurants = $em->getRepository(Restaurant::class)->findTenLastRegisterRestaurants();
        $topTenBestRatingRestaurants = $em->getRepository(Restaurant::class)->findTenBestRatingRestaurants();

        return $this->render('home/index.html.twig', [
            'restaurants' => $restaurants,
            'youngestRestaurants' => $youngestRestaurants,
            'topTen' => $topTenBestRatingRestaurants
        ]);
    }
}
