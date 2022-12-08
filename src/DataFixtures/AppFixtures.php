<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\City;
use Faker\Generator;
use App\Entity\Review;
use DateTimeImmutable;
use App\Entity\Restaurant;
use Doctrine\Persistence\ObjectManager;
use App\Repository\RestaurantRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{

    /**
     * @var Generator
     */
    private Generator $faker;

    public function __construct(RestaurantRepository $restaurants)
    {
        $this->faker = Factory::create('fr_FR');
        $this->restaurants = $restaurants;
    }

    public function load(ObjectManager $manager): void
    {
        // Création d'une boucle qui va créer 100 restaurants aléatoires
        // for ($i = 1; $i <= 100; $i++) {
        //     $restaurant = new Restaurant();
        //     $restaurant->setName($this->faker->name())
        //         ->setDescription($this->faker->text(80))
        //         ->setCreatedAt(new DateTimeImmutable('now'));
        //     $manager->persist($restaurant);
        // }

        // Création d'une boucle qui va créer 50 villes aléatoires
        // for ($i = 1; $i <= 50; $i++) {
        //     $city = new City();
        //     $city->setName($this->faker->city())
        //         ->setZipcode($this->faker->randomNumber(5));
        //     $manager->persist($city);
        // }

        // Création d'une boucle qui va créer 100 reviews aléatoires
        // for ($i = 1; $i <= 100; $i++) {
        //     $review = new Review();
        //     $review->setMessage($this->faker->text(20))
        //         ->setRating($this->faker->numberBetween(0, 5))
        //         ->setCreatedAt(new DateTimeImmutable('now'))
        //         ->setRestaurant($this->restaurants->find(rand(101, 200)));
        //     $manager->persist($review);
        // }
        $manager->flush();
    }
}
