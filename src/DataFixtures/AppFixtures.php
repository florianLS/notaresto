<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\City;
use Faker\Generator;
use DateTimeImmutable;
use App\Entity\Restaurant;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{

    /**
     * @var Generator
     */
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
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
        $manager->flush();
    }
}
