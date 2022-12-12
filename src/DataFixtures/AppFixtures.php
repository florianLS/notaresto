<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\City;
use App\Entity\User;
use Faker\Generator;
use App\Entity\Review;
use DateTimeImmutable;
use App\Entity\Restaurant;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use App\Repository\RestaurantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{

    /**
     * @var Generator
     */
    private Generator $faker;

    public function __construct(EntityManagerInterface $em)
    {
        $this->faker = Factory::create('fr_FR');
        $this->em = $em;
    }

    public function load(ObjectManager $manager): void
    {
        // $this->addUsers($manager);
        // $this->addRestaurants($manager);
        // $this->addCities($manager);
        // $this->addReviews($manager);
        // $this->addCityToRestau($manager);
    }

    public function addUsers($manager)
    {
        // Création d'une boucle qui va créer 100 Users aléatoires
        for ($i = 1; $i <= 100; $i++) {
            $user = new User();
            $user->setEmail($this->faker->email())
                ->setPassword($this->faker->password(4, 8));
            $manager->persist($user);
        }
        $manager->flush();
    }

    public function addRestaurants($manager)
    {
        // Création d'une boucle qui va créer 100 restaurants aléatoires
        for ($i = 1; $i <= 100; $i++) {
            $restaurant = new Restaurant();
            $restaurant->setName($this->faker->name())
                ->setDescription($this->faker->text(80))
                ->setCreatedAt(new DateTimeImmutable('now'))
                ->setUser($this->em->getRepository(User::class)->find(rand(302, 401)));
            $manager->persist($restaurant);
        }
        $manager->flush();
    }

    public function addCities($manager)
    {
        // Création d'une boucle qui va créer 100 restaurants aléatoires
        for ($i = 1; $i <= 100; $i++) {
            $city = new City();
            $city->setName($this->faker->city())
                ->setZipcode($this->faker->randomNumber(5));
            $manager->persist($city);
        }
        $manager->flush();
    }

    public function addReviews($manager)
    {
        // Création d'une boucle qui va créer 100 reviews aléatoires
        for ($i = 1; $i <= 100; $i++) {
            $review = new Review();
            $review->setMessage($this->faker->text(20))
                ->setRating($this->faker->numberBetween(0, 5))
                ->setCreatedAt(new DateTimeImmutable('now'))
                ->setRestaurant($this->em->getRepository(Restaurant::class)->find(rand(1, 100)))
                ->setUser($this->em->getRepository(User::class)->find(rand(302, 401)));
            $manager->persist($review);
        }
        $manager->flush();
    }

    public function addCityToRestau($manager)
    {
        // Création d'une boucle qui va relier les restaurants à des villes existantes en BDD.
        $restaurants = $this->em->getRepository(Restaurant::class)->findAll();
        foreach ($restaurants as $restaurant) {
            $restaurant->setCity($this->em->getRepository(City::class)->find(rand(151, 250)));
            $manager->persist($restaurant);
        }
        $manager->flush();
    }
}
