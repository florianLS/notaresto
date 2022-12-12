<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\User;
use DateTimeImmutable;
use App\Entity\Restaurant;
use App\Form\RestaurantType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class RestaurantController extends AbstractController
{
    #[Route('/restaurants', name: 'restaurants')]
    public function index(EntityManagerInterface $em): Response
    {
        $restaurants = $em->getRepository(Restaurant::class)->findAll();

        return $this->render('restaurant/index.html.twig', [
            'restaurants' => $restaurants
        ]);
    }

    #[Route('/restaurant/{id}', name: 'restaurant')]
    public function single(EntityManagerInterface $em, $id): Response
    {
        $restaurant = $em->getRepository(Restaurant::class)->findOneById($id);

        return $this->render('restaurant/show.html.twig', [
            'theRestaurant' => $restaurant
        ]);
    }

    #[Route('/restaurant/creation', name: 'add_restaurant', priority: 10)]
    public function add(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $restaurant = new Restaurant();
        $form = $this->createForm(RestaurantType::class, $restaurant);
        $form->handleRequest($request);
        $notification = null;
        if ($form->isSubmitted() && $form->isValid()) {
            $restaurant->setName($form->get('name')->getData());
            $restaurant->setDescription($form->get('description')->getData());
            $restaurant->setCreatedAt(new DateTimeImmutable);
            if ($this->getUser()) {
                $restaurant->setUser($this->getUser());
            } else {
                $restaurant->setUser($em->getRepository(User::class)->find(rand(302, 401)));
            }
            $poster = $form->get('poster')->getData();
            if ($poster) {
                $originalFilename = pathinfo($poster->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $poster->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $poster->move(
                        $this->getParameter('poster_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    dd($e);
                }
                $restaurant->setPoster($newFilename);
            }
            $em->persist($restaurant);
            $em->flush();
            $notification = "Nouveau restaurant fabriqué !";
        }

        return $this->render('restaurant/add.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }

    #[Route('/restaurant/search', name: 'search_restaurants', priority: 10)]
    public function search(EntityManagerInterface $em, Request $request): Response
    {
        $zipcode = $request->get("zipcode");
        if ($zipcode) {
            $resultCity = $em->getRepository(City::class)->findOneByZipcode($zipcode);
            $result = $resultCity->getRestaurants();

            return $this->render('restaurant/zipcode.html.twig', [
                'restaurants' => $result,
                'zipcode' => $zipcode
            ]);
        } else {
            return $this->redirectToRoute("home");
        }
    }
}
